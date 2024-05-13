<?php

class bcProposalViewCount
{
    public static function render()
    {
        // --- Proposal Id From Url ---
        $id = $_GET['bcsingleid'];
        // if(isset($_GET['bcsingleid']))
        // {
            
        // }
        // else{
        //     $baseurl_for_topics_page = get_option('baseurl_for_topics_page');
    
        //     // Redirect the user to the same page after logout
        //     wp_redirect($baseurl_for_topics_page);
        // }
        
        $proposalViewCount = "";
        $proposalBasicAnswerResultYes = "";
        $proposalBasicAnswerResultNo = "";

        // --- Api Function Call ---
        $ApiResult = bcProposalViewCount::ProposalViewCount_API($id);

        // --- Decode JSONS Data Result --- 
        $proposalViewCountJSONS = json_decode($ApiResult, true);
        
        
        
        if(isset($proposalViewCountJSONS['numberOfPeople']))
             {
                $proposalViewCount = strval($proposalViewCountJSONS['numberOfPeople']);
                $proposalBasicAnswerResultYes = ($proposalViewCountJSONS['basicResult']['answers'][0]['result']);
                $proposalBasicAnswerResultNo = ($proposalViewCountJSONS['basicResult']['answers'][1]['result']);
             } 
             else if(isset($proposalViewCountJSONS['message']))
             {
                  $ErrorMessage = $proposalViewCountJSONS['message'];
             }
            
             
             
        

        // --- Views Generation Part ---
        $view = "";
        $view .= '
        <div class="bcProposalDetailContainer">
        <div class="bcNoOfPeopleContainer">
                <svg width="27" height="31" viewBox="0 0 27 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.0785217 28.3671C0.0785217 29.4717 0.973952 30.3671 2.07852 30.3671H24.936C26.0406 30.3671 26.936 29.4717 26.936 28.3671V2.85889C26.936 1.75432 26.0406 0.858887 24.936 0.858887H2.07852C0.973952 0.858887 0.0785217 1.75432 0.0785217 2.85889V28.3671ZM17.9835 10.6952C17.9835 13.4165 15.9841 15.6132 13.5072 15.6132C11.0304 15.6132 9.03096 13.4165 9.03096 10.6952C9.03096 7.97388 11.0304 5.77716 13.5072 5.77716C15.9841 5.77716 17.9835 7.97388 17.9835 10.6952ZM13.5073 18.7281C10.5232 18.7281 4.55482 20.5313 4.55482 23.81C4.55482 24.7154 5.28879 25.4494 6.19417 25.4494H20.8205C21.7258 25.4494 22.4598 24.7154 22.4598 23.81C22.4598 20.5313 16.4915 18.7281 13.5073 18.7281Z" fill="#36BB91"/>
                </svg>
                <p>' . $proposalViewCount . '</p>
        </div>

        <div class="bcVotePercentContainer">
            <svg width="33" height="34" viewBox="0 0 33 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.08494 12.0063L19.7832 0.23584L22.2302 2.89158L20.5591 11.7113H32.9583V18.9244L27.4525 33.0227H9.08494V12.0063ZM6.10078 13.3506H0.132446V33.0227H6.10078V13.3506Z" fill="#36BB91"/>
                </svg>                        
            <p class="bcPercent">' . $proposalBasicAnswerResultYes . '%</p>
            <svg width="34" height="33" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.7962 21.2029L14.0979 32.9734L11.6509 30.3177L13.3221 21.498H0.922852V14.2849L6.42864 0.186523H24.7962V21.2029ZM33.7487 0.186523H27.7803V19.8587H33.7487V0.186523Z" fill="#F94025"/>
                </svg>                                          
            <p>' . $proposalBasicAnswerResultNo . '%</p>
    </div>';
        return $view;
    }

    // --- Proposal By Id Api Function Start ---
    public static function ProposalViewCount_API($proposalId)
    {

        // --- Get Login User Details And Access Token From WP DB ---
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
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
    // --- Proposal By Id Api Function End ---
}
