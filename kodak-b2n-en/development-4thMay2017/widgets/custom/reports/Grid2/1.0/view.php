<div id="rn_<?= $this->instanceID ?>" class="rn_Grid2<?=$this->data['topLevelClass'];?>">
    <div id="rn_<?=$this->instanceID;?>_Alert" role="alert" class="rn_ScreenReaderOnly"></div>
    <div id="rn_<?=$this->instanceID;?>_Loading"></div>
    <div id="rn_<?=$this->instanceID;?>_Content" class="yui-skin-sam">
        <table id="rn_<?=$this->instanceID;?>_Grid" class="yui-dt">
        <caption><?=$this->data['attrs']['label_caption']?></caption>
        <? if($this->data['attrs']['headers']):?>
            <thead>
                <tr class="yui-dt-first yui-dt-last">
                <? if($this->data['tableData']['row_num']):?>
                    <th scope="col" class="yui-dt-sortable"><?=$this->data['attrs']['label_row_number']?></th>
                <? endif;?>
                <? foreach($this->data['tableData']['headers'] as $header):?>
                    <? if($header['width'] !== null):?>
                        <th scope="col" style='width:"<?=$header['width'];?>%"' class="yui-dt-sortable"><?=$header['heading'];?></th>
                    <? else:?>
                        <th scope="col" class="yui-dt-sortable"><?=$header['heading'];?></th>
                    <? endif;?>
                <? endforeach;?>
                </tr>
            </thead>
        <? endif;?>
        <? if($this->data['internal_user'] == 'N' && count($this->data['tableData']['data']) > 0): ?>
            <tbody class="yui-dt-body">
            <? for($i = 0; $i < count($this->data['tableData']['data']); $i++): ?>
                <tr class="<?=($i%2 === 0)?'yui-dt-even':'yui-dt-odd'?>">
                <? if($this->data['tableData']['row_num']):?>
                    <td><?=$this->data['tableData']['start_num'] + $i;?></td>
                <? endif;?>
                <? for($j=0; $j<count($this->data['tableData']['headers']); $j++):?>
					<? if($this->data['attrs']['url_column'] > 0 && $j==$this->data['attrs']['url_column'] && $this->data['tableData']['data'][$i][$j]):?>
	<td><?='<a href="'.(($this->data['tableData']['data'][$i][$j]) ? $this->data['tableData']['data'][$i][$j] : '&nbsp;').'">'?><?=$this->data['attrs']['url_text']?></a></td>
					<? else:?>
						<td><?=($this->data['tableData']['data'][$i][$j]) ? $this->data['tableData']['data'][$i][$j] : '&nbsp;'?></td>
					<? endif;?>
                <? endfor;?>
                </tr>
            <? endfor;?>
            </tbody>
        <? else:?>
            <tbody id="rn_<?=$this->instanceID;?>_Tbody">
                <tr>
                <? foreach ($this->data['tableData']['headers'] as $column): ?>
                    <td></td>
                <? endforeach; ?>
                </tr>
            </tbody>
        <? endif;?>
        </table>
    </div>
</div>