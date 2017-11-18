<?php

/** Enter the URLs you want to download the videos from **/
$urls = [
    'https://www.instagram.com/p/Ba7e43oFEcG/?hl=en&taken-by=faz3poem',
];

/** Enter the path to the directory where you want to save the videos**/
$store = '/save/to/this/directory';

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
