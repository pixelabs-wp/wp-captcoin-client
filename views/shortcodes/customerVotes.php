<?php

class bcCustomerVotes
{

    public static function render()
    {
        $customerVotes = "";
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        if($accessToken)
        {
            
            // --- Api FunctionCall ---
            $ApiResult = bcCustomerVotes::customerVotes_API();
    
            // --- Decode JSONS Data ---
            $customerVotesjsons = json_decode($ApiResult, true);
        
            if(isset($customerVotesjsons['size']))
             {
                $customerVotes = $customerVotesjsons['size'];
             } 
             else if(isset($customerVotesjsons['message']))
             {
                  $ErrorMessage = $customerVotesjsons['message'];
             }
             else if($customerVotesjsons == null){
                
                 bcLogout::wp_logout_and_redirect();
            }
             else{
                 bcLogout::wp_logout_and_redirect();
             }
        }
        else{
            $customerVotes = "";
        }
        
        // --- Views Generated ---
        $view = "";
        $view .= $customerVotes;

        return $view;
    }

    // --- Votes Api Function Start ---
    public static function customerVotes_API()
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
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/votes/' . $cognitoId . '/size',
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
    // --- Votes Api Function End ---
}
