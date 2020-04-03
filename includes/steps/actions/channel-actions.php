<?php
namespace GroundhoggSlack\Steps\Actions;

use Groundhogg\Contact;
use Groundhogg\Event;
use Groundhogg\HTML;
use Groundhogg\Step;
use Groundhogg\Steps\Actions\Action;
use Groundhogg\Plugin as GHPlugin;

use function GroundhoggSlack\ghslack_get_user_id_by_email;
use function GroundhoggSlack\ghslack_get_channel_ids;
use function GroundhoggSlack\ghslack_client;

class Channel_Actions extends Action
{

    /**
     * Get the element name
     *
     * @return string
     */
    public function get_name()
    {
        return _x( 'Add / Remove User from Slack Channel(s)', 'step_name', GROUNDHOGG_SLACK_TEXT_DOMAIN );
    }

    /**
     * Get the element type
     *
     * @return string
     */
    public function get_type()
    {
        return 'slack_channel_actions';
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function get_description()
    {
        return _x( 'Add or Remove a User from Slack Channel(s)', 'step_description', GROUNDHOGG_SLACK_TEXT_DOMAIN );
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
        
        error_log('Saved Values::'.print_r($this->get_setting( 'channel_ids', []),true));
        
        $this->start_controls_section();
        
        $this->add_control( 'channel_action', [
            'label'         => __( 'Action', GROUNDHOGG_SLACK_TEXT_DOMAIN ),
            'type'          => HTML::DROPDOWN,
            'default'       => 'add',
            'description'   => __( 'You can add or remove users from the specified channels', GROUNDHOGG_SLACK_TEXT_DOMAIN ),
            'field'         => [
                'options'     => array(
                    'add'           => __( 'Add to Channel(s)' ),
                    'remove'     => __( 'Remove from Channel(s)' )
                ),
            ],
        ] );
        
        $this->add_control( 'channel_id', [
            'label'         => 'Channels: ',
            'type'          => HTML::SELECT2,
            'default' => ghslack_get_channel_ids(),
            'description'   => __( 'Add new channels by hitting [enter] or by typing a [comma].', GROUNDHOGG_SLACK_TEXT_DOMAIN ),
            'field'         => [
                'multiple' => true,
                'placeholder' => __( 'Please Enter a Channel(s)', GROUNDHOGG_SLACK_TEXT_DOMAIN),
                'tags' => true,
                'data' => ghslack_get_channel_ids()
            ]
         ]);
   
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
        
        $this->save_setting( 'channel_action', sanitize_text_field( $this->get_posted_data( 'channel_action', 'add' ) ) );
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
        
        //https://api.slack.com/methods/conversations.invite
        //https://api.slack.com/methods/conversations.kick
        
        $slack_id = ghslack_get_user_id_by_email($contact->get_email());
        
        $channel_ids = $this->get_setting( 'channel_id', []);
        
        $action = $this->get_setting( 'channel_action', 'add');
        
        foreach($channel_ids as $channel_id) {
            
            try {
                
                $payload = [
                    'channel' => $channel_id,
                    'user_id' => $slack_id
                ];
                
                $payload = apply_filters('groundhogg/slack/actions/payload', $payload, $contact, $event, $action);
        
                if($action == 'remove') {
                
                    $client->conversationsInvite($payload);
                }
            
                else {
                
                    $client->conversationsKick($payload);
                }
            }
        
            catch(\Exception $e) {
                
                error_log($e->getMessage());
            }
        }
        
        return true;
    }

}