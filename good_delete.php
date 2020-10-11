<?php 
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
	$good_id = $_REQUEST['good_id'];

	//いいね投稿を検査する
	$goods = $db->prepare('SELECT * FROM goods WHERE good_id=?');
	$goods->execute(array($good_id));
	$good= $goods->fetch();

	if($good['member_id'] == $_SESSION['id']) {
		//いいねを削除する
		$del = $db->prepare('DELETE FROM goods WHERE good_id=?');
		$del->execute(array($good_id));
	}
}

header('Location: index.php');
exit();
?>