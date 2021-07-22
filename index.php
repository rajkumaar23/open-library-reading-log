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
            $link = "$domain/books/{$entry->work->cover_edition_key}";
            $authors = $entry->work->author_names;
            $image = "https://covers.openlibrary.org/b/id/" . json_decode(file_get_contents("$link.json"))->covers[0] . "-L.jpg";
            if (!empty($image) && !empty($title) && !empty($link) && !empty($authors)) {
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


