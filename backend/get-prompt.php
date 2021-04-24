<?php

function getPoemPrompt($args) {
	$data = [];
	$data["type"] = "poem";
	$data["lines"] = 6;
	$data["rhyme"] = "ababab";
	$data["keyword"] = getKeywords($args);
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
		"Comic fantasy", "Cozy mystery"];
	$genre = pickRandom($genres);
	return $genre;
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

$prompt = getPrompt($_GET);
echo $prompt;
