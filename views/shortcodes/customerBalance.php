<?php

class bcCustomerBalance
{
    public static function render()
    {
        $user = wp_get_current_user();
        $user_ID = $user->ID;
         $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $customerBalance = "";
        
        if($accessToken)
        {
            // --- Api Function Call ---
        $ApiResult = bcCustomerBalance::customerBalance_API();

        // --- Decode JSONS Data Result --- 
        $customerBalanceJSONS = json_decode($ApiResult, true);

         if(isset($customerBalanceJSONS['balance']))
         {
             $customerBalance = $customerBalanceJSONS['balance'];
         }
         else if(isset($customerBalanceJSONS['message']))
         {
              $ErrorMessage = $customerBalanceJSONS['message'];
         }
         else if($customerBalanceJSONS == null){
                
                 bcLogout::wp_logout_and_redirect();
            }
         else{
             bcLogout::wp_logout_and_redirect();
         }
         
        }
        else{
            $customerBalance = "";
        }
        
        

        // --- Views Generated ---
        $view = "";
        $view .= $customerBalance;
        return $view;
    }

    // --- Balance Api Function Start ---
    public static function customerBalance_API()
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
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/customers/' . $cognitoId . '/balance',
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
    // --- Balance Api Function End ---
    
    
}
