<?php
// $Id: CC_Graph.php,v 1.29 2005/12/09 19:04:35 jamie Exp $
//=======================================================================
// CLASS: CC_Graph
//=======================================================================

/**
 * This class handles drawing graphs in N2O.
 *
 * @package N2O
 * @access public
 * @author The Crew <N2O@coverallcrew.com>
 * @copyright Copyright &copy; 2003, Coverall Crew
 */

class CC_Graph
{
	/**
     * A 3-D array of the graph's domain and range. There can be multiple data sets, depending on how many lines we are representing on one graph. The graph is presented on a 2 demensional grid.
     * 
     * @var array $data A three dimensional array of graph data.
     * @access private
     */	

	var $data;
	
	
	/**
     * The margin for the x axis.
     *
     * @var int $leftMargin
     * @access private
     */	

	var $leftMargin = 63;
	
	
	/**
     * The margin for the x axis.
     *
     * @var int $leftMargin
     * @access private
     */	

	var $rightMargin = 54;
	
	
	/**
     * The margin for the y axis.
     *
     * @var int $bottomMargin
     * @access private
     */	

	var $bottomMargin = 50;
	
	/**
     * The margin for the y axis.
     *
     * @var int $topMargin
     * @access private
     */	

	var $topMargin = 50;
	
	
	/**
     * The pixel width of this graph.
     *
     * @var int $width
     * @access private
     */	

	var $height;
	
	
	/**
     * The pixel width of this graph.
     *
     * @var int $width
     * @access private
     */	

	var $width;
	

	/**
     * The number of gradations on the X-axis.
     *
     * @var int $numXGradations
     * @access private
     */	

	var $numXGradations = 10;
	
	
	/**
     * The number of gradations on the Y-axis.
     *
     * @var int $numYGradations
     * @access private
     */	

	var $numYGradations = 20;
	
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
     * The title for this graph's x-axis.
     *
     * @var string $xTitle
     * @access private
     */	

	var $xTitle = 'domain';


	/**
     * The title for this graph's y-axis.
     *
     * @var string $yTitle
     * @access private
     */	

	var $yTitle = 'range';
	
	
	/**
     * The domain conversion factor to pixels.
     *
     * @var float $XScale
     * @access private
     */	

	var $XScale;


	/**
     * The range conversion factor to pixels.
     *
     * @var float $YScale
     * @access private
     */	

	var $YScale;
	
	
	/**
     * The domain conversion factor to pixels.
     *
     * @var float $XScale
     * @access private
     */	

	var $XGradationLabelFrequency = 2;


	/**
     * The range conversion factor to pixels.
     *
     * @var float $YScale
     * @access private
     */	

	var $YGradationLabelFrequency = 3;
	
	
	/**
     * The domain conversion factor to pixels.
     *
     * @var float $XScale
     * @access private
     */	

	var $startDomain;


	/**
     * The range conversion factor to pixels.
     *
     * @var float $YScale
     * @access private
     */	

	var $startRange;
	
	
	/**
     * The domain conversion factor to pixels.
     *
     * @var float $XScale
     * @access private
     */	

	var $endDomain;


	/**
     * The range conversion factor to pixels.
     *
     * @var float $YScale
     * @access private
     */	

	var $endRange;
	
	
	/**
     * The path to the image for this graph's y-axis.
     *
     * @var string $this->imageagePath
     * @access private
     */	

	var $imagePath = '/tmp/';
	var $applicationPath = '';
	var $serverPath = '';
	
	/**
     * The pixel width of the bars in the bar graph.
     *
     * @var string $barWidth
     * @access private
     */	

	var $barWidth = 5;
	
	
	/**
     * The field in the database table to use as the X-AXIS
     *
     * @var string $barWidth
     * @access private
     */	

	var $xField = 'DATE_ADDED';
	
	
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


	/**
     * The colour definitions.
     *
     * @var string $red
     * @access private
     */	
     
	var $white;
	var $black;
	var $red;
	var $green;
	var $blue;
	
	//the customizable colours
	var $imageBackGroundColour;
	var $graphBackGroundColour;
	var $xGridColour;
	var $yGridColour;
	var $titleColour;
	var $xTitleColour;
	var $yTitleColour;
	var $xMainGradationColour;
	var $yMainGradationColour;
	var $xSmallGradationColour;
	var $ySmallGradationColour;
	var $xGradationLabelsColour;
	var $yGradationLabelsColour;
	var $borderColour;
	var $timestampColour;
	
	var $lineGraphLineThickness = 1;
	var $xGridThickness = 1;
	var $yGridThickness = 1;
	
	var $includeXGrid = true;
	var $includeYGrid = true;
	
	var $includeWatermark = false;
	var $watermarkPath = '';
	
	var $showTimestamp = false;

	//-------------------------------------------------------------------
	// CONSTRUCTOR: CC_Graph
	//-------------------------------------------------------------------

	/**
	 * This constructor instantiates all given fields and, if the record already exists in the database (ie. it has a valid id), the field values are set to those in the database. All fields must appear in the given table as well as be defined in the CC_FIELDS table so that N2O knows what type of N2O fields they are.
	 *
	 * @access public
	 */


	function CC_Graph($startDomain, $endDomain, $startRange, $endRange, $width, $height)
	{
		$this->applicationPath = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'));
		$this->serverPath = substr($this->applicationPath, 0, strrpos($this->applicationPath, '/'));
	
		$this->width = $width;
		$this->height = $height;
		
		$this->startDomain = $startDomain;
		$this->startRange = $startRange;
		
		$this->endDomain = $endDomain;
		$this->endRange = $endRange;
		
		// Create the initial image
		$this->image = imagecreate($this->width, $this->height);
		//imageantialias($this->image, true);
		
		$this->white = imagecolorallocate($this->image, 0xFF, 0xFF, 0xFF);
		$this->grey  = imagecolorallocate($this->image, 0x66, 0x66, 0x66);
		$this->lightgrey  = imagecolorallocate($this->image, 0xCC, 0xCC, 0xCC);
		$this->black = imagecolorallocate($this->image, 0x00, 0x00, 0x00);
		$this->red   = imagecolorallocate($this->image, 0xFF, 0x00, 0x00);
		$this->green = imagecolorallocate($this->image, 0x00, 0xFF, 0x00);
		$this->blue  = imagecolorallocate($this->image, 0x00, 0x00, 0xFF);
		
		// Set the default colours
		$this->imageBackGroundColour = $this->white;
		$this->graphBackGroundColour = $this->white;
		$this->xGridColour = $this->black;
		$this->yGridColour = $this->black;
		$this->titleColour = $this->black;
		$this->yTitleColour = $this->black;
		$this->xTitleColour = $this->black;
		$this->xMainGradationColour = $this->black;
		$this->yMainGradationColour = $this->black;
		$this->xSmallGradationColour = $this->black;
		$this->ySmallGradationColour = $this->black;
		$this->xGradationLabelsColour = $this->black;
		$this->yGradationLabelsColour = $this->black;
		$this->borderColour = $this->black;
		$this->timestampColour = $this->black;
		
		$this->setScale();
   	}
   	
	
	//-------------------------------------------------------------------
	// METHOD: addLineFunction
	//-------------------------------------------------------------------

	/**
	 * This method adds a line function to the graph.
	 *
	 * @access public
	 */
	 
	function addLineFunction($tableName, $title, $colour, $whereClause = '')
	{
		global $application;
		
		$data = array();
		
		$query = 'select ' . $this->xField . ', ' . $this->yField . ' from ' . $tableName;
		
		if (strlen($whereClause) > 0)
		{
			$query .= " where $whereClause";
		}
		
		$query .= ' order by ' . $this->xField;
		
		$results = $application->db->doSelect($query);
		
		$count = 0;
		
		while (($resultData = cc_fetch_row($results)) != false)
		{
			$x = $this->convertXData($resultData[0]);
			$y = $this->convertYData($resultData[1]);
			
			// make sure we have a function (1 to 1) and not a relation (many to 1)
			if ($x != $data[$count - 1][0])
			{
				$data[$count++] = array($x, $y);
			}
		}
		
		$this->drawLineFunction($data, $title, $colour);
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
	// METHOD: imagelinethick
	//-------------------------------------------------------------------
	
	function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1) 
	{
		/* this way it works well only for orthogonal lines imagesetthickness($image, $thick);
		return imageline($image, $x1, $y1, $x2, $y2, $color);
		*/
		
		if ($thick == 1)
		{
			return imageline($image, $x1, $y1, $x2, $y2, $color);
		}
		
		$t = $thick / 2 - 0.5;
		
		if ($x1 == $x2 || $y1 == $y2)
		{
			return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
		}
		
		$k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
		$a = $t / sqrt(1 + pow($k, 2));
		$points = array(round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a), round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a), round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a), round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a));
		imagefilledpolygon($image, $points, 4, $color);
		return imagepolygon($image, $points, 4, $color);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: drawLineFunction
	//-------------------------------------------------------------------

	/**
	 * This method draws a line function to the graph.
	 *
	 * @access public
	 * @return string The HTML that displays the graph.
	 */
	 
	 function drawLineFunction($data, $title, $colour)
	 {
	 	//echo 'numpoints = ' . sizeof($data) . '<br>';
    	for ($i = 1; $i < sizeof($data); $i++)
    	{
    		$lastX = ($data[$i-1][0] - $this->startDomain) * $this->Xscale + $this->leftMargin;
    		$lastY = $this->height - (($data[$i-1][1] - $this->startRange)* $this->Yscale + $this->bottomMargin);
    		$currentX = ($data[$i][0] - $this->startDomain) * $this->Xscale + $this->leftMargin;
    		$currentY = $this->height - (($data[$i][1] - $this->startRange) * $this->Yscale + $this->bottomMargin);
    		
    		//echo sprintf("From (%s, %s) to (%s, %s)<br>", intval($lastX) + 1, intval($lastY) + 1, intval($currentX), intval($currentY));
    		 
			$this->imagelinethick($this->image, intval($lastX), intval($lastY), intval($currentX), intval($currentY), $colour, $this->lineGraphLineThickness);
    	}
	 }
	 
	 
	//-------------------------------------------------------------------
	// METHOD: convertXDataToGraph
	//-------------------------------------------------------------------

	/**
	 * This method converts a pixel X based in 0,0 at the bottom left, to the CC_Graph coordinate system.
	 *
	 * @access public
	 * @param int $x The x coordinate to convert
	 * @return array The converted x coordinate.
	 */
	 
	 function convertXDataToGraph($x)
	 {
		return ($x - $this->startDomain) * $this->Xscale + $this->leftMargin;
	 }
	 
	 
	//-------------------------------------------------------------------
	// METHOD: convertYDataToGraph
	//-------------------------------------------------------------------

	/**
	 * This method converts a pixel Y based in 0,0 at the bottom left, to the CC_Graph coordinate system.
	 *
	 * @access public
	 * @param int $y The y coordinate to convert
	 * @return int The converted y coordinate.
	 */
	 
	 function convertYDataToGraph($y)
	 {
		return $this->height - (($y - $this->startRange) * $this->Yscale + $this->bottomMargin);
	 }
	 

	//-------------------------------------------------------------------
	// METHOD: convertXPixelToGraph
	//-------------------------------------------------------------------

	/**
	 * This method converts a pixel X based in 0,0 at the bottom left, to the CC_Graph coordinate system.
	 *
	 * @access public
	 * @param int $x The x coordinate to convert
	 * @return array The converted x coordinate.
	 */
	 
	 function convertXPixelToGraph($x)
	 {
		return $this->leftMargin + $x;
	 }
	 
	
	//-------------------------------------------------------------------
	// METHOD: convertYPixelToGraph
	//-------------------------------------------------------------------

	/**
	 * This method converts a pixel Y based in 0,0 at the bottom left, to the CC_Graph coordinate system.
	 *
	 * @access public
	 * @param int $y The y coordinate to convert
	 * @return int The converted y coordinate.
	 */
	 
	 function convertYPixelToGraph($y)
	 {
		return $this->height - ($y + $this->bottomMargin);
	 }
	 
	 
	//-------------------------------------------------------------------
	// METHOD: convertData
	//-------------------------------------------------------------------

	/**
	 * This method converts graph data to a graphable format, based on the data type
	 *
	 * @access public
	 * @param mixed $value The value to convert
	 * @return mixed The converted value.
	 */
	 
	function convertData($axis, $value)
	{
		switch ($axis)
		{
			case 'x':
			{
				$dataType = $this->XDataType;
			}
			break;
			
			case 'y':
			{
				$dataType = $this->YDataType;
			}
			break;
		}
		
		switch ($dataType)
		{
			case 'date':
			{	
				return convertMysqlDateToTimestamp($value); 	
			}
			break;
			
			case 'datetime':
			{	
				return convertMysqlDateTimeToTimestamp($value); 	
			}
			break;
			
			case 'integer':
			{
				return sprintf("%d", $value);
			}
			break;
			
			case 'float':
			{
				return sprintf("%.2f", $value);
			}
			break;
			
			default:
			{
				return sprintf("%.2f", $value);
			}
		}
	}


	//-------------------------------------------------------------------
	// METHOD: convertXData
	//-------------------------------------------------------------------

	/**
	 * This method converts the x-axis data to a graphable format, based on the domain data type
	 *
	 * @access public
	 * @param int $x The domain value to convert
	 * @return mixed The converted x value.
	 */
	 
	 function convertXData($x)
	 {
	 	return $this->convertData('x', $x);
	 }


	//-------------------------------------------------------------------
	// METHOD: convertYData
	//-------------------------------------------------------------------

	/**
	 * This method converts the y-axis data to a graphable format, based on the range data type
	 *
	 * @access public
	 * @param int $y The range value to convert
	 * @return mixed The converted y value.
	 */
	 
	 function convertYData($y)
	 {
	 	return $this->convertData('y', $y);
	 }

	
	//-------------------------------------------------------------------
	// METHOD: filterXAxisLabel
	//-------------------------------------------------------------------

	/**
	 * This method converts the x-axis data to a human-readable format, based on the domain data type
	 *
	 * @access public
	 * @param int $x The domain value to convert
	 * @return array The converted x coordinate.
	 */
	 
	 function filterXAxisLabel($x)
	 {
	 	switch ($this->XDataType)
	 	{
			case 'date':
			{	
				return date("M j, 'y",$x); 	
	 		}
	 		break;
	 		
	 		case 'integer':
	 		{
	 			return sprintf("%d", $x);
	 		}
	 		break;
	 		
	 		case 'float':
	 		{
	 			return sprintf("%.2f", $x);
	 		}
	 		break;
	 		
			default:
			{
				return sprintf("%.2f", $x);
			}
		}
	 }
	 
	
	//-------------------------------------------------------------------
	// METHOD: filterYAxisLabel
	//-------------------------------------------------------------------

	/**
	 * This method converts a pixel Y based in 0,0 at the bottom left, to the CC_Graph coordinate system.
	 *
	 * @access public
	 * @param int $y The y coordinate to convert
	 * @return int The converted y coordinate.
	 */
	 
	 function filterYAxisLabel($y)
	 {
	 	switch ($this->YDataType)
	 	{
			case 'date':
			{	
				return date("M j, 'y", $y); 	
	 		}
	 		break;
	 		
	 		case 'integer':
	 		{
	 			return sprintf("%d", $y);
	 		}
	 		break;
	 		
	 		case 'float':
	 		{
	 			return sprintf("%.2f", $y);
	 		}
	 		break;
	 		
			default:
			{
				return sprintf("%.2f", $y);
			}
		}
	 }


	//-------------------------------------------------------------------
	// METHOD: drawDatedGraph
	//-------------------------------------------------------------------
	
	function drawDatedGraph($resolution, $tableName, $whereClause, $title, $barColour, $offset = 0)
	{
		global $application;
		
		$count = 0;
		$points = array();
		
		
		switch ($resolution)
		{
			case 'second':
			{
				$this->barWidth = ($this->width - $this->leftMargin) / ($this->endDomain - $this->startDomain);
			}
			break;
			
			case 'minute':
			{
				$this->barWidth = ($this->width - $this->leftMargin) / ($this->endDomain - $this->startDomain) * 60;
			}
			break;
			
			case 'hour':
			{
				$this->barWidth = ($this->width - $this->leftMargin) / ($this->endDomain - $this->startDomain) * 60 * 60;
			}
			break;
			
			case 'day':
			{
				$this->barWidth = ($this->width - $this->leftMargin) / ($this->endDomain - $this->startDomain) * 60 * 60 * 24;
			}
			break;
			
			case 'month':
			{
				$this->barWidth = ($this->width - $this->leftMargin) / ($this->endDomain - $this->startDomain) * 60 * 60 * 24 * 29;
			}
			break;
			
			case 'year':
			{
				$this->barWidth = ($this->width - $this->leftMargin) / ($this->endDomain - $this->startDomain) * 60 * 60 * 24 * 365;
			}
			break;
			
			default:
			{
				$this->barWidth = 1;
			}
		}
				
		if ($this->barWidth < 1)
		{
			$this->barWidth = 1;
		}
		else
		{
			$this->barWidth = intval($this->barWidth) - 4;
		}
		
		$query = 'select ' . $this->xField . ' from ' . $tableName;
			
		if (strlen($whereClause) > 0)
		{
			$query .= " where $whereClause";
		}
		
		$query .= ' order by ' . $this->xField;

		$results = $application->db->doSelect($query);
		
		$count = 0;
		$date = null;
		
		while (($resultData = cc_fetch_row($results)) != false)
		{
			$lastDate = $date;
			
			// set the resolution
			switch ($resolution)
			{
				case 'second':
				{
					$date = $resultData[0];
				}
				break;
				
				case 'minute':
				{
					$date = substr($resultData[0], 0, 16) . ':00';
				}
				break;
				
				case 'hour':
				{
					$date = substr($resultData[0], 0, 13) . ':00:00';
				}
				break;
				
				case 'day':
				{
					$date = substr($resultData[0], 0, 10) . ' 00:00:00';
				}
				break;
				
				case 'month':
				{
					$date = substr($resultData[0], 0, 7) . '-01 00:00:00';
				}
				break;
				
				case 'year':
				{
					$date = substr($resultData[0], 0, 4) . '-01-01 00:00:00';
				}
				break;
			}
			
			$date = strtotime($date);
			
			if (($lastDate == $date) || ($lastDate == null))
			{	
				//echo 'date:' . $lastDate . ' count:' . $count . '<br>';
				$count++;
			}
			else if ($count > 1)
			{
				//echo "a. setting point ($lastDate, $count)<br>";
				$points[] = array($lastDate, $count);
				$count = 1;
			}
			else if ($count == 1)
			{
				//echo "b. setting point ($date, 1)<br>";
				$points[] = array($lastDate, 1);
				$count = 1;
			}
		}
		
		// for the last line		
		if ($count >= 1)
		{
			$points[] = array($date, $count);
		}

		$this->drawBarFunction($points, $title, $barColour, $offset);		
	}
	
	 
	//-------------------------------------------------------------------
	// METHOD: drawBarFunction
	//-------------------------------------------------------------------

	/**
	 * This method adds a function to the graph.
	 *
	 * @access public
	 * @return string The HTML that displays the graph.
	 */
	 
	 function drawBarFunction($data, $title, $colour, $offset = 0)
	 {
    	for ($i = 0; $i < sizeof($data); $i++)
    	{
			$x = $this->convertXDataToGraph($data[$i][0]);
			$y = $this->convertYDataToGraph($data[$i][1]);
			
			$x1 = $x - ($this->barWidth/2);
    		$x2 = $x + ($this->barWidth/2);
    		
    		//echo "Pt1 ($x1, $y)<BR>";
    		//echo "Pt2 ($x2, " . $this->convertYPixelToGraph(0) . ')<BR>';
    			
			//only draw the bar if both bar pixel points are actually in the graph
			if ($this->isWithinGraph($x1, $this->convertYPixelToGraph(0)) || $this->isWithinGraph($x2, $this->convertYPixelToGraph(0)))
			{
				if ($x1 < $this->convertXDataToGraph($this->startDomain))
				{
					$x1 = $this->convertXDataToGraph($this->startDomain);
				}
				
				if ($x2 > $this->convertXDataToGraph($this->endDomain))
				{
					$x2 = $this->convertXDataToGraph($this->endDomain);
				}
				
				if ($y <= $this->topMargin)
				{
					$y = $this->topMargin - 2;
					imagestring($this->image, 1, $x1 + (strlen($data[$i][1]) * 0.25), $y - 10, $this->filterYAxisLabel($data[$i][1]), $this->black);
				}
				
				imagefilledrectangle($this->image, $x1 + $offset, $y, $x2, $this->convertYPixelToGraph(1), $colour);
				imagerectangle($this->image, $x1 + $offset, $y, $x2, $this->convertYPixelToGraph(1), $colour);
			}
		}
	 }
	
	
	//-------------------------------------------------------------------
	// METHOD: setScale
	//-------------------------------------------------------------------

	/**
	 * This method sets the X and Y scale factors.
	 *
	 * @access public
	 *
	 */

	function setScale()
	{
		// set the scale factor to convert from points to pixels		
		$this->Xscale = ($this->width - ($this->leftMargin + $this->rightMargin)) / ($this->endDomain - $this->startDomain);
		$this->Yscale = ($this->height - ($this->bottomMargin + $this->topMargin)) / ($this->endRange - $this->startRange);
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: setWatermark
	//-------------------------------------------------------------------

	/**
	 * This method sets whether or not to include a watermark.
	 *
	 * @access public
	 *
	 */

	function setWatermark($includeWatermark, $watermarkPath = '')
	{
		$this->includeWatermark = $includeWatermark;
		
		if ($watermarkPath != '')
		{
			$this->watermarkPath = $watermarkPath;
		}
		else
		{
			$this->watermarkPath = CC_FRAMEWORK_PATH . '/CC_Images/poweredby.gif';
		}
	}
	 
	 
	//-------------------------------------------------------------------
	// METHOD: isWithinGraph
	//-------------------------------------------------------------------

	/**
	 * This method determines whether or not a graph point is within the graph.
	 *
	 * @access public
	 * @return bool Whether the graph point is within the graph.
	 */

	function isWithinGraph($xCoord, $yCoord)
	{
		$within = (($xCoord >= $this->convertXDataToGraph($this->startDomain))) && ($xCoord <= $this->convertXDataToGraph($this->endDomain)) && ($yCoord <= $this->convertYDataToGraph($this->startRange) && ($yCoord >= $this->convertYDataToGraph($this->endRange)));
		
		return $within;
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: drawGraph
	//-------------------------------------------------------------------

	/**
	 * This method draws the graph.
	 *
	 * @access public
	 */
	 
	 function drawGraph()
	 {
	 	if ($this->transparency)
		{
			$this->imageBackGroundColour = imageColorAllocateAlpha($this->image, 255, 255, 255, round(127 * ($this->transparencyLevel / 100)));
			//$this->graphBackGroundColour = imageColorAllocateAlpha($this->image, 255, 255, 255, round(127 * ($this->transparencyLevel / 100)));
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
		}
		
	 	// Draw the base image
		imagefill($this->image, 0, 0, $this->imageBackGroundColour);
		imagefilledrectangle($this->image, $this->leftMargin, $this->topMargin, $this->width - $this->rightMargin, $this->height - $this->bottomMargin, $this->graphBackGroundColour);
		
		// Draw the graph gradations
    	for ($i = 0; $i <= $this->numXGradations; $i++)
    	{
    		// draw the X-axis gradation line
    		$xData = ($i * ($this->endDomain - $this->startDomain) / $this->numXGradations) + $this->startDomain;
			$xGraph = $this->convertXDataToGraph($xData);
			
			if ($this->includeXGrid && (($i > 0) && ($i < $this->numXGradations)))
    		{
    			$this->imagelinethick($this->image, $xGraph, $this->convertYPixelToGraph(0), $xGraph, $this->convertYPixelToGraph($this->height - ($this->bottomMargin + $this->topMargin)), $this->xGridColour, $this->xGridThickness);
			}
						
			// add some gradations and labels at the specified interval
			if ($i % $this->XGradationLabelFrequency == 0)
			{					
				if ($i < $this->numXGradations)
				{
					//imagefilledrectangle($this->image, $xGraph - 1, $this->convertYPixelToGraph(-2), $xGraph + 1, $this->convertYPixelToGraph(2), $this->black);
					imagerectangle($this->image, $xGraph - 1, $this->convertYPixelToGraph(-2), $xGraph + 1, $this->convertYPixelToGraph(2), $this->xMainGradationColour);
					imageline($this->image, $xGraph, $this->convertYPixelToGraph(-2), $xGraph, $this->convertYPixelToGraph(+2), $this->xMainGradationColour);
					imagestring($this->image, 1, $xGraph - strlen($this->filterXAxisLabel($xData)) * 2, $this->convertYPixelToGraph(0) + 7, $this->filterXAxisLabel($xData), $this->xGradationLabelsColour);
				}
			}
			else
			{
				imageline($this->image, $xGraph, $this->convertYPixelToGraph(-2), $xGraph, $this->convertYPixelToGraph(2), $this->xSmallGradationColour);
			}
		}
    	
    	for ($j = 0; $j <= $this->numYGradations; $j++)
    	{
    		// draw the Y-axis gradation line
			$yData = ($j * ($this->endRange - $this->startRange) / $this->numYGradations) + $this->startRange;
			$yGraph = $this->convertYDataToGraph($yData);
    		
    		if ($this->includeYGrid && (($j > 0) && ($j < $this->numYGradations)))
    		{
    			$this->imagelinethick($this->image, $this->convertXPixelToGraph(0), $yGraph, $this->convertXPixelToGraph($this->width - $this->rightMargin - $this->leftMargin), $yGraph, $this->yGridColour, $this->yGridThickness);
			}

    		// add gradations and labels based on range at the specified interval
			if ($j % $this->YGradationLabelFrequency == 0)
			{
				if ($j < $this->numYGradations)
				{
					imagefilledrectangle($this->image, $this->convertXPixelToGraph(-2), $yGraph - 1, $this->convertXPixelToGraph(2), $yGraph + 1, $this->yMainGradationColour);
					imagerectangle($this->image, $this->convertXPixelToGraph(-2), $yGraph - 1, $this->convertXPixelToGraph(2), $yGraph + 1, $this->yMainGradationColour);
					imagestring($this->image, 1, $this->convertXPixelToGraph(0) - 25, $yGraph - 3, $this->filterYAxisLabel($yData), $this->yGradationLabelsColour);
				}
			}
			else
			{
				imageline($this->image, $this->convertXPixelToGraph(-2), $yGraph, $this->convertXPixelToGraph(2), $yGraph, $this->ySmallGradationColour);
			}
		}
		
		// Draw the border
		imagerectangle($this->image, $this->leftMargin, $this->topMargin, $this->width - $this->rightMargin, $this->height - $this->bottomMargin, $this->borderColour);

    	// Draw the titles
    	$labelFont = 4;
    	$titleFont = 5;
    	$timestampFont = 1;
    	
		// timestamp
		if ($this->showTimestamp)
		{
			imagestring($this->image, $timestampFont, 0, $this->height - 10, '(' . date("F j, Y G:i:s", time()) . ')', $this->timestampColour);
  		}
  		  	
    	// title
    	imagestring($this->image, $titleFont, $this->width/2 - (strlen($this->title) * $titleFont), intval($this->topMargin / 3), $this->title, $this->titleColour);

    	// x-axis
    	imagestring($this->image, $labelFont, $this->width/2 - (strlen($this->xTitle) * $labelFont), $this->height - 24, $this->xTitle, $this->xTitleColour);
		
		// y-axis
		imagestringup($this->image, $labelFont, 10, $this->height/2 + (strlen($this->yTitle) * $labelFont), $this->yTitle, $this->yTitleColour);
		
		if ($this->includeWatermark)
		{
			$logo = imagecreatefromgif($this->watermarkPath);
			$dimensions = getImageDimensions($this->watermarkPath);
			imagecopymerge($this->image, $logo, $this->width - $this->rightMargin - $dimensions['width'] + 1, $this->height - $dimensions['height'], 0, 0, 96, 32, 50);
			//imagecopymerge($this->image, $logo, intval(($this->width/2) - 48), intval(($this->height/2) - 16), 0, 0, 96, 32, 25);
			unset($dimensions);
		}
	 }
	 
	 
	//-------------------------------------------------------------------
	// METHOD: streamImage
	//-------------------------------------------------------------------

	/**
	 * This method streams the image.
	 *
	 * @access public
	 * @return string The HTML that displays the graph.
	 */

	function streamImage()
	{
		$imageName = time();
		
		//generate the image
		$imagePath = $this->imagePath . $imageName . '.png';
		imagepng($this->image, $imagePath);
		
		//stream the image
		header('Content-Type: image/png');
		header('Content-Length: ' . filesize($imagePath));
		readfile($imagePath);
		
		//clean up
    	imagedestroy($this->image);
    	unlink($imagePath);
    	exit();
	}
	
	
	//-------------------------------------------------------------------
	// METHOD: saveImage
	//-------------------------------------------------------------------

	/**
	 * This method streams the image.
	 *
	 * @access public
	 * @return string The HTML that displays the graph.
	 */

	function saveImage($path)
	{						
		if (!imagepng($this->image, $path))
		{
			trigger_error('', E_USER_WARNING);
		}
	}
}

?>