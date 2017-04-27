<?php /* Originating Release: February 2012 */ ?>
<rn:meta controller_path="custom/knowledgebase/MyRelatedAnswers" 
         presentation_css="widgetCss/RelatedAnswers2.css" 
         compatibility_set="November '09+"
         required_js_module="november_09,mobile_may_10,none"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_RelatedAnswers2">
<?if($this->data['attrs']['label_title']):?>
    <h2><?=$this->data['attrs']['label_title'];?></h2>
<?endif;?>
    <ul>
    <? for($i=0; $i<count($this->data['relatedAnswers']); $i++):?>
        <li><a href="<?=$this->data['attrs']['url'].'/a_id/'.$this->data['relatedAnswers'][$i][0] . $this->data['appendedParameters'];?>" target="<?=$this->data['attrs']['target'];?>" <?=tabIndex($this->data['attrs']['tabindex'], $i);?>> <?=$this->data['relatedAnswers'][$i][2];?></a></li>
    <? endfor;?>
    </ul>
</div>
