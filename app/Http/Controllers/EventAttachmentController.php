<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Výdej přílohy akce (PDF / Word) ke stažení. Soubor se nabídne ke stažení
 * pod původním názvem, který administrátor nahrál.
 */
class EventAttachmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Event $event): BinaryFileResponse
    {
        $path = $event->attachmentPath();

        abort_unless($path !== null && is_file($path), 404);

        return response()->download($path, $event->attachment_name);
    }
}
