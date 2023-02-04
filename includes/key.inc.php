<?php
function e7061($e){
	$ed = base64_decode($e);
	$n = openssl_decrypt("$ed","AES-256-CBC","2545698542536548",0,"2545698542536548");
	return $n;
}
?>