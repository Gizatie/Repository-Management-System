<?php
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Admin') {
    $sidebar = $_SESSION["Sidebar"];
    $sidebar_links = array(
        1 => "../Admin/AddStaffs.php",
        2 => "../Admin/CreateAccount.php",
        3 => "../Admin/Deactivate_Account.php",
        4 => "../Admin/Deactivate_Student_Account.php",
        5 => "../Admin/view_Account_Detail.php",
        6 => "../Researcher/SubmitProposal.php",
        7 => "../Researcher/SubmitDocument.php",
        8 => "../Researcher/ViewProposalStatus.php",
        9 => "../Researcher/ViewDocumentStatus.php",
        10 => "../Admin/home.php",
        11 => "../Admin/Change_Password.php",
    );

    switch ($sidebar) {
        case 'AddStaffs':
            $sidebar_links[1] = "#";
            break;
        case 'CreateAccount':
            $sidebar_links[2] = "#";
            break;
        case 'Deactivate_Account':
            $sidebar_links[3] = "#";
            break;
        case 'Deactivate_Student_Account':
            $sidebar_links[4] = "#";
            break;
        case 'view_Account_Detail':
            $sidebar_links[5] = "#";
            break;
        case 'SubmitProposal':
            $sidebar_links[6] = "#";
            break;
        case 'SubmitDocument':
            $sidebar_links[7] = "#";
            break;
        case 'ViewProposalStatus':
            $sidebar_links[8] = "#";
            break;
        case 'ViewDocumentStatus':
            $sidebar_links[9] = "#";
            break;
        case 'home':
            $sidebar_links[10] = "#";
            break;
        case 'Change_Password':
            $sidebar_links[11] = "#";
            break;
        default:
            $sidebar_links[10] = "#";
    }
?>
<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
        <div class="nano-content">
            <div class="logo">
                <a href="<?php echo $sidebar_links[10]?>">
                    <!-- <img src="assets/images/logo.png" alt="" /> -->
                    <span>DTU</span>
                </a>
            </div>
            <ul>
                <li class="label">Admin</li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-home"></i> Manage Account
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo $sidebar_links[1]?>">Upload Staffs</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[2]?>">Create Account</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[3]?>">Manage Staff Account</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[4]?>">Manage Student Account</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[5]?>">view Account Detail</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[11]?>">Change Passsword</a>
                        </li>
                    </ul>
                </li>
                <li class="label">As Instructor</li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-home"></i> Submit
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo $sidebar_links[6]?>">Proposal</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[7]?>">Document</a>
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
                            <a href="<?php echo $sidebar_links[8]?>">Proposal</a>
                        </li>
                        <li>
                            <a href="<?php echo $sidebar_links[9]?>">Documentation</a>
                        </li>

                    </ul>
                </li>

                <li class="label">Extra</li>

                <li>
                    <a href="../documentation/index.html">
                        <i class="ti-file"></i> Documentation</a>
                </li>
                <li>
                    <a href="../page-login.php?action=logout">
                        <i class="ti-close"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /# sidebar -->
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>