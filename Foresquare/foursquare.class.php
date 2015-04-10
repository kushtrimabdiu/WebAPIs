<?php
/*
/This class is responsible for making the Foursquare API calls
/ it gets the venue (type of location) parameter and retrieves places near and their distances
/from the client 
*/
class foursquare
 {
	public $foursquareURL ="https://api.foursquare.com/v2/venues/search?";
	//Venue or category of our search e.g. coffe, restaurant...
	public $venue;
    public $date ="20141204";
	//the application key
	public $apiKey="THE_API_KEY";
	//the application secret password
	public $apiSecret="THE_API_SECRET";
	//Vaxjo's latitude
	public $lat=56.8769;
	//Vaxjo's longitude
	public $lon=14.8092;
            

    //the constructor needs the type of the location we are looking for e.g. Coffe, Shops, Cinema etc..
	/*function __construct($category)
    {

        $this->venue=$category;
    }*/
    //this function makes the request to retrieve the information from Foursquare
    //the data are raw and in JSON format
    public function callApi($category)
    {   
        $this->venue=$category;
        $jsonurl = $this->foursquareURL."client_id=".$this->apiKey."&client_secret=".$this->apiSecret."&v=".$this->date."&ll=".$this->lat.",".$this->lon."&query=".$this->venue;
      
        $newUrl = htmlspecialchars_decode($jsonurl);
        $json = file_get_contents($newUrl, 0, null, null);
        $json_output = $venues = stripslashes($json);
        
        return $json_output;
        
       
    }

    /*parse the JSON retrieved from the callApi() method 
    / this function returns another JSON  with places and distances 
    /to be accessed from AJAX 
    */
    public function getPlacesNearYou($category)
    {
        //we call the method callApi() to get a JSON of the raw data
        //we only need the place name and the distance from the Vaxjo center
    	$json =$this->callApi($category);
        //the JSON is decoded into an array so we can iterate through it and 
        //get the data we are interested in 
        $json_output = json_decode($json);
        //we create a new array which will hold the location name and distance 
        $arrayPlaces = array();
        
        //we loop through the multidimensional array with raw data 
        //and select only the above mentioned parameters (name and distance)
        foreach ($json_output->response->venues as $items) {
                //we create a temporary array with the parset data
                //we add this array to the arrayPlaces
                 $arrayTemp = array();
                 $arrayTemp[$items->name]="{$items->location->distance}";
                 
                 array_push($arrayPlaces,$arrayTemp);
             
            }
        $newArrayToJSON = json_encode($arrayPlaces);
        print_r($newArrayToJSON);

        return $newArrayToJSON;
    }


 }

?>
