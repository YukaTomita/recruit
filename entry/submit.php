<?php session_start(); ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. 入力されたDATAを取得
    $last_name = $_POST["last-name"];
    $first_name = $_POST["first-name"];
    $klast_name = $_POST["klast-name"];
    $kfirst_name = $_POST["kfirst-name"];
    $experience = $_POST["experience"];
    $email = $_POST["email"];
    $interview = $_POST["interview"];
    $role = $_POST["role"];
    $message = $_POST["message"];
    
    // 3. スキルチェックシートの処理
    // スキルチェックシートは別の方法で取得する必要があります。
    // iframe内の内容にアクセスするためのスクリプトを追加
    
    // 4. アップロードされたファイルを取得
    if (isset($_FILES['uploaded_file'])) {
        $filename = $_FILES['uploaded_file']['name'];
        $tempPath = $_FILES['uploaded_file']['tmp_name'];
        $fileSize = $_FILES['uploaded_file']['size'];
        $fileType = $_FILES['uploaded_file']['type'];
    }


    // フォームデータの処理を続行
    // データベースへの保存やメールの送信などのアクションを追加
    
    // 処理が完了したらリダイレクトまたは適切なメッセージを表示
}
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
    <link rel="icon" href="../img/favicon.ico">    
    <title>エントリー - 確認画面</title>
    <!--css-->
    <link rel="stylesheet" href="../css/reset.css" type="text/css">
    <link rel="stylesheet" href="../css/common.css" type="text/css">
    <link rel="stylesheet" href="../css/entry.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/main.js"></script>
</head>
<body>
<!--header-->
<header id="header"></header>
<div class="header_space"></div>
<!--main-->
<main class="main"> 
<div class="wrapper">
    <div class="gap-control"></div>
    <p class="f-title text-center">エントリー確認画面</p>
    <div class="gap-control-s"></div>
    <div class="f-word-b">入力内容の確認</div>
    <div class="gap-control-s"></div>
    <div class="f-word-b">ご入力内容をご確認いただき、<br>よろしければ「送信する」ボタンを押して下さい</div>
    <div class="gap-control-s"></div>
        <div class="f-word-b">名前：<?php echo htmlspecialchars($last_name . ' ' . $first_name, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">カナ：<?php echo htmlspecialchars($klast_name . ' ' . $kfirst_name, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">経験年数：<?php echo htmlspecialchars($experience, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">メールアドレス：<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">面談形式：<?php echo htmlspecialchars($interview, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">希望種別：<?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">備考：<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="f-word-b">添付ファイル：<?php echo readfile($_FILES['uploaded_file']['name']); ?></div>
    <form method="post" action="send.php">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($last_name . ' ' . $first_name); ?>"> 
        <input type="hidden" name="kananame" value="<?php echo htmlspecialchars($klast_name . ' ' . $kfirst_name); ?>">
        <input type="hidden" name="experience" value="<?php echo htmlspecialchars($klast_name . ' ' . $kfirst_name); ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="interview" value="<?php echo $interview; ?>">
        <input type="hidden" name="role" value="<?php echo $role; ?>">
        <input type="hidden" name="message" value="<?php echo $message; ?>">
        <input type="submit" value="送信">
    <?php
        $records = []; // レコードを格納する配列を初期化

        if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
            $file = new SplFileObject($_FILES['uploaded_file']['tmp_name'], 'r');
            $file->setFlags(SplFileObject::READ_CSV);

            foreach ($file as $row) {
                if (!empty($row)) { // 空の行をスキップ
                    $records[] = $row;
                }
            }
        }

        // $records 配列には、CSVファイルの各レコードが格納されています。
    ?>
        <button type="button" onclick="history.back()">戻る</button>
    </form>
</div>
</main>
</body>
</html>