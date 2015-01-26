<?php

class wikipedia
{
   //without this header information $userAgent we cannot get the response from Wikipedia
    protected $userAgent='myApplication/1.0 (http://www.kushtrim.com/)';
    protected $XML;
    protected $data = array();
 

   //a function that returns the text of the Wikipedia page
    public function getText()
    {

        $result = $this->callApi();
        $result = $this->parseText($result, 0);
        return $result;
    }

     //this function calls the Wikipedia API
    private function callApi()
    {
        //the query is predefined since we are looking for Vaxjo
    	$wikiURL = "http://en.wikipedia.org/w/api.php?format=xml&action=query&prop=revisions&titles=Vaxjo&redirects=true&rvprop=content&rvsection=0";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $wikiURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        $result = curl_exec($curl);
        return $result;
    }
    //this function parses the retrieved XML version of the Wikipedia page
    private function parseText($xml, $section)
    {
        $this->XML = new SimpleXMLElement($xml);
        $this->data = $this->XML->query->pages->page->revisions->rev;
        $string = $this->data[0];

        if ($section == 0) {
            //This removes the images/info box if the section is the summary
            $string = strstr($string, '\'\'\''); 
            //Replaces the ''' around titles to be "
            $string = str_replace('\'\'\'', '"', $string); 
        }
        $string = preg_replace('/<ref[^>]*>[^<]+<\/ref[^>]*>|\{{(?>[^}]++|}(?!}))\}}|==*[^=]+=*\n|File:(.*?)\n|\[\[|\]]|\n/', '', $string); 
        //Makes the wikilinks look better
        $string = str_replace('|', '/', $string); 
        $string = strip_tags($string); 
        return $string;
    }

}

?>

