<?php

class bcCore
{
  //Add Hooks, filters, actions  
  public static function run()
  {
    bcCore::registerShortcodes();
    bcCore::intializeScripts();
    bcCore::adminPages();
    bcCore::registerAJAX();
  }

  public static function registerShortcodes()
  {
    // add_shortcode('proposalList', 'bcProposalList::render');
    add_shortcode('singleProposal', 'bcSingleProposal::render');
    add_shortcode('customerBalance', 'bcCustomerBalance::render');
    add_shortcode('customerVotes', 'bcCustomerVotes::render');
    add_shortcode('customerVoteList', 'bcCustomerVoteList::render');
    add_shortcode('customerTransfers', 'bcCustomerTransfers::render');
    add_shortcode('customerTransfersList', 'bcCustomerTransfersList::render');
    add_shortcode('customerMakeTransfer', 'bcCustomerMakeTransfer::render');
    add_shortcode('proposalViewHeading', 'bcProposalViewHeading::render');
    add_shortcode('proposalViewSelection', 'bcProposalViewSelection::render');
    add_shortcode('proposalViewCount', 'bcProposalViewCount::render');
    add_shortcode('proposalViewDescription', 'bcProposalViewDescription::render');
    add_shortcode('proposalViewLoginNotice', 'bcProposalViewLoginNotice::render');
    add_shortcode('proposalViewVotesChart', 'bcProposalViewVotesChart::render');
    add_shortcode('proposalViewList', 'bcProposalViewList::render');
    add_shortcode('registerUser', 'bcRegisterUser::render');
    add_shortcode('cognitoLoginProposals', 'bcLoginUser::render');
    add_shortcode('customerDetails', 'bcCustomerDetails::render');
    add_shortcode('logout', 'bcLogout::render');
    
    
  }

  public static function intializeScripts()
  {
    wp_enqueue_style('style', bcPluginUrl . 'assets/css/style.css', false, '1.6', 'all'); // Inside a plugin
    wp_enqueue_script( 'bc-egister-form', bcPluginUrl . 'assets/js/custom.js', array( 'jquery' ), '1.1.0', true );
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '1');
    wp_enqueue_script('chartjsplugin', 'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0', array(), '1');
    wp_enqueue_style('space-grostek', 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('urbanist', 'https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', array(), null);
    wp_enqueue_style('dm-sans', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap', array(), null);
  }

  public static function adminPages()
  {
  }
  public static function registerAJAX()
  {
    add_action('admin_post_CCregisterAction', 'bcRegisterUser::registerUser_API');
    add_action('admin_post_nopriv_CCregisterAction', 'bcRegisterUser::registerUser_API');
    add_action('admin_post_CCtransferAction', 'bcCustomerMakeTransfer::TransferToken_API');
    add_action('admin_post_nopriv_CCtransferAction', 'bcCustomerMakeTransfer::TransferToken_API');
    add_action('admin_post_CCVoteAction', 'bcProposalViewSelection::vote_API');
    add_action('admin_post_nopriv_CCVoteAction', 'bcProposalViewSelection::vote_API');
  }
}
