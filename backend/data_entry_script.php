//DATA ENTRY SCRIPT
<?php
require_once('connect.php');

$user_link = connect('all_users');

$max = 31;

for($i=1;$i<=$max;$i++){
  $date = "2014-01-$i";

  $breakfast='4.1045,1.1065';
  $lunch = '2.1023,3.1065,1.1071';
  $dinner = '3.1071,2.1023,4.1012';

  $insert_query = "INSERT INTO `f2012077` VALUES('$i','$date','$breakfast','$lunch','$dinner',0,0,0,0)";
  echo '<br><br>INSERT QUERY:'.$insert_query.'<br><br>';

  if($insert_query_run = mysqli_query($user_link,$insert_query)){
    echo 'QUERY RUN SUCCESSFUL';
  }else{
    echo mysqli_error($user_link);
  }
}

?>