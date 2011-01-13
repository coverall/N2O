<?php
// $Id: CC_Remove_File_Confirmation_Window.php,v 1.9 2010/11/11 04:28:32 patrick Exp $
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

/*
$testButton = new CC_Button("Testing Button");
$testLink = new CC_Button("Testing Link", true);
*/

$cancelButton = new CC_Cancel_Button();
$cancelButton->setFieldUpdater(false);
$cancelButton->setValidateOnClick(false);

$deleteButton = new CC_Button("Delete", false);
$deleteButton->setFieldUpdater(false);
$deleteButton->setValidateOnClick(false);

$uploadField = &$application->getArgument("uploadFieldToRemove");

// ------------------------------------------------------------------
// (2) Create our handlers
//

// (!) what is $windows? -PG	

$deleteHandler = new CC_Remove_File_Handler($uploadField);
$backHandler = new CC_Cancel_Button_Handler();
$unregisterWindowHandler = new CC_Unregister_Window_Handler();


// ------------------------------------------------------------------
// (3) Register our handlers with our buttons
//
//$testButton->registerHandler($testHandler);
//$testLink->registerHandler($testHandler);

$deleteButton->registerHandler($deleteHandler);
$deleteButton->registerHandler($unregisterWindowHandler);
$deleteButton->registerHandler($backHandler);
$cancelButton->registerHandler($unregisterWindowHandler);

// ------------------------------------------------------------------
// (4) Register the CC_Record component with the application
//


// ------------------------------------------------------------------
// (5) Register the buttons with the window
//
//$window->registerComponent($testButton);
//$window->registerComponent($testLink);

$window->registerComponent($cancelButton);
$window->registerComponent($deleteButton);
$window->registerComponent($uploadField);
   

// ------------------------------------------------------------------
// (6) Register the window with the application
//

?>

<?php echo $window->getHeader(); ?>

<div class="ccContent" align="<?php echo $ccContentAlignment; ?>">

<table border="0" cellpadding="0" cellspacing="0"><tr><td bgcolor="<?php echo $ccContentBorderColour; ?>">
<table border="0" cellpadding="4" cellspacing="1">
 <tr bgcolor="<?php echo $ccTitleBarColour; ?>">
  <td colspan="2" class="ccSummaryHeadings">Are you sure you want to permanently delete the following file?</td>
 </tr>

 <tr>
  <td colspan="2" bgcolor="#cccccc" align=center><span class="small"><?php echo $uploadField->getViewHTML(); ?></span></td>
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
