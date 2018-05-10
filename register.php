<?php
    session_start();
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }
?>
<html lang="en">
	<?php $page_name="Register"; include('components/header.php'); ?>
	<body class="wrapper">
		<?php include('components/nav.php'); ?>
		<main class="register">
			<h1>Registration</h1>
			<form action="controllers/user.php" method="POST">
				<input type="text" name="username" placeholder="Username" value="<?php echo isset($_GET['username']) ? $_GET['username'] : '' ; ?>">
				<input type="password" name="password" placeholder="Password" class="<?php echo isset($_GET['password']) ? 'error' : '' ; ?>">
				<input type="password" name="password2" placeholder="Retype Password" class="<?php echo isset($_GET['password']) ? 'error' : '' ; ?>">
				<input type="email" name="email" placeholder="Email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : '' ; ?>">
				<input type="text" name="firstname" placeholder="First Name" value="<?php echo isset($_GET['firstname']) ? $_GET['firstname'] : '' ; ?>">
				<input type="text" name="lastname" placeholder="Last Name" value="<?php echo isset($_GET['lastname']) ? $_GET['lastname'] : '' ; ?>">
				<input type="text" name="address" placeholder="Address" value="<?php echo isset($_GET['address']) ? $_GET['address'] : '' ; ?>">
				<button type="submit" class="btn btn-default">Register</button>
				<input type="hidden" name="from" value="register">
				<input type="hidden" name="success" value="login">
				<p>Already registered? <a href="login.php">Login</a></p>
			</form>
		</main>
		<?php include('components/footer.php'); ?>
	<body>
</html>