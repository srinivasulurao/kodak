<rn:meta title="#rn:msg:ASK_QUESTION_HDG#" template="newkodak_b2b_template.php" clickstream="incident_create" />
<br><br>
<form method='post' id='test_form'>
<!--<div class='sm'><rn:widget path="custom/CIHFunction/ProblemFound"  data_type="product" name='Incident.Product' table="incidents" /></div> -->
<!--<rn:widget path="input/ProductCategoryInput"  data_type="category" name='Incident.Category' table="incidents" />-->
<div class='sm'><rn:widget path="custom/CIHFunction/ProblemFound"  data_type="category" name='Incident.Category' table="incidents" /></div>

</form>



<script>

function fireEvent(){
	var eo = new RightNow.Event.EventObject();

	eo.data.value=1481;

	RightNow.Event.fire("evt_populateProduct", eo);  
	
	//alert("Event Fired");
	 
	
}
</script>