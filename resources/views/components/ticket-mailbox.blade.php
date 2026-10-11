@php
    use Illuminate\Support\Facades\DB;

    $gebruiker = auth()->user();

    // Haal de oude sessieberichten op.
    $alleBerichten = session('ticket_mailbox', []);

    if ($gebruiker !== null) {
        // Alleen bestellingen van het ingelogde account.
        $eigenBestellingIds = DB::table('bestellingen')
            ->where('user_id', $gebruiker->id)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $mailboxBerichten = array_values(
            array_filter(
                $alleBerichten,
                fn($bericht) => in_array((int) ($bericht['id'] ?? 0), $eigenBestellingIds, true),
            ),
        );
    } else {
        // Gast: alleen de laatste bevestigde gastbestelling.
        $bevestiging = session('ticket_bevestiging');
        $gastBestellingId = is_array($bevestiging) ? (int) ($bevestiging['id'] ?? 0) : 0;

        $isGastBestelling =
            $gastBestellingId > 0 &&
            DB::table('bestellingen')->where('id', $gastBestellingId)->whereNull('user_id')->exists();

        $mailboxBerichten = $isGastBestelling
            ? array_values(
                array_filter($alleBerichten, fn($bericht) => (int) ($bericht['id'] ?? 0) === $gastBestellingId),
            )
            : [];
    }

    $mailboxBerichten = array_reverse($mailboxBerichten);

    $ongelezenAantal = collect($mailboxBerichten)->where('gelezen', false)->count();
@endphp

<div class="ticket-mailbox" data-ticket-mailbox>
    <button type="button" class="ticket-mailbox-knop" aria-label="Open mailbox" aria-expanded="false" data-mailbox-toggle>
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
            <path d="m22 7-10 7L2 7"></path>
        </svg>

        @if ($ongelezenAantal > 0)
            <span class="ticket-mailbox-badge">
                {{ $ongelezenAantal }}
            </span>
        @endif
    </button>

    <div class="ticket-mailbox-paneel" data-mailbox-panel hidden>
        <div class="ticket-mailbox-kop">
            <strong>MAILBOX</strong>
            <span>{{ count($mailboxBerichten) }} berichten</span>
        </div>

        <div class="ticket-mailbox-lijst">
            @forelse ($mailboxBerichten as $bericht)
                <a href="{{ route('mailbox.show', $bericht['id']) }}"
                    class="ticket-mailbox-bericht {{ !$bericht['gelezen'] ? 'ticket-mailbox-ongelezen' : '' }}">
                    <div class="ticket-mailbox-bericht-kop">
                        <strong>{{ $bericht['onderwerp'] }}</strong>

                        <span>
                            {{ $bericht['gelezen'] ? 'Gelezen' : 'Nieuw' }}
                        </span>
                    </div>

                    <small>
                        Aan: {{ $bericht['bestelling']['email'] }}
                    </small>

                    <p>
                        Hoi {{ $bericht['bestelling']['naam'] }},
                        je bestelling is bevestigd.
                    </p>

                    <div class="ticket-mailbox-samenvatting">
                        <span>Bestelling #{{ $bericht['id'] }}</span>

                        <span>
                            {{ $bericht['bestelling']['dag'] }}
                            {{ $bericht['bestelling']['tijdslot'] }}
                        </span>

                        <span>
                            {{ $bericht['bestelling']['aantal'] }} ticket(s)
                            · €{{ number_format($bericht['bestelling']['totaalprijs'], 2, ',', '.') }}
                        </span>
                    </div>

                    <span class="ticket-mailbox-openen">
                        BEKIJK BERICHT →
                    </span>
                </a>
            @empty
                <div class="ticket-mailbox-leeg">
                    <p>Je mailbox is nog leeg.</p>
                    <small>Na je bestelling verschijnt hier je bevestiging.</small>
                </div>
            @endforelse
        </div>
    </div>
</div>
