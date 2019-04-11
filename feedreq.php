<?

// post class to hold the posts we scrape
class Post {
	public $title;
  public $date;
	public $link;
	public $desc;

	public function __construct( $t, $d, $l, $c ) {
		$this->title = (string) $t;
		$this->date = (string) $d;
		$this->link = (string) $l;
		$this->desc = (string) $c;
	}
}

// function to return the json of the feed
function getFeed( $feedurl ) {
$feed = simplexml_load_file($feedurl);
$posts = array();

// iterate through all of the items
foreach ($feed->channel->item as $item) {

	$post = new Post(
		$item->title,
		$item->pubDate,
		$item->link != "" ? $item->link : $item->guid,
		$item->description
	);
	$posts[] = $post;

}

// return the array of posts
return $posts;
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
