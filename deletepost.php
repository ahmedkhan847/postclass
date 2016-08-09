<?php 
//It autoloads the class
include 'postclass.php';

$ids = $_GET['postid'];

$del = new Post();

$result = $del->deletearticle($ids);

if($result === true)
{
	echo "Post Is Deleted Successfully";
}
else
{
	echo $result;
}




?>