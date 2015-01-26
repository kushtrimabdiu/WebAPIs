<?php
require_once('wikipedia.class.php');
//make na instance of the wikipedia class
 $wikiVaxjo = new wikipedia();
 //save the vaxjo information from the wikipedia page into the info variable
 $info = $wikiVaxjo->getText();

 echo $info;
?>