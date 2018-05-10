<?php
	require_once('mysqli.php');
	require_once('hash.php');
	$deleted_account = 'deleted_account';
	function people_update2($username, string $firstname, string $lastname, string $password, string $address)
	{
		$err = null;
		$db = database_connect();
		if (strlen($firstname) < 3 || strlen($firstname) > 45)
			$err[] = 'First Name is too short';
		if (strlen($lastname) < 3 || strlen($lastname) > 45)
			$err[] = 'Last Name is too short';
		if (strlen($password) < 7)
			$err[] = 'Password is too short';
		else
			$password = encrypt($password);
		if (!empty($err))
			return ($err);
		$username = mysqli_real_escape_string($db, $username);
		$firstname = mysqli_real_escape_string($db, $firstname);
		$lastname = mysqli_real_escape_string($db, $lastname);
		$password = mysqli_real_escape_string($db, $password);
		$address = mysqli_real_escape_string($db, $address);
		$req = "UPDATE peoples SET firstname='$firstname', lastname='$lastname', password='$password', address='$address' WHERE username = '$username'";
		$req = mysqli_query($db, $req);
		if ($req)
			return true;
		return array('error');
	}
	function people_create(string $username, string $email, string $password, string $firstname, string $lastname, string $address, int $isAdmin)
	{
		$db = database_connect();
		$err = array();
		if (strlen($username) > 45 || strlen($username) < 5)
			$err[] = 'Username is too short';
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE)
			$err[] = 'Email is not valid';
		if (strlen($password) < 7)
			$err[] = 'Password is too short';
		else
			$password = encrypt($password);
		if (strlen($firstname) < 3 || strlen($firstname) > 45)
			$err[] = 'First Name is too short';
		if (strlen($lastname) < 3 || strlen($lastname) > 45)
			$err[] = 'Last Name is too short';
		if (!empty($err))
			return ($err);
		$username = mysqli_real_escape_string($db, $username);
		$email = mysqli_real_escape_string($db, $email);
		$password = mysqli_real_escape_string($db, $password);
		$firstname = mysqli_real_escape_string($db, $firstname);
		$lastname = mysqli_real_escape_string($db, $lastname);
		$address = mysqli_real_escape_string($db, $address);
		$req = "INSERT INTO peoples (username, email, password, isAdmin, firstname, lastname, address)
			VALUES ('$username', '$email', '$password', '$isAdmin', '$firstname', '$lastname', '$address')";
		if (mysqli_query($db, $req) === TRUE)
			return TRUE;
		return (array('general'));
	}
/*
  * peoples_update takes an array supposed to contain same datas than peoples_create params
  * and a boolean, if setted as 1 (default == 0) giving possibilities to update every 'good'
  * datas even if there is others that are wrong
*/
	function people_update(array $datas, string $old_username, bool $noError = false)
	{
		$db = database_connect(); /* Could control if connection worked or not (NULL or NOT) */
		$err = NULL;
		if ($datas['password'])
		{
			if (strlen($datas['password']) < 7)
				$err[] = 'password';
			else
			{
				$password = encrypt($datas['password']);
				$req['password'] = $password;
			}
		}
		if ($datas['firstname'])
		{
			if (strlen($datas['firstname']) < 3 || strlen($datas['firstname']) > 45)
				$err[] = 'firstname';
			else
				$req['firstname'] = mysqli_real_escape_string($db, $datas['firstname']);
		}
		if ($datas['lastname'])
		{
			if (strlen($datas['lastname']) < 3 || strlen($datas['lastname']) > 45)
				$err[] = 'lastname';
			else
				$req['lastname'] = mysqli_real_escape_string($db, $datas['lastname']);
		}
		$req['address'] = mysqli_real_escape_string($db, $datas['address']);
		if ($datas['isAdmin'])
			$req['isAdmin'] = $datas['isAdmin'];
		if ($datas['username'])
		{
			if (strlen($datas['username']) > 45 || strlen($datas['username']) < 5)
				$err[] = 'username';
			else
				$req['username'] = mysqli_real_escape_string($db, $datas['username']);
		}
		if ($datas['cookie'])
			$req['cookie'] = generate_cookie($old_username);
		else if ($datas['cookie'] === 0)
			$req['cookie'] = '';
		if ($err == NULL || $noError == 1)
		{
			$old_username = mysqli_real_escape_string($db, $old_username);
			$err = NULL;
			foreach($req as $k => $v)
			{
				$req = "UPDATE peoples set '$k' as '$v' WHERE username = '$old_username'";
				if (mysqli_query($db, $req) === FALSE)
					$err[] = $k;
			}
			return $err;
		}
		else
			return $err;
	}
	function people_clear($username, $password)
	{
		global $deleted_account;
		$username = mysqli_real_escape_string($db, $username);
		$password = encrypt($password);
		$req = "UPDATE peoples set (username, email, password, isAdmin, firstname, lastname, address, cookie, valid) VALUES
			('$deteted_account', '', '', 0, '', '', '', '', '') WHERE username = '$username' AND password = '$password'";
	}
	function admin_clear($username, $password)
	{
		global $deleted_account;
		$username = mysqli_real_escape_string($db, $username);
		$password = encrypt($password);
		$req = "UPDATE peoples set (username, email, password, isAdmin, firstname, lastname, address, cookie, valid) VALUES
			('$deteted_account', '', '', 0, '', '', '', '', '') WHERE username = '$username' AND password = '$password'";
	}
	function people_delete($username)
	{
		$db = database_connect();
		
		$username = mysqli_real_escape_string($db, $username);
		$req = "DELETE FROM peoples WHERE username = '$username'";
		$req = mysqli_query($db, $req);
		return ($req);
	}
	function admin_delete($username, $password)
	{
		$username = mysqli_real_escape_string($db, $username);
		$password = encrypt($password);
		$req = "DELETE FROM peoples WHERE username = '$username' AND password = '$password' AND isAdmin = 0";
		$req = mysqli_query($db, $req);
		return ($req);
	}
	function people_get($username, $password)
	{
		$db = database_connect();
		$password = encrypt($password);
		$username = mysqli_real_escape_string($db, $username);
		$req = mysqli_query($db, "SELECT * FROM peoples WHERE username = '$username' AND password = '$password'");
		if (!$req)
			return null;
		return mysqli_fetch_assoc($req);
	}
	function people_exist($username)
	{
		$db = database_connect();
		$username = mysqli_real_escape_string($db, $username);
		$req = "SELECT * FROM peoples WHERE username = '$username'";
		$req = mysqli_query($db, $req);
		if (!$req)
			return null;
		return mysqli_fetch_assoc($req);
	}
	function admin_exist($username)
	{
		$db = database_connect();
		$username = mysqli_real_escape_string($db, $username);
		$req = "SELECT * FROM peoples WHERE username = '$username' AND isAdmin = 1";
		$req = mysqli_query($db, $req);
		if (!$req)
			return null;
		return mysqli_fetch_assoc($req);
	}
	function people_get_all()
	{
		$db = database_connect();
		$req = mysqli_query($db, "SELECT * FROM peoples WHERE isAdmin = 0");
		if (!$req)
			return null;
		return mysqli_fetch_all($req, MYSQLI_ASSOC);
	}
	function admin_get($username, $password)
	{
		$db = database_connect();
		$password = encrypt($password);
		$username = mysqli_real_escape_string($db, $username);
		$req = "SELECT * FROM peoples WHERE username = '$username' AND password = '$password' AND isAdmin = 1";
		$req = mysqli_query($db, $req);
		return mysqli_fetch_assoc($req);
	}
?>