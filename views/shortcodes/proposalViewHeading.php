<?php

class bcProposalViewHeading
{
    public static function render($atts)
    {

        // --- Proposal Id From Url ---
        $id = $_GET['bcsingleid'];
        $proposalHeading = "";

        // --- Api FunctionCall ---
        $ApiResult = bcProposalViewHeading::ProposalViewHeading_API($id);

        // --- Decode JSONS Data ---
        $proposalViewHeadingJSONS = json_decode($ApiResult, true);
        if(isset($proposalViewHeadingJSONS['text']))
        {
            $proposalHeading = strval($proposalViewHeadingJSONS['text']);
        }
        else if(isset($proposalViewHeadingJSONS['message']))
        {
             $ErrorMessage = $proposalViewHeadingJSONS['message'];
        }

        // --- Views Generation Part ---
        $view = "";
        $view .= $proposalHeading;
        return $view;
    }

    // --- Proposal By Id Api Function Start ---
    public static function ProposalViewHeading_API($proposalId)
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
