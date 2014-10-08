<?php
$page = 1;
function getData($url) {
	global $page;
	echo "Fetching page {$page}\n\r";
	$page++;
	return json_decode(file_get_contents($url));
}

$commentId = '813531995360181'; // boxen
//$commentId = '813531668693547'; // scherm


$response = getData('https://graph.facebook.com/v2.1/'.$commentId.'?access_token=CAACEdEose0cBAJs2WZCQuoIF7j6tz09nB0fMMeSZCZCBPgVytZCFuwG77oP8adXLQkyjJHs7TEwZA3gUDfGGPSGjjAz4yroUl9Kf5TEGboNCZA77dgP9pv69D6NN20SnVB7uRTfNeIEBltIRRZCyzeEed3UZBurORJDYvsMDVydn6D7i7zh1V3uUdOjowGUWVGn0FrQYu36bhO4la5IRUosf');

$comments 	 = $response->comments;
$getComments = true;

$users = array();

do {
	foreach($comments->data as $comment) {
		$users[$comment->from->id] = array(
			'name' => $comment->from->name,
			'msg' => $comment->message,
		);
	}

	if (isset($comments->paging->next)) {
		$comments = getData($comments->paging->next);
	} else {
		$getComments = false;
	}
} while ($getComments);

$keys  = array_keys($users);
$count = count($keys);
$winner = $keys[rand(0, $count-1)];

echo "Unique posters: {$count}\n\r";
echo "Winner: {$users[$winner]['name']}, comment: {$users[$winner]['msg']}\n\r";

exit;

