<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION['Sidebar'] = 'Notification';
    require_once('../Common/DataBaseConnection.php');
    require_once '../Common/Head.php';
    require_once('../Common/Functions.php');
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
        }
        ?>
        <?php
        require_once '../Common/Header.php';
        if (isset($_POST['Submit'])) {

            $ID = $_SESSION['StaffId'];
            $Message  = $_POST['Message'];
            $Target = $_POST['Target'];
            $name = rand(1000, 10000) . '-' . $_FILES['File']['name'].'docx';
            $Faculty = 'ALL';
            $Department = $_POST['Department'];
            $tname = $_FILES['File']['tmp_name'];
            if (!$tname) {
                $name = 'NA';
            }
            $conn->begin_transaction();
            if ($_SESSION['StaffType'] === "RCD") {
                $Faculty = $_POST['Faculty'];
            }
            $stmt = $conn->prepare('INSERT INTO notification (PostedBy,Message,Faculty,Department, Target, File) VALUES (?, ?, ?, ?, ?, ?)');
            // echo $ID.' '.$Message. ' '.$Faculty.' '.$Department.' '.$Target.' '.$name.' '.$fileName;
            $stmt->bind_param('ssssss', $ID, $Message, $Faculty, $Department, $Target, $name);
            if ($stmt->execute()) {
                if ($tname) {
                    if (move_uploaded_file($tname, '../Documents/Other/Notifications/' . $name)) {
                        $conn->commit();
                        echo "<script>alert('Successfully Added')</script>";
                    } else {
                        $conn->rollback();
                        echo "<script>alert('Submitted Failed for Moving File')</script>";
                    }
                } else {
                    $conn->commit();
                    echo "<script>alert('Successfully Added')</script>";
                }
            } else {
                $conn->rollback();
                echo "<script>alert('Submitted Failed for Db Insertion')</script>";
            }
        }
        ?>
        <div class='content-wrap'>
            <div class='main'>
                <div class='container-fluid'>
                    <section id='main-content'>
                        <div class='row'>
                            <div class='col-lg-12'>
                                <div class='card'>
                                    <div class='card-title'>
                                        <h4>Post Notifications</h4>

                                    </div>
                                    <div class='card-body'>
                                        <div class='horizontal-form-elements'>


                                            <form action='Notification.php' method='post' enctype='multipart/form-data'>
                                                <div class='row'>
                                                    <div class='col-lg-6'>
                                                        <label class='control-label'>Message </label>
                                                        <textarea class='form-control' name='Message' rows='5' placeholder='Text input' required></textarea>

                                                    </div>
                                                    <div class='col-lg-5'>
                                                        <label class='control-label'>Select Target</label>

                                                        <select class='form-control' name='Target' required>
                                                            <option value='0'>Select Tagret</option>
                                                            <option value='Academicians'>All Academicians </option>
                                                            <option value='Researchers'>Researchers</option>
                                                            <option value='Technology Transfer'>Technology Transferors </option>
                                                            <option value='Community Service'>Community Servers</option>
                                                            <option value='Thesis'>Postgraduate Students </option>
                                                            <option value='Project'>Graduate Students </option>
                                                            <option value='Other'>Other</option>
                                                        </select>
                                                    </div>
                                                    <?php
                                                    if ($_SESSION['StaffType'] === "RCD") {
                                                    ?>
                                                        <div class='col-lg-4'>
                                                            <label class='control-label'>Select Faculty</label>

                                                            <select class='form-control' name='Faculty' required>
                                                                <option value='0'>Select Faculty</option>
                                                                <option value='Technology'>Technology</option>
                                                                <option value='Health'>Health</option>
                                                                <option value='All'>All</option>
                                                            </select>
                                                        </div>
                                                        
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class='col-lg-4'>
                                                            <label class='control-label'>Department</label>

                                                            <select class='form-control' name='Department' required>
                                                                <option value='0'>Select Department </option>
                                                                <option value='Computer Science'>Computer Science</option>
                                                                <option value='Information Technology'>Information Technology</option>
                                                                <option value='All'>All</option>
                                                            </select>
                                                        </div>
                                                    <div class='col-lg-4'>
                                                        <label class='control-label'>Upload The File</label>
                                                        <div class='col-sm-10'>
                                                            <input type='file' name='File' class='form-control'>
                                                        </div>
                                                    </div>
                                                    <div class='row col-lg-12' id='par'>

                                                    </div>
                                                    <div class='form-group'>
                                                        <div class='col-sm-offset-2 col-sm-10'>
                                                            <button type='submit' name='Submit' value='Submit' class='btn btn-default'>Post
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                        <!-- /# column -->
                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </div>
                <?php require_once '../Common/Footer.php';?>
                
                </section>
            </div>
        </div>
    </body>

    </html>
<?php
    
} else {
    header('Location: ../page-login.php');
}
?>

