<?php
declare(strict_types=1);

/**
 * Build trusted iframe URLs for the two configured embed providers.
 * IDs and episode numbers are strictly numeric to prevent URL injection.
 */
function tstream_embed_url(string $provider, string $type, $id, int $season = 1, int $episode = 1, string $subtitle = ''): string {
    $mediaId = trim((string)$id);
    if (!preg_match('/^(?:[0-9]{1,12}|tt[0-9]{5,12})$/i', $mediaId)) {
        throw new InvalidArgumentException('Invalid media identifier.');
    }
    $type = $type === 'tv' || $type === 'series' ? 'series' : 'movie';
    $season = max(1, min(999, $season));
    $episode = max(1, min(9999, $episode));

    if ($provider === 'fmov') {
        $path = $type === 'movie'
            ? 'https://fmov.my/embed/movie/' . rawurlencode($mediaId)
            : 'https://fmov.my/embed/series/' . rawurlencode($mediaId) . '/' . $season . '/' . $episode;
        $query = ['autoplay' => '0', 'title' => '1', 'color' => 'eab308'];
    } else {
        $path = $type === 'movie'
            ? 'https://movietvapi.stream/embed/movie/' . rawurlencode($mediaId)
            : 'https://movietvapi.stream/embed/tv/' . rawurlencode($mediaId) . '/' . $season . '/' . $episode;
        $query = [
            'autoplay' => '0',
            'title' => '1',
            'backdrop' => '1',
            'theme' => 'EAB308',
            'autoNext' => '1',
            'episodeList' => '1'
        ];
        if ($subtitle !== '' && $subtitle !== 'none') $query['subtitles'] = $subtitle;
    }
    return $path . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
}

function tstream_player_switcher(string $primaryUrl, string $alternateUrl, string $title): string {
    return '<div class="tstream-player-switcher" role="group" aria-label="Select video player">' .
        '<button type="button" class="player-source is-active" data-src="' . e($primaryUrl) . '" aria-pressed="true">MovieTV Player</button>' .
        '<button type="button" class="player-source" data-src="' . e($alternateUrl) . '" aria-pressed="false">FMOV Player</button>' .
        '<span class="player-source-note">Choose a playback source</span></div>';
}
