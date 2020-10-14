<?php
session_start();
require('dbconnect.php');

////SQL:リツイート対象の投稿が存在するか探し出す
$rtSearches = $db->prepare('SELECT * FROM posts WHERE id=?');
$rtSearches->execute(array($_REQUEST['post_id']));
$rtSearch = $rtSearches->fetch();

//if:セッションに数値形式で値が保存されているか調査 
if (is_numeric($_SESSION['id'])) {

    //if:リクエストにて受け取ったURLパラメータが数値形式で存在しているか調査
    if (is_numeric($_REQUEST['post_id'])) {

        //if:リツイート対象の投稿が存在すればいいね追加
        if (isset($rtSearch['id'])) {
            //リツイートを追加する
            $insert = $db->prepare('INSERT INTO posts SET message=?, member_id=?, rt_post_id=?, rt_member_id=?, created=NOW()');
            $insert->execute(array(
                $rtSearch['message'],
                $rtSearch['member_id'],
                $_REQUEST['post_id'],
                $_SESSION['id']
            ));
        } else {
            echo 'error:リツイート元投稿が存在しません';
            exit();
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
