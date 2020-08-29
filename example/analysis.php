<?php
include __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'analysis'.DIRECTORY_SEPARATOR.'analysis.class.php';
$ana=new analysis();
$result=$ana->doAnalysis(__DIR__.DIRECTORY_SEPARATOR.'Log'.DIRECTORY_SEPARATOR.'20200824080927-TRACE_1598256567850','|');
print_r($result);