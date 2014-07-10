<?
	function summarize($paragraph,$limit,$link=''){
		$tok = strtok($paragraph, " ");
		while($tok){
			$text .= " $tok";
			$words++;
			if(($words >= $limit) && ((substr($tok, -1) == "!")||(substr($tok, -1) == ".")))
				break;
			$tok = strtok(" ");
		}
		$text .= ' '.$link;
		return ltrim($text);
	}
?>