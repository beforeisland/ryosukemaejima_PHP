<?php 
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
    $member_id = $_SESSION['id'];
	$post_id = $_REQUEST['post_id'];

    //いいねを追加する
    $insert = $db->prepare('INSERT INTO good SET member_id=?, post_id=?, created=NOW()');
    $insert->execute(array(
        $_SESSION['id'],
        $_REQUEST['post_id']
    ));
}

header('Location: index.php');
exit();
?>