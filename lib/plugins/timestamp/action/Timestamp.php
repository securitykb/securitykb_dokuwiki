<?php
/**
 * DokuWiki Plugin timestamp (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Trenton Ivey <Trenton.Ivey@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_timestamp_Timestamp extends DokuWiki_Action_Plugin {

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler &$controller) {

       $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'handle_toolbar_define');
       $controller->register_hook('AJAX_CALL_UNKNOWN', 'BEFORE', $this, 'handle_ajax_call_unknown');
   
    }

    /**
     * Adds a toolbar button to insert timestamps
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */
    public function handle_toolbar_define(Doku_Event &$event, $param) {
        $event->data[] = array(    
            'type'   => 'timestamp',
            'title'  => $this->getLang('tb_timestamp_button'),
            'icon'   => '../../plugins/timestamp/timestamp.png',
            'key'    => ':',
            'block' => false
        );
    }

    /**
     * Handles AJAX 'timestamp' request and returns the strftime formatted time
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */
    public function handle_ajax_call_unknown(Doku_Event &$event, $param) {
        // Make sure we are handleing the correct AJAX 
        if ($event->data !== 'timestamp') {
            return;
        }
        // No other ajax call handlers are needed
        $event->stopPropagation();
        $event->preventDefault();
        // Set the content type
        header('Content-Type: text/plain');
        // 'Return' the strftime formated time
        echo strftime($this->getConf('timestamp_format'));
    }
}

// vim:ts=4:sw=4:et:
