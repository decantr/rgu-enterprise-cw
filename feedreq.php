<?

// post class to hold the posts we scrape
class Post {
	public $title;
  public $date;
	public $link;
	public $desc;

	public function __construct( $t, $d, $l, $c ) {
		$this->title = (string) $t;
		$this->date = $d;
		$this->link = (string) $l;
		$this->desc = (string) $c;
	}
}

//$url = 'https://lukesmith.xyz/rss.xml';
$url = 'lukesmith.xyz/rss.xml';
$feed = simplexml_load_file($url);
$posts = array();

// iterate through all of the items
foreach ($feed->channel->item as $item) {

	$post = new Post(
		$item->title,
		(strtotime( $item->pubDate) ),
		$item->link != "" ? $item->link : $item->guid,
		$item->description
	);
	$posts[] = $post;

}

// return the array of posts as a json object
echo json_encode($posts);
?>
