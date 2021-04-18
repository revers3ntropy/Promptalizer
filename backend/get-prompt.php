<?php

function getPoemPrompt($args) {
	$data = [];
	$data["type"] = "poem";
	$data["lines"] = 6;
	$data["rhyme"] = "ababab";
	$data["keyword"] = "fish";
	return json_encode($data);
}

function getStoryPrompt($args) {
	$data = [];
	$data["type"] = "story";
	$data["words"] = 1000;
	$data["genre"] = "science fiction";
	$data["keyword"] = "fish";
	return json_encode($data);
}

function getPrompt($args) {
	$type = $args["type"];
	if ($type == "poem") {
		$prompt = getPoemPrompt($args);
	}
	else {
		// Default to a story prompt:
		$prompt = getStoryPrompt($args);
	}
	return $prompt;
}

$prompt = getPrompt($_GET);
echo $prompt;

?>
