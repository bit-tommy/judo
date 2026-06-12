<?php

namespace App\Models;

use Database\Factories\GalleryAlbumFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Album nahrané přes administraci. Alba ze scraper pipeline (Rajče) žijí
 * v config/content/gallery.php; veřejná galerie obě sady slučuje —
 * toPublicArray() proto musí vracet přesně stejný tvar jako config.
 */
class GalleryAlbum extends Model
{
    /** @use HasFactory<GalleryAlbumFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'date_label',
        'year',
        'cats',
        'photos',
        'cover',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'cats' => 'array',
            'photos' => 'integer',
        ];
    }

    /** Adresář alba na disku. */
    public function dirPath(): string
    {
        return rtrim(config('gallery.media_path'), '/')."/{$this->year}/{$this->slug}";
    }

    /** URL prefix alba (pro album.json a obrázky). */
    public function urlBase(): string
    {
        return rtrim(config('gallery.media_url'), '/')."/{$this->year}/{$this->slug}";
    }

    /**
     * Tvar shodný s položkou v config('content.gallery.albums').
     *
     * @return array{slug: string, title: string, date: string, year: int, cats: array<int, string>, photos: int, videos: int, cover: ?string, data: string}
     */
    public function toPublicArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'date' => $this->date_label,
            'year' => $this->year,
            'cats' => $this->cats ?? [],
            'photos' => $this->photos,
            'videos' => 0,
            'cover' => $this->cover,
            'data' => $this->urlBase().'/album.json',
        ];
    }
}
