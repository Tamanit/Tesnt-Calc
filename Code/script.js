window.onload = function()
{
	
		$("#form").validate
	({
		rules:
		{
			startDate:
			{
				required: true,
				date: true,
			},
			term:
			{
				required: true,
				digits: true,
				min: 1,
				max: 60,
			},
			sum:
			{
				required: true,
				digits: true,
				min: 1000,
				max: 3000000,
			},
			percent:
			{
				required: true,
				digits: true,
				min: 3,
				max: 100,
			},
			sumAdd:
			{
				required: true,
				digits: true,
				min: 0,
				max: 3000000,
			},
		},
		messages:
		{
			startDate: "Введите корректную дату",
			term: "Срок может быть только в районе от 1 до 60 месяцов",
			sum: "Вклад от 1 000р. до 3 000 000р.",
			percent: "не меньше 3% и не более 100%",
			sumAdd: "от 0р. до 3 000 000р.",
		},
	});
	
	var MAndY = $("#MonthOrYear").val();
	
	$("#MonthOrYear").change ( function()
	{
		MAndY = $("#MonthOrYear").val();
	});
	
	$("#NextPayments").click ( function ()
	{ //Если ежемесячного пополнения нет - передавать 0
		
		if ( $("#NextPayments").is(":checked") )
		{
			$("#sumAdd").css("visibility", "visible");
			$("#sumAdd").val("");
		}
		else
		{
			$("#sumAdd").css("visibility", "hidden");
			$("#sumAdd").val("0");
		}
		
	});
	
	$("#submit").click( function ()
	{
		
		$.ajax
		({
			url: "calc.php",
			type: "POST",
			dataType: "JSON",
			data:
			{
				startDate: $("#startDate").val(),
				sum: $("#sum").val(),
				term: $("#term").val() * MAndY,
				percent:$("#percent").val(),
				sumAdd: $("#sumAdd").val()
			},
			success: function(data)
			{
				$("#results").html("₽ " + data.toLocaleString("ru"));
			},
			error: function (jqXHR, exception)
			{
				var msg = '';
				if (jqXHR.status === 0)
				{ msg = 'Not connect.\n Verify Network.'; } 
				else if (jqXHR.status == 404)
				{ msg = 'Requested page not found. [404]'; }
				else if (jqXHR.status == 500)
				{ msg = 'Internal Server Error [500].'; }
				else if (exception === 'parsererror')
				{ msg = 'Requested JSON parse failed.'; } 
				else if (exception === 'timeout')
				{ msg = 'Time out error.'; }
				else if (exception === 'abort')
				{ msg = 'Ajax request aborted.';}
				else
				{ msg = 'Uncaught Error.\n' + jqXHR.responseText;}
				alert(msg);
			}
		});
	});
	
}