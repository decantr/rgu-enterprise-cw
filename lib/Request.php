<? session_start();


switch ($_GET["q"]) {
case "getSubscribedFeeds":
	echo json_encode(getSubscribedFeeds());
	break;
case "getTopArticles":
	echo json_encode(getTopArticles( $_GET["n"] ));
	break;
case "refreshArticles":
	refreshArticles();
	break;
case "unsubscribe":
	unsubscribe( $_GET["feed_id"] );
	break;
case "search":
	echo json_encode( search( $_GET["s"] ) );
	break;
case "hideArticle":
	hideArticle( $_GET["article_id"] );
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

function getTopArticles( $qty ) {
	require_once "config.php";
	require_once "Article.php";

	$request =
		"SELECT `articles`.`id`, `articles`.`feed_id`, `articles`.`title`, `articles`.`summary`, `articles`.`link`, `articles`.`pubDate`, `feeds`.`title` AS `channel`
		FROM `articles`
		INNER JOIN `subscriptions` ON `subscriptions`.`feed_id` = `articles`.`feed_id`
		INNER JOIN `feeds` ON `feeds`.`id` = `articles`.`feed_id`
		LEFT JOIN `hidden` ON `hidden`.`article_id` = `articles`.`id`
		WHERE `subscriptions`.`user_id` = :user_id
			AND `hidden`.`article_id` IS NULL
		ORDER BY `articles`.`pubDate`
		DESC LIMIT :amount ";

	$statement = $db->prepare( $request );
	$statement->bindValue( ":user_id", $_SESSION["user_id"] );
	$statement->bindValue( ":amount", $qty , PDO::PARAM_INT );
	$statement->execute();

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

function search( $str ) {

	$suggestions = array();

	if ( ! empty( trim ( $str ) ) ) {

		require_once "config.php";
		$results = $db->prepare( "SELECT `link` FROM `feeds` WHERE `link` LIKE ?" );
		$results->execute( [ "%" . $str . "%" ] );

		foreach ( $results as $row ) {
			array_push( $suggestions , $row );
		}

	}
	return $suggestions;
}

// acts as a "read" tracker for user
function hideArticle( $article_id ) {
	if ( ! empty( trim ( $article_id ) ) ) {
	require_once "config.php";

	// check for already hidden
	$request = "SELECT * FROM `hidden` WHERE `user_id` = :user_id AND `article_id` = :article_id";
	$statement = $db->prepare( $request );
	$statement->execute([
		":user_id" => $_SESSION["user_id"],
		":article_id" => $article_id,
	]);
	if ( $statement->rowCount() == 0 ) {

	// insert into the hidden table
	$request = "INSERT INTO `hidden` (`user_id`, `article_id`) VALUES (:user_id, :article_id)";
	$statement = $db->prepare( $request );
	$statement->execute([
		":user_id" => $_SESSION["user_id"],
		":article_id" => $article_id,
	]);
	} // else error = it exsists
	} // else error = nothing supplied
}

?>

