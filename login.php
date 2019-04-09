<?

if ( isset( $_COOKIE["session"] ) ) {
	header("Location: /index.php");
	exit;
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
