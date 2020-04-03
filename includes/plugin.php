<?php

namespace GroundhoggSlack;

use Groundhogg\Extension;

use GroundhoggSlack\Steps\Actions\Invite_User;
use GroundhoggSlack\Steps\Actions\Remove_User;
use GroundhoggSlack\Steps\Actions\Channel_Actions;

class Plugin extends Extension{

    /**
     * Override the parent instance.
     *
     * @var Plugin
     */
    public static $instance;
    
    /**
     * Extension constructor.
     */
    public function __construct()
    {
        if ( $this->dependent_plugins_are_installed() ){

            $this->register_autoloader();

            if ( ! did_action( 'groundhogg/init/v2' ) ){
                add_action( 'groundhogg/init/v2', [ $this, 'init' ] );
            } else {
                $this->init();
            }  
        }
    }

    /**
     * Include any files.
     *
     * @return void
     */
    public function includes()
    {
        require GROUNDHOGG_SLACK_PATH . '/includes/functions.php'; 
        
        require GROUNDHOGG_SLACK_PATH . '/includes/lib/slack/vendor/autoload.php';
        
    }

    /**
     * Init any components that need to be added.
     *
     * @return void
     */
    public function init_components()
    {
        //Silence
    }
      
    /**
     * Register additional replacement codes.
     *
     * @param \Groundhogg\Replacements $replacements
     */
    public function add_replacements( $replacements )
    {
        $wc_replacements = new Replacements();

        foreach ($wc_replacements->get_replacements() as $replacement ){
         
            $replacements->add( $replacement[ 'code' ], $replacement[ 'callback' ], $replacement[ 'description' ] );
        }
    }

    /**
     * @param \Groundhogg\Steps\Manager $manager
     */
    public function register_funnel_steps($manager)
    {
        //Actions
        $manager->add_step( new Invite_User() );
        $manager->add_step( new Remove_User() );
        $manager->add_step( new Channel_Actions() );
    }

    /**
     * Get the ID number for the download in EDD Store
     *
     * @return int
     */
    public function get_download_id()
    {
        // TODO: Implement get_download_id() method.
    }

    /**
     * Get the version #
     *
     * @return mixed
     */
    public function get_version()
    {
        return GROUNDHOGG_SLACK_VERSION;
    }

    /**
     * @return string
     */
    public function get_plugin_file()
    {
        return GROUNDHOGG_SLACK__FILE__;
    }
    
    /**
     * Add settings to the settings page
     *
     * @param $settings array[]
     * @return array[]
     */
    public function register_settings( $settings ){ 
    
        $settings['gh_slack_api_key'] = array(
                'id' => 'gh_slack_api_key',
                'section' => 'gh_slack_settings',
                'label' => _x('Slack API Key', 'settings', 'groundhogg'),
                'desc' => _x('Your Slack API Key', 'settings', 'groundhogg'),
                'type' => 'input',
                'atts' => array(
                    'id' => 'gh_slack_api_key',
                    'name' => 'gh_slack_api_key',
                    'placeholder' => 'xoxp-123XXX1903815-11111903895-10XX726841111-xx69d339xxxxd055dc90ebfed4f1xxxx'
                ),
            );
        
        $settings['gh_slack_team_id'] = array(
                'id' => 'gh_slack_team_id',
                'section' => 'gh_slack_settings',
                'label' => _x('Slack Team ID', 'settings', 'groundhogg'),
                'desc' => _x('Your Slack Team ID', 'settings', 'groundhogg'),
                'type' => 'input',
                'atts' => array(
                    'id' => 'gh_slack_team_id',
                    'name' => 'gh_slack_team_id',
                    'placeholder' => 'T011B1XXXX'
                ),
            );
             
    return $settings;
    
    }

    /**
     * Add settings sections to the settings page
     *
     * @param $sections array[]
     * @return array[]
     */
    public function register_settings_sections( $sections ){ 
        
        $sections['gh_slack_settings'] = array(
                
            'id' => 'gh_slack_settings',
                
            'title' => _x('Slack Settings', 'settings_tabs', 'groundhogg'),
            
            'tab' => 'gh_slack'
        );
        
        return $sections;
    
    }

    /**
     * Add settings tabs to the settings page
     *
     * @param $tabs array[]
     * @return array[]
     */
    public function register_settings_tabs( $tabs ){ 
        
        $tabs['gh_slack'] =  array(
                
            'id' => 'gh_slack',
                
            'title' => _x('Slack', 'settings_tabs', 'groundhogg')
            
        );
    
        return $tabs;
    
    }
    
    /**
     * Register autoloader.
     *
     * Groundhogg autoloader loads all the classes needed to run the plugin.
     *
     * @since 1.6.0
     * @access private
     */
    protected function register_autoloader()
    {
        require GROUNDHOGG_SLACK_PATH . 'includes/autoloader.php';
        Autoloader::run();
    }
}

Plugin::instance();