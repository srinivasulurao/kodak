<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<?php
$categories=$this->data['categories'];
// echo "<pre>";
// print_r($categories);
// echo "</pre>";
?>
      <select name='cat' id='cat'>
      <option value="">--Select Problem--</option>
      <?php
      //Level1.
      foreach($categories['lvl_1'] as $key1=>$value1):
      	$level_value=$value1['ID'];
        $value1_lookupName=str_replace("X","",$value1['LookupName']);
      	echo "<option value='$level_value' class='lvl_1 rn_Hidden cat_{$value1['ID']}' disabled style='background:lightgrey'>$value1_lookupName</option>";
      //Level 2.
      	  if($value1['lvl_2']):
      	  foreach($value1['lvl_2'] as $key2=>$value2):
      	  	$level_value=$value1['ID'].",".$value2['ID'];
      	    echo "<option value='$level_value' class='lvl_2 rn_Hidden cat_{$value2['ID']}'>&nbsp|__ {$value2['LookupName']}</option>";
      //Level 3.
      	       if($value2['lvl_3']):
		       foreach($value2['lvl_3'] as $key3=>$value3):
		      	  $level_value=$value1['ID'].",".$value2['ID'].",".$value3['ID'];
		      	  echo "<option value='$level_value' class='lvl_3 rn_Hidden cat_{$value3['ID']}'>&nbsp&nbsp&nbsp&nbsp&nbsp|__ {$value3['LookupName']}</option>";
	  //Level 4.  
		      	     if($value3['lvl_4']):
			         foreach($value3['lvl_4'] as $key4=>$value4):
			      	  $level_value=$value1['ID'].",".$value2['ID'].",".$value3['ID'].",".$value4['ID'];
			      	  echo "<option value='$level_value' class='lvl_4 rn_Hidden cat_{$value4['ID']}'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|__ {$value4['LookupName']}</option>";      
			         endforeach;	
			         endif; 
     //Level 5.		 
	                     if($value4['lvl_5']):
				         foreach($value4['lvl_5'] as $key5=>$value5):
				      	  $level_value=$value1['ID'].",".$value2['ID'].",".$value3['ID'].",".$value4['ID'].",".$value5['ID'];
				      	  echo "<option value='$level_value' class='lvl_5 rn_Hidden cat_{$value5['ID']}'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|__ {$value5['LookupName']}</option>";      
				         endforeach;	
				         endif;
     //Level 6.
                             if($value5['lvl_6']):
					         foreach($value5['lvl_6'] as $key6=>$value6):
					      	  $level_value=$value1['ID'].",".$value2['ID'].",".$value3['ID'].",".$value4['ID'].",".$value5['ID'].",".$value6['ID'];
					      	  echo "<option value='$level_value' class='lvl_6 rn_Hidden cat_{$value6['ID']}'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp|__ {$value6['LookupName']}</option>";      
					         endforeach;	
					         endif;

		        endforeach;	
		      	endif;
      	  endforeach;	
      	  endif;
      endforeach;	
      ?>
      </select>
</div>

<style>
.lvl_2{
	padding-left:10px !important;
}
</style>