<?php
function fetchGoogleSearchResults($query, $start = 0, $num = 10) {
    $url = "https://www.google.com/search?q=" . urlencode($query) . "&start={$start}&num={$num}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function extractUrlsFromGoogleResults($html) {
    $urls = array();

    preg_match_all('/<a href="\/url\?q=([^"]+)"[^>]*>/', $html, $matches);

    if (!empty($matches[1])) {
        $urls = $matches[1];
    }

    return $urls;
}

$query = $_GET['palavra_chave'];

$limiteporPagina = 10;

$limitePaginas = 100;

$allUrls = array();
for ($start = 0; $start < $limitePaginas; $start += $limiteporPagina) {
    $html = fetchGoogleSearchResults($query, $start, $limiteporPagina);
    $urls = extractUrlsFromGoogleResults($html);
    $allUrls = array_merge($allUrls, $urls);
}

function UrlValida(string $url): string {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function stringValida($string): bool
{
     return is_string($string) && strlen($string) > 0;
}

if (!empty($allUrls)) {

    foreach ($allUrls as $index => $url) {
        if (UrlValida($url)) {
            $contentSite = fetchWebsiteContent($url);
            $contactInfo = extractContactInfo($contentSite);

            if (
                stringValida($contactInfo['Instagram'])
                || stringValida($contactInfo['Facebook'])
            ) {
                $instagram = stringValida($contactInfo['Instagram']) ? $contactInfo['Instagram'] : '';
                $facebook = stringValida($contactInfo['Facebook']) ? $contactInfo['Facebook'] : '';
                
                echo $index."</br>".
                    " - Intagram :". $instagram ."<br>".
                    " - Facebook :". $facebook."<br>";

                $index++;
            }
        }
    }
} else {
    echo "Nenhum resultado encontrado para '{$query}'.";
}

function fetchWebsiteContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function extractContactInfo($content) {

    $patternInstagram = '/https?:\/\/(?:www\.)?instagram\.com\/([^\s]+)/i';
    $patternFacebook = '/https?:\/\/(?:www\.)?facebook\.com\/([^\s]+)/i';
    preg_match($patternInstagram, $content, $matchesInstagram);
    preg_match($patternFacebook, $content, $matchesFacebook);

    $info = array(
        'Instagram' => isset($matchesInstagram[0]) ? $matchesInstagram[0] : '',
        'Facebook' => isset($matchesFacebook[0]) ? $matchesFacebook[0] : ''
    );

    return $info;
}

?>
