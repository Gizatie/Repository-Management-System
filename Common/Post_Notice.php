<?php

session_start();
if (isset($_SESSION["StaffId"]))
{
    $_SESSION["Sidebar"] = "Post_Notice";
    require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php';
?>
<body>
<?php
        $currentUserType = $_SESSION['StaffType'];
        switch ($currentUserType) {
            case 'Researcher':
                require_once '../Researcher/sidebar.php';
                break;
            case 'Department':
                require_once '../Department/sidebar.php';
                break;
            case 'RCC':
                require_once '../RCC/sidebar.php';
                break;
            case 'RCD':
                require_once '../RCD/sidebar.php';
                break;
            case 'Faculty':
                require_once '../Faculty/sidebar.php';
                break;
            case 'Admin':
                require_once '../Admin/sidebar.php';
                break;
        }
        ?>
<!-- /# sidebar -->

<?php
//require_once 'sidebar.php';
require_once '../Common/Header.php';

if (isset($_POST["Submit"])) {
    $Old = $_POST["Old"];
    $New1 = $_POST["New1"];
    $New2 = $_POST["New2"];
    $User = $_POST["User"];
    $StaffId = $_SESSION["StaffId"];

//to be get from the session which is added  during login to his home page

    $stmt = $conn->prepare("select * from users where password=? and StaffId=?");
    $stmt->bind_param("ss",$Old,$StaffId);
    $stmt->execute();
    $Result=$stmt->get_result();
    $Error= array();
    if ($Result->num_rows>0 ){
        if ($New1===$New2){
            $password=md5($New2);
            if (empty($User)){
                $stmt = $conn->prepare("update  users set  password=? and user_name=? where StaffId=?");
                $stmt->bind_param("sss", $password, $user, $StaffId);
                if ($stmt->execute()){
                    echo "<script>alert('Password Successfully changed')</script>";
                }else{
                    echo "<script>alert('Password  changed Failed')</script>";
                }

            }else{
                $password=md5($New2);
                $stmt = $conn->prepare("update  users set  password=?  where StaffId=?");
                $stmt->bind_param("ss", $password, $StaffId);
                if ($stmt->execute()){
                    echo "<script>alert('Password Successfully changed')</script>";
                }else{
                    echo "<script>alert('Password  changed Failed')</script>";
                    echo $stmt->error;
                }

            }
           }else{
            echo "<script>alert('The Two Passwords Didnt Match)</script>";
        }

    }else{
        echo "<script>alert('Wrong Old Password')</script>";
    }

}

?>


 <div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">

                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">Researcher</a>
                                </li>
                                <li class="breadcrumb-item active">Change Password</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
                    <!-- /# column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-title">
                                <h4></h4>

                            </div>
                            <div class="card-body">
                                <div class="horizontal-form-elements">
                                    <form action="Change%20Password.php" method="post" enctype="multipart/form-data">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="control-label">Enter New User Name <small>(if you want to change youre user name)</small> </label>
                                                    <input type="text"  name="User" class="form-control" placeholder="<?php echo $_SESSION["UserName"]?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Old Password </label>
                                                    <input type="text" required name="Old" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter New Password </label>
                                                    <input type="text" required name="New1" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter New Password  again</label>
                                                    <input type="text" required name="New2" class="form-control">
                                                </div>
                                            </div>
                                            <!-- /# column -->
                                            <div class="col-lg-6">

                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="Submit" value="Submit"
                                                            class="btn btn-default">Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /# column -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /# card -->
                </div>

            </section>
            <!-- /# column -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div id="extra-area-chart"></div>
                <div id="morris-line-chart"></div>
                <div class="footer">
                    <p>2018 © Admin Board. -
                        <a href="#">example.com</a>
                    </p>
                </div>
            </div>
        </div>
        </section>
    </div>
</div>


<?php require_once '../Common/Footer.php';
}else{
    header("Location: ../page-login.php");
}
?>
</body>
</html>

<script>
    function Participants(v = '') {

        let par = '';
        var fff = document.getElementById("par");

        if (v > 0) {
            fff.innerHTML = '';
            for (let i = 0; i < v; i++) {
                fff.innerHTML += '<div class="form-group"><label class="control-label">Enter Participant ID</label><input type="number" min="1" max="10" class="form-control" name="participant_' + (1 + i) + '"></div>'
            }
        }
    }
</script>