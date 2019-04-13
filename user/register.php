<? session_start();

if ( isset( $_SESSION["token"] ) ) {
	header("location: /");
}

$error = "";

// if it is a post request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

require_once "../lib/config.php";

// check if user and password fields are filled
if ( empty( trim( $_POST["username"] ) ) ) {
	$error = "Username cannot be empty";
} else if ( empty( trim( $_POST["password"] ) ) ) {
	$error = "Password cannot be empty";
} else if ( strlen( trim($_POST["password"]) ) < 8 ) {
		$error = "Password must have atleast 8 characters.";
} else {

	$username = trim( $_POST["username"] );

	$request = "SELECT `id` FROM `users` WHERE `username` = :username";

	$statement = $db->prepare($request);
	$statement->execute( [":username" => $username] );

// check if user exists
if ( $statement->rowCount() != 0 ){
	$error = "Username already taken";
	sleep(1);
} else {

	$password = password_hash(trim( $_POST["password"] ), PASSWORD_BCRYPT);

	$request = "INSERT INTO `users` (`username`, `password`) VALUES ( :username, :password)";

	$statement = $db->prepare($request);
	$statement->execute( [":username" => $username, ":password" => $password] );
	$_SESSION["username"] = $username;
	header("location: /user/login.php");
}
}
}

$error = $error . "<br />";
?>

<link rel="stylesheet" type="text/css" href="../lib/style.css" />

<? include("../lib/header.php") ?>

<form action="" method="POST">
<h1>Register</h1>
<span class="error"><?php echo $error ?></span>
<span>
	<label for="username">Username</label>
	<input type="text" name="username">
</span>
<span>
	<label for="password">Password</label>
	<input type="password" name="password" minlength="8" required>
</span>
<input type="submit" name="register">
</form>
or <a href="login.php">login</a>
