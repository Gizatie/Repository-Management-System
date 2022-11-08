<?php
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher') {
    $sidebar = $_SESSION["Sidebar"];
    $sidebar_links = array(
        1 => "../RCC/Approve.php",
        2 => "../RCC/Approve_Documents.php",
        3 => "../RCC/Merge_Proposal.php",
        4 => "../RCC/Budget_Entry.php",
        5 => "../Researcher/SubmitProposal.php",
        6 => "../Researcher/SubmitDocument.php",
        7 => "../Researcher/ViewProposalStatus.php",
        8 => "../Researcher/ViewDocumentStatus.php",
        9 => "../RCC/Completed_Researches.php",
        10 => "../RCC/Progressing_Researches.php",
        11 => "../RCC/Completed_Researches_Proposals.php",
        12 => "../RCC/Completed_TT.php",
        13 => "../RCC/Progressing_TT.php",
        14 => "../RCC/Completed_TT_Proposals.php",
        15 => "../RCC/Completed_CS.php",
        16 => "../RCC/Progressing_CS.php",
        17 => "../RCC/Completed_CS_Proposals.php",
        18 => "../Researcher/home.php",
        19 => "../RCC/Transfer.php",
        20 => "../Researcher/Take_Agreement.php",
        21 => "../Researcher/ViewPreviewsWork.php",
        22 => "../RCC/Modify_Proposal.php",
        23 => "../Researcher/Assigned_Proposals.php",
        24 => "../Researcher/Assigned_Documents.php",
        25 => "../RCC/Approve_Agreement.php",
        26 => "../Common/Post_Notice.php",
        27 => "../RCC/Update_Budget.php",
        28 => "../RCC/Update_Budget_Researcher.php",
        29 => "../RCC/Notification.php",
        30 => "../Common/Change_Password.php",
    );
    switch ($sidebar) {
        case 'Approve':
            $sidebar_links[1] = "#";
            break;
        case 'Approve_Documents':
            $sidebar_links[2] = "#";
            break;
        case 'Merge_Proposal':
            $sidebar_links[3] = "#";
            break;
        case 'Budget_Entry':
            $sidebar_links[4] = "#";
            break;
        case 'SubmitProposal':
            $sidebar_links[5] = "#";
            break;
        case 'SubmitDocument':
            $sidebar_links[6] = "#";
            break;
        case 'ViewProposalStatus':
            $sidebar_links[7] = "#";
            break;
        case 'ViewDocumentStatus':
            $sidebar_links[8] = "#";
            break;
        case 'Completed_Researches':
            $sidebar_links[9] = "#";
            break;
        case 'Progressing_Researches':
            $sidebar_links[10] = "#";
            break;
        case 'Completed_Researches_Proposals':
            $sidebar_links[11] = "#";
            break;
        case 'Completed_TT':
            $sidebar_links[12] = "#";
            break;
        case 'Progressing_TT':
            $sidebar_links[13] = "#";
            break;
        case 'Completed_TT_Proposals':
            $sidebar_links[14] = "#";
            break;
        case 'Completed_CS':
            $sidebar_links[15] = "#";
            break;
        case 'Progressing_CS':
            $sidebar_links[16] = "#";
            break;
        case 'Completed_CS_Proposals':
            $sidebar_links[17] = "#";
            break;
        case 'home':
            $sidebar_links[18] = "#";
            break;
        case 'Transfer':
            $sidebar_links[19] = "#";
            break;
        case 'Take_Agreement':
            $sidebar_links[20] = "#";
            break;
        case 'ViewPreviewsWork':
            $sidebar_links[21] = "#";
            break;
        case 'Modify_Proposal':
            $sidebar_links[22] = "#";
            break;
        case 'Assigned_Proposals':
            $sidebar_links[23] = "#";
            break;
        case 'Assigned_Documents':
            $sidebar_links[24] = "#";
            break;
        case 'Approve_Agreement':
            $sidebar_links[25] = "#";
            break;
        case 'Post_Notice':
            $sidebar_links[26] = "#";
            break;
        case 'Update_Budget':
            $sidebar_links[27] = "#";
            break;
        case 'Update_Budget_Researcher':
            $sidebar_links[28] = "#";
            break;
        case 'Notification':
            $sidebar_links[29] = "#";
            break;
        case 'Change_Password':
            $sidebar_links[30] = "#";
            break;
        default:
            $sidebar_links[18] = "#";
    }
?>
    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <div class="logo">
                    <a href="<?php echo $sidebar_links[18] ?>">
                        <!-- <img src="assets/images/logo.png" alt="" /> -->
                        <span>DTU</span>
                    </a>
                </div>
                <ul>
                    <li class="label">RCC</li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-thumb-up"></i> Approve
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[1]; ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[2]; ?>">Document</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[25]; ?>">Agreement</a>
                            </li>
                            
                            <!-- <li>
                            <a href="<?php echo $sidebar_links[4]; ?>">Budget</a>
                        </li>  -->
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
                                        <a href="<?php echo $sidebar_links[9]; ?>">Completed Researches</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $sidebar_links[10]; ?>">Progressing Researches</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $sidebar_links[11]; ?>"> Completed Researches Proposals</a>
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
                                        <a href="<?php echo $sidebar_links[12]; ?>">Completed TT</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $sidebar_links[13]; ?>">Progressing TT</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $sidebar_links[14]; ?>"> Proposals</a>
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
                                        <a href="<?php echo $sidebar_links[15]; ?>">Completed CS</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $sidebar_links[16]; ?>">Progressing CS</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $sidebar_links[17]; ?>"> Completed Proposals</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[3]; ?>">
                            <i class="ti-files"></i> Merge</a>
                    </li>
                    <!-- <li>
                        <a href="<?php echo $sidebar_links[4]; ?>">
                            <i class="ti-file"></i> Budget</a>
                    </li> -->
                    <li>
                        <a href="<?php echo $sidebar_links[27]; ?>">
                            <i class="ti-pencil-alt2"></i> Update Budget</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[19]; ?>">
                            <i class="ti-location-arrow"></i> Transfer</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[22]; ?>">
                            <i class="ti-pencil"></i> Modify Proposal</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[29]; ?>">
                            <i class="ti-bell"></i> Post Notice</a>
                    </li>
                    <li class="label">Instructor</li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-new-window"></i> Submit
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[5]; ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[6]; ?>">Document</a>
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
                                <a href="<?php echo $sidebar_links[7]; ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[8]; ?>">Document</a>
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
                                    <a href="<?php echo $sidebar_links[23] ?>">Assigned Proposals</a>
                                </li>
                                <li>
                                    <a href="<?php echo $sidebar_links[24] ?>">Assigned Documents </a>
                                </li>


                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                    <li>
                        <a href="<?php echo $sidebar_links[28]; ?>">
                            <i class="ti-pencil"></i> Update Budget</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[20]; ?>">
                            <i class="ti-pencil-alt"></i> Agreement</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[21]; ?>">
                            <i class="ti-eye"></i> view Previews Work</a>
                    </li>
                    <li class="label">Extra</li>
                    <li>
                        <a href="<?php echo $sidebar_links[30]; ?>">
                            <i class="ti-settings"></i> Setting</a>
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