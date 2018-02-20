// Creator Andre Hesel @2013
var setting = {
	constant: {
		datepicker_format: 'dd/mm/yy',
	},
	func: {
		number: {
			addCommas: function(nStr) {
				nStr += '';
			    var x = nStr.split('.');
			    var x1 = x[0];
			    var x2 = x.length > 1 ? ((!isNaN(x[1]) && parseInt(x[1]) > 0)? '.' + x[1] : '') : '';
			    var rgx = /(\d+)(\d{3})/;
			    while (rgx.test(x1)) {
			        x1 = x1.replace(rgx, '$1' + ',' + '$2');
			    }
			    return x1 + x2;
			},
			removeCommas: function(nStr) {
				nStr += '';
			    var x = nStr.split('.');
			    var x1 = x[0];
			    var x2 = x.length > 1 ? '.' + x[1] : '';
			    return x1.replace(/,/g,'') + x2;
			},
			
			// LO : Preserve the decimal value even when it's zero
			addCommasDec: function(nStr) {
				nStr += '';
			    var x = nStr.split('.');
			    var x1 = x[0];
			    var x2 = '';
			    
			    if(x.length > 1)
			    {
			     	if(!isNaN(x[1]))
			     	{
			     		if(x[1].length < 2)
			     		{
			     			x2= '.' + x[1] + '0';
			     		}
			     		else if(x[1].length == 2)
			     		{
			     			x2= '.' + x[1];
			     		}
			     		else
			     		{
			     			x2= '.' + x[1].substr(0,2);
			     		}
			     	}
			     	else
			    	{
			    		x2='';
			    	}
			    }
			    else
			    {
			    	if(nStr)
			    	{
			    		x2='.00';
			    	}
			    	else
			    	{
			    		x2='';
			    	}
			    }
			    
			    var rgx = /(\d+)(\d{3})/;
			    while (rgx.test(x1)) {
			        x1 = x1.replace(rgx, '$1' + ',' + '$2');
			    }
			    return x1 + x2;
			},
			
			applyFormatting:function(numberSelector){
				$(numberSelector).trigger('blur');
			}
		},
		date:{
			// AR : change the date format from mysql format[yyyy-mm-dd] to dd/mm/yyyy
			applyFormatting:function(dateSelector){
				
				$(dateSelector).each(function(){
					var dateValue = $(this).val();
					if( dateValue.indexOf('-') !== -1 ){
						var dateParts  = null; 
						if(dateValue.indexOf(' ') !== -1){
							var temp  = dateValue.split(" ");
							dateParts = temp[0].split("-");
						}else{
							dateParts = dateValue.split("-");
						}
						
						var jsDate 	  = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
						
						var month      = jsDate.getMonth()+1;
						if(month < 10)	
							month = '0'+month;

						var date      = jsDate.getDate();
						if(date < 10)	
							date  = '0'+date;
						
						var formattedJsDate = [date, month, jsDate.getFullYear()].join('/');
						$(this).val(formattedJsDate);
					}
				});
			}			
		}
	}
};

function registerFormatField(dateSelector,numberSelector)
{
	
	if($(numberSelector).length > 0){
	    $(numberSelector).bind('focus', function() {
			this.value = setting.func.number.removeCommas(this.value);
		}).bind('blur', function() {
			this.value = setting.func.number.addCommas(this.value);
		});
		setting.func.number.applyFormatting(numberSelector);
	}

    if($(dateSelector).length > 0 ){
        setting.func.date.applyFormatting(dateSelector);    
    }
}

function registerFormatNumberDec(numberSelector)
{
	if($(numberSelector).length > 0){
	    $(numberSelector).bind('focus', function() {
			this.value = setting.func.number.removeCommas(this.value);
		}).bind('blur', function() {
			this.value = setting.func.number.addCommasDec(this.value);
		});
		setting.func.number.applyFormatting(numberSelector);
	}
}
