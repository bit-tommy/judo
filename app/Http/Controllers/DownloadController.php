<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Výdej dokumentů ze stránky „Ke stažení" s počítáním stažení.
 * Soubory se zobrazují inline (chování jako dřívější přímý odkaz na PDF
 * s target="_blank"); externí odkazy přesměrují ven.
 */
class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Document $document): BinaryFileResponse|RedirectResponse
    {
        abort_unless($document->visible, 404);

        $document->increment('downloads');

        if ($document->isExternal()) {
            return redirect()->away($document->url);
        }

        $path = $document->filePath();

        abort_unless($path !== null && is_file($path), 404);

        return response()->file($path);
    }
}
