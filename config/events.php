<?php

/*
|--------------------------------------------------------------------------
| Akce — přílohy ke stažení
|--------------------------------------------------------------------------
| Soubory (PDF / Word) přiložené k akci v administraci. Ukládají se do
| `storage/` (ne do public/), takže přežijí atomické nasazení (release) i
| vyčištění – `storage` bývá sdílený symlink napříč releasy. Výdej běží přes
| EventAttachmentController (route „events.attachment"). V testech je cesta
| přesměrovaná do tmp adresáře, aby se nezapisovalo do skutečného úložiště.
*/

return [
    'attachments_path' => env('EVENTS_ATTACHMENTS_PATH', storage_path('app/akce-soubory')),
];
