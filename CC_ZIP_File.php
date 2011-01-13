<?php
// $Id: CC_ZIP_File.php,v 1.5 2003/12/02 17:47:10 patrick Exp $
//=======================================================================
// CLASS: CC_Zip_File
//=======================================================================

/**
 * Zip file creation class.
 * Makes zip files.
 *
 * Based on :
 *
 *  http://www.zend.com/codex.php?id=535&single=1
 *  By Eric Mueller <eric@themepark.com>
 *
 *  http://www.zend.com/codex.php?id=470&single=1
 *  by Denis125 <webmaster@atlant.ru>
 *
 *  a patch from Peter Listiak <mlady@users.sourceforge.net> for last modified
 *  date and time of the compressed file
 *
 * Official ZIP file format: http://www.pkware.com/appnote.txt
 *
 * @access public
 */
 
class CC_ZIP_File
{
    /**
     * Array to store compressed data
     * @var  array    $datasec
     */
     
    var $datasec      = array();

    /**
     * Central directory
     * @var  array    $ctrl_dir
     */
     
    var $ctrl_dir     = array();

    /**
     * End of central directory record
     * @var string $eof_ctrl_dir
     */
     
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";

    /**
     * Last offset position
     * @var  integer  $old_offset
     */
     
    var $old_offset   = 0;
    
    /**
     * File size of the ZIPed file
     * @var  integer  $compressed_file_size
     */
     
    var $compressed_file_size   = 0;

	
	//-------------------------------------------------------------------
	// METHOD: addFolder()
	//-------------------------------------------------------------------

    /**
     * Adds "directory" to archive - do this before putting any files in directory. Then you can add files using addFile() with names like "path/file.txt" 
     *
     * @param  $name - name of directory. eg: "path/"
     * @access public
     */

	function addFolder($name) 
	{ 
		$name = str_replace("\\", "/", $name); 
		
		$fr = "\x50\x4b\x03\x04"; 
		$fr .= "\x0a\x00";// ver needed to extract 
		$fr .= "\x00\x00";// gen purpose bit flag 
		$fr .= "\x00\x00";// compression method 
		$fr .= "\x00\x00\x00\x00"; // last mod time and date 
		
		$fr .= pack("V",0); // crc32 
		$fr .= pack("V",0); //compressed filesize 
		$fr .= pack("V",0); //uncompressed filesize 
		$fr .= pack("v", strlen($name) ); //length of pathname 
		$fr .= pack("v", 0 ); //extra field length 
		$fr .= $name; 
		// end of "local file header" segment 
		
		// no "file data" segment for path 
		
		// "data descriptor" segment (optional but necessary if archive is not served as file) 
		$fr .= pack("V",$crc); //crc32 
		$fr .= pack("V",$c_len); //compressed filesize 
		$fr .= pack("V",$unc_len); //uncompressed filesize 
		
		// add this entry to array 
		$this -> datasec[] = $fr; 
		
		$new_offset = strlen(implode("", $this->datasec)); 
		
		// ext. file attributes mirrors MS-DOS directory attr byte, detailed 
		// at http://support.microsoft.com/support/kb/articles/Q125/0/19.asp 
		
		// now add to central record 
		$cdrec = "\x50\x4b\x01\x02"; 
		$cdrec .= "\x00\x00";// version made by 
		$cdrec .= "\x0a\x00";// version needed to extract 
		$cdrec .= "\x00\x00";// gen purpose bit flag 
		$cdrec .= "\x00\x00";// compression method 
		$cdrec .= "\x00\x00\x00\x00"; // last mod time & date 
		$cdrec .= pack("V", 0); // crc32 
		$cdrec .= pack("V", 0); //compressed filesize 
		$cdrec .= pack("V", 0); //uncompressed filesize 
		$cdrec .= pack("v", strlen($name)); //length of filename 
		$cdrec .= pack("v", 0); //extra field length 
		$cdrec .= pack("v", 0); //file comment length 
		$cdrec .= pack("v", 0); //disk number start 
		$cdrec .= pack("v", 0); //internal file attributes 
		$ext = "\x00\x00\x10\x00"; 
		$ext = "\xff\xff\xff\xff"; 
		$cdrec .= pack("V", 16); //external file attributes- 'directory' bit set 
		
		$cdrec .= pack("V", $this->old_offset); //relative offset of local header 
		$this->old_offset = $new_offset; 
		
		$cdrec .= $name; 
		// optional extra field, file comment goes here 
		// save to array 
		$this->ctrl_dir[] = $cdrec; 
	}


	//-------------------------------------------------------------------
	// METHOD: unix2DosTime()
	//-------------------------------------------------------------------
	
    /**
     * Converts an Unix timestamp to a four byte DOS date and time format (date
     * in high two bytes, time in low two bytes allowing magnitude comparison).
     *
     * @param  integer  the current Unix timestamp
     * @return integer  the current date in a four byte DOS format
     * @access private
     */
     
    function unix2DosTime($unixtime = 0)
    {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
        	$timearray['year']    = 1980;
        	$timearray['mon']     = 1;
        	$timearray['mday']    = 1;
        	$timearray['hours']   = 0;
        	$timearray['minutes'] = 0;
        	$timearray['seconds'] = 0;
        } // end if

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    } // end of the 'unix2DosTime()' method

	
	//-------------------------------------------------------------------
	// METHOD: addFile()
	//-------------------------------------------------------------------
	
    /**
     * Adds "file" to archive
     *
     * @param string file contents
     * @param string name of the file in the archive (may contains the path)
     * @param integer the current timestamp
     *
     * @access public
     */
     
    function addFile($data, $name, $time = 0)
    {
        $name     = str_replace('\\', '/', $name);

        $dtime    = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr   = "\x50\x4b\x03\x04";
        $fr   .= "\x14\x00";            // ver needed to extract
        $fr   .= "\x00\x00";            // gen purpose bit flag
        $fr   .= "\x08\x00";            // compression method
        $fr   .= $hexdtime;             // last mod time and date

        // "local file header" segment
        $unc_len = strlen($data);
        $crc     = crc32($data);
		$zdata   = gzcompress($data);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
        $c_len   = strlen($zdata);        
        $fr      .= pack('V', $crc);             // crc32
        $fr      .= pack('V', $c_len);           // compressed filesize
        $fr      .= pack('V', $unc_len);         // uncompressed filesize
        $fr      .= pack('v', strlen($name));    // length of filename
        $fr      .= pack('v', 0);                // extra field length
        $fr      .= $name;

        // "file data" segment
        $fr .= $zdata;

        // "data descriptor" segment (optional but necessary if archive is not
        // served as file)
        $fr .= pack('V', $crc);                 // crc32
        $fr .= pack('V', $c_len);               // compressed filesize
        $fr .= pack('V', $unc_len);             // uncompressed filesize

        // add this entry to array
        $this -> datasec[] = $fr;
        $new_offset        = strlen(implode('', $this->datasec));

        // now add to central directory record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";                // version made by
        $cdrec .= "\x14\x00";                // version needed to extract
        $cdrec .= "\x00\x00";                // gen purpose bit flag
        $cdrec .= "\x08\x00";                // compression method
        $cdrec .= $hexdtime;                 // last mod time & date
        $cdrec .= pack('V', $crc);           // crc32
        $cdrec .= pack('V', $c_len);         // compressed filesize
        $cdrec .= pack('V', $unc_len);       // uncompressed filesize
        $cdrec .= pack('v', strlen($name) ); // length of filename
        $cdrec .= pack('v', 0 );             // extra field length
        $cdrec .= pack('v', 0 );             // file comment length
        $cdrec .= pack('v', 0 );             // disk number start
        $cdrec .= pack('v', 0 );             // internal file attributes
        $cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set

        $cdrec .= pack('V', $this->old_offset); // relative offset of local header
        $this->old_offset = $new_offset;

        $cdrec .= $name;
        
        //store this information to add to the headers for downloading
        $this->compressed_file_size = $c_len;

        // optional extra field, file comment goes here
        // save to central directory
        $this -> ctrl_dir[] = $cdrec;
    } // end of the 'addFile()' method

	
	//-------------------------------------------------------------------
	// METHOD: getFile()
	//-------------------------------------------------------------------
	
    /**
     * Dumps out file
     *
     * @return  string  the zipped file
     * @access public
     */
     
    function getFile()
    {
        $data    = implode('', $this -> datasec);
        $ctrldir = implode('', $this -> ctrl_dir);

        return
            $data .
            $ctrldir .
            $this -> eof_ctrl_dir .
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
            pack('V', strlen($ctrldir)) .           // size of central dir
            pack('V', strlen($data)) .              // offset to start of central dir
            "\x00\x00";                             // .zip file comment length
    } // end of the 'file()' method

} // end of the 'zipfile' class
?>
