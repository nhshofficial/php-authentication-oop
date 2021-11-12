<?php

    include_once('operations.php');
    // check if has active session if has redirect to dashboard
    if(($_SESSION)){  
        header("Location: dashboard.php");  
    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="./assets/css/style.css" rel="stylesheet">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
	<script src="./assets/js/jquery-3.6.0.min.js"></script>
</head>
<body>
<!-- bootstrap nav bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- logo -->
        <a class="navbar-brand" href="./">
            <img src="./assets/images/bhw_logo.png" alt="Bangladesh Health Watch Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./">Home</a>
                </li>
            </ul>
            <div class="navbar-nav">
                <!-- register modal trigger -->
                <button type="button" class="btn btn-bhw" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
            </div>
        </div>
    </div>
</nav>


<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 my-5">
            <div class="card">
                <div class="card-header">
                    <h3>Login</h3>
                </div>
                <div class="card-body">
                    <form name="login" method="post"> <!-- login form starts -->
                        <div class="mb-3">
                            <label for="loginemail" class="col-form-label">Your Email:</label>
                            <input name="emailid" id="loginemailid" class="form-control" required="required" type="email" placeholder="yourmail@mail.com"/>   
                        </div>
                        <div class="mb-3">
                            <label for="loginpassword" class="col-form-label">Password:</label>
                            <input name="password" id="loginpassword" class="form-control" required="required" type="password" placeholder="password" />   
                        </div>
                    </form> <!-- login form ends -->
                </div> <!-- card body -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="m-0">Forgot password? <a href="./reset.php">Reset here</a>.</p>
                        <input type="button" id="btnlogin" class="btn btn-bhw" name="login" value="Login" />   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="register" method="post">  <!-- register form starts -->
            <div class="mb-3">
                <label for="registerUserName" class="col-form-label" data-icon="u">Your Name:</label>  
                <input id="registerUserName" class="form-control" name="name" required="required" type="text" placeholder="your name" />  
            </div>
            <div class="mb-3">
                <label for="registerEmail" class="col-form-label" data-icon="e">Your Email:</label>  
                <input id="registerEmail" class="form-control" name="emailid" required="required" type="email" placeholder="youremail@mail.com" />  
            </div>
            <div class="mb-3">
                <label for="registerPassword" class="col-form-label" data-icon="p">Password:</label>  
                <input id="registerPassword" class="form-control" name="password" required="required" type="password" placeholder="password" />  
            </div>
            <div class="mb-3">
                <label for="registerConfirmPassword" class="col-form-label" data-icon="c">Confirm Password:</label>  
                <input id="registerConfirmPassword" class="form-control" name="confirm_password" required="required" type="password" placeholder="confirm password" />  
            </div>

            <div class="d-flex justify-content-end gutters">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                <input type="button" id="btnRegister" class="btn btn-bhw" name="register" value="Sign up" />   
            </div>
        </form>   <!-- register form ends -->
      </div>
    </div>
  </div>
</div>

<script src="./assets/js/bootstrap.bundle.min.js"></script>
<script src="./assets/js/sweetalert.min.js"></script>

    <script>
        $(document).ready(function(){

            // User login
            $("#btnlogin").click(function(){
                const email = $("#loginemailid").val();
                const password = $("#loginpassword").val();
                //   alert(email + password);

                if(email.length != "" && password.length != ""){
                    $.ajax({
                        type : 'POST',
                        url  : 'helper.php',
                        data : {'type': 'login', 'emailid': email, 'password': password},
                        dataType : 'JSON',
                        success : function(feedback){
                            if(feedback.status === "success"){
                            swal({
                                title: "Successful!",
                                text: "You have successfully logged in. Click OK to go to the Dashboard",
                                icon: "success",
                                })
                                .then((value) => {
                                    if(value){
                                        window.location = "dashboard.php";
                                    }
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Please provide valid login credentials!",
                                    icon: "error",
                                    button: "Close",
                                });
                            } 
                        },
                        error:function(xhr,status,error) {
                            console.log(error);
                        }
                    });
                } else {
                    swal({
                        title: "Error!",
                        text: "Please fill all the required fields!",
                        icon: "error",
                        button: "Close",
                    });
                } 
            });

            // User register
            $("#btnRegister").click(function(){
                const name = $("#registerUserName").val();
                const email = $("#registerEmail").val();
                const password = $("#registerPassword").val();
                const confirmPassword = $("#registerConfirmPassword").val();
                //   alert(name + email + password + confirmPassword);

                if(name.length != "" && email.length != "" && password.length != "" && confirmPassword.length != ""){
                    $.ajax({
                        type : 'POST',
                        url  : 'helper.php',
                        data : {
                            'type': 'register',
                            'name': name,
                            'emailid': email,
                            'password': password,
                            'confirm_password': confirmPassword
                        },
                        dataType : 'JSON',
                        success : function(feedback){
                            if(feedback.status === "success") {
                                swal({
                                    title: "Congratulation!",
                                    text: "Registration successful! Please login to view dashboard.",
                                    icon: "success",
                                    button: "Ok",
                                });
                                setTimeout(() => {
                                    window.location = "index.php";
                                }, 3000);
                            } else if (feedback.status === "error") {
                                swal({
                                    title: "Registration failed!",
                                    text: "A server back-end error occurred during registration!",
                                    icon: "error",
                                    button: "Close",
                                });
                            } else if (feedback.status === "emailError") {
                                swal({
                                    title: "Email Error!",
                                    text: "An user already exist with the same email!",
                                    icon: "warning",
                                    button: "Close",
                                });
                            } else if (feedback.status === "passwordError") {
                                swal({
                                    title: "Password Error!",
                                    text: "Password didn't match for Confirm Password!",
                                    icon: "warning",
                                    button: "Close",
                                });
                            } 
                        },
                        error:function(xhr,status,error) {
                            console.log(error);
                        }
                    });
                } else {
                    swal({
                        title: "Error!",
                        text: "Please fill all the required fields!",
                        icon: "error",
                        button: "Close",
                    });
                }
            });
        });
    </script>
</body>
</html>