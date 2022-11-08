<?php
session_start();
require_once('Common/DataBaseConnection.php');
// Create connection
$_SESSION["Sidebar"]="page-login";
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST["Submit"])) {

    $UserName = $_POST["UserName"];
    $UserPassword = md5($_POST["UserPassword"]);
    $stmt = $conn->prepare("select * from users where user_name=? and password=?");
    $stmt->bind_param("ss", $UserName, $UserPassword);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        $access_Time = $result["Access_Time"];
        $StaffId = $result["StaffId"];
        $_SESSION["UserName"] = $result["user_name"];
        $_SESSION["UserRole"] = $result["Role"];
        $stmt = $conn->prepare("select * from staffs where ID=?");
        $stmt->bind_param("s", $StaffId);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $Type = trim($result["Role"]);
        $_SESSION["StaffId"] = $StaffId;
        $_SESSION["StaffType"] = $Type;
        $_SESSION["Faculty"] = $result["Faculty"];
        $_SESSION["Committee"] = $result["Committee"];
        $_SESSION["Sidebar"] = 'home';
        $_SESSION["Department"] = $result["Department"];
        if ($access_Time !== "First Time") {
            switch ($Type) {
                case 'Researcher':
                    header("Location: Researcher/home.php");
                    break;
                case 'Department':
                    header("Location: Researcher/home.php");
                    break;
                case 'RCC':
                    header("Location: Researcher/home.php");
                    break;
                case 'Faculty':
                    header("Location: Researcher/home.php");
                    break;
                case 'RCD':
                    header("Location: Researcher/home.php");
                    break;
                case 'Student':
                    header("Location: Researcher/home.php");
                    break;
                case 'Admin':
                    header("Location: Admin/home.php");
                    break;
            }
        } else {
            header("Location: Admin/Change_Password.php");
        }
    }
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'logout') {
    session_destroy();
}
?>
<?php
require_once 'Common/Head.php';
?>
<!DOCTYPE html>
<html lang="en">
<body class="bg-primary">

    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="index.html"><span>DTU</span></a>
                        </div>
                        <div class="login-form">
                            <h4> Login Page</h4>
                            <form action="page-login.php" method="post">
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" name="UserName" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="UserPassword" class="form-control" placeholder="Password">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Remember Me
                                    </label>
                                    <label class="pull-right">
                                        <a href="#">Forgotten Password?</a>
                                    </label>

                                </div>
                                <button type="submit" name="Submit" value="Submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button>
                                <!-- <div class="social-login-content">
                                    <div class="social-button">
                                        <button type="button" class="btn btn-primary bg-facebook btn-flat btn-addon m-b-10"><i class="ti-facebook"></i>Sign in with facebook</button>
                                        <button type="button" class="btn btn-primary bg-twitter btn-flat btn-addon m-t-10"><i class="ti-twitter"></i>Sign in with twitter</button>
                                    </div>
                                </div> -->
                                <!-- <div class="register-link m-t-15 text-center">
                                    <p>Don't have account ? <a href="#"> Sign Up Here</a></p>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>