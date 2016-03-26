<?php


if(isset($_POST['tag']) && $_POST['tag'] != ''){

    libxml_use_internal_errors(true);
    
    //get tag
    $tag = $_POST['tag'];
    chdir('/home/afagas/Documents/server');
    
    if($tag == 'schedule'){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$cookie_name = '/cookie_' . $username . '.txt';
	//set cookies to a file
	$cookie_file_path = getcwd() . $cookie_name;
	
	//Emulating Chrome Browser:
	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";

	$login_url = 'https://www.inf.uth.gr/wp-login.php';

	//Now, use the session cookie to actually log in:
	$POSTFIELDS = "log=". $username ."&pwd=". $password .'&wp-submit=%CE%A3%CF%8D%CE%BD%CE%B4%CE%B5%CF%83%CE%B7&testcookie=1';  

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$login_url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/ca-bundle.crt");
	curl_setopt($ch, CURLOPT_REFERER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
	$login_result = curl_exec ($ch);
	curl_close ($ch);
	
	$ch = curl_init('http://www.inf.uth.gr/?page_id=1404');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	$resp = curl_exec($ch);
	curl_close($ch);

	unlink($cookie_file_path);
	
	$dom = new DOMDocument();
        @$dom->loadHTML($resp);
        $xpath = new DOMXPath($dom);
        
        $imera_array = array();
        $temp_array = array();
        
        $tabs = $xpath->query('//div[@class="tab_content"]');
        
        for($i = 0; $i < $tabs->length; $i++){
            $schedule_resp = array();
            $tr = $tabs->item($i)->getElementsByTagName('tr');
            
            for($j = 0; $j< $tr->length; $j++){
                if($j == 0){
                    continue;
                }
                $td = $tr->item($j)->getElementsByTagName('td');
                $temp_array["ora"] = $td->item(0)->nodeValue;
                $temp_array["mathima"] = $td->item(1)->c14n();
                $temp_array["typos"] = $td->item(2)->nodeValue;
                $temp_array["aithousa"] = $td->item(3)->nodeValue;
                $temp_array["didaskwn"] = $td->item(4)->c14n();
        
                array_push($schedule_resp, $temp_array);
            }
            $imera_array[(string)$i] = $schedule_resp;
        }
	echo json_encode($imera_array, JSON_UNESCAPED_UNICODE);
    }
    else if($tag == 'announcement_find'){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$link = $_POST['link'];
	
	$cookie_name = '/cookie_' . $username . '.txt';
	//set cookies to a file
	$cookie_file_path = getcwd() . $cookie_name;
	
	//Emulating Chrome Browser:
	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";
	$login_url = 'https://www.inf.uth.gr/wp-login.php';

	//Now, use the session cookie to actually log in:
	$POSTFIELDS = "log=". $username ."&pwd=". $password .'&wp-submit=%CE%A3%CF%8D%CE%BD%CE%B4%CE%B5%CF%83%CE%B7&testcookie=1';  

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$login_url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/TERENASSLCA");
	curl_setopt($ch, CURLOPT_REFERER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
	$login_result = curl_exec ($ch);
	curl_close ($ch);
	
	$ch = curl_init($link);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	$resp = curl_exec($ch);
	curl_close($ch);

	unlink($cookie_file_path);
	
	if($resp != false){
	  echo $resp;
	}
	else{
	  echo "false";
	}
	
 	//echo json_encode($response);
    }
    else if($tag == 'login'){
    
	$response = array();
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	//Emulating Chrome Browser:
        //$agent = "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0";
	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";

	$ldaprdn  = 'uid=' . $username .',ou=people,dc=uth,dc=gr';     // ldap rdn or dn
	$ldappass = $password;  // associated password

	// connect to ldap server
	$ldapconn = ldap_connect("ldap.uth.gr")
		or die("Could not connect to LDAP server.");

	// Set some ldap options for talking to 
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

	if ($ldapconn) {

		// binding to ldap server
		$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);

		// verify binding
		if ($ldapbind) {
		    //echo "LDAP bind successful...\n";
		    $response["logined"] = "true";
		    
                    $cookie_name = '/cookie_' . $username . '.txt';
                    //set cookies to a file
                    $cookie_file_path = getcwd() . $cookie_name;

		    $login_url = 'https://www.inf.uth.gr/wp-login.php';
		
		    //Now, use the session cookie to actually log in:
		    $POSTFIELDS = "log=". $username ."&pwd=". $password .'&wp-submit=%CE%A3%CF%8D%CE%BD%CE%B4%CE%B5%CF%83%CE%B7&testcookie=1';  

		    $ch = curl_init(); 
		    curl_setopt($ch, CURLOPT_URL,$login_url);
		    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		    curl_setopt($ch, CURLOPT_POST, 1); 
		    curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    //curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/inf-ca");
		    curl_setopt($ch, CURLOPT_REFERER, true);
		    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
		    curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
		    $login_result = curl_exec ($ch);
		    curl_close ($ch);
		
		    //ann/opt/lampp/htdocs/serverouncements
		    $ch = curl_init('http://www.inf.uth.gr/?cat=5&feed=rss2&lang=el');
		    curl_setopt($ch, CURLOPT_HEADER, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_FAILONERROR, true);
		    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
		    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
		    $resp1 = curl_exec($ch);
		    curl_close($ch);
		    
		    //schedule
		    $ch = curl_init('http://www.inf.uth.gr/?page_id=1404');
		    curl_setopt($ch, CURLOPT_HEADER, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_FAILONERROR, true);
		    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
		    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
		    $resp2 = curl_exec($ch);
		    curl_close($ch);
		   
                    $dom = new DOMDocument();
                    @$dom->loadHTML($resp2);
                    $xpath = new DOMXPath($dom);
                    
                    $imera_array = array();
                    $temp_array = array();
                    
                    $tabs = $xpath->query('//div[@class="tab_content"]');
                    
                    for($i = 0; $i < $tabs->length; $i++){
                        $schedule_resp = array();
                        $tr = $tabs->item($i)->getElementsByTagName('tr');
                        
                        for($j = 0; $j< $tr->length; $j++){
                            if($j == 0){
                                continue;
                            }
                            $td = $tr->item($j)->getElementsByTagName('td');

                            $temp_array["ora"] = $td->item(0)->nodeValue;
                            $temp_array["mathima"] = $td->item(1)->c14n();
                            $temp_array["typos"] = $td->item(2)->nodeValue;
                            $temp_array["aithousa"] = $td->item(3)->nodeValue;
                            $temp_array["didaskwn"] = $td->item(4)->c14n();
                            
                            array_push($schedule_resp, $temp_array);
                        }
                        $imera_array[(string)$i] = $schedule_resp;
                    }
                    
		    $response["ann"] = $resp1;
		    $response["sch"] = $imera_array;
		    
                    unlink($cookie_file_path);
	      }else{
		   // echo "LDAP bind failed...\n";
		    $response["logined"] = "false";
	      }
	}
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    else if($tag == 'grades'){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$cookie_name = '/cookie_' . $username . '_' . $tag . '.txt';
	//set cookies to a file
	$cookie_file_path = getcwd() . $cookie_name;
	
	//Emulating Chrome Browser:
	$agent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:37.0) Gecko/20100101 Firefox/37.0";

	$login_url = 'https://euniversity.uth.gr/unistudent/login.asp';

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$login_url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/ca-bundle.crt");
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	$login_result = curl_exec ($ch);
	
	//Now, use the session cookie to actually log in:
	$POSTFIELDS = "userName=". $username ."&pwd=". $password .'&submit1=%C5%DF%F3%EF%E4%EF%F2&loginTrue=login';  

	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS);
	curl_setopt($ch, CURLOPT_REFERER, $login_url);
	$login_result = curl_exec ($ch);
	curl_close ($ch);

	$ch = curl_init('https://euniversity.uth.gr/unistudent/stud_CResults.asp');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	$resp = curl_exec($ch);
	curl_close($ch);

	unlink($cookie_file_path);
	
	$dom = new DOMDocument();
	@$dom->loadHTML($resp);
	
	$eksamino = 0;
	$grades_resp = array();
	$temp_array = array();
	
	$tr = $dom->getElementsByTagName('tr');
	
	for($i = 0; $i < $tr->length; $i++){
            if($tr->item($i)->getAttribute('class') == 'italicHeader'){
                $eksamino++;
                continue;
            }
            if($tr->item($i)->hasAttribute('bgcolor')){
                
                $td = $tr->item($i)->getElementsByTagName('td');
                $temp_array["eksamino"] = $eksamino;
                $temp_array["mathima"] = $td->item(1)->nodeValue;
                $temp_array["typos"] = $td->item(2)->nodeValue;
                $temp_array["vathmos"] = $td->item(6)->nodeValue;
                
                array_push($grades_resp, $temp_array);
                continue;
            }
	}
	echo json_encode($grades_resp, JSON_UNESCAPED_UNICODE);
    }
    else if($tag == 'dilosi'){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$cookie_name = '/cookie_' . $username . '_' . $tag . '.txt';
	//set cookies to a file
	$cookie_file_path = getcwd() . $cookie_name;
	
	//Emulating Chrome Browser:
	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";


	$login_url = 'https://euniversity.uth.gr/unistudent/login.asp';

	
	$ch = curl_init(); 

	curl_setopt($ch, CURLOPT_URL,$login_url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/TERENASSLCA");
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	$login_result = curl_exec ($ch);
	
	//Now, use the session cookie to actually log in:
	$POSTFIELDS = "userName=". $username ."&pwd=". $password .'&submit1=%C5%DF%F3%EF%E4%EF%F2&loginTrue=login';  

	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS);
	curl_setopt($ch, CURLOPT_REFERER, $login_url);
	$login_result = curl_exec ($ch);
	curl_close ($ch);

	$ch = curl_init('https://euniversity.uth.gr/unistudent/stud_NewClass.asp?studPg=1&mnuid=diloseis;newDil&');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	$resp = curl_exec($ch);
	curl_close($ch);

	unlink($cookie_file_path);
	
	$dilosi_resp = array();
	$temp_array = array();
	
	$dom = new DOMDocument();
	@$dom->loadHTML($resp);
	$xpath = new DOMXPath($dom);
        $tds = $xpath->query('//td[@nowrap="true"]');
        
        for($i = 0; $i < $tds->length; $i++){
            $math = $tds->item($i)->textContent;
            if(strpos($math, '(') !== false){
                $first = strpos($math, '(');
                $temp_array["mathima"] = substr($math, $first);
                array_push($dilosi_resp, $temp_array);
            }
        }
        
        echo json_encode($dilosi_resp, JSON_UNESCAPED_UNICODE);

    }
    else if($tag == 'announcement'){
	$username = $_POST['username'];
	$password = $_POST['password'];
		
	$cookie_name = '/cookie_' . $username . '.txt';
	//set cookies to a file
	$cookie_file_path = getcwd() . $cookie_name;
	
	//Emulating Chrome Browser:
	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";

	$login_url = 'https://www.inf.uth.gr/wp-login.php';

	//Now, use the session cookie to actually log in:
	$POSTFIELDS = "log=". $username ."&pwd=". $password .'&wp-submit=%CE%A3%CF%8D%CE%BD%CE%B4%CE%B5%CF%83%CE%B7&testcookie=1';  

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$login_url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/TERENASSLCA");
	curl_setopt($ch, CURLOPT_REFERER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
	$login_result = curl_exec ($ch);
	curl_close ($ch);

	$ch = curl_init('http://www.inf.uth.gr/?cat=5&feed=rss2&lang=el');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	$resp = curl_exec($ch);
	curl_close($ch);

	unlink($cookie_file_path);
	
	echo $resp;
    }
     else if($tag == 'bus'){
	//Emulating Chrome Browser:
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.";


	$url = 'http://astikovolou.gr/index.php?option=com_content&view=article&id=23&Itemid=182';
     
	$ch = curl_init(); 

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);

	$dom = new DOMDocument();
	@$dom->loadHTML($resp);
	
	$buses_resp = array();
	$temp_array = array();
	
	$tr = $dom->getElementsByTagName('tr');
	
	for($i = 0; $i < $tr->length; $i++){
            if(strpos($tr->item($i)->nodeValue, "No") === false){
                continue;
            }
            
            $td = $tr->item($i)->getElementsByTagName('td');
            $temp_array["no"] = $td->item(0)->nodeValue;
            $temp_array["dromologio1"] = $td->item(1)->nodeValue;
            
            $i++;
            $td = $tr->item($i)->getElementsByTagName('td');
            $temp_array["dromologio2"] = trim($td->item(0)->nodeValue);

            array_push($buses_resp, $temp_array);
	}
	
	echo json_encode($buses_resp, JSON_UNESCAPED_UNICODE);
     }
     else if($tag == 'bus_line_find'){
	$name = $_POST['name'];
	$position = $_POST['position'];
	
	//Emulating Chrome Browser:
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.";

	$url = 'http://astikovolou.gr/index.php?option=com_content&view=article&id=23&Itemid=182';
     
	$dom = new DOMDocument();
	$dom->loadHTMLFile($url);
	$dom->preserveWhiteSpace = false;

	$content = $dom->getElementsByTagName("tr");
	$find_url = null;
	
	foreach($content as $item){
	  if(strpos($item->nodeValue, $name)){
	        
	    $find_url = 'http://astikovolou.gr/' . trim($item->getElementsByTagName('a')->item(0)->getAttribute('href'));
	    break;
	  }
	  
	}
	
	$dom = new DOMDocument();
	$dom->loadHTMLFile($find_url);
	$dom->preserveWhiteSpace = false;
	$content = $dom->getElementsByTagName("tr");
	$find_url = null;
	
	foreach($content as $item){
	  $find_url = 'http://astikovolou.gr/' . trim($item->getElementsByTagName('a')->item($position)->getAttribute('href'));
	  break;
	}

	if($find_url != null){
	  echo $find_url;
	}
	else{
	  echo "false";
	}
     }
     else if($tag == 'menu_find'){
     
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.";

	$url = 'http://www.uth.gr/students/student-welfare/programma-sitisis';
     
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);
	
	$dom = new DOMDocument();
        @$dom->loadHTML($resp);
        $xpath = new DOMXPath($dom);
        
        $lesxi_array = array();
       
        $tab = $xpath->query('//div[@id="content80"]');
        $p = $tab->item(0)->getElementsByTagName('p');
        $a = $tab->item(0)->getElementsByTagName('a');
        
        $lesxi_array["keimeno"] = $p->item(2)->nodeValue . "\n\n" . $p->item(3)->nodeValue . "\n\n" . $p->item(4)->nodeValue . "\n\n" ;
	
	$lesxi_array["link1"] = "http://uth.gr" . $a->item(0)->getAttribute('href');
	$lesxi_array["link2"] = "http://uth.gr" . $a->item(1)->getAttribute('href');
	
	echo json_encode($lesxi_array, JSON_UNESCAPED_UNICODE);
	
     }
     else if($tag == 'fisiognomia'){
     
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.";

	$url = 'http://www.inf.uth.gr/?page_id=2746';
     
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);

	if($resp != false){
	  echo $resp;
	}
	else{
	  echo "false";
	}
     }
     else if($tag == 'courses'){
     
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.";

     
	$url = 'http://www.inf.uth.gr/?page_id=7758';
     
	$ch = curl_init(); 

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);
	
	$dom = new DOMDocument();
	@$dom->loadHTML($resp);
	$xpath = new DOMXPath($dom);
	
	$courses_resp = array();
	$temp_array = array();
	
        $tabs = $xpath->query('//div[@id="tabs-1-1"]');
        $h3 = $tabs->item(0)->getElementsByTagName('h3');
        
        for($i = 0; $i < $h3->length; $i++){
            $temp_array["mathima"] = $h3->item($i)->nodeValue;
            array_push($courses_resp, $temp_array);
        }
	echo json_encode($courses_resp, JSON_UNESCAPED_UNICODE);
     }
     else if($tag == 'didaskontes'){
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.";

	$url = 'http://www.inf.uth.gr/?page_id=54';
     
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);

	$didask_resp = array();
	$temp_array = array();
	
	$dom = new DOMDocument();
	
	@$dom->loadHTML($resp);
	$xpath = new DOMXPath($dom);
	
        $cat = $dom->getElementsByTagName('h6');
        $name = $dom->getElementsByTagName('h3');
        $tabs = $xpath->query('//div[@class="pf-content"]');

        $m = 0;
        
        for($i = 0; $i < $cat->length; $i++){
            $items = $tabs->item($i+1)->getElementsByTagName('tbody');
            for($j = 0; $j < $items->length; $j++){
                $trs = $items->item($j)->getElementsByTagName('tr');
                
                for($k = 0; $k < $trs->length; $k++){}
                
                $prof = $trs->item($k-1);
                $temp_array["onoma"] = $name->item($m)->nodeValue;
                $temp_array["katigoria"] = $cat->item($i)->nodeValue;
                $temp_array["profil"] = $prof->c14n();
                array_push($didask_resp, $temp_array);

                $m++;
            }
            
        }
        
        echo json_encode($didask_resp, JSON_UNESCAPED_UNICODE);
     }
     else if($tag == 'genikes_ann'){
     	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";

	$url = 'http://www.inf.uth.gr/?cat=24&feed=rss2&lang=el';
     
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);

	if($resp != false){
	  echo $resp;
	}
	else{
	  echo "false";
	}
	
     }
     else if($tag == 'dilosi_find'){
     	$agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";

     
	$url = 'http://www.inf.uth.gr/?page_id=7758';
     
	$ch = curl_init(); 

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	$resp = curl_exec($ch);
	curl_close($ch);

	
	
	if($resp != false){
	  echo $resp;
	}
	else{
	  echo "false";
	}
	
     }
     else if($tag == 'aithousa_find'){
     
	$aithousa = $_POST['aithousa'];
	
	$str = '';
	$user = 'root';
        $pass = '';
        $db = 'map_db';
        
        $conn = new mysqli('localhost', $user, $pass, $db) or die('Unable to connect');
        mysqli_query($conn, "SET NAMES 'utf8'");
        $result = mysqli_query($conn, "SELECT lat , lng FROM map_table WHERE name = '$aithousa' ");
        
        if(mysqli_num_rows($result)){
            $find = mysqli_fetch_row($result);
            $str = $find[0] . "," . $find[1];
        }
        
        echo $str;
        
        mysqli_close($conn);

	//$map = getcwd() . '/map';
	//$file = file_get_contents($map);
    
	//$aithouses = explode("\n", $file);
	
	//for($i=0; $i<count($aithouses); $i++){
	//    if(strpos($aithouses[$i], $aithousa) !== false){
	//      $find = explode(":", $aithouses[$i]);
	//      echo $find[1];
	//      break;
	//    }
	//}
	
     }
     else if($tag == 'course_find'){
        $agent = "Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14";

	$course = $_POST['course'];
	$user = 'root';
        $pass = '';
        $db = 'courses_db';
        
        $conn = new mysqli('localhost', $user, $pass, $db) or die('Unable to connect');
        mysqli_query($conn, "SET NAMES 'utf8'");
        $result = mysqli_query($conn, "SELECT page_id FROM coursesIDs_table WHERE course_id = '$course' ");
        
        if(mysqli_num_rows($result)){
            $course_url =  "http://www.inf.uth.gr/?page_id=" . mysqli_fetch_object($result)->page_id ;
            
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL,$course_url);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            $resp = curl_exec($ch);
            curl_close($ch);

            if($resp != false){
                echo $resp;
            }
            else{
                echo "false";
            }
        
        }else{
            echo "false";

        }
        
        mysqli_close($conn);

     }
    else if($tag == 'map'){
	$str = '';

	$user = 'root';
        $pass = '';
        $db = 'map_db';
        
        $conn = new mysqli('localhost', $user, $pass, $db) or die('Unable to connect');
        mysqli_query($conn, "SET NAMES 'utf8'");
        $result = mysqli_query($conn, "SELECT * FROM map_table");
        
        while($row = mysqli_fetch_row($result)){
            $str .= $row[0] . ":" . $row[1] . "," . $row[2] . "\n";
        }
        
        echo $str;
        
        mysqli_close($conn);
    }
    
}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter 'tag' is missing!";
    
    echo json_encode($response);
}

?>
