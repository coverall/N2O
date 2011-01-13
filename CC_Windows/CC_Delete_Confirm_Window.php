<?php
// $Id: CC_Delete_Confirm_Window.php,v 1.18 2004/08/19 04:25:26 patrick Exp $
if ($application->hasArgument('displayNameForDelete'))
{
	$displayName = $application->getArgument('displayNameForDelete');
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
	$cancelButton = &new CC_Cancel_Button();
	$cancelButton->setFieldUpdater(false);
	$cancelButton->setValidateOnClick(false);
	
	$deleteButton = &new CC_Button("Delete", false);
	$deleteButton->setFieldUpdater(false);
	$deleteButton->setValidateOnClick(false);
	
	
	// ------------------------------------------------------------------
	// (2) Create our handlers
	//
	$deleteHandler = &new CC_Delete_Record_Handler($application->getArgument("tableNameForDelete"), $application->getArgument("deleteRecordId"));
	$unregisterWindowHandler = &new CC_Unregister_Window_Handler();
	
	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//
	$deleteButton->registerHandler($deleteHandler);
	$deleteButton->registerHandler($unregisterWindowHandler);
	$cancelButton->registerHandler($unregisterWindowHandler);

	// ------------------------------------------------------------------
	// (4) Register the CC_Record component with the application
	//
	$defaultDeleteRecord = &new CC_Record(getFieldListFromTable($application->getArgument("tableNameForDelete"), array('ID')), $application->getArgument("tableNameForDelete"), false, $application->getArgument("deleteRecordId"), $application->getArgument('idColumn'));
	
	
	// ------------------------------------------------------------------
	// (5) Register the buttons with the window
	//
	$window->registerComponent($cancelButton);
	$window->registerComponent($deleteButton);
	$window->registerComponent($defaultDeleteRecord);
		
}
else
{	
	$window = &$application->getWindow($application->getAction());


	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	
	if (!($window->isRecordRegisteredAtIndex(0)))
	{
		$defaultDeleteRecord = &new CC_Record(getFieldListFromTable($application->getArgument("tableNameForDelete")), $application->getArgument("tableNameForDelete"), false, $application->getArgument("deleteRecordId"), $application->getArgument('idColumn'));
	}
	else
	{
		$defaultDeleteRecord = &$window->getRecordAtIndex(0);
	}

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
  <td colspan="2" class="ccSummaryHeadings">Are you sure you want to delete the following <?php echo strtolower($displayName); ?>?</td>
 </tr>

 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordEvenRowColour ?>"><span class="small">Created:</span></td>
  <td bgcolor="<?php echo $ccRecordEvenRowColour ?>"><span class="small"><?php echo $defaultDeleteRecord->fields["DATE_ADDED"]->getHTML(); ?></span></td>
 </tr>

 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordOddRowColour ?>"><span class="small">Last Modified:</span></td>
  <td bgcolor="<?php echo $ccRecordOddRowColour ?>"><span class="small"><?php echo $defaultDeleteRecord->fields["LAST_MODIFIED"]->getHTML(); ?></span></td>
 </tr>


<?php 

$keys = array_keys($defaultDeleteRecord->fields);
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
		$field = &$defaultDeleteRecord->fields[$keys[$i]];
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
   <?php echo $cancelButton->getHTML(); ?> <?php echo $deleteButton->getHTML(); ?>
  </td>
 </tr>
 
</table>
</td></tr></table>

</div>

<?php echo $window->getFooter(); ?>
