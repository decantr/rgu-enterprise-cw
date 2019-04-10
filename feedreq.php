<?

class Post {
	public $title;
  public $date;
	public $link;
	public $desc;

	public function __construct( $t, $d, $l, $c ) {
		$this->title = $t;
		$this->date = $d;
		$this->link = $l;
		$this->desc = $c;
	}
}

//$url = 'https://lukesmith.xyz/rss.xml';
$url = 'lukesmith.xyz/rss.xml';
$feed = simplexml_load_file($url);
$posts = array();

foreach ($feed->channel->item as $item) {
	$post = new Post(
		(string) $item->title,
		(strtotime( $item->pubDate) ),
		(string) $item->link,
		(string) $item->description
	);
	$posts[] = $post;
}

echo $posts[0]->title;
?>
