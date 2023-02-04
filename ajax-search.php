<?php
include_once 'includes/config.php';

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
{
if(empty($_GET['keyword'])){
    
$noresult = [
      'content' => ''
];
echo json_encode($noresult);
die();
}

$searchresultmv = $APIbaseURL.$searchmovie.$api_key."&query=".urlencode($_GET['keyword']);
$searchresulttv = $APIbaseURL.$searchtv.$api_key."&query=".urlencode($_GET['keyword']);
$arrContextOptions=array(
    "ssl"=>array(
          "verify_peer"=>false,
          "verify_peer_name"=>false,
      ),
  ); 
$ambil = file_get_contents($searchresultmv, false, stream_context_create($arrContextOptions));
$ambiltv = file_get_contents($searchresulttv, false, stream_context_create($arrContextOptions));
$searchresultmv = json_decode($ambil, true);
$searchresulttv = json_decode($ambiltv, true);

function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

$div = '<ul style="margin-bottom: 0;">';
foreach ($searchresultmv["results"] as $searchresultmv) :
$div .= '<li><div class="tpe">movie</div><a href="/movies/'.$searchresultmv["id"].'/'.slugify($searchresultmv["title"]).'" class="ss-title">'.$searchresultmv["title"].'</a></li>';
endforeach;
foreach ($searchresulttv["results"] as $searchresulttv) :
$div .= '<li><div class="tpe">tv</div><a href="/tv/'.$searchresulttv["id"].'/'.slugify($searchresulttv["name"]).'" class="ss-title">'.$searchresulttv["name"].'</a></li>';
endforeach;
$div .= '</ul>';

$result = [
      'content' => $div
];
echo json_encode($result);
}
?>