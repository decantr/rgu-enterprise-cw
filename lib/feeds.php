<?

// post class to hold the posts we scrape
class Feed {
	public $id;
	public $title;
	public $summary;
	public $link;
	public $updated;

	public function __construct () {
		$this->id = $this->title = $this->summary = $this->link = "";
	}

	// the actual constructor
	protected function setAll ( $i, $t, $s, $l, $u ) {
		$this->id = $i;
		$this->title = (string) $t;
		$this->summary = substr( (string) $s, 0, 511 );
		$this->link = (string) $l;
		$this->updated = (string) $u;
	}

	// make feed given a url
	public static function feedFromUrl ( $url ) {
		$instance = new static();

		// get the feed
		$feed = simplexml_load_file($url);
		$feed = $feed->channel;
		$l = $feed->link != "" ? $feed->link : $feed->guid;

		// construct
		$instance->setAll( null, $feed->title, $feed->description , $l, null );
		return $instance;
	}

	// make a feed from a database entry
	public static function feedFromRow( $r ) {
		$instance = new static();

		$instance->setAll( $r["id"], $r["title"], $r["summary"], $r["link"], $r["updated"] );

		return $instance;
	}

}

?>
