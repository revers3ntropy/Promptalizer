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
    if ($args["type"] == "poem")
        return getPoemPrompt($args);

    // Default to a story prompt:
    return getStoryPrompt($args);
}

$prompt = getPrompt($_GET);
echo $prompt;