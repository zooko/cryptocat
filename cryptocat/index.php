<?php
	/* cryptocat 0.1 */
	$install = 'https://chatc.at/';
	$data = '/srv/data/';
	$timelimit = 1800;
	$update = 900;
	$nicks = array('bunny', 'kitty', 'pony', 'puppy', 'squirrel', 'sparrow', 'kiwi', 'bumblebee', 'fox', 'owl', 'raccoon');
	$maxusers = 8;
	$maxinput = 256;
?>
<?php
	function getpeople($chat) {
		global $nick, $myip, $usednicks, $usedips;
		preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\w+-/', $chat[1], $people);
		$people = $people[0];
		for ($i = 0; $i < count($people); $i++) {
			preg_match('/.+:/', $people[$i], $ip);
			$ip = substr($ip[0], 0, -1);
			preg_match('/:.+-/', $people[$i], $existingnick);
			$existingnick = substr($existingnick[0], 1, -1);
			if ($ip == $_SERVER['REMOTE_ADDR']) {
				$nick = $existingnick;
				$myip = $ip;
			}
			else {
				array_push($usedips, $ip);
				array_push($usednicks, $existingnick);
			}
		}
	}	
	if (isset($_GET['chat'])) {
		$chat = file($data.$_GET['chat']);
		getpeople($chat);
	}
	else if (isset($_POST['name']) && isset($_POST['input']) && $_POST['input'] != '' && strlen($_POST['input']) <= $maxinput*2) {
		$chat = file($data.$_POST['name']);
	}
	if (isset($_GET['chat']) && $_SERVER['HTTP_REFERER'] == $install."?c=".$_GET['chat'] && $myip == $_SERVER['REMOTE_ADDR']) {
		if (!$chat) {
			print('chat no longer exists');
		}
		else {
			for ($i = 2; $i < count($chat); $i++) {
				print(htmlspecialchars($chat[$i]));
			}
		}
		exit;
	}
	else if (isset($_POST['name']) && isset($_POST['input']) && $_POST['input'] != '' && strlen($_POST['input']) <= $maxinput*2.5) {
		getpeople($chat);
		if (preg_match('/^\w+:/', $_POST['input'], $nickauth) && substr($nickauth[0], 0, -1) == $nick) {
			$chat = "\n".$_POST['input'];
			file_put_contents($data.$_POST['name'], $chat, FILE_APPEND | LOCK_EX);
		}
		exit;
	}
?>
<?php print('<?xml version="1.0" encoding="UTF-8"?>'); ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xml:lang="en" > 
<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml" />
	<meta name="keywords" content="cryptocat, minichat, online chat" />
	<title>cryptocat</title>
	<link rel="icon" type="image/png" href="img/favicon.gif" />
	<script  type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/crypto.js"></script>
	<script type="text/javascript">
		function StuffSelect(id) {
			document.getElementById(id).focus();
			document.getElementById(id).select();
		}
	</script>
	<style type="text/css">
		body {
			background-color: #c7e5f5;
			margin: 0 auto;
			font-family: "Arial", "Helvetica", "Courier New", "Courier";
			font-size: 11px;
			color: #FFF;
			margin-top: 4.5%;
			padding: 0 12px 5px 12px;
		}
		div.main {
			border: 10px solid #000;
			background-color: #FFF;
			padding: 10px 10px 50px 10px;
			width: 600px;
			height: 420px;
			color: #000;
			margin: 0 auto;
		}
		p.intro {
			margin: 210px 0px 50px -10px;
			width: 615px;
			background-color: #000;
			padding: 3px 5px 3px 5px;
			text-align: center;
			color: #fff;
		}
		input.name, input.create {
			width: 200px;
			margin: 0 auto;
			background-color: #000;
			border: none;
			display: block;
			padding: 7px;
			color: #fff;
			outline: none;
			text-align: center;
			resize: none;
			font-size: 24px;
		}
		input.create {
			width: 214px;
			margin-top: 10px;
			padding: 0px 7px;
			border-bottom: 3px solid #97CEEC;
		}
		input.talk {
			background-color: #000;
			padding: 7px 11px 5px 10px;
			border: none;
			margin: -14px 0px 0px 2px;
			float: left;
			padding: 3px;
			width: 68px;
			color: #97CEEC;
			height: 52px;
			font-size: 22px;
			border-bottom: 3px solid #97CEEC;
		}
		input.create:hover, input.talk:hover {
			background-color: #97CEEC;
			color: #FFF;
		}
		div.chat {
			padding: 5px 5px 5px 0px;
			width: 567px;
			height: 315px;
			border: 3px solid #97CEEC;
			margin: 0 auto;
			margin-top: 20px;
			overflow-x: hidden;
			overflow-y: scroll;
			word-wrap: break-word;
			font-family: 'Courier New', 'Courier';
			line-height: 17px;
			font-size: 12px;
		}
		div.info {
			font-family: 'Verdana', 'Arial';
			font-size: 10px;
			color: #FFF;
			background-color: #000;
			padding: 2px 10px;
			width: 560px;
			margin: 0 auto;
			margin-top: 10px;
		}
		a {
			text-decoration: none;
			color: #97CEEC;
		}
		a:hover {
			text-decoration: underline;
		}
		a.logout {
			float: right;
			font-size: 10px;
			margin-right: 2px;
		}
		div.chat a {
			color: #000;
			border-bottom: 1px dashed #000;
		}
		div.chat a:hover {
			border-bottom: 1px solid #000;
			text-decoration: none;
		}
		input.logout:hover {
			text-decoration: underline;
		}
		a.logout:hover, input.create:hover, input.talk:hover {
			cursor: pointer;
		}
		input.input {
			margin: 0px 0px 0px 10px;
			background-color: #000;
			color: #FFF;
			padding: 5px 10px;
			width: 490px;
			border: none;
			float: left;
			height: 28px;
			outline: none;
			resize: none;
			word-wrap: break-word;
			font-family: 'Courier New', 'Courier';
			line-height: 17px;
			font-size: 12px;
		}
		input.input:active {
			border: none;
		}
		input.invisible, div.invisible, img.invisible {
			display: none;
		}
		input.url {
			font-size: 10px;
			background-color: #000;
			border: none;
			width: 150px;
			color: #97CEEC;
		}
		input.key {
			background-color: #97CEEC;
			color: #000;
			font-family: 'Verdana', 'Arial';
			font-size: 10px;
			padding: 2px 10px;
			width: 490px;
			margin-left: 10px;
			outline: none;
			resize: none;
			border: none;
		}
		div.msg, div.gsm, div.nmsg, div.ngsm {
			padding: 10px 10px 10px 5px;
			background-color: #D8EDF8;
			width: 540px;
			word-wrap: break-word;
			background-image: url("img/lock.png");
			background-repeat: no-repeat;
			background-position: 98%;
		}
		div.gsm {
			background-color: #FFF;
		}
		div.nmsg {
			background-image: url("img/unlock.png");
		}
		div.ngsm {
			background-image: url("img/unlock.png");
			background-color: #FFF;
		}
		span.nick {
			background-color: #000;
			color: #FFF;
			padding: 2px;
		}
		img.cryptocat {
			margin-bottom: 100px;
		}
		span.diffkey {
			border-bottom: 1px dashed #000;
		}
		div.text {
			max-width: 520px;
			margin: 0px 0px 0px 0px;
			padding: 0px 0px 0px 0px;
			word-wrap: break-word;
		}
	</style>
</head>
<?php
if (!isset($_GET)) {
	print('<body onload="document.getElementById(\'input\').focus();">');
}
else {
	print('<body onload="document.getElementById(\'name\').focus();">');
}
?>
	<?php
		function welcome($name) {
			global $install;
			print('<script type="text/javascript">
						function updateaction() {
							document.getElementById(\'welcome\').action = \''.$install.'\'+\'?c=\'+document.getElementById(\'name\').value;
						}
					</script>
			<div class="main">
				<img src="img/cryptocat.png" alt="cryptocat" class="cryptocat" />
				<form action="'.$install.'" method="post" class="create" id="welcome">
					<input type="text" class="name" name="name" id="name" onclick="StuffSelect(\'name\');" value="'.$name.'" />
					<input type="submit" name="create" class="create" value="enter" onclick="updateaction();" />
					<br /><center>(we\'re switching domain names to crypto.cat in a bit.)</center>
				</form>
				<p class="intro"><strong>cryptocat beta</strong> lets you set up encrypted, impromptu chats, which are securely wiped after 30 minutes of inactivity.</p>
			</div>');
		}
		function createchat($name) {
			global $data, $nicks;
			$name = strtolower($name);
			$nick = $nicks[mt_rand(0, count($nicks) - 1)];
			$chat = array(0 => "cryptocat", 1 => $_SERVER['REMOTE_ADDR'].':'.$nick.'-');
			array_push($chat, '* '.$nick.' has entered cryptocat :3 *');
			file_put_contents($data.$name, implode("\n", $chat), LOCK_EX);
			return 1;
		}
		function joinchat($name) {
			global $data, $nicks, $maxusers, $nick, $myip, $usednicks, $usedips;
			$name = strtolower($name);
			$chat = file($data.$name);
			getpeople($chat);
			if (count($usedips) >= $maxusers) {
				welcome('chat is full');
			}
			else {
				if (!isset($nick)) {
					$nick = $nicks[mt_rand(0, count($nicks) - 1)];
					while (in_array($nick, $usednicks)) {
						$nick = $nicks[mt_rand(0, count($nicks) - 1)];
					}
					$chat[1] = trim($chat[1]).$_SERVER['REMOTE_ADDR'].':'.$nick.'-'."\n";
					$chat[count($chat)+1] = "\n".'* '.$nick.' has entered cryptocat :3 *';
					file_put_contents($data.$name, implode('', $chat), LOCK_EX);
				}
				chat($name, $nick);
			}
		}
		function chat($name, $nick) {
			global $data, $timelimit, $maxinput, $install, $update;
			$name = strtolower($name);
			$chat = file($data.$name);
			print('<div class="main">
			<img src="img/cryptocat.png" alt="cryptocat" />
			<input type="text" value="'.$name.'" name="name" id="name" class="invisible" />
			<div class="invisible" id="loader"></div>
			<div class="chat" id="chat"></div>');
			print('<div class="info">chatting as <a href="#" id="nick">'.$nick.'</a> on 
			<input readonly type="text" id="url" onclick="StuffSelect(\'url\');" value="'.$install.'?c='.$name.'" class="url" />
			<a class="logout" href="'.$install.'?logout='.$name.'">log out</a></div>
			<input type="text" id="key" value="type a key here for encrypted chat. all chatters must use the same key." class="key" maxlength="192" onclick="StuffSelect(\'key\');" onkeyup="updatekey();" />
			<form name="chatform" id="chatform" method="post" action="'.$install.'">
			<input type="text" class="input" name="input" id="input" maxlength="'.$maxinput.'" 
			onkeydown="textcounter(document.chatform.input,document.chatform.talk,'.$maxinput.')" 
			onkeyup="textcounter(document.chatform.input,document.chatform.talk,'.$maxinput.')" />
			<input type="submit" name="talk" class="talk" id="talk" onmouseover="curcount = this.value; this.value=\'send\';" onmouseout="this.value=curcount;" value="'.$maxinput.'" />
			</form></div>');
			print('<script type="text/javascript">
				var salt;
				var key;
				var curcount;
				var changemon = document.getElementById("loader").innerHTML;
				var nick = document.getElementById("nick").innerHTML;
				var focus = true;
				var num = 0;
				var match;
				var defaultsalt = Crypto.SHA1(document.getElementById("key").value, { asString: true }).substring(0, 30);
				var defaultkey = Crypto.PBKDF2(getkey(3), defaultsalt, 64, { iterations: 128 });
				var defaultkey16 = getkey(16);
				window.onblur = function() { focus = false; }
				window.onfocus = function() { focus = true; }
				document.onblur = window.onblur;
				document.focus = window.focus;
				
				function getkey(n) {
					gk = Crypto.SHA1(document.getElementById("key").value, { asString: true });
					for (gki = 0; gki != n; gki++) {
						gk = Crypto.SHA1(gk, { asString: true });
						gk += Crypto.SHA1(gk, { asString: true });
					}
					return gk.substring(0, 64);
				}
				
				function textcounter(field,cntfield,maxlimit) {
					if (field.value.length > maxlimit) {
						field.value = field.value.substring(0, maxlimit);
					}
					else {
						cntfield.value = maxlimit - field.value.length;
					}
				}
				
				function updatekey() {
					if (document.getElementById("key").value == "") {
						salt = defaultsalt;
						key = defaultkey;
					}
					else {
						salt = Crypto.SHA1(document.getElementById("key").value, { asString: true }).substring(0, 30);
						key = Crypto.PBKDF2(getkey(3), salt, 64, { iterations: 128 });
					}
					if (key != defaultkey) {
						document.getElementById("key").type = "password";
					}
					updatechat("#chat");
				}
				
				function nl2br(str) {
					return (str + \'\').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, \'$1\' + \'<br />\' + \'$2\');
				}
				
				function scrubtags(str) {
					return str.replace(/</g, " ");
				}
				
				function processline(chat, flip) {
					chat = chat.split("\n");
					for (i=0; i <= chat.length-1; i++) {
						var already = 0;
						var encrypted = 0;
						if (match = chat[i].match(/\\[BEGIN\-HMAC\-:3\](.+?)\[END-HMAC-:3\]$/)) {
							match = match[0].substring(15, match[0].length-13);
							var hmac = match;
							if (match = chat[i].match(/\[BEGIN-CRYPTOCAT-:3](.+?)\[END-CRYPTOCAT-:3]/)) {
								cryptotext = match[0].substring(20, match[0].length-18);
							}
							chat[i] = chat[i].replace(/\[BEGIN-HMAC-:3](.+?)\[END-HMAC-:3]/, "");
						}
						if (match = chat[i].match(/\[BEGIN-CRYPTOCAT-:3](.+?)\[END-CRYPTOCAT-:3]/)) {
							match = match[0].substring(20, match[0].length-18);
							if ((hmac != Crypto.HMAC(Crypto.SHA1, match, getkey(16), { asBytes: true })) && ((hmac != Crypto.HMAC(Crypto.SHA1, match, defaultkey16, { asBytes: true })) && (key = defaultkey))) {
								chat[i] = chat[i].replace(/\[BEGIN-CRYPTOCAT-:3](.+?)\[END-CRYPTOCAT-:3]/, "<span class=\"diffkey\">encrypted</span>");
								encrypted = 1;
							}
							else {
								try {
									chat[i] = chat[i].replace(/\[BEGIN-CRYPTOCAT-:3](.+?)\[END-CRYPTOCAT-:3]/, Crypto.AES.decrypt(match, key));
									chat[i] = scrubtags(chat[i]);
									if (key != defaultkey) {
										encrypted = 1;
									}
								}
								catch (INVALID_CHARACTER_ERR) {
									try {
										chat[i] = chat[i].replace(/\[BEGIN-CRYPTOCAT-:3](.+?)\[END-CRYPTOCAT-:3]/, Crypto.AES.decrypt(match, defaultkey));
										chat[i] = scrubtags(chat[i]);
										encrypted = 0;
									}
									catch (INVALID_CHARACTER_ERR) {
										chat[i] = chat[i].replace(/\[BEGIN-CRYPTOCAT-:3](.+?)\[END-CRYPTOCAT-:3]/, "<span class=\"diffkey\">encrypted</span>");
										encrypted = 1;
									}
								}
							}
							if (match = chat[i].match(/((mailto\:|(news|(ht|f)tp(s?))\:\/\/){1}\S+)/)) {
								match = match[0];
								var sanitize = match.split("");
								for (ii=0; ii <= sanitize.length-1; ii++) {
									if (!sanitize[ii].match(/\w|\d|\:|\/|\?|\=|\#|\+|\,|\.|\&/)) {
										sanitize[ii] = encodeURIComponent(sanitize[ii]);
									}
								}
								sanitize = sanitize.join("");
								chat[i] = chat[i].replace(/((mailto\:|(news|(ht|f)tp(s?))\:\/\/){1}\S+)/, "<a target=\"_blank\" href=\"" + sanitize + "\">" + match + "</a>");
							}
							if (match = chat[i].match(/^[a-z]+:\s\/me\s/)) {
								match = match[0];
								chat[i] = chat[i].replace(/^[a-z]+:\s\/me\s/, "<span class=\"nick\">* " + nick + " ") + " :3 *</span>";
							}
							else if (match = chat[i].match(/^[a-z]{1,10}:/)) {
								if (!already) {
									match = match[0];
									chat[i] = chat[i].replace(/^[a-z]{1,10}:/, "<span class=\"nick\">" + match + "</span>");
								}
							}
						}
						else {
							if (match = chat[i].match(/\*.+\*/)) {
								match = match[0];
								chat[i] = chat[i].replace(/\*.+\*/, "<span class=\"nick\">" + match + "</span>");
							}
						}
						chat[i] = nl2br(chat[i]);
						if (encrypted) {
							tag = "";
						}
						else {
							tag = "n";
						}
						if ((!flip) && (document.getElementById("chat").innerHTML.split("\n").length % 2)) {
							tag += "msg";
						}
						else {
							if (i % 2) {
								tag += "msg";
							}
							else {
								tag += "gsm";
							}
						}
							chat[i] = "<div class=\"" + tag + "\"><div class=\"text\">" + chat[i] + "</div></div>";
					}
					chat = chat.join("\n");
					return chat;
				}
				
				function updatechat(div){
					if (focus) {
						num = 0;
						document.title = "[" + num + "] cryptocat";
					}
					$(div).load("index.php?chat='.$name.'", function() {
						if ((document.getElementById("loader").innerHTML != changemon) || (div == "#chat")) {
							var chat = document.getElementById("loader").innerHTML;
							chat = processline(chat, 1);
							document.getElementById("chat").innerHTML = chat;
							document.getElementById("chat").scrollTop = document.getElementById("chat").scrollHeight;
							if (focus == false) {
								num++;
								document.title = "[" + num + "] cryptocat";
							}
							changemon = document.getElementById("loader").innerHTML;
						}
					});
				}
				
				$("#chatform").submit( function() {
					var encoded = Crypto.AES.encrypt(document.getElementById("input").value, key);
					var hmac = Crypto.HMAC(Crypto.SHA1, encoded, getkey(16), { asBytes: true });
					encoded = nick + ": " + "[BEGIN-CRYPTOCAT-:3]" + encoded + "[END-CRYPTOCAT-:3][BEGIN-HMAC-:3]" + hmac + "[END-HMAC-:3]";
					document.getElementById("input").value = "";
					document.getElementById("chat").innerHTML += processline(encoded, 0);
					document.getElementById("chat").scrollTop = document.getElementById("chat").scrollHeight;
					encoded = encodeURIComponent(encoded);
					$.ajax( { url : "index.php",
						type : "POST",
						data : "input=" + encoded + "&name=" + $("#name").val() + "&talk=send",
						success : function(data) {
							document.getElementById("input").focus();
							document.getElementById("talk").value = "'.$maxinput.'";
							updatechat("#loader");
						},
						error : function(data) {
							document.getElementById("input").value = "connection error";
						}
					});
					return false;    
				});
				
				updatekey();
				updatechat("#loader");
				setInterval("updatechat(\"#loader\")", '.$update.');
			</script>
			<img src="img/lock.png" alt="" class="invisible" />');
		}
	?>
	<?php
		if (isset($_GET['c'])) {
			if (preg_match('/^\w+$/', $_GET['c'])) {
				$_GET['c'] = strtolower($_GET['c']);
				if (file_exists($data.$name)) {
					if (time() - filemtime($data.$_GET['c']) > $timelimit) {
						unlink($data.$_GET['c']);
						createchat($_GET['c']);
					}
					joinchat($_GET['c']);
				}
				else {
					$create = createchat($_GET['c']);
					if ($create) {
						joinchat($_GET['c']);
					}
				}
			}
			else {
				welcome('invalid name');
			}
		}
		else if (isset($_GET['logout'])) {
				$chat = file($data.$_GET['logout']);
				getpeople($chat);
				$chat[count($chat)+1] = "\n".'* '.$nick.' has left cryptocat :3 *';
				file_put_contents($data.$_GET['logout'], implode('', $chat), LOCK_EX);
				welcome('your chat');
		}
		else {
				welcome('your chat');
		}
	?>
</body> 
</html>