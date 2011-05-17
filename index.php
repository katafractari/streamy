<?php
/**
 * Streammanager HTML template
 *
 * @author Me
 * @version 0
 * @copyright Me, 01 May, 2011
 * @package streammanager
 **/

include 'php/Utils.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Stream manager</title>
		<meta name="author" content="Rok Pergarec" />
		<meta http-equiv="reply-to" content="brejktru@gmail.com" />
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="styles/screen.css" />
		<link rel="stylesheet" type="text/css" media="print" href="styles/print.css" />
		<link rel="stylesheet" type="text/css" media="handheld" href="styles/handheld.css" />
		<link rel="stylesheet" type="text/css" href="styles/status.css" />
		<link rel="stylesheet" type="text/css" href="styles/jquery-ui-1.8.11.custom.css" />
		<link rel="stylesheet" type="text/css" href="styles/streams.css" />
		<link rel="stylesheet" type="text/css" href="styles/menu.css" />
		<link rel="stylesheet" type="text/css" href="styles/addStreamDialog.css" />
		<link rel="stylesheet" type="text/css" href="styles/art.css" />
		<link rel="stylesheet" type="text/css" href="styles/prettyForms.css" />

		<script type="text/javascript" src="js/jquery-1.5.2.js"></script>
		<script 
			type="text/javascript" 
			src="js/jquery-ui-1.8.11.custom.min.js">
		</script>
		<script type="text/javascript" src="js/defines.js"></script>
		<script type="text/javascript" src="js/ui.js"></script>
		<script type="text/javascript" src="js/util.js"></script>
		<script type="text/javascript" src="js/prettyForms.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/init.js"></script>

	</head>
	<body>
		<div id="container">
			<div id="top">
				<select style="float: left; width:80px" name="outputs" id="outputs">
				</select>
				<table class="menuTable">
					<tr>
						<td>
							<table class="menuEntry">
								<tr>
									<td>
										<img onclick="showAddStreamDialog()" 
											id="addButton" 
											src="images/add.png">
									</td>
								</tr>
								<tr>
									<td>
										<span class="menuEntryText" onclick="showAddStreamDialog()">
											add stream
										</span>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table class="menuEntry">
								<tr>
									<td>
										<img onclick="showAddStreamDialog()" id="settingsButton" src="images/settings.png">
									</td>
								</tr>
								<tr>
									<td>
										<span class="menuEntryText" onclick="showAddStreamDialog()">
											settings
										</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id="sidebar">
				<div id="art">
					<img id="artCover">		
				</div>
				<div>
				</div>
			</div>
			<div id="body">
				<div id="name"></div>
				<div id="url"></div>
				<div id="title"></div>
				<div id="streams"></div>
				<div id="add"></div>
			</div>
		</div>
		
		<?php
		Utils::includeTemplate("addStreamDialog");
		?>
	</body>
</html>
