<?exit?>
{php $s = ob_get_contents();}
{php ob_end_clean();}
{php $s = preg_replace("/([\x01-\x09\x0b-\x0c\x0e-\x1f])+/", ' ', $s);}
{php $s = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $s);}
$s
]]></root>{php exit;}