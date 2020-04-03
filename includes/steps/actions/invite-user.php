<?php
namespace GroundhoggSlack\Steps\Actions;

use Groundhogg\Contact;
use Groundhogg\Event;
use Groundhogg\HTML;
use Groundhogg\Step;
use Groundhogg\Steps\Actions\Action;
use Groundhogg\Plugin as GHPlugin;

use function GroundhoggSlack\ghslack_get_channel_ids;
use function GroundhoggSlack\ghslack_client;

class Invite_User extends Action
{

    /**
     * Get the element name
     *
     * @return string
     */
    public function get_name()
    {
        return _x( 'Invite User to Slack', 'step_name', GROUNDHOGG_SLACK_TEXT_DOMAIN );
    }

    /**
     * Get the element type
     *
     * @return string
     */
    public function get_type()
    {
        return 'slack_invite_user';
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function get_description()
    {
        return _x( 'Adds a User to Slack', 'step_description', GROUNDHOGG_SLACK_TEXT_DOMAIN );
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
        
        $this->start_controls_section();
    
        $this->add_control( 'channel_id', [
            'label'         => __( 'Add to these channel(s)', GROUNDHOGG_SLACK_TEXT_DOMAIN ),
            'type'          => HTML::SELECT2,
            'default'       => ghslack_get_channel_ids(),
            'description'   => __( 'The user will be added to the following channels', GROUNDHOGG_SLACK_TEXT_DOMAIN ),
            'field'         => [
                'multiple' => true,
                'tags' => true,
                'data'  => ghslack_get_channel_ids(),
                'placeholder'       => 'Please enter the Channel(s)'
            ],
        ] );
   
        $this->end_controls_section();

    }
    
    /**
     * Save the step settings
     *
     * @param $step Step
     */
    public function save( $step )
    {
        $this->save_setting( 'channel_id', wp_parse_list( $this->get_posted_data( 'channel_id', [] ) ) );
        
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
        $channel_ids = $this->get_setting( 'channel_id', []);
        
        $client = ghslack_client();
        
        if(!$client) return false;
        
        //https://api.slack.com/methods/admin.users.invite
        
        try {
            
            $payload = [
                'team_id' => GHPlugin::$instance->settings->get_option('gh_slack_team_id'),
                'email' => $contact->get_email(),
                'channel_ids' => implode(',',$channel_ids)
            ];
            
            $payload = apply_filters('groundhogg/slack/invite/payload', $payload, $contact, $event);
        
            $client->adminUsersInvite($payload);
            
        }
        
        catch(\Exception $e) {
            
            error_log($e->getMessage());
        }
        
        return true;
    }

}