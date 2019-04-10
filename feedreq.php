<?

class Post {
    var $title;
    var $date;
    var $link;
		var $desc;
}

//$url = 'https://lukesmith.xyz/rss.xml';
$url = 'lukesmith.xyz/rss.xml';
$feed = simplexml_load_file($url);
$posts = array();

foreach ($feed->channel->item as $item) {
		$post = new Post();
		$post->title = (string) $item->title;
		$post->date  = (strtotime( $item->pubDate) );
		$post->link  = (string) $item->link;
		$post->desc  = (string) $item->description;

		$posts[] = $post;
}

//echo $posts[0]->title;
?>
