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

$sql = "SELECT `feeds`.`id`, `feeds`.`title`, `feeds`.`summary`, `feeds`.`link`, `feeds`.`updated` FROM `feeds` INNER JOIN `subscriptions` ON `subscriptions`.`feed_id` = `feeds`.`id` WHERE `subscriptions`.`user_id` = :user_id";

$statement = $db->prepare($sql);
$statement->execute( [":user_id" => $_SESSION["user_id"]] );

$feeds = array();

while ( $row = $statement->fetch(PDO::FETCH_ASSOC) )
	array_push( $feeds, Feed::feedFromRow($row) );

return $feeds;
}

function getTopArticles() {
	require_once "config.php";
	require_once "Feed.php";
	require_once "Article.php";

	$subscriptions = getSubscribedFeeds();
	$topFeeds = array();

	$request = "SELECT `articles`.`id`, `articles`.`feed_id`, `articles`.`title`, `articles`.`description`, `articles`.`link`, `articles`.`date`
FROM `articles`
INNER JOIN `subscriptions` ON `subscriptions`.`feed_id` = `articles`.`feed_id`
WHERE `subscriptions`.`user_id` = :user_id
ORDER BY `articles`.`date` DESC";

	$statement = $db->prepare( $request );
	$statement->execute([":user_id" => $session["user_id"]]);

	while ($row = $statement->fetch( PDO::FETCH_ASSOC )) {
		array_push( $topFeeds, getFeed( $feed ) );
	}

	return $topFeeds;
}

function getFeed( $f ) {
$feed = simplexml_load_file( $f->link );
$articles = array();

// iterate through all of the items
foreach ( $feed->channel->item as $item ) {
	$article = Article::articleFromItem( $item , $f->id );

	try {
		json_encode($article);
	} catch ( Exception $e ) {
		$article = null;
		break;
	}

	updateArticle( $article );

	if ( $article != null )
		array_push( $articles, $article );

}

// return the array of posts
return $articles;
}

function updateArticle( $article ) {

	$insert = $db->prepare("INSERT INTO `articles` (`feed_id`, `title`, `summary`, `link`, `published`) VALUES (:feed_id, :title, :summary, :link, :published)");

	$insert->execute([
		":feed_id" => $row["id"],
		":title" => $article["title"],
		":summary" => $article["description"],
		":link" => $article["link"],
		":published" => $article["date"]->format("Y-m-d H:i:s"),
	]);
	}

?>
