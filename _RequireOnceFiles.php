<?php
// $Id: _RequireOnceFiles.php,v 1.21 2005/05/11 00:31:51 patrick Exp $
require_once(CC_FRAMEWORK_PATH . '/CC_Utilities.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Database.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Component.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Application.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Action_Handler.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Button.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Error.php');
require_once(CC_FRAMEWORK_PATH . '/CC_ErrorManager.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Field.php');
require_once(CC_FRAMEWORK_PATH . '/CC_FieldManager.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Record.php');
require_once(CC_FRAMEWORK_PATH . '/CC_RelationshipManager.php');
require_once(CC_FRAMEWORK_PATH . '/CC_User.php');
require_once(CC_FRAMEWORK_PATH . '/CC_Window.php');

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

?>