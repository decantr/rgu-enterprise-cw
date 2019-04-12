<? session_start();

require_once "config.php";
require_once "lib/feeds.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

echo "POST";

$feedurl = trim ( $_POST["feedurl"] );

if ( empty ( $feedurl ) ) {
	$error = "No feed given";
} else {
	$exists = $db->prepare("SELECT * FROM feeds WHERE `url` = :url");
	$exists->execute( [":url" => $feedurl ] );
if ( $exists->rowCount() != 0 ) {
	$error = "Feed already exists at " . (string) $exists["id"];
	sleep(1);
} else {

	//$feed = new Feed($feedurl);
	$feed = Feed::feedFromUrl($feedurl);
	echo $feed->title;

	$request = "INSERT INTO `feeds` (`title`, `description`, `url`) VALUES ( :title, :description, :url)";
	$statement = $db->prepare($request);
	$statement->execute( [":title" => $feed->title, ":description" => $feed->summary, ":url" => $feed->link] );
}
}
} else {

	$statement = $db->prepare("SELECT * FROM feeds");
	$statement->execute();

	$feeds = array();

	while ( $row = $statement->fetch(PDO::FETCH_ASSOC) )
		array_push( $feeds, Feed::feedFromRow($row) );

	echo json_encode($feeds);

}

?>

<!--		js		-->

<link rel="stylesheet" type="text/css" href="style.css" />


<!--	header	-->

<? include("header.php")?>

<!--	content	-->
<body onLoad="getFeed()">
<table><thead>
<tr>
	<td><b>Last Activity</b></td>
	<td><b>Title</b></td>
	<td><b>Description</b></td>
	<td align="right"><b>Subscribe</b></td>
	<td align="right"><b>Feed ID</b></td>
</tr>
</thead><tbody id="posttable" >
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody></table>
&emsp;&emsp;<a href="#" onclick="getFeed()">refresh</a>
<br />
<br />
<br />
<h1>Add New Feed</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
	<label for="feedurl">Feed Url</label>
	<input type="text" name="feedurl" minlength="8" required>
	<input type="submit" value="Add" name="add">
</form>
</body>
