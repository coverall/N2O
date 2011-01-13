<?php
// $Id: CC_Delete_Folder_File_Handler.php,v 1.2 2003/09/04 19:51:31 mike Exp $
//=======================================================================

/**
 * This CC_Delete_Folder_File_Handler deletes selected files in a CC_Folder_Component.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Delete_Folder_File_Handler extends CC_Action_Handler
{
	/**
	 * The folder component where files are selected to delete.
	 *
	 * @access private
	 * @var string $folderComponent
	 */

	var $folderComponent;
	

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Delete_Folder_File_Handler
	//-------------------------------------------------------------------

	/**
	 * This method sets the parameters of the handler for use in the process method.
	 *
	 * @access public
	 * @param CC_Folder_Component $folderComponent The folder component where a list of deletable files reside.
	 * @param string $fileName The path to the file to delete.
	 */

	function CC_Delete_Folder_File_Handler(&$folderComponent)
	{	
		$this->folderComponent = &$folderComponent;
				
		$this->CC_Action_Handler();
	}


	//-------------------------------------------------------------------
	// METHOD: process()
	//-------------------------------------------------------------------

	/**
	 * This method deletes the files which are selected in the Folder Component for deletion.
	 *
	 * @access public
	 */

	function process()
	{
		$arrayOfFiles = $this->folderComponent->getCheckedFiles();
		
		if ($arrayOfFiles !== false)
		{
			$sizeOfArray = sizeof($arrayOfFiles);
			
			for ($i = 0; $i < $sizeOfArray; $i++)
			{
				$file = $this->folderComponent->getFolder() . $arrayOfFiles[$i];

				//echo 'Deleting File... ' .  $file . '<hr>';

				if (file_exists($file))
				{
					unlink($file);
				}
				
			}
		}
		else
		{
			//echo 'Sorry no files to delete.';
		}
	}
}

?>