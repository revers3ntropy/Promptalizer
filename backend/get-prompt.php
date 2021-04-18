<?php

function getPrompt() {
	$data = ["type"=>"poem",
		"lines"=>10,
		"rhyme"=>"ababababab",
		"keyword"=>"fish"];
	return json_encode($data);
}

$prompt = getPrompt();
echo $prompt;

?>
