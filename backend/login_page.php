<?php
require_once('already_logged_in.php');
@session_start();
if(loggedIn()){
  //header('Location:'$redirect_page_successful);
  echo 'The user is already logged in<br><br>'.$_SESSION['username'];
}else{
  echo 'Please Log In.';
}
?>
<html>
<head>
</head>
<form name="reg" action = "login_check.php" method="POST">
<table>
		<tr>
			<td>
				<h2 style="color:#777777;font-family:arial;">Login</h2>
			</td>
		</tr>

		<tr>
			<td>
				<input type="textbox" placeholder="User Id" name="username">
			</td>
		</tr>

		<tr>
			<td>
				<input type="password" placeholder="Password" name="password">
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" value="Submit">
			</td>
		</tr>
</table>
</form>
</html>

