<?php
// データベース接続情報
$host = 'localhost';
$db = 'enterprise';
$user = 'root';
$password = 'root';

// データベースに接続
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

// オプションの一覧を取得
$query = "SELECT * FROM options";
$stmt = $conn->prepare($query);
$stmt->execute();
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedOptions = isset($_POST['vote']) ? $_POST['vote'] : [];

    // 投票履歴をチェック
    $userIp = $_SERVER['REMOTE_ADDR'];
    $query = "SELECT * FROM votes_history WHERE user_ip = :user_ip";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_ip', $userIp);
    $stmt->execute();
    $voteHistory = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voteHistory && count($selectedOptions) > 0) {
        // 投票結果をデータベースに保存
        $query = "INSERT INTO votes (option_id) VALUES (:option_id)";
        $stmt = $conn->prepare($query);

        foreach ($selectedOptions as $option) {
            $stmt->bindParam(':option_id', $option);
            $stmt->execute();
        }

        // 投票履歴を保存
        $query = "INSERT INTO votes_history (user_ip, option_id) VALUES (:user_ip, :option_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_ip', $userIp);

        foreach ($selectedOptions as $option) {
            $stmt->bindParam(':option_id', $option);
            $stmt->execute();
        }
    }

    // ページをリロードして再投稿を防止するためのリダイレクト
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 集計クエリ実行
$query = "SELECT o.name, COUNT(*) AS count
FROM options o
JOIN votes v ON o.id = v.option_id
GROUP BY o.name
ORDER BY count DESC;
";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 投票済みかどうかをチェック
$userIp = $_SERVER['REMOTE_ADDR'];
$query = "SELECT * FROM votes_history WHERE user_ip = :user_ip";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_ip', $userIp);
$stmt->execute();
$voteHistory = $stmt->fetch(PDO::FETCH_ASSOC);

// 最終投票日を取得するクエリ実行
$query = "SELECT MAX(vote_date) AS last_vote_date FROM votes_history WHERE user_ip = :user_ip";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_ip', $userIp);
$stmt->execute();
$lastVoteDateResult = $stmt->fetch(PDO::FETCH_ASSOC);
$lastVoteDate = $lastVoteDateResult['last_vote_date'];

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
    <title>上級者向け</title>
    <!--css-->
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/common.css" type="text/css">
    <link rel="stylesheet" href="css/expert.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/newcomer.js"></script>
</head>
<body>
<!--header-->
<header id="header"></header>
<div class="header_space"></div>

<!--main-->
<main class="main"> 
<!-- トップ画像 -->
<div><img src="img/expert-TOP.png" class="img" alt=""></div>
<div class="gap-control"></div>

<div class="wrapper">
    <div class="gap-control-s"></div>
    <p class="f-title">質問</p>
    <hr class="border-line">
    <p class="f-word-r">「キャリアアップで選ぶポイントは何ですか？」</p>
    <div class="gap-control-s"></div>
    <div id="updateStatus">
        <div class="update-vote f-title">エンジニアが選ぶポイントを更新中<span id="dots">...</span></div>
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
    <div class="load">
        <?php if ($lastVoteDate) : ?>
            <p class="f-title">最終投票日: <?php echo date('Y.m.d', strtotime($lastVoteDate)); ?></p>
            <?php else : ?>
            <p>まだ投票がありません。</p>
        <?php endif; ?>
    </div>                
    <div class="gap-control"></div>
    <p class="f-large-r">現在のランキング</p>
</div>
<!-- エンジニアが選ぶ企業のポイント　ランキング -->
<div class="wrapper">
    <div style="position: relative; margin: auto;">
        <canvas id="voteChart" height="500px" width="100%"></canvas>
        <div id="imageContainer" style="position: absolute; bottom: 0; left: 0;"></div>
    </div>
            <script>
                // データの取得
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "root";
                $dbname = "enterprise";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "
                    SELECT o.name AS option_name, COUNT(v.id) AS vote_count
                    FROM options o
                    LEFT JOIN votes v ON o.id = v.option_id
                    GROUP BY o.id
                    ORDER BY vote_count DESC;
                ";

                $result = $conn->query($sql);
                $data = array();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = array(
                            "option_name" => $row["option_name"],
                            "vote_count" => $row["vote_count"]
                        );
                    }
                }

                $conn->close();
                ?>

                // データの設定
                var data = {
                    labels: <?php echo json_encode(array_column($data, "option_name")); ?>.map((v) => v.replace(/ー/g, '丨').split("")),
                    datasets: [{
                        data: <?php echo json_encode(array_column($data, "vote_count")); ?>,
                        backgroundColor: [
                            <?php
                            for ($i = 0; $i < count($data); $i++) {
                                if ($i < 3) {
                                    echo "'#8B2022',";
                                } else {
                                    echo "'rgba(139, 32, 34, 0.5)',";
                                }
                            }
                            ?>
                        ],
                        borderWidth: 0 // 区切り線を非表示
                    }]
                };

                // グラフ作成
                var ctx = document.getElementById('voteChart').getContext('2d');
                var voteChart = new Chart(ctx, {
                    type: 'bar', // 縦棒グラフ
                    data: data,
                    options: {
                        scales: {
                            x: {
                                display: true, // X軸目盛り表示
                                grid:{
                                    display:false,
                                },
                                ticks: {
                                    color: 'black', // 項目名の色
                                    font:{
                                        size: 16,
                                        weight: 'bold'
                                    }
                                }
                            },
                            y: {
                                display: false, // Y軸目盛り非表示
                            }
                        },
                        plugins: {
                            legend: {
                                display: false, // 凡例非表示
                            },
                            tooltip: {
                                enabled: false
                            },
                            
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 20,
                                right: 20,
                                top: 20,
                                bottom: 20
                            }
                        },
                        indexAxis: 'x', // 横軸に表示
                    },
                    plugins: [{
                        afterDraw: function(chart) {
                            displayImagesBelowBars(chart);
                        }
                    }]
                });
                
                // 画像を表示する関数
                function displayImagesBelowBars(chart) {
                    var imageContainer = document.getElementById('imageContainer');
                    var imageUrls = [
                        'img/ex-1.png', // 1位の画像URL
                        'img/ex-2.png', // 2位の画像URL
                        'img/ex-3.png'  // 3位の画像URL
                    ];

                    var xAxis = chart.scales.x;
                    var barWidth = xAxis.width / chart.data.labels.length;

                    chart.data.datasets[0].data.forEach(function(dataValue, index) {
                        if (index < imageUrls.length) {
                            var img = new Image();
                            img.src = imageUrls[index];
                            img.width = 45
                            img.height = 70;

                            var position = xAxis.getPixelForValue(index);
                            var imgContainer = document.createElement('div');
                            imgContainer.style.position = 'absolute';
                            imgContainer.style.left = (position - img.width / 2) + 'px';
                            imgContainer.style.bottom = '-60px'; // 画像をさらに下に移動
                            imgContainer.appendChild(img);

                            imageContainer.appendChild(imgContainer);
                        }
                    });
                }
            </script>
        </div>
        <!-- 隙間 -->
        <div class="gap-control-l"></div>
    <div class="wrapper">
        <button class="cercle" id="rankingButton" onclick="toggleRanking()">ランキングに参加する</button>
        <div class="arrow-container">
            <div class="arrow-bottom"></div>
            <div class="arrow-bottom arrow-bottom-Shifted"></div>
        </div>
    </div>
        <div class="gap-control-s"></div>
    <!-- 投票 -->
    <div class="ranking-section" id="rankingSection" style="display: none;">

    <div class="wrapper">
        <div class="gap-control-s"></div>
        <p class="font-style-comments2 txt line-height">キャリアアップで転職される際に、重要視されるポイントを下記よりお選びください。<br>※複数選択可能</p>
        <div class="gap-control-s"></div>
        <?php if (!$voteHistory) : ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="option-container">
                    <?php foreach ($options as $option) : ?>
                        <div class="option">
                            <input type="checkbox" id="option<?php echo $option['id']; ?>" name="vote[]" value="<?php echo $option['id']; ?>">
                            <label for="option<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="gap-control-s"></div>
                <button class="post-btn" type="submit"　onclick="showChart()">投票する</button>
            </form>
        <?php else : ?>
            <p class="vote-message asterisk">※すでに投票済みです。</p>
        <?php endif; ?>
        <div class="gap-control-s"></div>
    </div>
</div>
<div class="wrapper">
<div class="gap-control-s"></div>
    <div class="f-word-b txt">
        皆さんは、転職先を選ぶ時に何を最も重視しますか？
        たとえば…職場環境、年収、業務内容、技術力、ネームバリューなど。
        でも他にも重要視するポイントってありますよね。
    </div>
    <div class="gap-control-l"></div>
    <p class="f-title">変化する役割</p>
    <hr class="border-line">
    <p class="f-word-r">エンジニアの「キャリア」</p>
    <div class="gap-control-s"></div>
    <p class="f-title">これまでの経験を活かすために</p>
    <div class="gap-control"></div>
</div>

<!--キャリア-->
    <div class="flex-pic">
        <div class="flex-pic-img"><img src="img/sample1.png"></div>
        <div class="flex-txt">テキストテキストテキストテキストテキストテキストテキスト
            テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</div>
    </div>
    <div class="flex-pic">
        <div class="flex-pic-img"><img src="img/sample2.png"></div>
        <div class="flex-txt">テキストテキストテキストテキストテキストテキストテキスト
            テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</div>
    </div>
    <div class="gap-control"></div>

<!-- project -->
<div class="wrapper">
    <p class="f-word-r">「プロジェクト事例」</p>
    <div class="gap-control-s"></div>
    <p class="f-title">あなたの経験を活かせるプロジェクトがあります</p>
    <div class="gap-control-l"></div>
    <!-- 業務内容（アプリ開発） -->
    <div>
        <p class="font-bordeaux text-center">分野</p>
    </div>
    <div class="cercle">アプリ開発</div>
    <div class="gap-control"></div>

    <div class="flex-job">
        <div class="flex1 font-bordeaux">案件概要</div>
        <div class="flex2 font-bordeaux">技術要素</div>
    </div>
    <div class="flex-job">
        <div class="flex1 f-title">リスク管理システムパッケージ新規開発</div>
        <div class="flex2">Java<br>GWT<br>Hibernate<br>Jasper Studio<br>JP1<br>SQL Server<br><br></div>
    </div>
    <div class="flex-job">
        <div class="flex1 f-title">与信管理システム保守開発（クレジット会社向け）</div>
        <div class="flex2">Java<br>SQL<br>JP1<br>Oracle<br><br></div>
    </div>
    <div class="flex-job">
        <div class="flex1 f-title">給与計算システム（メーカー向け）</div>
        <div class="flex2">C<br>SHELL<br>PL<br>SQL<br>Oracle<br><br></div>
    </div>
    <div class="gap-control-l"></div>

    <!-- 業務内容（インフラエンジニア） -->
    <div>
        <p class="font-bordeaux text-center">分野</p>
    </div>
    <div class="cercle">インフラエンジニア</div>
    <div class="gap-control"></div>

    <div class="flex-job">
        <div class="flex1 font-bordeaux">案件概要</div>
        <div class="flex2 font-bordeaux">技術要素</div>
    </div>
    <div class="flex-job">
        <div class="flex1 f-title">オンプレLinuxサーバ（RHEL）からクラウド移行に伴う基盤移行<br>及びOS、ミドルウェアバージョンアップ</div>
        <div class="flex2">IBM MQ<br>IBM Tivoli Monitoring<br>Netbackup<br>NetWorker<br>VMware vSphere<br><br></div>
    </div>
    <div class="flex-job">
        <div class="flex1 f-title">物理SolarisサーバからLinuxサーバ（RHEL）への移行<br>及びミドルウェアバージョンアップ</div>
        <div class="flex2">NetWorker<br>IBM MQ<br>IBM Tivoli Monitoring<br>Oracle<br>Systemwalker<br>Storabe Cruiser<br>ServerView<br><br></div>
    </div>
    <div class="flex-job">
        <div class="flex1 f-title">保険システムにおける基盤構築支援</div>
        <div class="flex2">Lotus Notes<br>TeraTerm<br>Db2V10.1<br>WebSphereApplicationServerV8.5<br>SVF for PD<br><br></div>
    </div>
    <div class="gap-control"></div>
</div>
</main>
<!--footer-->
<footer id="footer"></footer>
</body>
</html>