<rn:meta controller_path="standard/knowledgebase/TopicWords2" js_path="standard/knowledgebase/TopicWords2" presentation_css="widgetCss/TopicWords2.css" compatibility_set="November '09+"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_TopicWords2 <?=$this->data['hiddenClass'];?>" >
    <div class="rn_Title"><?=$this->data['attrs']['label_title'];?></div>
    <? if (count($this->data['topicWords']) > 0): ?>
    <dl id="rn_<?=$this->instanceID;?>_List">
    <? for($i=0; $i<count($this->data['topicWords']); $i++):?>
        <dt>
        <? if($this->data['attrs']['display_icon']):?>
            <?=$this->data['topicWords'][$i]['icon'];?>
        <? endif;?>
            <a href="<?=$this->data['topicWords'][$i]['url'];?>" <?=tabIndex($this->data['attrs']['tabindex'], $i);?> target="<?=$this->data['attrs']['target'];?>" ><?=$this->data['topicWords'][$i]['title']?></a>
        </dt>
        <dd><?=$this->data['topicWords'][$i]['text'];?></dd>
    <? endfor;?>
    </dl>
    <? endif; ?>
</div>
