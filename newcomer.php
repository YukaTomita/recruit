<?php
// データベース接続情報
$host = 'localhost';
$db = 'sport';
$user = 'root';
$password = 'root';

// データベースに接続
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

// タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');


// 投票結果のリセット処理、1分間ボタンが押せなくなる、指定した時間に開いていないとリセットされない
if (date('H:i') === '03:00') {
    // 投票数をゼロにリセットするクエリを実行
    $resetQuery = "TRUNCATE TABLE votes";
    $resetStmt = $conn->prepare($resetQuery);
    $resetStmt->execute();

    // 投票履歴を削除するクエリを実行
    $deleteQuery = "TRUNCATE TABLE votes_history";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute();
}

// スポーツの一覧を取得
$query = "SELECT * FROM sports";
$stmt = $conn->prepare($query);
$stmt->execute();
$sports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedSport = $_POST['sport'];

    // 投票履歴をチェック
    $userIp = $_SERVER['REMOTE_ADDR'];
    $query = "SELECT * FROM votes_history WHERE user_ip = :user_ip";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_ip', $userIp);
    $stmt->execute();
    $voteHistory = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastVotingDate = $stmt->fetchColumn();

    if (!$voteHistory) {
        // 投票結果をデータベースに保存
        $query = "INSERT INTO votes (sport_id) VALUES (:sport_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sport_id', $selectedSport);
        $stmt->execute();

        // 投票履歴を保存
        $query = "INSERT INTO votes_history (user_ip, sport_id) VALUES (:user_ip, :sport_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_ip', $userIp);
        $stmt->bindParam(':sport_id', $selectedSport);
        $stmt->execute();
    }

    // ページをリロードして再投稿を防止するためのリダイレクト
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 投票結果の取得とランキングの作成
$query = "SELECT sport_id, COUNT(*) AS count FROM votes GROUP BY sport_id ORDER BY count DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$voteResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ランキングデータの生成
$ranking = [];
$rank = 1;
$prevCount = null;
foreach ($voteResults as $result) {
    $sportId = $result['sport_id'];
    $count = $result['count'];

    $sportName = "";
    foreach ($sports as $sport) {
        if ($sport['id'] == $sportId) {
            $sportName = $sport['name'];
            break;
        }
    }

    // 同率順位の場合、前の順位と投票数を比較して順位を設定
    if ($prevCount !== null && $prevCount !== $count) {
        $rank++;
    }

    $ranking[] = [
        'rank' => $rank,
        'sportName' => $sportName,
        'count' => $count
    ];

    $prevCount = $count;
}

// 投票済みかどうかをチェック
$userIp = $_SERVER['REMOTE_ADDR'];
$query = "SELECT * FROM votes_history WHERE user_ip = :user_ip";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_ip', $userIp);
$stmt->execute();
$voteHistory = $stmt->fetch(PDO::FETCH_ASSOC);

// 投票日取得：更新
$userIp = $_SERVER['REMOTE_ADDR'];
$query = "SELECT updated_at FROM votes_history WHERE user_ip = :user_ip ORDER BY updated_at DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_ip', $userIp);
$stmt->execute();
$lastVotingDateTime = $stmt->fetchColumn();

// データベース接続のクローズ
$conn = null;
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
    <title>新人向け</title>
    <!--css-->
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/common.css" type="text/css">
    <link rel="stylesheet" href="css/newcomer.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<!--header-->
<header id="header"></header>
<div class="header_space"></div>

<!--main-->
<main class="main"> 
<!-- トップ画像 -->
<div><img src="img/newcomer.jpg" class="img" alt=""></div>
<div class="gap-control"></div>

<div class="wrapper">
    <div class="gap-control-s"></div>
    <p class="f-title">質問</p>
    <hr class="border-line">
    <div class="gap-control-s"></div>
    <p class="f-word-r">あなたの好きなスポーツは何ですか？</p>
    <div class="gap-control-s"></div>

<!--Ranking Zone-->
    <div id="updateStatus">
        <div class="update-vote f-black wide-f">上位5つを更新中<span id="dots">...</span></div>
    </div>
    <script>
        // 点滅を制御する関数
        function toggleDots() {
            var dotsElement = document.getElementById('dots');
            var dotsText = dotsElement.innerHTML;

            // ドットが3つ未満の場合に新しいドットを追加
            if (dotsText.length < 3) {
                dotsElement.innerHTML += '.';
            } else {
                // 3つのドットが表示されたら最初からリセット
                dotsElement.innerHTML = '.';
            }
        }

        // 500ミリ秒ごとにtoggleDots関数を呼び出すタイマー
        setInterval(toggleDots, 500);
    </script>
    <p class="f-black wide-f">
        <?php
            if ($lastVotingDateTime) {
                // 最終投票日時を指定のフォーマットに変換して表示
                $formattedLastVotingDate = date('Y.m.d', strtotime($lastVotingDateTime));
                echo '更新: ' . $formattedLastVotingDate;
            } else {
                // 投票履歴がない場合の処理
                echo "まだ投票がありません。";
            }            
        ?>
    </p>
    <div class="gap-control"></div>
    <p class="f-large-r">現在のランキング</p>
    <?php if (!empty($ranking) && $voteHistory) : ?>

    <div class="ranking">
        <?php $count = 0; ?>
        <?php foreach ($ranking as $rankData) : ?>
            <?php if ($count >= 5) break; ?> <!-- 5つ以上の要素は表示しない -->
            <?php
                $rank = $rankData['rank'];
                $sportName = $rankData['sportName'];
                $imagePath = ''; // 画像のパスを指定する変数

            // 1位から3位までの場合に画像のパスを設定
            if ($rank === 1) {
                $imagePath = 'img/new1.png';
                $rankClass = 'rank-1'; /* 1位の場合のクラスを追加 */
            } elseif ($rank === 2) {
                $imagePath = 'img/new2.png';
                $rankClass = 'rank-2'; /* 2位の場合のクラスを追加 */
            } elseif ($rank === 3) {
                $imagePath = 'img/new3.png';
                $rankClass = 'rank-3'; /* 3位の場合のクラスを追加 */
            } else {
                $rankClass = ''; /* 1位から3位以外はクラスを空にする */
            }
            ?>

        <div class="bar-graph text-align <?php echo $rankClass; ?>">
            <!-- 画像を挿入 -->
            <?php if (!empty($imagePath)) : ?>
                <img src="<?php echo $imagePath; ?>" alt="<?php echo $rank; ?>位の画像" style="width: 40px; height: 30px;">
            <?php else : ?>
                <div style="width: 40px; height: 30px;"></div>
            <?php endif; ?>

            <p class="rank"><span><?php echo $rank; ?></span>位</p>
            <p class="sportName"><?php echo $sportName; ?></p>
        </div>
            <?php $count++; ?>
        <?php endforeach; ?>
    </div>
    <?php else : ?>
        <p class="ranking asterisk">※投票するとランキングが表示されます。</p>
    <?php endif; ?>

    <div class="gap-control"></div>

    <button class="cercle" id="rankingButton" onclick="toggleRanking()">ランキングに参加する</button>
    <div class="arrow-container">
        <div class="arrow-bottom"></div>
        <div class="arrow-bottom arrow-bottom-Shifted"></div>
    </div>
    <div class="gap-control-s"></div>
</div>
<!-- 投票欄 -->
    <div class="ranking-section" id="rankingSection" style="display:none">
        <div class="wrapper">
            <div class="font-style-comments2 line-height">
                <p class="v-text">「学生時代していた。」もしくは、「個人でしていた。」など、該当するスポーツを下記からお選びください。<br>（※複数されていた方は、一番長く在籍していたスポーツをお選びください。）</p>
                <div class="vote">
                    <?php if (!$voteHistory) : ?>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="radio-buttons">
                                <?php foreach ($sports as $sport) : ?>
                                    <label class="radio-column">
                                        <input type="radio" name="sport" value="<?php echo $sport['id']; ?>" onchange="this.form.submit()">
                                        <?php echo $sport['name']; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </form>
                    <?php else : ?>
                        <p class="asterisk">※すでに投票済みです。</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-control"></div>
</div>
<br><br>
<div class="wrapper">
    <div class="f-word-b txt">
        エンジニアに何故スポーツ？と思う方もいるかもしませんが、今回入社した若手エンジニアたちは、皆スポーツ経験者です。
        彼らの仕事に取り組む姿勢にスポーツでの経験が活かされています。
        エンジニアとしての実務経験がなかったり、短かったりしても、取り組む姿勢は強い武器になっています。
    </div>
    <div class="gap-control-l"></div>
    <div class="f-large-r">「求められるから頑張れる！」</div>
    <div class="gap-control-s"></div>
    <div class="sport-container">
        <div class="sport-image-container">
            <img src="img/NC1.jpg" alt="画像１" class="sport-main-image">
            <p class="sport-word f-word-b bolder">僕はラグビー<br>をしてました。</p>
            <p class="link f-white" onclick="togglePopup('popup1')">▶ もっと見る</p>
            <!-- ポップアップ -->
            <div class="sport-popup popup1">
                <div class="sport-popup-content">
                    <div class="sport-popup-title f-title text-left">「スポーツで培った習慣は<br>今の仕事に役立っていると感じる時があります！」</div>
                    <div class="sport-popup-info">
                        <img src="img/rugby.png" alt="ポップアップ画像" class="sport-popup-image">
                        <p class="sport-popup-comment f-word-b text-left">ラグビーでのチームワークが、プロジェクト進行のほうれんそうの習慣など、
                            業務に役立っていると思います。またラグビーは個々のポジションでも自分が何をしなければならないかなど
                            責任を果たすところで活きています。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-control-s"></div>
    <div class="sport-container">
        <div class="sport-image-container">
            <img src="img/NC2.jpg" alt="画像１" class="sport-main-image">
            <p class="sport-word f-word-b bolder">僕はサッカー<br>をしてました。</p>
            <p class="link f-white" onclick="togglePopup('popup2')">▶ もっと見る</p>
            <!-- ポップアップ -->
            <div class="sport-popup popup2">
                <div class="sport-popup-content">
                    <div class="sport-popup-title f-title text-left">「スポーツで培ったチームの強さは<br>職場で最大限の成果をあげる時に必須です！」</div>
                    <div class="sport-popup-info">
                        <img src="img/soccor.png" alt="ポップアップ画像" class="sport-popup-image">
                        <p class="sport-popup-comment f-word-b text-left">サッカーはチーム内でコミュニケーション能力を駆使しチームの連携を最大限に高める
                            ことで成果を出すことを学びました。今の仕事では、連携を活かして成果をあげられるように
                            頑張っています。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-control-s"></div>
    <div class="sport-container">
        <div class="sport-image-container">
            <img src="img/NC3.jpg" alt="画像１" class="sport-main-image">
            <p class="sport-word f-word-b bolder">僕はバスケ<br>をしてました。</p>
            <p class="link f-white" onclick="togglePopup('popup3')">▶ もっと見る</p>
            <!-- ポップアップ -->
            <div class="sport-popup popup3">
                <div class="sport-popup-content">
                    <div class="sport-popup-title f-title text-left">「スポーツで培った精神は<br>日々のスキルアップにとても役立っています！」</div>
                    <div class="sport-popup-info">
                        <img src="img/basketball.png" alt="ポップアップ画像" class="sport-popup-image">
                        <p class="sport-popup-comment f-word-b text-left">バスケは9年していました。どんなに疲れていても欠かさず練習に取り組み、
                            毎日続けることの大切さを身につけました。部活の習慣が身についているので今でも毎日技術力向上のために
                            勉強を続けることができています。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-control-s"></div>
    <!-- フレックス群 -->
    <div class="text-left f-title">2022年入社　新卒入社</div>
    <div class="flex-img">
        <ul class="flex-ul">
            <li>
                <img class="img" src="img/inose.png" alt="画像">
                <p class="text-small">アプリケーション開発エンジニア</p>
                <p class="f-title text-left">猪瀬</p>
            </li>
            <li></li>
            <li>
                <img class="img" src="img/okazaki.png" alt="画像">
                <p class="text-small">アプリケーション開発エンジニア</p>
                <p class="f-title text-left">岡崎</p>
            </li>
            <div class="text-center text-bottom"><a href="upperclass.html">先輩たちをもっと見る →</a></div>
        </ul>
    </div> 
</div>
<div id="entry-space"></div>
</main>
<!--footer-->
<footer id="footer"></footer>
<!--js-->
<script src="js/newcomer.js"></script>
</body>
</html>