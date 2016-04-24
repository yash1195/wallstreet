// global variables
var companies = ["NASDAQ:GOOG" , "NASDAQ:YHOO"];
var temp = "";
var selectedStock = ""; // stores the stock name of the current transaction
var fence2 = false; // turns true if user answers buy or sell
var fence3 = false; // turns true if user enters the amount of stock
var fence4 = false; // turns true if user confirms purchase
var stockAmt = 0; // stores the amount of stock to be traded
var bors = ""; // stores whether it is a purchase or a sale
$(document).on('click','.block',function(){
	var lval = $('.tunnel').css('left');
	var eleID = this.id;
	if(eleID == 'block1')
	{
		$('.tunnel').animate({
			'left':'4%'
		});
	}
	else if(eleID == 'block2')
	{
		$('.tunnel').animate({
			'left':'-46%'
		});
	}
	else
	{
		$('.tunnel').animate({
			'left':'-96%'
		});
	}
});
// update page
function updatePage(){
	// START TRADING
	startTrade();
	var mydata = {};
	$.get("api/mystocks/index.php",function(e){
		mydata = JSON.parse(e);

	});
	$.get("api/stocks/index.php",function(e){
		$('.dynamic-table').html("");
		var obj = JSON.parse(e);
		var vol ="0";
		for(var i = 0 ; i < obj.length ; i++)
		{
			for(var j=0 ; j< mydata.length;j++)
			{
				vol = "0";
				if(obj[i].stockname == mydata[j].stockname)
				{
					vol  = mydata[j].stockvol;
					break;
				}
			}
			$('.dynamic-table').append("<a class='row-link' href=''><div class='row' data-stockname='"+obj[i].stockname+"'><div class='col'>"+obj[i].stockname+"</div><div class='col'>"+obj[i].c+"</div><div class='col'>"+obj[i].l+"</div><div class='col'>"+obj[i].PCLS+"</div><div class='col'>"+vol+"</div></div></a>");
		}
	});
	$('.total').html("0");
	$.get("api/incash/index.php",function(e){
		var o =JSON.parse(e);
		$('.incash').html(o.cash);
		var init = parseInt($('.total').text(),10);
		init += parseInt(o.cash,10);
		$('.total').html(init);
	});

	$.get("api/instock/index.php",function(e){
		var o =JSON.parse(e);
		$('.instock').html(o.instock);
		var init = parseInt($('.total').text(),10);
		init += parseInt(o.instock,10);
		$('.total').html(init);
	});

	//$('.total').html(total);
	$.get("api/cattle/index.php",function(e){
		var o = JSON.parse(e);

		$('.leaders').html(o.leaders);
		$('.atpar').html(o.atpar);
		$('.trailers').html(o.trailers);
	});
}
// blinking terminal
$(document).ready(function() {
    var f = document.getElementById('blinker');
    setInterval(function() {
        f.style.display = (f.style.display == 'none' ? '' : 'none');
    }, 200);	
    updatePage();
    
    //select terminal
    var terminal = document.getElementById ('terminal-input');
    terminal.select();
});

$(document).on('click','#terminal',function(){
	var terminal = document.getElementById ('terminal-input');
    terminal.select();
});
//select stock
$(document).on('click','.row',function(){
	var temp = $(this).data("stockname");
	$('.terminal-text').append("<br>"+temp+" selected.<br>");
	getSideTag();
	$('.terminal-text').append("@wallstreet:-$ "+"Enter (b) to buy stock or (s) to sell stock - ");
	var terminal = document.getElementById ('terminal-input');
	localStorage.setItem("stockname",temp);
	terminal.focus();
});
function getSideTag()
{
	var usr = $('#username').text();
	$('.terminal-text').append("<br>"+usr+"@wallstreet:-$ ");	
}
//clearing the terminal input
function clearInput()
{
	var temp = $('#terminal-input').val();
	$('.terminal-text').append(" "+temp+"<br>");
	$('#terminal-input').val("");
	
}
// START TRADE
function startTrade()
{
	selectedStock = "";
	fence2 = false;
	fence3 = false;
	fence4 = false;
	getSideTag();
	$('.terminal-text').append("Select stock to trade...");
}
// restart trade function
function redoTrade()
{
	selectedStock = "";
	fence2 = false;
	fence3 = false;
	fence4 = false;
	getSideTag();
	$('.terminal-text').append("Select stock to trade...");
	var objDiv = document.getElementById(".terminal");
	objDiv.scrollTop = objDiv.scrollHeight;

}
function clearTerminal()
{
	$('.terminal-text').html("");
	// resetting variables
	selectedStock = "";
	fence2 = false;
	fence3 = false;
	fence4 = false;
	startTrade();
}
// BUY STOCK
function buyStock(stock,amt)
{
	$('.terminal-text').append("loading ...<br>");
	var data = {};
	data.stockname = stock;
	data.stockvol = amt;
	var url = 'api/buy/index.php';
	$.post(url,data,function(e){
		var obj = $.parseJSON(e);
		if(obj.status == '307')
		{
			$('.terminal-text').append("<br>***** Transaction Complete *****<br><br>");
			updatePage();
			//redoTrade();
		}
		else if(obj.status == '305')
		{
			$('.terminal-text').append("<br>***** Insuffecient Cash *****<br><br>");
			redoTrade();
		}
		else
		{
			$('.terminal-text').append("<br>***** Transaction Failed *****<br><br>");
			redoTrade();
		}
	});
}
// SELL STOCK
function sellStock(stock,amt)
{
	$('.terminal-text').append("loading ...<br>");
	var data = {};
	data.stockname = stock;
	data.stockvol = amt;
	var url = 'api/sell/index.php';
	$.post(url,data,function(e){
		var obj = $.parseJSON(e);
		if(obj.status == '307')
		{
			console.log('trans done');
			$('.terminal-text').append("<br>***** Transaction Complete *****<br><br>");
			updatePage();
			//redoTrade();
		}
		else if(obj.status == '308')
		{
			$('.terminal-text').append("<br>***** You do not own this stock *****<br><br>");
			redoTrade();
		}
		else if(obj.status == '309')
		{
			$('.terminal-text').append("<br>***** You do not own enough stock *****<br><br>");
			redoTrade();
		}
		else
		{
			$('.terminal-text').append("<br>***** Transaction Failed *****<br><br>");
			redoTrade();
		}
	});
}
// TERMINATE TRANSACTION
function terminateTrade()
{
	$('.terminal-text').append("<br>***** Transaction Terminated *****<br><br>");
	redoTrade();	
}
// close help page
$(document).on('click','#rules-done',function(){
	$('#help').css({
		'display':'none'
	});
});
$(document).on("keyup",'#terminal-input',function(e){
	selectedStock = localStorage.getItem("stockname");
	var charCode = e.which || e.keyCode;
	var str = $(this).val();
	if(charCode == 13){
		if(!fence2 && str == "")
		{
			console.log('here');
			clearInput();
			$('.terminal-text').append("invalid input<br>");
			redoTrade();
		}
		else if(str == "clear" || str == "CLEAR"){
			clearInput();
			clearTerminal();
		}
		else if(str == "b" || str == "B"){
			bors = 'b';
			if(selectedStock == "")
			{
				$('.terminal-text').append("<br>kindly select a stock from the list<br>");
				redoTrade();
				clearInput();
			}
			else
			{
				clearInput();
				fence2 = true;
				getSideTag();
				$('.terminal-text').append("Enter the number of stocks you want to buy - <br>");	
			}
		}
		else if(str == "s" || str == "S"){
			bors = 's';
			if(selectedStock == "")
			{
				$('.terminal-text').append("<br>kindly select a stock from the list<br>");
				redoTrade();
			}
			else
			{
				clearInput();
				fence2 = true;
				getSideTag();
				$('.terminal-text').append("Enter the number of stocks you want to sell - <br>");	
			}
		}
		else if((str == 'y' || str == 'Y') && selectedStock != "" && fence2 && fence3)
		{
			// function to trade stock
			if(bors == 'b')
			{
				clearInput();
				selectedStock = localStorage.getItem("stockname");
				buyStock(selectedStock,stockAmt);
			}
			else
			{
				clearInput();
				selectedStock = localStorage.getItem("stockname");
				sellStock(selectedStock,stockAmt);
			}
		}
		else if(!isNaN(str) && selectedStock != "" && fence2)
		{
			clearInput();
			stockAmt = str;
			getSideTag();
			fence3 = true;
			if(bors == 'b')
			{
				$('.terminal-text').append("Enter (y) to confirm purchase of "+str+" "+selectedStock+" shares.<br>Enter any other key to abort - ");	
			}
			else
			{
				$('.terminal-text').append("Enter (y) to confirm sale of "+str+" "+selectedStock+" shares.<br>Enter any other key to abort - ");	
			}
		}
		else if(str == "help" || str == "HELP")
		{
			$('#help').css({
				'display':'block'
			});
		}
		else if(fence2 && fence3 || str == "")
		{
			//TERMINATE TRADE
			terminateTrade();
		}
		else if(str == "whoareyou?" || "who are you?" || "who are you")
		{
			$("#hawk").show().delay(800).fadeOut();
			clearInput();
			redoTrade();
		}
		else
		{
			clearInput();
			$('.terminal-text').append("invalid input<br>");
			redoTrade();
		}
		$('#terminal').animate({scrollTop: $('#terminal').prop('scrollHeight')});
	}
});