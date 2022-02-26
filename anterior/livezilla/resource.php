<?php
/****************************************************************************************
 * LiveZilla resource.php
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 * Improper changes to this file may cause critical errors.
 ***************************************************************************************/

$ftype = (true) ? "min." : "";
$paramIndex = 1;
$code = "";
while(isset($_REQUEST[$paramIndex]))
{
    if(strtolower($_REQUEST[$paramIndex])=="style.min.css")
        $code .= file_get_contents("./templates/style.".$ftype."css");
    else if(strtolower($_REQUEST[$paramIndex])=="chatv21/style.min.css")
        $code .= file_get_contents("./templates/overlays/chatv21/style.".$ftype."css");
    else if(strtolower($_REQUEST[$paramIndex])=="jsglobal.min.js")
    {
        $code .= file_get_contents("./templates/jscriptv21/jsglobal.".$ftype."js");
        $code .= file_get_contents("./templates/jscriptv21/icons.".$ftype."js");
    }
    else if(strtolower($_REQUEST[$paramIndex])=="jsbox.min.js")
    {
        $code .= file_get_contents("./templates/jscriptv21/jsbox.".$ftype."js");
        $code .= file_get_contents("./templates/jscriptv21/jsboxv2.".$ftype."js");
    }
    else if(strtolower($_REQUEST[$paramIndex])=="jstrack.min.js")
        $code .= file_get_contents("./templates/jscriptv21/jstrack.".$ftype."js");
    else if(strtolower($_REQUEST[$paramIndex])=="jsextern.min.js")
        $code .= file_get_contents("./templates/overlays/chatv21/jsextern.".$ftype."js");

    $paramIndex++;
}
if($_REQUEST["t"]=="css")
    header("Content-Type: text/css;");
else
    header("Content-Type: application/javascript;");

$expires = 60*60*24;
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
exit($code);
?>