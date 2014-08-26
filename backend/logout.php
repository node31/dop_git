<?php
//logout.php ---- Will be used for logging the user out

session_start();

if(isset($_SESSION['username'])){
  unset($_SESSION['username']);
  echo 'The Session has been successfully destroyed';
}
else{
  echo 'The Session was never set';
}


?>