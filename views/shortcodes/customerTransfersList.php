<?php

class bcCustomerTransfersList
{

    public static function render($atts)
    {
        // --- Parameters From Shortcode ---
        $atts = shortcode_atts(array(
            'limit' => 10,
            'offset' => 0,
        ), $atts, 'customerTransfersList');

        $limit = $atts['limit'];
        $offset = $atts['offset'];
        
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $view = "";
        
        if($accessToken)
        {
            // --- Api FunctionCall ---
            $ApiResult = bcCustomerTransfersList::customerTransfersList_API($limit, $offset);
    
            // --- Decode JSONS Data ---
            $customerTransfersListJSONS = json_decode($ApiResult, true);
            // --- Views Generated ---
            
            
            $view .= '
                <div class = "bcTransferListContainer">
                    <table class="bcTransferListTable" style="border:none">
                        <thead style="border-bottom: 1px solid #CDD6D7;">
                            <tr class="bcTransferListTableHead" style="border:none">
                                <th >Transfer From</th>
                                <th >Transfer To</th>
                                <th class="bcBlur">Amount</th>
                                <th class="bcBlur">Date</th>
                            </tr>
                        </thead>
                    <tbody >';
            // --- Looping Over Data ---
            if (empty($customerTransfersListJSONS["list"])) {
            }
            else{
                foreach ($customerTransfersListJSONS["list"] as  $value) {
                $from = $value['from'];
                $to = $value['to'];
                $amount = $value['amount'];
                $timestamp = strtotime($value['timestamp']);
                $timestamp = date("d/m/y", $timestamp);
                $transactionHash = $value['transactionHash'];
                $confirmed = $value['confirmed'];
                if ($confirmed == true) {
                    $payment_status = "Payed";
                } else {
                    $payment_status = "Pending";
                }
                $view .= ' <tr>
                                <td>' . $from . '</td>
                                <td class="bcBlur">' . $to . '</td>
                                <td class="bcBlur">' . $amount . '</td>
                                <td class="bcBlur">' . $timestamp . '</td>
                            </tr>';
            }
            }
            
            $view .= '</tbody>
                    </table>
                    </div>';
        }
        else{
            
        }

        

        return $view;
    }

    // --- Transfer List Api Function Start ---
    public static function customerTransfersList_API($limit, $offset)
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
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/transfers/' . $cognitoId . '?limit=' . $limit . '&offset=' . $offset,
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
    // --- Transfer List Api Function End ---
}
