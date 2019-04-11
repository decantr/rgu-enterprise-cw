<? session_start();

if ( isset( $_SESSION["token"] ) ) {
	header("location: /index.php");
}

// if this is a login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

require_once "config.php";

if ( empty( trim( $_POST["username"] ) ) || empty( trim( $_POST["password"] ) ) ) {
	exit;
}

$request = "select `username`, `password`, `seen` from `users` where `username` = :username";
$statement = $db->prepare($request);
$statement->execute([":username" => $_POST["username"]]);

if ( $statement->rowCount() == 0 ){
	exit;
}

$result = $statement->fetch(PDO::FETCH_ASSOC);

if ( $_POST["password"] == $result["password"] ) {
	$update = $db->prepare("UPDATE users SET seen = NOW() WHERE `username` = :username");
	$update->execute([":username" => $_POST["username"]]);

	// store data in session variables
	$_SESSION["token"] = bin2hex( random_bytes(32) );
	$_SESSION["seen"] = $results["seen"];
	$_SESSION["username"] = $username;

	setcookie("session", $token);
	setcookie("seen", $seen);
	header("Location: /");
	exit();
}
}

?>

<form action="" method="POST">
<h3>login</h3>
<span>
	<label for="username">username</label>
	<input type="text" name="username">
</span>
<span>
	<label for="password">password</label>
	<input type="text" name="password">
</span>
<input type="submit" name="login">
</form>
