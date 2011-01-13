<!-- $Id: CC_Add_Record_Window.php,v 1.19 2010/11/11 04:28:32 patrick Exp $ -->
<?php
if ($application->hasArgument('displayNameForAdd'))
{
	$displayName = $application->getArgument('displayNameForAdd');
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
	//$window->setUnregisterOnLeave();
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
	
	$addButtonHandler = new CC_Insert_Record_Handler($record);
	
	
	// ------------------------------------------------------------------
	// (4) Register our handlers with our buttons
	//
	
	$addButton->registerHandler($addButtonHandler);


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
	
	$cancelButton = &$window->getButton('Cancel');
	$addButton = &$window->getButton('Add');
}
?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<h1 class="ccH1">Adding New <?php echo $displayName; ?></h1>

<?php echo $window->getErrorMessage(); ?>

<table border="0" cellpadding="0" cellspacing="0" class="ccTable">

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
   <?php echo $cancelButton->getHTML(); ?> <?php echo $addButton->getHTML(); ?>
  </td>
 </tr>
 
</table>

</div>

<?php echo $window->getFooter(); ?>
