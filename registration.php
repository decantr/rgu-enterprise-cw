<?
if ( isset( $_COOKIE["session"] ) ) {
	header("Location: /index.php");
	exit;
}

require_once "config.php";
$error=false;

// if it is a post request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if ( empty( trim( $_POST["username"] ) ) || empty( trim( $_POST["password"] ) ) ) {
	$error = true;
	exit;
}

$request = "select `id` from `users` where `username` = :username";

$statement = $db->prepare($request);
$statement->execute( [":username" => $_POST["username"]] );

if ( $statement->rowCount() != 0 ){
	$error = true;
	exit;
}

$request = "insert into `users` (`username`, `password`) values ( :username, :password)";

$statement = $db->prepare($request);
$statement->execute( [":username" => $_POST["username"], ":password" => $_POST["password"]] );
}
?>

<form action="" method="POST">
<h3>register</h3>
<span>
	<label for="username">username</label>
	<input type="text" name="username">
</span>
<span>
	<label for="password">password</label>
	<input type="text" name="password">
</span>
<input type="submit" name="register">
</form>
