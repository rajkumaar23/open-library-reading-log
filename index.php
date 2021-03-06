<?php

$domain = "https://openlibrary.org";
$base_uri = "$domain/people/rajkumaar23/books";
$types = ["currently-reading", "want-to-read", "already-read"];

try {
    $data = [];
    foreach ($types as $type) {
        $entries = json_decode(file_get_contents("$base_uri/$type.json"))->reading_log_entries;
        foreach ($entries as $entry) {
            $title = $entry->work->title;
            if (empty($entry->logged_edition)) {
                $link = "$domain/books/{$entry->work->cover_edition_key}";
            } else {
                $link = $domain . $entry->logged_edition;
            }
            $authors = $entry->work->author_names;
            echo $title . "\n";
            $cover = json_decode(file_get_contents("$link.json"))->covers[0];
            if (empty($cover)) {
                $link = "$domain/books/{$entry->work->cover_edition_key}";
                $cover = json_decode(file_get_contents("$link.json"))->covers[0];
            }
            $image = "https://covers.openlibrary.org/b/id/$cover-L.jpg";
            if (!empty($cover) && !empty($title) && !empty($link) && !empty($authors)) {
                $data[$type][] = compact('image', 'title', 'link', 'authors');
            } else {
                exit(1);
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    if (!empty($data)) {
        file_put_contents(__DIR__ . "/_data/reading-log.json", json_encode($data, JSON_PRETTY_PRINT));
    }
} catch (Exception $exception) {
    echo $exception->getMessage();
}


