<?php

session_start();  

// importing php mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// adding required files for phpmailer 
require dirname(__FILE__) . '/libs/phpmailer/Exception.php';
require dirname(__FILE__) . '/libs/phpmailer/PHPMailer.php';
require dirname(__FILE__) . '/libs/phpmailer/SMTP.php';

class dboperations {
    private $con;
    function __construct(){

        require_once dirname(__FILE__) . "/conn.php";
        $db  = new dbconnect;
        $this->con = $db->connect();
    }

    // registration 
    public function UserRegister($name, $email, $password){  
        $randomOtp = rand(100000, 999999);  // random otp for init :P
        $password = md5($password);  // cook md5 format
        $stmt = $this->con->prepare("INSERT INTO users(name, email, password, otp) VALUES(?, ?, ?, ?)"); 
        $stmt->bind_param('ssss', $name, $email, $password, $randomOtp); 
        
        if($stmt->execute()){
            // Registration Successful
            return true;
        }
        
    }

    // login
    public function Login($email, $password){  
        $password = md5($password);
        $stmt = $this->con->prepare("SELECT id, name, email FROM users WHERE email = ? AND password = ? ");  
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $stmt->bind_result($id, $name, $email);
        
        
        $users = array();
        if($stmt->fetch()){ // if details matched
            $user = array();
            $user['id']         = $id;
            $user['name']   = $name;
            $user['email']      = $email;
            array_push($users, $user);
            
            // setting up data in session key
            $_SESSION['login'] = true;  
            $_SESSION['uid'] = $users[0]['id'];  
            $_SESSION['name'] =  $users[0]['name'];  
            $_SESSION['email'] = $users[0]['email'];  
            return true;
        } else {
            return false;
        }
    }

    // check if user exist
    public function isUserExist($email){  
        $stmt = $this->con->prepare("SELECT id from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }  

    //get password in md5 format by email
    public function getUserPasswordByEmail($email){
        $stmt = $this->con->prepare("SELECT password from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        return $password;
    }

    // change password
    public function changeUserPassword($oldPassword, $newPassword, $email){
        $oldPassword = md5($oldPassword); // cooking md5 format
        $newPassword = md5($newPassword); // cooking md5 format
        $getDBPassword = $this->getUserPasswordByEmail($email); // getting old pass md5 format from DB for matching

        // if matched, change password with the new one
        if($oldPassword == $getDBPassword){
            $stmt = $this->con->prepare("UPDATE users SET password =  ? WHERE email = ?");
            $stmt->bind_param("ss", $newPassword, $email);
            if($stmt->execute()){
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        } else {
            echo json_encode(['status' => 'mismatch']);
        }
    }

    //reset password
    public function resetPassword($email) {
        
        $randomOtp = rand(100000,999999); // generating random otp
        $stmt = $this->con->prepare("UPDATE users SET otp = ? WHERE email = ?");
        $stmt->bind_param('ss', $randomOtp, $email);
        if($stmt->execute()){
            // sending otp to the user
            $this->sendOtp($randomOtp, $email);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    //get DB otp by user's email
    public function getOtp($email){
        $stmt = $this->con->prepare("SELECT otp from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($otp);
        $stmt->fetch();
        return $otp;
    }

    public function otpCheck($otp, $email) {
        $dbOtp = $this->getOtp($email);
        // checking if OTP matched
        if($dbOtp == $otp){
            // matched! now generate a new password and send to user's email
            $randomPassword = rand(1000000000,9999999999);
            $finalGeneratedPassword = md5($randomPassword);
            $stmt = $this->con->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param('ss', $finalGeneratedPassword, $email);

            if($stmt->execute()){
                // reset otp in DB so that previous won't work anymore
                $randomOtp = rand(10000000,99999999);
                $stmtOtp = $this->con->prepare("UPDATE users SET otp = ? WHERE email = ?");
                $stmtOtp->bind_param('ss', $randomOtp, $email);
                $stmtOtp->execute();

                // sending new password
                $this->sendPassword($randomPassword, $email);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // mail OTP
    public function sendOtp($otp, $email) {

        $mail = new PHPMailer; //PhP Mailer Instance
        // $mail->SMTPDebug = 3; //Enable SMTP Debugging
        $mail->isSMTP(); //Enable PHPMailer for SMTP Use
        $mail->Host = "SMTP_HOST"; //SMTP Host Name
        $mail->SMTPAuth = true;  //Enable SMTP Authentication
        $mail->Username = "noreply@yourmail.com"; //Email Address                 
        $mail->Password = "YOUR_PASSWORD"; //Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  //Enable TLS Encryption
        $mail->Port = 465; //SMTP Port
         
        $mail->setFrom('noreply@yourmail.com', 'NAME'); //Sender Email Address
        $mail->addReplyTo('noreply@yourmail.com', 'NAME'); //Reply To Email Address
        $mail->addAddress($email); // Send To Email Address
        $mail->Subject = "Your OTP"; //Subject
        $mail->Body = "You have requested to reset your password. Here is your verification OTP: $otp"; //Email Body
         
        //send the message, check for errors
        if (!$mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    // mail Password
    public function sendPassword($newPassword, $email) {

        $mail = new PHPMailer; //PhP Mailer Instance
        // $mail->SMTPDebug = 3; //Enable SMTP Debugging
        $mail->isSMTP(); //Enable PHPMailer for SMTP Use
        $mail->Host = "SMTP_HOST"; //SMTP Host Name
        $mail->SMTPAuth = true;  //Enable SMTP Authentication
        $mail->Username = "noreply@yourmail.com"; //SMTP email username [email address]                 
        $mail->Password = "YOUR_PASSWORD"; //Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  //Enable TLS Encryption
        $mail->Port = 465; //SMTP Port
         
        $mail->setFrom('noreply@yourmail.com', 'NAME'); //Sender Email Address
        $mail->addReplyTo('noreply@yourmail.com', 'NAME'); //Reply To Email Address
        $mail->addAddress($email); // Send To Email Address
        $mail->Subject = "Your New Password"; //Subject
        $mail->Body = "You have requested a new password. Here is your new Password: $newPassword"; //Email Body
         
        //send the message, check for errors
        if (!$mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    // logout
    public function logout() {
        // remove all vars 
        session_unset();
        // destroy    
        session_destroy();  
    }

}
