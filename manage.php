<? session_start();

require_once "lib/config.php";
require_once "lib/Feed.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$feedurl = trim ( $_POST["feedurl"] );

if ( empty ( $feedurl ) ) {
	$error = "No feed given";
} else if (filter_var($feedurl, FILTER_VALIDATE_URL) === false) {
	$error = "Not a valid url";
} else {
	$exists = $db->prepare("SELECT * FROM feeds WHERE `link` = :link LIMIT 0, 1");
	$exists->execute( [":link" => $feedurl ] );
if ( $exists->rowCount() != 0 ) {
	$feed = Feed::feedFromRow( $exists->fetch(PDO::FETCH_ASSOC) );
} else {
	$feed = Feed::feedFromUrl( $feedurl );

	$request = "INSERT INTO `feeds` (`title`, `summary`, `link`) VALUES ( :title, :summary, :link)";
	$statement = $db->prepare($request);
	$statement->execute( [":title" => $feed->title, ":summary" => $feed->summary, ":link" => $feed->link] );
	$feed->id = $db->lastInsertId();
}

// test if the feed is already subscribed
$exists = $db->prepare( "SELECT * FROM subscriptions WHERE user_id = :user_id AND feed_id = :feed_id" );
$exists->execute([":user_id" => $_SESSION["user_id"], ":feed_id" => $feed->id]);

if ( $exists->rowCount() != 0 ) {
	$error = "Already subscribed";
} else {
	// insert the subscription into the db
	$request = "INSERT INTO `subscriptions` (`user_id`, `feed_id`) VALUES (:user_id, :feed_id)";
	$statement = $db->prepare( $request );
	$statement->execute([":user_id" => $_SESSION["user_id"], ":feed_id" => $feed->id]);
	$error = "Subscribed to " . $feed->title . "!";
}
}
}
$error = $error == "" ? $error : $error . "<br />";
?>

<!--	imports	-->
<link rel="stylesheet" type="text/css" href="lib/style.css" />
<script type="text/javascript" src="lib/script.js"></script>

<!--	header	-->

<? include("lib/header.php")?>

<!--	content	-->
<body onLoad="getSubscriptions()">
<table><thead>
<tr>
	<td><b>Last Published</b></td>
	<td><b>Title</b></td>
	<td align="right"><b>Feed ID</b></td>
	<td align="right"><b>Star</b></td>
	<td align="right"><b>Unsubscribe</b></td>
</tr>
</thead><tbody id="posttable" >
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody><tfoot>
<tr height="20em"></tr>
<tr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
	<td><h1>Add New Feed</h1></td>
	<td align="center" colspan="3">
		<input id="search" type="text" name="feedurl" onkeyup="showFeeds(this.value)" required>
	</td>
	<td align="center">
		<input onclick="refreshArticles()" type="submit" value="Add" name="add">
	</td>
</tr>
</form>
<tr><td></td><td colspan="1" align="center" id="searchresults"></td><td></td></tr>
</tfoot></table>
<span class="error"><?php echo $error ?></span>
</body>
