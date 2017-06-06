<?php 

if(!isset($_SESSION)){
	session_start();
}

if(!isset($_SESSION['user'])){
	header('Location:/');
	die();
}

include('includes/header.php'); 

?>


<div class='page'>
<div class='chat'>
		<div class='chattext'>
		</div>
		<input type='text' class='newmessage' />
	</div>
	<div class='main'>
		<div class='list'>
			<div class='searchstuff'>
				<div class='row'>
					<input type='text' class='searchword' />
					<input type='button' value='Search' class='search' />
				</div>
				<div class='row'>
					<input class='moreoptions' type='button' value='More Options' />
				</div>
				<div class='more'>
					<div class='row'>
						
						
							
						
					</div>
					<div class='row'>
						<div class='splace'>
						
						</div>
						<div class='stype'>
							<strong>Types</strong><br/>
							<input type='checkbox' id='schoice' />
							<label for='schoice'>Choice</label><br />
							<input type='checkbox' id='sdealwithit' />
							<label for='sdealwithit'>Deal with it</label><br />
						</div>
						<div class='sstatus'>
							<strong>Status</strong><br />
							<input type='checkbox' id='sgettingstarted' />
							<label for='sgettingstarted'>Getting Started</label><br />
							<input type='checkbox' id='sneedswork'/>
							<label for='sneedswork'>Needs Work</label><br />
							<input type='checkbox' id='salmostthere' />
							<label for='salmostthere'>Almost There</label><br />
							<input type='checkbox' id='sdone' />
							<label for='sdone'>Done</label><br />
						</div>
					</div>
							
				</div>
			</div>
			<div class='row'>
				<input class='new' value='New Card' type='button' />
			</div>
			<table class='cards'>
			</table>
			<div class='nothing'>No results found.</div>
		</div>
		<div class='one'>
			<div class='hidden'></div>
			<div class='row edit'></div>
			<div class='row'>
				<label for='status'>Status:</label>
				<select id='status' class='status'>
					<option value='Getting started'>Getting started</option>
					<option value='Needs work'>Needs work</option>
					<option value='Almost there'>Almost there</option>
					<option value='Done'>Done</option>
				</select>
			</div>
			<div class='row'><label for='name'>Name:</label><input type='text' id='name' class='name' /></div>
			<div class='row'><label for='place'>Region/Building:</label>
				<select id='place' class='place'>
				</select>
			</div>
			<div class='row'><div><label for='description'>Description:</label></div><div><textarea id='description' class='description'></textarea></div></div>
			<div class='row'><div class='fake'>Type:</div>
			<label for='choice' class='radio'>Choice</label><input name='type' type='radio'  value='choice' class='choiceopt' />
			<label for='dealwithit' class='radio'>Deal With It</label><input name='type' type='radio'value='dealwithit' class='dealwithit' />
			
			<div class='deal'>
				<div class='row'><div><label for='outcome'>Outcome:</label></div><div><textarea id='outcome' class='outcome'></textarea></div></div>
			</div>
			<div class='choice'>
				<div class='choice1'>
					<div class='row'>
						<div class='row'><div><label for='option1'>Option 1:</label></div><div><textarea id='option1' class='option1'></textarea></div></div>
						<div class='row'><label class='roll' for='roll11'>Roll 1 to</label><input class='roll11 roll' id='roll11' type='text' /></div>
						<div class='row'><div><label for='good1'>Good Outcome:</label></div><div><textarea id='good1' class='good1'></textarea></div></div>
						<div class='row'><label class='roll' for='roll12'>Roll</label><input class='roll12 roll' id='roll12' type='text' /> to 6</div>
						<div class='row'><div><label for='bad1'>Bad Outcome:</label></div><div><textarea id='bad1' class='bad1'></textarea></div></div>
					</div>
				</div>
				<div class='choice2'>
					<div class='row'>
						<div class='row'><div><label for='option2'>Option 2:</label></div><div><textarea id='option2' class='option2'></textarea></div></div>
						<div class='row'><label class='roll' for='roll21'>Roll 1 to</label><input class='roll21 roll' id='roll21' type='text' /></div>
						<div class='row'><div><label for='good2'>Good Outcome:</label></div><div><textarea id='good2' class='good2'></textarea></div></div>
							<div class='row'><label class='roll' for='roll22'>Roll</label><input class='roll22 roll' id='roll22' type='text' /> to 6</div>
						<div class='row'><div><label for='bad2'>Bad Outcome:</label></div><div><textarea id='bad2' class='bad2'></textarea></div></div>
					</div>
				</div>
			</div>
				
			
		</div>
		<div class='row buttons'><input type='button' value='Save' class='save' /><input type='button' value='Save and exit' class='exit' /><input value='Cancel' class='cancel' type='button' /></div>
		<div class='row'>
			<input type='checkbox' id='old' class='old' /><label for='old'>Show previous versions</label>
		</div>
		<div class='row olddiv'>
		</div>
	</div>

</div>
	

<script type='text/javascript'>
var lastid = -1;
function drawTable(data){

	var str = "<thead><tr><th>Name</th><th>Description</th><th>Type</th><th>Status</th></thead><tbody>";
	for(var i = 0; i < data.length; i++){
		
		str += "<tr class='row' id='rev-" + data[i].id + "'><td>" + data[i].name + "</td><td>" + data[i].description + "</td>";
		if(data[i].outcome == null){
			str += "<td class='type'>Choice</td>";
		}else{
			str += "<td class='type'>Deal with it</td>";	
		}
		str += "<td class='status'>" + data[i].status + "</td></tr>";
		
	}
	
	str += "</tbody>";
	
	$(".cards").html(str);
	
}

function enterPlaces(data){
	var str ="";
	var sstr = "<strong>Buildings / Regions:</strong><br />";
	for(var i = 0; i < data.length; i++){
		str += "<option value='" + data[i].id + "' id='place-" + data[i].id + "'>" + data[i].name + "</option>";
		sstr += "<input type='checkbox' id='s" + data[i].name.toLowerCase().replace(/\s+/g, '') + "' /><label for='s" + data[i].name.toLowerCase().replace(/\s+/g, '') + "'>" + data[i].name + "</label><br />";
		
	}
	
	$(".place").html(str);
	$(".splace").html(sstr);
}

function pad(num, size) {
    var s = "0000" + num;
    return s.substr(s.length-size);
}

function switchToCard(data){
	$(".old").attr('checked', false);
	
	$(".name").val(data.name);
	$(".place").val(data.place);
	$(".description").val(data.description);
	$(".hidden").html(data.id);
	
	
	if(data.outcome == null){
		$(".deal").hide();
		$(".choice").show();
		$(".option1").val(data.option1);
		$(".roll11").val(data.roll1);
		$(".roll12").val(parseInt(data.roll1) + 1);
		$(".good1").val(data.good1);
		$(".bad1").val(data.bad1);
		$(".option2").val(data.option2);
		$(".roll21").val(data.roll2);
		$(".roll22").val(parseInt(data.roll2) + 1);
		$(".good2").val(data.good2);
		$(".bad2").val(data.bad2);
		$(".choiceopt").prop("checked", true);
		$(".dealwithit").removeAttr("checked");
		
	}else{
		$(".deal").show();
		$(".choice").hide();
		$(".dealwithit").prop("checked", true);
		$(".choiceopt").removeAttr("checked");
		$(".outcome").val(data.outcome);	
	}
	if(data.intials == null){
		$(".edit").hide();
	}else{
		$(".edit").show();
	}
	$(".edit").html("Last edited by <span style='color:#" + data.colour + "'>" + data.initials + "</span> at " + data.edittime + ".");
	$('.status').val(data.status);

}

function save(exit){
	var stuff = Object();
	
	stuff.name = $(".name").val();
	stuff.description = $(".description").val();
	stuff.place = $(".place").val();
	stuff.type = $("input[name='type']:checked").val();
	stuff.id = $(".hidden").html();
	stuff.status = $(".status").val();
	
	if(stuff.type == 'choice'){
		stuff.option1 = $(".option1").val();
		stuff.roll1 = $(".roll11").val();
		stuff.good1 = $(".good1").val();
		stuff.bad1 = $(".bad1").val();
		stuff.option2 = $(".option2").val();
		stuff.roll2 = $(".roll21").val();
		stuff.good2 = $(".good2").val();
		stuff.bad2 = $(".bad2").val();

		
	}else{
		stuff.outcome = $(".outcome").val();
	}
	$.ajax({
		url: "/ajax.php?action=save",
		data:stuff
	}).done(function(data) {
		console.log(data);
		if(data == ""){
			window.location.href = "/index.php";
		}else{
			if(exit){
				drawTable($.parseJSON(data).cards);	
				$(".list").slideDown();	
				$(".one").slideUp();
			}else{
				var update = $.parseJSON(data).update;
				$(".edit").html("Last edited by <span style='color:#" + update.colour + "'>" + update.initials + "</span> at " + update.edittime + ".");
			}
		}
		
	}).fail(function(A, B, C) {
		alert("BAD:" + A + ":" + B + ":" + C);
	});	
}

function showOldStuff(data){
	var str = "";
	for(var i = 0; i < data.length; i++){
		str += "<div class='edit'>";
		str += "<div class='row'><input type='button' class='revert' id='rev-" + data[i].rid + "' value='Revert to this Version' /></div>";
		str += "<div class='row'>Edit by <span style='color:#" + data[i].colour + "'>" + data[i].initials + "</span> at " + data[i].edittime + ".</div>";
		str += "<div class='row'>Name: " + data[i].name + "</div>";
		str += "<div class='row'>Description: " + data[i].description + "</div>";
		str += "<div class='row'>Place: " + data[i].placename + "</div>";
		
		if(data[i].outcome == null){
			str += "<div class='opt'><div class='row'>Option 1: " + data[i].option1 + "</div>";
			str += "<div class='row'>Roll 1 to" + data[i].roll1 + "</div>";
			str += "<div class='row'>Good Outcome: " + data[i].good1 + "</div>";
			str += "<div class='row'>Roll " + (parseInt(data[i].roll1) + 1) + " to 6</div>";
			str += "<div class='row'>Bad Outcome: " + data[i].bad1 + "</div></div>";
			str += "<div class='opt'><div class='row'>Option 2: " + data[i].option2 + "</div>";
			str += "<div class='row'>Roll 1 to " + data[i].roll2 + "</div>";
			str += "<div class='row'>Good Outcome: " + data[i].good2 + "</div>";
			str += "<div class='row'>Roll " + (parseInt(data[i].roll2) + 1) + " to 6</div>";
			str += "<div class='row'>Bad Outcome:" + data[i].bad2 + "</div></div>";
		}else{
			str += "<div class='row'>Outcome:" + data[i].outcome + "</div>";	
		}
		
		str += "</div>";
	}
	
	$(".olddiv").html(str);
	
}

function search(){
	
	$(".cards > tbody > tr").show();
	
	var found = false;
	$(".cards > tbody > tr").each(function(i, v){
			var bad = false;
			if($(".searchword").val() != "" && $(this).html().includes($(".searchword").val())){
				bad = true;
			}
			if($(".more").is(":visible")){
				if(!$("#s" + $(this).find(".type").html().toLowerCase().replace(/\s+/g, '')).is(':checked')){
					bad = true;
				}
				
				if(!$("#s" + $(this).find(".status").html().toLowerCase().replace(/\s+/g, '')).is(':checked')){
					bad = true;
				}
					
			}
			
			if(!bad){
				found = true;
			}else{
				$(this).hide();	
			}
	});
	if(found){
		$(".nothing").hide();
		
	}else{
		$(".nothing").show();	
	}	
}


function getMessages(){
	$.ajax({
			url: "/ajax.php?action=getmessages",
			data:{"last":lastid}
		}).done(function(data) {
			console.log(data);
			if(data == ""){
				window.location.href = "/index.php";
			}else{
				data = $.parseJSON(data);
				if(data.length > 0){
					lastid = data[data.length - 1].id;
					var str = "";
					for(var i = 0; i < data.length; i++){
						str += "<div style='color:#" + data[i].colour + "'>" + data[i].initials + ":" + data[i].message + "</div>";
					}
					$(".chattext").html($(".chattext").html() + str);
					$(".chattext").scrollTop($(".chattext").prop("scrollHeight"));
				}
				setTimeout(getMessages, 1000);
			}
			
		}).fail(function(A, B, C) {
			alert("BAD:" + A + ":" + B + ":" + C);
	});	
	
}
$(document).ready(function(){

$.ajax({
			url: "/ajax.php?action=cards",
		}).done(function(data) {
			console.log(data);
			if(data == ""){
				window.location.href = "/index.php";
			}else{
				drawTable($.parseJSON(data));
			}
			
		}).fail(function(A, B, C) {
			alert("BAD:" + A + ":" + B + ":" + C);
	});	
		
$.ajax({
	url: "/ajax.php?action=places",
	}).done(function(data) {
		console.log(data);
		if(data == ""){
			window.location.href = "/index.php";
		}else{
			enterPlaces($.parseJSON(data));
		}
		
	}).fail(function(A, B, C) {
		alert("BAD:" + A + ":" + B + ":" + C);
});	
		
$(".cards").on('click', '.row',function(){
	$.ajax({
			url: "/ajax.php?action=card&id=" + $(this).attr('id').substring(4),
		}).done(function(data) {
			console.log(data);
			if(data == ""){
				window.location.href = "/index.php";
			}else{
				switchToCard($.parseJSON(data));
				$(".old").removeAttr('disabled');
				$(".list").slideUp();
				$(".one").slideDown();
			}
			
		}).fail(function(A, B, C) {
			alert("BAD:" + A + ":" + B + ":" + C);
	});	
});

$('input[type=radio][name=type]').change(function() {
	if ($("input[name='type']:checked").val() == 'choice') {
		$(".deal").slideUp();
		$(".choice").slideDown();
	}
	if ($("input[name='type']:checked").val() == 'dealwithit') {
		$(".choice").slideUp();
		$(".deal").slideDown();
	}
});

$(".cancel").click(function(){
	if(confirm("This will undo all changes without saving. Are you sure?")){
	
	$.ajax({
		url: "/ajax.php?action=cards",
	}).done(function(data) {
		//alert(data);
		if(data == ""){
			window.location.href = "/index.php";
		}else{
			drawTable($.parseJSON(data));
			$(".list").slideDown();	
			$(".one").slideUp();
		}
		
		}).fail(function(A, B, C) {
			alert("BAD:" + A + ":" + B + ":" + C);
	});	
		
	}
});

$(".save").click(function(){
	save(false);

});

$(".exit").click(function(){
	save(true);
});

$(".old").change(function(){
	if(this.checked){
		$.ajax({
			url: "/ajax.php?action=old&id=" + $(".hidden").html(),
		}).done(function(data) {
			console.log(data);
			if(data == ""){
				window.location.href = "/index.php";
			}else{
				showOldStuff($.parseJSON(data));
				$(".olddiv").show();
			}
			
		}).fail(function(A, B, C) {
			alert("BAD:" + A + ":" + B + ":" + C);
		});	
	}else{
		$(".olddiv").hide();
		
		
	}
});



$(".olddiv").on('click', '.revert', function(){
	$.ajax({
		url: "/ajax.php?action=revert&id=" + $(this).attr('id').substring(4),
	}).done(function(data) {
		console.log(data);
		if(data == ""){
			window.location.href = "/index.php";
		}else{
			
			switchToCard($.parseJSON(data));
			$(".old").removeAttr('disabled');
			$('.old').attr('checked', false); 
			$(".olddiv").hide();
		}
		
	}).fail(function(A, B, C) {
		alert("BAD:" + A + ":" + B + ":" + C);
	});	
	
});

$(".search").click(function(){
	search();
});

$(".moreoptions").click(function(){
	if($(".more").is(":visible")){
		$(".more").slideUp(function(){search();});
		$(".moreoptions").val("More Options");
	}else{
		$(".more").slideDown();
		$(".moreoptions").val("Less Options");
		$(".more input[type='checkbox']").prop("checked", true);
	}
});


$(".new").click(function(){
	var data = Object();
	data.roll1 = 1;
	data.roll2 = 1;
	data.place = 1;
	data.status = "Getting started";
	$(".old").attr('disabled', true);
	switchToCard(data);	
	$(".list").slideUp();
	$(".one").slideDown();
});

$(".roll").on("change", function(){
	var first = $(this).attr('id').substring(4,5);
	var second = $(this).attr('id').substring(5,6);
	var other;
	var newNum = parseInt($(this).val());
	if($(this).val() % 1 != 0 || newNum < 1 || newNum > 6){
		alert("Invalid dice roll");
		$(this).select();
		$(this).css('background', '#ff0000');
		return;
	}else{
		$(this).css('background', '#ffffff');
	}
	if(second == 2){
		//The bottom number is changed
		other = newNum - 1;
		$("#roll" + first + "1").val(other);
	}else{
		//The top number is changed
		other = newNum + 1;
		$("#roll" + first + "2").val(other);
	}

	
});

$('.newmessage').bind("enterKey",function(e){
  $.ajax({
		url: "/ajax.php?action=message",
		data:{"message":$(this).val()}
	}).done(function(data) {
		console.log("Sending:" + data);
		if(data == ""){
			window.location.href = "/index.php";
		}else{
			
			$('.newmessage').val('');
		}
		
	}).fail(function(A, B, C) {
		alert("BAD:" + A + ":" + B + ":" + C);
	});	
});
$('.newmessage').keyup(function(e){
    if(e.keyCode == 13)
    {
        $(this).trigger("enterKey");
    }
    
});


setTimeout(getMessages, 1000);

});
</script>
<?php include('includes/footer.php'); ?>

