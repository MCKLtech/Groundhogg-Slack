<?php
namespace GroundhoggSlack\Steps\Actions;

use Groundhogg\Contact;
use Groundhogg\Event;
use Groundhogg\HTML;
use Groundhogg\Step;
use Groundhogg\Steps\Actions\Action;
use Groundhogg\Plugin as GHPlugin;

use function GroundhoggSlack\ghslack_get_user_id_by_email;
use function GroundhoggSlack\ghslack_client;

class Remove_User extends Action
{

    /**
     * Get the element name
     *
     * @return string
     */
    public function get_name()
    {
        return _x( 'Remove User from Slack', 'step_name', GROUNDHOGG_SLACK_TEXT_DOMAIN );
    }

    /**
     * Get the element type
     *
     * @return string
     */
    public function get_type()
    {
        return 'slack_remove_user';
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function get_description()
    {
        return _x( 'Removes a User to Slack', 'step_description', GROUNDHOGG_SLACK_TEXT_DOMAIN );
    }

    /**
     * Get the icon URL
     *
     * @return string
     */
    public function get_icon()
    {
        return GROUNDHOGG_SLACK_ASSETS_URL . '/images/slack.png';
    }

    /**
     * @param Step $step
     */
    public function settings( $step )
    {
        //No Settings
    }
    
    /**
     * Save the step settings
     *
     * @param $step Step
     */
    public function save( $step )
    {
        //Silence
        
    }

    /**
     *
     * @param $contact Contact
     * @param $event Event
     *
     * @return bool
     */
    
    public function run( $contact, $event )
    { 
        
        $client = ghslack_client();
        
        if(!$client) return false;
        
        //https://api.slack.com/methods/admin.users.remove
        
        $slack_id = ghslack_get_user_id_by_email($contact->get_email());
        
        try {
        
            $client->adminUsersRemove([
                'team_id' => GHPlugin::$instance->settings->get_option('gh_slack_team_id'),
                'user_id' => $slack_id,
            ]);   
        }
        
        catch(\Exception $e) {
            error_log($e->getMessage());
        }
        
        return true;
    }

}