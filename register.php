<?php
$register_form = true;
require_once "lib/autoload.php";

$css = array( "style.css");
$VS->BasicHead( $css );
?>
<body>

<div class="jumbotron text-center">
    <h1>Registratie</h1>
</div>

<div class="container">
    <div class="row">

        <?php
        print $VS->LoadTemplate("register");
        ?>

    </div>
</div>

</body>
</html>