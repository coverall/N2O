// $Id: cc_dynamic_summary.js,v 1.4 2008/06/12 01:18:52 mike Exp $

if (typeof __click == "undefined")
{
	__click = function() { };
}


function sortColumn(summary, column)
{
	var summaryTable = $(summary);

	var body = summaryTable.getElement('tbody');

	var jSonRequest = new Json.Remote("?method=sortSummary&summary=" + summary + "&column=" + column, {onComplete: function(result)
	{
		if (result.success)
		{
			// get all the sort arrows, and hide 'em!
			var theRow = $(summary + '_heading_' + column).parentNode;
			
			var images = theRow.getElementsByTagName('img');
			
			for (var i = 0; i < images.length; i++)
			{
				$(images[i]).setStyle('display', 'none');
			}

			// show the current sort arrow!
			$(summary + '_' + column + '_sort').setStyle('display', 'inline');
	
			var image = $(summary + '_' + column + '_sort');
			
			if (result.direction == 'ASC')
			{
				image.src = image.src.replace('down', 'up');
			}
			else
			{
				image.src = image.src.replace('up', 'down');
			}
			
			printSummaryTable(summary, body, result);
		}
		else
		{
			body.innerHTML = "Loading Failed";
		}
	}}).send();
}

function jumpToPage(summary)
{
	var summaryTable = $(summary);
	
	var pageSelector = $("ccDynamicPageSelector-" + summary);
	
	var page = pageSelector.value;
	
	var body = summaryTable.getElement('tbody');

	var jSonRequest = new Json.Remote("?method=jumpToPage&summary=" + summary + "&page=" + page, {onComplete: function(result)
	{
		if (result.success)
		{
			printSummaryTable(summary, body, result);
		}
		else
		{
			body.innerHTML = "Loading Failed";
		}
	}}).send();
}

function nextPage(summary, page)
{
	var summaryTable = $(summary);

	var body = summaryTable.getElement('tbody');

	var jSonRequest = new Json.Remote("?method=nextPage&summary=" + summary, {onComplete: function(result)
	{
		if (result.success)
		{
			printSummaryTable(summary, body, result);
		}
		else
		{
			body.innerHTML = "Loading Failed";
		}
	}}).send();
}

function previousPage(summary, page)
{
	var summaryTable = $(summary);

	var body = summaryTable.getElement('tbody');

	var jSonRequest = new Json.Remote("?method=previousPage&summary=" + summary, {onComplete: function(result)
	{
		if (result.success)
		{
			printSummaryTable(summary, body, result);
		}
		else
		{
			body.innerHTML = "Loading Failed";
		}
	}}).send();
}


function printSummaryTable(summary, body, result)
{
	var theRows = body.getElementsByTagName("tr");
	
	var headings = body.parentNode.getElementsByTagName("th");
	
	if (theRows.length > 0)
	{
		for (var i = theRows.length - 1; i >= 0; i--)
		{
			body.removeChild(theRows[i]);
		}
	}
	
	if (result.records > 0)
	{
		var even = true;
		for (var i in result.rows)
		{
			row = result.rows[i];
			
			rowHTML = new Element('tr', {'class':(even ? 'even' : 'odd')}); //document.createElement('tr');
			//rowHTML.addClass((even ? 'even' : 'odd'));
			
			for (var j = 0; j < row.length; j++)
			{
				column = row[j];
				
				columnHTML = $(document.createElement('td'));
				columnHTML.innerHTML = column;
				columnHTML.addClass(headings[j].className);
				columnHTML.style.textAlign = headings[j].style.textAlign;
				
				rowHTML.appendChild(columnHTML);
			}
		
			body.appendChild(rowHTML);
			
			even = !even;
		}
	}
	else
	{
		rowHTML = new Element('tr', {'class':(even ? 'even' : 'odd')});
		
		columnHTML = $(document.createElement('td'));
		
		columnHTML.innerHTML = 'No ' + result.pluralDisplayName;
		
		rowHTML.appendChild(columnHTML);
		
		body.appendChild(rowHTML);
	}
	
	if ($(summary + '_summaryStarRow'))
	{
		if (result.records > 0)
		{
			$(summary + '_summaryStarRow').innerHTML = result.startRow;
		}
		else
		{
			$(summary + '_summaryStarRow').innerHTML = 0;
		}
	}
	
	if ($(summary + '_summaryEndRow'))
	{
		$(summary + '_summaryEndRow').innerHTML = result.endRow;
	}

	if ($(summary + '_summaryRecords'))
	{
		$(summary + '_summaryRecords').innerHTML = result.records;
	}

	$(summary + '_summaryNext').parentNode.innerHTML = result.nextButton;
	$(summary + '_summaryPrevious').parentNode.innerHTML = result.previousButton;
	
	if (result.controlRow)
	{
		$(summary + '_controlRow').innerHTML = result.controlRow;
	}
}
