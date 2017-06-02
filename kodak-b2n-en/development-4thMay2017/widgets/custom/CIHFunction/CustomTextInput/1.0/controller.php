<?php
namespace Custom\Widgets\CIHFunction;

class CustomTextInput extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

        
    }

    function getData() {
        $this->data['js']['value'] = $this->data['info']['attributes']['value'];
        return parent::getData();

    }
}