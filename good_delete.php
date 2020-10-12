<?php 
session_start();
require('dbconnect.php');

//if:セッションに数値形式で値が保存されているか調査 
if (is_numeric($_SESSION['id'])) {

	//if:リクエストにて受け取ったURLパラメータが数値形式で存在しているか調査
	if(is_numeric($_REQUEST['good_id'])) {

		//いいね投稿を検査する
		$goods = $db->prepare('SELECT * FROM goods WHERE good_id=?');
		$goods->execute(array($_REQUEST['good_id']));
		$good= $goods->fetch();

		//if:ログイン者がいいね投稿者と一致するか調査
		if($good['member_id'] == $_SESSION['id']) {
			//いいねを削除する
			$del = $db->prepare('DELETE FROM goods WHERE good_id=?');
			$del->execute(array($_REQUEST['good_id']));
		} else {
			echo 'error:ログイン者といいね投稿者が一致しません';
		}

	} else {
		echo 'error:URLパラメータが不正です';
        exit();
	}

} else {
	echo 'error:セッションにidが保存されていません';
    exit();
}

header('Location: index.php');
exit();
?>
