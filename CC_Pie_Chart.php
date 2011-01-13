<?php
// $Id: CC_Pie_Chart.php,v 1.11 2010/11/11 04:28:32 patrick Exp $
//=======================================================================
// CLASS: CC_Pie_Chart
//=======================================================================

/**
 * This class handles drawing pie charts in N2O.
 *
 * <p><i>Example:</i>
 *
 * <p><pre>
 *  $piechart = new CC_Pie_Chart('Favourite Types of Pie', 500, 200, true);
 *
 *  // Note that these values don't have to add up to 100. CC_Pie_Chart
 *  // calculates percentages based on the total sum.
 *  $data = array();
 *  $data[] = array(45, 'Apple');
 *  $data[] = array(14, 'Blueberry');
 *  $data[] = array(16, 'Cherry');
 *  $data[] = array(13, 'Lemon Meringue');
 *  $data[] = array(10, 'Rhubarb');
 *  $data[] = array(2, 'Saskatoon-berry');
 *
 *  $piechart->setData($data);
 *
 *  // render the image and send it out as a PNG file
 *  $piechart->streamImage();
 *
 * </pre>
 * 
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Pie_Chart
{
	/**
     * A 2D array of the graph's data. The first element represents the value (which do not
     * necessarily collectively add up to 100), the second represents the text to use for 
     * the item in the legend.
     *
     * @var array $data A two dimensional array of graph data.
     * @access private
     */	

	var $data;
		
	
	/**
     * The pixel width of the total graph image.
     *
     * @var int $width
     * @access private
     */	

	var $height;
	
	
	/**
     * The pixel height of the total graph image.
     *
     * @var int $height
     * @access private
     */	

	var $width;
	
	
	/**
     * The pixel width of the pie itself.
     *
     * @var int $xsize
     * @access private
     */	

	var $xsize;
	
	
	/**
     * The pixel height of the pie itself.
     *
     * @var int $ysize
     * @access private
     */	

	var $ysize;
	
	
	/**
     * The x coordinate of the chart's center.
     *
     * @var int $centerX
     * @access private
     */	

	var $centerX;
	
	
	/**
     * The y coordinate of the chart's center.
     *
     * @var int $centerY
     * @access private
     */	

	var $centerY;
	
	
	/**
     * The title of this graph.
     *
     * @var string $title
     * @access private
     */	

	var $title;	
	
	
	/**
     * The subtitle of this graph.
     *
     * @var string $subTitle
     * @access private
     */	

	var $subTitle;


	/**
     * The path to the image.
     *
     * @var string $this->imagePath
     * @access private
     */	

	var $imagePath = '/tmp/';
	var $applicationPath = '';
	var $serverPath = '';
	

	/**
     * The font size for the graph legend.
     *
     * @var int $fontSize
     * @access private
     */	
     
	var $fontSize;


	/**
     * Boolean to control the displaying of the title.
     *
     * @var boolean $showTitle
     * @access private
     */	
	
	var $showTitle;


	/**
     * Boolean to control transparency of background.
     *
     * @var boolean $transparency
     * @access private
     */	

	var $transparency = false;


	/**
     * Transparency level percentage. Only used if transparency is true.
     *
     * @var int $transparencyLevel
     * @access private
     */	
	
	var $transparencyLevel = 100;


	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Pie_Chart
	//-------------------------------------------------------------------

	/**
	 * Constructs a CC_Pie_Chart.
	 *
	 * @param $title string The title of the pie chart.
	 * @param $width integer The width in pixels of the pie chart.
	 * @param $height integer The height in pixels of the pie chart.
	 * @param $showTitle boolean Toggles the display of the title.
	 *
	 * @access public
	 */

	function CC_Pie_Chart($title = '', $width = 512, $height = 300, $showTitle = false)
	{		
		$this->applicationPath = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'));
		$this->serverPath = substr($this->applicationPath, 0, strrpos($this->applicationPath, '/'));
		
		$pieSize = ($height > $width) ? $width - 50 : $height - 50;
		
		$this->xsize = $pieSize;
		$this->ysize = $pieSize;

		$this->width = $width;
		$this->height = $height;
		
		$this->centerX = intval($width/4) - 42;
		$this->centerY = intval($height/2);
		
		$this->fontSize = 3;
		
		$this->title = $title;
		$this->setShowTitle($showTitle);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setData
	//-------------------------------------------------------------------

	/**
	 * This method accepts a two-dimensional array of data, where the second dimension contains two elements: the first being the value; the second being the label to use. The values don't have to add up to 100, as the CC_Pie_Chart calculates percentages based on the total sum.
	 *
	 * <i>Example:</i>
	 *
	 * <pre>$array = array(array(40, 'Blue Eyes'), array(23, 'Green Eyes'), array(55, 'Brown'), array(15, 'Hazel');</pre>
	 *
	 * @access public
	 * @param array An array of arrays.
	 */
	 
	function setData($data)
	{
		$this->data = $data;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setShowTitle
	//-------------------------------------------------------------------

	/**
	 * This method allows you to set whether or not the title will be displayed in the graph. Some people may want to show the title outside of the image in HTML.
	 *
	 * @access public
	 * @param $showTitle boolean Toggles the display of the title.
 	 *
	 */

	function setShowTitle($showTitle)
	{				
		$this->showTitle = $showTitle;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setGraphColours
	//-------------------------------------------------------------------

	/**
	 * This method sets the graph's colours.
	 *
	 * @access private
	 */

	function setGraphColours()
	{				
		$this->graphColours[0] = imageColorAllocate($this->image, 0xed, 0x1b, 0x23);
		$this->graphColours[1] = imageColorAllocate($this->image, 0xf7, 0x94, 0x1c);
		$this->graphColours[2] = imageColorAllocate($this->image, 0xff, 0xf2, 0x00);
		$this->graphColours[3] = imageColorAllocate($this->image, 0x00, 0xa6, 0x50);
		$this->graphColours[4] = imageColorAllocate($this->image, 0x00, 0x54, 0xa6);
		$this->graphColours[5] = imageColorAllocate($this->image, 0x91, 0x26, 0x8f);
		$this->graphColours[6] = imageColorAllocate($this->image, 0xe6, 0x00, 0x8c);
	}


	//-------------------------------------------------------------------
	// METHOD: setTransparency
	//-------------------------------------------------------------------

	/**
	 * This method sets whether or not the background of the graph will be transparent.
	 *
	 * @see setTransparencyLevel
	 * @access public
	 */

	function setTransparency($transparency)
	{
		$this->transparency = $transparency;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setTransparencyLevel
	//-------------------------------------------------------------------

	/**
	 * This method sets the level of transparency of the image's background. You pass in a percentage value where 0 is fully opaque and 100 is fully transparent. This only works if you have call setTransparency(true).
	 *
	 * @see setTransparency
	 * @access public
	 */

	function setTransparencyLevel($level)
	{
		$this->transparencyLevel = $level;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: streamImage
	//-------------------------------------------------------------------

	/**
	 * This method streams the image in PNG format. It sets the appropriate Content-Type and Content-Length headers, outputs the image, and ends the PHP script execution by calling exit().
	 *
	 * @access public
	 */

	function streamImage()
	{				
		// Create the initial image
		$this->image = imagecreatetruecolor($this->width, $this->height);
		
		//set the graph colours
		$this->setGraphColours();
				
		$this->drawPie();
		
		$imagePath = tempnam($this->imagePath, get_class($this));
		
		// generate the image
		imagepng($this->image, $imagePath);
		
		//stream the image
		header('Content-Type: image/png');
		header('Content-Length: ' . filesize($imagePath));
		readfile($imagePath);
		
		// clean up
    	imagedestroy($this->image);
    	unlink($imagePath);
    	exit();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: output
	//-------------------------------------------------------------------

	/**
	 * Does all the magical drawing...
	 *
	 * @access private
	 */
	 
	 function output()
	 {
	 	$this->streamImage();
	 }
	 
	 
	//-------------------------------------------------------------------
	// METHOD: drawPie
	//-------------------------------------------------------------------

	/**
	 * Does all the magical drawing...
	 *
	 * @access private
	 */
	
	function drawPie()
	{	
		if ($this->transparency)
		{
			$white   = imageColorAllocateAlpha($this->image, 255, 255, 255, round(127 * ($this->transparencyLevel / 100)));
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
		}
		else
		{
			$white   = imageColorAllocate($this->image, 255, 255, 255);
		}
		$black   = imageColorAllocate($this->image, 0, 0, 0);

		$legendBoxSize = 10;
		$spaceBetweenLegendBoxes = 10;
		
		$numSlices = sizeof($this->data);
		
		$spaceNeeded = ($legendBoxSize + $spaceBetweenLegendBoxes) * $numSlices;
		
		$y = intval(($this->height - $spaceNeeded) / 2);
		
		// figure out the total value here
		$total = 0;
		
		for ($j = 0; $j < $numSlices; $j++)
		{
			$total += $this->data[$j][0];
		}
		
		$percent = 0;
		$thisPercent = 0;
		$lastPercent = 0;

		imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $white);
		
		for ($i = 0; $i < sizeof($this->data); $i++)  // run through data array
		{
			$lastPercent = $thisPercent;
			
			if ($total == 0)
			{
				$percent = 1 / sizeof($this->data);
			}
			else
			{
				$percent = $this->data[$i][0] / $total;
			}
			
			$thisPercent = $lastPercent + $percent;
			
			imagefilledarc($this->image, $this->centerX, $this->centerY, $this->xsize, $this->ysize, $lastPercent * 360, $thisPercent * 360, $this->graphColours[$i], IMG_ARC_PIE);
			
			$x = $this->width/2 - 63;
			
			// add the squares
			imagefilledrectangle($this->image, $x, $y + 2, $x + $legendBoxSize, $y + $legendBoxSize + 2, $this->graphColours[$i]);
			imagerectangle($this->image, $x, $y + 2, $x + $legendBoxSize, $y + $legendBoxSize + 2, $black);
			
			$buffer = sprintf("%s (%d, %.1f%%)", $this->data[$i][1], $this->data[$i][0], $percent * 100);
			imageString($this->image, $this->fontSize, $x + 20, $y, $buffer, $black);
			
			$y += $legendBoxSize + $spaceBetweenLegendBoxes;
		}
		
		// Do the circle...
		imageArc($this->image, $this->centerX, $this->centerY, $this->xsize, $this->ysize, 0, 360, $black);

		// Title
		if ($this->showTitle)
		{
			$titleFont = $this->fontSize + 2;
	
			imageString($this->image, $titleFont, ($this->width / 2) - (imageFontWidth($titleFont) * strlen($this->title) / 2), 0, $this->title, $black);
		}
	}
}

?>