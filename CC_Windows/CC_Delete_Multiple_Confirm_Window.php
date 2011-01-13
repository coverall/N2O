<?php
// $Id: CC_Delete_Multiple_Confirm_Window.php,v 1.10 2010/11/11 04:28:32 patrick Exp $
if ($application->hasArgument('displayNameForDeleteMultiple'))
{
	$displayName = $application->getArgument('displayNameForDeleteMultiple');
}
else
{
	$displayName = 'Record';
}

if (!$application->isWindowRegistered($application->getAction()))
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

	// ------------------------------------------------------------------
	// (1) Create our buttons
	//
	
	$cancelButton = new CC_Cancel_Button();
	$cancelButton->setFieldUpdater(false);
	$cancelButton->setValidateOnClick(false);
	
	$deleteButton = new CC_Button("Delete", false);
	$deleteButton->setFieldUpdater(false);
	$deleteButton->setValidateOnClick(false);
	
	
	// ------------------------------------------------------------------
	// (2) Create our handlers
	//

	// (!) what is $windows? -PG	
	
	$deleteHandler = new CC_Delete_Multiple_Records_Handler($application->getArgument('tableNameForDeleteMultiple'), $application->getObject('recordsForDeleteMultiple'));
	$unregisterWindowHandler = new CC_Unregister_Window_Handler();
	
	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//
	$deleteButton->registerHandler($deleteHandler);
	$deleteButton->registerHandler($unregisterWindowHandler);
	$cancelButton->registerHandler($unregisterWindowHandler);
	
	// ------------------------------------------------------------------
	// (4) Register the buttons with the window
	//
	
	$window->registerComponent($cancelButton);
	$window->registerComponent($deleteButton);	
	
	// ------------------------------------------------------------------
	// (5) Register the window with the application
	//
	
	$application->registerWindow($window);
}
else
{	
	$window = &$application->getWindow($application->getAction());

	// (6) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
		
	$cancelButton = &$window->getButton("Cancel");
	$deleteButton = &$window->getButton("Delete");
}

?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<?php echo $window->getErrorMessage(); ?>

<table border="0" cellpadding="0" cellspacing="0"><tr><td bgcolor="<?php echo $ccContentBorderColour; ?>">
<table border="0" cellpadding="4" cellspacing="1">
 <tr bgcolor="<?php echo $ccTitleBarColour; ?>">
  <td colspan="2" class="ccSummaryHeadings">
  
  <?php
  	$recordsToDelete = &$application->getObject('recordsForDeleteMultiple');
  	
  	echo 'Are you sure you want to delete ' . strtolower($displayName);
  	
  	if (sizeof($recordsToDelete) > 2)
  	{
 		echo ' ';
 		
		for ($j = 0; $j < sizeof($recordsToDelete); $j++)
		{
			if ($j != (sizeof($recordsToDelete) - 1))
			{
				echo $recordsToDelete[$j]['ID'] . ', ';
			}
			else
			{
				echo 'and ' . $recordsToDelete[$j]['ID'];
			}
		}

 	}
 	else if (sizeof($recordsToDelete) == 2)
 	{
 		echo ' ' . $recordsToDelete[0]['ID'] . ' and ' . $recordsToDelete[1]['ID'];
 	}
 	else
 	{
 		echo ' ' . $recordsToDelete[0]['ID'];
 	}
 	 	
 	echo '?';
  ?>
  
  </td>
 </tr>

 <tr bgcolor="<?php echo $ccButtonBarRowColour; ?>">
  <td colspan="2" align="right">
   <?php echo $cancelButton->getHTML(); ?> <?php echo $deleteButton->getHTML(); ?>
  </td>
 </tr>
 
</table>
</td></tr></table>

</div>

<?php echo $window->getFooter(); ?>
