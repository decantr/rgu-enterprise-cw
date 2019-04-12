<?

require_once "config.php";

function updateFeed( $feed ) {
while ( $row = $feed ) {

	$data = get_from_url($row["url"]);
	$articles = parse_rss_data($data);

	foreach ($articles as $article) {
		$exists = $db->prepare("SELECT `id` FROM `articles` WHERE `feed_id` = :feed_id");

		$exists->execute([":feed_id" => $row["id"]]);

		// if the article does not exist
		if ($exist_statement->rowCount() == 0) {
			$insert = $db->prepare("INSERT INTO `articles` (`feed_id`, `title`, `summary`, `link`, `published`) VALUES (:feed_id, :title, :summary, :link, :published)");

			$insert->execute([
				":feed_id" => $row["id"],
				":title" => $article["title"],
				":summary" => $article["description"],
				":link" => $article["link"],
				":published" => $article["date"]->format("Y-m-d H:i:s"),
			]);
		}
	}
}
}

?>
