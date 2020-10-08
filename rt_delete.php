<?php 
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
	$rt_id = $_REQUEST['rt_id'];

	//リツイート投稿を検査する
	$rts = $db->prepare('SELECT * FROM posts WHERE id=?');
	$rts->execute(array($rt_id));
	$rt= $rts->fetch();

	if($rt['rt_member_id'] == $_SESSION['id']) {
		//リツイートを削除する
		$del = $db->prepare('DELETE FROM posts WHERE id=?');
		$del->execute(array($rt_id));
	}
}

header('Location: index.php');
exit();
?>