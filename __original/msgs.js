/* main functionality */
// when document finishes loading, update messages display every second
$(document).ready(function(){
	// initial message load and scroll to bottom
	readMsgs();
	setTimeout(function(){
		$("#msgs").scrollTop(500);
	}, 50);
	
	// refresh messages every second
	setInterval(readMsgs, 1000);
	// return to bottom every 10 seconds 
	// this allows users to scroll up to previous messages
	setInterval(function(){
		$("#msgs").scrollTop(500);
	}, 10000);
});
// load messages
function readMsgs(){
	$.ajax({
		type: 'POST',
		data: {read: ""}, // request contents of messages file
		url: 'chat.php',
		success: function(ret){
			ret = ret.toString();
			$("#msgs").html(format_bb(ret)); // turn BBCode into HTML tags
			//console.log(ret);
		},
		error: function(e){
			console.log(e);
		}
	});
}
// add new message to list
function sendMsg(){
	// delete any HTML from input
	var msg = strip_tags($("#new_msg").val());
	$.ajax({
		type: 'POST',
		data: {msg: msg},
		url: 'chat.php',
		success: function(ret){
			// empty the input box on success
			$("#new_msg").val("");
		},
		error: function(e){
			console.log(e);
		}
	});
}
// register a name to an IP
// only used at ./?reg
function regUser(){
	// extract username
	var user = $("#user_name").val();
	//console.log(user);
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
	// call nl2br() first so that the replace() calls don't fuck up the newline characters
	text = nl2br(text, true);
	
	// turn [b] into <strong>
	text = text.replace(/\[b\]/gi, "<strong>").replace(/\[\/b\]/gi, "</strong>");
	// turn [i] into <em>
	text = text.replace(/\[i\]/gi, "<em>").replace(/\[\/i\]/gi, "</em>");
	// turn <u> into <span class='under'>
	text = text.replace(/\[u\]/gi, "<span class='under'>").replace(/\[\/u\]/gi, "</span>");
	// turn [url http://www.google.com/] into a valid HTML link
	// opens in a new tab
	text = text.replace(/\[url ([^\]]*)\]/gi, "<a href='$1' target='_blank'>").replace(/\[\/url\]/gi, "</a>");
	// turn the end "]" of an [img] code into " />"
	// prevents issues when trying to catch a URL followed immediately by "]"
	text = text.replace(/\[img [:\/\.a-z\s]([\]])/gi, " />")
	// turn [img http://www.muffins.org/muffins.jpg] into <img src="http://www.muffins.org/muffins.jpg"
	// look for width and height values, add them if found
	// also makes the image a link to itself in a new tab
	text = text.replace(/\[img ([:\/\.a-z]*[^\]\s])/gi, "<a href='$1' target='_blank'><img src='$1'").replace(/w:([0-9]*)/gi, " width='$1'").replace(/h:([0-9]*)/gi, " height='$1'").replace(/\]/gi, " /></a>");
	
	// return modified text for display
	return text;
}