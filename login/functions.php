<?php
function get_post($con, $var){
  return $con->real_escape_string($_POST[$var]); 
}
function clean_str($var){
  $var = htmlentities($var);
  $var = strip_tags($var);
  return stripslashes($var);
}
function val_regno($var){
  if(strlen($var) != 9)
    return false;
  else
    return true;
}
function val_phone($var){
  if(strlen($var) != 11)
    return false;
  else
    return true;
}
function check_pw($var1, $var2){
  if($var1 !== $var2)
    return false;
  else
    return true;
}
?>