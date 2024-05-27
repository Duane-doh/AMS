<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Background_process {

    public function __construct()
    {
        $this->ci =& get_instance();
    }
    
    function test_asynch() {

        RLog::info("START: PHP Async Test... " . date('Y-m-d h:i:s.u'));

        $params = array(
            "one" => "111111",
            "two" => "22222",
            "three" => "33333",
            "four" => "44444",
        );
        $this->curl_post_async("http://127.0.0.1/doh_ptis/main/background_process/longone", $params);
        
        RLog::info("END: PHP Async Test... (continuing process) " . date('Y-m-d h:i:s.u'));
    }

    function longone(){

        $one = $_POST["one"];
        $two = $_POST["two"];
        $three = $_POST["three"];
        $four = $_POST["four"];

        RLog::info( uniqid("You won't see this because your PHP script isn't waiting to read any response") );

        // put some long delay in here, so you can see how quickly the async requests returns
        sleep(20);

        // and the proof that something actually happens...  write out the HTTP params that were sent over the wire
        $fp = fopen('D:/long_one_data.txt', 'w');
        
        fwrite($fp, $one . date('Y-m-d h:i:s.u') . "\n");
        sleep(30);
        
        fwrite($fp, $two . date('Y-m-d h:i:s.u') . "\n");
        sleep(30);
        
        fwrite($fp, $three . date('Y-m-d h:i:s.u') . "\n");
        sleep(30);
        
        fwrite($fp, $four . date('Y-m-d h:i:s.u') . "\n");
        fclose($fp);

    }

    function curl_post_async($url, $params = array())
    {
    	RLog::info( "START: CURL POST ASYNC " . date('Y-m-d h:i:s.u') );
    	
        $post_params = array();

        foreach ($params as $key => &$val)
        {
			if (is_array($val)) $val = implode(',', $val);
				$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

        $parts=parse_url($url);

        $fp = fsockopen($parts['host'],
        	isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);

		$out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out.= $post_string;

        fwrite($fp, $out);
        fclose($fp);
        
        RLog::info( "END: CURL POST ASYNC " . date('Y-m-d h:i:s.u') );
    }    

    /*
    function do_in_background($url, $params)
    {
	    $post_string = http_build_query($params);
	    $parts = parse_url($url);
		$errno = 0;
	    $errstr = "";

		//Use SSL & port 443 for secure servers
		//Use otherwise for localhost and non-secure servers
		//For secure server
	    $fp = fsockopen('ssl://' . $parts['host'], isset($parts['port']) 
                   ? $parts['port'] : 443, $errno, $errstr, 30);
                   
	    //For localhost and un-secure server
		//$fp = fsockopen($parts['host'], isset($parts['port']) 
        //           ? $parts['port'] : 80, $errno, $errstr, 30);

	    if(!$fp)
	        echo "Some thing Problem";    

	    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
	    $out.= "Host: ".$parts['host']."\r\n";
	    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
	    $out.= "Content-Length: ".strlen($post_string)."\r\n";
	    $out.= "Connection: Close\r\n\r\n";
	    if (isset($post_string)) $out.= $post_string;
	    fwrite($fp, $out);
	    fclose($fp);
	}
	*/
}


/*
class Scratch extends SQ_Controller {

    function Scratch() {
        parent::SQ_Controller();
    }


}
*/
