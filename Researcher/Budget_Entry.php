<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION['Sidebar'] = 'Budget_Entry';

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

        <!-- /# sidebar -->

        <?php
        //require_once 'sidebar.php';
        require_once '../Common/Header.php';

        if (isset($_POST['Submit'])) {

            $Title = $_POST['Title'];
            $Term = $_POST['Term'];
            $Type = $_POST['Type'];
            $name = rand(1000, 10000) . '-' . $_FILES['File']['name'];
            $Abstract = $_POST['Abstract'];
            $Faculty = $_SESSION['Faculty'];
            $Department = $_SESSION['Department'];
            $date = date('Y/m/d');

            //to be get from the session which is added  during login to his home page
            $user_id = $_SESSION['StaffId'];
            $NumberofParticipants = (int)$_POST['participants'];
            $conn->begin_transaction();

            $stmt = $conn->prepare('INSERT INTO proposal (Title, Type, File,date,Abstract,Faculty,Department,Term) VALUES (?, ?, ?,?,?,?,?,?)');
            $stmt->bind_param('sssssssi', $Title, $Type, $name, $date, $Abstract, $Faculty, $Department, $Term);
            $stmt->execute();
            $query = 'SELECT LAST_INSERT_ID() as last';
            $result = $conn->query($query);
            $result = $result->fetch_assoc();
            $last = (int)$result['last'];
            $file = $_FILES['File'];
            $tname = $_FILES['File']['tmp_name'];
            $size = $_FILES['File']['size'];
            $file_type = $_FILES['File']['type'];

            if ($last != 0) {
                $status = '';
                $moved = '';
                $role = '';
                $num = 0;
                switch ($Type) {
                    case 'Research':
                        move_uploaded_file($tname, '../Documents/Research/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Technology Transfer':
                        move_uploaded_file($tname, '../Documents/Technology Transferer/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Community Service':
                        move_uploaded_file($tname, '../Documents/Community Service/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Thesis':
                        move_uploaded_file($tname, '../Documents/Thesis/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Project':
                        move_uploaded_file($tname, '../Documents/Project/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                }
                $stmt = $conn->prepare('INSERT INTO participant (	Staff_ID, Proposal_ID, Role) VALUES (?, ?, ?)');

                do {
                    if ($num == 0) {
                        $role = 'PI';
                        $stmt->bind_param('sis', $user_id, $last, $role);
                    } else {

                        $par = 'participant_' . $num;
                        $user = $_POST[$par];
                        // echo ' dddddddddddddddddddddddddd'.validateUser( $user, $user_id );
                        if (validateUser($user, $user_id)) {
                            $role = 'Co';
                            $stmt->bind_param('sis', $user, $last, $role);
                        } else {
                            $status = 'Invalid';
                            break;
                        }
                    }
                    if ($stmt->execute()) {
                        $status = 'Successful';
                    } else {
                        $status = 'Failed';
                    }
                    $num++;
                } while ($num <= $NumberofParticipants);

                if ($status === 'Successful' && $moved === 'Successful') {
                    $conn->commit();
                    echo "<script>alert('Submitted Successfully!!!! ')</script>";
                } elseif ($status === 'Invalid') {
                    $conn->rollback();
                    echo "<script>alert('Submitted Failed for invalid user')</script>";
                } else {
                    $conn->rollback();
                    echo "<script>alert('Submitted Failed!!!! ')</script>";
                }
            }
        }
        ?>

        <div class='content-wrap'>
            <div class='main'>
                <div class='container-fluid'>
                   
                    <!-- /# row -->
                    <section id='main-content'>
                        <div class='row'>
                            <!-- /# column -->
                            <div class='col-lg-12'>
                                <div class='card'>
                                    <div class='card-title'>
                                        <h4>Budget Entry</h4>

                                    </div>
                                    <div class='card-body'>
                                        <div class='horizontal-form-elements'>
                                            <div class='row'>

                                                    <div class='col-lg-12'>
                                                       
                                                    </div>
                                                    
                                                </div>
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


<script>
    function Participants(v = '') {

        let par = '';
        var fff = document.getElementById('par');

        if (v > 0) {
            fff.innerHTML = '';
            for (let i = 0; i < v; i++) {
                fff.innerHTML += '<div class="form-group"><label class="control-label">Enter Participant ID</label><input type="text" min="1"  class="form-control" name="participant_' + (1 + i) + '"></div>'
            }
        }
    }
</script>