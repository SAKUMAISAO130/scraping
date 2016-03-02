<?php



#接続設定
$dsn = 'mysql:dbname=sakuma_press;host=localhost';
$user = 'root';
$password = 'root';

#接続
try{
    $dbh = new PDO($dsn, $user, $password);

#接続確認
/*
    if ($dbh == null){
        print('接続に失敗しました。<br>');
    }else{
        print('接続に成功しました。<br>');
    }
*/
#文字コード指定
    $dbh->query('SET NAMES utf8');

#書き込み前のデータ出力記述
/*
    $sql = 'SELECT * FROM wp_posts LIMIT 0 , 30';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        var_dump($result);
    }
*/

#sql作成とmysqlへデータの書き込み
    $sql = 'INSERT INTO wp_posts (ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
    VALUES (null, 1, "2016-02-29 08:16:38", "2016-02-28 23:16:38","' . h($item['price']) . '<br>' . h($item['title']) . '<br><img src="' . h($item['image']) . '" alt=""><br><a href="' . h($item['url']) . '">' . h($item['title']) . '</a><br>' . '", "Hello world!", "", "publish", "open", "open", "", "hello-world", "", "", "2016-02-29 08:16:38", "2016-02-28 23:16:38", "", 0, "http://localhost:8888/scraping_site/wp/?p=1", 0, "post", "", 1)';
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute();

#書き込み確認
    if ($flag){
        print('データの追加に成功しました<br>');
    }else{
        print('データの追加に失敗しました<br>');
    }
#追加後のデータ一覧出力
/*
    $sql = 'SELECT * FROM wp_posts LIMIT 0 , 30';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        var_dump($result);
    }
*/

#処理が通らなかった場合dieする
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}

#データベースとの接続解除
$dbh = null;

?>

