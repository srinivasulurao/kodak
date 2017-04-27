<?php /* Originating Release: February 2012 */

class related_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }


    /**
     * Retrieves a set number of related answers associated to a specific
     * answer ID. It will first grab all manually related answers and then
     * any learned link answers if size permits
     *
     * @param $answerID int Answer ID from which to get related answers
     * @param $limit int Amount of related and learned link answers to retrieve
     * @param $truncateSize int The number of characters to truncate answer text to
     *
     * @return array Results from query
     */
    function getMyRelatedAnswers($answerID, $limit, $truncateSize, $returnRelatedOnly )
    {
   $CI = get_instance();
    $CI->load->model('standard/Answer_model');
    $relatedAnswers = $CI->Answer_model->getRelatedAnswers($answerID, 100, $truncateSize);
    $myRelatedAnswers = array();
    $interfaceId = intf_id();
	$countRonly = 0;
	$countLonly = 0;
    foreach($relatedAnswers as $answer){
	   $toanswerID = $answer[0];
       $sql = "SELECT l.static_strength FROM links l, answers fto, interfaces i where (l.from_a_id = '$answerID') AND (l.to_a_id = '$toanswerID' ) AND (l.to_a_id = fto.a_id) AND (i.lang_id = fto.lang_id) AND (i.interface_id = $interfaceId)";
	   $si = sql_prepare($sql);
       sql_bind_col($si, 1, BIND_INT, 0);
	   $row = sql_fetch($si);
   
	   if($row[0] && $returnRelatedOnly && ($countRonly <> $limit)) {
		  $myRelatedAnswers[] = $answer; 
		  $countRonly++;
	   }
	   if(!$row[0] && !$returnRelatedOnly && ($countLonly <> $limit))  {
		 $myRelatedAnswers[] = $answer; 
		 $countLonly++;
	   }		
       sql_free($si);
    } 
    return $myRelatedAnswers; 
    }

}
