<?php
// $Id: CC_Delete_Ordered_Confirm_Window.php,v 1.13 2010/11/11 04:28:32 patrick Exp $
if ($application->hasArgument('displayNameForDeleteOrdered'))
{
	$displayName = $application->getArgument('displayNameForDeleteOrdered');
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
	$application->registerWindow($window);


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
	$deleteHandler = new CC_Delete_Ordered_Record_Handler($application->getArgument('tableNameForDelete'), $application->getArgument('deleteRecordId'),$application->getArgument('deleteSortId'));
	$unregisterWindowHandler = new CC_Unregister_Window_Handler();
	
	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//
	$deleteButton->registerHandler($deleteHandler);
	$deleteButton->registerHandler($unregisterWindowHandler);
	$cancelButton->registerHandler($unregisterWindowHandler);


	// ------------------------------------------------------------------
	// (4) Register the CC_Record component with the application
	//
	$defaultDeleteRecord = new CC_Record(getFieldListFromTable($application->getArgument('tableNameForDelete'), array('ID')), $application->getArgument('tableNameForDelete'), false, $application->getArgument('deleteRecordId'));
	
	
	// ------------------------------------------------------------------
	// (5) Register the buttons with the window
	//
	$window->registerComponent($cancelButton);
	$window->registerComponent($deleteButton);
	$window->registerComponent($defaultDeleteRecord);
	
	
	// ------------------------------------------------------------------
	// (6) Register the window with the application
	//
	
}
else
{	
	$window = &$application->getWindow($application->getAction());


	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	
	if (!($window->isRecordRegisteredAtIndex($index)))
	{
		$defaultDeleteRecord = new CC_Record(getFieldListFromTable($application->getArgument("tableNameForDelete")), $application->getArgument("tableNameForDelete"), false, $application->getArgument("deleteRecordId"));
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
  <td align="right" bgcolor="<?php echo $ccRecordEvenRowColour; ?>"><span class="small">Created:</span></td>
  <td bgcolor="<?php echo $ccRecordEvenRowColour; ?>"><span class="small"><?php echo $defaultDeleteRecord->fields['DATE_ADDED']->getHTML(); ?></span></td>
 </tr>

 <tr>
  <td align="right" bgcolor="<?php echo $ccRecordOddRowColour; ?>"><span class="small">Last Modified:</span></td>
  <td bgcolor="<?php echo $ccRecordOddRowColour; ?>"><span class="small"><?php echo $defaultDeleteRecord->fields['LAST_MODIFIED']->getHTML(); ?></span></td>
 </tr>


<?php 

$keys = array_keys($defaultDeleteRecord->fields);

for ($i = 0; $i < sizeof($keys); $i++)
{
	if ($i % 2 == 0)
	{
		$rowColour = $ccRecordOddRowColour;
	}
	else
	{
		$rowColour = $ccRecordEvenRowColour;
	}

	if (($keys[$i] != 'DATE_ADDED') && ($keys[$i] != 'LAST_MODIFIED') && ($keys[$i] != 'SORT_ID'))
	{
		$field = &$defaultDeleteRecord->fields[$keys[$i]];
		echo ' <tr valign="top" bgcolor="' . $rowColour . '">' . "\n";
		echo '  <td align="right">' . $field->getLabel() . ':</td>' . "\n";
		echo '  <td>' . $field->getHTML() . '</td>' . "\n";
		echo ' </tr>' . "\n";

		unset($field);
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
