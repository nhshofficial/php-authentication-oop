<?php   
    include_once('operations.php');
    // checking if has active session else redirect to homepage 
    if(!($_SESSION)){  
        header("Location: index.php");  
    }  
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
	<script src="./assets/js/jquery-3.6.0.min.js"></script>
</head>
<body>
<!-- Bootstrap nav bar -->
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
                <!-- change password modal trigger -->
                <button type="button" class="btn btn-bhw me-2" data-bs-toggle="modal" data-bs-target="#cpModal">Change Password</button>
            </div>
        </div>
    </div>
</nav>

<!-- Password change Modal -->
<div class="modal fade" id="cpModal" tabindex="-1" aria-labelledby="cpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cpModalLabel">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="changePassword" method="post"> <!-- change password form starts -->
            <div class="mb-3">
                <label for="oldPassword" class="col-form-label">Old Password:</label>
                <input name="oldPassword" id="oldPassword" class="form-control" required="required" type="password" placeholder="Enter old password"/>   
            </div>
            <div class="mb-3">
                <label for="newPassword" class="col-form-label">New Password:</label>
                <input name="newPassword" id="newPassword" class="form-control" required="required" type="password" placeholder="Enter new password" />   
                <input name="email" id="email" class="form-control" required="required" type="hidden" value="<?php echo $_SESSION['email'] ?>" />   
            </div>
            
            <div class="d-flex justify-content-end gutters">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                <input type="button" id="btnChangePass" class="btn btn-warning" name="login" value="Change Password" />   
            </div>
        </form> <!-- form ends -->
      </div>
    </div>
  </div>
</div>

<!-- user info -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card my-5">
                <div class="card-header">
                    <h4 class="m-0">User Info</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">Name: <?php echo $_SESSION['name']; ?></li>
                        <li class="list-group-item">Email: <?php echo $_SESSION['email']; ?></li>
                    </ul>
                </div>
                <div class="card-footer">
                    <form name="logout" method="post" class="m-0"> 
                        <input type="button" id="logout" class="btn btn-danger" name="logout" value="Logout" />   
                    </form> 
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
        $("#btnChangePass").click(function(){
            const currentPassword = $("#oldPassword").val();
            const newPassword = $("#newPassword").val();
            const email = $("#email").val();

            if(currentPassword.length != "" && newPassword.length != "" && email.length != ""){
                $.ajax({
                    type : 'POST',
                    url  : 'helper.php',
                    data : {'type': 'changePassword', 'oldPassword': currentPassword, 'newPassword': newPassword, 'email': email},
                    dataType : 'JSON',
                    success : function(feedback){
                        if(feedback.status === "success"){
                            swal({
                                title: "Successful!",
                                text: "Your password has been changed successfully.",
                                icon: "success"
                                })
                                .then((value) => {
                                    if(value) {
                                        window.location = "dashboard.php";
                                    }
                                });
                        } else if(feedback.status === "mismatch") {
                            swal({
                                title: "Password Error!",
                                text: "Your old password didn't match.",
                                icon: "error",
                                button: "Close",
                            });
                        } else {
                            swal({
                                title: "Failed!",
                                text: "A server back-end error occurred.",
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

        // logout
        $("#logout").click(function(){

                $.ajax({
                    type : 'POST',
                    url  : 'helper.php',
                    data : {'type': 'logout'},
                    dataType : 'JSON',
                    success : function(feedback){
                        if(feedback.status === "success"){
                            swal({
                                title: "Successful!",
                                text: "You have successfully logged out.",
                                icon: "success"
                                })
                                .then((value) => {
                                    if(value) {
                                        window.location = "index.php";
                                    }
                                });
                        } else {
                            swal({
                                title: "Failed!",
                                text: "A server back-end error occurred.",
                                icon: "error",
                                button: "Close",
                            });
                        }
                    },
                    error:function(xhr,status,error) {
                        console.log(error);
                    }
                });
        });
    });
</script>
</body>
</html>