<?php
require_once "lib/autoload.php";

$css = array( "style.css");
$VS->BasicHead( $css );
?>
<body>

<div class="jumbotron text-center">
    <h1>Detailpagina Afbeelding</h1>
</div>

<div class="container">
    <div class="row">

        <?php
        $cities = $Container->getCityLoader()->Load( $id = $_GET['id'] );
        $template = $VS->LoadTemplate("stad");

        print $VS->ReplaceCities( $cities, $template);
        ?>

    </div>
</div>

</body>
</html>