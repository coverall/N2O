<?php
// $Id: _RequireOnceFiles.php,v 1.20 2005/02/14 01:09:37 patrick Exp $
require_once(CC_FRAMEWORK_PATH . '/CC_Utilities.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Database.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Component.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Application.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Summary.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Action_Handler.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Button.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Error.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Graph.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Pie_Chart.php');
require_once(CC_FRAMEWORK_PATH . '/CC_ErrorManager.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_FieldManager.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Record.php');
require_once(CC_FRAMEWORK_PATH . '/CC_RelationshipManager.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Summary_Content_Provider.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Summary_Filter.php');
require_once(CC_FRAMEWORK_PATH . '/CC_User.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Window.php');
require_once(CC_FRAMEWORK_PATH . '/CC_ZIP_File.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Image_Utilities.php');

// ----------------------------------------------------------------
// Use the Require-O-Matic (tm) to include all the files in the
// following folders...
//
require_once(CC_FRAMEWORK_PATH . '/CC_Fields/CC_Text_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Fields/CC_Multiple_Choice_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Fields/CC_SelectList_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Fields/CC_Date_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Fields/CC_DateTime_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Fields/CC_FloatNumber_Field.php');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Fields/');
require_once(CC_FRAMEWORK_PATH . '/CC_Handlers/CC_Insert_Record_Handler.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Handlers/CC_Remove_File_Handler.php');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Handlers/');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Handlers/CC_Multiple_Selections_Handlers/');
require_once(CC_FRAMEWORK_PATH . '/CC_Buttons/CC_Text_Button.php');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Buttons/');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Summaries/');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Summary_Filters/');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Summary_Content_Providers/');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Components/');
requireAllFilesInFolder(CC_FRAMEWORK_PATH . '/CC_Components/CC_Query_Additions/');

?>