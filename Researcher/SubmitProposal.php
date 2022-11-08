<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION['Sidebar'] = 'SubmitProposal';
    $submitedProposal = null;
    $type = null;
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
        $input = filter_input_array(INPUT_POST);
        if (isset($input['action']) && $input['action'] == 'edit') {

            $data = explode("/", $input['ID']);

            $proposalID = $data[0];
            $updateColumn = $data[1];
            $bugetData = $input['Total_Birr'];

            $bugetdetailData = '';
            if (!isset($input['numbers'])) {
                $bugetdetailData = "industry standard ";
            } else {
                $bugetdetailData = $input['numbers'];
            }
            if ($data[2] === 'Community Service') {
                $stmt = $conn->prepare('UPDATE community_service_budget set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                $stmt->bind_param('di', $bugetData, $proposalID);
            } else {
                $stmt = $conn->prepare('UPDATE budget set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                $stmt->bind_param('di', $bugetData, $proposalID);
            }
            if ($stmt->execute()) {
                if ($data[2] === 'Community Service') {
                    $stmt = $conn->prepare('UPDATE community_service_budget_details set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                    $stmt->bind_param('si', $bugetdetailData, $proposalID);
                } else {
                    $stmt = $conn->prepare('UPDATE budget_detail set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                    $stmt->bind_param('si', $bugetdetailData, $proposalID);
                }
                if ($stmt->execute()) {
                    echo "<script>alert('Successfully updated')</script>";
                } else {
                    echo "<script>alert('Failed to updated')</script>";
                }
            }
        } else  if (isset($_POST['Submit'])) {

            $Title = $_POST['Title'];
            $Term = $_POST['Term'];
            $Type = $_POST['Type'];
            $name = rand(1000, 10000) . '-' . $_FILES['File']['name'];
            $Abstract = $_POST['Abstract'];
            
            $Department = $_SESSION['Department'];
            $date = $_POST['Year'].'-01'.'-01';

            //to be get from the session which is added  during login to his home page
            $user_id = $_SESSION['StaffId'];
            $NumberofParticipants = (int)$_POST['participants'];
            $conn->begin_transaction();
            $Faculty = $_SESSION['Faculty'];
            $stmt = $conn->prepare('INSERT INTO proposal (Title, Type, File,date,Abstract,Faculty,Department,Term) VALUES (?, ?, ?,?,?,?,?,?)');
            $stmt->bind_param('sssssssi', $Title, $Type, $name, $date, $Abstract, $Faculty, $Department, $Term);
            $stmt->execute();
            $submitedProposal = $stmt->insert_id;
            $type = $_POST['Type'];
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
                    $Proposal_ID = $submitedProposal;
                    $Investigators_for_experience_sharing = 0.00;
                    $Investigators_perdim_for_Follow_up = 0.00;
                    $Data_collector_perdim = 0.00;
                    $System_analysis_design_implementation = 0.00;
                    $Traineer_perdim = 0.00;
                    $data_collector_perdim_for_training_pretest = 0.00;
                    $Data_entry = 0.00;
                    $Transport_for_expiriace_sharing = 0.00;
                    $Lab_technician_cost = 0.00;
                    $User_mannual = 0.00;
                    $Professional_for_Testing_financial_standard = 0.00;
                    if ($Type === "Community Service") {
                        // $stmt = $conn->prepare('INSERT INTO community_service_budget (Proposal_ID, Duplication_and_Stationery, Investigators_perdiem_for_supervision, Investigators_perdiem_for_training_and_pre_test, Data_collectors_perdiem_for_training_and_pre_test, Data_collectors_perdiem_for_data_collection, identification_of_eligible_study, data_entry, Transport_cost, Transport_cost_for_purchasing, Perdiem_for_purchasing, Perdiem_for_laboratory_work,Materials_tobe_Purchased,Software_development,Daily_labourer_payment,Land_rent,Laboratory_setup_cost,Laboratory_Technician_cost,Focused_group_discussion,Local_transport,Guider_cost,Security_cost,Boat_rent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                        $stmt = $conn->prepare('INSERT INTO community_service_budget (Proposal_ID)  values (?)');
                        $Proposal_ID = $submitedProposal;
                        // $Duplication_and_Stationer=0.00;
                        // $Investigators_perdiem_for_supervision=0.00;
                        // $Investigators_perdiem_for_training_and_pre_test=0.00; 
                        // $Data_collectors_perdiem_for_training_and_pre_test=0.00;
                        // $Data_collectors_perdiem_for_data_collection=0.00;
                        // $identification_of_eligible_study=0.00;
                        // $data_entry=0.00;
                        // $Transport_cost=0.00;
                        // $Transport_cost_for_purchasing=0.00;
                        // $Perdiem_for_purchasing=0.00;
                        // $Perdiem_for_laboratory_work=0.00;
                        // $Materials_tobe_Purchased=0.00;
                        // $Software_development=0.00;
                        // $Daily_labourer_payment=0.00;
                        // $Land_rent=0.00;
                        // $Laboratory_setup_cost=0.00;
                        // $Laboratory_Technician_cost=0.00;
                        // $Focused_group_discussion=0.00;
                        // $Local_transport=0.00;
                        // $Guider_cost=0.00;
                        // $Security_cost=0.00;
                        // $Boat_rent=0.00;
                        $stmt->bind_param('i', $Proposal_ID);
                        // $stmt->bind_param('idddddddddddddddddddddd',$Proposal_ID,$Duplication_and_Stationer,$Investigators_perdiem_for_supervision,$Investigators_perdiem_for_training_and_pre_test, $Data_collectors_perdiem_for_training_and_pre_test,$Data_collectors_perdiem_for_data_collection, $identification_of_eligible_study,$data_entry, $Transport_cost,$Transport_cost_for_purchasing,$Perdiem_for_purchasing,$Perdiem_for_laboratory_work,$Materials_tobe_Purchased,$Software_development, $Daily_labourer_payment,$Land_rent,$Laboratory_setup_cost,$Laboratory_Technician_cost,$Focused_group_discussion,$Local_transport,$Guider_cost,$Security_cost,$Boat_rent);

                    } else {
                        $stmt = $conn->prepare('INSERT INTO budget (Proposal_ID, Investigators_for_experience_sharing, Investigators_perdim_for_Follow_up, Data_collector_perdim, System_analysis_design_implementation, Traineer_perdim, data_collector_perdim_for_training_pretest, Data_entry, Transport_for_expiriace_sharing, Lab_technician_cost, User_mannual, Professional_for_Testing_financial_standard) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                        $stmt->bind_param('iddddddddddd', $Proposal_ID, $Investigators_for_experience_sharing, $Investigators_perdim_for_Follow_up, $Data_collector_perdim, $System_analysis_design_implementation, $Traineer_perdim, $data_collector_perdim_for_training_pretest, $Data_entry, $Transport_for_expiriace_sharing, $Lab_technician_cost, $User_mannual, $Professional_for_Testing_financial_standard);
                    }
                    if ($stmt->execute()) {
                        if ($Type === "Community Service") {
                            $stmt = $conn->prepare('INSERT INTO community_service_budget_details (Proposal_ID) VALUES (?)');
                            $stmt->bind_param('i', $submitedProposal);
                        } else {
                            $stmt = $conn->prepare('INSERT INTO budget_detail (Proposal_ID, Investigators_for_experience_sharing, Investigators_perdim_for_Follow_up, Data_collector_perdim, System_analysis_design_implementation, Traineer_perdim, data_collector_perdim_for_training_pretest, Data_entry, Transport_for_expiriace_sharing, Lab_technician_cost, User_mannual, Professional_for_Testing_financial_standard) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                            $stmt->bind_param('isssssssssss', $Proposal_ID, $Investigators_for_experience_sharing, $Investigators_perdim_for_Follow_up, $Data_collector_perdim, $System_analysis_design_implementation, $Traineer_perdim, $data_collector_perdim_for_training_pretest, $Data_entry, $Transport_for_expiriace_sharing, $Lab_technician_cost, $User_mannual, $Professional_for_Testing_financial_standard);
                        }
                        if ($stmt->execute()) {
                            $conn->commit();
                            echo "<script>alert('Submitted But  Budget must Be Enterd for  Consideration!!!! ')</script>";
                        } else {
                            $conn->rollback();
                            echo "<script>alert('Submitted Failed for invalid user')</script>";
                        }
                    } else {
                        $conn->rollback();
                        echo "<script>alert('Submitted Failed for invalid user')</script>";
                    }
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
                                    
                                    <div class='card-body'>
                                        <div class='horizontal-form-elements'>
                                            <?php
                                            if ($submitedProposal === null) {


                                            ?>
                                                <form action='SubmitProposal.php' method='post' enctype='multipart/form-data'>
                                                    <div class='row'>
                                                        <div class='col-lg-4'>
                                                            <label class='control-label'>Enter Title </label>
                                                            <input type='text' required name='Title' class='form-control'>
                                                        </div>
                                                        <div class='col-lg-3'>
                                                            <label class='control-label'>Select Type</label>

                                                            <select class='form-control' name='Type' required>
                                                                <option value='0'>Select Proposal Type</option>
                                                                <option value='Research'>Research</option>
                                                                <option value='Technology Transfer'>Technology Transfer</option>
                                                                <option value='Community Service'>Community Service</option>
                                                                <option value='Thesis'>Thesis</option>
                                                                <option value='Project'>Project</option>
                                                                <option value='Other'>Other</option>
                                                            </select>
                                                        </div>

                                                        <div class='col-lg-2'>
                                                            <label class='control-label'>Select Terms</label>

                                                            <select class='form-control' name='Term' required>
                                                                <option value=''>Select Term</option>
                                                                <option value='1'>One Term</option>
                                                                <option value='2'>Two Term</option>
                                                                <option value='3'>Three Term</option>
                                                            </select>
                                                        </div>

                                                        <div class='col-lg-3'>
                                                            <label class='control-label'>Upload The Proposal</label>
                                                            <div class='col-sm-10'>
                                                                <input type='file' name='File' class='form-control'>
                                                            </div>
                                                        </div>
                                                        <div class='col-lg-6'>
                                                            <label class='control-label'>Abstract</label>
                                                            <textarea class='form-control' name='Abstract' rows='5' placeholder='Text input'></textarea>

                                                        </div>
                                                        <div class='col-lg-2'>
                                                            <label class='control-label'>Select Year</label>
                                                            <select class='form-control' name='Year'>
                                                            <option value=''>Select Fiscal Year</option>
                                                                <?php 
                                                                $year=Date("Y")+1;
                                                                while($year>=2001){
                                                                    
                                                                    ?>
                                                                      <option value='<?php echo $year;?>'><?php echo $year;?></option>
                                                                    <?php
                                                                    $year--;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class='col-lg-4' id=parent>
                                                            <label class='control-label'>Select Number of Participants </label>
                                                            <div class='col-sm-10'>
                                                                <select class='form-control' onchange='Participants(this.value)' name='participants'>
                                                                    <option value='0'>Select Participants</option>
                                                                    <option value='1'>1</option>
                                                                    <option value='2'>2</option>
                                                                    <option value='3'>3</option>
                                                                    <option value='4'>4</option>
                                                                    <option value='5'>5</option>
                                                                    <option value='6'>6</option>
                                                                    <option value='7'>7</option>
                                                                    <option value='8'>8</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class='row col-lg-12' id='par'>

                                                        </div>
                                                        <div class='form-group'>
                                                            <div class='col-sm-offset-2 col-sm-10'>
                                                                <button type='submit' name='Submit' value='Submit' class='btn btn-default'>Next
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <!-- /# column -->
                                        <div class='col-lg-12'>
                                            <h1>Please Enter the Budget for the Proposal(<?php echo $submitedProposal;?>) to be Considered else it will be rejected</h1>

                                            <?php
                                            if ($submitedProposal) {
                                                if ($Type === 'Research' || $Type === 'Technology Transfer') {
                                            ?>
                                                    <table id="tabledit" class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="ID" scope="col" style="width:5%">ID </th>
                                                                <th class="second" scope="col" style="width:50%">Criteria</th>
                                                                <th class="numbers" scope="col" style="width:25%">No of days,Sites,Trips,no of investigators</th>
                                                                <th class="Total_Birr" scope="col" style="width:25%">Total Birr</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $year = Date("Y");

                                                            $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN budget as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                            $stmt->bind_param("i", $submitedProposal);
                                                            $stmt->execute();
                                                            $Result = $stmt->get_result();
                                                            $data;
                                                            $data2;
                                                            if ($Result->num_rows > 0) {
                                                                $data = $Result->fetch_assoc();
                                                                $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN budget_detail as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                                $stmt->bind_param("i", $submitedProposal);
                                                                $stmt->execute();
                                                                $Result = $stmt->get_result();
                                                                if ($Result->num_rows > 0) {
                                                                    $data2 = $Result->fetch_assoc();
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Investigators_for_experience_sharing'; ?></td>
                                                                        <th>Investigators perdim for Expirianse Sharing</th>
                                                                        <td><?php echo $data2["Investigators_for_experience_sharing"]; ?></td>
                                                                        <td><?php echo $data["Investigators_for_experience_sharing"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Investigators_perdim_for_Follow_up'; ?></td>
                                                                        <th>Investigators perdim for Follow up</th>
                                                                        <td><?php echo $data2["Investigators_perdim_for_Follow_up"]; ?></td>
                                                                        <td><?php echo $data["Investigators_perdim_for_Follow_up"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Data_collector_perdim'; ?></td>
                                                                        <th scope="row">Data Collector Perdim</th>
                                                                        <td><?php echo $data2["Data_collector_perdim"]; ?></td>
                                                                        <td><?php echo $data["Data_collector_perdim"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'System_analysis_design_implementation'; ?></td>
                                                                        <th scope="row">System analysis, design <br />and implementation</th>
                                                                        <td><?php echo $data2["System_analysis_design_implementation"]; ?></td>
                                                                        <td><?php echo $data["System_analysis_design_implementation"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Traineer_perdim'; ?></td>
                                                                        <th scope="row">Traineer Perdim</th>
                                                                        <td><?php echo $data2["Traineer_perdim"]; ?></td>
                                                                        <td><?php echo $data["Traineer_perdim"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'data_collector_perdim_for_training_pretest'; ?></td>
                                                                        <th scope="row">Data Collector Perdim <br />for Tranning</th>
                                                                        <td><?php echo $data2["data_collector_perdim_for_training_pretest"]; ?></td>
                                                                        <td><?php echo $data["data_collector_perdim_for_training_pretest"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Data_entry'; ?></td>
                                                                        <th scope="row">Data Entry</th>
                                                                        <td><?php echo $data2["Data_entry"]; ?></td>
                                                                        <td><?php echo $data["Data_entry"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Transport_for_expiriace_sharing'; ?></td>
                                                                        <th scope="row">Transport cost for <br />expiriace sharing</th>
                                                                        <td><?php echo $data2["Transport_for_expiriace_sharing"]; ?></td>
                                                                        <td><?php echo $data["Transport_for_expiriace_sharing"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Lab_technician_cost'; ?></td>
                                                                        <th scope="row">Lab Technician Cost</th>
                                                                        <td><?php echo $data2["Lab_technician_cost"]; ?></td>
                                                                        <td><?php echo $data["Lab_technician_cost"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'User_mannual'; ?></td>
                                                                        <td>User Mannual</td>
                                                                        <td><?php echo $data2["User_mannual"]; ?></td>
                                                                        <td><?php echo $data["User_mannual"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Professional_for_Testing_financial_standard'; ?></td>
                                                                        <th scope="row">Professional cost for <br />Testing and financial standard</th>
                                                                        <th>Finance Standard</th>
                                                                        <td><?php echo $data["Professional_for_Testing_financial_standard"]; ?></td>
                                                                    </tr>
                                                                    <?php

                                                                    $sub_total = 0.00;
                                                                    foreach ($data as $key => $value) {
                                                                        if ($key === "Investigators_for_experience_sharing" || $key === "Investigators_perdim_for_Follow_up" || $key === "Data_collector_perdim" || $key === "System_analysis_design_implementation" || $key === "Traineer_perdim" || $key === "data_collector_perdim_for_training_pretest" || $key === "Data_entry" || $key === "Transport_for_expiriace_sharing" || $key === "Lab_technician_cost" || $key === "User_mannual" || $key === "Professional_for_Testing_financial_standard") {
                                                                            $sub_total += (int)$value;
                                                                        }
                                                                    }
                                                                    $contingency = $sub_total * 0.05;
                                                                    $grand_Cost = $sub_total + $contingency;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal; ?></td>
                                                                        <th scope="row"> <b>Sub-total</b></th>

                                                                        <th colspan="2"><b><?php echo $sub_total; ?></b></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal; ?></td>
                                                                        <th scope="row">Contingency Cost(5%)</th>
                                                                        <th colspan="2"><?php echo $contingency; ?></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal; ?></td>
                                                                        <th scope="row"><b>Grand Cost</b></th>
                                                                        <th colspan="2"><b><?php echo $grand_Cost; ?></b></th>
                                                                    </tr>
                                                                <?php } else {
                                                                ?>
                                                                    <tr>
                                                                        <td colspan="4">
                                                                            <center>Enter and submit proposal information first</center>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <center>Enter and submit proposal information first</center>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                                } elseif ($Type === 'Community Service') {
                                                ?>
                                                    <table id="tabledit" class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="ID" scope="col" style="width:5%">ID </th>
                                                                <th class="second" scope="col" style="width:50%">Criteria </th>
                                                                <th class="numbers" scope="col" style="width:25%">No of days,Sites,Trips,no of investigators</th>
                                                                <th class="Total_Birr" scope="col" style="width:25%">Total Birr</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $year = Date("Y");

                                                            $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN community_service_budget as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                            $stmt->bind_param("i", $submitedProposal);
                                                            $stmt->execute();
                                                            $Result = $stmt->get_result();
                                                            $data;
                                                            $data2;
                                                            if ($Result->num_rows > 0) {
                                                                $data = $Result->fetch_assoc();
                                                                $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN community_service_budget_details as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                                $stmt->bind_param("i", $submitedProposal);
                                                                $stmt->execute();
                                                                $Result = $stmt->get_result();
                                                                if ($Result->num_rows > 0) {
                                                                    $data2 = $Result->fetch_assoc();
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Duplication_and_Stationery' . '/' . $Type; ?></td>
                                                                        <th>Duplication and Stationery (pen, paper, etc.)</th>
                                                                        <td><?php echo $data2["Duplication_and_Stationery"]; ?></td>
                                                                        <td><?php echo $data["Duplication_and_Stationery"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Investigators_perdiem_for_supervision' . '/' . $Type; ?></td>
                                                                        <th>Investigators per diem for supervision</th>
                                                                        <td><?php echo $data2["Investigators_perdiem_for_supervision"]; ?></td>
                                                                        <td><?php echo $data["Investigators_perdiem_for_supervision"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Investigators_perdiem_for_training_and_pre_test' . '/' . $Type; ?></td>
                                                                        <th scope="row">Investigators per diem for training and pre-test</th>
                                                                        <td><?php echo $data2["Investigators_perdiem_for_training_and_pre_test"]; ?></td>
                                                                        <td><?php echo $data["Investigators_perdiem_for_training_and_pre_test"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Data_collectors_perdiem_for_training_and_pre_test' . '/' . $Type; ?></td>
                                                                        <th scope="row">Data collectors per diem for training and pre test</th>
                                                                        <td><?php echo $data2["Data_collectors_perdiem_for_training_and_pre_test"]; ?></td>
                                                                        <td><?php echo $data["Data_collectors_perdiem_for_training_and_pre_test"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Data_collectors_perdiem_for_data_collection' . '/' . $Type; ?></td>
                                                                        <th scope="row">Data collectors per diem for data collection
                                                                            <br />(Sample data collectors, surveyors, GPS, water quality, solid waste, <br />flow measurement, soil)
                                                                        </th>
                                                                        <td><?php echo $data2["Data_collectors_perdiem_for_data_collection"]; ?></td>
                                                                        <td><?php echo $data["Data_collectors_perdiem_for_data_collection"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'identification_of_eligible_study' . '/' . $Type; ?></td>
                                                                        <th scope="row">Number of questionnaires to be collected per day for<br /> identification of eligible study population</th>
                                                                        <td><?php echo $data2["identification_of_eligible_study"]; ?></td>
                                                                        <td><?php echo $data["identification_of_eligible_study"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'data_entry' . '/' . $Type; ?></td>
                                                                        <th scope="row">Payment rate per questionnaire for data entry</th>
                                                                        <td><?php echo $data2["data_entry"]; ?></td>
                                                                        <td><?php echo $data["data_entry"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Transport_cost' . '/' . $Type; ?></td>
                                                                        <th scope="row">Transport cost</th>
                                                                        <td><?php echo $data2["Transport_cost"]; ?></td>
                                                                        <td><?php echo $data["Transport_cost"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Transport_cost_for_purchasing' . '/' . $Type; ?></td>
                                                                        <th scope="row">Transport cost for purchasing (if required)</th>
                                                                        <td><?php echo $data2["Transport_cost_for_purchasing"]; ?></td>
                                                                        <td><?php echo $data["Transport_cost_for_purchasing"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Perdiem_for_purchasing' . '/' . $Type; ?></td>
                                                                        <td>Per diem for purchasing (if required)</td>
                                                                        <td><?php echo $data2["Perdiem_for_purchasing"]; ?></td>
                                                                        <td><?php echo $data["Perdiem_for_purchasing"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Perdiem_for_laboratory_work' . '/' . $Type; ?></td>
                                                                        <th scope="row">Per diem for laboratory work (if required)</th>
                                                                        <th><?php echo $data2["Perdiem_for_laboratory_work"]; ?></th>
                                                                        <td><?php echo $data["Perdiem_for_laboratory_work"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Materials_tobe_Purchased' . '/' . $Type; ?></td>
                                                                        <th scope="row">Materials /Resources to be Purchased (Animals, seed, fertilizer, Lab chemicals,<br />
                                                                            equipment, feed, soft wares, data etc.)</th>
                                                                        <th><?php echo $data2["Materials_tobe_Purchased"]; ?></th>
                                                                        <td><?php echo $data["Materials_tobe_Purchased"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Software_development' . '/' . $Type; ?></td>
                                                                        <th scope="row">Software development</th>
                                                                        <th><?php echo $data2["Software_development"]; ?></th>
                                                                        <td><?php echo $data["Software_development"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Daily_labourer_payment' . '/' . $Type; ?></td>
                                                                        <th scope="row">Daily labourer payment </th>
                                                                        <th><?php echo $data2["Daily_labourer_payment"]; ?></th>
                                                                        <td><?php echo $data["Daily_labourer_payment"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Perdiem_for_laboratory_work' . '/' . $Type; ?></td>
                                                                        <th scope="row">Land rent (if any)</th>
                                                                        <th><?php echo $data2["Perdiem_for_laboratory_work"]; ?></th>
                                                                        <td><?php echo $data["Perdiem_for_laboratory_work"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Land_rent' . '/' . $Type; ?></td>
                                                                        <th scope="row">Per diem for laboratory work (if required)</th>
                                                                        <th><?php echo $data2["Land_rent"]; ?></th>
                                                                        <td><?php echo $data["Land_rent"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Laboratory_setup_cost' . '/' . $Type; ?></td>
                                                                        <th scope="row">Laboratory setup cost (if applicable)</th>
                                                                        <th><?php echo $data2["Laboratory_setup_cost"]; ?></th>
                                                                        <td><?php echo $data["Laboratory_setup_cost"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Laboratory_Technician_cost' . '/' . $Type; ?></td>
                                                                        <th scope="row">Laboratory Technician cost (if applicable) </th>
                                                                        <th><?php echo $data2["Laboratory_Technician_cost"]; ?></th>
                                                                        <td><?php echo $data["Laboratory_Technician_cost"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Focused_group_discussion' . '/' . $Type; ?></td>
                                                                        <th scope="row">Focused group discussion (FGD)</th>
                                                                        <th><?php echo $data2["Focused_group_discussion"]; ?></th>
                                                                        <td><?php echo $data["Focused_group_discussion"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Local_transport' . '/' . $Type; ?></td>
                                                                        <th scope="row">Local transport</th>
                                                                        <th><?php echo $data2["Local_transport"]; ?></th>
                                                                        <td><?php echo $data["Local_transport"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Guider_cost' . '/' . $Type; ?></td>
                                                                        <th scope="row">Guider cost (if applicable) </th>
                                                                        <th><?php echo $data2["Guider_cost"]; ?></th>
                                                                        <td><?php echo $data["Guider_cost"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Security_cost' . '/' . $Type; ?></td>
                                                                        <th scope="row">Security cost (if applicable)</th>
                                                                        <th><?php echo $data2["Security_cost"]; ?></th>
                                                                        <td><?php echo $data["Security_cost"]; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal . '/' . 'Boat_rent' . '/' . $Type; ?></td>
                                                                        <th scope="row">Boat rent (for water sampling in a water body like in a lake)<br /> and traditional transport cost</th>
                                                                        <th><?php echo $data2["Boat_rent"]; ?></th>
                                                                        <td><?php echo $data["Boat_rent"]; ?></td>
                                                                    </tr>
                                                                    <?php

                                                                    $sub_total = 0.00;
                                                                    foreach ($data as $key => $value) {
                                                                        if ($key === "Duplication_and_Stationery" || $key === "Investigators_perdiem_for_supervision" || $key === "Investigators_perdiem_for_training_and_pre_test" || $key === "Data_collectors_perdiem_for_training_and_pre_test" || $key === "Data_collectors_perdiem_for_data_collection" || $key === "identification_of_eligible_study" || $key === "data_entry" || $key === "Transport_cost" || $key === "Transport_cost_for_purchasing" || $key === "Perdiem_for_purchasing" || $key === "Perdiem_for_laboratory_work" || $key === "Materials_tobe_Purchased" || $key === "Software_development" || $key === "Daily_labourer_payment" || $key === "Land_rent" || $key === "Laboratory_setup_cost" || $key === "Laboratory_Technician_cost" || $key === "Focused_group_discussion" || $key === "Local_transport" || $key === "Guider_cost" || $key === "Security_cost" || $key === "Boat_rent") {
                                                                            $sub_total += (int)$value;
                                                                        }
                                                                    }
                                                                    $contingency = $sub_total * 0.05;
                                                                    $grand_Cost = $sub_total + $contingency;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal; ?></td>
                                                                        <th scope="row"> <b>Sub-total</b></th>

                                                                        <th colspan="2"><b><?php echo $sub_total; ?></b></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal; ?></td>
                                                                        <th scope="row">Contingency Cost(5%)</th>
                                                                        <th colspan="2"><?php echo $contingency; ?></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?php echo $submitedProposal; ?></td>
                                                                        <th scope="row"><b>Grand Cost</b></th>
                                                                        <th colspan="2"><b><?php echo $grand_Cost; ?></b></th>
                                                                    </tr>
                                                                <?php } else {
                                                                ?>
                                                                    <tr>
                                                                        <td colspan="4">
                                                                            <center>Enter and submit proposal information first</center>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <center>Enter and submit proposal information first</center>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <center>Enter and submit proposal information first</center>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="3">
                                                                <center>Enter and submit proposal information first</center>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>

                                                        </tbody>
                                                    </table>
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
            <?php require_once '../Common/Footer.php'; ?>
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

<style>
    .hidden {
        visibility: hidden
    }
</style>
<script>
    function tableData() {
        // alert("ddddddddddd");
        // document.getElementById("label").style.display = 'none';
        // document.getElementById("label").style.display = 'block';
        $('#tabledit').Tabledit({
            url: 'SubmitProposal.php',
            eventType: 'dblclick',
            deleteButton: false,
            method: 'POST',
            hideIdentifier: true,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [2, 'numbers'],
                    [3, 'Total_Birr']
                ]
            },
            onSuccess: function(data, textStatus, jqXHR) {
                alert('Successfully Updated');
                SelectDocuments('');
            },
            onFail: function(jqXHR, textStatus, errorThrown) {
                console.log('onFail(jqXHR, textStatus, errorThrown)');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            onAjax: function(action, serialize) {
                console.log('onAjax(action, serialize)');
                console.log(action);
                console.log(serialize);
                validation(serialize);
            },
            buttons: {
                edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-pencil"></span>',
                    action: 'edit'
                },
                delete: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-close"></span>',
                    action: 'delete'
                },
                save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Save'
                },
                restore: {
                    class: 'btn btn-sm btn-warning',
                    html: 'Restore',
                    action: 'restore'
                },
                confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirm'
                }
            }

        });
    }

    function validation(serialize) {
        const totalbirr = serialize.split("&")[2].split("=");

        if (isNaN(totalbirr[1])) {

            alert("Total can't be a string");
            return false;
        } else if (totalbirr[1] < 0) {
            alert("Total can't be Negative");
            return false;
        }
    }

    function Participants(v = '') {

        // let par = '';
        var fff = document.getElementById('par');
        fff.innerHTML = '';
        // alert(fff.hasChildNodes());

        if (v > 0) {
            // var parent = document.getElementById('par');
            var html = '';
            for (let i = 0; i < v; i++) {

                html += '<div class="col-lg-3"><label class="control-label">Enter Participant ID</label><input type="text" min="1"  class="form-control" name="participant_' + (1 + i) + '"></div>';
                // parent.after(html);

            }
            fff.innerHTML += html;
            tableData();
        }
    }

    function validation(input) {

    }
</script>
<?php
echo '<script type="text/javascript">',
'tableData();',
'</script>';
?>