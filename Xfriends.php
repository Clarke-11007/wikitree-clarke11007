<?php

session_start();
$_SESSION['app'] = "WikiTree"; // say something to initialize session
date_default_timezone_set('UTC');

// if (isset( $_GET['app'] )) {
// 	$app = $_GET['app'];
	// $acceptableApps = array("FanChart", "FanDoku", "FractalTree","SpiderWebs","XFamChart","BigXFamTree");
	
// 	if ( $app == "FanChart" || $app ==  "FanDoku"|| $app ==  "FractalTree"|| $app == "SpiderWebs"|| $app == "XFamChart"|| $app == "BigXFamTree"|| $app == "Hourglass"|| $app == "EKA"|| $app == "DNAmatrix" || $app == "Xfriends") {
// 		// HURRAY
// 	} else {
// 		// not valid input for app - so - defaulting to FanChart
// 		$app = "FanChart";
// 	}
// } else {
	// if no app is given, then also defaulting to Fan Chart
$app = "Xfriends";
// }

// echo "<H1> $app </H1>";
?>

<html>
<head>
<meta charset="utf-8" />
	<TITLE>Full JS WTreeApp </TITLE>
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
<script src="js/dnaTestComparatorFunctions.js"></script>

<script src="https://wikitree.com/skins/wikitree/dnaTestComparatorX.js+jquery.auto-complete.min.js,q2.pagespeed.jc.YuFfRiQFxB.js"></script>
<style>#dnaTestComparator{display:none;position:fixed;bottom:10px;right:10px;width:200px;height:200px;overflow:auto;background:#fff;border:3px solid #f2b600;padding:10px;color:#000;overflow:hidden;-webkit-border-radius:1em;-moz-border-radius:1em;border-radius:1em}#dtcCloser{color:#060;cursor:pointer;color:#060;float:right}#dtcStatus{clear:both;float:left;color:#a00000;display:hidden}.dtcAddTest{color:#060;text-decoration:underline;outline:0}.dtcAddTest:hover{text-decoration:none;background:#ffe270;cursor:pointer}.dtcAddTest:active{color:#8ec641;text-decoration:none}.dtcTestSection{margin-top:10px}.dtcTestSection:first{margin-top:0}.dtcTestTitle{font-weight:bold;font-size:110%;float:left}.dtcTestButton{float:right;margin:10px;color:#fff;background:#25422d;border:0;outline:none;cursor:pointer;text-align:center;padding:7px 15px;font-size:.9em;text-transform:uppercase;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;font-weight:normal;text-decoration:none!important;line-height:normal}.dtcTestButton:hover,dtcTestButton:hover,input[type="submit"]:hover,input[type="reset"]:hover,input[type="button"]:hover{color:#fff;background:#8ec641}.dtcTestButton:active,dtcTestButton:active,input[type="submit"]:active,input[type="reset"]:active,input[type="button"]:active{position:relative;top:1px;color:#fff}.dtcTestList{list-style-type:none;margin:0;padding:0;clear:left}.dtcTestList li{display:inline-block;float:left;margin:1px;background-color:#fff;color:#000;border:1px solid #060;padding:2px}.dtcTestList li:first{clear:left;margin-left:5px}.dtcTestList li .selected{background-color:#00f;color:#fff}.dtcRemoveTestButton{cursor:pointer;float:left;margin-right:2px;font-size:50%}.dtcManualPlaceholder{display:none}#dtcManualSection{display:none}#dtcCloseManualButton{cursor:pointer;float:left;margin-right:2px;font-size:50%}#dtcHelpButton{position:absolute;bottom:5px;right:5px}#dtcSectionButtonBox{position:absolute;bottom:5px;left:5px}.dtcSectionButton{float:left}</style>


<!--  READY FUNCTION and LOGIN FUNCTIONS -->
<script type="text/javascript">
	var appID = "Xfriends";
	var API_URL = 'https://api.wikitree.com/api.php';
	var theAppID = "Xfriends";

	var isLoggedIn = false;

	var rowsOfGEDcom = [];
	var showDebugMsgs = false;

	function gotoGEDcom() {		
		window.open("Xfriends.html");
	}

	function condLog(msg) {
		if (showDebugMsgs) {
			console.log(msg);
		}
	}

	var openFile = function(event) {
      console.log("OPEN FILE button PRESSED !!!");
      var input = event.target;

      // RESET the COLLECTIONS
      PeopleCollection = [];
      FamilyCollection = [];
      rowsOfGEDcom = [];


      var reader = new FileReader();
      reader.onload = function(){
        var text = reader.result;
        var node = document.getElementById('GEDcomParmsDIV');
        var IndiDIV = document.getElementById('Individuals');

        // node.innerText = text;
        console.log(reader.result.substring(0, 200));

        rowsOfGEDcom = text.split('\n');

      
	    };
	    reader.readAsText(input.files[0]);
	     // ADD input.files[0] to a NEWLY CREATED SPAN for GEDcomFileName
	    if (document.getElementById("GEDcomFileName") && input.files[0]) {
	    		document.getElementById("GEDcomFileName").innerHTML = "Filename: " + input.files[0].name + "<br>" + "Last Modified: " + input.files[0].lastModifiedDate;
	    		console.log(input.files[0]);
	  	}

    }

  
      

 
	$(document).ready(function(){ 
		showDebugMsgs = (gup("debug") == "Y");
		console.log("Showing DEBUG messages = ", showDebugMsgs);

		

		wikitree.init({});
		document.title = lilAppName;
		headerMenuArray[0][3] = "https://api.wikitree.com/api.php?action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/apps/Xfriends.php";
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
										window.location = 'Xfriends.php';
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

		WikiTree_UserName1 = gup("id");
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
			$('.dtcAddTest').click(function(){dtcAddTest($(this));}); 
	}
			// console.log("Activating the Compare Links now ...");
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


<div id=bigContainerDIV align="center">

<div id=XFriendsExplainer>
	<table  style="background-color: beige;border-color: beige;">
		<TR>
       <TD colspan=2>The X-Chromosome Friend Finder app will <UL><LI>start with a single ROOT individual (at or near the bottom of a family tree),<LI>then traverse upwards through their tree (back in time) to find all potential ancestors who could have donated part of their X-Chromosome<LI>then will travel back down through the tree and spread out to eventually find all possible cousins who could potentially share some portion of the same X-Chromosome.</UL><P>NOTE: You must specify a maximum number of generations to go back in time.<P>There are two ways to generate this list of X-Friends:</TD>
          
    </TR>
    <TR style="padding: inherit; background-color:beige;" colspan=2>
    	<td align="center">
     	<table style="border:  5px solid beige;">
     		<tr>

          <TD style="padding: 20px; background-color: white;border:  5px solid beige;">
						<I>OPTION # 1</I><BR><BR>
						Enter the <a target="_blank" href="https://www.wikitree.com/wiki/Help:WikiTree_ID">WikiID</a> for the initial person in the X-Chromosome Friend Finder,<br>to create a list of X-Chromosome ancestors and descendant test-takers.<br><br></p>
						<p><b>Initial Person:</b> <input type="text" name="id" id="Person1" value=""><br><i>e.g. Marcoux-336</i><br></p><b>How far back do you wish to go?:</b> &nbsp;<a class="noUnderline" onclick="decStepper(&quot;numGensStepper&quot;)"><span id="numGensStepperDEC"><font color="blue">◀</font></span></a> <span id="numGensSteppervalue">5</span> generations<a class="noUnderline" onclick="incStepper(&quot;numGensStepper&quot;)"><span id="numGensStepperINC"><font color="red">▶</font></span></a>&nbsp;
						<p></p><input type="hidden" name="NumGens" value="5"><input type="hidden" name="ph" value="1"><input type="hidden" name="TheWidth" value="1000"><input type="hidden" name="TheHeight" value="1000"><br><input class="button" type="submit" onclick="loadInitialRecords(true);" value="Find X-Chromosome Friends on WikiTree"><br>&nbsp;
          </TD>

          <TD style="padding: 20px; background-color: white;border-color: beige;" valign=top>
          	<I>OPTION # 2</I><BR><BR>
          	<!-- <B> - OR - </B><br/><br/>&#x1F4C1;<BR><button onclick="gotoGEDcom();">Import a GEDcom file</button><BR>which contains the Family Tree information required. -->
          	Import a GEDcom file which contains the Family Tree information required.<BR><BR><BR><BR>
          	<input style="padding:3px 10px; font-size:20px; background-color: green; color: white; width: 400px;" type='file' accept='*.GED' onchange='openFile(event)'><br><span id=GEDcomFileName></span><br><br>
          	<button onclick="processGEDcom();">Process GEDcom file</button>
          </TD>
        </TR>
        <tr>
        	<td colspan="2" style="padding: 5px; background-color: white;border-color: beige;" align=center valign=top>
        		 <br/><br/><H4>Privacy Settings</H4><input type=radio id=privacyRadio1 name=privacySetting value=0 checked onclick='updatePrivacySetting(0, false);' >Display all Names 
        <br/><input type=radio id=privacyRadio2 name=privacySetting value='FN'  onclick='updatePrivacySetting(1, false);' >Living people: First Names only  
        <br/><input type=radio id=privacyRadio3 name=privacySetting value='LN'  onclick='updatePrivacySetting(2, false);' >Living people: Last Names only  
        <br/><input type=radio id=privacyRadio4 name=privacySetting value='ABC'  onclick='updatePrivacySetting(3, false);' >Living people: Initials only  
        <br/><input type=radio id=privacyRadio5 name=privacySetting value='Living'  onclick='updatePrivacySetting(4, false);' >Living people: 'Living'  
        <br/><input type=checkBox id=privacycheckBox6 name=privacySetting value='showDates' checked onclick='updatePrivacySetting(5,false);'  >Show Dates for Living people  
        <br/><input type=checkBox id=privacycheckBox7 name=privacySetting value='showPlaces' checked onclick='updatePrivacySetting(6,false);'  >Show Places for Living people  
        	</td>
        </tr>
      </table> 
    	</TD>
    </TR>
  </table>

  <BR/><BR/>
  
 </div>

  <div id='GEDcomParmsDIV'><div id='GedComProcessingMsgBox'></div></div>
		<div id=LoadingHeader></div><svg id=TickerTape></svg><div id=LoadingMessage></div>

    <div id='Individuals'></div>
		<div id="AppInfo">XFriends info goes here</div><BR>
		<svg id="SVGchart" style="background-color:none;"></svg>
	</div>

	<div class=popupBox id='mydiv'>
			<div id='mydivheader' align=left style='display:block;'>
				<div align=left width=1 id=xxx onclick='closeFloater();' style='background-color: red; display:inline-block; font-family: sans-serif;'>&nbsp;x&nbsp;</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<div align=right width=10px id=PIN onclick='unPin();' style='background-color: DarkOrange; transform-origin:50%; transform:rotate(45deg); display:inline-block; font-family: sans-serif; color:#0000FF;  border-left: 10px solid transparent; border-right: 10px solid transparent; border-top: 10px solid transparent; border-bottom: 10px solid transparent; border-radius: 20px;'>T</div>
				<div id='mydivcontent'><B>Click or Rollover someone's name.</B>
				<BR/><I>Click again to pin or unpin an ancestor.</I><BR/>
					<BR/><A >Wiki ID link will take you to their WikiTree profile</A>
					<BR/>
					<BR/>b. Birthdate, Birth Place
					<BR/>d. Date of Death, Place of Death
					<BR/><A><IMG src=pix/fan240.png height=20px></A>
					<BR/><I>Click fan icon to view new Fan Chart</I>
			</div>
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