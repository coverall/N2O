<?php
// $Id: CC_Default_Summary_Window.php,v 1.7 2003/06/10 22:54:56 patrick Exp $
if (!($window = &$application->getWindow($action)))
{
	// ------------------------------------------------------------------
	// Create our window
	//
	if (isset($window_class))
	{
		$window = new $window_class();
	}
	else
	{
		$window = new CC_Window();
	}
	$application->registerWindow($window);

	
	// ------------------------------------------------------------------
	// (1) Create our buttons
	//
	
	$backButton = new CC_Button("Back", false);

	// ------------------------------------------------------------------
	// (2) Create our handlers
	//
	
	$backButtonHandler = new CC_Cancel_Button_Handler();
	
	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//
	//$testButton->registerHandler($testHandler);
	//$testLink->registerHandler($testHandler);
	
	$backButton->registerHandler($backButtonHandler);


	// ------------------------------------------------------------------
	// (4) Register the buttons with the window
	//
	
	$window->registerComponent($backButton);
	
	
	// ------------------------------------------------------------------
	// (5) Register the summary with the application
	//
	$defaultSummary = new CC_Summary($window, $application->getArgument("query"), "defaultSummary");
	
	$window->registerComponent($databaseSummary);
	
	// ------------------------------------------------------------------
	// (6) Register the window with the application
	//
	
}
else
{
	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	
	$defaultSummary = &$window->getSummary("defaultSummary");
	$defaultSummary->update();
	
	$backButton = &$window->getButton("backButton");
}

?>

<?php echo $window->getHeader(); ?>

<table border="0" width="640"><tr><td>

<?php
echo $defaultSummary->getHTML();
//echo $testButton->getHTML();
//echo $testLink->getHTML();
?>

<p>

<?php $backButton->getHTML(); ?>


</td></tr></table>

<?php echo $window->getFooter(); ?>
