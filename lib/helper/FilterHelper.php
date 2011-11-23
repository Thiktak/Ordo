<?php

function filter_link_to($sText, $mUrl, $sFilterValue = null, array $aParams = null, $sFilterName = 'filter')
{	
	$aFilterValues = array();
	$array = null;
	$isactive = true;
	
	foreach( (array) $sFilterValue as $key => $value )
		if( is_object($value) ) {
			$array = explode('-', $value->getParameter($sFilterName));
			unset($sFilterValue[$key]);
		}
	
	if( $array )
	{
		/*$aFilterValues = array_merge($array, $aFilterValues);
		
		foreach( (array) $sFilterValue as $value )
		{
			$a = false;
			foreach( (array) $sFilterValue as $value2 )
				if( !in_array($value2, (array) $array) )
				{
					$aFilterValues[$value] = $value;
					$a = true;
					$isactive = false;
					break;
				}
			
			if( !$a )
				unset($aFilterValues[$value]);
		}*/
		
		if( $array == (array) $sFilterValue )
		{
			$isactive = true;
			$aFilterValues = null;
		}
	}
	else
	{
		$isactive = false;
		$aFilterValues = (array) $sFilterValue;
	}
	
	//*/
	rsort($aFilterValues = array_unique($aFilterValues));
	
	$mUrl .= (strpos($mUrl, '?') !== false ? '&' : '?') . $sFilterName . '=' . urlencode(implode('-', $aFilterValues));
	
	
	$aParams = array_merge(array('class' => 'filter' . ($isactive ? ' active' : null)), (array) $aParams);
	return link_to($sText, $mUrl, $aParams);
}
