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
	while ( $row = $exists->fetch(PDO::FETCH_ASSOC) ) {
		$feed = Feed::feedFromRow( $row );
	}

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
echo "hello " . $exists->rowCount();

if ( $exists->rowCount() != 0 ) {
	$error = "already subscribed";
} else {
	// insert the subscription into the db
	$request = "INSERT INTO `subscriptions` (`user_id`, `feed_id`) VALUES (:user_id, :feed_id)";
	$statement = $db->prepare( $request );
	$statement->execute([":user_id" => $_SESSION["user_id"], ":feed_id" => $feed->id]);
	$error = "Feed successfully created";
}
}
}
//$error = "Feed already exists at " . (string) $exists["id"];
$error = $error == "" ? $error : $error . "<br />";
?>

<!--	imports	-->
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="lib/script.js"></script>

<!--	header	-->

<? include("header.php")?>

<!--	content	-->
<body onLoad="getSubscriptions()">
<table><thead>
<tr>
	<td><b>Last Published</b></td>
	<td><b>Title</b></td>
	<td align="right"><b>Feed ID</b></td>
	<td align="right"><b>Star</b></td>
	<td align="right"><b>Remove</b></td>
</tr>
</thead><tbody id="posttable" >
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody></table>
&emsp;&emsp;<a href="#" onclick="getSubscriptions()">refresh</a>
<br />
<br />
<br />
<h1>Add New Feed</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
	<label for="feedurl">Feed Url</label>
	<input type="text" name="feedurl" required>
	<input type="submit" value="Add" name="add">
</form>
<br />
<span class="error"><?php echo $error ?></span>
</body>
