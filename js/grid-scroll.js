// LO: Align the width of tbody columns to match the header

function checkGridScroll()
{
	var table = $(".grid-view").children('table');
	var body = table.children('tbody');
	
	if(body.length)
	{
		if(body.get(0).scrollHeight > body.height()) //check whether tbody has a scrollbar
		{
			table.children('thead').css('width', '100%').css('width', '-=17px');	
		}
		else
		{
			table.children('thead').css('width', '100%');	
		}
		alignGridColumn(table);
	}
}

function alignGridColumn(table)
{		
	var unprocessed;
	var col_index;
	var total_col = table.children('thead').children('tr').children('th').length;
	var col_width = [];
	var total_width = 0;
	var header_width = table.children('thead').width();
	var isEmpty = table.children('tbody').children('tr:first').children('td.empty').length?true:false;

	if(table.children('thead').children('tr').children('th:first').children('[type=checkbox]').length)
	{
		// Customized for inbox unprocessed
		unprocessed = true;
		col_index = 1;
		total_col--;
	}
	else
	{
		unprocessed = false;
		col_index = 0;
	}
	
	table.children('thead').children('tr').children('th').filter(':eq('+col_index+'), :gt('+col_index+')').each(function()
	{
		if(col_index < total_col)
		{
			var body_col = table.children('tbody').children('tr:first').children('td:eq('+col_index+')');
			
			if($(this).width() > body_col.width())
			{
				col_width[col_index] = $(this).width();
			}
			else
			{
				col_width[col_index] = body_col.width();
			}
			
			total_width += col_width[col_index];
			col_index++;
		}
		else
		{
			return false;
		}
	});
	
	if(unprocessed)
	{
		col_index = 1;
		
		var firstHeader = table.children('thead').children('tr').children('th:first');
		var lastHeader = table.children('thead').children('tr').children('th:last');
		
		firstHeader.css('width', parseInt(0.02 * header_width));
		lastHeader.css('width', parseInt(0.06 * header_width));
	}
	else
	{
		col_index = 0;
	}
	
	table.children('thead').children('tr').children('th').filter(':eq('+col_index+'), :gt('+col_index+')').each(function()
	{
		if(col_index < total_col)
		{
			var multiplier = unprocessed?0.92:1;
			var width = parseInt(col_width[col_index] / total_width * multiplier * header_width);
			
			$(this).css('width',width);
			
			col_index++;
		}
		else
		{
			return false;
		}
	});
	
	col_index = 0;

	if(!isEmpty)
	{
		table.children('tbody').children('tr:first').children('td').each(function()
		{
			$(this).css('width',table.children('thead').children('tr').children('th:eq('+(col_index++)+')').width());
		});
	}
}