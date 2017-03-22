<rn:meta controller_path="custom/reports/Multiline2" js_path="custom/reports/Multiline2" presentation_css="widgetCss/Multiline2.css"  compatibility_set="November '09+"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_Multiline2<?=$this->data['topLevelClass'];?>">
    <div id="rn_<?=$this->instanceID;?>_Alert" role="alert" class="rn_ScreenReaderOnly"></div>
    <div id="rn_<?=$this->instanceID;?>_Loading"></div>
    <div id="rn_<?=$this->instanceID;?>_Content">
        <? if(is_array($this->data['tableData']['data']) && count($this->data['tableData']['data']) > 0): ?>
        <? if($this->data['tableData']['row_num']):?>
            <ol start="<?=$this->data['tableData']['start_num'];?>" >
        <? else:?>
            <ul>
        <? endif;?>
        <?
        // first three entries have no headers.  All others do
        // changes to the html here should be repeated in the logic file
        $cols = count($this->data['tableData']['headers']);
        foreach($this->data['tableData']['data'] as $value):?>
            <li>
                 <span class="rn_Element1"><?=$value[0];?></span>
                <? if($value[1]):?>
                <span class="rn_Element2"><?=$value[1];?></span>
                <? endif;?>
                <br/>
                <? if($value[2]):?>
                <span class="rn_Element3"><?=$value[2];?></span><br/>
                <? endif;?>
                <? for ($i = 3; $i < $cols; $i++):?>
                    <? if($this->data['tableData']['headers'][$i]['heading'] === ''):?>
                        <span class="rn_ElementsHeader"><?=$this->data['tableData']['headers'][$i]['heading']?></span>
                    <? else:?>
                        <span class="rn_ElementsHeader"><?=$this->data['tableData']['headers'][$i]['heading'].': '?> </span>
                    <? endif;?>
                    <span class="rn_ElementsData"><?=$value[$i];?> </span><br/>
                <? endfor;?>
            </li>
        <? endforeach; ?>
        <? if ($this->data['tableData']['row_num']):?>
            </ol>
        <? else: ?>
            </ul>
        <? endif; ?>
        <? endif;?>
    </div>
</div>
