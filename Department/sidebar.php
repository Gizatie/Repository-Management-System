<?php
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department') {
    $sidebar = $_SESSION["Sidebar"];
    $sidebar_links = array(
        1 => "../Department/Approve.php",
        2 => "../Department/ApproveDocument.php",
        3 => "../Department/Merge_Proposal.php",
        4 => "../Researcher/SubmitProposal.php",
        5 => "../Researcher/SubmitDocument.php",
        6 => "../Researcher/ViewProposalStatus.php",
        7 => "../Researcher/ViewDocumentStatus.php",
        8 => "../Researcher/ViewPreviewsWork.php",
        9 => "../Researcher/home.php",
        10 => "../Department/Transfer.php",
        11 => "../Researcher/Take_Agreement.php",
        12 => "../Department/Assign_Committee_Work.php",
        13 => "../Department/Assign_Proposals.php",
        14 => "../Researcher/Assigned_Proposals.php",
        15 => "../Researcher/Assigned_Documents.php",
        16 => "../RCC/Update_Budget_Researcher.php",
        17 => "../RCC/Update_Budget_Researcher.php",
        18 => "../Common/Change_Password.php"
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
        case 'home':
            $sidebar_links[9] = "#";
            break;
        case 'SubmitProposal':
            $sidebar_links[4] = "#";
            break;
        case 'SubmitDocument':
            $sidebar_links[5] = "#";
            break;
        case 'ViewProposalStatus':
            $sidebar_links[6] = "#";
            break;
        case 'ViewDocumentStatus':
            $sidebar_links[7] = "#";
            break;
        case 'ViewPreviewsWork':
            $sidebar_links[8] = "#";
            break;
        case 'Transfer':
            $sidebar_links[10] = "#";
            break;
        case 'Take_Agreement':
            $sidebar_links[11] = "#";
            break;
        case 'Assign_Committee_Work':
            $sidebar_links[12] = "#";
            break;
        case 'Assign_Proposals':
            $sidebar_links[13] = "#";
            break;
        case 'Assigned_Proposals':
            $sidebar_links[14] = "#";
            break;
        case 'Assigned_Documents':
            $sidebar_links[15] = "#";
            break;
        case 'Update_Budget':
            $sidebar_links[16] = "#";
            break;
        case 'Update_Budget_Researcher':
            $sidebar_links[17] = "#";
            break;
        case 'Change_Password':
            $sidebar_links[18] = "#";
            break;
    }
?>
    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <div class="logo">
                    <a href="<?php echo $sidebar_links[9]; ?>">
                        <!-- <img src="assets/images/logo.png" alt="" /> -->
                        <span>DTU</span>
                    </a>
                </div>
                <ul>
                    <li class="label">Department</li>


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


                        </ul>
                    </li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-user"></i> Committee
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[12]; ?>">Assign Committee</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[13] ?>">Assign Proposals </a>
                            </li>


                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[3]; ?>">
                            <i class="ti-files"></i>Merge</a>

                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[10]; ?>">
                            <i class="ti-location-arrow"></i> Transfer</a>
                    </li>
                    

                    <li class="label">As Instructor</li>
                    <li>
                        <a class="sidebar-sub-toggle">
                            <i class="ti-new-window"></i> Submit
                            <span class="sidebar-collapse-icon ti-angle-down"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?php echo $sidebar_links[4]; ?>">Proposal</a>
                            </li>
                            <li>
                                <a href="<?php echo $sidebar_links[5]; ?>">Document</a>
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
                                <a href="<?php echo $sidebar_links[6]; ?>">Proposal</a>
                            </li>

                            <li>
                                <a href="<?php echo $sidebar_links[7]; ?>">Document</a>
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
                                    <a href="<?php echo $sidebar_links[14] ?>">Assigned Proposals</a>
                                </li>
                                <li>
                                    <a href="<?php echo $sidebar_links[15] ?>">Assigned Documents </a>
                                </li>


                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                    <li>
                        <a href="<?php echo $sidebar_links[17]; ?>">
                            <i class="ti-pencil"></i> Update Budget</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[11]; ?>">
                            <i class="ti-pencil-alt"></i> Agreement</a>
                    </li>
                    <li>
                        <a href="<?php echo $sidebar_links[8]; ?>">
                            <i class="ti-eye"></i> view Previews Work</a>
                    </li>

                    <li class="label">Extra</li>

                    <li>
                        <a href="<?php echo $sidebar_links[18] ?>">
                            <i class="ti-settings"></i>Setting</a>
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