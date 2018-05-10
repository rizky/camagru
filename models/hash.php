<?php
	function encrypt($password)
	{
		return hash('whirlpool', 'c4m4gru'.$password);
	}
?>