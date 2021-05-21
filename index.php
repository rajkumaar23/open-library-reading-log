<?php

use voku\helper\HtmlDomParser;

require_once 'vendor/autoload.php';

$domain = "https://openlibrary.org";
$base_uri = "$domain/people/rajkumaar23/books";
$types = ["currently-reading", "want-to-read", "already-read"];

$data = [];
foreach ($types as $type) {
    $dom = HtmlDomParser::file_get_html("$base_uri/$type");
    $elements = $dom->find('.searchResultItem');
    foreach ($elements as $element) {
        $image = "https:" . $element->findOne('img')->getAttribute('src');
        $title = $element->findOne('.booktitle > a')->text();
        $link = $domain . $element->findOne('.booktitle > a')->getAttribute('href');
        $authors = $element->find('span[itemprop="author"] a')->text();
        $data[$type][] = compact('image', 'title', 'link', 'authors');
    }
}

header('Content-Type: application/json');
echo json_encode($data);
file_put_contents(__DIR__ . "/_data/reading-log.json", json_encode($data, JSON_PRETTY_PRINT));

