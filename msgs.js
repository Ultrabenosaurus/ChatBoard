/* main functionality */
// when document finishes loading, update messages display every second
$(document).ready(function(){
	readMsgs();
	setTimeout(function(){
		$("#msgs").scrollTop(500);
	}, 50);
	setInterval(readMsgs, 1000);
	setInterval(function(){
		$("#msgs").scrollTop(500);
	}, 10000);
});
// load messages
function readMsgs(){
	$.ajax({
		type: 'POST',
		data: {read: ""},
		url: 'chat.php',
		success: function(ret){
			ret = ret.toString();
			$("#msgs").html(format_bb(ret));
			//console.log(ret);
		},
		error: function(e){
			console.log(e);
		}
	});
}
// add new message to list
function sendMsg(){
	var msg = strip_tags($("#new_msg").val());
	$.ajax({
		type: 'POST',
		data: {msg: msg},
		url: 'chat.php',
		success: function(ret){
			$("#new_msg").val("");
		},
		error: function(e){
			console.log(e);
		}
	});
}
// register a name to an IP
function regUser(){
	var user = $("#user_name").val();
	console.log(user);
	window.location.href = "./?reg="+user;
}


/* text manipulation functions */
// replace newlines with <br /> to make it format properly
function nl2br(str, xhtml){
	var breakTag = (xhtml || typeof xhtml === 'undefined') ? '<br />' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
// remove any HTML from the message
function strip_tags(html){
	return html.replace(/<(?:.|\n)*?>/gm, '');
}
// add allowed HTML according to BBCode rules
function format_bb(text){
	text = nl2br(text, true);
	text = text.replace(/\[b\]/gi, "<strong>").replace(/\[\/b\]/gi, "</strong>");
	text = text.replace(/\[i\]/gi, "<em>").replace(/\[\/i\]/gi, "</em>");
	text = text.replace(/\[u\]/gi, "<span class='under'>").replace(/\[\/u\]/gi, "</span>");
	text = text.replace(/\[url ([^\]]*)\]/gi, "<a href='$1'>").replace(/\[\/url\]/gi, "</a>");
	text = text.replace(/\[img [:\/\.a-z\s]([\]])/gi, " />")
	text = text.replace(/\[img ([:\/\.a-z]*[^\]\s])/gi, "<img src='$1'").replace(/w:([0-9]*)/gi, " width='$1'").replace(/h:([0-9]*)/gi, " height='$1'").replace(/\]/gi, " />");
	return text;
}