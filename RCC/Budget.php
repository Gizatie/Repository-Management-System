<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCC'){
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>

<!--Side bar Starts-->
<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
        <div class="nano-content">
            <div class="logo">
                <a href="home.php">
                    <!-- <img src="assets/images/logo.png" alt="" /> -->
                    <span>DTU</span>
                </a>
            </div>
            <ul>
                <li class="label">RCC</li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-home"></i> Approve
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="Approve.php">Proposal</a>
                        </li>
                        <li>
                            <a href="ApproveDocument.php">Document</a>
                        </li>
                        <li>
                            <a href="#">Budget</a>
                        </li><li>
                            <a href="Merge_Proposal.php">Merge</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-home"></i> Reports
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>

                    <ul>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-home"></i> Research
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="Completed_Researches.php">Completed Researches</a>
                                </li>
                                <li>
                                    <a href="Progressing_Researches.php">Progressing Researches</a>
                                </li>
                                <li>
                                    <a href="#"> Proposals</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-home"></i> TT
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="#">Completed TT</a>
                                </li>
                                <li>
                                    <a href="#">Progressing TT</a>
                                </li>
                                <li>
                                    <a href="#"> Proposals</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-home"></i> CS
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="#">Completed CS</a>
                                </li>
                                <li>
                                    <a href="#">Progressing CS</a>
                                </li>
                                <li>
                                    <a href="#"> Proposals</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
                <li class="label">Instructor</li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-home"></i> Submit
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="SubmitProposal.php">Proposal</a>
                        </li>
                        <li>
                            <a href="SubmitDocument.php">Document</a>
                        </li>


                    </ul>
                </li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-bar-chart-alt"></i> View Status
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="ViewProposalStatus.php">Proposal</a>
                        </li>
                        <li>
                            <a href="ViewDocumentStatus.php">Document</a>
                        </li>

                    </ul>
                </li>

                <li class="label">Extra</li>
                <li>
                    <a href="../documentation/index.html">
                        <i class="ti-file"></i> Documentation</a>
                </li>
                <li>
                    <a href="http://localhost/system/page-login.php">
                        <i class="ti-close"></i> Logout</a>
                </li>


            </ul>
        </div>
    </div>
</div>
<!--Side bar Ends -->

<?php
require_once '../Common/Header.php';
//  to the button action on each proposal is clicked
if (isset($_GET['Action'])) {
    echo 'fffffffffffffffffff ';
    $Action = $_GET["Action"];
    $ProposalId = $_GET["ProposalId"];
    $status = '';
    $Final_Status='';
    $stmt = $conn->prepare("UPDATE proposal set Department_level=? where ID=?");

    switch ($Action) {
        case 'Approve':
            $status = 'Approved';
            $stmt->bind_param("ss", $status, $ProposalId);
            break;
        case 'Reject':
            $status = 'Reject';
            $stmt->bind_param("ss", $status,$ProposalId);
            break;
        case 'suspend':
            $status = 'suspend';
            $stmt->bind_param("ss", $status, $ProposalId);
            break;
    }
    if ($stmt->execute()) {
        echo '<script>alert("Action taken")</script>';
    }
}
elseif (isset($_REQUEST['Submit'])){
    $conn->begin_transaction();
    $values = $_GET['ary'];
    $Status='';
    $temp=$_GET['ary'];
    $temp=explode('/',$temp[0]);
    $stmt=$conn->prepare("update proposal set Merge_With=? where ID=?");
    $State='Merged';
    $ProposalID=(int)$temp[2];
    $stmt->bind_param('si',$State,$ProposalID);

   if ($stmt->execute()){
       foreach ($values as $a){
           $data=explode('/',$a);
           $ProposalId=(int)$data[0];
           $ParentProposal=(int)$data[2];
           $stmt=$conn->prepare("update proposal set Merge_With=? where ID=?");
           $stmt->bind_param('ii',$ParentProposal,$ProposalId);
           if ($stmt->execute()){
               $stmt=$conn->prepare("update proposal set Status=? where ID=?");
               $stat='Merged';
               $stmt->bind_param('si',$stat,$ProposalId);
               $stmt->execute();
               $Status='Successful';
           }else{
               $Status='Failed';
           }
       }
       if ($Status==='Successful'){
           $conn->commit();
           echo '<script>alert("Merged Successfully")</script>';
       }else{
           $conn->rollback();
           echo '<script>alert(" Failed to Merged Successfully")</script>';
       }
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
                            <h1><span></span>
                            </h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">DTU</a>
                                </li>
                                <li class="breadcrumb-item active">Department/home/Approve Proposal</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
                    <div class="col-lg-6">
                        <label class="control-label">Select Type</label>
                        <select class="form-control" onchange="selectProposal(this.value)" id="Type" required>
                            <option value="">Select Type</option>
                            <option value="Research">Research</option>
                            <option value="Technology Transfer">Technology Transfer</option>
                            <option value="Community Service">Community Service</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>
                                <center>#</center>
                            </th>
                            <th>
                                <center>Title</center>
                            </th>
                            <th>
                                <center>Type</center>
                            </th>
                            <th>
                                <center>Date</center>
                            </th>
                            <th>
                                <center>Terms</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Merged With</center>
                            </th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="body">

                        </tbody>

                    </table>
                </div>
                <?php require_once '../Common/Footer.php';?>
            </section>
        </div>
    </div>
</div>




</body>

</html>
<script>
    function selectProposal(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess?Proposals=Proposals&&v=' + v,

            success: function (html) {
                $('#body').html(html);
            }
        });
    }
    function merge(v='') {
        alert(v);

    }
</script>
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>