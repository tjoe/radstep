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
	
	  function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("reset code is empty!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);
        
		$user_rec = array();
		
		if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }
		
        if($user_rec['password'] != $code)
        {
            $this->HandleError("Bad reset code!");
            return false;
		}
		
		$new_password = 
		ChangePasswordInDB
        
        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Error updating new password");
            return false;
        }
        
        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Error sending new password");
            return false;
        }
        return true;
    }


    function GetUserFromEmail($email,&$user_rec)
    {
 
        $email = $this->SanitizeForSQL($email);
        	 
        $results = $this->db->query("SELECT * FROM '$this->$db_userstablename' WHERE email='$email'");  

        $result = $result->fetchArray();
        if(!$result)
        {
            $this->HandleError("There is no user with email: $email");
            return false;
        }else
			{
				$user_rec = $result;
			}
        
        return true;
    }
	
	
	function SendResetPasswordLink($user_rec)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your reset password request at ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
		$code = uniqid();
		
		$this->ChangePasswordInDB($user_rec, $code);
		
        $link = $this->GetAbsoluteURLFolder().'/resetpwd.php?email='.urlencode($email).'&code='.urlencode($code);

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
	
	function ChangePasswordInDB($user_rec, $newpwdhash)
    {
        $newpwdhash = $this->SanitizeForSQL($newpwdhash);
        
		try{
			$db->query("UPDATE ".$this->$db_userstablename." SET password='".$newpwdhash."' WHERE userid=".$user_rec['userid']);
		}catch (Exception $e){
			$this->HandleError("Update password failed!".'\n'.$e);
            return false;
		}
		
        return true;
    }
	

		
	//PRIVATE HELPER FUNCTIONS
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }
    
    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    } 
    
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
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
	function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
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
	
	

