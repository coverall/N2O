<!-- $Id: CC_Manage_FK_Table_Window.php,v 1.18 2010/11/11 04:28:32 patrick Exp $ -->
<?php
//get the foreign key field name from the arguments array
$foreignKeyField = &$application->getArgument('foreignKeyField');
$foreignKeyName = $application->fieldManager->getDisplayName($foreignKeyField);

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
	// (0) Register the window with the application
	//

	$application->registerWindow($window);
	

	// ------------------------------------------------------------------
	// (1) Create our buttons
	//
	
	$doneButton = new CC_Cancel_Button('Done');

	
	// ------------------------------------------------------------------
	// (2) Create our handlers
	//
	
	//$doneHandler = new CC_Back_Button_Handler();

	
	// ------------------------------------------------------------------
	// (3) Register our handlers with our buttons
	//


	// ------------------------------------------------------------------
	// (4) Register the buttons with the window
	//
	
	$window->registerComponent($doneButton);
	//$window->registerComponent($addRecordButton);
	
	
	// ------------------------------------------------------------------
	// (5) Register the summary with the application
	//
		
	$FKSummary = new CC_Summary('FKSummary', 'select ID, ' . $application->relationshipManager->getDisplayColumn($foreignKeyField) . ' from ' . $application->relationshipManager->getRelatedTable($foreignKeyField), $application->relationshipManager->getRelatedTable($foreignKeyField), $application->relationshipManager->getDisplayColumn($foreignKeyField), true, true, true, true, 'CC_View_Record_Handler', 'CC_Edit_Record_Handler', 'CC_Delete_Confirm_Handler', 'CC_Add_FK_Record_Handler');

	$FKSummary->setShowIdColumn(false);
	$FKSummary->setAllowAdd(true);
	$FKSummary->setDisplayName($foreignKeyName);
	
	$textShorteningFilter = new CC_Text_Shortening_Filter(36);

	$FKSummary->registerFilter($application->relationshipManager->getDisplayColumn($foreignKeyField), $textShorteningFilter);

	// (!) we should let developers do this on construction because
	//     otherwise if we change it here, we have to call update()
	//     to re-sort it...
	//$FKSummary->sortByDirection    = "DESC";	// the background colour of odd rows
	//$FKSummary->update();
	
	$window->registerComponent($FKSummary);
}
else
{
	$window = &$application->getWindow($application->getAction());

	// (7) you must explicitly retrieve all the components in the window after
	// the objects have already been created (ie. anytime after the first access)
	
	//$FKSummary = &$window->getSummary("FKSummary");
	
	$FKSummary = &$window->getSummary('FKSummary');
	$FKSummary->update();
	
	$doneButton = $window->getButton('Done');
	//$addRecordButton = $window->getButton("Add New");
}

?>

<?php echo $window->getFooter(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<table width="600">
 <tr>
  <td>Below are all the available <?php echo $foreignKeyName;?> entries. You may view, edit, delete, or click on the 'Add New' (+) button to add a new <?php echo $foreignKeyName; ?>.</td>
 </tr>
</table>

<p>

<table border="0" cellpadding="1" cellspacing="0" class="ccTable">
 <tr>
  <td>
   <table border="0" cellpadding="4" cellspacing="0" width="600">
    <tr bgcolor="<?php echo $ccTitleBarColour; ?>">
     <td class="ccSummaryHeadings"><?php echo $foreignKeyName; ?> Entries</td>
    </tr>

    <tr bgcolor="<?php echo $ccContentBackgroundColour ?>">
     <td><?php echo $FKSummary->getHTML(); ?></td>
    </tr>

    <tr bgcolor="<?php echo $ccButtonBarRowColour; ?>">
     <td align="right"><?php echo $doneButton->getHTML(); ?></td>
    </tr>
 
   </table>
  </td>
 </tr>
</table>

<?php echo $window->getFooter(); ?>
