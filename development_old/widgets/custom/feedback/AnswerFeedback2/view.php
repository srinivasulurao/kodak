<?php /* Originating Release: February 2012 */ ?>
<rn:meta controller_path="standard/feedback/AnswerFeedback2" js_path="standard/feedback/AnswerFeedback2" base_css="standard/feedback/AnswerFeedback2" presentation_css="widgetCss/AnswerFeedback2.css"  compatibility_set="November '09+"/>
<style>
.rn_AnswerFeedback2 input[type="submit"],
.rn_AnswerFeedback2 button {
    /*  button gradient 1x60 sprite image */
    background:#FFFF00 url(images/buttonGradientCombo.png) 0px 0px repeat-x;
    -moz-border-radius:4px;
    -webkit-border-radius:4px;
    -moz-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
    -webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
    border:1px solid #304764;
    color:#000;
    cursor:pointer;
    font:bold 12px Helvetica,Arial,sans-serif;
    line-height:normal;
    margin-right:6px;
    padding:6px 8px;
    text-decoration:none;
    text-shadow:2px 2px 2px rgba(0,0,0,0.25);
    /*Fix for IE6/7 button width bug*/
    *width:auto;
    *overflow:visible;
}
.rn_AnswerFeedback2 input[type="submit"]:hover,
.rn_AnswerFeedback2 input[type="submit"]:focus,
.rn_AnswerFeedback2 button:hover,
.rn_AnswerFeedback2 button:focus {
    background-position: 0px -30px;
    border-color:#46494d;
    cursor:pointer;
}
.rn_AnswerFeedback2 input[type="submit"][disabled],
.rn_AnswerFeedback2 button[disabled] {
    background-color:#46494d;
    background-position: 0px -30px;
    border-color:#333;
    color:#DDD;
}
.rn_AnswerFeedback2 input[type="submit"]:focus,
.rn_AnswerFeedback2 button:focus {
    /*IE8 doesn't apply focus outline natively*/
    outline /*\**/:#000 dotted 1px\9
}

</style>
<div id="rn_<?=$this->instanceID?>" class="rn_AnswerFeedback2">
    <? /* Define the rating feedback control. */ ?>
    <div id="rn_<?=$this->instanceID?>_AnswerFeedback2Control" class="rn_AnswerFeedback2Control">
        <? if ($this->data['attrs']['label_title']): ?>
            <div class="rn_Title"><?=$this->data['attrs']['label_title']?></div>
        <? endif;?>
        <? if ($this->data['js']['buttonView']): ?>
        <div id="rn_<?=$this->instanceID?>_RatingButtons">
            <button id="rn_<?=$this->instanceID?>_RatingYesButton" class="feedbackYesButton"><?=$this->data['attrs']['label_yes_button']?></button>
            <button id="rn_<?=$this->instanceID?>_RatingNoButton" class="feedbackNoButton"><?=$this->data['attrs']['label_no_button']?></button>
            <span id="rn_<?=$this->instanceID?>_ThanksLabel" class="rn_ThanksLabel">&nbsp;</span>
        </div>
        <? elseif ($this->data['attrs']['use_rank_labels']):?>
        <div id="rn_<?=$this->instanceID?>_RatingButtons">
           <? if ($this->data['attrs']['options_descending']):
                  for($i=$this->data['attrs']['options_count'];$i>0;$i--):
           ?>
                       <button id="rn_<?=$this->instanceID?>_RatingButton_<?=$i?>"><?=getMessage($this->data['rateLabels'][$i])?></button>
           <?
                   endfor;
               else:
                   for($i=1;$i<=$this->data['attrs']['options_count'];$i++):
           ?>
                       <button id="rn_<?=$this->instanceID?>_RatingButton_<?=$i?>"><?=getMessage($this->data['rateLabels'][$i])?></button>
           <?
                   endfor;
               endif;
           ?>
            <span id="rn_<?=$this->instanceID?>_ThanksLabel" class="rn_ThanksLabel">&nbsp;</span>
        </div>
        <? else:?>
        <div id="rn_<?=$this->instanceID?>_RatingMeter" class="rn_RatingMeter <?=$this->data['RatingMeterHidden']?>">
           <? if ($this->data['attrs']['options_descending']):
                  for($i=$this->data['attrs']['options_count'];$i>0;$i--)
                       echo "<a id='rn_".$this->instanceID.'_RatingCell_'.$i."' href='javascript:void(0)' class='rn_RatingCell' title='".getMessage($this->data['rateLabels'][$i])."' ".tabIndex($this->data['attrs']['tabindex'], $i).sprintf('><span class="rn_ScreenReaderOnly">'.$this->data['attrs']['label_accessible_option_description'], $i, $this->data['attrs']['options_count']).'</span>&nbsp;</a>';
               else:
                   for($i=1;$i<=$this->data['attrs']['options_count'];$i++)
                       echo "<a id='rn_".$this->instanceID.'_RatingCell_'.$i."' href='javascript:void(0)' class='rn_RatingCell' title='".getMessage($this->data['rateLabels'][$i])."' ".tabIndex($this->data['attrs']['tabindex'], $i).sprintf('><span class="rn_ScreenReaderOnly">'.$this->data['attrs']['label_accessible_option_description'], $i, $this->data['attrs']['options_count']).'</span>&nbsp;</a>';
               endif;
           ?>
            <span id="rn_<?=$this->instanceID?>_ThanksLabel" class="rn_ThanksLabel">&nbsp;</span>
        </div>
        <? endif;?>
    </div>
    <? /* Container for feedback form.  It's hidden on the page. */ ?>
    <div id="rn_<?=$this->instanceID?>_AnswerFeedback2Form" class="rn_AnswerFeedback2Form rn_Hidden">
        <div id="rn_<?=$this->instanceID?>_DialogDescription" class="rn_DialogSubtitle"><?=$this->data['attrs']['label_dialog_description'];?></div>
        <div id="rn_<?=$this->instanceID;?>_ErrorMessage"></div>
        <form>
        <? if (!$this->data['js']['isProfile']): ?>
            <label for="rn_<?=$this->instanceID?>_EmailInput"><?=$this->data['attrs']['label_email_address'];?><span class="rn_Required" > <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span></label>
            <input id="rn_<?=$this->instanceID?>_EmailInput"  class="rn_EmailField" type="text" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> value="<?=$this->data['js']['email'];?>"/>
        <? endif;?>
        <label for="rn_<?=$this->instanceID?>_FeedbackTextarea"><?=$this->data['attrs']['label_comment_box'];?><span class="rn_Required" > <?=getMessage(FIELD_REQUIRED_MARK_LBL);?></span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span></label>
        <textarea id="rn_<?=$this->instanceID?>_FeedbackTextarea" class="rn_Textarea" rows="4" cols="60" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>></textarea>
        </form>
    </div>
    <? /* End form */ ?>
</div>
