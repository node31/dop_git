<?php

require_once('connect.php');

$link = connect('dop');



if(isset($_POST['id']) && isset($_POST['pass']) && isset($_POST['retyped_pass']) && isset($_POST['name']) && isset($_POST['mobile']) && isset($_POST['email']) ){
	
	
  $id = protect_string($_POST['id']);
  $pass = protect_string($_POST['pass']);
  $retyped_pass = protect_string($_POST['retyped_pass']);
  $name = protect_string($_POST['name']);
  $mobile = protect_string($_POST['mobile']);
  $email = protect_string($_POST['email']);
  
  echo $id.'  \n'.$pass.'  \n'.$retyped_pass.'   \n'.$name.'   \n'.$mobile.'   \n'.$email.'  \n';
  

  if(!empty($id) && !empty($pass) && !empty($retyped_pass) && !empty($name) && !empty($mobile) && !empty($email)){

    if($pass!=$retyped_pass){
		echo '<br> Pass!=Retyped Pass';
      //echo header('Location:register.php?shiva=1');
    }else{
      echo '<br>THEY ARE EQUAL';
    }


    $query = "SELECT * FROM `user` WHERE `id`='$id'";
	echo $query;

    if($query_run = mysqli_query($link,$query)){
						  if(mysqli_num_rows($query_run)!=0){
							  echo '<br> Some User Exists';
							//echo header('Location:register.php?shiva=2');
						  }else{
										$pass_hash = sha1($pass);
										$insert_query = "INSERT INTO `user` VALUES('','$id','$pass_hash','$name','$email','$mobile')";
										echo '<br>'.$insert_query.'<br>';
										if($insert_query_run=mysqli_query($link,$insert_query)){
													echo 'Query Run Successful';
													$link2 = connect('all_users');
													echo '<br>'.mysqli_error($link2);
													$table_create_query = "CREATE TABLE $id (
																			date VARCHAR(10),
																			breakfast VARCHAR(100),
																			lunch VARCHAR(100),
																			dinner VARCHAR(100),
																			carbo FLOAT,
																			protein FLOAT,
																			fat FLOAT,
																			calories FLOAT
																			)";
													echo '<br>'.$table_create_query.'<br>';
													if($table_create_query_run = mysqli_query($link2,$table_create_query)){
														echo 'Table Created';
													}else{
														echo die('Error 105:Could Not Create Table<br>'.mysqli_error($link2));
													}
										}else{
											
													die('Error 104: Insert Query could not run<br>'.mysqli_error($link));
										}
						  }
    }
    else{
      die('Error 103: Registration Query could not run<br>'.mysqli_error($link));
    }
  }
}


function protect_string($string){
	return mysql_real_escape_string(addslashes(htmlentities($string)));
}

?>