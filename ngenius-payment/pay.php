<?php 
$outlet 	  = "outlet reference/ID";                                                                  // set your outlet reference/ID value here (example only)
$apikey 	  = "apikey 2 diffrent form web api";
  
  
 if (isset($_REQUEST['sid'])) { 
    $session = $_REQUEST['sid'];
    try {
        $idData = identify($apikey);
        if (isset($idData->access_token)) { 
			$token 				= $idData->access_token;
            $payData 			= pay($session, $token, $outlet);
          //  print_r($payData); exit;
            $arrayResponse 		= (array) $payData;
            echo $paymentResponse 	= json_encode($payData);
            $orderReference 	= "'".$arrayResponse['orderReference']."'";
        }
    } catch (Exception $e) {

      echo($e->getMessage());

    }
}
 

function identify($apikey) { 

    $idUrl = "https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token";
    $idHead = array("Authorization: Basic ".$apikey, "Content-Type: application/vnd.ni-identity.v1+json");
    //$idPost = http_build_query(array('grant_type' => 'client_credentials'));
    $idOutput = invokeCurlRequest("POST", $idUrl, $idHead, $idPost='');
    return $idOutput;
}

function pay($session, $token, $outlet) {        
    // construct order object JSON
    $ord = new stdClass;
    $ord->action = "SALE";
    $ord->amount = new stdClass;
    $ord->amount->currencyCode = "AED";
    $ord->amount->value = "10";           
	$ord->merchantOrderReference = time();	

    $payUrl = "https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/".$outlet."/payment/hosted-session/".$session;
    $payHead = array("Authorization: Bearer ".$token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");
    $payPost = json_encode($ord); 

    $payOutput = invokeCurlRequest("POST", $payUrl, $payHead, $payPost, true);
	

	
    return $payOutput;
}

function invokeCurlRequest($type, $url, $headers, $post) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);       

    if ($type == "POST" || $type == "PUT") {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        if ($type == "PUT") {
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
    }

    $server_output = curl_exec ($ch);
    return json_decode($server_output);
}

?>
