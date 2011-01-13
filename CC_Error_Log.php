<?php

//=======================================================================
// FILE: CC_Error_Log
//=======================================================================

$application = $_SESSION['application'];

if ($application->hasApplicationErrors())
{
	$applicationErrors = $application->getApplicationErrors();
}
else
{
	echo "There are no errors!<p>";
}

?>