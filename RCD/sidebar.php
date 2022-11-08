<?php
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCD') {
    $sidebar = $_SESSION["Sidebar"];
    $sidebar_links = array(
        1 => "../RCD/Approve.php",
        2 => "../RCD/ApproveDocument.php",
        3 => "../RCD/Merge_Proposal.php",
        4 => "../RCD/ViewResearchProgress.php",
        5 => "../RCD/ViewTTProgress.php",
        6 => "../RCD/ViewCSProgress.php",
        7 => "../Faculty_Level_Report.php",
        8 => "../University_Level_Report.php",
        9 => "../Researcher/SubmitProposal.php",
        10 => "../Researcher/SubmitDocument.php",
        11 => "../Researcher/ViewProposalStatus.php",
        12 => "../Researcher/ViewDocumentStatus.php",
        13 => "../RCD/home.php",
        14 => "../RCD/Transfer.php",
        15 => "../Researcher/Take_Agreement.php",
        16 => "../Researcher/Assigned_Proposals.php",
        17 => "../Researcher/Assigned_Documents.php",
        18 => "../RCD/Approve_Agreement.php",
        19 => "../RCC/Update_Budget.php",
        20 => "../Researcher/ViewPreviewsWork.php",
        21 => "../RCC/Update_Budget_Researcher.php",
        22 => "../RCC/Notification.php",
    );
    switch ($sidebar) {
        case 'Approve':
            $sidebar_links[1] = "#";
            break;
        case 'ApproveDocument':
            $sidebar_links[2] = "#";
            break;
        case 'Merge_Proposal':
            $sidebar_links[3] = "#";
            break;
        case 'ViewResearchProgress':
            $sidebar_links[4] = "#";
            break;
        case 'ViewTTProgress':
            $sidebar_links[5] = "#";
            break;
        case 'ViewCSProgress':
            $sidebar_links[6] = "#";
            break;
        case 'Faculty_Level_Report':
            $sidebar_links[7] = "#";
            break;
        case 'University_Level_Report':
            $sidebar_links[8] = "#";
            break;
        case 'SubmitProposal':
            $sidebar_links[9] = "#";
            break;
        case 'SubmitDocument':
            $sidebar_links[10] = "#";
            break;
        case 'ViewProposalStatus':
            $sidebar_links[11] = "#";
            break;
        case 'ViewDocumentStatus':
            $sidebar_links[12] = "#";
            break;
        case 'home':
            $sidebar_links[13] = "#";
            break;
        case 'Transfer':
            $sidebar_links[14] = "#";
            break;
        case 'Take_Agreement':
            $sidebar_links[15] = "#";
            break;
        case 'Assigned_Proposals':
            $sidebar_links[16] = "#";
            break;
        case 'Assigned_Documents':
            $sidebar_links[17] = "#";
            break;
        case 'Approve_Agreement':
            $sidebar_links[18] = "#";
            break;
        case 'Update_Budget':
            $sidebar_links[19] = "#";
            break;
        case 'ViewPreviewsWork':
            $sidebar_links[20] = "#";
            break;
        case 'Update_Budget_Researcher':
            $sidebar_links[21] = "#";
            break;
        case 'Notification':
            $sidebar_links[22] = "#";
            break;
        default:
            $sidebar_links[13] = "#";
    }
?>
    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <div class="logo">
                    <a href="<?php echo $sidebar_links[13] ?>">
                        <!-- <img src="assets/images/logo.png" alt="" /> -->
                        <span>DTU</span>
                    </a>
                </div>
                <ul>
                    <li class="label">RCD</li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-home"></i> Approve
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[1] ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[2] ?>">Document</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[18] ?>">Agreement</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[3] ?>">
                            <i class="ti-file"></i> Merge</a>
                    </li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-bar-chart-alt"></i> View Progress
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[4] ?>">Research</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[5] ?>">TT</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[6] ?>">CS</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-bar-chart-alt"></i> Report
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[7] ?>">Faculty level</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[8] ?>">University Level</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[14] ?>"><i class="ti-file"></i> Transfer</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[19] ?>"><i class="ti-file"></i> Update Budget</a>
                    </li>
                    <li class="label">Instructor</li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-home"></i> Submit
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[9] ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[10] ?>">Document</a>
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
                                <a href="<?php echo $sidebar_links[11] ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[12] ?>">Document</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-bar-chart-alt"></i> Agreementssss
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[15] ?>">Take Agreement</a>
                            </li>
                            <!-- <li>
                            <a href="<?php echo $sidebar_links[6] ?>">View Agreement Status</a>
                        </li> -->
                        </ul>
                    </li>
                    <?php
                    if ($_SESSION["Committee"] === 'Reviewer') {
                    ?>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-bar-chart-alt"></i> Commite Work
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="<?php echo $sidebar_links[16] ?>">Assigned Proposals</a>
                                </li>
                                <li>
                                    <a href="<?php echo $sidebar_links[17] ?>">Assigned Documents </a>
                                </li>


                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                    <li>
                        <a href="<?php echo $sidebar_links[20]; ?>">
                            <i class="ti-eye"></i> view Previews Work</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[21]; ?>">
                            <i class="ti-pencil"></i> Update Budget</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[22]; ?>">
                            <i class="ti-bell"></i>Post Notice</a>
                    </li>
                    <li class="label">Extra</li>

                    <li>
                        <a>
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