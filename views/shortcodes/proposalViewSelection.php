<?php

class bcProposalViewSelection
{
    public static function render()
    {
        $id = $_GET['bcsingleid'];

        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
		
// 		if (!is_user_logged_in()) {
//             $accessToken = $_SESSION["AT"];
//         } else {
//             $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
//         }
		
        $baseurl_for_Apis = get_option('baseurl_for_Apis');
        $canVote = 0;
        $answerId = "";
        if ($accessToken) {
            $ApiResult = bcProposalViewSelection::getVoteAccess($id);
            $voteAccessJSONS = json_decode($ApiResult, true);
            $canVote = $voteAccessJSONS['canVote'];
            if (!empty($voteAccessJSONS['customerVotes'])) {
                foreach ($voteAccessJSONS['customerVotes'] as  $value) {
                    $answerId = $value['answerId'];
                    
                }
                $canVote = 0;
            }
        }


        // --- Api FunctionCall ---
        $ApiResult = bcProposalViewSelection::ProposalViewSelection_API($id);

        // // --- Decode JSONS Data ---
        $proposalViewSelectionJSONS = json_decode($ApiResult, true);
         $proposalFinished = $proposalViewSelectionJSONS['finished'];
        $proposalEndTimestamp = strval($proposalViewSelectionJSONS['endTimestamp']);
        $time = time();
        $compare = strtotime($proposalEndTimestamp);
        if ($compare > $time) {
            $seconds = $compare - $time;
            $days = floor($seconds / 86400);
            $seconds %= 86400;
            $hours = floor($seconds / 3600);
            $seconds %= 3600;
            $minutes = floor($seconds / 60);
            $seconds %= 60;
        } else {
            $days = 0;
            $hours = 0;
            $seconds = 0;
        }
        $value = "'1'";
        $value2 = "'2'";
        // --- Views Generated ---
        $view = "";
        $view .= '
        <div class="bcVoteContainer">';
        if ($answerId) {
            if ($answerId == 1) {
                $view .= '
                <div class="bcYesContainerResult" >
                    <span class="bcYesVote">Yes</span>
                    <span class="bcYesVoteDescription">You voted Yes!</span>
                </div>
                <div class="bcNoContainer">
                    <p>No</p>
                </div>';
            } else {
                $view .= '
                <div class="bcYesContainer" >
                <p>Yes</p>
                </div>
                <div class="bcNoContainerResult">
                    <span class="bcNoVote">No</span>
                    <span class="bcNoVoteDescription">You voted No!</span>
                </div>';
            }
        } else {

            $view .= ' <div class="bcYesContainer" >';
            if ($canVote == 1) {
                $view .= '<p onclick="openModal(' . $value . ')">Yes</p>';
            } else {
                $view .= '<p>Yes</p>';
            }
            $view .= '
                </div>
                <div class="bcNoContainer">';

            if ($canVote == 1) {
                $view .= '<p onclick="openModal(' . $value2 . ')">No</p>';
            } else {
                $view .= '<p>No</p>';
            }
            $view .= '
                </div>';
        }

        $view .= '      
                <div class="bcTimeContainer">
                    ';
        if ($proposalFinished == 1) {
            $view .= '<span class="bcfinished">Finished</span>';
        } else {
            $view .= '<div class="bcdays">' . $days . '
                        <span>days</span>
                    </div>
                    <div class="bchours">' . $hours . '
                        <span>hrs</span>
                    </div>
                    <div class="bcseconds">' . $seconds . '
                        <span>secs</span>
                    </div>';
        }

        $view .= '
                </div>
            </div>';
        if ($canVote == 1) {
            $view .= '
            <!-- Modal -->
            <div class="bcModal" id="bcMyModal">
                <div class="bcModal-header">
                    <button class="bcClose-button" style="margin-right:5px; margin-top:5px; color: #04091E;" onclick="bcCloseModal()">&times;</button>
                </div>
                <div class="bcModal-header1">
                    <label>Confirm password</label>
                </div>
                <div class="bcModal-content">
                    <form method="POST" id="bcPasswordFormId" action="' . admin_url("admin-post.php") . '"';
            $view .= '>
                    <hr class="bcConfirm-hr">
                        <label >Please enter your password to confirm your action:</label>
                        <input type="password" name="lightwalletPassword" id="bc-lightwallet-password" placeholder="Lightwallet password*" required>
                        <span id="bc-lightwallet-password-validity" style="color:red; display:inline-block; margin-left: 30px; margin-right: 30px; "></span>
                        <hr class="bcConfirm-hr">
                        <input type="hidden" name="url" value="" id="url">
                        <input type="hidden" name="proposalId" value="' . $id . '" id="bcProposalId">
                        <input type="hidden" name="answerId" value="" id="bcAnswerId">
                        <input type="hidden" name="action" value="CCVoteAction" />
                        <button type="submit" id="bcConfirm-Button">Confirm</button>
                    </form>
                </div>
            </div>

            <!-- Overlay -->
            <div class="bcOverlay" id="bcOverlayId"></div>

            <script>
                var url = window.location.href.split(' . "'?'" . ')[0];
                url = url+"?bcsingleid="+' . $id . ';
                document.getElementById("url").value = url;
            </script>

            <!-- Modal Script -->
            <script>
                function openModal(value) {
                    // Get modal and overlay elements
                    var modal = document.getElementById("bcMyModal");
                    var overlay = document.getElementById("bcOverlayId");
                    var answer = document.getElementById("bcAnswerId");
                    // Show modal and overlay
                    modal.style.display = "block";
                    overlay.style.display = "block";
                    answer.value = value;
                
                    // Disable background scroll
                    document.body.style.overflow = "hidden";
                
                    
                }
                
                function bcCloseModal() {
                    // Get modal and overlay elements
                    var modal = document.getElementById("bcMyModal");
                    var overlay = document.getElementById("bcOverlayId");
                
                    // Hide modal and overlay
                    modal.style.display = "none";
                    overlay.style.display = "none";
                
                    // Enable background scroll
                    document.body.style.overflow = "auto";
                }
            </script>
            ';
        }
        return $view;
    }

    public static function ProposalViewSelection_API($proposalId)
    {
        $user = wp_get_current_user();
        $user_ID = $user->ID;
//         $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $baseurl_for_Apis = get_option('baseurl_for_Apis');

		
		if (!is_user_logged_in()) {
            $accessToken = $_SESSION["AT"];
        } else {
            $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        }
        // --- Api Call  ---
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/' . $proposalId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $accessToken
            ),
        ));

        // --- Api Response  ---
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function vote_API()
    {
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $user_ID = get_current_user_id();
        
        $userData = get_user_meta($user_ID, 'userData', true);
        $userData = json_decode($userData,true);
        $ethereumAddress = $userData['ethereumAddress'];

        // --- Form Post Data Start ---
        $lightwalletPassword = $_POST['lightwalletPassword'];
        $proposalId = $_POST['proposalId'];
        $answerId = $_POST['answerId'];
        $from = $ethereumAddress;
        $feeless = true;
        $refferer = $_POST['url'];
        
        // --- Form Post Data End ---
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $baseurl_for_Apis = get_option('baseurl_for_Apis');
        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/vote',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "feeless": ' . $feeless . ',
          "lightwalletPassword": "' . $lightwalletPassword . '",
          "proposalId": ' . $proposalId . ',
          "answerId": ' . $answerId . ',
          "from": "' . $from . '"
        }',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
          ),
        ));




        // -- Curl operation (POST Request) Start --
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/vote',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{
        //     "feeless": ' . $feeless . ',
        //     "lightwalletPassword": "' . $lightwalletPassword . '",
        //     "proposalId": ' . $proposalId . ',
        //     "answerId": ' . $answerId . ',
        //     "from": "' . $from . '"
        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Authorization: Bearer ' . $accessToken,
        //         'Content-Type: application/json'
        //     ),
        // ));

        // -- Curl operation (POST Request) Ends --

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $response = json_decode($response, true);
        curl_close($curl);

        // --- Data Array To Store In Wordpress ---
        $voteData = '{
        "feeless": ' . $feeless . ',
        "proposalId": ' . $proposalId . ',
        "answerId": ' . $answerId . ',
        "from": "' . $from . '"
        }';


        // --- Return Actions ---
        if ($httpcode != 204) {
            // --- Redirect With Error Response---
            wp_redirect($refferer . "?error=" . $response['message'] . "&httpcode=$httpcode");
            exit;
        } else {

            if ($httpcode == 204) {
                // --- Store Voting Data Array In Wordpress Metadata---
                add_user_meta($user_ID, 'proposal_' . $proposalId, $voteData);
            }
            // --- Redirect With Success Response---
            wp_redirect($refferer . "&&status=success&&httpcode=$httpcode");
            exit;
        }
    }




    public static function getVoteAccess($proposalId)
    {
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);

        $baseurl_for_Apis = get_option('baseurl_for_Apis');

        // --- Api Call  ---
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/votes/' . $proposalId . '/' . $cognitoId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $accessToken
            ),
        ));


        // --- Api Response  ---
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
