
//Owner Page
$("button#new_owner").click(function(){
	//$(this).slideUp();
	$.get("lib.php",{new_owner: $("input#add_owner_text").val() });
	//window.location.reload(true);
	//var t = setTimeout(window.location.reload(true),3000);
	
});
$("button#add_owner_button").click(function(){
	$("#add_owner2").show();
	$("#add_owner1").hide();

});