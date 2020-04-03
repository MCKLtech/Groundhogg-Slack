<?php

namespace GroundhoggSlack;
use Groundhogg\Plugin as GHPlugin;
use function Groundhogg\get_contactdata;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
* Returns a list of Channel IDs / Names from Slack Team/Workspace
*
* @param None
*
* @return array
*/

function ghslack_get_channel_ids() {
    
    //https://slack.com/api/conversations.list
    
    $channels = get_transient('groundhogg_slack_channels');
    
    if(is_array($channels) && !empty($channels)) return $channels;
    
    $client = ghslack_client();
    
    $payload = [
            'types' => apply_filters('groundhogg/slack/channels/payload', 'public_channel')
        ];
    
    $payload = apply_filters('groundhogg/slack/channels/payload', $payload);
    
    $response = $client->conversationsList($payload);
    
    $options = [];
  
    foreach($response->getChannels() as $channel) {
                
        $options[$channel->getId()] = $channel->getName();
    }
    
    if(!empty($options)) {
        
        set_transient('groundhogg_slack_channels', $options, HOUR_IN_SECONDS);
    }

    
    return $options;
    
}

/**
* Returns Slack User ID based on User Email
*
* @param string $email
*
* @return string $slack_id
*/

function ghslack_get_user_id_by_email($email) {
    
    $contact = get_contactdata( $email );
    
    if ( $contact && $contact->exists() ){
        
        $slack_id = $contact->get_meta('slack_user_id');
        
        if($slack_id) return $slack_id;
    }
    
    try {
    
        //https://slack.com/api/users.lookupByEmail
    
        $client = ghslack_client();
    
        $response = $client->usersLookupByEmail(
            [
            'email' => $email
            ]);
      
        $user = $response->getUser();
    
        if ( $contact && $contact->exists() ){
        
            if($user->getId()) $contact->get_meta('slack_user_id', $user->getId());
        }
    
        $slack_id = $user->getId();
        
    }
    
    catch(\Exception $e) {
        
        $slack_id = false;
        
        error_log($e->getMessage());
        
    }
    
    return $slack_id;
}


/**
* Returns Slack User ID based on User Email
*
* @param none
*
* @return JoliCode Slack Client
*/

function ghslack_client() {
    
    try {
        
        $token = GHPlugin::$instance->settings->get_option('gh_slack_api_key');
    
        return \JoliCode\Slack\ClientFactory::create($token);
   
    }
    
    catch(\Exception $e) {
        
        error_log($e->getMessage());
    }
    
    return false;
}

