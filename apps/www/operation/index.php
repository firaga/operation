<?php

$msg = 'hello world!';
$render = new KRender_default;
$render->oSetTemplate('operation/index.html')
	->oSetData('msg', $msg)
	->oSend();
var_dump(debug_backtrace());
