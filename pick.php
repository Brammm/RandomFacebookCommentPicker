<?php

require('vendor/autoload.php');
$config = require('configuration.php');

use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use RandomLib\Factory;

##########################################################
## Use the Facebook SDK to fetch all comments on a post ##
##########################################################

FacebookSession::setDefaultApplication($config['app-id'], $config['app-secret']);
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

############################
## Select a random winner ##
############################

$keys  = array_keys($users);
$count = count($keys);

$factory   = new Factory;
$generator = $factory->getMediumStrengthGenerator();
$index     = $generator->generateInt(0, $count-1);

$winner = $users[$keys[$index]];

echo "Unique posters: {$count}\n\r";
echo "Winner: {$winner['name']}, comment: {$winner['msg']}\n\r";
