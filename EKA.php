<?php

session_start();
$_SESSION['app'] = "WikiTree"; // say something to initialize session
date_default_timezone_set('UTC');

// if (isset( $_GET['app'] )) {
$app = "EKA";
	// $acceptableApps = array("FanChart", "FanDoku", "FractalTree","SpiderWebs","XFamChart","BigXFamTree");
	
// 	if ( $app == "FanChart" || $app ==  "FanDoku"|| $app ==  "FractalTree"|| $app == "SpiderWebs"|| $app == "XFamChart"|| $app == "BigXFamTree"|| $app == "Hourglass"|| $app == "EKA"|| $app == "DNAmatrix") {
// 		// HURRAY
// 	} else {
// 		// not valid input for app - so - defaulting to FanChart
// 		$app = "FanChart";
// 	}
// } else {
// 	// if no app is given, then also defaulting to Fan Chart
// 		$app = "FanChart";
// }

// echo "<H1> $app </H1>";
?>

<html>
<head>
<meta charset="utf-8" />
	<TITLE>EKA finder</TITLE>
	<LINK REL="stylesheet" TYPE="text/css" HREF="WTapps.css"/>
	<LINK REL="stylesheet" TYPE="text/css" HREF="css/base.css"/>

<!-- <style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}

/* Firefox */
input[type=number] {
	-moz-appearance: textfield;
}

textarea {
  box-sizing: border-box; /* Solution 1: To fix the border issue */
  float: center; /* Solution 2 : Float to fix the height issue */
  padding: 0;
}
</style>
 -->

<!-- zimjs.com - JavaScript Canvas Framework  - see mathies.ca apps if needed -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/apps/jquery.cookie.js"></script>
<!-- <script src="/apps/wikitree.js"></script> -->
<script src="/apps/clarke11007/wikitree.js"></script>
<script src="js/WikiTreeAuthGPC.js"></script>


<script src="js/globalVars.js"></script>

<?php
//	 $app == "FanChart" || $app ==  "FanDoku"|| $app ==  "FractalTree"|| $app == "SpiderWebs"|| $app == "XFamChart"|| $app == "BigXFamTree" 
	echo '<script src="js/customVars_' . $app . '.js"></script><BR>';
	echo '<script src="js/customFns_' . $app . '.js"></script><BR>';

?>

<script src="js/WikiAppFunctions.js"></script>
<script src="js/WikiDataFunctions.js"></script>
<script src="js/WikiDisplayFunctions.js"></script>


<!--  READY FUNCTION and LOGIN FUNCTIONS -->
<script type="text/javascript">
	var appID = "EKA";
	var API_URL = 'https://api.wikitree.com/api.php';
	var isLoggedIn = false;
	var theAppID = "EKA";

	$(document).ready(function(){ 
		wikitree.init({});
		document.title = lilAppName;
		headerMenuArray[0][3] = "https://api.wikitree.com/api.php?appId=EKA&action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/apps/EKA.php";

		//alert("Init done");

			// wikitree.session.checkLogin({})
			login()
				.then(function(data) { 
					// transfer values from wikiTreeAuth
					wikitree.session.loggedIn = thisUserIsLoggedIn;
					wikitree.session.user_name = getUserName();
					wikitree.session.user_id = getUserId();
					
					//alert("in checkLogin");
					if (wikitree.session.loggedIn) { 
						/* We're already logged in and have a valid session. */
						// alert("Logged In @ " + wikitree.session.user_name);
						isLoggedIn = true;
						buildMenus();
						swapHeader();
						// document.TheForm.Person1.value = wikitree.session.user_name;
						// document.TheForm.WikiTreeUserName.value = wikitree.session.user_name;
						// $('#need_login').hide();
						// $('#logged_in').show();
					} 
					else { 
						/* We're not yet logged in, but maybe we've been returned-to with an auth-code */
						// alert ("Not logged in yet - in process");
						var x = window.location.href.split('?');
						var queryParams = new URLSearchParams( x[1] );
						if (queryParams.has('authcode')) { 
							var authcode = queryParams.get('authcode');
							wikitree.session.clientLogin( {'authcode': authcode}  )
								.then(function(data) {
									if (wikitree.session.loggedIn) { 
										/* If the auth-code was good, redirect back to ourselves without the authcode in the URL (don't want it bookmarked, etc). */
										window.location = 'EKA.php';
									}
									else { 
										var theInfoDIV = document.getElementById('AppInfo');
										theInfoDIV.style.display = 'block';
										// $('#need_login').show();
										// $('#logged_in').hide();
									}
								});
						} else { 
							// alert ("NOT logged at all!!");
							// $('#need_login').show();
							// $('#logged_in').hide();
						}
					}
				});
			//alert ("After session.checkLogin");


		buildMenus();
		swapHeader();
		dragElement('mydiv');

		WikiTree_UserName1 = gup("id");
		if (WikiTree_UserName1 == 'na' && wikitree.session.user_name) {
			WikiTree_UserName1 = wikitree.session.user_name;
		}
		// alert ("Let's start investigating " + WikiTree_UserName1);
		if (WikiTree_UserName1 == 'na' || WikiTree_UserName1 == 'NA') {
			getInitialParameters();
		} else {
			loadInitialRecords();
		}
		
	});
</script>


<meta name="viewport" content="width=device-width, user-scalable=no" />
</head>
<body>
	<!-- SETUP BASIC STRUCTURE of APP in HTML then let JavaScript tweak it as it goes along -->
	<!-- TOP part of App is inside the WRAPPER and floats above the chart -->
	<div class="wrapper" itemprop="mainEntity" itemscope itemtype="https://schema.org/Person" itemtype="http://historical-data.org/Person.html">
				<div id="header"></div>

				<div id=otherHeaderStuff>
					<H1 id=H1inBigHeader>Test App for GPC</H1>

					<TABLE id=TABLEinBigHeader valign=top align=center width=100%>
					</TABLE>
				</div>

	</div>
	
	
	<BR>

	<div align="center"><div id=LoadingHeader></div><svg id=TickerTape></svg><div id=LoadingMessage></div>
		<div id="AppInfo">Chart goes here</div><BR>
		<svg id="SVGchart" style="background-color:none;"></svg>
	</div>

	<div class=popupBox id='mydiv'>
			<div id='mydivheader' align=left style='display:none;'>
				<!-- <div align=left width=1 id=xxx onclick='closeFloater();' style='background-color: red; display:inline-block; font-family: sans-serif;'>&nbsp;x&nbsp;</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<div align=right width=10px id=PIN onclick='unPin();' style='background-color: DarkOrange; transform-origin:50%; transform:rotate(45deg); display:inline-block; font-family: sans-serif; color:#0000FF;  border-left: 10px solid transparent; border-right: 10px solid transparent; border-top: 10px solid transparent; border-bottom: 10px solid transparent; border-radius: 20px;'>T</div>
				<div id='mydivcontent'><B>Click or Rollover someone's name.</B>
				<BR/><I>Click again to pin or unpin an ancestor.</I><BR/>
					<BR/><A >Wiki ID link will take you to their WikiTree profile</A>
					<BR/>
					<BR/>b. Birthdate, Birth Place
					<BR/>d. Date of Death, Place of Death
					<BR/><A><IMG src=pix/fan240.png height=20px></A>
					<BR/><I>Click fan icon to view new Fan Chart</I> -->
			</div>

	</div>
			<BR><P align=center>Last updated on 30.1 March 2026.</P>
</body>
</html>