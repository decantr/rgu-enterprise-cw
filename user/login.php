<? session_start();

if ( isset( $_SESSION["token"] ) ) {
	header("location: /index.php");
}

$error = $redirect = "";

// if this is a login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

require_once "../lib/config.php";

if ( empty( trim( $_POST["username"] ) ) ) {
	$error = "Username cannot be empty";
} else if ( empty( trim( $_POST["password"] ) ) ) {
	$error = "Password cannot be empty";
} else {

	$request = "select `id`, `username`, `password`, `seen` from `users` where `username` = :username";
	$statement = $db->prepare($request);
	$statement->execute([":username" => $_POST["username"]]);

if ( $statement->rowCount() == 0 ){
	$error = "Invalid Username or Password";
	sleep(1);
} else {

	$result = $statement->fetch(PDO::FETCH_ASSOC);

if ( ! password_verify( trim ( $_POST["password"] ), $result["password"] ) ) {
	$error = "Invalid Password";
	sleep(1);
} else {
	$update = $db->prepare("UPDATE users SET seen = NOW() WHERE `username` = :username");
	$update->execute([":username" => $_POST["username"]]);

	// store data in session variables
	$_SESSION["token"] = bin2hex( random_bytes(32) );
	$_SESSION["seen"] = $result["seen"];
	$_SESSION["username"] = $_POST["username"];
	$_SESSION["user_id"] = $result["id"];

	header("location: /");
}
}
}
} else {
	if ( ! empty($_SESSION['username']) && empty ( $_SESSION['token'] ) ) {
		$redirect = $_SESSION['username'];
		$_SESSION['username'] = '';
	}
}
$error = $error . "<br />";
?>

<link rel="stylesheet" type="text/css" href="../lib/style.css" />
<? include("../lib/header.php") ?>

<form action="" method="POST">
<h1>Login</h1>
<span class="error"><?php echo $error ?></span>
<span>
	<label for="username">Username</label>
	<input value="<? echo $redirect; ?>" type="text" name="username">
</span>
<span>
	<label for="password">Password</label>
	<input type="password" name="password" required>
</span>
<input type="submit" name="login">
</form>
or <a href="register.php">register</a>

