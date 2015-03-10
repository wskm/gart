<?exit?>
{php ob_end_clean();}
{php ob_start();}
{php @header("Expires: -1");}
{php @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);}
{php @header("Pragma: no-cache");}
{php @header("Content-type: application/xml; charset=".PAGE_CHARSET);}
{echo '<?xml version="1.0" encoding="'.PAGE_CHARSET.'"?>' }
<root><![CDATA[