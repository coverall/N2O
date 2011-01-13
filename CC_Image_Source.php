<?php
// $Id: CC_Image_Source.php,v 1.9 2003/06/28 20:10:04 jamie Exp $
require_once('CC_Utilities.php');

//=======================================================================
// FILE: CC_Image_Source
//=======================================================================

/**
 * CC_Image_Source is used to display inline images in CC_Index.
 * 
 * @package N2O
 * @access private
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 * @see CC_Index
 */

header('Content-Type: ' . URLValueDecode($imageType));
header('Content-Length: ' . $imageSize);
readfile(URLValueDecode($imagePath));

?>