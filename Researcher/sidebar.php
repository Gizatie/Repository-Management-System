<?php
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Researcher') {
    $sidebar = $_SESSION["Sidebar"];
    $sidebar_links = array(
        1 => "../Researcher/SubmitProposal.php",
        "../Researcher/SubmitDocument.php",
        "../Researcher/ViewProposalStatus.php",
        "../Researcher/ViewDocumentStatus.php",
        "../Researcher/Take_Agreement.php",
        "../Researcher/View_Agreement_Status.php",
        "../Researcher/View_Research.php",
        "../Researcher/View_Technology_Transfer.php",
        "../Researcher/View_Community_Service.php",
        "../Researcher/home.php",
        "../Researcher/ViewPreviewsWork.php",
        "../Researcher/Assigned_Proposals.php",
        "../Researcher/Assigned_Documents.php",
        "../Researcher/Budget_Entry.php",
        "../RCC/Update_Budget_Researcher.php",
        "../Common/Change_Password.php",
        "../page-login.php?action=logout",
    );
    switch ($sidebar) {
        case 'SubmitProposal':
            $sidebar_links[1] = "#";
            break;
        case 'SubmitDocument':
            $sidebar_links[2] = "#";
            break;
        case 'ViewProposalStatus':
            $sidebar_links[3] = "#";
            break;
        case 'ViewDocumentStatus':
            $sidebar_links[4] = "#";
            break;
        case 'Take_Agreement':
            $sidebar_links[5] = "#";
            break;
        case 'View_Agreement_Status':
            $sidebar_links[6] = "#";
            break;
        case 'View_Research':
            $sidebar_links[7] = "#";
            break;
        case 'View_Technology_Transfer':
            $sidebar_links[8] = "#";
            break;
        case 'View_Community_Service':
            $sidebar_links[9] = "#";
            break;
        case 'home':
            $sidebar_links[10] = "#";
            break;
        case 'ViewPreviewsWork':
            $sidebar_links[11] = "#";
            break;
        case 'Assigned_Proposals':
            $sidebar_links[12] = "#";
            break;
        case 'Assigned_Documents':
            $sidebar_links[13] = "#";
            break;
        case 'Budget_Entry':
            $sidebar_links[14] = "#";
            break;
        case 'Budget_Entry':
            $sidebar_links[15] = "#";
            break;
        case 'Change_Password':
            $sidebar_links[16] = "#";
            break;
        case 'page-login':
            $sidebar_links[17] = "#";
            break;
        default:
            $sidebar_links[10] = "#";
    }
?>
    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <div class="logo">
                    <a href="<?php echo $sidebar_links[10] ?>">
                        <span>DTU</span>
                    </a>
                </div>
                <ul>
                    <li class="label">Researcher </li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-new-window"></i> Submit
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[1] ?>">Proposal</a>
                            </li>
                            <!-- <li>
                                <a href="<?php echo $sidebar_links[14] ?>">Budget Entry</a>
                            </li> -->
                            <li>
                                <a href="<?php echo $sidebar_links[2] ?>">Document</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-bar-chart-alt"></i> Status
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[3] ?>">Proposal </a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[4] ?>">Document</a>
                            </li>

                        </ul>

                    </li>
                    <?php
                    if ($_SESSION["Committee"] === 'Reviewer') {
                    ?>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-user"></i> Commite Work
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="<?php echo $sidebar_links[12] ?>">Assigned Proposals</a>
                                </li>
                                <li>
                                    <a href="<?php echo $sidebar_links[13] ?>">Assigned Documents </a>
                                </li>


                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                    <li>
                        <a href="<?php echo $sidebar_links[15] ?>">
                            <i class="ti-pencil"></i> Update Budget</a>
                    </li>

                    <li>
                        <a href="<?php echo $sidebar_links[5] ?>">
                            <i class="ti-pencil-alt"></i> Agreement</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[11] ?>">
                            <i class="ti-eye"></i> View Previews Work</a>
                    </li>


                    <li class="label">Extra</li>
                    <li>
                        <a href="<?php echo $sidebar_links[16] ?>">
                            <i class="ti-settings"></i> Account Setting</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[17] ?>">
                            <i class="ti-close"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>
<!-- /# sidebar -->