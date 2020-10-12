<?php 
session_start();
require('dbconnect.php');

//if:セッションに数値形式で値が保存されているか調査 
if (is_numeric($_SESSION['id'])) {
    //リツイートを追加する
    $rtCopys = $db->prepare('SELECT * FROM posts WHERE id=?');
    $rtCopys->execute(array($_REQUEST['post_id']));
	$rtCopy = $rtCopys->fetch();

    $insert = $db->prepare('INSERT INTO posts SET message=?, member_id=?, rt_post_id=?, rt_member_id=?, created=NOW()');
    $insert->execute(array(
        $rtCopy['message'],
        $rtCopy['member_id'],
        $_REQUEST['post_id'],
        $_SESSION['id']
    ));
}

header('Location: index.php');
exit();
?>
