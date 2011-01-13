<?php
// $Id: CC_View_Ordered_Record_Window.php,v 1.12 2004/08/19 04:25:26 patrick Exp $
if ($application->hasArgument('displayNameForViewOrdered'))
{
	$displayName = $application->getArgument('displayNameForViewOrdered');
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
		$window = &new $window_class();
	}
	else
	{
		$window = &new CC_Window();
	}
	$application->registerWindow($window);


	// ------------------------------------------------------------------
	// (1) Create our buttons
	//
	
	$doneButton = &new CC_Button("Done", false);
	$doneButton->setFieldUpdater(false);
	$doneButton->setValidateOnClick(false);
	
	
	// ------------------------------------------------------------------
	// (2) Create our handlers
	//
	
	$doneButtonHandler = &new CC_Cancel_Button_Handler();
	$unregisterWindowHandler = &new CC_Unregister_Window_Handler();
	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//
	
	$doneButton->registerHandler($doneButtonHandler);
	$doneButton->registerHandler($unregisterWindowHandler);
	
	
	// ------------------------------------------------------------------
	// (4) Register the CC_Record component with the application
	//

	$record = &new CC_Record(getFieldListFromTable($application->getArgument("tableNameForView"), array(ID)), $application->getArgument("tableNameForView"), false, $application->getArgument("viewRecordId"));
	
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
	if (!($window->isRecordRegistered($key)))
	{
		$record = &new CC_Record(getFieldListFromTable($application->getArgument("tableNameForView")), $application->getArgument("tableNameForView"), false, $application->getArgument("viewRecordId"));
	}
	else
	{
		$record = &$window->getRecordAtIndex($key, true);
	}
	
	$doneButton = &$window->getButton("Done");
}
?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<table border="0" cellpadding="0" cellspacing="0"><tr><td bgcolor="<?php echo $ccContentBorderColour; ?>">
<table border="0" cellpadding="4" cellspacing="1">
 <tr bgcolor="<?php echo $ccTitleBarColour; ?>">
  <td colspan="2" class="ccSummaryHeadings">Viewing <?php echo $displayName; ?> #</td>
 </tr>

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

	if (($keys[$i] != 'DATE_ADDED') && ($keys[$i] != 'LAST_MODIFIED') && ($keys[$i] != 'SORT_ID'))
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

 <tr bgcolor="<?php echo $ccButtonBarRowColour; ?>">
  <td colspan="2" align="right">
   <?php echo $doneButton->getHTML(); ?>
  </td>
 </tr>
 
</table>
</td></tr></table>

</div>

<?php echo $window->getFooter(); ?>
