<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dataファイルを読み込んで表示するページ</title>
</head>

<body>

    <?php
    //エラーを検出
    ini_set('display_errors', 1);

    $contents = file('data/data.txt');
    //echo var_dump($contents) . "<br>";


    // 登録してある人数が返り値
    $arr_length = count($contents);
    //echo "arr_lengthを出力" . $arr_length . "<br>";

    // テキストファイルをまずは登録ごとで配列に格納する
    for ($i = 0; $i < $arr_length; $i++) {
        //echo $i . "周目";
        $org1 = mb_strpos($contents[$i], ',');
        // 日付直後のカンマ 0から始まるので事前にプラス１
        $org2 = mb_strpos($contents[$i], ',', $org1 + 1);

        // 日付だけを抽出（0文字目からカンマまで）
        $dbl_arr[$i][0] = mb_substr($contents[$i], 0, $org1);

        // genderだけを抽出
        $dbl_arr[$i][1] = mb_substr($contents[$i], $org1 + 1, $org2 - $org1 - 1);
    }
    //echo var_dump($dbl_arr) . "<br>";

    // 配列を格納用に必ず必要！
    $gender_male_total_pg = [];
    $gender_female_total_pg = [];

    // 人数分の配列を回す
    for ($i = 0; $i < $arr_length; $i++) {
        // 配列の結合方法
        // https://www.sejuku.net/blog/28533

        // 男性かどうかを判定
        if ($dbl_arr[$i][1] == "male") {
            // 男性が何人いるのかを配列に全て格納
            $gender_male_total_pg = array_merge($gender_male_total_pg, explode(",", $dbl_arr[$i][1]));
            // echo $dbl_arr[$i][0]."の".$dbl_arr[$i][1]."は30才未満<br>";
        } else {
            // 女性が何人いるのかを配列に全て格納
            $gender_female_total_pg = array_merge($gender_female_total_pg, explode(",", $dbl_arr[$i][1]));
            // echo $dbl_arr[$i][0]."の".$dbl_arr[$i][1]."は70才未満<br>";
        }
    }

    // 性別
    $gender_male_output = array_count_values($gender_male_total_pg);
    $gender_female_output = array_count_values($gender_female_total_pg);

    //echo var_dump($gender_male_total_pg) . "<br>";
    //echo var_dump($gender_female_total_pg) . "<br>";

    // javascirptへの受け渡し用にphpの配列をjsonデコード処理
    $json_list_male = json_encode($gender_male_output, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    $json_list_female = json_encode($gender_female_output, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

    <canvas id="myPieChart"></canvas>

    <script>
        // パースしてphpから情報を受け取る
        // https://qiita.com/cr_tk/items/900914e8a6e19ee3c5b7
        var js_list_male = JSON.parse('<?php echo $json_list_male; ?>');
        var js_list_female = JSON.parse('<?php echo $json_list_female; ?>');


        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["男性", "女性"],
                datasets: [{
                    backgroundColor: [
                        "#2B5179",
                        "#F2F207",
                    ],
                    data: [
                        js_list_male.male,
                        js_list_female.female
                    ]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: '男女比のテスト',
                    fontSize: 36
                },
                responsive: true
            }
        });
    </script>

</body>

</html>