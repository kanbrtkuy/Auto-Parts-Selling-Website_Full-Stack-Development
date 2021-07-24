<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Agent - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-info">
    <?php
        $err_msg = "";
        $request = $_SERVER['REQUEST_METHOD'];
        require_once 'dblogin.php';

        $conn = new mysqli($db_hostname,$db_username,$db_password,$db_database);
        if($conn->connect_error){
            die("Connection failed" . mysqli_connect_error);
        }

        if($request == "POST"){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql1 = "SELECT agentPassword_011 AS id FROM AgentPassword_011 WHERE agentPassword_011 = ? AND agentUsername_011 = ?";

            $check = $conn->prepare($sql1);
            $check->bind_param("ss",$password,$username);
            $check->execute();
            $result = $check->get_result();

            if($result->num_rows === 0){
                $err_msg="Sorry, username or password is incorrect.";
            }
            else{
                $row01 = $result->fetch_assoc();
                $_SESSION['agt_id'] = $row01['id'];
                $_SESSION["loggedin"] = true;
                header("location: CompanyAgent.php");
            }
        }
    ?>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6  d-lg-block "><img src="img/dal.png" style="width: 100%;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome!</h1>
                                    </div>
                                    <form class="user" name="Login" method="post" action="agent_login_011.php">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="username" name = "username"
                                                placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name = "password"placeholder="Password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" id="loginButton">
                                            Login
                                        </button>
                                    </form>
                                    <div class="col-lg-12"><p style="color: #FF0000;"><?php echo $err_msg; ?></p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>