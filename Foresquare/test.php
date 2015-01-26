<?php

  require_once('foursquare.class.php');

  $foursquareAPI = new foursquare();

  $foursquareAPI->getPlacesNearYou("mcdonalds");

  
?>