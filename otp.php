<?php   
    $email = $_GET['email'];
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Page</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
	<script src="./assets/js/jquery-3.6.0.min.js"></script>
</head>
<body>
<!-- bootstrap nav bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
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
        </div>
    </div>
</nav>


<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card my-5">
                <div class="card-header">
                    <h4 class="m-0">OTP verification</h4>
                </div>
                <div class="card-body">
                    <form name="otpVerify" method="post"> <!-- form starts -->
                        <div class="mb-3">
                            <label for="userOtp" class="col-form-label">Enter OTP:</label>
                            <input name="userOtp" id="userOtp" class="form-control" required="required" type="text" placeholder="Enter OTP code"/>

                            <input name="email" id="email" class="form-control" required="required" type="hidden" value="<?php echo $email; ?>" />   
                        </div>
                        
                        <div class="d-flex justify-content-end gutters">
                            <input type="button" id="btnOtp" class="btn btn-bhw" name="otp" value="Verify" />   
                        </div>
                    </form> <!-- form ends -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BootStrap Js -->
<script src="./assets/js/bootstrap.bundle.min.js"></script>
<!-- Sweet alert -->
<script src="./assets/js/sweetalert.min.js"></script>
<script>
    $(document).ready(function(){

        // Change Password
        $("#btnOtp").click(function(){
            const otp = $("#userOtp").val();
            const email = $("#email").val();
            // alert(otp + email);
            if(otp.length != "" && email.length != ""){
                $.ajax({
                    type : 'POST',
                    url  : 'helper.php',
                    data : {'type': 'otpcheck', 'otp': otp, 'email': email},
                    dataType : 'JSON',
                    success : function(feedback){
                        if(feedback.status === "success"){
                            swal({
                                title: "Successful!",
                                text: "A new password has been sent to your email. You will be redirected to login page in 3 seconds.",
                                icon: "success",
                                button: "Ok",
                            });
                            setTimeout(() => {
                                window.location = "index.php";
                            }, 3000);
                        } else {
                            swal({
                                title: "Failed!",
                                text: "Enter valid OTP code.",
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
    });
</script>
</body>
</html>