<?php

/**
 * Plugin Name:       Captcoin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle Votes, Stats & Transactions of Byelex Coin.
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Author:            Syed Ali Haider
 * Author URI:        https://www.fiverr.com/users/syedali157
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       byelex-coin
 * Domain Path:       
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

define('BYELEX_COIN_VERSION', '1.0.0');


remove_filter('template_redirect', 'redirect_canonical');


require plugin_dir_path(__FILE__) . 'includes/coin_main.php';
// require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalList.php';
// require plugin_dir_path(__FILE__) . 'views/shortcodes/singleProposal.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerBalance.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerVotes.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerVoteList.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerTransfers.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerTransfersList.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerMakeTransfer.php';

require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewHeading.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewSelection.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewCount.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewDescription.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewLoginNotice.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewVotesChart.php';

require plugin_dir_path(__FILE__) . 'views/shortcodes/proposalViewList.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/registerUser.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/loginUser.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/customerDetails.php';
require plugin_dir_path(__FILE__) . 'views/shortcodes/logout.php';

//Cognito Login Start
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
include_once(PLUGIN_PATH . 'settings.php');
// --- Include Utilities ---
include_once(PLUGIN_PATH . 'includes/utils/generate-strings.php');
// --- Include Units ---
include_once(PLUGIN_PATH . 'includes/units/auth.php');
include_once(PLUGIN_PATH . 'includes/units/programmatic-login.php');
include_once(PLUGIN_PATH . 'includes/units/user.php');


/**
 * General initialization function container
 */
class Cognito_Login
{
  /**
   * The default shortcode returns an "a" tag, or a logout link, depending on if the user is
   * logged in
   */
  public static function shortcode_default($atts)
  {
    global $wpdb;
    $atts = shortcode_atts(array(
      'text' => NULL,
      'class' => NULL
    ), $atts);
    $user = wp_get_current_user();

    if ($user->{'ID'} !== 0) {
      return Cognito_Login_Generate_Strings::already_logged_in($user->{'user_login'});
    }

    return Cognito_Login_Generate_Strings::a_tag($atts);
  }

  /**
   * Handler for the "parse_query" action. This is the "main" function that listens for the
   * correct query variable that will trigger a login attempt
   */
  public static function parse_query_handler()
  {

    // Remove this function from the action queue - it should only run once
    remove_action('parse_query', array('Cognito_Login', 'parse_query_handler'));

    // Try to get a code from the url query and abort if we don't find one, or the user is already logged in
    $code = Cognito_Login_Auth::get_code();
	
    $state = Cognito_Login_Auth::get_state();
    if ($code === FALSE) return;
    if (is_user_logged_in()) return;

    // Attempt to exchange the code for a token, abort if we weren't able to

   
     $token = Cognito_Login_Auth::get_token($code);
     if ($token === FALSE) return;

     $AT = $token['access_token'];
    

    
    // Parse the token
    $parsed_token = Cognito_Login_Auth::parse_jwt($token['id_token']);
    
    $_SESSION["id_token"] = json_encode($parsed_token);
    

    // Determine user existence
    if (!in_array(get_option('username_attribute'), $parsed_token)) return;
  
    
    $username = $parsed_token[get_option('username_attribute')];
    
    
    $user = get_user_by('login', $username);

    if ($user === FALSE) {
      // Also check for a user that only matches the first part of the email
      $non_email_username = substr($username, 0, strpos($username, '@'));
      $user = get_user_by('login', $non_email_username);

      if ($user !== FALSE) $username = $non_email_username;
    }

    if ($user === FALSE) {
      // Create a new user only if the setting is turned on
      if (get_option('create_new_user') !== 'true') return;

      // Create a new user and abort on failure
      $user = Cognito_Login_User::create_user($parsed_token);
      if ($user === FALSE) return;
    }
    


    // Log the user in! Exit if the login fails
    // if (Cognito_Login_Programmatic_Login::login($username) === FALSE) return;
    

    // Get the user by their username
    $user = get_user_by('login', $username);

    // Check if the user exists
    if (!$user) {
        return;
    }

    // Try to authenticate the user with the given password
    $auth = wp_authenticate($username, "1234!@#$");

    // Check if the authentication was successful
    // if (is_wp_error($auth)) {
    //     echo $auth->get_error_message();
    // }

    // Log in the user
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);
   

    // Return true on success
    

    $user_ID = get_current_user_id();

    echo $user_ID;

    if (metadata_exists('user', $user_ID, 'captCoinAT')) {
      update_user_meta($user_ID, 'captCoinAT', $AT);
    } else {
      add_user_meta($user_ID, 'captCoinAT', $AT);
    }

    if (metadata_exists('user', $user_ID, 'CC-cognitoId')) {
    } else {
      add_user_meta($user_ID, 'CC-cognitoId', $code, true);
    }
update_user_meta($user_ID, 'refreshToken', $token['refresh_token']);
update_user_meta($user_ID, 'idToken', $token['id_token']);

    // Redirect the user to the "homepage", if it is set (this will hide all `print` statements)
    $homepage = get_option('homepage');
    if (!empty($state)) {
    
      Cognito_Login_Auth::redirect_to(trim(urldecode($state)));
    }
  }
  
  

  /**
   * Will disable the default WordPress login experience, replacing the login interface with
   * a link to the Cognito login page. Will only activate if the disable_wp_login setting
   * is set to `true`
   * 
   * This method should be added to the `login_head` action
   */
  public static function disable_wp_login()
  {
    if (get_option('disable_wp_login') !== 'true') return;

    wp_enqueue_style('cognito-login-wp-login', plugin_dir_url(__FILE__) . 'public/css/cognito-login-wp-login.css');

    $loginLink = Cognito_Login_Generate_Strings::a_tag(array(
      'text' => NULL,
      'class' => NULL
    ));
?>
    <script>
      window.addEventListener('load', function() {
        // Get the form
        var loginForm = document.querySelector('body.login div#login form#loginform');

        // Fully disable the form
        loginForm.action = '/';

        // Modify the inner HTML, adding the login link and removing everything else
        loginForm.innerHTML = '<?php echo $loginLink ?>';

        // Also get rid of the nav, password resets are not handled by WordPress
        var nav = document.querySelector('#nav');
        nav.parentNode.removeChild(nav);
      });
    </script>
<?php
  }
}

// --- Add Shortcodes ---
add_shortcode('cognito_login', array('Cognito_Login', 'shortcode_default'));
// --- Add Actions ---
add_action('parse_query', array('Cognito_Login', 'parse_query_handler'));
add_action('login_head', array('Cognito_Login', 'disable_wp_login'));




//Cognito Login End
define('bcPluginPath', plugin_dir_path(__FILE__));
define('bcPluginUrl', plugin_dir_url(__FILE__));






function captcoin_initialize()
{
    if (!is_user_logged_in()) {
       $_SESSION["AT"] = getAccessToken();
    }
    
    bcCore::run();
}



add_action('init', 'captcoin_initialize');



function getAccessToken(){
$ch = curl_init();

$machine_app_auth_url = get_option('machine_app_auth_url');
$machine_app_client_id = get_option('machine_app_client_id');
$machine_app_client_secret = get_option('machine_app_client_secret');
$machine_app_grant_type = get_option('machine_app_grant_type');
$machine_app_scope = get_option('machine_app_scope');

$value = $machine_app_client_id.':'.$machine_app_client_secret;




curl_setopt($ch, CURLOPT_URL, $machine_app_auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'grant_type' => $machine_app_grant_type,
    'client_id' => $machine_app_client_id,
    'scope' => $machine_app_scope,
)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic ' . base64_encode($value),
    'Content-Type: application/x-www-form-urlencoded',
));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response);

return $data->access_token;
}
