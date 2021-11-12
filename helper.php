<?php

    include_once('operations.php');  
       
    $dbObj = new dboperations;  // creating instance

    // for login
    if($_POST['type'] == 'login'){  
        $emailid = $_POST['emailid'];  
        $password = $_POST['password'];  
        $user = $dbObj->Login($emailid, $password);  
        if ($user) {  
            // login Success json response 
            echo json_encode(['status' => 'success']);
        } else {  
            // failed json response
            echo json_encode(['status' => 'error']);
            
        }  
    }  

    // for registration
    if($_POST['type'] == 'register'){ 
        $name = $_POST['name'];  
        $emailid = $_POST['emailid'];  
        $password = $_POST['password'];  
        $confirmPassword = $_POST['confirm_password'];  
        // checking if passwords matching
        if($password == $confirmPassword){  
            // checking if user already exist in DB
            $email = $dbObj->isUserExist($emailid);
            if(!$email){ 
                // user not exist, proceed to register 
                $register = $dbObj->UserRegister($name, $emailid, $password);  
                if($register){  
                    // registration success
                    echo json_encode(['status' => 'success']); 
                }else{  
                    // registration error [server]
                    echo json_encode(['status' => 'error']); 
                }  
            } else {  
                // registration error [email exist]
                echo json_encode(['status' => 'emailError']);  
            }  
        } else {  
            // registration error [password mismatch]
            echo json_encode(['status' => 'passwordError']);  
        }  
    } 
    
    // for changing password
    if($_POST['type'] == 'changePassword'){  
        $currentPassword = $_POST['oldPassword'];  
        $newPassword = $_POST['newPassword'];  
        $email = $_POST['email'];  
        $dbObj->changeUserPassword($currentPassword, $newPassword, $email);  
        // not doing json response here because to show that json response can be derive from the class as well ;) check operations.php L 118 
    } 
    
    // check otp
    if($_POST['type'] == 'otpcheck'){  
        $email = $_POST['email'];  
        $otp = $_POST['otp'];  
        $execute = $dbObj->otpCheck($otp, $email);  
        if ($execute) {  
            echo json_encode(['status' => 'success']);
        } else {  
            echo json_encode(['status' => 'error']);
            
        }  
    } 
    
    // for reset password REQUEST 
    if($_POST['type'] == 'resetpassword'){  
        $email = $_POST['email'];  
        $dbEmail = $dbObj->isUserExist($email); // checking if user exist in DB
        
        if($dbEmail){
            // if user exist then initiate reset process by sending OTP
            $execute = $dbObj->resetPassword($email); // json response will come from related method in class
        } else {
            // oops! user not found in DB
            echo json_encode(['status' => 'emailError']);
        }
    } 
    // logout [remove session and session data]
    if($_POST['type'] == 'logout'){  
        $dbObj->logout();
        echo json_encode(['status' => 'success']);
    } 