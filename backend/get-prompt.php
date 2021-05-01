<?php

// sample queries:
//
// ?type=poem&min_lines=5&max_lines=10&num_keywords=2
// ?type=story&min_words=100&max_words=500&num_keywords=1
// giving no arguments defaults to story with min_words=100 and max_words=1000 and num_keywords=1

function getPoemPrompt($args) {
    $data = [];
    $data["type"] = "poem";
    $poemType = getPoemType($args);
    $data["poemType"] = $poemType;

    if ($poemType == "Limerick") {
        $data["lines"] = 4;
    }
    elseif ($poemType == "Villanelle") {
        $data["lines"] = 19;
    }
    elseif($poemType == "Haiku") {
        $data["lines"] = 3;
    }
    else {
        $data["lines"] = getLines($args);
    }
    $data["keywords"] = getKeywords($args);
    return json_encode($data);
}

function getStoryPrompt($args) {
    $data = [];
    $data["type"] = "story";
    $data["words"] = getWords($args);
    $data["genre"] = getGenre($args);
    $data["keywords"] = getKeywords($args);
    return json_encode($data);
}

function getPrompt($args) {
    if (array_key_exists("type", $args) && $args["type"] == "poem")
        return getPoemPrompt($args);

    // Default to a story prompt:
    return getStoryPrompt($args);
}

function pickRandom($items) {
    $item = $items[array_rand($items)];
    return $item;
}

function getGenre($args) {
    // TODO: This should go in a database
    $genres = ["Science fiction", "Horror", "Romance", "Comedy", "Romantic comedy", "Historical fiction",
        "Biography", "Crime caper", "Detective", "Magical realism", "Adventure", "Fantasy",
        "Suspense", "Thriller", "Noir", "Medical drama", "Political thriller", "Cyberpunk", "Steampunk",
        "Alternative history", "Dystopia", "Space opera", "Psychological thriller", "Espionage",
        "Comic fantasy", "Cozy mystery"];
    $genre = pickRandom($genres);
    return $genre;
}

function getPoemType($args) {
    // TODO: This should go in a database
    $types = ["Blank verse", "Free verse", "Narrative poetry", "Haiku", "Pastoral Poetry", "Sonnet", "Elegy",
        "Ode", "Limerick", "Ballad", "Soliloquy", "Villanelle"];
    $type = pickRandom($types);
    return $type;
}

function getWords($args) {
    $min_words = 100;
    $max_words = 1000;
    if (array_key_exists("min_words", $args))
    {
        $min_words = $args["min_words"];
    }
    if (array_key_exists("max_words", $args))
    {
        $max_words = $args["max_words"];
    }
    return rand($min_words, $max_words);
}


function getLines($args) {
    $min_lines = 4;
    $max_lines = 20;
    if (array_key_exists("min_lines", $args))
    {
        $min_words = $args["min_lines"];
    }
    if (array_key_exists("max_lines", $args))
    {
        $max_words = $args["max_lines"];
    }
    return rand($min_lines, $max_lines);
}



function getKeywords($args) {
    $num_keywords = 1;
    if (array_key_exists("num_keywords", $args)) {
        $num_keywords = $args["num_keywords"];
    }
    $words = file("words.txt");
    $keywords = [];
    for ($i = 0; $i < $num_keywords; $i ++) {
        $keyword = $words[rand(0, count($words) - 1)];
        $keyword = str_replace("\n", '', $keyword);
        array_push($keywords, $keyword);
    }
    return $keywords;
}

$args = $_GET;
if (!array_key_exists("type", $args)) {
    $args["type"] = pickRandom(["poem", "story"]);
}

$prompt = getPrompt($args);
print $prompt;