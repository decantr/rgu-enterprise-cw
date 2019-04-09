<?

require_once "config.php";

if ( isset( $_COOKIE["session"] ) ) {
	header("Location: /index.php");
	exit;
}

if ( empty( trim( $_POST["username"] ) || empty( trim( $_POST["password"] ) ) {
	$error = true
	exit
}

$request = "SELECT `id` FROM `users` WHERE `username` = :username";

$statement = $database->prepare($request);
$statement->execute( [":username" => $_POST["username"]] );

if ( $statement->rowCount() != 0 ){
	$error = true;
	exit
}

$request = "insert into `user` (`username`, `password`) values ( :username, :password)";

$statement = $database->prepare($request);
$statement->execute( [":username" => $username, ":password" => $password] );

?>

<form action="" method="POST">
<h3>register</h3>
<? if (	$error ) { ?>
	invalid username or password
<? } else { ?>
	user created
<? } ?>
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
