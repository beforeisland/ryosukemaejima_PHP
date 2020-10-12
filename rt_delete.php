<?php 
session_start();
require('dbconnect.php');

//if:セッションに数値形式で値が保存されているか調査 
if(is_numeric($_SESSION['id'])) {

    //if:リクエストにて受け取ったURLパラメータが数値形式で存在しているか調査
    if(is_numeric($_REQUEST['rt_id'])) {

        //リツイート投稿を検査する
        $rts = $db->prepare('SELECT * FROM posts WHERE id=?');
        $rts->execute(array($_REQUEST['rt_id']));
        $rt= $rts->fetch();

        //if:ログイン者がリツイート投稿者と一致するか調査
        if($rt['rt_member_id'] == $_SESSION['id']) {
            //リツイートを削除する
            $del = $db->prepare('DELETE FROM posts WHERE id=?');
            $del->execute(array($_REQUEST['rt_id']));
        } else {
            echo 'error:ログイン者とリツイート投稿者と一致しません';
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
