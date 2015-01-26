<?php

/*
/to make api calls we have to use these information provided in http://tagtider.net/api/
/ username : tagtider
/ password : codemocracy
*/
class Trains
{
  public $username ="tagtider";
  public $password = "codemocracy";
   ///Vaxjo's station id 315
  public $request = 'http://api.tagtider.net/v1/stations/315/transfers/departures.json';
  public $userAgent = "PHP-Service";
  protected $curlDefaultHeaders = array('Content-type:text/xml; charset=UTF-8');

  public function callApi ()
  {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->curlDefaultHeaders); 
        curl_setopt($ch, CURLOPT_URL, $this->request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING ,""); 
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
  }

  public function getAllTrains()
  {
        $jsonInfo = $this->callApi();

        $arrayInfo = json_decode($jsonInfo);
      
       //refining the data
        $arrayTrain= array('train'=>'departure');

        foreach($arrayInfo->station->transfers->transfer as $transfer)
        {
           $tmpDestination = $transfer->destination;
           $tmpDeparture = $transfer->departure;

          // echo 'unprocessed:'.$tmpDestination. " ".$tmpDeparture;
           
           $tmpArray =explode(",",$tmpDestination);
           foreach($tmpArray as $value)
           {
             $arrayTrain[$value]=$tmpDeparture;
           }          
        }
        print_r($arrayTrain);
        return $arrayTrain;
  }

  public function getTrain($dest)
  {
    $allTrains = $this->getAllTrains();
    $dest=strtolower($dest);
    $hasTrain= false;
    $timesArray = array();
    foreach($allTrains as $destination=>$time)
    {
         $destination =strtolower($destination);
         if($destination==$dest)
         {
            $hasTrain=true;
            array_push($timesArray,$time);
         }
      }
      if($hasTrain)
      {
        print_r($timesArray);
      }
      else
      {
        echo "no train for your destination";
      }
  }

}

?>