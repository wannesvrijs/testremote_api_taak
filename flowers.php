<?php
ini_set("error_reporting", E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require_once "lib/autoload.php";

$css = array( "style.css");
$VS->BasicHead($css);

$flowers = $Container->getFlowerLoader()->Load();
$template = $VS->LoadTemplate("flowers");

$MS->ShowMessages();
?>
<body>

<div class="jumbotron text-center">
    <h1>Bloemenpracht</h1>
    <p>Bloemen houden van mensen, haal ze in huis!</p>
</div>

<?php $VS->PrintNavBar( $Container->getDBM() ); ?>

<div class="container">
    <div class="row">

        <?php
        print $VS->ReplaceFlowers( $flowers, $template);

        //example looping over flowers again without reloading
        foreach( $Container->getFlowerLoader()->getItems() as $flower )
        {
            print $flower . "<br>";
            /*
            print $flower->getName() . "<br>";
            print $flower->getColor() . "<br>";
            */
        }
        ?>

    </div>
</div>

</body>
</html>