<header>
<?php
	session_start();
?>
<ul class="nav">
	<li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
	<li><a href="index.php"><i class="fa fa-image"></i> Gallery</a></li>
	<li class="nav_logo">Camagru</li>
	<li><a href="index.php"><i class="fa fa-camera"></i> Camera</a></li>
	<?php
	if (isset($_SESSION['username']) && !empty($_SESSION['username']))
	{
		echo '
		<li class="nav_account">
			<span><i class="fa fa-user-circle-o"></i> '.$_SESSION["username"].'</span>
			<ul class="ani">';
		if ($admin !== null)
			echo '<li><a href="admin.php">Manage</a></li>';
			echo '<li><a href="account.php">Account</a></li>';
			echo '<li><a href="logout.php">Logout</a></li>
			</ul>
		</li>
		';
	}
	else
	{
		echo '
		<li class="nav_account">
			<span><i class="fa fa-user-circle-o"></i> Account</span>
			<ul class="ani">
				<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>
			</ul>
		</li>';
	}
	?>
</ul>
</header>