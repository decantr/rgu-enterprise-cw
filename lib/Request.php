<? session_start();

switch ($_SERVER['QUERY_STRING']) {
case "q=getSubscribedFeeds":
	echo json_encode(getSubscribedFeeds());
	break;
case "q=getTopArticles":
	echo json_encode(getTopArticles());
	break;
default;
	echo "error" . $_SERVER['QUERY_STRING'];
}

function getSubscribedFeeds() {
require_once "config.php";
require_once "Feed.php";

$sql = "SELECT `feeds`.`id`, `feeds`.`title`, `feeds`.`summary`, `feeds`.`link`, `feeds`.`updated` FROM `feeds` INNER JOIN `subscriptions` ON `subscriptions`.`feed_id` = `feeds`.`id` WHERE `subscriptions`.`user_id` = :user_id";

$statement = $db->prepare($sql);
$statement->execute( [":user_id" => $_SESSION["user_id"]] );

$feeds = array();

while ( $row = $statement->fetch(PDO::FETCH_ASSOC) )
	array_push( $feeds, Feed::feedFromRow($row) );

return $feeds;
}

function getArticles() {
	require_once "Article.php";

	$subscriptions = getSubscribedFeeds();
	$topFeeds = array();

	foreach ( $subscriptions as $feed) {
		array_push( $topFeeds, getFeed( $feed->link ) );
	}

	return $topFeeds;
}

function getFeed( $feedLink ) {
$feed = simplexml_load_file( $feedLink );
$articles = array();
$source = (string) $feed->channel->title;

// iterate through all of the items
$count = 0;
foreach ( $feed->channel->item as $item ) {
	if ( $count > 10 ) break;
	$article = Article::articleFromItem( $item , $source );

	try {
		json_encode($article);
	} catch ( Exception $e ) {
		$article = null;
	}

	if ( $article != null )
		array_push( $articles, $article );

	$count++;
}

// return the array of posts
return $articles;
}

?>
