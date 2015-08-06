<?php


     /* ElasticSearch Configurations  Starts   */

        $esHost             = '127.0.0.1';
        $esPort             = '9200';
        $esIndex            = 'students';
        $esType             = 'student';
        $availAggs          = array('Department','Marks','Semester');
        $searchQuery        = '';
        $from               = 0;
        $size               = 20;
        $postFilters        = array();
        $result             = '';
        
     /* ElasticSearch Configurations  Ends   */    
        
     /*
      *     This function used to fire query to Elasticsearch
      * 
      */   
      function call($queryData, $esAPI = '/_search', $method='POST'){
          global $esHost,$esPort,$esIndex,$esType;
	    try {
                $esURL = 'http://'.$esHost.':'.$esPort.'/'.$esIndex.'/'.$esType.$esAPI;
                $ci = curl_init();
                curl_setopt($ci, CURLOPT_URL, $esURL);
                curl_setopt($ci, CURLOPT_PORT, $esPort);
                curl_setopt($ci, CURLOPT_TIMEOUT, 200);
                curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ci, CURLOPT_FORBID_REUSE, 0);
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST,$method);
                curl_setopt($ci, CURLOPT_POSTFIELDS, $queryData);
                return curl_exec($ci);
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\r\n";
            }
      }
      
      $Department = array('Electronics','Computer','Mechanical','Civil','Automobile');
      for($i=0; $i<10; $i++){
          $student = array();
          $student['Name']         = generateRandomString();
          $student['Department']     = $Department[array_rand($Department)];
          $student['Marks']        = rand(0,100);
          $student['Semester']       = rand(1,4);
          $student =  json_encode($student, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_NUMERIC_CHECK );
          echo call($student,'','POST');
      }
      
      function generateRandomString($length = 5) {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
     }