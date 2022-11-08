<?php
require_once 'Common/DataBaseConnection.php';
if (isset($_REQUEST["View"])) {
    $Year = $_REQUEST['v'];
    $Faculty = $_REQUEST['Faculty'];
    $Total = 0;
    $Female = 0;
    $Male = 0;
    $TotalBudget = 0;
//    the three line code below defines the variables for holding the budgets of the  three group
    $Research_Budget=0;
    $Community_Service_Budget = 0;

    $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and date like '" . $Year . "%'");
    $stmt->bind_param('s', $Faculty);
    $stmt->execute();
    $Result = $stmt->get_result();
    while ($data = $Result->fetch_assoc()) {
        $Research_Budget += $data['Cost'];
    }
    ?>
    <tr>
        <td rowspan="8">Research</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Type='Research' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();
        $Total += $Result->num_rows;
        ?>
        <td>Submitted by Researchers</td>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Submitted_Male_Participants = array();
            $Submitted_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Submitted_Male_Participants) && $row['sex'] == 'Male') {
                                    $Submitted_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Submitted_Female_Participants) && $row['sex'] == 'Female') {
                                    $Submitted_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $TotalBudget+=$Research_Budget;
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td rowspan="8"><?php echo $Research_Budget; ?></td>
        <!--        for the female and male participanta selection PHP-->
        <td><?php echo count($Submitted_Female_Participants); ?></td>
        <td><?php echo count($Submitted_Male_Participants); ?></td>
    </tr>
    <!--    Select Proposals which are submitted at faculty level but didnt presented -->
    <tr>
        <td>Assessed at Faculty Level</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status!='Not Approved' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Assessed_Male_Participants = array();
            $Assessed_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Assessed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Assessed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Assessed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Assessed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Assessed_Female_Participants) ?></td>
        <td><?php echo count($Assessed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at Faculty Level</td>
        <!--        Getting information about Proposals that are passed at Faculty level -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcd_level='Approved' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Passed_Male_Participants = array();
            $Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Passed_Female_Participants); ?></td>
        <td><?php echo count($Passed_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Assessed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcc_level='Approved' and Rcd_level!='Not Approved' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Male_Participants = array();
            $University_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Assessed at University level ';
            }
            ?>
        </td>
        <td><?php echo count($University_Female_Participants) ?></td>
        <td><?php echo count($University_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcc_level='Approved' and Rcd_level='Approved' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_passed_Male_Participants = array();
            $University_passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Passed at university level';
            }
            ?>
        </td>
        <td><?php echo count($University_passed_Female_Participants) ?></td>
        <td><?php echo count($University_passed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Who takes Budget</td>
        <!--        Proposal in which their budget is withdraw proved if the all agreements are taken -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Budget_Male_Participants = array();
            $Budget_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Budget_Male_Participants) && $row['sex'] == 'Male') {
                                    $Budget_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Budget_Female_Participants) && $row['sex'] == 'Female') {
                                    $Budget_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Budget_Female_Participants);
                    $Male += count($Budget_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Budget_Female_Participants); ?></td>
        <td><?php echo count($Budget_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Progressing</td>
        <!--        Researches that are on Progress -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Progressing_Male_Participants = array();
            $Progressing_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Progressing_Male_Participants) && $row['sex'] == 'Male') {
                                    $Progressing_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Progressing_Female_Participants) && $row['sex'] == 'Female') {
                                    $Progressing_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Progressing_Female_Participants); ?></td>
        <td><?php echo count($Progressing_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Completed</td>
        <!--        Completed Researches -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='Completed' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Completed_Male_Participants = array();
            $Completed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Completed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Completed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Completed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Completed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Completed_Female_Participants);
                    $Male += count($Completed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Completed Research';
            }
            ?>
        </td>
        <td><?php echo count($Completed_Female_Participants) ?></td>
        <td><?php echo count($Completed_Male_Participants) ?></td>
    </tr>
    <!--Community Service starts -->
<?php

    $Research_Budget += $data['Cost'];
    ?>
    <tr>

        <td rowspan="8">Community Service</td>
        <td>Who takes Budget</td>
        <!--        Proposal in which their budget is withdraw proved if the all agreements are taken -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Budget_Male_Participants = array();
            $Budget_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Community_Service_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Budget_Male_Participants) && $row['sex'] == 'Male') {
                                    $Budget_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Budget_Female_Participants) && $row['sex'] == 'Female') {
                                    $Budget_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Budget_Female_Participants);
                    $Male += count($Budget_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service';
            }
            ?>
        </td>
        <td rowspan="8"><?php echo $Community_Service_Budget ?></td>
        <td><?php echo count($Budget_Female_Participants); ?></td>
        <td><?php echo count($Budget_Male_Participants); ?></td>
    </tr>
    <tr>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Community_Result = $stmt->get_result();

        $Total += $Result->num_rows;


        ?>
        <td>Submitted Proposals</td>
        <td><?php echo $Community_Result->num_rows; ?></td>
        <td>
            <?php
            $Submitted_Community_Male_Participants = array();
            $Submitted_Community_Female_Participants = array();
            if ($Community_Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Community_Result->fetch_assoc()) {


//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Submitted_Community_Male_Participants) && $row['sex'] == 'Male') {
                                    $Submitted_Community_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Submitted_Community_Female_Participants) && $row['sex'] == 'Female') {
                                    $Submitted_Community_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Submitted_Female_Participants);
                    $Male += count($Submitted_Male_Participants);
                    $TotalBudget+=$Community_Service_Budget;
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Submitted';
            }
            ?>
        </td>

        <td><?php echo count($Submitted_Community_Female_Participants) ?></td>
        <td><?php echo count($Submitted_Community_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Assessed at Faculty Level</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status!='Not Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Assessed_Male_Participants = array();
            $Assessed_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Assessed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Assessed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Assessed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Assessed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Assessed_Female_Participants);
                    $Male += count($Assessed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo ' <li>No Proposal</li>';
            }
            ?>
        </td>
        <td><?php echo count($Assessed_Female_Participants) ?></td>
        <td><?php echo count($Assessed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at Faculty Level</td>
        <!--        Getting information about Proposals that are passed at Faculty level -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcc_level='Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Passed_Male_Participants = array();
            $Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Passed_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Passed';
            }
            ?>
        </td>
        <td><?php echo count($Passed_Female_Participants); ?></td>
        <td><?php echo count($Passed_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Assessed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcd_level!='Not Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Male_Participants = array();
            $University_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Female_Participants);
                    $Male += count($University_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Assessed at University level ';
            }
            ?>
        </td>
        <td><?php echo count($University_Female_Participants) ?></td>
        <td><?php echo count($University_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcd_level='Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Passed_Male_Participants = array();
            $University_Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Passed_Female_Participants);
                    $Male += count($University_Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Passed at University Level';
            }
            ?>
        </td>
        <td><?php echo count($University_Passed_Female_Participants) ?></td>
        <td><?php echo count($University_Passed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Progressing</td>
        <!--        Researches that are on Progress -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Progressing_Male_Participants = array();
            $Progressing_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Progressing_Male_Participants) && $row['sex'] == 'Male') {
                                    $Progressing_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Progressing_Female_Participants) && $row['sex'] == 'Female') {
                                    $Progressing_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Progressing_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Found';
            }
            ?>
        </td>
        <td><?php echo count($Progressing_Female_Participants); ?></td>
        <td><?php echo count($Progressing_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Completed</td>
        <!--        Completed Researches -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='Completed' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Completed_Male_Participants = array();
            $Completed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Completed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Completed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Completed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Completed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Completed_Female_Participants);
                    $Male += count($Completed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Completed Community Service';
            }
            ?>
        </td>
        <td><?php echo count($Completed_Female_Participants) ?></td>
        <td><?php echo count($Completed_Male_Participants) ?></td>
    </tr>
    <!--    Technology Transfer Starts -->
    <tr>
        <td rowspan="8">Technology Transfer</td>
        <td>Who takes Budget</td>
        <!--        Proposal in which their budget is withdraw proved if the all agreements are taken -->
        <?php
        $Technology_Transfer_Budget = 0;
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Budget_Male_Participants = array();
            $Budget_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Technology_Transfer_Budget += $data['Cost'];
//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Budget_Male_Participants) && $row['sex'] == 'Male') {
                                    $Budget_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Budget_Female_Participants) && $row['sex'] == 'Female') {
                                    $Budget_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Budget_Female_Participants);
                    $Male += count($Budget_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer ';
            }
            ?>
        </td>
        <td rowspan="8"><?php echo $Technology_Transfer_Budget ?></td>
        <td><?php echo count($Budget_Female_Participants); ?></td>
        <td><?php echo count($Budget_Male_Participants); ?></td>
    </tr>
    <tr>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Community_Result = $stmt->get_result();

        $Total += $Result->num_rows;
        ?>

        <td>Submitted Proposals</td>
        <td><?php echo $Community_Result->num_rows; ?></td>
        <td>
            <?php
            $Submitted_Community_Male_Participants = array();
            $Submitted_Community_Female_Participants = array();
            if ($Community_Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Community_Result->fetch_assoc()) {

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Submitted_Community_Male_Participants) && $row['sex'] == 'Male') {
                                    $Submitted_Community_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Submitted_Community_Female_Participants) && $row['sex'] == 'Female') {
                                    $Submitted_Community_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Submitted_Female_Participants);
                    $Male += count($Submitted_Male_Participants);
                    $TotalBudget+=$Technology_Transfer_Budget
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Submitted';
            }
            ?>
        </td>

        <td><?php echo count($Submitted_Community_Female_Participants) ?></td>
        <td><?php echo count($Submitted_Community_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Assessed at Faculty Level</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status!='Not Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Assessed_Male_Participants = array();
            $Assessed_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Assessed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Assessed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Assessed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Assessed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Assessed_Female_Participants);
                    $Male += count($Assessed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo ' <li>No Technology Transfer Assessed at Faculty Level</li>';
            }
            ?>
        </td>
        <td><?php echo count($Assessed_Female_Participants) ?></td>
        <td><?php echo count($Assessed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at Faculty Level</td>
        <!--        Getting information about Proposals that are passed at Faculty level -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcc_level='Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Passed_Male_Participants = array();
            $Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Passed_Female_Participants);
                    $Male += count($Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Passed at Faculty Level';
            }
            ?>
        </td>
        <td><?php echo count($Passed_Female_Participants); ?></td>
        <td><?php echo count($Passed_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Assessed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcd_level!='Not Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Male_Participants = array();
            $University_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Female_Participants);
                    $Male += count($University_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Assessed at University Level';
            }
            ?>
        </td>
        <td><?php echo count($University_Female_Participants) ?></td>
        <td><?php echo count($University_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Rcd_level='Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Passed_Male_Participants = array();
            $University_Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Passed_Female_Participants);
                    $Male += count($University_Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Passed at University Level';
            }
            ?>
        </td>
        <td><?php echo count($University_Passed_Female_Participants) ?></td>
        <td><?php echo count($University_Passed_Male_Participants) ?></td>
    </tr>

    <tr>
        <td>Progressing</td>
        <!--        Researches that are on Progress -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='On Progress' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Progressing_Male_Participants = array();
            $Progressing_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Progressing_Male_Participants) && $row['sex'] == 'Male') {
                                    $Progressing_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Progressing_Female_Participants) && $row['sex'] == 'Female') {
                                    $Progressing_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Progressing_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Found';
            }
            ?>
        </td>
        <td><?php echo count($Progressing_Female_Participants); ?></td>
        <td><?php echo count($Progressing_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Completed</td>
        <!--        Completed Researches -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where Faculty=? and Status='Completed' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->bind_param('s', $Faculty);
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Completed_Male_Participants = array();
            $Completed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Completed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Completed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Completed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Completed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Completed_Female_Participants);
                    $Male += count($Completed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Completed Technology Transfer';
            }
            ?>
        </td>
        <td><?php echo count($Completed_Female_Participants) ?></td>
        <td><?php echo count($Completed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td colspan="2">Total</td>
        <td><?php echo $Total; ?></td>
        <td></td>
        <td><?php echo $TotalBudget?></td>
        <td><?php echo $Female ?></td>
        <td><?php echo $Male ?></td>
    </tr>
    <?php
}
elseif (isset($_REQUEST["university_View"])) {
    $Year = $_REQUEST['v'];
    $Total = 0;
    $Female = 0;
    $Male = 0;
    $TotalBudget = 0;
//    the three line code below defines the variables for holding the budgets of the  three group
    $Research_Budget=0;
    $Community_Service_Budget = 0;

    $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and date like '" . $Year . "%'");
     $stmt->execute();
    $Result = $stmt->get_result();
    while ($data = $Result->fetch_assoc()) {
        $Research_Budget += $data['Cost'];
    }
    ?>
    <tr>
        <td rowspan="8">Research</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Type='Research' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();
        $Total += $Result->num_rows;
        ?>
        <td>Submitted by Researchers</td>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Submitted_Male_Participants = array();
            $Submitted_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Submitted_Male_Participants) && $row['sex'] == 'Male') {
                                    $Submitted_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Submitted_Female_Participants) && $row['sex'] == 'Female') {
                                    $Submitted_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Submitted_Female_Participants);
                    $Male += count($Submitted_Male_Participants) ;
                    $TotalBudget+=$Research_Budget;
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td rowspan="8"><?php echo $Research_Budget; ?></td>
        <!--        for the female and male participanta selection PHP-->
        <td><?php echo count($Submitted_Female_Participants); ?></td>
        <td><?php echo count($Submitted_Male_Participants); ?></td>
    </tr>
    <!--    Select Proposals which are submitted at faculty level but didnt presented -->
    <tr>
        <td>Assessed at Faculty Level</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status!='Not Approved' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Assessed_Male_Participants = array();
            $Assessed_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Assessed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Assessed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Assessed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Assessed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Assessed_Female_Participants);
                    $Male += count($Assessed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Assessed_Female_Participants) ?></td>
        <td><?php echo count($Assessed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at Faculty Level</td>
        <!--        Getting information about Proposals that are passed at Faculty level -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcd_level='Approved' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Passed_Male_Participants = array();
            $Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Passed_Female_Participants);
                    $Male += count($Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Passed_Female_Participants); ?></td>
        <td><?php echo count($Passed_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Assessed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcc_level='Approved' and Rcd_level!='Not Approved' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Male_Participants = array();
            $University_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Female_Participants);
                    $Male += count($University_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Assessed at University level ';
            }
            ?>
        </td>
        <td><?php echo count($University_Female_Participants) ?></td>
        <td><?php echo count($University_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcc_level='Approved' and Rcd_level='Approved' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_passed_Male_Participants = array();
            $University_passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_passed_Female_Participants);
                    $Male += count($University_passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Passed at university level';
            }
            ?>
        </td>
        <td><?php echo count($University_passed_Female_Participants) ?></td>
        <td><?php echo count($University_passed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Who takes Budget</td>
        <!--        Proposal in which their budget is withdraw proved if the all agreements are taken -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Budget_Male_Participants = array();
            $Budget_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Budget_Male_Participants) && $row['sex'] == 'Male') {
                                    $Budget_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Budget_Female_Participants) && $row['sex'] == 'Female') {
                                    $Budget_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Budget_Female_Participants);
                    $Male += count($Budget_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Budget_Female_Participants); ?></td>
        <td><?php echo count($Budget_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Progressing</td>
        <!--        Researches that are on Progress -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Progressing_Male_Participants = array();
            $Progressing_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Progressing_Male_Participants) && $row['sex'] == 'Male') {
                                    $Progressing_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Progressing_Female_Participants) && $row['sex'] == 'Female') {
                                    $Progressing_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Progressing_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Research Submitted';
            }
            ?>
        </td>
        <td><?php echo count($Progressing_Female_Participants); ?></td>
        <td><?php echo count($Progressing_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Completed</td>
        <!--        Completed Researches -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='Completed' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Completed_Male_Participants = array();
            $Completed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Completed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Completed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Completed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Completed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Completed_Female_Participants);
                    $Male += count($Completed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Completed Research';
            }
            ?>
        </td>
        <td><?php echo count($Completed_Female_Participants) ?></td>
        <td><?php echo count($Completed_Male_Participants) ?></td>
    </tr>
    <!--Community Service starts -->
<?php

    $Research_Budget += $data['Cost'];
    ?>
    <tr>

        <td rowspan="8">Community Service</td>
        <td>Who takes Budget</td>
        <!--        Proposal in which their budget is withdraw proved if the all agreements are taken -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Budget_Male_Participants = array();
            $Budget_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Community_Service_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Budget_Male_Participants) && $row['sex'] == 'Male') {
                                    $Budget_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Budget_Female_Participants) && $row['sex'] == 'Female') {
                                    $Budget_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Budget_Female_Participants);
                    $Male += count($Budget_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service';
            }
            ?>
        </td>
        <td rowspan="8"><?php echo $Community_Service_Budget ?></td>
        <td><?php echo count($Budget_Female_Participants); ?></td>
        <td><?php echo count($Budget_Male_Participants); ?></td>
    </tr>
    <tr>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Community_Result = $stmt->get_result();

        $Total += $Result->num_rows;


        ?>
        <td>Submitted Proposals</td>
        <td><?php echo $Community_Result->num_rows; ?></td>
        <td>
            <?php
            $Submitted_Community_Male_Participants = array();
            $Submitted_Community_Female_Participants = array();
            if ($Community_Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Community_Result->fetch_assoc()) {


//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Submitted_Community_Male_Participants) && $row['sex'] == 'Male') {
                                    $Submitted_Community_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Submitted_Community_Female_Participants) && $row['sex'] == 'Female') {
                                    $Submitted_Community_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Submitted_Female_Participants);
                    $Male += count($Submitted_Male_Participants);
                    $TotalBudget+=$Community_Service_Budget;
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Submitted';
            }
            ?>
        </td>

        <td><?php echo count($Submitted_Community_Female_Participants) ?></td>
        <td><?php echo count($Submitted_Community_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Assessed at Faculty Level</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status!='Not Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Assessed_Male_Participants = array();
            $Assessed_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Assessed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Assessed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Assessed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Assessed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Assessed_Female_Participants);
                    $Male += count($Assessed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo ' <li>No Proposal</li>';
            }
            ?>
        </td>
        <td><?php echo count($Assessed_Female_Participants) ?></td>
        <td><?php echo count($Assessed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at Faculty Level</td>
        <!--        Getting information about Proposals that are passed at Faculty level -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcc_level='Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Passed_Male_Participants = array();
            $Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Passed_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Passed';
            }
            ?>
        </td>
        <td><?php echo count($Passed_Female_Participants); ?></td>
        <td><?php echo count($Passed_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Assessed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcd_level!='Not Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Male_Participants = array();
            $University_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Female_Participants);
                    $Male += count($University_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Assessed at University level ';
            }
            ?>
        </td>
        <td><?php echo count($University_Female_Participants) ?></td>
        <td><?php echo count($University_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcd_level='Approved' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Passed_Male_Participants = array();
            $University_Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Passed_Female_Participants);
                    $Male += count($University_Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Passed at University Level';
            }
            ?>
        </td>
        <td><?php echo count($University_Passed_Female_Participants) ?></td>
        <td><?php echo count($University_Passed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Progressing</td>
        <!--        Researches that are on Progress -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Progressing_Male_Participants = array();
            $Progressing_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Progressing_Male_Participants) && $row['sex'] == 'Male') {
                                    $Progressing_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Progressing_Female_Participants) && $row['sex'] == 'Female') {
                                    $Progressing_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }

                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Progressing_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Community Service Found';
            }
            ?>
        </td>
        <td><?php echo count($Progressing_Female_Participants); ?></td>
        <td><?php echo count($Progressing_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Completed</td>
        <!--        Completed Researches -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='Completed' and Type='Community Service' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Completed_Male_Participants = array();
            $Completed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Completed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Completed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Completed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Completed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Completed_Female_Participants);
                    $Male += count($Completed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Completed Community Service';
            }
            ?>
        </td>
        <td><?php echo count($Completed_Female_Participants) ?></td>
        <td><?php echo count($Completed_Male_Participants) ?></td>
    </tr>
    <!--    Technology Transfer Starts -->
    <tr>
        <td rowspan="8">Technology Transfer</td>
        <td>Who takes Budget</td>
        <!--        Proposal in which their budget is withdraw proved if the all agreements are taken -->
        <?php
        $Technology_Transfer_Budget = 0;
        $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Budget_Male_Participants = array();
            $Budget_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Technology_Transfer_Budget += $data['Cost'];
//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Budget_Male_Participants) && $row['sex'] == 'Male') {
                                    $Budget_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Budget_Female_Participants) && $row['sex'] == 'Female') {
                                    $Budget_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Budget_Female_Participants);
                    $Male += count($Budget_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer ';
            }
            ?>
        </td>
        <td rowspan="8"><?php echo $Technology_Transfer_Budget ?></td>
        <td><?php echo count($Budget_Female_Participants); ?></td>
        <td><?php echo count($Budget_Male_Participants); ?></td>
    </tr>
    <tr>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Community_Result = $stmt->get_result();

        $Total += $Result->num_rows;
        ?>

        <td>Submitted Proposals</td>
        <td><?php echo $Community_Result->num_rows; ?></td>
        <td>
            <?php
            $Submitted_Community_Male_Participants = array();
            $Submitted_Community_Female_Participants = array();
            if ($Community_Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Community_Result->fetch_assoc()) {

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Submitted_Community_Male_Participants) && $row['sex'] == 'Male') {
                                    $Submitted_Community_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Submitted_Community_Female_Participants) && $row['sex'] == 'Female') {
                                    $Submitted_Community_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Submitted_Female_Participants);
                    $Male += count($Submitted_Male_Participants);
                    $TotalBudget+=$Technology_Transfer_Budget
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Submitted';
            }
            ?>
        </td>

        <td><?php echo count($Submitted_Community_Female_Participants) ?></td>
        <td><?php echo count($Submitted_Community_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Assessed at Faculty Level</td>
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status!='Not Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();
        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Assessed_Male_Participants = array();
            $Assessed_Female_Participants = array();
            if ($Result->num_rows > 0) {
                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Assessed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Assessed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Assessed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Assessed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Assessed_Female_Participants);
                    $Male += count($Assessed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo ' <li>No Technology Transfer Assessed at Faculty Level</li>';
            }
            ?>
        </td>
        <td><?php echo count($Assessed_Female_Participants) ?></td>
        <td><?php echo count($Assessed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at Faculty Level</td>
        <!--        Getting information about Proposals that are passed at Faculty level -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcc_level='Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Passed_Male_Participants = array();
            $Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Passed_Female_Participants);
                    $Male += count($Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Passed at Faculty Level';
            }
            ?>
        </td>
        <td><?php echo count($Passed_Female_Participants); ?></td>
        <td><?php echo count($Passed_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Assessed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcd_level!='Not Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Male_Participants = array();
            $University_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Female_Participants);
                    $Male += count($University_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Assessed at University Level';
            }
            ?>
        </td>
        <td><?php echo count($University_Female_Participants) ?></td>
        <td><?php echo count($University_Male_Participants) ?></td>
    </tr>
    <tr>
        <td>Passed at University Level</td>
        <!--        Getting information about proposals that are passed at University level-->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Rcd_level='Approved' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $University_Passed_Male_Participants = array();
            $University_Passed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $University_Passed_Male_Participants) && $row['sex'] == 'Male') {
                                    $University_Passed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $University_Passed_Female_Participants) && $row['sex'] == 'Female') {
                                    $University_Passed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($University_Passed_Female_Participants);
                    $Male += count($University_Passed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Passed at University Level';
            }
            ?>
        </td>
        <td><?php echo count($University_Passed_Female_Participants) ?></td>
        <td><?php echo count($University_Passed_Male_Participants) ?></td>
    </tr>

    <tr>
        <td>Progressing</td>
        <!--        Researches that are on Progress -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='On Progress' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows; ?></td>
        <td>
            <?php
            $Progressing_Male_Participants = array();
            $Progressing_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Progressing_Male_Participants) && $row['sex'] == 'Male') {
                                    $Progressing_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Progressing_Female_Participants) && $row['sex'] == 'Female') {
                                    $Progressing_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Progressing_Female_Participants);
                    $Male += count($Progressing_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Technology Transfer Found';
            }
            ?>
        </td>
        <td><?php echo count($Progressing_Female_Participants); ?></td>
        <td><?php echo count($Progressing_Male_Participants); ?></td>
    </tr>
    <tr>
        <td>Completed</td>
        <!--        Completed Researches -->
        <?php
        $stmt = $conn->prepare("Select * from Proposal Where  Status='Completed' and Type='Technology Transfer' and date like '" . $Year . "%'");
        $stmt->execute();
        $Result = $stmt->get_result();

        ?>
        <td><?php echo $Result->num_rows ?></td>
        <td>
            <?php
            $Completed_Male_Participants = array();
            $Completed_Female_Participants = array();
            if ($Result->num_rows > 0) {

                ?>
                <ol>
                    <?php

                    while ($data = $Result->fetch_assoc()) {
                        $Research_Budget += $data['Cost'];

//                        for counting female and male participants in each Proposal
                        $ProposalID = $data["ID"];
                        $stmt = $conn->prepare("SELECT DISTINCT  s.ID,s.sex FROM staffs as s , participant as pa WHERE s.ID=pa.Staff_ID and pa.Proposal_ID=?");
                        $stmt->bind_param('i', $ProposalID);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $StaffID = $row['ID'];
                                if (!in_array($StaffID, $Completed_Male_Participants) && $row['sex'] == 'Male') {
                                    $Completed_Male_Participants[] = $StaffID;
                                } elseif (!in_array($StaffID, $Completed_Female_Participants) && $row['sex'] == 'Female') {
                                    $Completed_Female_Participants[] = $StaffID;
                                }
                            }
                        } else {
//                            noting to do here
                        }
                        ?>
                        <li><?php echo $data['Title']; ?></li>

                        <?php
                    }
                    $Female += count($Completed_Female_Participants);
                    $Male += count($Completed_Male_Participants);
                    ?>
                </ol>
                <?php
            } else {
                echo 'No Completed Technology Transfer';
            }
            ?>
        </td>
        <td><?php echo count($Completed_Female_Participants) ?></td>
        <td><?php echo count($Completed_Male_Participants) ?></td>
    </tr>
    <tr>
        <td colspan="2">Total</td>
        <td><?php echo $Total; ?></td>
        <td></td>
        <td><?php echo $TotalBudget?></td>
        <td><?php echo $Female ?></td>
        <td><?php echo $Male ?></td>
    </tr>
    <?php
} elseif (isset($_REQUEST['Year'])) {
    $Faculty = $_REQUEST['v'];
    if (!empty($Faculty)) {

        $year = date('Y');
        ?>
        <option value="">Select Year</option>
        <?php
        for ($i = $year; $i > 2001; $i--) {
            ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php
        }
    }else{
        ?>
        <option value="">First Select Faculty</option>
<?php
    }

}
?>
