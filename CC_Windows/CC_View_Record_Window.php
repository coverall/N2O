<?php
// $Id: CC_View_Record_Window.php,v 1.21 2010/11/11 04:28:32 patrick Exp $
if ($application->hasArgument('displayNameForView'))
{
	$displayName = $application->getArgument('displayNameForView');
}
else
{
	$displayName = 'Record';
}

if (!$application->isCurrentWindowRegistered())
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
	$window->setUnregisterOnLeave();
	$application->registerWindow($window);


	// ------------------------------------------------------------------
	// (1) Create our buttons
	//
	
	$doneButton = new CC_Button('Done', false);
	$doneButton->setFieldUpdater(false);
	$doneButton->setValidateOnClick(false);
	
	
	// ------------------------------------------------------------------
	// (2) Create our handlers
	//
	
	$doneButtonHandler = new CC_Cancel_Button_Handler();
	$unregisterWindowHandler = new CC_Unregister_Window_Handler();
	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//
	
	$doneButton->registerHandler($doneButtonHandler);
	$doneButton->registerHandler($unregisterWindowHandler);
	
	
	// ------------------------------------------------------------------
	// (4) Register the CC_Record component with the application
	//

	$record = new CC_Record(getFieldListFromTable($application->getArgument('tableNameForView'), array('ID')), $application->getArgument('tableNameForView'), false, $application->getArgument('viewRecordId'), $application->getArgument('idColumn'));
	
	//$window->registerComponent($record);
	
	
	// ------------------------------------------------------------------
	// (5) Register the components with the window
	//
	
	$window->registerComponent($doneButton);
	$window->registerComponent($record);
	
	
	// ------------------------------------------------------------------
	// (6) Register the window with the application
	//
	
}
else
{	
	$window = &$application->getWindow($application->getAction());


	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	
	if (!($window->isRecordRegisteredAtIndex(0)))
	{
		$record = new CC_Record(getFieldListFromTable($application->getArgument('tableNameForView')), $application->getArgument('tableNameForView'), false, $application->getArgument('viewRecordId'), $application->getArgument('idColumn'));
	}
	else
	{
		$record = &$window->getRecordAtIndex(0);
	}
	
	$doneButton = &$window->getButton('Done');
}
?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<h1 class="ccH1">Viewing <?php echo $displayName; ?> #<?php echo $record->id; ?></h1>

<table border="0" cellpadding="0" cellspacing="0" class="ccTable">
 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordEvenRowColour; ?>"><span class="small">Created:</span></td>
  <td bgcolor="<?php echo $ccRecordEvenRowColour; ?>"><span class="small"><?php echo $record->fields['DATE_ADDED']->getHTML(); ?></span></td>
 </tr>

 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordOddRowColour; ?>"><span class="small">Last Modified:</span></td>
  <td bgcolor="<?php echo $ccRecordOddRowColour; ?>"><span class="small"><?php echo $record->fields['LAST_MODIFIED']->getHTML(); ?></span></td>
 </tr>

<?php 

$keys = array_keys($record->fields);
$shaded = false;

for ($i = 0; $i < sizeof($keys); $i++)
{
	if ($shaded)
	{
		$rowColour = $ccRecordOddRowColour;
	}
	else
	{
		$rowColour = $ccRecordEvenRowColour;
	}

	if (($keys[$i] != 'DATE_ADDED') && ($keys[$i] != 'LAST_MODIFIED'))
	{
		$field = &$record->fields[$keys[$i]];
		echo ' <tr valign="top" bgcolor="' . $rowColour . '">' . "\n";
		echo '  <td align="right">' . $field->getLabel() . ':</td>' . "\n";
		echo '  <td>' . $field->getHTML() . '</td>' . "\n";
		echo ' </tr>' . "\n";
	
		unset($field);
		
		$shaded = !$shaded;
	}
}
?>

 <tr class="buttonRow">
  <td></td>
  <td>
   <?php echo $doneButton->getHTML(); ?>
  </td>
 </tr>
 
</table>

</div>

<?php echo $window->getFooter(); ?>
