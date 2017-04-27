<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
    <fieldset>
<? if($this->data['attrs']['label_input']):?>
    <legend id="rn_<?=$this->instanceID;?>_Legend" class="rn_Label"><?=$this->data['attrs']['label_input'];?>
    <? if($this->data['attrs']['required']):?>
        <span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?> </span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span>
    <? endif;?>
    </legend>
<? endif;?>
<? for($i = 0; $i < 3; $i++):?>

    <? /**Year*/ ?>
    <? if($this->data['yearOrder'] === $i):?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Year" class="rn_ScreenReaderOnly"><?=$this->data['yearLabel'];?><? if($this->data['js']['hint'] && !$this->data['attrs']['hide_hint'] && $i===0):?> <?=$this->data['js']['hint']?><?endif?></label>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Year" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = $this->data['maxYear']; $j >= $this->data['minYear']; $j--):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][2] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>

    <? /**Month*/ ?>
    <? elseif($this->data['monthOrder'] === $i):?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Month" class="rn_ScreenReaderOnly"><?=$this->data['monthLabel'];?><? if($this->data['js']['hint'] && !$this->data['attrs']['hide_hint'] && $i===0):?> <?=$this->data['js']['hint']?><?endif;?></label>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Month" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = 1; $j < 13; $j++):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][0] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>

    <? /**Day*/ ?>
    <? else:?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Day" class="rn_ScreenReaderOnly"><?=$this->data['dayLabel'];?><? if($this->data['js']['hint'] && !$this->data['attrs']['hide_hint'] && $i===0):?> <?=$this->data['js']['hint']?><?endif;?></label>
    <select id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>_Day" <?=tabIndex($this->data['attrs']['tabindex'], 1 + $i);?>>
        <option value=''>--</option>
        <? for($j = 1; $j < 32; $j++):?>
        <? if($this->data['defaultValue']) $selected = ($this->data['value'][1] == $j) ? 'selected="selected"' : '';?>
        <option value="<?=$j;?>" <?=$selected;?>><?=$j;?></option>
        <? endfor;?>
    </select>
    <? endif;?>
<? endfor;?>

</fieldset>
</div>