<?php
class bcRegisterUser
{
  public static function render($atts)
  {
    $atts = shortcode_atts(array(
      'proposalId' => NULL
    ), $atts, 'singleProposal');
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $id_token = get_user_meta($user_ID, 'idToken', true);
        $parsedToken = Cognito_Login_Auth::parse_jwt($id_token);
        $givenName = $parsedToken["given_name"];
        $familyName = $parsedToken["family_name"];
        $email = $parsedToken["email"];
    return '
		 <style>
        body::after {
          content: "";
          position: fixed;
          top: 0;
		  z-index:1;
		  width: -webkit-fill-available;
          background: #0000005e;
          height: -webkit-fill-available;
      } 
        </style>
       
		<section class="registration-popup">
        <div class="register-form">
            <div class="cross-icon">
                <img src="/CaptainsCoin-imgs/x-icon.svg" alt="">
            </div>
            <div class="register-heading">
                <p>Register</p>
            </div>
            <hr class="body-hr">
            <div class="register-content">
                <p>Here you can Register for  Captain’s Coin.<br>
                    After that you will be taken to the next step to buy Captain’s Coin and start voting!</p>
            </div>
            <div class="reg-form">
                <form action="' . admin_url('admin-post.php') . '" method="post">
                    <div class="fname-lname flex">
                        <label for="fname"></label>
                        <input type="text" name="firstName" id="fname" placeholder="First Name*" value="'.$givenName.'" readonly>
                        <label for="lname"></label>
                        <input type="text" name="lastName" id="lname" placeholder="Last Name*" value="'.$familyName.'" readonly>
                    </div>
                    <div class="email-lang-password">
                        <label for="email"></label>
                        <input type="email" name="email" id="email" placeholder="E-mail address*" value="'.$email.'" readonly>
                        <select name="language" id="language" required>
                         
                          <option value="AF">Afrikaans</option>
                          <option value="SQ">Albanian</option>
                          <option value="AR">Arabic</option>
                          <option value="HY">Armenian</option>
                          <option value="EU">Basque</option>
                          <option value="BN">Bengali</option>
                          <option value="BG">Bulgarian</option>
                          <option value="CA">Catalan</option>
                          <option value="KM">Cambodian</option>
                          <option value="ZH">Chinese (Mandarin)</option>
                          <option value="HR">Croatian</option>
                          <option value="CS">Czech</option>
                          <option value="DA">Danish</option>
                          <option value="NL">Dutch</option>
                          <option value="EN">English</option>
                          <option value="ET">Estonian</option>
                          <option value="FJ">Fiji</option>
                          <option value="FI">Finnish</option>
                          <option value="FR">French</option>
                          <option value="KA">Georgian</option>
                          <option value="DE">German</option>
                          <option value="EL">Greek</option>
                          <option value="GU">Gujarati</option>
                          <option value="HE">Hebrew</option>
                          <option value="HI">Hindi</option>
                          <option value="HU">Hungarian</option>
                          <option value="IS">Icelandic</option>
                          <option value="ID">Indonesian</option>
                          <option value="GA">Irish</option>
                          <option value="IT">Italian</option>
                          <option value="JA">Japanese</option>
                          <option value="JW">Javanese</option>
                          <option value="KO">Korean</option>
                          <option value="LA">Latin</option>
                          <option value="LV">Latvian</option>
                          <option value="LT">Lithuanian</option>
                          <option value="MK">Macedonian</option>
                          <option value="MS">Malay</option>
                          <option value="ML">Malayalam</option>
                          <option value="MT">Maltese</option>
                          <option value="MI">Maori</option>
                          <option value="MR">Marathi</option>
                          <option value="MN">Mongolian</option>
                          <option value="NE">Nepali</option>
                          <option value="NO">Norwegian</option>
                          <option value="FA">Persian</option>
                          <option value="PL">Polish</option>
                          <option value="PT">Portuguese</option>
                          <option value="PA">Punjabi</option>
                          <option value="QU">Quechua</option>
                          <option value="RO">Romanian</option>
                          <option value="RU">Russian</option>
                          <option value="SM">Samoan</option>
                          <option value="SR">Serbian</option>
                          <option value="SK">Slovak</option>
                          <option value="SL">Slovenian</option>
                          <option value="ES">Spanish</option>
                          <option value="SW">Swahili</option>
                          <option value="SV">Swedish </option>
                          <option value="TA">Tamil</option>
                          <option value="TT">Tatar</option>
                          <option value="TE">Telugu</option>
                          <option value="TH">Thai</option>
                          <option value="BO">Tibetan</option>
                          <option value="TO">Tonga</option>
                          <option value="TR">Turkish</option>
                          <option value="UK">Ukrainian</option>
                          <option value="UR">Urdu</option>
                          <option value="UZ">Uzbek</option>
                          <option value="VI">Vietnamese</option>
                          <option value="CY">Welsh</option>
                          <option value="XH">Xhosa</option>
                        </select>
                        <label for="password"></label>
                        <input type="password" name="lightwalletPassword" id="bc-lightwallet-password" placeholder="Lightwallet password*" required>
                        <span id="bc-lightwallet-password-validity" style="color:red;"></span>
                        <input type="hidden" name="url" value="'.$actual_link.'" id="url">
                        <input type="hidden" name="action" value="CCregisterAction" />
                    </div>
                    <hr class="body-hr">
                    <div class="signup-btn">
                    <input type="submit" value="Sign up">
                </div>
                </form>          
            </div>
           
        </div>
    </section>  
    


';
  }


  public static function registerUser_API()
  {


    $user = wp_get_current_user();
    $user_ID = $user->ID;
    $fName = $_POST['firstName'];
    $lName = $_POST['lastName'];
    $email = $_POST['email'];
    $lang = $_POST['language'];
    $lightWalletPassword = $_POST['lightwalletPassword'];
    $refferer = $_POST['url'];
    $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
    $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
    $baseurl_for_Apis = get_option('baseurl_for_Apis');

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $baseurl_for_Apis . 'tcc/customers',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
  "tccCustomerId": "' . $cognitoId . '",
  "cognitoCustomerId": "' . $cognitoId . '",
  "firstName": "' . $fName . '",
  "lastName": "' . $lName . '",
  "email": "' . $email . '",
  "language": "' . $lang . '",
  "lightwalletPassword": "' . $lightWalletPassword . '",
  "externalId": "' . $cognitoId . '"
  }',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    $response = json_decode($response, true);
    curl_close($curl);

    if ($httpcode != 200) {
      wp_redirect($refferer . "?error=" . $response['message'] . "&httpcode=$httpcode&at=$accessToken");
      exit;
    } else {
        
        $userData = '{
  "tccCustomerId": "' . $cognitoId . '",
  "cognitoCustomerId": "' . $cognitoId . '",
  "firstName": "' . $fName . '",
  "lastName": "' . $lName . '",
  "email": "' . $email . '",
  "language": "' . $lang . '",
  "externalId": "' . $cognitoId . '",
  "ethereumAddress": "' . $response['ethereumAddress'].'",
  "recoveryDocument": "' . $response['recoveryDocument'].'"
  }';


      add_user_meta($user_ID, 'userData', $userData);
      if (strpos($refferer,'?') == true) {
    wp_redirect($refferer . "&status=success&httpcode=$httpcode");
      exit;
} else {
    wp_redirect($refferer . "?status=success&httpcode=$httpcode");
      exit;
}
      
    }
  }
}
