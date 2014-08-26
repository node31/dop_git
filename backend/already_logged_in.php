<?php
//already_logged_in.php
//To check whether the user has already logged in or not

session_start();
function loggedIn(){
  if(isset($_SESSION['username'])){
    echo 'The user is already logged in<br><br>'.$_SESSION['username'];
    return true;
  }
  else{
    echo 'Please Log In.';
    return false;
  }
}
loggedIn();



?>