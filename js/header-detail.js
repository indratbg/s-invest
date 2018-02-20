// Creator Andre Hesel @2013

// AR : 1 form detail Method
function ajxCreateOrUpdateDetail(formSelector,detailGridSelector,url,data)
{
   var jForm  = $(formSelector);

   // AR :     {update} || {insert} 
   var jUrl   = url  || jForm.attr('action');
   var jData  = data || jForm.serialize();
        
   $.ajax({
         type    : 'POST',
         url     : jUrl,
         data    : jData,
         dataType:'json',
         success :function(data) {
        	 
            $('#wrap-detail').html(data.content);
           
     		//  AR : apply formatting
     		if($('.tdetaildate').length > 0)
     			registerFormatField('.tdetaildate','.tdetailnumber');
     		
            // AR : focusing in first input  
            $('#wrap-detail input:first').focus();
            if(data.status == 'success'){
             	
                $.fn.yiiGridView.update(detailGridSelector);
                $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
             }
         }
   }); 
   
   
}
