<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class DialogPopup extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
        $this->attrs['title'] = new Attribute("Title", 'String', "Title of dialog to be displayed", "text");
        $this->attrs['message'] = new Attribute("Message", 'String', "Text of the message to be displayed", "text");
        $this->attrs['width'] = new Attribute("Width", 'INT', "Width in pixels of the popup", "400");
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



