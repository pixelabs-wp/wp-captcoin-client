<?php

class bcCustomerMakeTransfer
{

    public static function render()
    {

        // --- Get Login User Details And Access Token From WP DB ---
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $customerBalance = 0;
        $userData = get_user_meta($user_ID, 'userData', true);
        $userData = json_decode($userData,true);
        $ethereumAddress = $userData['ethereumAddress'];
        $baseurl_for_Apis = get_option('baseurl_for_Apis');
        if(isset($_GET["message"]))
        {
            $message = $_GET["message"];
        }
       
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
                
                 bcLogout::wp_logout_and_redirect_main();
            }
         else{
             bcLogout::wp_logout_and_redirect_main();
         }
        }
        // --- Views Generated ---
        $view = "";
        $view .= '
        <div class="bcMainTransferContainer" >
            <p class="bcTransferTopText" style="overflow-x: auto;">
                Here you can Transfer your Captain’s Coins to another Captain’s Coin account.
                <br>
                Please make sure all the fields are correct, before Transfer. Check the Lightwallet address, password,
                amount en the Receiver Lightwallet address or otherwise it will be lost.
            <br><br>
                Your Captain’s Coin Lightwallet:  
                <b class="bcTransferWalletAddress">' . $ethereumAddress . '</b>
            </p>
            <form method="POST" action="' . admin_url("admin-post.php") . '"';
        $view .= '>
                        <div>
                        <div class="bcSenderContainer">
                            <input class="bcSender" type="password" name="lightwalletPassword" id="bc-lightwallet-password" placeholder="Your Lightwallet password" required>
                        </div>
                        <span id="bc-lightwallet-password-validity" style="color:red; display:inline-block;"></span>
                        <div class="bcAmountContainer">
                            <input class="bcAmount" type="number" min="0" max="'.$customerBalance.'" name="amount" value="0" placeholder="Amount" required>
                        </div>
                        <div class="bcRecieverContainer">
                            <input class="bcReciever" type="text" name="to"  placeholder="Receiver Captain’s Coin Lightwallet" required>
                        </div>
                        ';
                        if(isset($_GET['message']))
                        {
                            $view .= '<span id="bc-api-Errors" style="color:red;">'.$message.'</span>';
                        }
                        $view .= '
                        <hr class="bchr">
                        <div class="bcTransferButtonContainer">
                                <input class="bcTransferButton" name="" type="submit" id="submit" value="Transfer">
                        </div>
                        </div>
                        <input type="hidden" name="url" value="" id="url">
                        <input type="hidden" name="action" value="CCtransferAction" />
                    </form>
                    </div>

                    <script>
    var url = window.location.href.split(' . "'?'" . ')[0];
    document.getElementById("url").value = url;
</script>
                    ';
        return $view;
    }


    public static function TransferToken_API()
    {
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $user_ID = get_current_user_id();
        
        $userData = get_user_meta($user_ID, 'userData', true);
        $userData = json_decode($userData,true);
        $ethereumAddress = $userData['ethereumAddress'];
        
        $lightwalletPassword = $_POST['lightwalletPassword'];
        $from =  $ethereumAddress;
        $to = $_POST['to'];
        $amount = $_POST['amount'];
        $refferer = $_POST['url'];
        $feeless = true;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $baseurl_for_Apis = get_option('baseurl_for_Apis');
        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $baseurl_for_Apis . 'tcc/transfers',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "feeless": '. $feeless. ',
          "lightwalletPassword": "' . $lightwalletPassword . '",
          "from": "' . $from . '",
          "to": "' . $to . '",
          "amount": ' . $amount . '
        }',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = json_decode($response, true);
        curl_close($curl);

        // -- Return Actions --
        if ($httpcode != 204) {
            // --- Redirect With Error Response---
            wp_redirect($refferer . "?message=" . $response['message'] . "&httpcode=$httpcode");
            exit;
        } else {
            // --- Redirect With Success Response---
            wp_redirect($refferer . "?status=success&httpcode=$httpcode");
            exit;
        }
    }
}
