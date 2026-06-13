<?php

/*
|--------------------------------------------------------------------------
| Akce — přílohy ke stažení
|--------------------------------------------------------------------------
| Soubory (PDF / Word) přiložené k akci v administraci. `path` je v testech
| přesměrovaná do tmp adresáře, aby se nezapisovalo do public/. Výdej běží
| přes EventAttachmentController (route „events.attachment"), proto adresář
| nekoliduje s veřejnou routou /akce.
*/

return [
    'attachments_path' => env('EVENTS_ATTACHMENTS_PATH', public_path('akce-soubory')),
];
