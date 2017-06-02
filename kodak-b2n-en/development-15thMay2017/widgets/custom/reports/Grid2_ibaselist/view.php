<rn:meta controller_path="custom/reports/Grid2_ibaselist" js_path="custom/reports/Grid2_ibaselist" presentation_css="widgetCss/Grid2.css" base_css="custom/reports/Grid2_ibaselist" compatibility_set="November '09+"/>
<? if($this->data['attrs']['headers']):?>
<? $this->addJavaScriptInclude(getYUICodePath('datasource/datasource-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('datatable/datatable-min.js'));?>
<? endif;?>


<div id="rn_<?=$this->instanceID;?>" class="rn_Grid2<?=$this->data['topLevelClass'];?>">
    <div id="rn_<?=$this->instanceID;?>_Alert" role="alert" class="rn_ScreenReaderOnly"></div>
    <div id="rn_<?=$this->instanceID;?>_Loading"></div>
    <div id="rn_<?=$this->instanceID;?>_Content" class="yui-skin-sam">
        <table id="rn_<?=$this->instanceID;?>_Grid" class="yui-dt" summary="<?=$this->data['attrs']['label_summary']?>">
        <caption><?=$this->data['attrs']['label_caption']?></caption>
        <? if($this->data['attrs']['headers']):?>
            <thead>
                <tr class="yui-dt-first yui-dt-last">
                <? if($this->data['tableData']['row_num']):?>
                    <th scope="col" class="yui-dt-sortable"><?=$this->data['attrs']['label_row_number']?></th>
                <? endif;?>
                <? foreach($this->data['tableData']['headers'] as $header):?>
                    <? if($header['width'] !== null):?>
                        <th scope="col" width="<?=$header['width'];?>%" class="yui-dt-sortable"><?=$header['heading'];?></th>
                    <? else:?>
                        <th scope="col" class="yui-dt-sortable"><?=$header['heading'];?></th>
                    <? endif;?>
                <? endforeach;?>
                </tr>
            </thead>
        <? endif;?>
             <? if( count($this->data['tableData']['data']) > 0): ?>
            <tbody class="yui-dt-body">
            <? for($i = 0; $i < count($this->data['tableData']['data']); $i++): ?>
              
				<tr class="<?=($i%2 === 0)?'yui-dt-even':'yui-dt-odd'?>">
               
				<? if($this->data['tableData']['row_num']):?>
                    <td><?=$this->data['tableData']['start_num'] + $i;?></td>
                <? endif;?>

                <? for($j=0; $j<count($this->data['tableData']['headers']); $j++):?>
					<? if($this->data['tableData']['headers'][$j]['heading'] != 'Product Description1') {  ?>
                    <td><?=($this->data['tableData']['data'][$i][$j]) ? $this->data['tableData']['data'][$i][$j] : '&nbsp;'?></td>
						<? } else {
							echo "<td>";
		if(count($this->data['tableData']['data']['equipment'][($i+1)]) >0) {
			$p = 1;
		    foreach($this->data['tableData']['data']['equipment'][($i+1)] as $all_lvl) {

				$lvl_id = explode(",",$all_lvl['lvl_id']);
				if($all_lvl['name']!="") {
					$equipment_name = "<a href=\"/app/ask/p/0/incidents.c\$ek_equip_component_id/".$all_lvl['k_number']."/incidents.c\$ek_serial_number/".$all_lvl['serial_number']."\">".$all_lvl['name']."</a>";

			  if($p == 1) {
					echo "&nbsp".$equipment_name."<br/>"; 
			      }
  			  else if($p == 2) {
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$all_lvl['name']."<br/>"; 
			      }
			  else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$all_lvl['name']."<br/>"; 
		    	 }
				$p++;
			}
			}
		 }
           echo "</td>"; } ?>
                <? endfor;?>
                </tr>
            <? endfor;?>
            </tbody>
        <? else:?>
            <tbody id="rn_<?=$this->instanceID;?>_Tbody"><tr><td></td></tr></tbody>
        <? endif;?>
        </table>
    </div>
</div>
