<?php

session_start();
$_SESSION['app'] = "WikiTree"; // say something to initialize session
date_default_timezone_set('UTC');

$app = "SixDegrees";

?>

<html>
<head>
<meta charset="utf-8" />
	<TITLE>Full JS WTreeApp </TITLE>
	<LINK REL="stylesheet" TYPE="text/css" HREF="WTapps.css"/>
	<LINK REL="stylesheet" TYPE="text/css" HREF="css/base.css"/>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/apps/jquery.cookie.js"></script>
<!-- <script src="/apps/wikitree.js"></script> -->
<script src="/apps/clarke11007/wikitree.js"></script>
<script src="js/WikiTreeAuthGPC.js"></script>

<script src="js/globalVars.js"></script>

 
<script src="js/jspdf.umd.js"></script>
<script src="js/umd.js"></script>'



<?php
	echo '<script src="js/customVars_' . $app . '.js"></script>';
	echo '<script src="js/customFns_' . $app . '.js"></script>';
?>


<!-- custom font definition -->
<!-- <script src="jsPDF/src/libs/Luminari-normal.js" type="module"></script> -->
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="js/WikiAppFunctions.js"></script>
<script src="js/WikiDataFunctions.js"></script>
<script src="js/WikiDisplayFunctions.js"></script>
<script src="js/dnaTestComparatorFunctions.js"></script>

<script src="https://wikitree.com/skins/wikitree/dnaTestComparatorX.js+jquery.auto-complete.min.js,q2.pagespeed.jc.YuFfRiQFxB.js"></script>
<style>#dnaTestComparator{display:none;position:fixed;bottom:10px;right:10px;width:200px;height:200px;overflow:auto;background:#fff;border:3px solid #f2b600;padding:10px;color:#000;overflow:hidden;-webkit-border-radius:1em;-moz-border-radius:1em;border-radius:1em}#dtcCloser{color:#060;cursor:pointer;color:#060;float:right}#dtcStatus{clear:both;float:left;color:#a00000;display:hidden}.dtcAddTest{color:#060;text-decoration:underline;outline:0}.dtcAddTest:hover{text-decoration:none;background:#ffe270;cursor:pointer}.dtcAddTest:active{color:#8ec641;text-decoration:none}.dtcTestSection{margin-top:10px}.dtcTestSection:first{margin-top:0}.dtcTestTitle{font-weight:bold;font-size:110%;float:left}.dtcTestButton{float:right;margin:10px;color:#fff;background:#25422d;border:0;outline:none;cursor:pointer;text-align:center;padding:7px 15px;font-size:.9em;text-transform:uppercase;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;font-weight:normal;text-decoration:none!important;line-height:normal}.dtcTestButton:hover,dtcTestButton:hover,input[type="submit"]:hover,input[type="reset"]:hover,input[type="button"]:hover{color:#fff;background:#8ec641}.dtcTestButton:active,dtcTestButton:active,input[type="submit"]:active,input[type="reset"]:active,input[type="button"]:active{position:relative;top:1px;color:#fff}.dtcTestList{list-style-type:none;margin:0;padding:0;clear:left}.dtcTestList li{display:inline-block;float:left;margin:1px;background-color:#fff;color:#000;border:1px solid #060;padding:2px}.dtcTestList li:first{clear:left;margin-left:5px}.dtcTestList li .selected{background-color:#00f;color:#fff}.dtcRemoveTestButton{cursor:pointer;float:left;margin-right:2px;font-size:50%}.dtcManualPlaceholder{display:none}#dtcManualSection{display:none}#dtcCloseManualButton{cursor:pointer;float:left;margin-right:2px;font-size:50%}#dtcHelpButton{position:absolute;bottom:5px;right:5px}#dtcSectionButtonBox{position:absolute;bottom:5px;left:5px}.dtcSectionButton{float:left}</style>


<!--  READY FUNCTION and LOGIN FUNCTIONS -->
<script type="text/javascript">
	var appID = "6degs";
	var API_URL = 'https://api.wikitree.com/api.php';
	var theAppID = "6degs";
	var isLoggedIn = false;
	var theKeyDownNow = "";
	var lastKeyDownNow = "";

	function isDown(k) {
		theKeyDownNow = k;
		lastKeyDownNow = k;
		// console.log("Key DOWN: ", k);
	}
	function isUp(k) {
		theKeyDownNow = '';
		// console.log("Key UP: ", k);
	}

	$(document).ready(function(){ 
		wikitree.init({});
		document.title = lilAppName;
		$('.dtcAddTest').click(function(){dtcAddTest($(this));}); 
		// ***** FILL THIS IN WHEN CREATING NEW APPS BY DUPLICATING THIS PAGE *****
		headerMenuArray[0][3] = "https://api.wikitree.com/api.php?appId=6degs&action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/SixDegrees.php";

		$(document).keyup(function(e) {
		     if (e.key === "Escape") { // escape key maps to keycode `27`
		        console.log("ESCAPE NOW!!!");
		        escapeFunction();
		    } else {
		    	isUp(e.key);
		    }
		});

		$(document).keydown(function(e) {
		     if (e.key === "Escape") { // escape key maps to keycode `27`
		     	// do nothing
		        // console.log("ESCAPE NOW!!!");
		        // escapeFunction();
		    } else {
		    	isDown(e.key);
		    }
		});

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
										window.location = 'SixDegrees.php';
									}
									else { 
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

		WikiTree_UserName1 = massageTitle(gupSafe("id"));

		if (WikiTree_UserName1 == 'na' && wikitree.session.user_name) {
			WikiTree_UserName1 = wikitree.session.user_name;
		}
		// alert ("Let's start investigating " + WikiTree_UserName1);
		if (WikiTree_UserName1 == 'na' || WikiTree_UserName1 == 'NA' || alwaysStartWithEntryForm === true) {
			getInitialParameters();
		} else {
			loadInitialRecords();
		}
		
	});


	function makeCompareLinksActiveForDTC() {
			console.log("Activating the Compare Links now ...");
			$('.dtcAddTest').click(function(){dtcAddTest($(this));}); 
	}

</script>





<meta name="viewport" content="width=device-width, user-scalable=no" />
</head>
<body>
	<!-- SETUP BASIC STRUCTURE of APP in HTML then let JavaScript tweak it as it goes along -->
	<!-- TOP part of App is inside the WRAPPER and floats above the chart -->
	
<div class=popupBox id='mydiv'>
			<div id='mydivheader' align=left style='display:block;'>
				<div align=left width=1 id=xxx onclick='closeFloater();' style='background-color: red; display:inline-block; font-family: sans-serif;'>&nbsp;x&nbsp;</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<div align=right width=10px id=PIN onclick='unPin();' style='background-color: DarkOrange; transform-origin:50%; transform:rotate(45deg); display:inline-block; font-family: sans-serif; color:#0000FF;  border-left: 10px solid transparent; border-right: 10px solid transparent; border-top: 10px solid transparent; border-bottom: 10px solid transparent; border-radius: 20px;'>T</div>
				<div id='mydivcontent'><B>Click or Rollover someone's name.</B>
				<BR/><I>Click again to hide, or click [X], or hit ESC key.</I><BR/>
					<BR/><A >Wiki ID link will take you to their WikiTree profile</A>
					<BR/>
					<BR/>b. Birthdate, Birth Place
					<BR/>d. Date of Death, Place of Death
					<BR/><A><IMG src=pix/chartType6Deg.png height=20px></A>
					<BR/><I>Click ring cluster icon to view new Six Degrees Chart</I>
			</div>
	</div>
	<div class="wrapper" itemprop="mainEntity" itemscope itemtype="https://schema.org/Person" itemtype="http://historical-data.org/Person.html">
				<div id="header"></div>

				<div id=otherHeaderStuff>
					<H1 id=H1inBigHeader>Test App for GPC</H1>

					<TABLE id=TABLEinBigHeader valign=top align=center width=100%>
					</TABLE>
				</div>

	</div>
	
	
	<BR>

	<div align="center"><div id=StatusBar></div><div id=WarningMessage></div><div id=LoadingHeader></div><svg id=TickerTape></svg><div id=LoadingMessage></div>
		<div id="AppInfo">Chart goes here</div><BR>
		<svg id="SVGchart" style="background-color:none;"></svg>
	</div>

	
	<div id="dnaTestComparator">
		<div id="dtcCloser" onClick="dtcCloseComparator()">X</div>
		<div id="dtcSectionContainer"></div>
		<div id="dtcStatus"></div>
		<div id="dtcManualSection">
			<span id="dtcCloseManualButton" onClick="dtcManuallyAddTest(\\'close\\');">X</span>
			<input type="text" class="small" name="dtcManualAddTestId" id="dtcManualAddTestId" value="" size=10>
			<input type="hidden" name="dtcManualAddTestName" id="dtcManualAddTestName" value="">
			<div class="dtcManualPlaceholder" id="dtcManualPlaceholder" data-test-id="" data-test-name=""></div>
			<input type="button" class="button small" name="dtcManualAddButton" id="dtcManualAddButton" value="Add" onClick="dtcDoManuallyAddTest();">
		</div>
		<div id="dtcSectionButtonBox" class="small"></div>
		<span id="dtcHelpButton" class="small">[<a href="/wiki/Help:DNA_Comparison" target="_Help" title="Click here for help on DNA test comparisons">help</a>]</span>
	</div>
</body>
</html>