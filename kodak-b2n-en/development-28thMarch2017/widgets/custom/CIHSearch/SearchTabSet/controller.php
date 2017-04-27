<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

// use this controller if you don't need any data in your widget
// $name and $info[w_id] will be available in your view for creating unique html names

class SearchTabSet extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->info ['type'] = 'blank';
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] =  getMessage(WIDGET_BLANK_CONTROLLER_SPECIFIC_MSG);
    }

    function getData()
    {
    }

}
