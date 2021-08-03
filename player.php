<html>
    <head>
        <title>
            StatWatch
        </title>
        <link rel='stylesheet' href='/static/CSS/style.scss'>
        <link href="/static/CSS/css-circular-prog-bar.css" rel="stylesheet"/>
        <link href = "/static/fontawesome/css/all.css" rel="stylesheet"/>
        <script src="/static/JS/chart.min.js"></script>
        <script src="/static/JS/moment.js"></script>
        <script src="/static/JS/chartjs-adapter-moment.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
        <script src="/static/JS/chartjs-plugin-zoom.min.js"></script>
    </head>
    <body>
        <?php
            include_once("static/Model/Classes/BuildingBlocks.php");

            if(!isset($_GET["playerID"])){
                echo("Invalid Player ID");
                exit();
            }

            $player = $model->query('SELECT * FROM ((tbl_Player INNER JOIN tbl_Player_Statistic_Total ON playerID = tbl_Player_Statistic_Total.playerID_F) INNER JOIN tbl_Player_Communication ON playerID = tbl_Player_Communication.playerID_F) WHERE playerID = '.$_GET["playerID"])->fetch();
            $communications = $model->query('SELECT sql FROM sqlite_master WHERE tbl_name = "table_name" AND type = "table"')->fetchAll();
        ?>
        <!-- Header -->
        <header class='navigation' style='padding-bottom: 0;background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url("./static/Images/Maps_Header/kingsrow.jpg");'>
            <div>
                <h1 style='line-height = 50%;'>
                    Summary of <?= $player["playerName"]?>
                </h1>
            </div>
        </header>

        <?php
        
            switch($player["roleID_F"]){
                case 1:
                    include './static/phpComponents/tankSummary.php';
                    break;
                case 2:
                    include './static/phpComponents/dpsSummary.php';
                    break;
                case 3:
                    include './static/phpComponents/supportSummary.php';
                    break;
            }
        
        ?>
        <article name="Ultimate">
            <section class="dateField">Ultimate Usage</section>
            <section style="width:100px; float: left; margin-right: 10px; margin-bottom: 10px;">
                <div class="frame">
                    <div style="height: 50%;"><h5>Ultimates Earned</h5></div>
                    <div style="height: 50%;"><?= $player["Ultimates_Earned"] ?></div>
                </div>
                <div class="frame">
                    <div style="height: 50%;"><h5>Ultimates Used</h5></div>
                    <div style="height: 50%;"><?= $player["Ultimates_Used"] ?></div>
                </div>
            </section>
            <section class="frame" style="height: 210px; width: 540px; padding: 10px; padding-top: 15px;padding-right: 15px;">
                <canvas id="ultChargeChart"></canvas>
            </section>
            <section style="width:100px; float: left; margin-right: 10px; margin-bottom: 10px;">
                <div class="frame">
                    <div style="height: 50%;"><h5>Avg Holdtime</h5></div>
                    <div style="height: 50%;"><?= $player["Ultimates_HoldTime_Avg"] ?>s</div>
                </div>
                <div class="frame">
                    <div style="height: 50%;"><h5>Max Holdtime</h5></div>
                    <div style="height: 50%;"><?= $player["Ultimates_HoldTime_Max"] ?>s</div>
                </div>
            </section>
            <script>
                var ctx = document.getElementById("ultChargeChart").getContext('2d'); 
                let width, height, gradient;
                function getGradient(ctx, chartArea) {
                    const chartWidth = chartArea.right - chartArea.left;
                    const chartHeight = chartArea.bottom - chartArea.top;
                    if (gradient === null || width !== chartWidth || height !== chartHeight) {
                        // Create the gradient because this is either the first render
                        // or the size of the chart has changed
                        width = chartWidth;
                        height = chartHeight;
                        gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, "#d3540000");
                        gradient.addColorStop(1, "#d35400CC");
                    }
                    return gradient;
                }
                const config = {
                    type: 'scatter',
                    data: {
                        datasets: [
                        {
                        label: 'Ultimate Charge',
                        data: [
                            <?php
                                if(!isset($playerID))
                                    $playerID = $_GET["playerID"];
                                $UltCharges = $model->query('SELECT * FROM tbl_Player_UltimateCharge WHERE playerID_F = '.$playerID)->fetchAll();
                                foreach($UltCharges as $UltCharge){
                                    echo("{x: \"".$UltCharge["gameTime"]."\", y: ".$UltCharge["chargeValue"]."},");
                                }
                        ?>
                        ],
                        borderColor: "#d35400",
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;

                            if (!chartArea) {
                            // This case happens on initial chart load
                            return null;
                            }
                            return getGradient(ctx, chartArea);
                        },
                        showLine: true,
                        cubicInterpolationMode: 'monotone',
                        tension: 0.4,
                        fill: true
                        }
                    ]},
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false,
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                type: "time",
                                time: {
                                    parser: "HH:mm:ss",
                                    unit: "seconds",
                                    displayFormats: {
                                        'seconds': 'HH:mm:ss'
                                    },
                                    stepSize: "00:02:00"
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Ultimate Charge'
                                }
                            }
                        },
                        elements:{
                            point:{
                                radius:0
                            }
                        }
                    }
                };
                var ultChargeChart_<?= $playerID?> = new Chart(ctx, config);
            </script>
        </article>
        <article name="Communication">
            <section class="dateField">Communication</section>
            <?php
                foreach($communications as $com){
                    if($com != "playerID_F"){
                        BuildingBlocks.CreateSmallField("Hey", "Boy");
                    }
                }
            ?>
        </article>
    </body>
</html>