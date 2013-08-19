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
		<title>Streamy</title>
		<meta name="author" content="Rok Pergarec" />
		<meta http-equiv="reply-to" content="brejktru@gmail.com" />
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="styles/screen.css" />
		<link rel="stylesheet" type="text/css" media="print" href="styles/print.css" />
		<link rel="stylesheet" type="text/css" media="handheld" href="styles/handheld.css" />
		<link rel="stylesheet" type="text/css" href="styles/status.css" />
		<link rel="stylesheet" type="text/css" href="styles/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="styles/streams.css" />
		<link rel="stylesheet" type="text/css" href="styles/menu.css" />
		<link rel="stylesheet" type="text/css" href="styles/addDialog.css" />
		<link rel="stylesheet" type="text/css" href="styles/art.css" />
		<link rel="stylesheet" type="text/css" href="styles/prettyForms.css" />

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
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
				<select style="width:130px" name="outputs" id="outputs">
				</select>
				<br/>
				<img onclick="onClickAddOutput()" src="images/add-plus-sign.png" />
				<table class="menuTable">
					<tr>
						<td>
							<table class="menuEntry">
								<tr>
									<td>
										<img onclick="onClickChangeState()" 
											id="state" 
											src="images/paused.png">
									</td>
								</tr>
								<tr>
									<td>
										<span id="stateText" class="menuEntryText" onclick="showAddStreamDialog()">
											paused ...
										</span>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<div class="menuSeparator" />
						</td>
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
		Utils::includeTemplate("addOutputDialog");
		?>
	</body>
</html>
