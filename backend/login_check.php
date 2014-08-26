<?php

require_once 'connect.php';

function log_in_user(){


  $redirect_page_unsuccessful='login.html';
  $redirect_page_successful='meals.html';

  if(count($_POST)<=0){
    //header('Location:'.$redirect_page_unsuccessful.'?rama=1';
    die('Error 108:The post script is empty. Return Status:1');
  }



  if(!isset($_POST['username']) || !isset($_POST['password'])){
    header('Location:'.$redirect_page_unsuccessful.'?rama=2');
    //die('Error 109:Either the username or password has not been set.Return Status:2');
  }

  if(empty($_POST['username']) || empty($_POST['password'])){
    header('Location:'.$redirect_page_unsuccessful.'?rama=3');
    //die('Error 110:Either username or password is empty.Return Status:3');
  }


  $db_name = 'dop';
  $dop_link = connect($db_name);

  $username = protect_string($_POST['username']);
  $pass = protect_string($_POST['password']);

  $user_exist_check = "SELECT `id`,`password` FROM `user` WHERE `id`='$username' ";
  echo '<br><br> USER EXIST CHECK QUERY:'.$user_exist_check.'<br><br>';
  if($user_exist_check_run = mysqli_query($dop_link,$user_exist_check)){

             $result_row = mysqli_fetch_row($user_exist_check_run);
             echo '<br><br>ID:'.$result_row[0].'<br>PASSWORD:'.$result_row[1];

             if(sha1($pass)!=$result_row[1]){
                   header('Location:'.$redirect_page_unsuccessful.'?rama=5');
                   //die('Error 112:Incorrect Password.Return Status:5');
             }

             echo '<br><br>PASSSS!!'.sha1($pass);

             session_start();
             $_SESSION['username']=$username;

             if(isset($_SESSION['username'])){
               header('Location:'.$redirect_page_successful);
               //echo 'THE USER HAS BEEN SUCCESSFULLY LOGGED IN';
             }else{
               header('Location:'.$redirect_page_unsuccessful.'?rama=6');
               //die('Error 113:THE USER COULD NOT BE LOGGED IN. RETURN STATUS:6');
             }
  }else{
    header('Location:'.$redirect_page_unsuccessful.'?rama=4');
    //die('Error 111:The username does not exist.Please register and then login.Return Status:4');
  }

}

function protect_string($string){
  return mysql_real_escape_string(addslashes(htmlentities($string)));
}
log_in_user();

?>