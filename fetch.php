<?php
include_once('config.php');
/** Enter the URLs you want to download the videos from **/
$urls = getUrls();
var_dump($urls);
die();


/** **/

foreach ($urls as $key => $url) {

    $vUrl = getVideoUrl($url);

    echo "Detected $vUrl \n";

    if ($vUrl) {
        echo "Downloading...";
        downloadFile($vUrl, $store, $key . "_" . rand()) ;
        echo "Complete.\n";
    }

}

function downloadFile($url, $folder, $filename) {
    return file_put_contents("$folder/$filename.mp4", fopen("$url", 'r'));
}

function getUrls()
{
    $arr = file("urls.txt", FILE_SKIP_EMPTY_LINES);

    return $arr;
}

function getVideoUrl($url)
{
    $x = [];
    $doc = new DOMDocument();
    $doc->loadHTML(file_get_contents($url));
    $xpath = new DOMXPath($doc);
    $raw = (string) $doc->saveHtml();
    $start = 'video_url": "http';
    $end = '"video_view_count"';

    return findText($raw, $start, $end);
}


function findText($string, $start, $end)
{
    $text = "";
    $posStart = strpos($string, $start);
    $posEnd = strpos($string, $end, $posStart);
    if($posStart > 0 && $posEnd > 0)
    {
        $text = substr($string, $posStart + 13, $posEnd - $posStart - 16);
    }
    if ($text) {
        return $text;
    }

    return false;
}
