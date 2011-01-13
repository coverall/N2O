// $Id: cc_summary_search_component.js,v 1.2 2008/06/12 02:56:38 jamie Exp $

function filterPage(summary, filterName, searchField, queryAdditionName, index)
{
	var summaryTable = $(summary);

	var body = summaryTable.getElement('tbody');
	
	if (queryAdditionName)
	{
		if (index != null && $(queryAdditionName + '_' + index).type == 'checkbox')
		{
			var queryAdditionValue = $(queryAdditionName + '_' + index).checked;
		}
		else if ($(queryAdditionName + '_queryaddition_date'))
		{
			var queryAdditionValue = $(queryAdditionName + '_queryaddition_year').value + '-' + $(queryAdditionName + '_queryaddition_month').value + '-' + $(queryAdditionName + '_queryaddition_date').value;
		}
		else
		{
			var queryAdditionValue = $(queryAdditionName).value;
		}
	}
	else
	{
		var queryAdditionName = ""
		var queryAdditionValue = "";
	}
		

	var jSonRequest = new Json.Remote("?method=filterSummary&summary=" + summary + "&filterName=" + filterName + "&q=" + searchField.value + "&qaname=" + queryAdditionName + "&qavalue=" + queryAdditionValue + "&index=" + index, {onComplete: function(result)
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
