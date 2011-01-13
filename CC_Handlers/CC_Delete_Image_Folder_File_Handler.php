<?php
// $Id: CC_Delete_Image_Folder_File_Handler.php,v 1.1 2004/03/04 22:04:34 mike Exp $
//=======================================================================

/**
 * This CC_Delete_Folder_File_Handler deletes selected files in a CC_Folder_Component.
 * 
 * @package CC_Action_Handlers
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Delete_Image_Folder_File_Handler extends CC_Delete_Folder_File_Handler
{
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
				
				$thumbnail = getThumbnail($file, 0, 0, false);
				
				if (file_exists($file))
				{
					unlink($file);
				}
				
				if (file_exists($thumbnail))
				{
					unlink($thumbnail);
				}
				
			}
		}
	}
}

?>