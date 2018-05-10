<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru - <?php echo $page_name?></title>
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/nav.css">
	<link rel="stylesheet" href="css/footer.css">
	<link rel="stylesheet" href="css/toast.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="js/toast.js"></script>
	<script>
	window.onload = function(){ 
		<?php
		session_start();
		if ($_GET['toast'])
			echo "toast('".$_GET['toast']."');";
		?>
	};
	</script>
</head>