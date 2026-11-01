<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: private, max-age=60');

$keyword = trim((string)($_GET['keyword'] ?? ''));
if (function_exists('mb_substr')) $keyword = mb_substr($keyword, 0, 80, 'UTF-8');
else $keyword = substr($keyword, 0, 80);

if (strlen($keyword) < 2) {
    echo json_encode(['results'=>[]], JSON_UNESCAPED_UNICODE);
    exit;
}

$movies = tmdb_request('/search/movie', ['query'=>$keyword, 'page'=>1, 'include_adult'=>'false'], 120);
$shows = tmdb_request('/search/tv', ['query'=>$keyword, 'page'=>1], 120);
$results = [];
foreach (array_slice($movies['results'] ?? [], 0, 6) as $item) {
    if (empty($item['id']) || empty($item['title'])) continue;
    $results[] = ['type'=>'movie','id'=>(int)$item['id'],'title'=>(string)$item['title'],'slug'=>slugify($item['title'])];
}
foreach (array_slice($shows['results'] ?? [], 0, 6) as $item) {
    if (empty($item['id']) || empty($item['name'])) continue;
    $results[] = ['type'=>'tv','id'=>(int)$item['id'],'title'=>(string)$item['name'],'slug'=>slugify($item['name'])];
}
echo json_encode(['results'=>$results], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
