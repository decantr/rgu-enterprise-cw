<? session_start();

require_once "config.php";
require_once "Feed.php";


$statement = $db->prepare("SELECT * FROM feeds");
$statement->execute();

$feeds = array();

while ( $row = $statement->fetch(PDO::FETCH_ASSOC) )
	array_push( $feeds, Feed::feedFromRow($row) );

echo json_encode($feeds);

?>
