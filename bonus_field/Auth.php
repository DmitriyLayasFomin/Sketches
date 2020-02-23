<?php
class Auth {
    public $login,
    $pass, $token,
    $guid, $host;
public $res, $test;
    function __construct() {
        $this->login = 'demoDelivery';
        $this->pass = 'PI1yFaKFCGvvJKi';
        $this->host = 'https://iiko.biz:9900/api/0/';
        $this->guid = '23f55ba4-8c8a-11e7-80df-d8d38565926f';
        $this->options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_USERAGENT      => "test", // name of client
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_SSL_VERIFYHOST => 0, //unsafe, but the fastest solution for the error " SSL certificate problem, verify that the CA cert is OK"
            CURLOPT_SSL_VERIFYPEER => 0, //unsafe, but the fastest solution for the error " SSL certificate problem, verify that the CA cert is OK"
        ); 
        $this->res = curl_init();
        curl_setopt_array($this->res, $this->options);
        curl_setopt($this->res, CURLOPT_URL, $this->host.'auth/access_token?user_id='.$this->login.'&user_secret='.$this->pass);
        $this->token = str_replace('"', '', curl_exec($this->res));
           
    }
    
    // close link
    private function link_close()
    {curl_close($this->res);}

    public function get_bonuses($phone)
    {
        curl_setopt($this->res, CURLOPT_URL,
        $this->host.'customers/get_customer_by_phone?access_token='.$this->token.
        '&organization='.$this->guid.'&phone='.$phone); 
        $test = json_decode(curl_exec($this->res), true);
        $bonus;
        foreach ($test['walletBalances'] as $value){
            
            $bonus =+ $value['balance'];
        }
            
        return $bonus;
    }

    public function get_discount($order)
    {
		$post = array_replace(
			array(
				
				'organization' => $this->guid,
			),
			$order
		);
		$url = $this->host.'orders/calculate_checkin_result?access_token='.$this->token;
		$defaults = array(
			CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url ,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json; charset=utf-8'),
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => json_encode($post)
		);
        curl_setopt_array($this->res, $defaults);
        $test = json_decode(curl_exec($this->res), true);
    
            
        return $test;
	}
	

	public function get_nomenclature()
    {
        curl_setopt($this->res, CURLOPT_URL,
        $this->host.'nomenclature/'.$this->guid.'?access_token='.$this->token); 
        $test = json_decode(curl_exec($this->res), true);
       
            
        return $test;
    }
    public function rmsSettings()
    {
        curl_setopt($this->res, CURLOPT_URL,
        $this->host.'rmsSettings/getPaymentTypes?access_token='.$this->token.'&organization='.$this->guid); 
        $test = json_decode(curl_exec($this->res), true);
       
            
        return $test;
	}
	
	


    


   
}
?>
