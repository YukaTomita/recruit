<?php session_start(); ?>
<?php 
    // フォームから送信されたデータを各変数に格納
    $last_name = $_POST["last-name"];
    $first_name = $_POST["first-name"];
    $klast_name = $_POST["klast-name"];
    $kfirst_name = $_POST["kfirst-name"];
    $experience = $_POST["experience"];
    $email = $_POST["email"];
    $interview = $_POST["interview"];
    $role = $_POST["role"];
    $message = $_POST["message"];

    // 送信ボタンが押されたら
    if (isset($_POST["submit"])) {
        // 送信ボタンが押された時に動作する処理をここに記述する

        // 日本語をメールで送る場合のおまじない
mb_language("ja");
mb_internal_encoding("UTF-8");

// --ヘッダー情報を設定--
//メール形式
$header = "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
//メールの返信先のアドレス
$header .= "Return-Path:MISTsolution採用担当 <r_pr@mistnet.co.jp>\n";
//送信者の名前（または組織名）とメールアドレス
$header .= "From:MISTsolution採用担当 <r_pr@mistnet.co.jp>\n";
// $header .= "\r\n";//消すやつ
//送信者の名前（または組織名）とメールアドレス
$header .= "Sender:MISTsolution採用担当 <r_pr@mistnet.co.jp>\n";
//受け取った人に表示される返信の宛先
$header .= "Reply-To:MISTsolution採用担当 <r_pr@mistnet.co.jp>\n";

//応募者用自動返信メール件名
$auto_reply_subject = "ご応募ありがとうございます";

//自動返信メール本文
$auto_reply_text = "この度は、ご応募頂き誠にありがとうございます。\n下記の内容でご応募を受け付けました。\n採用担当より5営業日以内に折り返しご連絡させていただきます。\n\n";
$auto_reply_text .= "ご応募日時：" .date_default_timezone_set('Asia/Tokyo'). date("Y-m-d H:i") . "\n\n";
$auto_reply_text .= "お名前：$last_name . ' ' . $first_name \n";
$auto_reply_text .= "フリガナ：$klast_name . ' ' . $kfirst_name\n";
$auto_reply_text .= "経験年数：" . $experience . "\n";
$auto_reply_text .= "メールアドレス：" . $email . "\n";
$auto_reply_text .= "面談方式：" . $interview . "\n";
$auto_reply_text .= "希望種別：" . $role . "\n";
$auto_reply_text .= "備考：" . $message . "\n";
$auto_reply_text .= "履歴書・職務経歴書：" . $filename . "\n";
$auto_reply_text .= "個人情報の取り扱いについて：" . $agree . "\n\n";
$auto_reply_text .= "MISTsolution 採用担当";

// 応募者用自動返信メール用テキストメッセージを記述
$body = "--__BOUNDARY__\n";
$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
$body .= $auto_reply_text . "\n";
$body .= "--__BOUNDARY__\n";

// 応募者用自動返信メール用メール送信
mb_send_mail($email, $auto_reply_subject, $body, $header);

// 管理者確認用メールの件名
$admin_reply_subject = "リクルートサイトより応募を受け付けました";

// 管理者確認用メール本文
$admin_reply_text = "下記内容で応募がありました。\n\n";
$admin_reply_text .= "応募日時：" . date_default_timezone_set('Asia/Tokyo'). date("Y-m-d H:i") . "\n\n";
$admin_reply_text .= "お名前：$last_name . ' ' . $first_name \n";
$admin_reply_text .= "フリガナ：$klast_name . ' ' . $kfirst_name\n";
$admin_reply_text .= "経験年数：" . $experience . "\n";
$admin_reply_text .= "メールアドレス：" . $email . "\n";
$admin_reply_text .= "面談方式：" . $interview . "\n";
$admin_reply_text .= "希望職種：" . $role . "\n";
$admin_reply_text .= "備考：" . $message . "\n";
$admin_reply_text .= "履歴書・職務経歴書：" . $filename . "\n";
$admin_reply_text .= "個人情報の取り扱いについて：" . $agree . "\n\n";

// 管理者確認用テキストメッセージを記述
$body = "--__BOUNDARY__\n";
$body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
$body .= $admin_reply_text . "\n";
$body .= "--__BOUNDARY__\n";
$body .= "Content-Type: application/octet-stream; name= \"recruit-".date('Y-m-d').".pdf\"\n";  
$body .= "Content-Disposition: attachment; filename= \"recruit-".date('Y-m-d').".pdf\"\n";
$body .= "Content-Transfer-Encoding: base64\n";
$body .= "\n";
$body .= chunk_split(base64_encode(file_get_contents('./attachment/'.$_POST['input_file'])))."\n";
$body .= "--__BOUNDARY__--";

// 管理者確認用メール送信
if(mb_send_mail( 'r_pr@mistnet.co.jp', $admin_reply_subject, $body, $header)){
    header("Location: ../entry/thanks.html");
} else {
    header("Location: ../entry/failure.html");
}
    exit;
}
?>
<html>
<head>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-159140072-1');
</script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>ENTRY確認</title>
<meta name="keywords" content="株式会社ミストソリューション,ミストソリューション,採用情報,recruite,MISTsolution"/>
<meta name="description" content="MISTsolutionRecruit - ENTRY確認">
<meta name="copyright" content="Copyright (C) 株式会社ミストソリューション." />
<!-- OGP -->
<meta property="og:url" content="https://www.mistnet.co.jp">
<meta property="og:title" content="株式会社MISTsolution Recruit | WEBサイト"/>
<meta property="og:site_name" content="株式会社MISTsolution Recruit | WEBサイト">
<meta name="og:description" content="株式会社MISTsolution Recruit - ENTRY FORM エンジニアがエンジニアとして、
イイカオで、ラクに、そして、思い通りに働ける、。">
<meta property="og:type" content="website">
<meta property="og:locale" content="ja-JP">
<meta property="og:image" content="assets/images/mist-rec_ogp.png">
<meta name="twitter:card" content="summary"/>

<!-- Bootstrap -->
<link rel="stylesheet" href="../css/common.css" type="text/css">
<link rel="stylesheet" href="../css/entry.css" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<link rel="icon" href="../img/favicon.ico">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
<!-- 入力内容 -->
<section>
<form action="confirm.php" method="post">
    <input type="hidden" name="name" value="<?php echo $last_name . ' ' . $first_name; ?>">
    <input type="hidden" name="furigana" value="<?php echo $klast_name . ' ' . $kfirst_name; ?>">
    <input type="hidden" name="experience" value="<?php echo $experience; ?>">
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="hidden" name="tel" value="<?php echo $tel; ?>">
    <input type="hidden" name="interview" value="<?php echo $interview; ?>">
    <input type="hidden" name="role" value="<?php echo $role; ?>">
    <input type="hidden" name="message" value="<?php echo $message; ?>">
        <div class="entry-wrap">
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">お名前</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo $last_name . ' ' . $first_name; ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">フリガナ</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo $klast_name . ' ' . $kfirst_name; ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">経験年数</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo $experience ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">メールアドレス</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo $email ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">面談方式</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo $interview ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">希望職種</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo $role ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">スキルシート</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3">

                <?php
        // POST リクエストからスキル情報を取得
        if (isset($_POST['language'])) {
            $languages = $_POST['language'];
            $levels = $_POST['level'];
            $experiences = $_POST['experience'];

            // ループを使用してデータを表示
            $numSkills = count($languages);
            for ($i = 0; $i < $numSkills; $i++) {
                $language = $languages[$i];
                $level = isset($levels[$i]) ? $levels[$i] : "未入力";
                $experience = isset($experiences[$i]) ? $experiences[$i] : "未入力";

                echo "言語: " . $language . "　" . "レベル: " . $level . "　" . "経験: " . $experience . "<br>";
            }
        } else {
            echo "スキル情報が未入力です。";
        }
        ?>                </label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">添付ファイル</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3">
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // ファイルがアップロードされた場合
                        if (isset($_FILES['input_file']) && $_FILES['input_file']['error'] === UPLOAD_ERR_OK) {
                            $uploadedFile = $_FILES['input_file']['tmp_name'];
                            
                            // アップロードされたファイルの情報を表示
                            echo  $_FILES['input_file']['name'];

                            // ファイルを指定のディレクトリに移動する
                            $targetDirectory = 'C:\xampp\htdocs\mist_recruit\test'; // 保存先のディレクトリを指定
                            $targetFile = $targetDirectory . $_FILES['input_file']['name'];

                            // ファイルを指定のディレクトリに移動
                            if (move_uploaded_file($uploadedFile, $targetFile)) {
                                // ここで確認画面に表示するか、その他の処理を行います。
                            } else {
                                echo "ファイルの移動に失敗しました。<br>";
                            }
                        } else {
                            echo "ファイルのアップロードに失敗しました。<br>";
                        }
                    } else {
                        echo "無効なリクエストです。<br>";
                    }
                ?>
                </label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">備考</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3"><?php echo nl2br($message); ?></label>
            </div>
            <div class="form-row">
                <div class="bullet-point"></div>
                <label class="flex-basis1">個人情報の取り扱いについて</label>
                <label class="flex-basis2"></label>
                <label class="flex-basis3">同意する</label>
            </div>
            <div class="gap-control-s"></div>
            <button type="submit" class="send-button" name="submit">送信する</button>
            <input type="button" class="back-button" value="内容を修正する" onclick=history.back()>
            <div class="gap-control"></div>
        </div>
</form>
</section>
</div><!-- wrapper div -->

<!-- footer -->
<div class="footer-area">
    <small>&copy; 1997,2023 mistsolution.All Rights Reserved.</small>
</div>
</body>
</html>
