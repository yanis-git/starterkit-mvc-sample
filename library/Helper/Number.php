<?php
/**
* Number Helper
*/
class Helper_Number
{
	
	//Random Float
	public static function frand($Min, $Max, $round=0){
	    //validate input
	    if ($Min>$Max) { $min=$Max; $max=$Min; }
        else { $min=$Min; $max=$Max; }
	    
	    $randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
	    if($round>0)
	        $randomfloat = round($randomfloat,$round);

	    return $randomfloat;
	}
}