<?php 
set_time_limit(0);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!--SDK url with rest of your scripts-->
    <script src="https://paypage.sandbox.ngenius-payments.com/hosted-sessions/sdk.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!--<script src="https://paypage.ngenius-payments.com/hosted-sessions/sdk.js"></script>-->
  </head>
  <body>
    <div id="mount-id" style="max-width: 550px; height: 250px"></div>
	<br/>
	<br/>
    <button onclick="createSession()" class="checkoutButton">Check out</button>
	<div id="3ds_iframe"></div>
  </body>
  
<script>
    /* Method call to mount the card input on your website */
    window.NI.mountCardInput('mount-id'/* the mount id*/, {
      style: {
                main: {
                    padding: '0px'
                },
                base: {
                    backgroundColor: '#FFFFFF',
                    fontSize: '16px'
                },
                input: {
                    borderWidth: '1px',
                    borderRadius: '5px',
                    borderStyle: 'solid',
                    backgroundColor: '#FFFFFF',
                    borderColor: '#DDDDDD',
                    color: '#000000',
                    padding: '5px'
                },
                invalid: {
                    borderColor: 'red'
                }
            }, // Style configuration you can pass to customize the UI
      
	  
	  apiKey : 'apikey 1 diffrent form server api --->> https://www.network.ae/en/contents/listing/online-solutions#book-1', // API Key for WEB SDK from the portal
      outletRef : 'outlet reference ', // outlet reference from the portal
	  
      onSuccess : '', // Success callback if apiKey validation succeeds
      onFail :'', // Fail callback if apiKey validation fails
      onChangeValidStatus: ({
    		isCVVValid,
    		isExpiryValid,
    		isNameValid,
    		isPanValid
  		}) => {
    		console.log(isCVVValid, isExpiryValid, isNameValid, isPanValid);
  		}
    });
	
	var sessionId;
        function createSession() {
            window.NI.generateSessionId().then(function (response) {
                sessionId = response.session_id;
				//console.log(sessionId);
				if (typeof response.session_id !== 'undefined') {
					//window.location.href = 'pay.php?sid='+sessionId;
					
					$.ajax({
                        url:"pay.php", 
                        type: "post",
                        dataType: 'json',
                        data: { 
                            sid:sessionId 
                        },
                        beforeSend: function() {
                            //document.getElementById("loading").style.display = "block";
                            //document.getElementById("mount-id").style.display = "none"; 
                            //document.getElementById("pay-now").style.display = "none";
                            //document.getElementById("error").style.display = "none";
                        },
                        success:function(result){ 
                            console.log("result",result);
                            //document.getElementById("loading").style.display = "none";
                            //document.getElementById("error").style.display = "none"; 
                            document.getElementById("3ds_iframe").innerHTML = result; 
                            //document.getElementById("3ds_iframe123").innerHTML = result; 
                            //check3ds(result);   
							//console.log(result);
							check3ds(result); 	
                        },
                        error: function(error) {
                            console.log(error);
                       }
                    });   
					
				}else{
					
				}		
				
				
            }).catch(function (error) {
                console.error(error);
                //document.getElementById('error').innerHTML = msg;
            });
        }
	
	function check3ds(paymentResponse) {  
        console.log("check3ds",paymentResponse);
            if (typeof paymentResponse.orderReference !== 'undefined') {           
                //document.getElementById("loading").style.display = "none";
                window.NI.handlePaymentResponse(paymentResponse, {
                    mountId: '3ds_iframe',              
                }).then(function (response) {
					
					var status = response.status;
					
					console.log(status);
					console.log(paymentResponse.orderReference);
					var merchantRedirectUrl = 'websdk_response.php?ref=';
                    console.log(merchantRedirectUrl,'===================',+paymentResponse.orderReference);
                    //document.getElementById("loading").style.display = "block";
                    //window.location.href = merchantRedirectUrl+paymentResponse.orderReference;
                }); 
            } else {
                //document.getElementById("error").style.display = "block"; 
                //document.getElementById("error").innerHTML = paymentResponse.error;
            }             
        }
	
	
  </script>  
  
</html>