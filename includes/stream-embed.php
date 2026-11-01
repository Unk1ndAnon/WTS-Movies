<?php
declare(strict_types=1);

/**
 * Build iframe URLs only for the two trusted embed hosts.
 * MovieTV: /embed/{id} and /embed/tv/{id}/{season}/{episode}.
 * FMOV: /embed/movie/{id} and /embed/series/{id}/{season}/{episode}.
 */
function tstream_embed_url(string $provider, string $type, $id, int $season = 1, int $episode = 1, string $subtitle = ''): string {
    $mediaId = trim((string)$id);
    if (!preg_match('/^(?:[0-9]{1,12}|tt[0-9]{5,12})$/i', $mediaId)) {
        throw new InvalidArgumentException('Invalid media identifier.');
    }
    if (!in_array($provider, ['movietv', 'fmov'], true)) {
        throw new InvalidArgumentException('Unsupported embed provider.');
    }
    if (!in_array($type, ['movie', 'tv', 'series'], true)) {
        throw new InvalidArgumentException('Unsupported media type.');
    }

    $isMovie = $type === 'movie';
    // TMDB season 0 is Specials; never silently turn it into season 1.
    $season = max(0, min(999, $season));
    $episode = max(1, min(9999, $episode));

    if ($provider === 'fmov') {
        $path = $isMovie
            ? 'https://fmov.my/embed/movie/' . rawurlencode($mediaId)
            : 'https://fmov.my/embed/series/' . rawurlencode($mediaId) . '/' . $season . '/' . $episode;
        $query = ['autoplay' => '0', 'title' => '1', 'color' => 'eab308'];
    } else {
        $path = $isMovie
            ? 'https://movietvapi.stream/embed/' . rawurlencode($mediaId)
            : 'https://movietvapi.stream/embed/tv/' . rawurlencode($mediaId) . '/' . $season . '/' . $episode;
        $query = [
            'autoplay' => '0',
            'title' => '1',
            'backdrop' => '1',
            'theme' => 'EAB308'
        ];
        if (!$isMovie) {
            $query['autoNext'] = '1';
            $query['episodeList'] = '1';
            if ($subtitle !== '' && $subtitle !== 'none') {
                $query['subtitles'] = $subtitle;
            }
        }
    }

    return $path . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
}

function tstream_player_switcher(string $primaryUrl, string $alternateUrl, string $title): string {
    return '<div class="tstream-player-switcher" role="group" aria-label="Select video player">' .
        '<button type="button" class="player-source is-active" data-src="' . e($primaryUrl) . '" aria-pressed="true">MovieTV Player</button>' .
        '<button type="button" class="player-source" data-src="' . e($alternateUrl) . '" aria-pressed="false">FMOV Player</button>' .
        '<span class="player-source-note">Choose a playback source</span></div>';
}
