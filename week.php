<?php
require_once "lib/autoload.php";
$apiActions = $Container->getApiActions();

if (isset($_POST)){
    $data_array =  array(
        "taa_omschr"        => $_POST['taa_omschr'],
        "taa_datum"         => $_POST['taa_datum']
    );
    $make_call = $apiActions->callAPI('POST', 'http://localhost/testremote_api_taak/api/taken', json_encode($data_array));
    $response = json_decode($make_call, true);
    $errors   = $response['response']['errors'];
    $data     = $response['response']['data'][0];
}


$css = array( "style.css" );
$VS->BasicHead( $css );

$MS->ShowMessages();



?>
    <body>

    <div class="jumbotron text-center">
        <h1>Weekoverzicht</h1>
    </div>
    <?php $VS->PrintNavBar( $Container->getDBM() ); ?>

    <div class="container">
        <div class="row">

            <?php
            $year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
            $week = (isset($_GET['week'])) ? $_GET['week'] : date("W");

            if ($week > 52)
            {
                $year++;
                $week = 1;
            }
            elseif ($week < 1)
            {
                $year--;
                $week = 52;
            }
?>
    <form method="post" action="week.php">
        <legend>taak toevoegen:</legend>
        <input type="text" name="taa_omschr">
        <input type="date" name="taa_datum">
        <input type="submit">
    </form>

    <table class="table">
        <tr>
            <th>Weekdag</th>
            <th>Datum</th>
            <th>Taken</th>
        </tr>
            <?php

            if( isset($_GET['week']) AND $week < 10 ) { $week = '0' . $week; }




            for( $day=1; $day <= 7; $day++ )
            {
                $taken = [];
                $d = strtotime($year . "W" . $week . $day);
                $sqldate = date("Y-m-d", $d);

                $get_data = $apiActions->callAPI("GET","http://localhost/testremote_api_taak/api/taken/date/$sqldate",false);
                $response = json_decode($get_data, true);

                if (!$response == null){
                    foreach ($response as $row) {
                        $taken[] = $row['taa_omschr'];
                    }
                }

                $takenlijst = "<ul><li>" . implode("</li><li>", $taken) . "</li></ul>";

                echo "<tr>";
                echo "<td>" . date("l", $d). "</td>";
                echo "<td>" . date("d/m/Y", $d). "</td>";
                echo "<td>" . $takenlijst . "</td>";
                echo "</tr>" ;
            }

            echo "</table>";

            $link_vorige = "week.php?week=" . ($week == 1 ? 52 : $week - 1 ) . "&year=" . ($week == 1 ? $year - 1 : $year);
            $link_volgende = "week.php?week=" . ($week == 52 ? 1 : $week + 1 ) . "&year=" . ($week == 52 ? $year + 1 : $year);
            echo "<a href=$link_vorige>Vorige Week</a>&nbsp";
            echo "<a href=$link_volgende>Volgende Week</a>&nbsp";
            ?>

        </div>
    </div>
    </body>
</html>