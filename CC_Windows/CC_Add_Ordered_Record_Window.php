<?php
// $Id: CC_Add_Ordered_Record_Window.php,v 1.12 2010/11/11 04:28:32 patrick Exp $
if ($application->hasArgument('displayNameForAddOrdered'))
{
	$displayName = $application->getArgument('displayNameForAddOrdered');
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
	// (1) Construct the CC_Record component
	//	
	$record = new CC_Record(getEditableFieldListFromTable($application->getArgument('tableNameForAdd')), $application->getArgument('tableNameForAdd'), true);
	
	
	// ------------------------------------------------------------------
	// (2) Create our buttons
	//
	
	$cancelButton = new CC_Cancel_Button();
	$addButton = new CC_Button('Add');
	
	
	// ------------------------------------------------------------------
	// (3) Create our handlers
	//
	
	$addButtonHandler = new CC_Insert_Ordered_Record_Handler($record, $application->getArgument('tableNameForAdd'),  $application->getArgument('addPosition'));
	$unregisterWindowHandler = new CC_Unregister_Window_Handler();
	
	
	// ------------------------------------------------------------------
	// (4) Register our handlers with our buttons
	//
	
	$addButton->registerHandler($addButtonHandler);
	$addButton->registerHandler($unregisterWindowHandler);


	// ------------------------------------------------------------------
	// (5) Register the components with the window
	//
	
	$window->registerComponent($cancelButton);
	$window->registerComponent($addButton);
	$window->registerComponent($record);
	
	$window->setDefaultButton($addButton);
	
	
	// ------------------------------------------------------------------
	// (6) Register the window with the application
	//
	
}
else
{		
	$window = &$application->getCurrentWindow();


	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	
	$record = &$window->getRecordAtIndex(0);
	
	$cancelButton = &$window->getButton("Cancel");
	$addButton = &$window->getButton("Add");
}
?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<?php echo $window->getErrorMessage(); ?>

<table border="0" cellpadding="1" cellspacing="0"><tr><td bgcolor="<?php echo $ccContentBorderColour; ?>">
<table border="0" cellpadding="4" cellspacing="0">

 <tr bgcolor="<?php echo $ccTitleBarColour; ?>">
  <td colspan="2" class="ccSummaryHeadings">Adding New <?php echo $displayName; ?></td>
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
   <?php echo $cancelButton->getHTML(); ?> <?php echo $addButton->getHTML(); ?>
  </td>
 </tr>
 
</table>
</td></tr></table>

</div>

<?php echo $window->getFooter(); ?>
