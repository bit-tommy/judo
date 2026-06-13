<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Výdej přílohy akce (PDF / Word). Ve výchozím stavu se soubor nabídne ke
 * stažení pod původním názvem; s `?inline=1` se vydá k zobrazení v prohlížeči
 * (náhled PDF v modálu na veřejné stránce akcí).
 */
class EventAttachmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Event $event): BinaryFileResponse
    {
        $path = $event->attachmentPath();

        abort_unless($path !== null && is_file($path), 404);

        if ($request->boolean('inline')) {
            return response()->file($path)->setContentDisposition('inline', $event->attachment_name);
        }

        return response()->download($path, $event->attachment_name);
    }
}
