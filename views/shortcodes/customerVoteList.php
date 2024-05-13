<?php

class bcCustomerVoteList
{

    public static function render()
    {
        
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $view = "";
        
        

            // --- Api FunctionCall ---
            $ApiResult = bcCustomerVoteList::customerVoteList_API();
    
            // --- Decode JSONS Data ---
            $customerVoteListJSONS = json_decode($ApiResult, true);

            // --- Views Generated ---
            
            $view .= '
                <div class = "bcTransferListContainer">
                    <table class="bcTransferListTable" style="border:none">
                        <thead style="border-bottom: 1px solid #CDD6D7;">
                            <tr class="bcTransferListTableHead" style="border:none">
                                <th >Topics</th>
                                <th >Voted</th>
                                <th class="bcBlur">Date</th>
                            </tr>
                        </thead>
                    <tbody >';
                    
        if($accessToken)
        {
            
            // --- Looping Over Data ---
            if (empty($customerVoteListJSONS["list"])) {
            }
            else{
                if ($customerVoteListJSONS["list"] != null) {
        
                    foreach ($customerVoteListJSONS["list"] as  $value) {
                        if ($value["customerVotes"] != null) {
                            $answerId = $value["customerVotes"][0]['answerId'];
                            $proposalId = $value["customerVotes"][0]['proposalId'];
                            $from = $value["customerVotes"][0]['from'];
                            $tccCustomerId = $value["customerVotes"][0]['tccCustomerId'];
                            $votedTimestamp = $value["customerVotes"][0]['votedTimestamp'];
        
                            // --- Api FunctionCall ---
                            $ApiResult2 = bcCustomerVoteList::ProposalData_API($proposalId);
        
                            // --- Decode JSONS Data ---
                            $proposalDataJSONS = json_decode($ApiResult2, true);
                            $proposaltext = strval($proposalDataJSONS['text']);
        
                            // --- Basic Result 1st Answer Detail ---
                            $answerId_1 = $proposalDataJSONS['basicResult']['answers'][0]['id'];
                            $answer_1 = $proposalDataJSONS['basicResult']['answers'][0]['answer'];
                            $result_1 = $proposalDataJSONS['basicResult']['answers'][0]['result'];
        
                            // --- Basic Result 2nd Answer Detail ---
                            $answerId_2 = $proposalDataJSONS['basicResult']['answers'][1]['id'];
                            $answer_2 = $proposalDataJSONS['basicResult']['answers'][1]['answer'];
                            $result_2 = $proposalDataJSONS['basicResult']['answers'][1]['result'];
        
                            $timestamp = strtotime($votedTimestamp);
                            $timestamp = date("d/m/y", $timestamp);
        
                            $view .= ' <tr>
                        <td>' . $proposaltext . '</td>';
                            if ($answerId == $answerId_1) {
                                $view .= ' <td class="bcTransferAnswerYes">' . $answer_1 . '</td>';
                            } else if ($answerId == $answerId_2) {
                                $view .= ' <td class="bcTransferAnswerNo">' . $answer_2 . '</td>';
                            }
                            $view .= '  <td class="bcBlur">' . $timestamp . '</td>';
                            $view .= '  </tr>';
                        }
                    }
                }
            }
        }
    
            $view .= '</tbody>
                    </table>
                    </div>';

        return $view;
    }

    // --- Vote List Api Function Start ---
    public static function customerVoteList_API()
    {
        // --- Get Login User Details And Access Token From WP DB ---
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $baseurl_for_Apis = get_option('baseurl_for_Apis');

        // --- Api Call  ---
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/votes/search/' . $cognitoId,
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

    // --- Vote List Api Function End ---

    // --- Proposal By Id Api Function Start ---
    public static function ProposalData_API($proposalId)
    {

        // --- Get Login User Details And Access Token From WP DB ---
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $baseurl_for_Apis = get_option('baseurl_for_Apis');

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
    // --- Proposal By Id Api Function End ---
}
