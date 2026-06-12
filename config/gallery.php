<?php

/*
|--------------------------------------------------------------------------
| Galerie — infrastrukturní nastavení
|--------------------------------------------------------------------------
| Pozor: data alb (tituly, covery…) žijí v config/content/gallery.php
| (generuje scraper) a v tabulce gallery_albums (alba z administrace).
| Tady je jen umístění médií a parametry zpracování obrázků.
|
| `media_path` je v testech přesměrovaná do tmp adresáře, aby se nikdy
| nezapisovalo do public/.
*/

return [
    'media_path' => env('GALLERY_MEDIA_PATH', public_path('galerie-media')),
    'media_url' => '/galerie-media',

    // Šířky generovaných obrázků (px) — náhled a plná velikost.
    'thumb_width' => 600,
    'max_width' => 2000,
];
