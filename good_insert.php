<?php
session_start();
require('dbconnect.php');

//if:セッションに数値形式で値が保存されているか調査 
if (is_numeric($_SESSION['id'])) {

    //if:リクエストにて受け取ったURLパラメータが数値形式で存在しているか調査
    if (is_numeric($_REQUEST['post_id'])) {
        //いいねを追加する
        $insert = $db->prepare('INSERT INTO goods SET member_id=?, post_id=?, created=NOW()');
        $insert->execute(array(
            $_SESSION['id'],
            $_REQUEST['post_id']
        ));
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
