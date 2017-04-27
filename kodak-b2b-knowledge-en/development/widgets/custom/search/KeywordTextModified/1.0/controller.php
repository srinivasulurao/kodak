<?php
namespace Custom\Widgets\search;

class KeywordTextModified extends \RightNow\Widgets\KeywordText {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        //$this->data['js']['initialValue']=$this->attrs['default_value']->value;

        return parent::getData();

    }
}