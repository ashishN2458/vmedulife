$(document).ready(function(){
	$("#savebtn").click(function(evt){
		evt.preventDefault();
		editmobile();
	});
	  $.ajax
	  ({
	   type: "GET",
	   url: "http://localhost/mobileshop-master/api.php",
	   data: {
				"action": "getMobilesforhomepage"		
			},
	   cache: false,
	   success: function(html)
	   {
		   $('#home_mobile_list').html(html); 
	   } 
	   });
	   
	   
	   $.ajax
	  ({
	   type: "GET",
	   url: "http://localhost/mobileshop-master/api.php",
	   data: {
				"action": "getmobilelistafterlogin"		
			},
	   cache: false,
	   success: function(html)
	   {
		   $('#mobile_list').html(html); 
	   } 
	   });
	   
	   $.ajax
	  ({
	   type: "GET",
	   url: "http://localhost/mobileshop-master/api.php",
	   data: {
				"action": "listforadminpage"		
			},
	   cache: false,
	   success: function(html)
	   {
		   $('#detail').html(html);  
	   } 
	   });
	   
});


function editmobile(){
	var data = $('#form').serialize();
	console.log("data", data);
	 $.ajax
	  ({
	   type: "POST",
	   url: "http://localhost/mobileshop-master/api.php",
	   data: {
				"data": data,
				"action": "editmobile"		
			},
	   cache: false,
	   contentType: false, //this is requireded please see answers above
       processData: false,
	   success: function(html) 
	   {
		   var arr = JSON.parse(html);
		   var status = arr.status;
		   if(status == 'success'){
			   window.location = 'http://localhost/mobileshop-master/AdminPage.php';
		   }
		   //$('#detail').html(html);  
	   } 
	   });
}