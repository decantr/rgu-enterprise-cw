<? session_start();


switch ($_GET["q"]) {
case "getSubscribedFeeds":
	echo json_encode(getSubscribedFeeds());
	break;
case "getTopArticles":
	echo json_encode(getTopArticles());
	break;
case "refreshArticles":
	refreshArticles();
	break;
case "unsubscribe":
	unsubscribe( $_GET["feed_id"] );
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

function getTopArticles( ) {
	require_once "config.php";
	require_once "Article.php";

	$request =
		"SELECT `articles`.`id`, `articles`.`feed_id`, `articles`.`title`, `articles`.`summary`, `articles`.`link`, `articles`.`pubDate`
		FROM `articles`
		INNER JOIN `subscriptions`
		ON `subscriptions`.`feed_id` = `articles`.`feed_id`
		WHERE `subscriptions`.`user_id` = :user_id
		ORDER BY `articles`.`pubDate` DESC
		LIMIT 10";

	$statement = $db->prepare( $request );
	$statement->execute([":user_id" => $_SESSION["user_id"]]);

	$topFeeds = array();
	while ($row = $statement->fetch( PDO::FETCH_ASSOC ))
		array_push( $topFeeds, Article::articleFromRow( $row ) );

	return $topFeeds;
}

function parseFeed( $flink, $fid ) {
$feed = simplexml_load_file( $flink );
$articles = array();

// iterate through all of the items
foreach ( $feed->channel->item as $item ) {
	$article = Article::articleFromItem( $item , $fid );

	try {
		json_encode($article);
	} catch ( Exception $e ) {
		$article = null;
		break;
	}

	if ( $article != null )
		array_push( $articles, $article );

}

// return the array of posts
return $articles;
}

function refreshArticles() {

require_once "config.php";
require_once "Feed.php";
require_once "Article.php";

$statement = $db->prepare(
	"SELECT `feeds`.`id`, `feeds`.`link`
		FROM `feeds`
		INNER JOIN `subscriptions`
		ON`subscriptions`.`feed_id` = `feeds`.`id`
		WHERE `user_id` = :user_id"
	);
$statement->execute( [":user_id" => $_SESSION["user_id"] ]);

while ( $row = $statement->fetch(PDO::FETCH_ASSOC) ) {
	$articles = parseFeed( $row["link"], $row["id"]);

	foreach ( $articles as $article ) {
		$exists = $db->prepare( "SELECT `id` FROM `articles` WHERE `link` = :link" );
		$exists->execute([ ":link" => $article->link ]);

		if ( $exists->rowCount() == 0 ) {

		$insert = $db->prepare("INSERT INTO `articles` (`feed_id`, `title`, `summary`, `link`, `pubDate`) VALUES (:feed_id, :title, :summary, :link, :pubDate)");

		$insert->execute([
			":feed_id" => $row["id"],
			":title" => $article->title,
			":summary" => $article->summary,
			":link" => $article->link,
			":pubDate" => $article->pubDate,
		]);
		}
	}
}
}

function unsubscribe( $feed_id ) {
	require_once "config.php";
	// remove entry from subscriptions if it exists
	$delete = $db->prepare("DELETE FROM `subscriptions` WHERE `feed_id` = :feed_id AND `user_id` = :user_id");
	$delete->execute([":feed_id" => $feed_id, ":user_id" => $_SESSION["user_id"] ]);
}

?>
