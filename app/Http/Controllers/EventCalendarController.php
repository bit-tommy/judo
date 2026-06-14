<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;

/**
 * Výdej akce jako kalendářového souboru .ics (RFC 5545). Událost je celodenní
 * (Event nedrží čas), konec se udává nevčetně (+1 den). Soubor otevře Apple
 * Kalendář, Outlook i ostatní; Google Kalendář má vlastní odkaz na stránce akcí.
 */
class EventCalendarController extends Controller
{
    public function __invoke(Event $event): Response
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//JC Raion-ryu//Akce//CS',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT',
            'UID:event-'.$event->id.'@'.request()->getHost(),
            'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
            'DTSTART;VALUE=DATE:'.$event->starts_on->format('Ymd'),
            'DTEND;VALUE=DATE:'.$event->calendarEndDate()->format('Ymd'),
            'SUMMARY:'.$this->escape($event->title),
        ];

        if ($event->place !== null) {
            $lines[] = 'LOCATION:'.$this->escape($event->place);
        }

        $description = trim(($event->description ?? '')."\n".route('events'));
        $lines[] = 'DESCRIPTION:'.$this->escape($description);

        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        $body = implode("\r\n", $lines)."\r\n";

        return response($body, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.($event->slug ?: 'akce').'.ics"',
        ]);
    }

    /**
     * Escapování textových hodnot dle RFC 5545. Zpětné lomítko se musí nahradit
     * jako první, ať nedojde k dvojímu escapování nově přidaných lomítek.
     */
    private function escape(string $value): string
    {
        return str_replace(
            ['\\', ';', ',', "\r\n", "\n", "\r"],
            ['\\\\', '\\;', '\\,', '\\n', '\\n', '\\n'],
            $value,
        );
    }
}
