<?php

// Proposal_ID1 proposal to where the other proposal will be merged 
//  Proposal_ID2 list of proposals to be erged with proposal above

use LDAP\Result;

// require_once '../Common/DataBaseConnection.php';
function Merg($values)
{

    global $db_instance;
    global $conn;
    global  $Status;
    if ($values) {
        $Status = '';
        $temp = explode('/', $values[0]);
        $stmt = $conn->prepare("update proposal set Merge_With=? where ID=?");
        $State = 'Merged';
        $ProposalID = (int)$temp[1];
        $stmt->bind_param('si', $State, $ProposalID);
        if ($stmt->execute()) {
            foreach ($values as $a) {
                $data = explode('/', $a);
                $ProposalId = (int)$data[0];
                $ParentProposal = (int)$data[1];
                $stmt = $conn->prepare("update proposal set Merge_With=? where ID=?");
                $stmt->bind_param('ii', $ParentProposal, $ProposalId);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("update proposal set Status=? where ID=?");
                    $stat = 'Merged';
                    $stmt->bind_param('si', $stat, $ProposalId);
                    if ($stmt->execute()) {
                        $stmt = $conn->prepare("select * from  participant where Proposal_ID =?");
                        $stmt->bind_param('i', $ProposalId);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows) {
                            while ($propsalRows = $res->fetch_assoc()) {
                                $childPartecepantId = $propsalRows["Staff_ID"];
                                $stmt = $conn->prepare("select * from  participant where Proposal_ID =? and Staff_ID=?");
                                $stmt->bind_param('is', $ParentProposal, $childPartecepantId);
                                $stmt->execute();
                                $res2 = $stmt->get_result();
                                if ($res2->num_rows === 0) {
                                    $Staff_ID = $childPartecepantId;
                                    $Proposal_ID = $ParentProposal;
                                    $Role = 'Co';
                                    $Agreement = 'Not Taken';
                                    $stmt = $conn->prepare("INSERT INTO   participant (Staff_ID, Proposal_ID, Role, Agreement) VALUES (?, ?, ?, ?)");
                                    // $stmt = $conn->prepare("update  participant set Proposal_ID =? where Proposal_ID =? and Staff_ID=?");
                                    $stmt->bind_param('siss', $Staff_ID, $Proposal_ID, $Role, $Agreement);
                                    if ($stmt->execute()) {
                                        // Replaced by $ParentProposal becuase we can know with which proposal the proposal is merged
                                        // $agreement='Merged/';
                                        $stmt1 = $conn->prepare("update participant set Agreement=? where Proposal_ID=?");
                                        $stmt1->bind_param('si', $$ParentProposal, $ProposalId);
                                        if ($stmt1->execute()) {
                                            $Status = 'Successful';
                                        } else {
                                            $Status = 'Failed';
                                        }
                                    } else {
                                        $Status = 'Failed';
                                    }
                                }
                            }
                        }
                    }
                    $Status = 'Successful';
                } else {
                    $Status = 'Failed';
                }
            }
            if ($Status === 'Successful') {
                $conn->commit();
                echo '<script>alert("Merged Successfully")</script>';
            } else {
                $conn->rollback();
                echo '<script>alert(" Failed to Merged Successfully")</script>';
            }
        }
    }
    function SelectDocuments($data)
    {
        global $db_instance;
        global $conn;
    }
}
function validateUser($user, $pi)
{
    global $db_instance;
    global $conn;
    global  $Status;


    $stmt = $conn->prepare("select count(*) as total from staffs where ID=?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $data = $stmt->get_result();
    $row = $data->fetch_assoc();
    // return  $row['total'];
    // return $data;
    // $count=$row['count'];
    // echo "the returned value is ".$data
    // echo "<script>alert(hello)</script>";
    if ($row['total'] > 0 && $user !== $pi) {
        return true;
    } else {
        return false;
    }
}
function getFaculty()
{
    require_once '../Common/DataBaseConnection.php';

    $stmt = $conn->prepare("select * from faculty");
    $stmt->execute();
    return$stmt->get_result();
}
