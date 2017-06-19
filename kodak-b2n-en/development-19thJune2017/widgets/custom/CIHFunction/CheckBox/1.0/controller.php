<?php
namespace Custom\Widgets\CIHFunction;

class CheckBox extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
      
        $this->data['js']['value'] = $this->data['attrs']['value'];
        return parent::getData();

    }
}