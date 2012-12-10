<?php

/**** ChromePhp Debugger ****/
include 'ChromePhp.php';
//ChromePhp::log('hello world');
ChromePhp::log($_SERVER);

// using labels
foreach ($_SERVER as $key => $value) {
    ChromePhp::log($key, $value);
}

// warnings and errors
//ChromePhp::warn('this is a warning');
//ChromePhp::error('this is an error');

/***** END ChromePhp Debugger ****/


/**** FirePHP Debugger ****/
require_once('FirePHPCore/FirePHP.class.php');
ob_start();
/***** END FirePHP Debugger ****/

/*
    Adapted from GNU http://www.html-form-guide.com/php-form/

*/
require_once("class.phpmailer.php");
require_once("formvalidator.php");

class RadStepAuthentication{
	
	//CLASS VARIABLES
	var $error_message;
	var $db_path;
	var $db;
	var $db_userstablename;
	var $rand_key;
	
	//INSTANTIATION
	function RadStepAuthentication(){
		$this->rand_key = 'ycgfJ4i77KNo0h0';
	}
	
	//PUBLIC FUNCTIONS
	function InitDB($db_path,$userstablename)
    {
        $this->db_path  = $db_path;
        $this->db_userstablename = $userstablename;
        
    }
	function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }
	
	
	function Login()
    {
        if(empty($_POST['username_email']))
        {
            $this->HandleError("Username/E-mail is empty!");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }
        
        $username_email = trim($_POST['username_email']);
        $password = trim($_POST['password']);
        
        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($username_email,$password))
        {
            return false;
        }
        
        $_SESSION[$this->GetLoginSessionVar()] = $_SESSION['username'];
        
        return true;
    }
	
	//
	function CheckLoginInDB($username_email,$password)
    {
    	$this->DBConnect();
		
        if(!$this->db)
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
        $username_email = $this->SanitizeForSQL($username_email);
        $pwdmd5 = md5($password);
        $qry = "SELECT firstname, lastname, email, username FROM '$this->db_userstablename' WHERE (username='$username_email' OR email='$username_email') AND passhash='$pwdmd5' AND confirmcode='y'";
        
        $result = $this->db->query($qry);
        
        if(!$result)
        {
        	
            $this->HandleError("Error logging in. The username or password does not match" . '\n' . $qry);
			
            return false;
        }
        
        $row = $result->fetchArray();
        
        
        $_SESSION['name_of_user']  = $row['firstname'] . " " . $row['lastname'];
        $_SESSION['email_of_user'] = $row['email'];
		$_SESSION['username'] = $row['username'];
        
        return true;
    }
	
	function DBConnect()
    {

        try{
        	 $this->db = new SQLite3($this->db_path);
		}
		catch(Exception $e){
			$this->HandleDBError($e . "\n" . "Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
		}

        /*if(!$this->db->query("SET NAMES 'UTF8'",$this->db))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }*/
        return true;
    }    

    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
	
    //PUBLIC HELPER FUNCTIONS
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }    
    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
	
	function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }  
	function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
	function SanitizeForSQL($str)
    {
        if( function_exists( "sqlite_escape_string" ) )
        {
              $ret_str = sqlite_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
 	/*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }    
}



?>
	
	

