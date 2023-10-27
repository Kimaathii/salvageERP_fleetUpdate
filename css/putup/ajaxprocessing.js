//this all the ajax in this erp	
$(document).ready(function() {
	//cusomter code generator
  $("#cusCode").click(function() {
	//clas ajax to generate a code
	var name  = (this.name);
	//alert(name);
	$.ajax ({
	 type: "POST",
	 data: {bName:name},
  url: path+"/ajaxPages/codegenerator.php",
  success: function(data){
	  if(name == 'salesCode') {
		 $("#SalesmanCode").val("");
		 $("#SalesmanCode").val(data);
	  } else if(name == "cusCode") {
  $("#DebtorNo").val("");
  $("#DebtorNo").val(data);
	  } else if(name == "areaCode") {
		  $("#AreaCode").val("");
		  $("#AreaCode").val(data);
	  } else if(name == "catCode") {
		  $("#CategoryID").val("");
		  $("#CategoryID").val(data)
          } else if(name == "itemCode") {
			  $("#stockID").val("");
			  $("#stockID").val(data);
		  } else if(name == "supCode") {
		      $("#SupplierID").val("");
		      $("#SupplierID").val(data);
		  }
       } //testing and testing 
			}); 
		});
   //get stock code
  $("#StockCat").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
	$.ajax({
		type: "POST",
		data: {stockID:value},
		url: path+"/ajaxPages/getstocks.php",
		success: function(data) {
			//console.log(data);
		$("#StockCode").html("");
		$("#StockCode").html(data);
			}
		});
	});
  
  $("#Tag").change(function() {
	var value  =  $(this).children("option:selected").val();
	//alert(value);
	if(value != '0')
	$("#processgl").attr("disabled", false);
	else
	$("#processgl").attr("disabled", true);
    });
  //tag 2
  $("#tag2").change(function() {
	var value  =  $(this).children("option:selected").val();
	//alert(value);
	if(value != '0')
	$("#processgl2").attr("disabled", false);
	else
	$("#processgl2").attr("disabled", true);
    });
//get stock description
$("#StockCode").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
	$.ajax({
		type: "POST",
		data: {stockCode:value},
		url: path+"/ajaxPages/getstocknotes.php",
		success: function(data) {
			//console.log(data);
		$("#Keywords").val("");
		$("#Keywords").val(data);
			}
		});
	});


  //region stuff
  $("#Address2").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
	//alert(value);
	$.ajax({
		type: "POST",
		data: {regionName:value},
		url: path+"/ajaxPages/getstates.php",
		success: function(data) {
		$("#Address3").html("");
		$("#Address3").html(data);
			}
		});
	});
  
   $("#Address3").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
	//alert(value);
	$.ajax({
		type: "POST",
		data: {stateName:value},
		url: path+"/ajaxPages/getlocalgov.php",
		success: function(data) {
		$("#Address4").html("");
		$("#Address4").html(data);
			}
		});
	});
	
	//let get the account balance
	$("#source").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
	var trdate = $("#TransDate").val();
	$.ajax({
		type: "POST",
		data: {accountcode:value,trdate:trdate},
		url: path+"/ajaxPages/getbalances.php",
		success: function(data) {
		$("#balances").val("");
		$("#balances").val(data);
			}
		});
	});
	//select user by depart ment 
	$("#tagRef").change(function() {
	var value  =  $(this).children("option:selected").val();
	//alert(value);
	if((value != 0) || (value == "")) {
		$("#ProcessInvoiceBut").removeAttr("disabled");
	  }
	});
	//for payment and itse subsidairies
   $("#Paymenttype").change(function() {
	var value  =  $(this).children("option:selected").val();
	 if(value == "Cheque")
		$("#cheque").removeClass("hide");
		else
		$("#cheque").addClass("hide");
	});
   //approval type
    $("#approvaltype").change(function() {
	var value  =  $(this).children("option:selected").val();
	 if((value == "Audit") || (value == "Account"))
		$("#limitby").removeClass("hide");
		else
		$("#limitby").addClass("hide");
	});
	//limits 
	$("#limits").change(function() {
	var value  =  $(this).children("option:selected").val();
	 if(value == "limit")
		$("#limitamount").removeClass("hide");
		else
		$("#limitamount").addClass("hide");
	});
	 //show amount or not 
   //delete from glpayments
   $(".deleteGL").click(function() {
	var id  =  this.id;
	 $.ajax({
		type: "POST",
		data: {id:id},
		url: path+"/ajaxPages/deleteGL.php",
		success: function(data) {
			//console.log(data);
		location.reload();
	      	}
		});
	});
	$("#BankAccount").change(function() {
	var bankcode  =  $(this).children("option:selected").val();
	var datep = $("#DatePaid").val();
	$.ajax({
		type: "POST",
		data: {account:bankcode,datep:datep},
		url: path+"/ajaxPages/getbankaccountbalance.php",
		success: function(data) {
			//console.log(data);
			//show header
			var dat = $.parseJSON(data);
			$("#textupdate").html("");
			$("#textupdate").html(dat.header);
			//show balance
			$("#showbalance").html("");
			$("#showbalance").html(dat.balance);
			}
		});
	});
	
$("#displayby").change(function() {
	var val =  $(this).children("option:selected").val();
	if(val == 'DisplayByZone') {
	    $("#displaybyzone").removeClass('hide');
	    $("#displaybycustomer").addClass('hide');
		$("#displaybylocation").addClass('hide');
	} else if(val == 'DisplayByCustomer') {
	    $("#displaybycustomer").removeClass('hide');
	    $("#displaybyzone").addClass('hide');
		$("#displaybylocation").addClass('hide');
     	}else if(val == 'DisplayByLocation') {
	    $("#displaybycustomer").addClass('hide');
	    $("#displaybyzone").addClass('hide');
		 $("#displaybylocation").removeClass('hide');
     	}
	});
	
	$("#DatePaid").blur(function() {
	var value  =  $(this).val();
	$("#datechange").html("");
	$("#datechange").html(value);
	});
	
	$("#department").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
		var loc = $("#location").children("option:selected").val();
	$.ajax({
		type: "POST",
		data: {id:value,loc:loc},
		url: path+"/ajaxPages/getusers.php",
		success: function(data) {
			//console.log(data);
			var dat = $.parseJSON(data);
		$("#userbydepart").html("");
		$("#userbydepart").html(dat.us);
		//data 2
		$("#assigned").html("");
		$("#assigned").html(dat.gs);
		
			}
		});
	});
	
	$("#GLGroup").change(function() {
	//class ajax to generate a code
	var value  =  $(this).children("option:selected").val();
	var type  =  $("#CodingType").children("option:selected").val();
	//check if the count evelemnt is there 
	var val = $("#count").val();
	if(val == 0)
	var realcount = 1;
	else
	var realcount = parseInt(val) + 1;
	$.ajax({
		type: "POST",
		data: {id:value,count:realcount,type:type},
		url: path+"/ajaxPages/getglaccounts.php",
		success: function(data) {
			//console.log(data);
			if(type == 'split') {
		$("#showotherform").append(data);
		$("#count").val(realcount);
			} else {
				  $("#showotherform").html("");
			  $("#showotherform").html(data);
		     	}
			}
		});
	});
	
	$('body').delegate('.addMoreLine', 'click', function() {
	//class ajax to generate a code
	var val = $("#count").val();
	var realcount = parseInt(val) + 1;
	var value  =  $("#GLGroup").children("option:selected").val();
	var type = 'split';
	$.ajax({
		type: "POST",
		data: {id:value,count:realcount,type:type},
		url: path+"/ajaxPages/getglaccounts.php",
		success: function(data) {
			//console.log(data);
		$("#showotherform").append(data);
			$("#count").val(realcount);
			}
		});
	});
	
	$('body').delegate('#Period', 'click', function() {
	 var value  =  $(this).children("option:selected").val();
	 if((value == "CustomDate"))
		$(".customDate").removeClass("hide");
		else
		$(".customDate").addClass("hide");
	});
	
	$('body').delegate('.RemoveLine', 'click', function() {
	var id = this.id;													
    $("#del"+id).remove();
	});
	
	
		//let us see this separately 
	$('body').delegate('.amt', 'change', function() {
	var id = this.id;
	var remain_amount = parseInt($("#remain").val()); //fetch remainin amount
	var original_amount = parseInt($("#amtpaid").val()); //fetch original quoted amount
	var amount_coded = parseInt($("#"+id).val()); //amount coded
	if(remain_amount == 1) 
	var amte = original_amount - amount_coded;
	 else
	var amte = remain_amount -  amount_coded;
	 
	 //check against starint gaing 
	 if(remain_amount == 0) { 
	 alert("No more amount to code");
	 $("#"+id).val("");
	 } else {
	  if(amte >= 0) {
		$("#remain").val(amte);
 $("#rema").append("<p id="+id+">The remaining amount is: " + Number(amte).toLocaleString('en') + " - You've done : " + Number(amount_coded).toLocaleString('en') +"</p>"); 
     } else {
	  alert("The amount coded is more than remain amount to be codec");
	  $("#"+id).val("");
        }
	 }
	 });
	
	$('body').delegate('#Period', 'click', function() {
	 var value  =  $(this).children("option:selected").val();
	 if((value == "CustomDate"))
		$(".customDate").removeClass("hide");
		else
		$(".customDate").addClass("hide");
	});
	
	$('body').delegate('.RemoveLine', 'click', function() {
	var id = this.id;													
    $("#del"+id).remove();
	});
	
	$("#unitcost").blur(function() {
	 var unitcost =  $(this).val();
	var quantity  = $("#quantity").val();
	 var total = parseInt(unitcost) * parseInt(quantity);
	 $("#totalcost").val("");
	$("#totalcost").val(total);
	 });
	
	$("#mortality").blur(function() {
	var mortality =  $(this).val();
	var quantity  = $("#quantity").val();
	var total2 = parseInt(quantity) - parseInt(mortality);
	$("#st_stock").val("");
	$("#st_stock").val(total2);
	//calculate the new starting stock unit price
	var totalcost =  parseInt($("#totalcost").val());
	var unitprice = totalcost / parseInt(total2);
	$("#st_stock_unitcost").val("");
	$("#st_stock_unitcost").val(parseFloat(unitprice).toFixed(2));
	});
	
	$('#expenses_head').change(function() {
     var categoryid = $(this).children("option:selected").val();
	 var pen_id = $("#pen_id").val();
	 var tag = $("#tag").val();
			 $.ajax({
      type:'POST',
	   data: {categoryid:categoryid,pen_id:pen_id,tag:tag},
      url: path+"/ajaxPages/getavailableresources.php",
      success:function(data) {
		  //console.log(data);
		if($("#controller").hasClass(categoryid) == false) {
		$("#showinputform").append(data);
		$("#controller").addClass(categoryid);
		  }
	   } //ajax success
      })
	});
	
	$('body').delegate('#expenseresources', 'change', function() {													
	 var stockcode  =  $(this).children("option:selected").val();
	 var categoryid = $("#expenses_head").children("option:selected").val();
	 var pen_id = $("#pen_id").val();
	 var tag = $("#tag").val();
	 //alert(stockcode+"-"+categoryid+"-"+pen_id+"-"+tag);
	   $.ajax({
      type:'POST',
      data: {categoryid:categoryid,pen_id:pen_id,tag:tag,stockcode:stockcode},
      url: path+"/ajaxPages/showResourcesExpensesForm.php",
      success:function(data) {
		//console.log(data);
		//av_ff_cc
		if($("#"+categoryid).hasClass(stockcode) == false) {
		 $("#"+categoryid).append(data);
		 $("#"+categoryid).addClass(stockcode);
		 //let see it here
		var str = $("#expTypes").val();
		if(str == "")
		var strType = stockcode; // to avoid the preecing _
		else
		var strType = str+"_"+stockcode;
		$("#expTypes").val(strType);
	    }
	   } //ajax success
	     })
		});
	
	$("#submit_dayexpenses").click(function() {
	 var formdata = $("#expensesForm").serialize();
	 if(confirm("Sure you're ready to submit? This action is not reversible!")){
	 $.ajax({
      type:'POST',
      data: formdata,
      url: path+"/ajaxPages/submitrearing_Expenses.php",
      success:function(data) {
		//console.log(data);
		location.reload();
	   } //ajax success
	  })
	   }
	});
	
	$("body").delegate('.melo','blur',function() {
	var val = parseFloat($(this).val());
	var id = this.id;
	var str = id.split("_");
	var remain = parseFloat($("#"+str[1]).text());
	//alert(val+id+remain);
	if(val > remain) {
	alert("The value entered is greater than the remain content of the item to be use");
	$("#"+id).val("");
	   }
	});
	
	$("#closeweek").click(function() {
	 //get the of the button
	 var name = $(this).prop("name")
	 $.ajax({
      type:'POST',
	  data: {name:name},
      url: path+"/ajaxPages/closerearing_weeks.php",
      success:function(data) {
		//console.log(data);
		location.reload();
	   } //ajax success
    });						   
		});
	
	$("#closerearing").click(function() {
	 //get the of the button
	 var name = $(this).attr("name");
	 var stage = $("#stage").val();
	 //alert(name);
	  if (confirm("Are you sure? This will close "+ stage +" and all expenses entry will be forbidden!")){
	 $.ajax({
      type:'POST',
	  data: {name:name},
      url: path+"/ajaxPages/closerearing.php",
      success:function(data) {
		//console.log(data);
		location.reload();
	   } //ajax success
      });		
	 }
  });
	
  //select whether is rearing or laying 
  $('#rearingstage_Inq').change(function() {
	var stage = $(this).children("option:selected").val();
	//alert(stage);
	if(stage == 'LAYNG') 
		$('#page').val('layingInquiry');
		else if((stage == 'BRDNG') || (stage == 'GRWNG'))
		$('#page').val('rearingInquiry');
		else if(stage == 'FPOND')
		$('#page').val('pondInquiry');
	 $.ajax({
      type:'POST',
	  data: {stage:stage},
      url: path+"/ajaxPages/chooserearingorlaying.php",
      success:function(data) {
		//console.log(data);
		$("#showrearingorlaying").html("");
		$("#showrearingorlaying").html(data);
		//location.reload();
	   } //ajax success
      });				
	});
  
    $("#tagRef").change(function() {
	 var tag  =  $(this).children("option:selected").val();	
	 var whichstage = $("#whichstage").val();
	$.ajax({
      type:'POST',
	  data:{tag:tag,whichstage:whichstage},
      url: path+"/ajaxPages/getpenunderfarm.php",
      success:function(data) {
		  //console.log(data);
		$("#penname").html("");
		$("#penname").html(data);
	   } //ajax success
     }); 
	});
	
	 $("#penname").change(function() {
	 var pen  =  $(this).children("option:selected").val();	
	 var stage = $("#stage").val();
	$.ajax({
      type:'POST',
	  data:{pen:pen,stage:stage},
      url: path+"/ajaxPages/getbirdsunderfarm.php",
      success:function(data) {
		  console.log(data);
		$("#birdlist").html("");
		$("#birdlist").html(data);
	   } //ajax success
     }); 
	});
	 
	
	 $("#stage").change(function() {
	 var tag  =  $("#tagReport").children("option:selected").val();	
	 var stage = $(this).children("option:selected").val();	
	 var page = $("#page").val();
	 var signal = $("#signal").val();
	$.ajax({
      type:'POST',
	  data:{tag:tag,whichstage:stage,page:page,signal:signal},
      url: path+"/ajaxPages/getpenunderfarm.php",
      success:function(data) {
		  console.log(data);
		$("#penname").html("");
		$("#penname").html(data);
	   } //ajax success
     }); 
	});
	
	 $("#refresh").change(function() {
	 var tag  =  $(this).children("option:selected").val();	
	$.ajax({
      type:'POST',
	  data:{tag:tag},
      url: path+"/ajaxPages/viewfarmresourcesbypen.php",
      success:function(data) {
		 //console.log(data);
		//append this
		$("#refreshpen").html("");
		$("#refreshpen").html(data);
	   } //ajax success
     }); 
	});
	 
	//select user by depart ment 
	$(".yesno").click(function() {
	var val  =  $(this).val();
	if(val == 'Yes') 
		$("#hideorshowmanpr").removeClass("hide");
		else
		$("#hideorshowmanpr").addClass("hide");
	});
	
	 $(' body').delegate("#manpractice", "change", function() {
     var manpr  =  $(this).children("option:selected").val();
	 //alert(manpr);
	 var str = manpr.split(":");
	 if(str[1] == 'DEBEAKING') {
		 var exphead = str[0];
		var forminput = '<table class="selection table">' +
		'<tr><td colspan="3">Enter value for '+str[1]+'</td></tr>' +
		'<tr><td width="33%"><label>Cost Per Bird</label><input type="number" name="cost_'+exphead+'" id="cost_'+exphead+'" /></td>' +
		'<td width="33%"><label>How Many Bird Done?</label><input type="number" name="howmany_'+exphead+'" id="howmany_'+exphead+'" /></td>' +
		'</tr></table>';
		 } else if(str[1] == 'GRADING') {
			 //function 
			  $.ajax({
	  async: false,
      type:'GET',
	  global: false,
      dataType: 'html',
      url: path+"/ajaxPages/getgradinginfo.php?id="+str[0],
      success:function(data) {
      forminput = data;
	   } 
		});
		 } else if(str[1] == 'WEEKLY WEIGHING') { 
			  var exphead = str[0];
		var forminput = '<table class="selection table">' +
		'<tr><td colspan="2">Enter value for '+str[1]+'</td></tr>' +
		'<tr><td><label>Number of Bird Weighed </label><input type="text" name="per_'+exphead+'" id="per_'+exphead+'" /></td>' +
		'<td><label>Weight In (KG)</label><input type="number" name="weight_'+exphead+'" id="weight_'+exphead+'" /></td></tr>'+
		'</table>';	 
		 }
		 if($("#showmanprinputform").hasClass(str[1]) == false) {
	     $("#showmanprinputform").append(forminput);
		 $("#showmanprinputform").addClass(str[1]);
		  //let see it here
		var strs = $("#manTypes").val();
		if(strs == "")
		var strType = str[0]; // to avoid the preecing _
		else
		var strType = strs+"_"+str[0];
		$("#manTypes").val(strType);
		 }
	  
	});
	
	 $('body').delegate("#LocationName", "change", function() {
     var catid  =  $(this).children("option:selected").val();
	 var tag = $("#tagRef").children("option:selected").val();
    $.ajax({
      type:'POST',
	  data: {catid:catid,tag:tag},
      url: path+"/ajaxPages/getavailablestockitems.php",
      success:function(data) {
		  //console.log(data);
		  $("#stockitems").html("");
		 $("#stockitems").html(data);
	   } //ajax success
      });
	}); 
	 
	  $('body').delegate(".tagRef", "change", function() {
	 var tag = $("#tagRef").children("option:selected").val();
	 //alert(tag);
    $.ajax({
      type:'POST',
	  data: {tag:tag},
      url: path+"/ajaxPages/getexpensescategorywithitemshared.php",
      success:function(data) {
		  //console.log(data);
		  $("#LocationName").html("");
		 $("#LocationName").html(data);
	   } //ajax success
      });
	}); 
	
	
  $(' body').delegate('.selectedajax', 'change', function() {
     var penname  =  $(this).children("option:selected").val();	
	 var id = this.id;
	 var page = $("#page").val();
	 //alert(page);
	 if(id == 'penname')
	 var penname2 = '';
	 else
	 var penname2 = $("#penname").children("option:selected").val();
	  $.ajax({
      type:'POST',
	  data:{id:id,penname:penname,penname2:penname2,page:page},
      url: path+"/ajaxPages/gethowmanyweeks.php",
      success:function(data) {
		  //console.log(data);
		  if(id == 'penname') {
		$("#showlistofbirds").html("");
		 $("#showlistofbirds").html(data);
		  } else {
		$("#showlistofweeks").append(data);
		$("#runreport").attr("disabled", false);
		}
	   } //ajax success
     }); 
  });
    //transafer into laying 
   $('body').delegate('.rearingTransfer','change', function() {
	var layname  =  $(this).children("option:selected").val();
	var chickid = $("#listbird").children("option:selected").val();
	var penname = $("#penname").children("option:selected").val();
	var page = $("#page").val();
	   $.ajax({
      type:'POST',
	  data:{layname:layname,chickid:chickid,penname:penname,page:page},
      url: path+"/ajaxPages/getfarmhousescapacity.php",
      success:function(data) {
		  //console.log(data);
		  var getdata = $.parseJSON(data);
		  //capacity to hold
		$("#capacitytohold").html("");
		$("#capacitytohold").html(getdata.capacity);
		//available to transfer
		$("#availablefortranster").html("");
		$("#availablefortranster").html(getdata.totransfer);
		//the breed
		$("#breed").val(getdata.breed);
		//rearing cost per head
		$("#rearingcost").val(getdata.rearingcost);
		//supplier name
		$("suppliername").val(getdata.suppliername);
	   } //ajax success
       }); 								 
	});
  //receive into laying 
  $('body').delegate('.receiveintoLaying','change', function() {
	var layname  =  $(this).children("option:selected").val();
	var page = $("#page").val();
	   $.ajax({
      type:'POST',
	  data:{layname:layname,page:page},
      url: path+"/ajaxPages/getfarmhousescapacity.php",
      success:function(data) {
		  //console.log(data);
		  var getdata = $.parseJSON(data);
		  //capacity to hold
		$("#capacitytohold").html("");
		$("#capacitytohold").html(getdata.capacity);
	   } //ajax success
       }); 								 
	});
  //for broding get theior capacity 
   $('body').delegate('.penname','change', function() {
	var page = $("#page").val();
	var penname  =  $(this).children("option:selected").val();
	   $.ajax({
      type:'POST',
	  data:{penname:penname,page:page},
      url: path+"/ajaxPages/getholdingcapacity.php",
      success:function(data) {
		  //console.log(data);
		  var getdata = $.parseJSON(data);
		  //capacity to hold
		$("#capacitytohold").html("");
		$("#capacitytohold").html(getdata.capacity);
	   } //ajax success
       }); 								 
	});
   
  
   //checking the quantity transfer
   $("#quantity").blur(function() {
	var val = $(this).val();
	var page = $("#page").val();
	if((page == 'RearingTransfer') || (page == 'LayingTransfer')) {
	var layname  =  $("#layname").children("option:selected").val();
	var chickid = $("#listbird").children("option:selected").val();
	var penname = $("#penname").children("option:selected").val();
		var availto = $("#availablefortranster").text();
	}  else if(page == 'PointofLayTransfer') {
		var chickid = '';
		var penname = '';
		var availto  = '';
		var layname  =  $("#layname").children("option:selected").val();
	} else if(page == 'BroodingTransfer') {
		var penname = $("#penname").children("option:selected").val();
		var chickid = '';
		var layname = '';
		var availto = $("#availablefortranster").text();
	}
	$.ajax({
      type:'POST',
	  data:{val:val,layname:layname,chickid:chickid,penname:penname,page:page,availto:availto},
      url: path+"/ajaxPages/checkquantityentered.php",
      success:function(data) {
	  if(data !== 'Ok') {
		  alert(data);
	  $("#quantity").val("");
	 //location.reload();
	    }
	  }
		   });
	 });
   
    $("#openlaying").change(function() {
	var chickid = $(this).children("option:selected").val();
		$.ajax({
      type:'POST',
	  data:{chickid:chickid},
      url: path+"/ajaxPages/getopenlayinginfo.php",
      success:function(data) {
		  //console.log(data);
		  var data = $.parseJSON(data);
		   $("#weeks").val("");
		 $("#weeks").val(data.whatweek); //what week is it 
		  $("#days").val("");
		 $("#days").val(data.whatday); //what day is it 
		  $("#totalbird").val("");
		 $("#totalbird").val(data.population); //total bird and population
		 $("#pen_id").val("");
		 $("#pen_id").val(data.pen_id); //pen id
		 //information purposes
		 $("#whatweek").html("");
		 $("#whatweek").html("Production week "+data.whatweek+"  and day "+data.whatday);
		 if(data.mortality == null)
		 var mor = 0;
		 else
		 var mor = data.mortality;
		 //information purposes
		 $("#birds").html("");
		 $("#birds").html("Starting Stock: "+data.stock+" | Total Mortality: "+mor+" | Total population: " + data.population);
		 
	//{"pen_id":"LAY01","whatweek":"23","whatday":"1","mortality":"1","stock":"1996","population":1995} 
	  }
		   });
	 });
	
   $("#eggtype").change(function() {
	var eggtype = $(this).children("option:selected").val();
	var inputform = '<div class="input-group mb-3 egg_'+eggtype+'">' +
			        '<div class="input-group-append">' +
                    '<button class="btn btn-light" type="button">'+eggtype+' EGGS</button></div>' +
					'<input type="number" class="form-control" name="eggperday_'+eggtype+'" id="eggperday_'+eggtype+'" />' +
                    '<div class="input-group-append">' +
                    '<button class="btn btn-dark removeEggType" type="button" id="'+eggtype+'">Remove</button></div></div>';
	if($("#whichegg").hasClass(eggtype) == false) {
		$("#whichegg").append(inputform);
		$("#whichegg").addClass(eggtype);
	} else
	 alert("This option is already added just modify it or remove it, if you fill it's added by mistake");
	 });
   
   $('body').delegate(".removeEggType",'click', function() {
	var ids = $(this).attr('id');
	//alert(ids);
	$(".egg_"+ids).remove();
	$("#whichegg").removeClass(ids);
	});
   
   //getting actual production pecentatge daily per pen 
   $("#eggperday").blur(function() {
	var val = $(this).val()
	var layname = $("#layname").children("option:selected").val();
	$.ajax({
      type:'POST',
	  data:{val:val,layname:layname},
      url: path+"/ajaxPages/eggproductionpercentage.php",
      success:function(data) {
		  //console.log(data);
	  $("#egg_percentage").val("");
	  $("#egg_percentage").val(data);
	  }
		   });
	});
   
    $("#eggstatus").change(function() {
	var eggstatus = $(this).children("option:selected").val();
	var inputform = '<table width="100%" class="egg_'+eggstatus+'"><tr>' +
			'<td style="border:none"><label>'+eggstatus+' Quantity</lable>' +
			'<input type="number" class="form-control candlingqty" name="qty_'+eggstatus+'" id="qty_'+eggstatus+'" /></td>' +
	        '<td style="border:none"><label>'+eggstatus+' Percentage(%)</lable>'+
			'<input type="number" readonly class="form-control" name="per_'+eggstatus+'" id="per_'+eggstatus+'" /></td>' +
			'<td style="border:none"><button class="btn btn-danger removeCandlinStatus" type="button" id="'+eggstatus+'">Remove</button></td>' +
			'</tr></table';
	if($("#candlingdata").hasClass(eggstatus) == false) {
		$("#candlingdata").append(inputform);
		$("#candlingdata").addClass(eggstatus);
	} else
	 alert("This option is already added just modify it or remove it, if you fill it's added by mistake");
	 });
   
   $('body').delegate(".removeCandlinStatus",'click', function() {
	var ids = $(this).attr('id');
	//alert(ids);
	$(".egg_"+ids).remove();
	$("#candlingdata").removeClass(ids);
	});
   
   //calaculating percentages
   $("body").delegate(".candlingqty",'blur', function() {
	var value = $(this).val();
	var idr = $(this).attr('id');
	var str = idr.split('_');
	var total = $("#eggnumber").val();
	var per = (value / total) * 100;
	//use that store the ething 
	$("#per_"+str[1]).val(per)
   });
   
   //get teh age
   $(".age").click(function() {
	var whichisit = this.value
	if(whichisit == 'Day') {
	$("#days").removeClass("hide");
	$("#weeks").addClass("hide");
	} else {
		$("#weeks").removeClass("hide");
	$("#days").addClass("hide");
	}
     });
   
   $("#hcstatus").change(function() {
	var hcstatus = $(this).children("option:selected").val();
	var abo = ''
	if(hcstatus == 'Good')
	 abo = 'Chicks';
	else
	 abo = 'Unhatch';
	var inputform = '<table width="100%" class="egg_'+hcstatus+'"><tr>' +
			'<td style="border:none"><label>'+hcstatus+' '+abo +' Quantity</lable>' +
			'<input type="number" class="form-control hatchingqty" name="qty_'+hcstatus+'" id="qty_'+hcstatus+'" /></td>' +
	        '<td style="border:none"><label>'+hcstatus+' '+abo +' Percentage(%)</lable>'+
			'<input type="number" readonly class="form-control" name="per_'+hcstatus+'" id="per_'+hcstatus+'" /></td>' +
			'<td style="border:none"><button class="btn btn-danger removeHatchingStatus" type="button" id="'+hcstatus+'">Remove</button> '+
			'</td></tr></table';
	if($("#hatchingdata").hasClass(hcstatus) == false) {
		$("#hatchingdata").append(inputform);
		$("#hatchingdata").addClass(hcstatus);
	} else
	 alert("This option is already added just modify it or remove it, if you fill it's added by mistake");
	 });
   
   $('body').delegate(".removeHatchingStatus",'click', function() {
	var ids = $(this).attr('id');
	//alert(ids);
	$(".egg_"+ids).remove();
	$("#hatchingdata").removeClass(ids);
	});
   
   //calaculating percentages
   $("body").delegate(".hatchingqty",'blur', function() {
	var value = $(this).val();
	var idr = $(this).attr('id');
	var str = idr.split('_');
	var total = $("#fertile").val();
	var per = (value / total) * 100;
	//use that store the ething 
	$("#per_"+str[1]).val(per)
   });
   
	$("#submitHatching").click(function() {
	 var formdata = $("#hatchingForm").serialize();
	 if(confirm("Sure you're ready to submit? This action is not reversible!")){
	 $.ajax({
      type:'POST',
      data: formdata,
      url: path+"/ajaxPages/submithatching_activities.php",
      success:function(data) {
		 //var ret = $.parseJSON(data);
		  window.location.reload();
		 //console.log(data);
		/* if((ret.mes == 'TOOMUCH') && (ret.stage == 'candling')) {
		 alert('The quantity entered is more than the egg transfered to hatching! Please check and try again');
		 window.location.reload();
		 } else if((ret.mes == 'TOOMUCH') && (ret.stage == 'hatched')) {
		 alert('The quantity entered is more than the fertile egg transfered into hatchery! Please check and try again');
		 window.location.reload();
		 } else if((ret.mes == 'OK') && (ret.stage == 'hatched'))
		  window.location.reload();
		   else if((ret.mes == 'OK') && (ret.stage == 'candling'))
		  window.location.reload();
		   else if((ret.mes == 'OK') && (ret.stage == 'hatchery'))
		  window.location.reload();
		   else if((ret.mes == 'OK') && (ret.stage == 'setter'))
		  window.location.reload();*/
	       } //ajax success
	     })
	   }
	});
	
  });