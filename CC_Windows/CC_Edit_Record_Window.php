<?php
// $Id: CC_Edit_Record_Window.php,v 1.24 2010/11/11 04:28:32 patrick Exp $
if ($application->hasArgument('displayNameForEdit'))
{
	$displayName = $application->getArgument('displayNameForEdit');
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
	
	//echo "creating window!<bR>" . $application->getAction() . "<br>";
	
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
	// (1) Construct the CC_Record component
	//
	$record = new CC_Record(getFieldListFromTable($application->getArgument("tableNameForEdit"), array('ID')), $application->getArgument("tableNameForEdit"), true, $application->getArgument("editRecordId"), $application->getArgument('idColumn'));
	
	$record->fields['DATE_ADDED']->setReadOnly(true);
	
	
	// ------------------------------------------------------------------
	// (2) Create our buttons
	//
	
	$cancelButton = new CC_Cancel_Button();
	$updateButton = new CC_Button("Update");
	
	
	// ------------------------------------------------------------------
	// (3) Create our handlers
	//
	
	$updateButtonHandler = new CC_Update_Record_Handler($record);
	$cancelCleanupHandler = new CC_Cancel_Cleanup_Handler($window);
	
	// ------------------------------------------------------------------
	// (4) Register our handlers with our buttons
	//
	
	$updateButton->registerHandler($updateButtonHandler);

	$cancelButton->registerHandler($cancelCleanupHandler);


	// ------------------------------------------------------------------
	// (5) Register the components with the window
	//
	
	$window->registerComponent($cancelButton);
	$window->registerComponent($updateButton);
	$window->registerComponent($record);
	
	$window->setDefaultButton($updateButton);
	
	
	// ------------------------------------------------------------------
	// (6) Register the window with the application
	//	
}
else
{	
	$window = &$application->getWindow($application->getAction());


	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	$record = &$window->getRecordAtIndex(0, true);
	
	$cancelButton = &$window->getButton("Cancel");
	$updateButton = &$window->getButton("Update");
}
?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<h1 class="ccH1">Editing <?php echo $displayName; ?> #<?php echo $record->id; ?></h1>

<?php echo $window->getErrorMessage(); ?>

<table border="0" cellpadding="0" cellspacing="0" class="ccTable">
 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordEvenRowColour; ?>"><span class="small">Created:</span></td>
  <td bgcolor="<?php echo $ccRecordEvenRowColour; ?>"><span class="small"><?php echo $record->fields["DATE_ADDED"]->getHTML(); ?></span></td>
 </tr>

 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordOddRowColour; ?>"><span class="small">Last Modified:</span></td>
  <td bgcolor="<?php echo $ccRecordOddRowColour; ?>"><span class="small"><?php echo $record->fields["LAST_MODIFIED"]->getHTML(); ?></span></td>
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

	if (($keys[$i] != "DATE_ADDED") && ($keys[$i] != "LAST_MODIFIED"))
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
   <?php echo $cancelButton->getHTML(); ?> <?php echo $updateButton->getHTML(); ?>
  </td>
 </tr>
</table>

</div>

<?php echo $window->getFooter(); ?>
