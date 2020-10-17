<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    //ログインしていない
    header('Location: login.php');
    exit();
}

//自分発信の投稿を記録する
if (!empty($_POST)) {
    if ($_POST['message'] != '') {
        $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id=?, created=NOW() ');
        $message->execute(array(
            $member['id'],
            $_POST['message'],
            $_POST['reply_post_id']
        ));

        header('Location: index.php');
        exit();
    }
}

//投稿を取得する
$page = ($_REQUEST['page'] ?? FALSE);
if ($page == '') {
    $page = 1;
}
$page = max($page, 1);

//最終ページを取得する
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?, 5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();


//返信の場合
if (isset($_REQUEST['res'])) {
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $message = '@' . $table['name'] . ' ' . $table['message'];
}

// htmlspecialcharsのショートカット
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

//本文内のURLにリンクを設定します
function makeLink($value)
{
    return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);
}


//いいねの取得
$goods = $db->prepare('SELECT p.id, g.good_id, g.member_id, g.post_id FROM posts p, goods g WHERE p.id=g.post_id AND p.id=? AND g.member_id=?');

//リツイート投稿に対する元ツイートのいいねの取得
$rtGoods = $db->prepare('SELECT p.id, g.good_id, g.member_id, g.post_id FROM posts p, goods g WHERE p.id=g.post_id AND p.id=? AND g.member_id=?');

//いいね数の取得
$goodCounts = $db->prepare('SELECT COUNT(post_id) AS goodcnt FROM goods WHERE post_id=?');

//リツイート投稿に表示するいいね数の取得
$rtGoodCounts = $db->prepare('SELECT COUNT(post_id) AS rtgoodcnt FROM goods WHERE post_id=?');

//リツイート投稿を特定する
$rtSearches = $db->prepare('SELECT p.* FROM posts p WHERE p.rt_post_id=? AND p.rt_member_id=?');

//リツイート元投稿を特定する
$originalRtSearches = $db->prepare('SELECT p.* FROM posts p WHERE p.rt_post_id=? AND p.rt_member_id=?');

//リツイート投稿のリツイート者名の取得
$rtMembers = $db->prepare('SELECT m.name, p.* FROM posts p LEFT JOIN members m ON p.rt_member_id=m.id AND p.id=?');

//リツイート投稿のリツイート数の取得
$rtCounts = $db->prepare('SELECT COUNT(rt_post_id) AS rtcnt FROM posts WHERE rt_post_id=?');

//リツイート元投稿のリツイート数の取得
$originalRtCounts = $db->prepare('SELECT COUNT(rt_post_id) AS ortcnt FROM posts WHERE rt_post_id=?');


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ひとこと掲示板</title>

    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>ひとこと掲示板</h1>
        </div>
        <div id="content">
            <div style="text-align: right">
                <a href="logout.php">ログアウト</a>
            </div>
            <form action="" method="post">
                <dl>
                    <dt><?php echo h($member['name']); ?>さん、メッセージをどうぞ</dt>
                    <dd>
                        <textarea name="message" cols="50" rows="5"><?php echo h($message ?? FALSE); ?></textarea>
                        <input type="hidden" name="reply_post_id" value="<?php echo h($_REQUEST['res']); ?>">
                    </dd>
                </dl>
                <div>
                    <input type="submit" value="投稿する" />
                </div>
            </form>

            <?php
            foreach ($posts as $post) :
            ?>
                <div class="msg">

                    <?php
                    $rtMembers->execute(array($post['id']));
                    $rtMember = $rtMembers->fetch();
                    //リツイート投稿者の表示
                    if ($post['rt_post_id'] > 0) :
                    ?>
                        <p><?php echo h($rtMember['name']); ?>さんがリツイート</p>
                    <?php endif; ?>


                    <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>">
                    <p>
                        <?php echo makeLink(h($post['message'])); ?>
                        <span class="name">(<?php echo h($post['name']); ?>)</span>
                        [<a href="index.php?res=<?php echo h($post['id']); ?>">Re</a>]
                    </p>
                    <p class="day">
                        <a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>
                        <?php if ($post['reply_post_id'] > 0) : ?>

                            <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元のメッセージ</a>
                        <?php endif; ?>
                        <?php if ($post['rt_post_id'] <= 0) {
                            if ($_SESSION['id'] === $post['member_id']) : ?>
                                [<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">削除</a>]
                        <?php endif;
                        }
                        ?>
                    </p>
                    <div class="rt_iine" style="display: flex;">
                        <div>
                            <?php
                            //いいね機能の実装
                            $goods->execute(array($post['id'], $_SESSION['id']));
                            $good = $goods->fetch();

                            $rtGoods->execute(array($post['rt_post_id'], $_SESSION['id']));
                            $rtGood = $rtGoods->fetch();

                            if ($post['rt_post_id'] > 0) {
                                //if:リツイート元投稿に自分のいいねがあるかどうか確認「ログイン者か否か、そのリツイート元投稿にいいねが存在しているか」条件文
                                if ((($_SESSION['id'] ?? FALSE) === ($rtGood['member_id'] ?? FALSE)) && (($rtGood['post_id'] ?? FALSE) === ($post['rt_post_id'] ?? FALSE))) : ?>
                                    <a href="good_delete.php?good_id=<?php echo h($rtGood['good_id']); ?>"><img src="images/good_on_icon.png" width="20px" height="20px"></a>
                                <?php else : ?>
                                    <a href="good_insert.php?post_id=<?php echo h($post['rt_post_id']); ?>"><img src="images/good_off_icon.png" width="20px" height="20px"></a>
                                <?php endif;
                            } else {
                                //if:自分のいいねがあるかどうか確認「ログイン者か否か、その投稿にいいねが存在しているか」条件文 
                                if ((($_SESSION['id'] ?? FALSE) === ($good['member_id'] ?? FALSE)) && (($good['post_id'] ?? FALSE) === ($post['id'] ?? FALSE))) : ?>
                                    <a href="good_delete.php?good_id=<?php echo h($good['good_id']); ?>"><img src="images/good_on_icon.png" width="20px" height="20px"></a>
                                <?php else : ?>
                                    <a href="good_insert.php?post_id=<?php echo h($post['id']); ?>"><img src="images/good_off_icon.png" width="20px" height="20px"></a>
                            <?php endif;
                            }
                            ?>


                            <?php
                            //いいね数カウント
                            $goodCounts->execute(array($post['id']));
                            $goodCount = $goodCounts->fetch();

                            //リツイート投稿に対するいいね数カウント
                            $rtGoodCounts->execute(array($post['rt_post_id']));
                            $rtGoodCount = $rtGoodCounts->fetch();

                            if ($post['rt_post_id'] > 0) {
                                echo $rtGoodCount['rtgoodcnt'];
                            } else {
                                echo $goodCount['goodcnt'];
                            } ?>
                        </div>

                        <div>
                            <?php
                            //リツイート機能の実装
                            $rtSearches->execute(array($post['rt_post_id'], $_SESSION['id']));
                            $rtSearch = $rtSearches->fetch();

                            $originalRtSearches->execute(array($post['id'], $_SESSION['id']));
                            $originalRtSearch = $originalRtSearches->fetch();

                            if ($post['rt_post_id'] > 0) {
                                //if:リツイート投稿かつ自分がリツイートしたもの(削除する) 
                                if (($_SESSION['id'] ?? FALSE) === ($post['rt_member_id'] ?? FALSE)) : ?>
                                    <a href="rt_delete.php?rt_id=<?php echo h($rtSearch['id']); ?>"><img src="images/rt_on_icon.png" width="20px" height="20px"></a>
                                <?php //if:リツイート投稿かつ自分以外がリツイートしたもののうち、自分がリツイート済みのもの
                                elseif ($rtSearch['id'] ?? FALSE) : ?>
                                    <a href="rt_delete.php?rt_id=<?php echo h($rtSearch['id']); ?>"><img src="images/rt_on_icon.png" width="20px" height="20px"></a>
                                <?php //if:リツイート投稿かつ自分以外がリツイートしたもののうち、未リツイートのもの
                                else : ?>
                                    <a href="rt_insert.php?post_id=<?php echo h($post['rt_post_id']); ?>"><img src="images/rt_off_icon.png" width="20px" height="20px"></a>

                                <?php endif;
                            } else {
                                //if:オリジナル投稿かつ自分がリツイート済みのもの
                                if ($originalRtSearch['id'] ?? FALSE) : ?>
                                    <a href="rt_delete.php?rt_id=<?php echo h($originalRtSearch['id']); ?>"><img src="images/rt_on_icon.png" width="20px" height="20px"></a>
                                <?php //オリジナル投稿かつ未リツイートのもの 
                                else : ?>
                                    <a href="rt_insert.php?post_id=<?php echo h($post['id']); ?>"><img src="images/rt_off_icon.png" width="20px" height="20px"></a>

                            <?php endif;
                            }
                            ?>
                            <?php
                            //リツイート投稿に対するリツイート数取得
                            $rtCounts->execute(array($post['rt_post_id']));
                            $rtCount = $rtCounts->fetch();

                            //リツイート元投稿に対するリツイート数取得
                            $originalRtCounts->execute(array($post['id']));
                            $originalRtCount = $originalRtCounts->fetch();

                            //リツイート数カウント
                            //リツイート投稿か否か
                            if ($post['rt_post_id'] > 0) {
                                echo $rtCount['rtcnt'];
                            } else {
                                echo $originalRtCount['ortcnt'];
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
            ?>

            <ul class="paging">
                <?php if ($page > 1) : ?>
                    <li><a href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
                <?php else : ?>
                    <li>前のページへ</li>
                <?php endif; ?>

                <?php if ($page < $maxPage) : ?>
                    <li><a href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
                <?php else : ?>
                    <li>次のページへ</li>
                <?php endif; ?>

            </ul>

        </div>

    </div>
</body>

</html>
