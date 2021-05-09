<?php

// sample queries:
//
// ?type=poem&min_lines=5&max_lines=10&num_keywords=2
// ?type=story&min_words=100&max_words=500&num_keywords=1
// giving no arguments defaults to story with min_words=100 and max_words=1000 and num_keywords=1
// ?id=2367234 will return a previously saved prompt with the specified id

require_once("query-sql.php");

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
    $lengths = [25, 50, 100, 150, 200, 250, 300, 350, 400, 450, 500, 750, 1000, 1250, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];
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

    $lengths_filtered = [$min_words, $max_words];

    foreach($lengths as $length) {
        if ($length > $min_words && $length < $max_words && !in_array($length, $lengths_filtered)) {
	    array_push($lengths_filtered, $length);
        }
    }
    return pickRandom($lengths_filtered);
}


function getLines($args) {
    $min_lines = 4;
    $max_lines = 20;
    if (array_key_exists("min_lines", $args))
    {
        $min_lines = $args["min_lines"];
    }
    if (array_key_exists("max_lines", $args))
    {
        $max_lines = $args["max_lines"];
    }
    return rand($min_lines, $max_lines);
}



function getKeywords($args) {
    $num_keywords = 1;
    if (array_key_exists("num_keywords", $args)) {
        $num_keywords = $args["num_keywords"];
    }
    $words = file("words.txt", FILE_IGNORE_NEW_LINES);
    $keywords = [];
    for ($i = 0; $i < $num_keywords; $i ++) {
        $keyword = $words[rand(0, count($words) - 1)];
	$keyword = str_replace("\n", '', $keyword);
	// strip off final 's' if it's a plural:
	if (substr($keyword, -1) == "s" && in_array(substr($keyword, 0, -1), $words)) {
	    $keyword = substr($keyword, 0, -1);
	}
	if (substr($keyword, -2) == "es" && in_array(substr($keyword, 0, -2), $words)) {
	    $keyword = substr($keyword, 0, -2);
	}
        array_push($keywords, $keyword);
    }
    return $keywords;
}

function getLocation($args) {
    $locations = ["Supermarket", "Cinema", "Forest", "City", "Village", "School", "Office", "Palace", "Cellar", "Sewer",
	    "Cathedral", "Farm", "Orchard", "Factory", "Hospital", "Ship"];
    $location = pickRandom($locations);
    return $location;
}

function getCharacter($args) {
    $characters = ["Doctor", "Robber", "Child", "Mother", "Guardian", "Alien", "Warrior", "Prince", "Princess", "Emperor", 
	    "Sailor", "Prisoner", "Comedian", "Actor", "Baby", "Invisible man", "Student", "Teacher"];
    $character = pickRandom($characters);
    return $character;
}

function getEvent($args) {
    $events = ["Wedding", "Birth", "Party", "Celebration", "Funeral", "Disaster", "Discovery", "War", "Plague", "End of the world",
	    "Uprising or revolution", "Publication of a book", "Opening of a zoo", "Closing down sale", "Barmitzvah"];
    $event = pickRandom($events);
    return $event;
}


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
    return $data;
}

function getStoryPrompt($args) {
    $data = [];
    $data["type"] = "story";
    $data["words"] = getWords($args);
    $data["genre"] = getGenre($args);
    $data["keywords"] = getKeywords($args);
    $data["character"] = getCharacter($args);
    $data["location"] = getLocation($args);
    $data["event"] = getEvent($args);
    return $data;
}

function getPrompt($args) {
    if (array_key_exists("type", $args) && $args["type"] == "poem")
        return getPoemPrompt($args);

    // Default to a story prompt:
    return getStoryPrompt($args);
}

$args = $_GET;
if (array_key_exists("id", $args)) {
    $id = $args["id"];
    $prompt = get_prompt_by_id($id);
}
else {
    if (!array_key_exists("type", $args)) {
        $args["type"] = pickRandom(["poem", "story"]);
    }
    $prompt = getPrompt($args);
    $id = store_prompt($args, $prompt);
    $prompt["id"] = $id;
}

print json_encode($prompt);
