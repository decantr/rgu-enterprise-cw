<?

// article class to hold the articles we scrape
class Article {

	public $id;
	public $feed_id;
	public $title;
	public $link;
  public $pubDate;
	public $summary;

	public function __construct() {
		$this->id = $this->feed_id = $this->title = $this->link = $this->pubDate = $this->summary = "";
	}

	// the actual constructor
	protected function setAll ( $i, $f, $t, $l, $d, $s ) {
		$this->id = (string) $i;
		$this->feed_id = (string) $f;
		$this->title = (string) $t;
		$this->link = (string) $l;
		$this->pubDate = (string) $d;
		$this->summary = substr( strip_tags((string) $s), 0, 511 );
	}

	// make an article from an item (from xml)
	public static function articleFromItem ( $item , $feed_id) {
		$instance = new static();

		// construct from the item we are given
		$pd = new DateTime($item->pubDate);

		$instance->setAll(
			null, $feed_id,
			$item->title,
			$item->link != "" ? $item->link : $item->guid,
			$pd->format("Y-m-d H:i:s"),
			$item->description
		);

		return $instance;
	}

	// make a article from a database entry
	public static function articleFromRow( $r ) {
		$instance = new static();

		$instance->setAll( $r["id"], $r["feed_id"], $r["title"], $r["link"], $r["pubDate"], $r["summary"] );
		return $instance;
	}


}
?>
