var foodItems = new Array();
var foodCodes = new Array();
var check = new Array();
var totalEaten = 0;

/* var myData = [
					{"name":"Aloo Bhonda", "id":"1234"},
					{"name":"Palak Paneer", "id":"1235"},
					{"name":"Daal Makhani", "id":"1236"},
					{"name":"Aloo Bhonda", "id":"1234"},
					{"name":"Palak Paneer", "id":"1235"},
					{"name":"Daal Makhani", "id":"1236"},
					{"name":"Aloo Bhonda", "id":"1234"},
					{"name":"Palak Paneer", "id":"1235"}
			];
			 */




$(document).ready(function() {
	//parseJson(myJSONdata);
	//alert("entered");
	var page = 'e';
	$.ajax({
		url: 'send_items3.php', 
		data: ({ page:page }),
		method:'POST',
		dataType:'json',
		success: function(data)
		{
			//alert("suc");
			parseJson(data);
		}
	});
});

function parseJson(data) {
	//alert("loaded");
	for (i=0; i < data.length; i++) {
		check[i]=0;
	}	

	var c = 0;
	for ( c=0; c < data.length; c++ ) {
		
		// create the new element
		foodItems[c] = data[c].name;
		foodCodes[c] = data[c].id;
		d = document.createElement('div');
		var newId = 'item'+c;
		
		$(d).addClass("itemsSingle")
			.attr('id',newId)
			.appendTo($("#itemsList"))
			.click(function() {
				var id = $(this).attr('id').substring(4);
				
				if (check[id] == 0) {
					$(this).css({'background-color':'#088A08'});	
					check[id] = 1;
				}
				else if (check[id] == 1){
					check[id] = 0;
					$(this).css({'background-color':'#B40404'})
				}
				//alert(check);
			});
			
		// The title div added to main container
		d_title = document.createElement('div');
		var newId = 'title'+c;
		$(d_title).attr('id',newId)
				  .addClass("itemsName")
				  .html(foodItems[c])
				  .appendTo(d);
	}
	
	d = document.createElement('div');
	$(d).attr('id',"fillServings")
		.appendTo($("#itemsList"))
		.html(">> Fill Servings")
		.click(function() {
			//alert('hello');
			addServings();
		});
		
	$(submitButton).click(function() {
		updateList();
	});	
};

$('.foodServings').change(function() {
//alert("change");
  updateList();
});

function updateList() {
	itemsList="";
		for (i=0;i<foodItems.length;i++) {
			if (check[i] == 1) {
				itemsList += $('#txtField'+i).val() + '.' + foodCodes[i] + ',';
			}
		}
		var n = itemsList.length - 1;
		var itemsList = itemsList.substring(0, n);
		
		$("#eaten_food").attr("value", itemsList);
		$("#food_time").attr("value", "1");
		//alert(itemsList);
		/* document.location.href = 'test.php?shiva='+itemsList; */
}

function addServings() {
	lb = document.createElement('label');
	$(lb).html("Please fill the servings:-")
		 .appendTo("#servingsList");
		 
/* 	br = document.createElement('br');
	$(br).appendTo('#servingsList'); */
	
	for (i=0; i < check.length; i++) {
		
		if (check[i] == 1) {
			//alert('hello'+i);
			/* d = document.createElement('div');
			$(d).attr('id', "serve"+i)
				.appendTo($("#servingsList"))
				.html(foodItems[i]); */
			br = document.createElement('br');
			$(br).appendTo('#servingsList');
			
			lb = document.createElement('label');
			$(lb).html(foodItems[i])
				 .appendTo("#servingsList");
				 
			ip = document.createElement('input');
			$(ip).attr('type', 'text')
				 .attr('name', foodItems[i])
				 .attr('id', 'txtField'+i)
				 .attr('class', 'foodServings')
				 .appendTo("#servingsList");
			
			br = document.createElement('br');
			$(br).appendTo('#servingsList');
			
			totalEaten++;
		}
	}
}