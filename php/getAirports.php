<?php

	// remove for production

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    $executionStartTime = microtime(true);

	$url = 'http://api.geonames.org/searchJSON?formatted=true&q=' . $_REQUEST['iso'] . '&featureCode=AIRP&lang=en&username=ekruger&style=full';

    $ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);

	$result=curl_exec($ch);

	curl_close($ch);

    $decode = json_decode($result,true);

	$airports = [];

	for ($i = 0; $i < count($decode['geonames']); $i++) {
        if($decode['geonames'][$i]['countryCode'] == $_REQUEST['iso']) {
            array_push($airports, $decode['geonames'][$i]);
        }
    }
     
    $output['status']['code'] = "200";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "success";
	$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
	$output['data'] = $airports;
	
	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output); 
    ?>