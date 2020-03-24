<?php
$no_access = true;
require_once "lib/autoload.php";

$css = array( "style.css");
$VS->BasicHead( $css );

?>
<body>

<div class="jumbotron text-center">
    <h1>Geen toegang!</h1>
    <p>Gelieve eerst in te loggen</p>
</div>

<div class="container">

        <?php
        print $VS->LoadTemplate("no_access");
        ?>

</div>

</body>
</html>