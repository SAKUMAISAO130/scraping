<?php

/* HTML特殊文字をエスケープする関数 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

# $_GET['q'] を文字列として受け取る (未定義の場合は空文字列になる)
/*
$q = (string)filter_input(INPUT_GET, 'q');
*/

# $_GET['q'] を文字列として受け取る (未定義の場合は空文字列になる)
$q = "筋肉";

# $_GET['q'] が空文字列でなかった場合にのみ検索を行う
if ($q !== '') {

    // エラーを出さずにHTMLをDOMDocumentに読み込む
    $dom = new DOMDocument;
    @$dom->loadHTMLFile('http://www.amazon.co.jp/s/field-keywords=' . urlencode($q));

    // DOMDocumentからXPath式を実行するためのDOMXPathを生成
    $xpath = new DOMXPath($dom);

    // class属性値にs-result-itemを含むli要素を全ノードから検索する
    foreach ($xpath->query('//li[contains(@class, "s-result-item")]') as $li) {
        // class属性値に所定の値を含む所定の要素を各li要素の中から検索する
        // 各情報をまとめて配列にし、更にその配列を$items配列の要素として代入する
        $items[] = [
            'title' => $xpath->evaluate('string(.//h2[contains(@class, "s-access-title")])', $li),
            'image' => $xpath->evaluate('string(.//img[contains(@class, "s-access-image")]/@src)', $li),
            'price' => $xpath->evaluate('string(.//span[contains(@class, "s-price")])', $li),
            'url'   => $xpath->evaluate('string(.//a[contains(@class, "s-access-detail-page")]/@href)', $li),
        ];
    }

}

# 文字コードUTF-8のHTML文書であるとしてブラウザに知らせる
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Amazon Scraping Sample</title>
        <style>
            article {
                padding: 10px 5px;
            }
            article:after {
                content: "";
                clear: both;
                height: 0;
                display: block;
                visibility: hidden;
            }
            article + article {
                border-top: 1px solid #CCC;
            }
            img {
                float: left;
            }
            .price {
                color: orange;
            }
        </style>
    </head>
    <body>
<!--
        <section>
            <h1>検索フォーム</h1>
            <form method="get" action="">
                <input type="text" name="q" value="<?=h($q)?>">
                <input type="submit" value="検索">
            </form>
        </section>
-->
<?php if (!empty($items)): ?>
        <section>
            <h1>Amazonの筋肉</h1>
<?php foreach ($items as $item): ?>
            <article>
                <a href="<?=h($item['url'])?>" target="_blank"><img src="<?=h($item['image'])?>"></a><br>
                <a href="<?=h($item['url'])?>" target="_blank"><?=h($item['title'])?></a><br>
                <span class="price"><?=h($item['price'])?></span><br>
            </article>
            <?php include('../pdo_php/index.php'); ?>
<?php endforeach; ?>
        </section>
<?php endif; ?>
    </body>
</html>