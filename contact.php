<?php
//dit is commentaar
require_once "lib/autoload.php";

$css = array( "style.css" );
$VS->BasicHead( $css );

$MS->ShowMessages();
?>
<body>

<div class="jumbotron text-center">
    <h1>Contact</h1>
</div>

<?php $VS->PrintNavBar( $Container->getDBM() ); ?>

<div class="container">
    <div class="row">

    </div>
</div>

</body>
</html>