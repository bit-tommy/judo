<?php

/*
|--------------------------------------------------------------------------
| Dokumenty ke stažení
|--------------------------------------------------------------------------
| Soubory spravované administrací (stránka „Ke stažení"). `path` je
| v testech přesměrovaná do tmp adresáře, aby se nezapisovalo do public/.
*/

return [
    'path' => env('DOCUMENTS_PATH', public_path('dokumenty')),
    'url' => '/dokumenty',
];
