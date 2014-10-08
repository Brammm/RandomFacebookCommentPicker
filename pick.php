<?php

require('vendor/autoload.php');

use Facebook\FacebookRequest;
use Facebook\FacebookSession;

$session = FacebookSession::newAppSession();

$postId = '813531995360181'; // speakers
//$postId = '813531668693547'; // monitor

$request = new FacebookRequest(
	$session,
	'GET',
	sprintf('/%s/comments', $postId)
);

$page = 1;
$users = array();
while ($request) {
	$response = $request->execute();
	echo "Fetching Page {$page} \n\r";
	$graphObject = $response->getGraphObject()->asArray();
	foreach ($graphObject['data'] as $comment) {
		$users[$comment->from->id] = array(
			'name' => $comment->from->name,
			'msg' => $comment->message,
 		);
	}
	// Get next
	$page++;
	$request = $response->getRequestForNextPage();
}

$keys  = array_keys($users);
$count = count($keys);
$winner = $keys[rand(0, $count-1)];

echo "Unique posters: {$count}\n\r";
echo "Winner: {$users[$winner]['name']}, comment: {$users[$winner]['msg']}\n\r";

//var_dump($graphObject); exit;
