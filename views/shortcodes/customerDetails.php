<?php

class bcCustomerDetails
{
    public static function render()
    {
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        
        $userData = get_user_meta($user_ID, 'userData', true);
        $userData = json_decode($userData,true);
        
        // --- Views Generated ---
        $view = "";
        
        if($userData)
        {
            $firstName = $userData['firstName'];
            $lastName = $userData['lastName'];
            $view .= $firstName. " " .$lastName;
        }
        
        
        return $view;
    }

    
}
