<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CurrentMeter extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
        $this->attrs['element_id'] = new Attribute("elementID", 'String', "Id used to form element name", "text");
        $this->attrs['date_hint'] = new Attribute("dateHint", 'String', "text for date entry", "text");
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] =  "The popup dialog displays with an OK button.";
    }

    function getData()
    {
        //Perform php logic here
    }
}



