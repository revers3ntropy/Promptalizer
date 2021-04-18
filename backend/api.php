<?php
	
function get_prompt() {
	$prompt = ["type"=>"poem","num_lines"=>10, "keyword"=>"fish"];
	return $prompt;
}

$prompt = get_prompt();
print json_encode($prompt);
?>
