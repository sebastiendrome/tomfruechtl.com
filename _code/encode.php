<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');

$string = 'éç';
$encoded = filename($string, 'encode');
echo $encoded;
