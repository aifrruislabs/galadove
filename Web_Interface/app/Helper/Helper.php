<?php

//Public Location
function pF() {

	$currentUrl = url()->current();

	$explodeUrl = explode(":8000", $currentUrl);

	if ($explodeUrl[0] == "http://127.0.0.1" || $explodeUrl[0] == "https://127.0.0.1") {	        
	    return "";
	}else {
		return "/public";
	}
	
}