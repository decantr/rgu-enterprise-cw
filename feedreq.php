<?

require_once "lib/Article.php";

// function to return the json of the feed
function getFeed( $feedurl ) {
$feed = simplexml_load_file($feedurl);
$articles = array();
$source = (string) $feed->channel->title;

// iterate through all of the items
foreach ($feed->channel->item as $item) {
	array_push( $articles, Article::articleFromItem( $item , $source ) );
}

// return the array of posts
return $articles;
}

function buildFeed( $quantity ) {
	// FOR TESTING
	//$url = 'https://lukesmith.xyz/rss.xml';
	$testUrl = 'lukesmith.xyz/rss.xml';
	// TODO : get the urls from the users DB
	// TODO : select handle the amount from the user
	// TODO : select the time ordered top # of feeds

	$feeds = array();
	// get the urls for the user
	$user_subscriptions = array();
	$user_subscriptions[0] = $testUrl;
	$user_subscriptions[1] = $testUrl;

	foreach ( $user_subscriptions as $url ) {
		$feeds = getFeed( $url );
	}

	// TODO : sort the arrays by chronology
	$topFeeds = array_slice($feeds, 0, $quantity);
	return $topFeeds;
}

echo json_encode(buildFeed( 10 ));

?>
