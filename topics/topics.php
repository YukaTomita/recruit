<?php
  $articlesData = file_get_contents('topic-articles.json');
  $articles = json_decode($articlesData)->articles;

  $perPage = 20; // Number of articles per page
  $totalPages = ceil(count($articles) / $perPage); // Calculate total pages

  // Get page number from query string, default to 1
  $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

  // Calculate the starting and ending index for the current page
  $startIndex = ($currentPage - 1) * $perPage;
  $endIndex = min($startIndex + $perPage - 1, count($articles) - 1);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
    <meta name="description" content="株式会社MIST solution - トップページ 株式会社ミストソリューションは、異なった業界との接点を持つことで化学反応を起こし、
    幅広いニーズにより的確にお応えできる、常に進化しているIT企業です。">
    <meta name="keywords" content="株式会社ミストソリューション,ミストソリューション,MISTsolution,ミスト" />
    <meta name="copyright" content="© 1997, 2023 mistsolution. All Rights Reserved.">
    <meta name="format-detection" content="telephone=no">
    <!-- OGP -->
    <meta property="og:url" content="https://www.mistnet.co.jp">
    <meta property="og:title" content="株式会社MIST solution | WEBサイト"/>
    <meta property="og:site_name" content="株式会社MIST solution | WEBサイト">
    <meta name="og:description" content="株式会社MIST solution - トップページ 株式会社ミストソリューションは、異なった業界との接点を持つことで化学反応を起こし、
    幅広いニーズにより的確にお応えできる、常に進化しているIT企業です。">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ja-JP">
    <meta property="og:image" content="assets/img/mist-ogp.jpg">
    <meta name="twitter:card" content="summary"/>
    <!-- favicon -->
    <link rel="icon" href="img/favicon.ico">    
    <title>日々の出来事</title>
    <!--css-->
    <base href="/recruit/">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/common.css" type="text/css">
    <link rel="stylesheet" href="css/articles.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<!--header-->
<header id="header"></header>
<div class="header_space"></div>

<!--main-->
<main class="main"> 
<!-- Title-band -->
<div class="bg-gray">
    <div class="wrapper">
        <div class="gap-control-s"></div>
        <div class="f-word-r">
            私たちの日々の出来事<span class="f-word-b">／トピックス</span>
        </div>
        <div class="gap-control-s"></div>
    </div>
</div>
<div class="wrapper">
    <nav>
        <ul class="breadcrumbs">
            <li class="breadcrumbs-li"><a href="index.html">TOP</a></li>
            <li class="breadcrumbs-li"><a href="#!"></a>トピックス</li>
            <li class="breadcrumbs-li"><a href="#!"></a>履歴</li>
        </ul>
    </nav>
    <div class="gap-control-s"></div>
    <!--コンテンツ内容-->
    <div class="text-left f-word-b">過去一覧</div>
    <div class="gap-control-s"></div>
    <!--記事一覧-->
    <ul id="article-list" class="article-list">
        <?php
            for ($i = $startIndex; $i <= $endIndex; $i++) {
                $article = $articles[$i];
                echo '<li class="article">';
                echo '<div class="article-image">';
                echo '<img src="topics/images/' . $article->image . '" alt="Article Image">';
                echo '</div>';
                echo '<div class="article-content">';
                echo '<div class="article-date">' . date('Y.m.d', strtotime($article->date)) . '</div>';
                echo '<h2 class="article-title">' . $article->title . '</h2>';
                echo '</div>';
                echo '<a href="articles/' . $article->url . '" class="read-more">もっと見る &gt;</a>';
                echo '</li>';
            }
        ?>
    </ul>
    <div class="gap-control"></div>
    <!-- Pager -->
    <div class="pager">
        <?php
        echo '<a href="?page=' . max($currentPage - 1, 1) . '">＜</a> '; // Previous page link

        for ($page = 1; $page <= $totalPages; $page++) {
            echo '<a href="?page=' . $page . '" class="' . ($page === $currentPage ? 'current' : '') . '">' . $page . '</a> ';
        }

        echo '<a href="?page=' . min($currentPage + 1, $totalPages) . '">＞</a>'; // Next page link
        ?>
    </div>
    <div class="gap-control"></div>
</div>
<!--footer-->
<footer id="footer"></footer>
<script>
    document.getElementById("backButton").addEventListener("click", function() {
    history.back();
});
</script>
</body>
</html>    