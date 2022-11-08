<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    require_once('../Common/DataBaseConnection.php');
    if (isset($_REQUEST['Proposals'])) {
        $StaffID = $_SESSION['StaffId'];
        $stmt = $conn->prepare("select * from proposal as p , participant as pa  where p.ID=pa.Proposal_ID  and pa.Staff_ID=? and Type=? and p.Rcd_level='Approved'  and p.date like '2022%' order by p.date DESC");
        $status = 'Not Approved';
        $Department = $_SESSION["Department"];
        $Type = $_REQUEST['v'];
        $stmt->bind_param("ss", $StaffID, $Type);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $ToolTipModalId = 0;
            while ($data = $result->fetch_assoc()) {
                $ToolTipModalId++;
                             ?>
                <tr>
                    <td><?php echo $data["ID"] ?></td>
                    <td><?php echo $data["Title"] ?></td>
                    <td><?php echo $data["Type"] ?></td>
                    <td><?php echo $data["date"] ?></td>
                    <td>
                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                    </td>
                    <td><?php echo $data["Agreement"]; ?></td>
                    <td><?php echo $data["Cost"]; ?></td>
                    <td>
                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">More
                        </button>

                        <!-- Modal -->
                        <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header mo">
                                        <button type="button" class="close" data-dismiss="modal"></button>
                                        <h4 class="modal-title">Select The Proposal tobe merged with</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="Take_Agreement.php">
                                            <div class="form-group">
                                                <div class="col-lg-12">
                                                    <label id="label">Dear <?php echo $data['ID'] ?> </label>
                                                    <input type="email" name="Staff ID" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-12" multiple="">
                                                    <?php
                                                    if ($data["Type"] === 'Community Service') {
                                                    } else if ($data["Type"] === 'Project') {
                                                    } else if ($data["Type"] === 'Thesis') {
                                                    } else if ($data["Type"] === 'Technology Transfer') {
                                                    ?>

                                                    <?php

                                                    } else if ($data["Type"] === 'Research') {


                                                    ?>
                                                        <center>

                                                            THIS AGREEMENT is entered into and effective as of the ____ day
                                                            of
                                                            __________ 20__
                                                            ("Effective Date") by and between _______________________, a
                                                            _________corporation with
                                                            offices at ___________________________(“SPONSOR”); and The
                                                            University of Tennessee, a
                                                            public higher education institution and instrumentality of the
                                                            State
                                                            of Tennessee, on behalf of its
                                                            Health Science Center, with offices at 62 S. Dunlap, Suite 300,
                                                            Memphis, Tennessee 38163 ("the
                                                            UNIVERSITY").
                                                            RECITALS
                                                            WHEREAS, the research program contemplated by this Agreement is
                                                            of
                                                            mutual interest and benefit
                                                            to the parties and will further the instructional and research
                                                            objectives of the UNIVERSITY and the
                                                            research objectives of SPONSOR in a manner consistent with the
                                                            UNIVERSITY's status as an
                                                            educational corporate agency of the State of Tennessee.
                                                            NOW, THEREFORE, the parties agree as follows:
                                                            1. STATEMENT OF WORK AND PRINCIPAL INVESTIGATOR.
                                                            1.1 The UNIVERSITY agrees to use its reasonable efforts to
                                                            perform
                                                            the research project
                                                            ("Project") as set out in the Statement of Work titled,
                                                            ________________________________________, attached hereto and
                                                            incorporated herein by
                                                            reference as Appendix A.
                                                            1.2 The UNIVERSITY's obligations as stated in Article 1.1 above
                                                            shall be carried out under the
                                                            supervision of _____________________, Principal Investigator.
                                                            The
                                                            failure of _______________
                                                            for any reason to continue to serve as Principal Investigator
                                                            until
                                                            the normal conclusion of the Project
                                                            shall not be considered a breach of this Agreement and shall not
                                                            subject the UNIVERSITY to any
                                                            liability. In such case, the UNIVERSITY and SPONSOR shall
                                                            endeavor
                                                            to agree upon a successor,
                                                            and if they fail to agree, either of them may terminate this
                                                            Agreement without cause upon written
                                                            notice to the other party.
                                                            2. RESEARCH SUPPORT BY SPONSOR.
                                                            2.1 The total research support to be provided by SPONSOR is
                                                            $___________. Payments shall be
                                                            made to the UNIVERSITY by SPONSOR according to the following
                                                            schedule:
                                                            Insert schedule of payments (include non-refundable start-up
                                                            costs
                                                            if applicable, applicable
                                                            overhead, as much as possible up front, and not more than 10%
                                                            withheld as final payment)
                                                            NOTE: Budget should cover FULL cost of research
                                                            2
                                                            2.2 Checks shall be made payable to The University of Tennessee
                                                            and
                                                            shall be mailed to the
                                                            following address:
                                                            Anthony A. Ferrara, Vice Chancellor for Finance and Operations
                                                            UTHSC
                                                            62 S. Dunlap, Suite 300
                                                            Memphis, TN 38163
                                                            901 448-5523
                                                            spa@uthsc.edu
                                                            2.3 All funds provided by SPONSOR under this Agreement may be
                                                            used
                                                            at the discretion of the
                                                            UNIVERSITY.
                                                            3. TERM AND TERMINATION.
                                                            3.1 The term of this Agreement shall be from ___________________
                                                            (Effective Date) through
                                                            _____________________ , 20 ____, unless sooner terminated in
                                                            accordance with the provisions set
                                                            out herein. This Agreement may be extended, renewed, or
                                                            otherwise
                                                            amended at any time by the
                                                            mutual written consent of the parties.
                                                            3.2 In the event that either the UNIVERSITY or SPONSOR defaults
                                                            in
                                                            the due performance of its
                                                            obligations hereunder or in the event that any representation by
                                                            either of them proves to be false or
                                                            incorrect, and such default or breach is not cured within thirty
                                                            (30) days of written notice thereof,
                                                            then the party giving such notice may elect to terminate this
                                                            Agreement by final written notice to the
                                                            defaulting party. The parties recognize that the results of any
                                                            particular research project cannot be
                                                            guaranteed even through the use of the UNIVERSITY's reasonable
                                                            efforts; therefore, it is specifically
                                                            agreed that the failure of the UNIVERSITY to achieve specific
                                                            research results shall not constitute a
                                                            default or breach of this Agreement.
                                                            3.3 Either UNIVERSITY or SPONSOR may terminate this Agreement
                                                            without cause upon
                                                            written notification to the other at least thirty (30) days
                                                            prior to
                                                            the effective date of termination.
                                                            3.4 If the total funds paid by SPONSOR by the date of
                                                            termination
                                                            are insufficient to cover the
                                                            amounts earned in accordance with the budget and commitments
                                                            incurred by the UNIVERSITY in
                                                            the performance of the research, SPONSOR shall reimburse the
                                                            UNIVERSITY for same within thirty
                                                            (30) days of termination, provided that in no event shall
                                                            SPONSOR be
                                                            responsible for any amount in
                                                            excess of that stated in Article 2.1.
                                                            3.5 In the event of termination of this Agreement by the
                                                            UNIVERSITY
                                                            for default on the part of
                                                            SPONSOR, or termination of this Agreement by SPONSOR without
                                                            cause,
                                                            the Option granted under
                                                            Article 7 below shall thereupon terminate automatically.
                                                            3
                                                            4. EQUIPMENT.
                                                            4.1 Title to any equipment purchased, manufactured, or otherwise
                                                            acquired in the course of the
                                                            work under this Agreement shall vest in the UNIVERSITY,
                                                            notwithstanding any contribution
                                                            directly or indirectly from SPONSOR.
                                                            5. PUBLISHING.
                                                            5.1 UNIVERSITY reserves to itself and its employees the sole
                                                            right
                                                            to publish the results of the
                                                            Project in whole or in part as they deem appropriate. In order
                                                            that
                                                            premature public disclosure of
                                                            such information does not adversely affect the interests of the
                                                            parties, the UNIVERSITY shall
                                                            provide SPONSOR with a copy of each manuscript pertaining to the
                                                            Project that is intended for
                                                            publication. SPONSOR may request delay in publication for a
                                                            period
                                                            not to exceed ninety (90) days
                                                            from the date on which SPONSOR receives the manuscript. If
                                                            SPONSOR
                                                            does not make a written
                                                            request for delay in publication within thirty (30) days after
                                                            receipt of a manuscript, UNIVERSITY
                                                            shall be free to publish the manuscript at any time after the
                                                            end of
                                                            the thirty (30) days. SPONSOR’s
                                                            right to request a delay in publication shall not apply to any
                                                            thesis or dissertation.
                                                            6. CONFIDENTIALITY.
                                                            6.1 The UNIVERSITY and SPONSOR recognize that the conduct of a
                                                            research program may
                                                            require the transfer of proprietary information between the
                                                            parties.
                                                            Accordingly, it is agreed that the
                                                            acceptance by either of them of the other's proprietary
                                                            information
                                                            shall be subject to the following:
                                                            A. The term "Confidential Information" as used herein, in the
                                                            case
                                                            of documentary information,
                                                            shall include only that documentary information which is clearly
                                                            marked as proprietary (or
                                                            confidential) at the time when it is given to the receiving
                                                            party.
                                                            "Confidential Information" which is
                                                            originally orally disclosed shall include only that information
                                                            which is identified as being proprietary
                                                            or confidential at the time of disclosure and confirmed as
                                                            confidential by written communication sent
                                                            within a reasonably prompt period of time after it is disclosed
                                                            to
                                                            the receiving party.
                                                            B. The subject matter of the Confidential Information is to be
                                                            limited to that which is relative to
                                                            the research outlined in the Statement of Work under Article 1
                                                            above.
                                                            C. The receiving party will not publish or otherwise reveal to
                                                            any
                                                            third party the Confidential
                                                            Information (properly designated) of the disclosing party
                                                            without
                                                            the disclosing party's written
                                                            permission, unless the information:
                                                            (1) is already lawfully in the receiving party's possession at
                                                            the
                                                            time of receipt from the
                                                            disclosing party as evidenced by appropriate documentation;
                                                            (2) is or later becomes public through no fault of the receiving
                                                            party;
                                                            (3) is published by the UNIVERSITY and or its employee(s) in
                                                            accordance with the
                                                            provisions of Article 5 above;
                                                            4
                                                            (4) is lawfully received from a third party as evidenced by
                                                            appropriate documentation;
                                                            (5) has been in the possession of the receiving party for five
                                                            (5)
                                                            years or longer;
                                                            (6) is independently developed by the receiving party as
                                                            evidenced
                                                            by appropriate
                                                            documentation; or
                                                            (7) is required by law, including the Tennessee Public Records
                                                            Act,
                                                            T.C.A. 10-7-503 et
                                                            seq., to be disclosed.
                                                            7. INTELLECTUAL PROPERTY.
                                                            7.1 Pre-Existing Intellectual Property Rights of the Parties. No
                                                            party claims by virtue of this
                                                            Agreement any right, title, or interest in (a) any issued or
                                                            pending
                                                            patents or any copyrights owned or
                                                            controlled by another party or (b) any previous invention,
                                                            process,
                                                            or product of another party,
                                                            whether or not patented or patentable.
                                                            7.2 Definition. The term "Intellectual Property" shall mean all
                                                            inventions and developments
                                                            (whether or not patentable) and other creative works (excluding
                                                            theses, dissertations and scholarly
                                                            publications) developed in the course of the performance of the
                                                            work
                                                            under this Agreement,
                                                            including without limitation any patent, trademark, copyright,
                                                            mask
                                                            work right, or other property
                                                            right pertaining to same.
                                                            7.3 Allocation of rights.
                                                            A. Both UNIVERSITY and SPONSOR agree to promptly disclose to the
                                                            other all Intellectual
                                                            Property developed in the course of the work under this
                                                            Agreement.
                                                            B. The Intellectual Property developed solely by the UNIVERSITY
                                                            or
                                                            jointly by the
                                                            UNIVERSITY and SPONSOR in the performance of work under this
                                                            Agreement shall be
                                                            owned by the UNIVERSITY.
                                                            C. UNIVERSITY hereby grants to SPONSOR an exclusive option
                                                            ("Option") to acquire a
                                                            worldwide (to the extent possible) royalty-bearing license to
                                                            use
                                                            the Intellectual Property
                                                            developed in the course of the work under this Agreement in the
                                                            field of
                                                            __________________ (the “Optioned IP”).
                                                            D. The "Option Period" shall commence on the date of disclosure
                                                            to
                                                            SPONSOR of the Optioned
                                                            IP and shall terminate on the earlier of the following: (a) six
                                                            months from the date of
                                                            disclosure; or (b) termination of the Option pursuant to Article
                                                            3.5; or (c) proper exercise of
                                                            the Option by SPONSOR.
                                                            E. SPONSOR may exercise the Option during the Option Period by
                                                            giving written notice of
                                                            same to UNIVERSITY provided that SPONSOR is not then in default
                                                            or
                                                            breach of any of its
                                                            obligations under this Agreement.
                                                            F. Upon proper exercise of the Option by SPONSOR, UNIVERSITY and
                                                            SPONSOR will
                                                            negotiate in good faith in an effort to reach a
                                                            commercialization
                                                            agreement satisfactory to
                                                            5
                                                            both parties, the negotiation period not to exceed six (6)
                                                            months.
                                                            Upon the first to occur of
                                                            (a) termination of the Option by operation of the provisions of
                                                            Article 3.5 above; or (b)
                                                            expiration of the Option Period with the Option unexercised; or
                                                            (c)
                                                            expiration of the sixmonth negotiation period without the
                                                            execution
                                                            of a commercialization agreement,
                                                            UNIVERSITY shall have no further obligation to SPONSOR under
                                                            this
                                                            Agreement with
                                                            regard to the Optioned IP. In the absence of a further agreement
                                                            between UNIVERSITY and
                                                            SPONSOR, SPONSOR agrees that it will not use the Optioned IP for
                                                            any
                                                            commercial or noncommercial purpose.
                                                            G. During the Option Period, UNIVERSITY and SPONSOR will confer
                                                            concerning the proper
                                                            protection of the Optioned IP. Within thirty (30) days after
                                                            receipt
                                                            of an invoice from
                                                            UNIVERSITY, SPONSOR shall reimburse UNIVERSITY for all
                                                            out-of-pocket
                                                            expenses
                                                            incurred by UNIVERSITY during the Option Period in the filing,
                                                            prosecution, and
                                                            maintenance of United States and foreign patent applications,
                                                            issued
                                                            patents, and other forms
                                                            of intellectual property protection for the Optioned IP, all of
                                                            which shall be owned by
                                                            UNIVERSITY.
                                                            H. It is understood and agreed that any rights granted by or to
                                                            any
                                                            party by the terms of this
                                                            Agreement shall in all respects be subject to any rights claimed
                                                            or
                                                            restrictions and obligations
                                                            imposed by the United States government or any agency thereof,
                                                            whether such rights or
                                                            restrictions and obligations arise out of federal funding of the
                                                            underlying research or
                                                            otherwise.
                                                            8. BINDING AGREEMENT.
                                                            8.1 This Agreement shall be binding upon and inure to the
                                                            benefit of
                                                            each of the parties hereto,
                                                            their successors and assigns; provided, however, that this
                                                            Agreement
                                                            is not assignable or transferable,
                                                            in whole or in part, by any party without the prior written
                                                            consent
                                                            of the other parties.
                                                            Notwithstanding the foregoing, UNIVERSITY may assign its rights
                                                            and
                                                            interest to the University of
                                                            Tennessee Research Foundation (“UTRF”) or a successor in
                                                            interest to
                                                            UTRF or the UNIVERSITY
                                                            without the prior written consent of SPONSOR.
                                                            9. LIABILITY/INDEMNIFICATION.
                                                            9.1 The UNIVERSITY makes no warranties, either expressed or
                                                            implied,
                                                            as to the work to be
                                                            performed under this Agreement or the Optioned IP. THE
                                                            UNIVERSITY
                                                            SPECIFICALLY
                                                            DISCLAIMs ANY WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A
                                                            PARTICULAR PURPOSE. The UNIVERSITY shall not be liable for any
                                                            direct, consequential, or
                                                            other damages suffered by SPONSOR or others resulting from the
                                                            work
                                                            performed under this
                                                            Agreement or the Optioned IP.
                                                            9.2 LIMITATION OF LIABILITY ON BEHALF OF THE UNIVERSITY. The
                                                            UNIVERSITY
                                                            is self-insured under the provisions of the Tennessee Claims
                                                            Commission Act (T.C.A. 9-8-301 et
                                                            seq.), and its liability to SPONSOR and to third parties for the
                                                            negligence of the UNIVERSITY and
                                                            its employees is subject to the tort provisions of that Act.
                                                            Accordingly, any liability of the
                                                            UNIVERSITY for any damages, losses, or costs arising out of or
                                                            related to acts performed by the
                                                            6
                                                            UNIVERSITY or its employees under this agreement is governed by
                                                            the
                                                            provisions of said Act.
                                                            Notwithstanding anything in this agreement to the contrary, any
                                                            provisions or provisions of this
                                                            agreement will not apply to the extent that it is (they are)
                                                            finally
                                                            determined to violate the laws or
                                                            Constitution of the State of Tennessee.
                                                            9.3 SPONSOR will indemnify UNIVERSITY and their respective
                                                            trustees,
                                                            directors, officers,
                                                            employees and agents and hold them harmless from every loss,
                                                            cost or
                                                            damage for judgments, awards
                                                            or the compromise of any claim arising out of the use of the
                                                            Optioned IP or the advertisement,
                                                            manufacture, use or sale of any product or process by SPONSOR,
                                                            its
                                                            sublicensees, dealers or
                                                            customers.
                                                            10. EXPORT C
                                                            <label id="label">The Proposal Id is . <?php ?> </label>
                                                            <div class="well well-lg text-danger">Remember if you click the
                                                                agree button it will be considered as you agree the terms
                                                                listed
                                                                above including the Buget
                                                            </div>
                                                        </center>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Agreed&&ProposalID=<?php echo $data['ID'] ?>">Agree</a>
                                            </button>
                                            <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Disagreed&&ProposalID=<?php echo $data['ID'] ?>">Disagree</a>
                                            </button>
                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </td>
                    <td>
                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-info btn-small" data-toggle="modal" data-target="<?php echo '#ToolTipModal' . $data['ID']; ?>">See
                        </button>

                        <!-- Modal -->
                        <div id="<?php echo 'ToolTipModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header mo">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>
                                                        <center>First Name </center>
                                                    </th>
                                                    <th>
                                                        <center>Middle Name</center>
                                                    </th>
                                                    <th>
                                                        <center>Last Name</center>
                                                    </th>
                                                    <th>
                                                        <center>Status</center>
                                                    </th </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $conn->prepare("select * from staffs as s, participant as pa where  pa.Proposal_ID =?  and  s.ID=pa.Staff_ID  ");
                                                $Proposal_Id = $data['ID'];
                                                $stmt->bind_param("s", $Proposal_Id);
                                                $stmt->execute();
                                                $output = $stmt->get_result();
                                                if ($output->num_rows > 0) {
                                                    while ($row = $output->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $row["First_Name"] ?></td>
                                                            <td><?php echo $row["Middle_Name"] ?></td>
                                                            <td><?php echo $row["Last_Name"] ?></td>
                                                            <td><?php echo $row["Agreement"] ?></td>
                                                    <?php
                                                    }
                                                }
                                                    ?>
                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="8">
                    <center>No Record Found</center>
                </td>
            </tr>
            <?php
        }
    } 
    elseif (isset($_REQUEST['ViewProposal'])) {
        $StaffID = $_SESSION['StaffId'];
        $Type = $_REQUEST['Type'];
        $stmt = $conn->prepare("SELECT  p.Comment,p.Committee_Decision,p.ID,p.Title,p.File,p.Type from proposal as p  where p.Reviewer=? and p.Type=? and p.Status!='Completed' and p.Status!='On Progress' and p.Status!='Merged' and p.date like '" . Date("Y") . "%' order by date DESC");
        $stmt->bind_param("ss", $StaffID, $Type);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows) {

            while ($data = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $data["ID"] ?></td>
                    <td><?php echo $data["Title"] ?></td>
                    <td>
                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                    </td>
                    <td><?php echo $data["Committee_Decision"] ?></td>
                    <td><?php echo $data["Comment"] ?></td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">
                    <center>No Record<?php echo $StaffID, $Type?></center>
                </td>
            </tr>
            <?php
        }
    } 
    elseif (isset($_REQUEST['Select_Proposal'])) {
        $data = explode("/", $_REQUEST['Select_Proposal']);
        $Start_Year = $data[0];
        $Start_Year=$Start_Year.'-01-01';
        $Type = $data[1];
        if(count($data)===3){
         $End_Year=$data[2];
         $End_Year=$End_Year.'-12-12';
         $stmt = $conn->prepare("SELECT  p.Comment,p.Committee_Decision,p.ID,p.Title,p.File,p.Type from proposal as p  where p.Reviewer=? and p.Type=? and p.Status!='Completed' and p.Status!='On Progress' and p.Status!='Merged' and p.date like '" . Date("Y") . "%' order by date DESC");
         $stmt = $conn->prepare("SELECT p.Comment,p.Committee_Decision,p.ID,p.Title,p.File * FROM proposal as p where p.Type=?   and p.date >= '" . $Start_Year . "' and  p.date <='" .$End_Year. "' and (p.Status ='On Progress' OR p.Status='Completed')  order by p.date DESC");
        }else{
            $stmt = $conn->prepare("SELECT * FROM proposal as p where p.Type=? and (p.Status ='On Progress' OR p.Status='Completed')  and p.date >= '" . $Start_Year . "' and p.date <='" . Date("y-m-d") . "' order by p.date DESC");
        }
        $stmt->bind_param("s",  $Type);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
        $rowcolor=1;
        $rowcolorValue="";
            while ($data = $result->fetch_assoc()) {
                $rowcolor++;
                if($rowcolor%2===0){
                 $rowcolorValue='table-info';
                }else{
                   $rowcolorValue= 'table-Success';
                }
                 ?>
                <tr class="accordion-toggle collapsed <?php echo $rowcolorValue?>" id="accordion_<?php echo $data["ID"]?>" data-toggle="collapse" data-parent="#accordion_<?php echo $data["ID"]?>" href="#collapse_<?php echo $data["ID"]?>">
                    <td class="expand-button"></td>
                    <td><?php echo $data["ID"]?></td>
                    <td>  <center><a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                       </td>
                    <td>  <center><a href="../Documents/<?php echo $data["Type"] ?>/Final/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                       </td>

                </tr>
                <tr class="hide-table-padding">
                    <td></td>
                    <td colspan="3">
                        <div id="collapse_<?php echo $data["ID"]?>" class="collapse in p-4">
                            <center>
                                <p>
                                   <?php echo $data["Abstract"];?>
                                </p>
                            </center>
                        </div>
                    </td>
                </tr> 
            <?php
            
            }
        } else {
            ?>
            <tr>
                <td colspan="5">
                    <center>No Record</center>
                </td>
            </tr>
            <?php
        }
    } elseif (isset($_REQUEST['ViewDocument'])) {
        $StaffID = $_SESSION['StaffId'];
        $Type = $_REQUEST['Type'];
        // $stmt = $conn->prepare("SELECT  d.Comment,d.Committee_Decision,d.ID,d.Title,p.File from documents as d  where p.Reviewer=? and p.Type=? and p.Status!='Completed' and p.Status!='On Progress' and p.Status!='Merged' and p.date like '" . date("Y") . "%' order by date DESC");
        $stmt = $conn->prepare("SELECT DISTINCT d.Comment,d.Committee_Decision,d.ID,p.Title,d.file,p.Type
        FROM documents as d
        INNER JOIN proposal as p
        ON d.Proposal_ID = p.ID and D.Status!='Completed' and p.Reviewer=? and p.Type=?  ");
        $stmt->bind_param("ss", $StaffID, $Type);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {

            while ($data = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $data["ID"] ?></td>
                    <td><?php echo $data["Title"] ?></td>
                    <td>
                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["file"] ?>"><?php echo $data["file"] ?></a>
                    </td>
                    <td><?php echo $data["Committee_Decision"] ?></td>
                    <td><?php echo $data["Comment"] ?></td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">
                    <center>No Record</center>
                </td>
            </tr>
            <?php
        }
    } elseif (isset($_REQUEST['ProposalsByType'])) {
        $StaffID = $_SESSION['StaffId'];
        $stmt = $conn->prepare("select * from proposal as p , participant as pa  where p.ID=pa.Proposal_ID  and pa.Staff_ID=? and Type=? and Status='On Progress' and p.date like '" . date("Y") . "%' order by date DESC");
        $status = 'Not Approved';
        $Department = $_SESSION["Department"];
        $Type = $_REQUEST['v'];
        $stmt->bind_param("ss", $StaffID, $Type);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {

            while ($data = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $data["ID"] ?></td>
                    <td><?php echo $data["Title"] ?></td>
                    <td><?php echo $data["Type"] ?></td>
                    <td><?php echo $data["date"] ?></td>
                    <td>
                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                    </td>
                    <td><?php echo $data["Agreement"]; ?></td>
                    <td>
                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">Agree
                        </button>

                        <!-- Modal -->
                        <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header mo">
                                        <button type="button" class="close" data-dismiss="modal"></button>
                                        <h4 class="modal-title">Select The Proposal tobe merged with</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="Take_Agreement.php">
                                            <div class="form-group">
                                                <div class="col-lg-12">
                                                    <label id="label">Dear <?php echo $data['ID'] ?> </label>
                                                    <input type="email" name="Staff ID" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-12" multiple="">
                                                    <center>

                                                        THIS AGREEMENT is entered into and effective as of the ____ day
                                                        of
                                                        __________ 20__
                                                        ("Effective Date") by and between _______________________, a
                                                        _________corporation with
                                                        offices at ___________________________(“SPONSOR”); and The
                                                        University of Tennessee, a
                                                        public higher education institution and instrumentality of the
                                                        State
                                                        of Tennessee, on behalf of its
                                                        Health Science Center, with offices at 62 S. Dunlap, Suite 300,
                                                        Memphis, Tennessee 38163 ("the
                                                        UNIVERSITY").
                                                        RECITALS
                                                        WHEREAS, the research program contemplated by this Agreement is
                                                        of
                                                        mutual interest and benefit
                                                        to the parties and will further the instructional and research
                                                        objectives of the UNIVERSITY and the
                                                        research objectives of SPONSOR in a manner consistent with the
                                                        UNIVERSITY's status as an
                                                        educational corporate agency of the State of Tennessee.
                                                        NOW, THEREFORE, the parties agree as follows:
                                                        1. STATEMENT OF WORK AND PRINCIPAL INVESTIGATOR.
                                                        1.1 The UNIVERSITY agrees to use its reasonable efforts to
                                                        perform
                                                        the research project
                                                        ("Project") as set out in the Statement of Work titled,
                                                        ________________________________________, attached hereto and
                                                        incorporated herein by
                                                        reference as Appendix A.
                                                        1.2 The UNIVERSITY's obligations as stated in Article 1.1 above
                                                        shall be carried out under the
                                                        supervision of _____________________, Principal Investigator.
                                                        The
                                                        failure of _______________
                                                        for any reason to continue to serve as Principal Investigator
                                                        until
                                                        the normal conclusion of the Project
                                                        shall not be considered a breach of this Agreement and shall not
                                                        subject the UNIVERSITY to any
                                                        liability. In such case, the UNIVERSITY and SPONSOR shall
                                                        endeavor
                                                        to agree upon a successor,
                                                        and if they fail to agree, either of them may terminate this
                                                        Agreement without cause upon written
                                                        notice to the other party.
                                                        2. RESEARCH SUPPORT BY SPONSOR.
                                                        2.1 The total research support to be provided by SPONSOR is
                                                        $___________. Payments shall be
                                                        made to the UNIVERSITY by SPONSOR according to the following
                                                        schedule:
                                                        Insert schedule of payments (include non-refundable start-up
                                                        costs
                                                        if applicable, applicable
                                                        overhead, as much as possible up front, and not more than 10%
                                                        withheld as final payment)
                                                        NOTE: Budget should cover FULL cost of research
                                                        2
                                                        2.2 Checks shall be made payable to The University of Tennessee
                                                        and
                                                        shall be mailed to the
                                                        following address:
                                                        Anthony A. Ferrara, Vice Chancellor for Finance and Operations
                                                        UTHSC
                                                        62 S. Dunlap, Suite 300
                                                        Memphis, TN 38163
                                                        901 448-5523
                                                        spa@uthsc.edu
                                                        2.3 All funds provided by SPONSOR under this Agreement may be
                                                        used
                                                        at the discretion of the
                                                        UNIVERSITY.
                                                        3. TERM AND TERMINATION.
                                                        3.1 The term of this Agreement shall be from ___________________
                                                        (Effective Date) through
                                                        _____________________ , 20 ____, unless sooner terminated in
                                                        accordance with the provisions set
                                                        out herein. This Agreement may be extended, renewed, or
                                                        otherwise
                                                        amended at any time by the
                                                        mutual written consent of the parties.
                                                        3.2 In the event that either the UNIVERSITY or SPONSOR defaults
                                                        in
                                                        the due performance of its
                                                        obligations hereunder or in the event that any representation by
                                                        either of them proves to be false or
                                                        incorrect, and such default or breach is not cured within thirty
                                                        (30) days of written notice thereof,
                                                        then the party giving such notice may elect to terminate this
                                                        Agreement by final written notice to the
                                                        defaulting party. The parties recognize that the results of any
                                                        particular research project cannot be
                                                        guaranteed even through the use of the UNIVERSITY's reasonable
                                                        efforts; therefore, it is specifically
                                                        agreed that the failure of the UNIVERSITY to achieve specific
                                                        research results shall not constitute a
                                                        default or breach of this Agreement.
                                                        3.3 Either UNIVERSITY or SPONSOR may terminate this Agreement
                                                        without cause upon
                                                        written notification to the other at least thirty (30) days
                                                        prior to
                                                        the effective date of termination.
                                                        3.4 If the total funds paid by SPONSOR by the date of
                                                        termination
                                                        are insufficient to cover the
                                                        amounts earned in accordance with the budget and commitments
                                                        incurred by the UNIVERSITY in
                                                        the performance of the research, SPONSOR shall reimburse the
                                                        UNIVERSITY for same within thirty
                                                        (30) days of termination, provided that in no event shall
                                                        SPONSOR be
                                                        responsible for any amount in
                                                        excess of that stated in Article 2.1.
                                                        3.5 In the event of termination of this Agreement by the
                                                        UNIVERSITY
                                                        for default on the part of
                                                        SPONSOR, or termination of this Agreement by SPONSOR without
                                                        cause,
                                                        the Option granted under
                                                        Article 7 below shall thereupon terminate automatically.
                                                        3
                                                        4. EQUIPMENT.
                                                        4.1 Title to any equipment purchased, manufactured, or otherwise
                                                        acquired in the course of the
                                                        work under this Agreement shall vest in the UNIVERSITY,
                                                        notwithstanding any contribution
                                                        directly or indirectly from SPONSOR.
                                                        5. PUBLISHING.
                                                        5.1 UNIVERSITY reserves to itself and its employees the sole
                                                        right
                                                        to publish the results of the
                                                        Project in whole or in part as they deem appropriate. In order
                                                        that
                                                        premature public disclosure of
                                                        such information does not adversely affect the interests of the
                                                        parties, the UNIVERSITY shall
                                                        provide SPONSOR with a copy of each manuscript pertaining to the
                                                        Project that is intended for
                                                        publication. SPONSOR may request delay in publication for a
                                                        period
                                                        not to exceed ninety (90) days
                                                        from the date on which SPONSOR receives the manuscript. If
                                                        SPONSOR
                                                        does not make a written
                                                        request for delay in publication within thirty (30) days after
                                                        receipt of a manuscript, UNIVERSITY
                                                        shall be free to publish the manuscript at any time after the
                                                        end of
                                                        the thirty (30) days. SPONSOR’s
                                                        right to request a delay in publication shall not apply to any
                                                        thesis or dissertation.
                                                        6. CONFIDENTIALITY.
                                                        6.1 The UNIVERSITY and SPONSOR recognize that the conduct of a
                                                        research program may
                                                        require the transfer of proprietary information between the
                                                        parties.
                                                        Accordingly, it is agreed that the
                                                        acceptance by either of them of the other's proprietary
                                                        information
                                                        shall be subject to the following:
                                                        A. The term "Confidential Information" as used herein, in the
                                                        case
                                                        of documentary information,
                                                        shall include only that documentary information which is clearly
                                                        marked as proprietary (or
                                                        confidential) at the time when it is given to the receiving
                                                        party.
                                                        "Confidential Information" which is
                                                        originally orally disclosed shall include only that information
                                                        which is identified as being proprietary
                                                        or confidential at the time of disclosure and confirmed as
                                                        confidential by written communication sent
                                                        within a reasonably prompt period of time after it is disclosed
                                                        to
                                                        the receiving party.
                                                        B. The subject matter of the Confidential Information is to be
                                                        limited to that which is relative to
                                                        the research outlined in the Statement of Work under Article 1
                                                        above.
                                                        C. The receiving party will not publish or otherwise reveal to
                                                        any
                                                        third party the Confidential
                                                        Information (properly designated) of the disclosing party
                                                        without
                                                        the disclosing party's written
                                                        permission, unless the information:
                                                        (1) is already lawfully in the receiving party's possession at
                                                        the
                                                        time of receipt from the
                                                        disclosing party as evidenced by appropriate documentation;
                                                        (2) is or later becomes public through no fault of the receiving
                                                        party;
                                                        (3) is published by the UNIVERSITY and or its employee(s) in
                                                        accordance with the
                                                        provisions of Article 5 above;
                                                        4
                                                        (4) is lawfully received from a third party as evidenced by
                                                        appropriate documentation;
                                                        (5) has been in the possession of the receiving party for five
                                                        (5)
                                                        years or longer;
                                                        (6) is independently developed by the receiving party as
                                                        evidenced
                                                        by appropriate
                                                        documentation; or
                                                        (7) is required by law, including the Tennessee Public Records
                                                        Act,
                                                        T.C.A. 10-7-503 et
                                                        seq., to be disclosed.
                                                        7. INTELLECTUAL PROPERTY.
                                                        7.1 Pre-Existing Intellectual Property Rights of the Parties. No
                                                        party claims by virtue of this
                                                        Agreement any right, title, or interest in (a) any issued or
                                                        pending
                                                        patents or any copyrights owned or
                                                        controlled by another party or (b) any previous invention,
                                                        process,
                                                        or product of another party,
                                                        whether or not patented or patentable.
                                                        7.2 Definition. The term "Intellectual Property" shall mean all
                                                        inventions and developments
                                                        (whether or not patentable) and other creative works (excluding
                                                        theses, dissertations and scholarly
                                                        publications) developed in the course of the performance of the
                                                        work
                                                        under this Agreement,
                                                        including without limitation any patent, trademark, copyright,
                                                        mask
                                                        work right, or other property
                                                        right pertaining to same.
                                                        7.3 Allocation of rights.
                                                        A. Both UNIVERSITY and SPONSOR agree to promptly disclose to the
                                                        other all Intellectual
                                                        Property developed in the course of the work under this
                                                        Agreement.
                                                        B. The Intellectual Property developed solely by the UNIVERSITY
                                                        or
                                                        jointly by the
                                                        UNIVERSITY and SPONSOR in the performance of work under this
                                                        Agreement shall be
                                                        owned by the UNIVERSITY.
                                                        C. UNIVERSITY hereby grants to SPONSOR an exclusive option
                                                        ("Option") to acquire a
                                                        worldwide (to the extent possible) royalty-bearing license to
                                                        use
                                                        the Intellectual Property
                                                        developed in the course of the work under this Agreement in the
                                                        field of
                                                        __________________ (the “Optioned IP”).
                                                        D. The "Option Period" shall commence on the date of disclosure
                                                        to
                                                        SPONSOR of the Optioned
                                                        IP and shall terminate on the earlier of the following: (a) six
                                                        months from the date of
                                                        disclosure; or (b) termination of the Option pursuant to Article
                                                        3.5; or (c) proper exercise of
                                                        the Option by SPONSOR.
                                                        E. SPONSOR may exercise the Option during the Option Period by
                                                        giving written notice of
                                                        same to UNIVERSITY provided that SPONSOR is not then in default
                                                        or
                                                        breach of any of its
                                                        obligations under this Agreement.
                                                        F. Upon proper exercise of the Option by SPONSOR, UNIVERSITY and
                                                        SPONSOR will
                                                        negotiate in good faith in an effort to reach a
                                                        commercialization
                                                        agreement satisfactory to
                                                        5
                                                        both parties, the negotiation period not to exceed six (6)
                                                        months.
                                                        Upon the first to occur of
                                                        (a) termination of the Option by operation of the provisions of
                                                        Article 3.5 above; or (b)
                                                        expiration of the Option Period with the Option unexercised; or
                                                        (c)
                                                        expiration of the sixmonth negotiation period without the
                                                        execution
                                                        of a commercialization agreement,
                                                        UNIVERSITY shall have no further obligation to SPONSOR under
                                                        this
                                                        Agreement with
                                                        regard to the Optioned IP. In the absence of a further agreement
                                                        between UNIVERSITY and
                                                        SPONSOR, SPONSOR agrees that it will not use the Optioned IP for
                                                        any
                                                        commercial or noncommercial purpose.
                                                        G. During the Option Period, UNIVERSITY and SPONSOR will confer
                                                        concerning the proper
                                                        protection of the Optioned IP. Within thirty (30) days after
                                                        receipt
                                                        of an invoice from
                                                        UNIVERSITY, SPONSOR shall reimburse UNIVERSITY for all
                                                        out-of-pocket
                                                        expenses
                                                        incurred by UNIVERSITY during the Option Period in the filing,
                                                        prosecution, and
                                                        maintenance of United States and foreign patent applications,
                                                        issued
                                                        patents, and other forms
                                                        of intellectual property protection for the Optioned IP, all of
                                                        which shall be owned by
                                                        UNIVERSITY.
                                                        H. It is understood and agreed that any rights granted by or to
                                                        any
                                                        party by the terms of this
                                                        Agreement shall in all respects be subject to any rights claimed
                                                        or
                                                        restrictions and obligations
                                                        imposed by the United States government or any agency thereof,
                                                        whether such rights or
                                                        restrictions and obligations arise out of federal funding of the
                                                        underlying research or
                                                        otherwise.
                                                        8. BINDING AGREEMENT.
                                                        8.1 This Agreement shall be binding upon and inure to the
                                                        benefit of
                                                        each of the parties hereto,
                                                        their successors and assigns; provided, however, that this
                                                        Agreement
                                                        is not assignable or transferable,
                                                        in whole or in part, by any party without the prior written
                                                        consent
                                                        of the other parties.
                                                        Notwithstanding the foregoing, UNIVERSITY may assign its rights
                                                        and
                                                        interest to the University of
                                                        Tennessee Research Foundation (“UTRF”) or a successor in
                                                        interest to
                                                        UTRF or the UNIVERSITY
                                                        without the prior written consent of SPONSOR.
                                                        9. LIABILITY/INDEMNIFICATION.
                                                        9.1 The UNIVERSITY makes no warranties, either expressed or
                                                        implied,
                                                        as to the work to be
                                                        performed under this Agreement or the Optioned IP. THE
                                                        UNIVERSITY
                                                        SPECIFICALLY
                                                        DISCLAIMs ANY WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A
                                                        PARTICULAR PURPOSE. The UNIVERSITY shall not be liable for any
                                                        direct, consequential, or
                                                        other damages suffered by SPONSOR or others resulting from the
                                                        work
                                                        performed under this
                                                        Agreement or the Optioned IP.
                                                        9.2 LIMITATION OF LIABILITY ON BEHALF OF THE UNIVERSITY. The
                                                        UNIVERSITY
                                                        is self-insured under the provisions of the Tennessee Claims
                                                        Commission Act (T.C.A. 9-8-301 et
                                                        seq.), and its liability to SPONSOR and to third parties for the
                                                        negligence of the UNIVERSITY and
                                                        its employees is subject to the tort provisions of that Act.
                                                        Accordingly, any liability of the
                                                        UNIVERSITY for any damages, losses, or costs arising out of or
                                                        related to acts performed by the
                                                        6
                                                        UNIVERSITY or its employees under this agreement is governed by
                                                        the
                                                        provisions of said Act.
                                                        Notwithstanding anything in this agreement to the contrary, any
                                                        provisions or provisions of this
                                                        agreement will not apply to the extent that it is (they are)
                                                        finally
                                                        determined to violate the laws or
                                                        Constitution of the State of Tennessee.
                                                        9.3 SPONSOR will indemnify UNIVERSITY and their respective
                                                        trustees,
                                                        directors, officers,
                                                        employees and agents and hold them harmless from every loss,
                                                        cost or
                                                        damage for judgments, awards
                                                        or the compromise of any claim arising out of the use of the
                                                        Optioned IP or the advertisement,
                                                        manufacture, use or sale of any product or process by SPONSOR,
                                                        its
                                                        sublicensees, dealers or
                                                        customers.
                                                        10. EXPORT C
                                                        <label id="label">The Proposal Id is . <?php ?> </label>
                                                        <div class="well well-lg text-danger">Remember if you click the
                                                            agree button it will be considered as you agree the terms
                                                            listed
                                                            above
                                                        </div>
                                                </div>
                                            </div>
                                            <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Agreed&&ProposalID=<?php echo $data['ID'] ?>">Agree</a>
                                            </button>
                                            <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Disagreed&&ProposalID=<?php echo $data['ID'] ?>">Disagree</a>
                                            </button>
                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#ToolTipModal' . $data['ID']; ?>">Agree
                        </button>
                        <!-- Modal -->
                        <div id="<?php echo 'ToolTipModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header mo">
                                        <button type="button" class="close" data-dismiss="modal"></button>
                                        <h4 class="modal-title">Select The Proposal tobe merged with</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="Take_Agreement.php">
                                            <div class="form-group">
                                                <div class="col-lg-12">
                                                    <label id="label">Dear <?php echo $data['ID'] ?> </label>
                                                    <input type="email" name="Staff ID" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-12" multiple="">
                                                    <center>

                                                        THIS AGREEMENT is entered into and effective as of the ____ day
                                                        of
                                                        __________ 20__
                                                        ("Effective Date") by and between _______________________, a
                                                        _________corporation with
                                                        offices at ___________________________(“SPONSOR”); and The
                                                        University of Tennessee, a
                                                        public higher education institution and instrumentality of the
                                                        State
                                                        of Tennessee, on behalf of its
                                                        Health Science Center, with offices at 62 S. Dunlap, Suite 300,
                                                        Memphis, Tennessee 38163 ("the
                                                        UNIVERSITY").
                                                        RECITALS
                                                        WHEREAS, the research program contemplated by this Agreement is
                                                        of
                                                        mutual interest and benefit
                                                        to the parties and will further the instructional and research
                                                        objectives of the UNIVERSITY and the
                                                        research objectives of SPONSOR in a manner consistent with the
                                                        UNIVERSITY's status as an
                                                        educational corporate agency of the State of Tennessee.
                                                        NOW, THEREFORE, the parties agree as follows:
                                                        1. STATEMENT OF WORK AND PRINCIPAL INVESTIGATOR.
                                                        1.1 The UNIVERSITY agrees to use its reasonable efforts to
                                                        perform
                                                        the research project
                                                        ("Project") as set out in the Statement of Work titled,
                                                        ________________________________________, attached hereto and
                                                        incorporated herein by
                                                        reference as Appendix A.
                                                        1.2 The UNIVERSITY's obligations as stated in Article 1.1 above
                                                        shall be carried out under the
                                                        supervision of _____________________, Principal Investigator.
                                                        The
                                                        failure of _______________
                                                        for any reason to continue to serve as Principal Investigator
                                                        until
                                                        the normal conclusion of the Project
                                                        shall not be considered a breach of this Agreement and shall not
                                                        subject the UNIVERSITY to any
                                                        liability. In such case, the UNIVERSITY and SPONSOR shall
                                                        endeavor
                                                        to agree upon a successor,
                                                        and if they fail to agree, either of them may terminate this
                                                        Agreement without cause upon written
                                                        notice to the other party.
                                                        2. RESEARCH SUPPORT BY SPONSOR.
                                                        2.1 The total research support to be provided by SPONSOR is
                                                        $___________. Payments shall be
                                                        made to the UNIVERSITY by SPONSOR according to the following
                                                        schedule:
                                                        Insert schedule of payments (include non-refundable start-up
                                                        costs
                                                        if applicable, applicable
                                                        overhead, as much as possible up front, and not more than 10%
                                                        withheld as final payment)
                                                        NOTE: Budget should cover FULL cost of research
                                                        2
                                                        2.2 Checks shall be made payable to The University of Tennessee
                                                        and
                                                        shall be mailed to the
                                                        following address:
                                                        Anthony A. Ferrara, Vice Chancellor for Finance and Operations
                                                        UTHSC
                                                        62 S. Dunlap, Suite 300
                                                        Memphis, TN 38163
                                                        901 448-5523
                                                        spa@uthsc.edu
                                                        2.3 All funds provided by SPONSOR under this Agreement may be
                                                        used
                                                        at the discretion of the
                                                        UNIVERSITY.
                                                        3. TERM AND TERMINATION.
                                                        3.1 The term of this Agreement shall be from ___________________
                                                        (Effective Date) through
                                                        _____________________ , 20 ____, unless sooner terminated in
                                                        accordance with the provisions set
                                                        out herein. This Agreement may be extended, renewed, or
                                                        otherwise
                                                        amended at any time by the
                                                        mutual written consent of the parties.
                                                        3.2 In the event that either the UNIVERSITY or SPONSOR defaults
                                                        in
                                                        the due performance of its
                                                        obligations hereunder or in the event that any representation by
                                                        either of them proves to be false or
                                                        incorrect, and such default or breach is not cured within thirty
                                                        (30) days of written notice thereof,
                                                        then the party giving such notice may elect to terminate this
                                                        Agreement by final written notice to the
                                                        defaulting party. The parties recognize that the results of any
                                                        particular research project cannot be
                                                        guaranteed even through the use of the UNIVERSITY's reasonable
                                                        efforts; therefore, it is specifically
                                                        agreed that the failure of the UNIVERSITY to achieve specific
                                                        research results shall not constitute a
                                                        default or breach of this Agreement.
                                                        3.3 Either UNIVERSITY or SPONSOR may terminate this Agreement
                                                        without cause upon
                                                        written notification to the other at least thirty (30) days
                                                        prior to
                                                        the effective date of termination.
                                                        3.4 If the total funds paid by SPONSOR by the date of
                                                        termination
                                                        are insufficient to cover the
                                                        amounts earned in accordance with the budget and commitments
                                                        incurred by the UNIVERSITY in
                                                        the performance of the research, SPONSOR shall reimburse the
                                                        UNIVERSITY for same within thirty
                                                        (30) days of termination, provided that in no event shall
                                                        SPONSOR be
                                                        responsible for any amount in
                                                        excess of that stated in Article 2.1.
                                                        3.5 In the event of termination of this Agreement by the
                                                        UNIVERSITY
                                                        for default on the part of
                                                        SPONSOR, or termination of this Agreement by SPONSOR without
                                                        cause,
                                                        the Option granted under
                                                        Article 7 below shall thereupon terminate automatically.
                                                        3
                                                        4. EQUIPMENT.
                                                        4.1 Title to any equipment purchased, manufactured, or otherwise
                                                        acquired in the course of the
                                                        work under this Agreement shall vest in the UNIVERSITY,
                                                        notwithstanding any contribution
                                                        directly or indirectly from SPONSOR.
                                                        5. PUBLISHING.
                                                        5.1 UNIVERSITY reserves to itself and its employees the sole
                                                        right
                                                        to publish the results of the
                                                        Project in whole or in part as they deem appropriate. In order
                                                        that
                                                        premature public disclosure of
                                                        such information does not adversely affect the interests of the
                                                        parties, the UNIVERSITY shall
                                                        provide SPONSOR with a copy of each manuscript pertaining to the
                                                        Project that is intended for
                                                        publication. SPONSOR may request delay in publication for a
                                                        period
                                                        not to exceed ninety (90) days
                                                        from the date on which SPONSOR receives the manuscript. If
                                                        SPONSOR
                                                        does not make a written
                                                        request for delay in publication within thirty (30) days after
                                                        receipt of a manuscript, UNIVERSITY
                                                        shall be free to publish the manuscript at any time after the
                                                        end of
                                                        the thirty (30) days. SPONSOR’s
                                                        right to request a delay in publication shall not apply to any
                                                        thesis or dissertation.
                                                        6. CONFIDENTIALITY.
                                                        6.1 The UNIVERSITY and SPONSOR recognize that the conduct of a
                                                        research program may
                                                        require the transfer of proprietary information between the
                                                        parties.
                                                        Accordingly, it is agreed that the
                                                        acceptance by either of them of the other's proprietary
                                                        information
                                                        shall be subject to the following:
                                                        A. The term "Confidential Information" as used herein, in the
                                                        case
                                                        of documentary information,
                                                        shall include only that documentary information which is clearly
                                                        marked as proprietary (or
                                                        confidential) at the time when it is given to the receiving
                                                        party.
                                                        "Confidential Information" which is
                                                        originally orally disclosed shall include only that information
                                                        which is identified as being proprietary
                                                        or confidential at the time of disclosure and confirmed as
                                                        confidential by written communication sent
                                                        within a reasonably prompt period of time after it is disclosed
                                                        to
                                                        the receiving party.
                                                        B. The subject matter of the Confidential Information is to be
                                                        limited to that which is relative to
                                                        the research outlined in the Statement of Work under Article 1
                                                        above.
                                                        C. The receiving party will not publish or otherwise reveal to
                                                        any
                                                        third party the Confidential
                                                        Information (properly designated) of the disclosing party
                                                        without
                                                        the disclosing party's written
                                                        permission, unless the information:
                                                        (1) is already lawfully in the receiving party's possession at
                                                        the
                                                        time of receipt from the
                                                        disclosing party as evidenced by appropriate documentation;
                                                        (2) is or later becomes public through no fault of the receiving
                                                        party;
                                                        (3) is published by the UNIVERSITY and or its employee(s) in
                                                        accordance with the
                                                        provisions of Article 5 above;
                                                        4
                                                        (4) is lawfully received from a third party as evidenced by
                                                        appropriate documentation;
                                                        (5) has been in the possession of the receiving party for five
                                                        (5)
                                                        years or longer;
                                                        (6) is independently developed by the receiving party as
                                                        evidenced
                                                        by appropriate
                                                        documentation; or
                                                        (7) is required by law, including the Tennessee Public Records
                                                        Act,
                                                        T.C.A. 10-7-503 et
                                                        seq., to be disclosed.
                                                        7. INTELLECTUAL PROPERTY.
                                                        7.1 Pre-Existing Intellectual Property Rights of the Parties. No
                                                        party claims by virtue of this
                                                        Agreement any right, title, or interest in (a) any issued or
                                                        pending
                                                        patents or any copyrights owned or
                                                        controlled by another party or (b) any previous invention,
                                                        process,
                                                        or product of another party,
                                                        whether or not patented or patentable.
                                                        7.2 Definition. The term "Intellectual Property" shall mean all
                                                        inventions and developments
                                                        (whether or not patentable) and other creative works (excluding
                                                        theses, dissertations and scholarly
                                                        publications) developed in the course of the performance of the
                                                        work
                                                        under this Agreement,
                                                        including without limitation any patent, trademark, copyright,
                                                        mask
                                                        work right, or other property
                                                        right pertaining to same.
                                                        7.3 Allocation of rights.
                                                        A. Both UNIVERSITY and SPONSOR agree to promptly disclose to the
                                                        other all Intellectual
                                                        Property developed in the course of the work under this
                                                        Agreement.
                                                        B. The Intellectual Property developed solely by the UNIVERSITY
                                                        or
                                                        jointly by the
                                                        UNIVERSITY and SPONSOR in the performance of work under this
                                                        Agreement shall be
                                                        owned by the UNIVERSITY.
                                                        C. UNIVERSITY hereby grants to SPONSOR an exclusive option
                                                        ("Option") to acquire a
                                                        worldwide (to the extent possible) royalty-bearing license to
                                                        use
                                                        the Intellectual Property
                                                        developed in the course of the work under this Agreement in the
                                                        field of
                                                        __________________ (the “Optioned IP”).
                                                        D. The "Option Period" shall commence on the date of disclosure
                                                        to
                                                        SPONSOR of the Optioned
                                                        IP and shall terminate on the earlier of the following: (a) six
                                                        months from the date of
                                                        disclosure; or (b) termination of the Option pursuant to Article
                                                        3.5; or (c) proper exercise of
                                                        the Option by SPONSOR.
                                                        E. SPONSOR may exercise the Option during the Option Period by
                                                        giving written notice of
                                                        same to UNIVERSITY provided that SPONSOR is not then in default
                                                        or
                                                        breach of any of its
                                                        obligations under this Agreement.
                                                        F. Upon proper exercise of the Option by SPONSOR, UNIVERSITY and
                                                        SPONSOR will
                                                        negotiate in good faith in an effort to reach a
                                                        commercialization
                                                        agreement satisfactory to
                                                        5
                                                        both parties, the negotiation period not to exceed six (6)
                                                        months.
                                                        Upon the first to occur of
                                                        (a) termination of the Option by operation of the provisions of
                                                        Article 3.5 above; or (b)
                                                        expiration of the Option Period with the Option unexercised; or
                                                        (c)
                                                        expiration of the sixmonth negotiation period without the
                                                        execution
                                                        of a commercialization agreement,
                                                        UNIVERSITY shall have no further obligation to SPONSOR under
                                                        this
                                                        Agreement with
                                                        regard to the Optioned IP. In the absence of a further agreement
                                                        between UNIVERSITY and
                                                        SPONSOR, SPONSOR agrees that it will not use the Optioned IP for
                                                        any
                                                        commercial or noncommercial purpose.
                                                        G. During the Option Period, UNIVERSITY and SPONSOR will confer
                                                        concerning the proper
                                                        protection of the Optioned IP. Within thirty (30) days after
                                                        receipt
                                                        of an invoice from
                                                        UNIVERSITY, SPONSOR shall reimburse UNIVERSITY for all
                                                        out-of-pocket
                                                        expenses
                                                        incurred by UNIVERSITY during the Option Period in the filing,
                                                        prosecution, and
                                                        maintenance of United States and foreign patent applications,
                                                        issued
                                                        patents, and other forms
                                                        of intellectual property protection for the Optioned IP, all of
                                                        which shall be owned by
                                                        UNIVERSITY.
                                                        H. It is understood and agreed that any rights granted by or to
                                                        any
                                                        party by the terms of this
                                                        Agreement shall in all respects be subject to any rights claimed
                                                        or
                                                        restrictions and obligations
                                                        imposed by the United States government or any agency thereof,
                                                        whether such rights or
                                                        restrictions and obligations arise out of federal funding of the
                                                        underlying research or
                                                        otherwise.
                                                        8. BINDING AGREEMENT.
                                                        8.1 This Agreement shall be binding upon and inure to the
                                                        benefit of
                                                        each of the parties hereto,
                                                        their successors and assigns; provided, however, that this
                                                        Agreement
                                                        is not assignable or transferable,
                                                        in whole or in part, by any party without the prior written
                                                        consent
                                                        of the other parties.
                                                        Notwithstanding the foregoing, UNIVERSITY may assign its rights
                                                        and
                                                        interest to the University of
                                                        Tennessee Research Foundation (“UTRF”) or a successor in
                                                        interest to
                                                        UTRF or the UNIVERSITY
                                                        without the prior written consent of SPONSOR.
                                                        9. LIABILITY/INDEMNIFICATION.
                                                        9.1 The UNIVERSITY makes no warranties, either expressed or
                                                        implied,
                                                        as to the work to be
                                                        performed under this Agreement or the Optioned IP. THE
                                                        UNIVERSITY
                                                        SPECIFICALLY
                                                        DISCLAIMs ANY WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A
                                                        PARTICULAR PURPOSE. The UNIVERSITY shall not be liable for any
                                                        direct, consequential, or
                                                        other damages suffered by SPONSOR or others resulting from the
                                                        work
                                                        performed under this
                                                        Agreement or the Optioned IP.
                                                        9.2 LIMITATION OF LIABILITY ON BEHALF OF THE UNIVERSITY. The
                                                        UNIVERSITY
                                                        is self-insured under the provisions of the Tennessee Claims
                                                        Commission Act (T.C.A. 9-8-301 et
                                                        seq.), and its liability to SPONSOR and to third parties for the
                                                        negligence of the UNIVERSITY and
                                                        its employees is subject to the tort provisions of that Act.
                                                        Accordingly, any liability of the
                                                        UNIVERSITY for any damages, losses, or costs arising out of or
                                                        related to acts performed by the
                                                        6
                                                        UNIVERSITY or its employees under this agreement is governed by
                                                        the
                                                        provisions of said Act.
                                                        Notwithstanding anything in this agreement to the contrary, any
                                                        provisions or provisions of this
                                                        agreement will not apply to the extent that it is (they are)
                                                        finally
                                                        determined to violate the laws or
                                                        Constitution of the State of Tennessee.
                                                        9.3 SPONSOR will indemnify UNIVERSITY and their respective
                                                        trustees,
                                                        directors, officers,
                                                        employees and agents and hold them harmless from every loss,
                                                        cost or
                                                        damage for judgments, awards
                                                        or the compromise of any claim arising out of the use of the
                                                        Optioned IP or the advertisement,
                                                        manufacture, use or sale of any product or process by SPONSOR,
                                                        its
                                                        sublicensees, dealers or
                                                        customers.
                                                        10. EXPORT C
                                                        <label id="label">The Proposal Id is . <?php ?> </label>
                                                        <div class="well well-lg text-danger">Remember if you click the
                                                            agree button it will be considered as you agree the terms
                                                            listed
                                                            above
                                                        </div>
                                                </div>
                                            </div>
                                            <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Agreed&&ProposalID=<?php echo $data['ID'] ?>">Agree</a>
                                            </button>
                                            <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Disagreed&&ProposalID=<?php echo $data['ID'] ?>">Disagree</a>
                                            </button>
                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="8">
                    <center>No Record</center>
                </td>
            </tr>
        <?php
        }
    } //this is for the toolTip Content Called by the Ajax Request
    elseif (isset($_REQUEST["Agreement"])) {
        $proposalId = (int)$_REQUEST['id'];
        $stmt = $conn->prepare("select * from participant where Proposal_ID=?");
        $stmt->bind_param("i", $proposalId);
        if ($stmt->execute()) {
            $data = $stmt->get_result();
        ?>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>
                            <center>#</center>
                        </th>
                        <th>
                            <center>First Name</center>
                        </th>
                        <th>
                            <center>Middle Name</center>
                        </th>
                        <th>
                            <center>Last Name</center>
                        </th>

                        <th>
                            <center>Agreement Status</center>
                        </th>
                    </tr>
                </thead>
                <tbody id="body">
                    <?php
                    if ($data->num_rows > 0) {
                        while ($row = $data->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['Staff_ID'] ?></td>
                                <td><?php echo $row['Staff_ID'] ?></td>
                                <td><?php echo $row['Staff_ID'] ?></td>
                                <td><?php echo $row['Staff_ID'] ?></td>
                                <td><?php echo $row['Agreement'] ?></td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7">
                                <center>No Record</center>
                                >
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
    } elseif (isset($_REQUEST["SelectYear"])) {
        $Year = Date("Y");
        ?>
        <option value=''>Select Start Year</option>
        <?php

        while ($Year >= 2001) {

        ?>
            <option value='<?php echo $Year . '/' . $_REQUEST["SelectYear"] ?>'><?php echo $Year ?></option>
        <?php
            $Year--;
        }
    } elseif (isset($_REQUEST["EndYear"])) {
        $Year = Date("Y");
        $Start_Year = explode("/", $_REQUEST["EndYear"]);
        $start = $Start_Year[0];
        if (intval($start) < $Year) {
        ?>
            <option value=''>Select Start Year</option>
            <?php

            while ($Year >= $start) {

            ?>
                <option value='<?php echo $_REQUEST["EndYear"] .'/'.$start  ?>'><?php echo $start ?></option>
<?php
                $start++;
            }
        } else {
        }
    }
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>