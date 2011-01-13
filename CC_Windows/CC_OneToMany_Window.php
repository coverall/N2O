<?php
// $Id: CC_OneToMany_Window.php,v 1.16 2010/11/11 04:28:32 patrick Exp $
// If the window is registered but the record is not, we want to unRegister the
// window so it constructs all its components afresh

/*
if ($application->isWindowRegistered($application->getAction()))
{
	$window = &$application->getWindow($application->getAction());	
	
	$key = CC_Record::getKeyID($application->getArgument("tableNameForEdit"), $application->getArgument("editRecordId"));
	
	if (!($window->isRecordRegistered($key)))
	{
		$application->unRegisterWindow($application->getAction());
	}	
}
*/

$oneToManySelectedIds 	= $application->getArgument('oneToManySelectedIds');
$oneToManySetTable    	= $application->getArgument('oneToManySetTable');
$oneToManySourceTable 	= $application->getArgument('oneToManySourceTable');
$oneToManyDisplayColumn = $application->getArgument('oneToManyDisplayColumn');
$oneToManyLabel 		= $application->getArgument('oneToManyLabel');
$oneToManyField			= &$application->getObject('oneToManyField');
$oneToManyReadOnly		= $application->getArgument('oneToManyReadOnly');

if (!$application->isCurrentWindowRegistered())
{
	// ------------------------------------------------------------------
	// (1) Create our window and register it with the application
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
	// (2) Create our components
	//
	{
		$entries = new CC_Summary($oneToManySourceTable . '-entries', "select ID, $oneToManyDisplayColumn from $oneToManySourceTable", $oneToManySourceTable);
		$entries->setAllowView(true);
		$entries->setDefaultNumRowsPerPage(25);
		
		if (!$oneToManyReadOnly)
		{
			$entries->setAllowEdit(true);
			$entries->setAllowAdd(true);

			$contentProvider = new CC_OneToMany_Checkbox_Provider($oneToManySelectedIds);
			$entries->addColumn('Selected', $contentProvider);
			$entries->setAdditionalColumnsFirst(true);

			$cancelButton = new CC_Cancel_Button();
		}
		else
		{
			$contentProvider = new CC_OneToMany_Checkbox_Provider($oneToManySelectedIds, true);
			$entries->addColumn('Selected', $contentProvider);
			$entries->setAdditionalColumnsFirst(true);

			$cancelButton = new CC_Cancel_Button('Done');
		}

		$finishButton = new CC_Button('Finish');
	}

	
	// ------------------------------------------------------------------
	// (3) Create our handlers, filters, and content providers
	//
	{
		$finishButtonHandler = new CC_Update_OneToMany_Handler($oneToManyField, $contentProvider);
		$backHandler = new CC_Cancel_Button_Handler();
	}


	// ------------------------------------------------------------------
	// (4) Register our handlers, filters, and content providers
	//
	{
		$finishButton->registerHandler($finishButtonHandler);
		$finishButton->registerHandler($backHandler);
	}


	// ------------------------------------------------------------------
	// (5) Register the components with the window
	//
	$window->registerComponent($entries);
	$window->registerComponent($cancelButton);
	$window->registerComponent($finishButton);
	
	$window->setDefaultButton($finishButton);
}
else
{	
	$window = &$application->getCurrentWindow();

	$entries = &$window->getSummary($oneToManySourceTable . '-entries');
	$entries->update();

	if (!$oneToManyReadOnly)
	{
		$cancelButton = &$window->getButton('Cancel');
	}
	else
	{
		$cancelButton = &$window->getButton('Done');
	}
	$finishButton = &$window->getButton('Finish');
}

?>

<?php echo $window->getHeader(); ?>

<p>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<?php if (!$oneToManyReadOnly) { ?>
Pick your <?php echo $oneToManyLabel; ?> selections below, and then click on the 'Finish' button to return to the record you are currently editing.<p>
<?php } else { ?>
View the current <?php echo $oneToManyLabel; ?> below by clicking on the 'View' links. When you are done, click on the 'Done' button below.<p>
<?php } ?>

<table border="0" cellpadding="0" cellspacing="0"><tr><td bgcolor="<?php echo $ccContentBorderColour; ?>">
<table border="0" cellpadding="4" cellspacing="1" width="400">
 <tr bgcolor="<?php echo $ccTitleBarColour; ?>">
  <td class="ccSummaryHeadings">Managing <?php echo $oneToManyLabel; ?></td>
 </tr>

 <tr bgcolor="#ffffff">
  <td><?php echo $entries->getHTML(); ?></td>
 </tr>

 <tr bgcolor="<?php echo $ccButtonBarRowColour; ?>">
  <td align="right">
   <?php echo $cancelButton->getHTML(); ?> <?php if (!$oneToManyReadOnly) { echo $finishButton->getHTML(); } ?>
  </td>
 </tr>
 
</table>
</td></tr></table>

</div>

<?php echo $window->getFooter(); ?>
