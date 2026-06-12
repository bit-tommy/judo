<?php

namespace App\Support;

use App\Models\GalleryAlbum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Zápis alb nahraných přes administraci do public/galerie-media (resp.
 * config('gallery.media_path')) ve stejné struktuře, jakou generuje scraper
 * pipeline z Rajčete: <rok>/<slug>/{fotky}.jpg + thumb/{fotky}.jpg
 * + album.json ve tvaru {title, date, photos: [{t, f, c}]}.
 *
 * Veřejná galerie pak alba čte beze změny klientského JS. Zpracování
 * obrázků je čisté GD (žádná composer závislost): EXIF orientace,
 * zmenšení na max šířku a JPEG výstup.
 */
class GalleryImporter
{
    /**
     * Založí nové album: adresáře, obrázky, náhledy a album.json.
     *
     * @param  array<int, UploadedFile>  $photos
     * @return array{slug: string, cover: ?string, photos: int}
     */
    public function create(string $title, int $year, string $dateLabel, array $cats, array $photos): array
    {
        $this->assertGdAvailable();

        $slug = $this->uniqueSlug($title, $year);
        $dir = $this->dirPath($year, $slug);

        File::ensureDirectoryExists($dir.'/thumb');

        try {
            $entries = $this->processPhotos($photos, $year, $slug, []);
        } catch (RuntimeException $e) {
            File::deleteDirectory($dir);

            throw $e;
        }

        $this->writeAlbumJson($dir, $title, $dateLabel, $entries);

        return [
            'slug' => $slug,
            'cover' => $entries[0]['t'] ?? null,
            'photos' => count($entries),
        ];
    }

    /**
     * Přidá fotky do existujícího (administrací založeného) alba.
     *
     * @param  array<int, UploadedFile>  $photos
     * @return array{photos: int, cover: ?string}
     */
    public function append(GalleryAlbum $album, array $photos): array
    {
        $this->assertGdAvailable();

        $dir = $album->dirPath();
        File::ensureDirectoryExists($dir.'/thumb');

        $existing = $this->readAlbumJson($dir);
        $entries = array_merge(
            $existing['photos'] ?? [],
            $this->processPhotos($photos, $album->year, $album->slug, $existing['photos'] ?? []),
        );

        $this->writeAlbumJson($dir, $album->title, $album->date_label, $entries);

        return [
            'photos' => count($entries),
            'cover' => $entries[0]['t'] ?? null,
        ];
    }

    /** Přepíše metadata (titul, datum) v album.json po úpravě v adminu. */
    public function rewriteMeta(GalleryAlbum $album): void
    {
        $dir = $album->dirPath();
        $existing = $this->readAlbumJson($dir);

        $this->writeAlbumJson($dir, $album->title, $album->date_label, $existing['photos'] ?? []);
    }

    /** Smaže celý adresář alba z disku. */
    public function delete(GalleryAlbum $album): void
    {
        File::deleteDirectory($album->dirPath());
    }

    /**
     * Zpracuje nahrané soubory → uloží plnou velikost + náhled, vrátí
     * položky pro album.json.
     *
     * @param  array<int, UploadedFile>  $photos
     * @param  array<int, array{t: string, f: string, c: string}>  $existingEntries
     * @return array<int, array{t: string, f: string, c: string}>
     */
    private function processPhotos(array $photos, int $year, string $slug, array $existingEntries): array
    {
        $dir = $this->dirPath($year, $slug);
        $urlBase = rtrim(config('gallery.media_url'), '/')."/{$year}/{$slug}";

        $usedNames = array_map(
            fn (array $entry) => pathinfo((string) parse_url($entry['f'], PHP_URL_PATH), PATHINFO_FILENAME),
            $existingEntries,
        );

        $entries = [];

        foreach ($photos as $photo) {
            $bytes = $photo->get();

            if (! is_string($bytes) || $bytes === '') {
                throw new RuntimeException('Soubor „'.$photo->getClientOriginalName().'" se nepodařilo načíst.');
            }

            $image = @imagecreatefromstring($bytes);

            if ($image === false) {
                throw new RuntimeException('Soubor „'.$photo->getClientOriginalName().'" není platný obrázek (JPG, PNG nebo WEBP).');
            }

            $image = $this->applyExifOrientation($image, $bytes, $photo);
            $image = $this->flattenToTrueColor($image);

            $name = $this->uniquePhotoName($photo->getClientOriginalName(), $usedNames);
            $usedNames[] = $name;

            $full = $this->resizeToMaxWidth($image, (int) config('gallery.max_width', 2000));
            imagejpeg($full, "{$dir}/{$name}.jpg", 82);

            $thumb = $this->resizeToMaxWidth($image, (int) config('gallery.thumb_width', 600));
            imagejpeg($thumb, "{$dir}/thumb/{$name}.jpg", 80);

            if ($full !== $image) {
                imagedestroy($full);
            }
            if ($thumb !== $image) {
                imagedestroy($thumb);
            }
            imagedestroy($image);

            $entries[] = [
                't' => "{$urlBase}/thumb/{$name}.jpg",
                'f' => "{$urlBase}/{$name}.jpg",
                'c' => '',
            ];
        }

        return $entries;
    }

    /** Otočení podle EXIF Orientation (foto z mobilu na výšku apod.). */
    private function applyExifOrientation(\GdImage $image, string $bytes, UploadedFile $photo): \GdImage
    {
        if (! function_exists('exif_read_data') || ! str_starts_with($bytes, "\xFF\xD8")) {
            return $image; // EXIF má smysl jen u JPEG
        }

        $tmp = tempnam(sys_get_temp_dir(), 'rr-exif-');

        try {
            file_put_contents($tmp, $bytes);
            $exif = @exif_read_data($tmp);
        } finally {
            @unlink($tmp);
        }

        $orientation = (int) ($exif['Orientation'] ?? 1);

        if ($orientation <= 1 || $orientation > 8) {
            return $image;
        }

        // 2/4/5/7 zahrnují zrcadlení, 3/6/8 jen rotaci.
        if (in_array($orientation, [2, 4, 5, 7], true)) {
            imageflip($image, IMG_FLIP_HORIZONTAL);
        }

        $angle = match ($orientation) {
            3, 4 => 180,
            5, 6 => -90,
            7, 8 => 90,
            default => 0,
        };

        if ($angle !== 0) {
            $rotated = imagerotate($image, $angle, 0);

            if ($rotated !== false) {
                imagedestroy($image);

                return $rotated;
            }
        }

        return $image;
    }

    /** PNG/WEBP s průhledností položí na bílé pozadí (JPEG alfu neumí). */
    private function flattenToTrueColor(\GdImage $image): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $canvas = imagecreatetruecolor($width, $height);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopy($canvas, $image, 0, 0, 0, 0, $width, $height);
        imagedestroy($image);

        return $canvas;
    }

    /** Zmenšení na maximální šířku (menší obrázky nezvětšujeme). */
    private function resizeToMaxWidth(\GdImage $image, int $maxWidth): \GdImage
    {
        if (imagesx($image) <= $maxWidth) {
            return $image;
        }

        $resized = imagescale($image, $maxWidth, -1, IMG_BICUBIC);

        if ($resized === false) {
            throw new RuntimeException('Zmenšení obrázku se nepodařilo.');
        }

        return $resized;
    }

    private function uniquePhotoName(string $originalName, array $usedNames): string
    {
        $base = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'foto';
        $name = $base;
        $suffix = 2;

        while (in_array($name, $usedNames, true)) {
            $name = $base.'-'.$suffix++;
        }

        return $name;
    }

    private function uniqueSlug(string $title, int $year): string
    {
        $base = Str::slug($title) ?: 'album';
        $slug = $base;
        $suffix = 2;

        while (
            GalleryAlbum::where('slug', $slug)->exists()
            || is_dir($this->dirPath($year, $slug))
            || collect(config('content.gallery.albums', []))->contains(fn ($a) => $a['slug'] === $slug)
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * @return array{title?: string, date?: string, photos?: array<int, array{t: string, f: string, c: string}>}
     */
    private function readAlbumJson(string $dir): array
    {
        $path = $dir.'/album.json';

        if (! is_file($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true) ?: [];
    }

    /** @param array<int, array{t: string, f: string, c: string}> $entries */
    private function writeAlbumJson(string $dir, string $title, string $dateLabel, array $entries): void
    {
        file_put_contents(
            $dir.'/album.json',
            json_encode([
                'title' => $title,
                'date' => $dateLabel,
                'photos' => array_values($entries),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        );
    }

    private function dirPath(int $year, string $slug): string
    {
        return rtrim(config('gallery.media_path'), '/')."/{$year}/{$slug}";
    }

    private function assertGdAvailable(): void
    {
        if (! function_exists('imagecreatefromstring')) {
            throw new RuntimeException('Na serveru chybí PHP rozšíření GD — nahrávání fotek není možné.');
        }
    }
}
