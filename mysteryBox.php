<?php

// ======================
// MYSTERY BOX App, for tracking and assigning Profiles during Mystery Box Challenge
// ======================

// Mystery Box Challenge - conceived and implemented by Vicki Blanco
// auto-number update permission given .... I think ....
// App idea by Greg Clarke, October 2024
// Programmed by Greg Clarke and Ambar Díaz

// Input - WikiTree Profile ID
// Output - 

session_start();
$_SESSION["app"] = "WikiTree"; // say something to initialize session
date_default_timezone_set("UTC");

// =========
// CONSTANTS
// =========
define("API_URL", "https://api.wikitree.com/api.php");

// =========
// VARIABLES
// =========
?>
	<HTML>
		<HEAD>
		<TITLE>? Box ?</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="../css/base.css"/>
		<LINK REL="stylesheet" TYPE="text/css" HREF="../WTapps.css"/>

		<!-- For convenience, use JQuery for our Ajax interactions with the API. Use the cookie module to grab any existing user id. --> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="/apps/jquery.cookie.js"></script>

		<!-- <script src="/apps/wikitree.js"></script> -->
		<script src="../wikitree.js"></script>
		<script src="js/api/WikiTreeAuthGPC.js"></script>

		<script src="mysteryBoxSuggestions.js"></script>
		<script src="js/main.js" type="module"></script>

	<style>
			input.mediumInputButton {				
			    width: auto;
			    padding: 5px;
			    text-transform: none;
			    font-size: medium;
			}

			input.activeSection {
				background-color: orange;
			}
			
			span.boxParam {
			    background-color: orange;
			    font-family: monospace;
			    font-weight: bold;
			    outline-color: darkgreen;
			    outline-width: thick;
			    outline-style: double;
			}

         #confirm {
            display: none;
            background-color: gold;
            border: 15px double darkgreen;
            border-radius: 20px;
            position: fixed;
            width: 500px;
            left: 50%;
            margin-left: -250px;
            padding: 6px 8px 8px;
            box-sizing: border-box;
            text-align: center;
         }
         #confirm button {
            background-color: #48E5DA;
            display: inline-block;
            border-radius: 5px;
            border: 1px solid #aaa;
            padding: 5px;
            text-align: center;
            width: 360px;
            cursor: pointer;
            color: blue;
            margin: 5px;
            text-transform: none;
         }

         #confirm .message {
            text-align: left;
         }

         #FloatingLineage {
         	 
            float: right;
		    background-color: #fde193 ; /*#91FF00;*/
		    border: 1px solid #aaa;
		    /* position: fixed; */
		    /* width: 45%; */	
		    /* left: 50%; */
		    /* margin-left: -100px; */
		    padding: 6px 8px 8px;
		    box-sizing: border-box;
		    text-align: center;
		    /*z-index: 1;*/
		    border-radius: 20px;
		    border: green solid 2px;

         }
      </style>
		<script type="text/javascript">

		// Define the appID that will be used in all calls to the API - needed for WikiTreeAuthGPC.js 
		var appID = 'mBox';

		// Define the URL where our API requests will be POSTed.
		// This is set from PHP constant and can be overridden by config.js if needed
		var API_URL = <?php echo json_encode(API_URL); ?>;
		
		var theRecentTime = new Date();
		// var PrivatePersonForArray =  {Name:"Private", LastNameAtBirth:"Private"};

		var checkOutAllSuggestionsForValidation = false;

		// VALID VARIABLES to be used in the MYSTERY BOX APP (need to cull the list above )
		var currentMysteryBoxData = {"takenIDs":[]};
		var SuggestionsListObject = null;

		var queryStarter = "";
		var queryStarterArray = [];

		var numProfilesChosen = 0;
		var ignoreMeListOfProfileIDs = [];

		var thisBoxLevel = "";
		var thisBoxTime = "";
		var thisBoxPlace = "";
		var thisPlaceType = "Location";
		var thisAcceptedTime = "";
		var thisAcceptedTimeZone = "";
		var thisDoneTime = "";

		var mysteryProfiles = [ {}, {}, {}, {}, {} ];
		var myStatsData = {};
		var allStatsData = {};

		var thisBoxNum = -1;
		var thisUserID = -1;
		var thisUserName = "";
		var thisBoxParams = "";
		var theProfilesChosenHTML = "";

		var thisWikiTreerChallenger = null;
		var currentUserStatus = "Open"; // Open / Active / Done / Practice

		var currentBoxFromSQL = {};

	
		// When the document is ready, see if we have a user session with checkLogin().
		// If no, then show the "need login" div with the form and button to post to the API clientLogin() function.
		// If yes, then hide that and show our function content.
		$(document).ready(function(){ 
			//alert ("Ready");


			wikitree.init({});
			// alert("Init done");

			// wikitree.session.checkLogin({})
			login()
				.then(function(data) { 
					// alert("in checkLogin");
					wikitree.session.loggedIn = thisUserIsLoggedIn;
					wikitree.session.user_name = getUserName();
					wikitree.session.user_id = getUserId();

					if (wikitree.session.loggedIn) { 
						/* We're already logged in and have a valid session. */
						// alert("Logged In @ " + wikitree.session.user_name);

						// DO NOT NEED THE LOGIN DIV 
						document.getElementById('need_login').style.display = 'none';
						// console.log("Do Not Need the LOGIN DIV");

						<?php
      $ph = isset($ph) ? $ph : 0; // Default to 0 if not set
      if ($ph == 0 && isset($_SESSION["id"]) && $_SESSION["id"] > "") {
          // Fill in form with username

          echo "\ndocument.getElementById('ResetAndSearch').style.display = 'block';";
          echo "\ndocument.getElementById('Profile').value = '" . $_SESSION["id"] . "';";
      } elseif ($ph === 0) {
          // Fill in form with username
          echo "\ndocument.getElementById('ResetAndSearch').style.display = 'block';";
          echo "\ndocument.getElementById('Profile').value = wikitree.session.user_name;";
      } elseif ($ph == 1 && isset($_SESSION["id"]) && $_SESSION["id"] > "") {
          // start the process to actually find the surnames and build the list
          echo "\ndocument.getElementById('ResetAndSearch').style.display = 'none';";
          if ($ask == "Y") {
              echo "showManualInfo();";
          } else {
              echo "generateSurnames();";
          }
      } else {
          echo "\ndocument.getElementById('ResetAndSearch').style.display = 'block';";
          echo "\ndocument.getElementById('Profile').value = wikitree.session.user_name;";
          echo "\ndocument.getElementById('need_login').style.display = 'none';";
      }
      ?>	
						// document.getElementById('Profile').value = wikitree.session.user_name;
						// document.TheStart.WikiTreeUserName.value = wikitree.session.user_name;
						$('#need_login').hide();
						// $('#logged_in').show();
					} 
					else { 
						/* We're not yet logged in, but maybe we've been returned-to with an auth-code */
						// alert ("Not logged in yet - in process");

						document.getElementById('ResetAndSearch').style.display = 'none';
						if (document.getElementById('ResetBtnPh1')){
							document.getElementById('ResetBtnPh1').style.display = 'none';
							document.getElementById('Prompts').style.display = 'none';
						}


						<?php if (isset($_SESSION["id"]) && $_SESSION["id"] > "") {
          // Fill in form with username to make it quick and easy to generate your own surnames list
          echo "var id = '" . $_SESSION["id"] . "';";
      } else {
          echo "var id = 'none';";
      } ?>	


						var x = window.location.href.split('?');
						var queryParams = new URLSearchParams( x[1] );
						if (queryParams.has('authcode')) { 
							var authcode = queryParams.get('authcode');
							wikitree.session.clientLogin( {'authcode': authcode}  )
								.then(function(data) {
									if (wikitree.session.loggedIn) { 
										/* If the auth-code was good, redirect back to ourselves without the authcode in the URL (don't want it bookmarked, etc). */
										// alert("auth code is OK after all ?");
										document.getElementById('ResetAndSearch').style.display = 'block';
										document.getElementById('need_login').style.display = 'none';
										document.getElementById('Profile').value = wikitree.session.user_name;

										if (id != 'none') {

											window.location.assign('https://apps.wikitree.com/apps/clarke11007/apps/mysteryBox.php' );

										} else {
											window.location.assign('https://apps.wikitree.com/apps/clarke11007/apps/mysteryBox.php');
										}

									}
									else { 
										document.getElementById('need_login').style.display = 'block';
										//window.location = "https://api.wikitree.com/api.php?appId=mBox&action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/surnames.php";
										// alert ("has authcode but seemingly not logged in ???");
									}
								});
						} else { 
							document.getElementById('need_login').style.display = 'block';
							// alert ("has no authcode and seemingly not logged in ???");
							//window.location = "https://api.wikitree.com/api.php?appId=mBox&action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/surnames.php";
							//alert ("NOT logged !!");
							// $('#need_login').show();
							// $('#logged_in').hide();
						}
					}
				});
			//alert ("After session.checkLogin");
				
				thisUserID = wikitree.session.user_id;
				thisUserName = wikitree.session.user_name;
				// thisUserID = 3921860;
				// thisUserName = "Kellett-33";
				
				// Initialize the application after module loads
				window.initAfterModuleLoad = function() {
					loadMysteryBoxJSON();
					loadSuggestionsListJSON();
					loadProfileForWikiTreerChallenger();
					callBeginNowData(thisUserID);
				};
				
				// Wait for required functions to be available before calling
				const waitForRequiredFunctions = () => {
					if (typeof window.retrieveFromSQL === 'function' && 
						typeof window.hasUserDoneMysteryBoxForTodayFromSQL === 'function') {
						// Module is loaded, trigger initialization
						if (typeof window.initAfterModuleLoad === 'function') {
							window.initAfterModuleLoad();
						}
					} else {
						setTimeout(waitForRequiredFunctions, 100);
					}
				};
				waitForRequiredFunctions();
				// checkOutValidSuggestions();

				// checkForMyMysteryBoxThenStart();

		});

		async function callBeginNowData(thisUserID) {
			console.log("callBeginNowData: ", {thisUserID});
			
			// Ensure retrieveFromSQL is available
			if (typeof window.retrieveFromSQL !== 'function') {
				console.error("retrieveFromSQL function not available");
				return;
			}
			
			let beginNowData =  await retrieveFromSQL("BeginNow", ["ID="+thisUserID] );
			console.log({beginNowData});
			checkForMyMysteryBoxThenStartAfterSQL(beginNowData);

		}


		var CongratulationsPracticeBoxHTML = "<H3>CONGRATULATIONS!</H3><B>You have completed your MYSTERY BOX for today!<BR><BR>Come back tomorrow and try another one!</B><BR><BR>If you would like to keep up your skills to be ready for tomorrow,<BR>" + "<input type=submit class='submit mediumInputButton' style='color:orange;' id=showMyStats value='do a practice box' onclick='doPracticeBox();'><BR><I>NOTE: This Practice Box will NOT count in your stats,<BR>but, it WILL help clear a few more Suggestions, and help improve the Tree.</I>";

		function checkForMyMysteryBoxThenStart(){

		}

		function checkForMyMysteryBoxThenStartAfterSQL(beginNowData){
			updateStatusStuff();
			console.log("READY to start with ",thisUserID, beginNowData);
			console.log()

			thisUserID = 1.0 * thisUserID;
			if (beginNowData.currentBoxStatus == "Active"/* currentMysteryBoxData.activeIDs.indexOf(thisUserID) > -1 */) {
				currentUserStatus = "Active";
				document.getElementById("ChooseBoxButton").style.display = "none";
				document.getElementById("ResetAndSearch").style.display = "none";
				document.getElementById("GoToBoxButton").style.display = "revert";
				document.getElementById("GoToBoxButton").value = "Go to Mystery Box";
				document.getElementById("StartingNoteSPAN").innerText = "You have a MYSTERY BOX on the go - check out the details now!";
				currentBoxFromSQL = beginNowData.currentBox;
				thisAcceptedTime = currentBoxFromSQL.bdTimeStart;
				loadProfilesForMysteryBoxInProgress(false);
			} else if (beginNowData.currentBoxStatus == "Practice") {
				currentUserStatus = "Practice";
				document.getElementById("ChooseBoxButton").style.display = "none";
				document.getElementById("ResetAndSearch").style.display = "none";
				document.getElementById("GoToBoxButton").style.display = "revert";
				currentBoxFromSQL = beginNowData.currentBox;
				thisAcceptedTime = new Date(currentBoxFromSQL.bdTimeStart);
				document.getElementById("GoToBoxButton").value = "Go to Practice Box";

				document.getElementById("StartingNoteSPAN").innerText = "You have a PRACTICE BOX on the go - check out the details now!";
				loadProfilesForMysteryBoxInProgress(false);

			} else {
				// Ensure hasUserDoneMysteryBoxForTodayFromSQL is available
				if (typeof window.hasUserDoneMysteryBoxForTodayFromSQL !== 'function') {
					console.error("hasUserDoneMysteryBoxForTodayFromSQL function not available");
					// Fallback: treat as not done if function not available
					currentUserStatus = "Open";
					document.getElementById("ChooseBoxButton").style.display = "revert";
					document.getElementById("GoToBoxButton").style.display = "none";
					document.getElementById("StartingNoteSPAN").innerText = "";
					return;
				}
				if (hasUserDoneMysteryBoxForTodayFromSQL(beginNowData.recentDoneBox, updateStatusStuff) ) {
					currentUserStatus = "Done";
					document.getElementById("StartingNoteSPAN").innerHTML = CongratulationsPracticeBoxHTML;
					document.getElementById("GoToBoxButton").style.display = "none";
					document.getElementById("ChooseBoxButton").style.display = "none";
				} else {
					currentUserStatus = "Open";
					document.getElementById("ChooseBoxButton").style.display = "revert";
					document.getElementById("GoToBoxButton").style.display = "none";
					document.getElementById("StartingNoteSPAN").innerText = "";
				}

			}

			console.log(currentMysteryBoxData);
			// ChooseBoxButton
			// StartingNoteSPAN
			// GoToBoxButton
			updateStatusStuff();
		}


		function clickForArchiveBoxDetails(num, whichDIV = "") {
			clickForMysteryBoxDetails(num, "Archive", whichDIV);
		}
		function clickForActiveBoxDetails(num, whichDIV = "") {
			clickForMysteryBoxDetails(num, "Active");
		}

		function findIDforBoxNum(num) {
			let thisID = -1;
			for (var anID in  currentMysteryBoxData.activeMystery) {
				let thisActiveMystery = currentMysteryBoxData.activeMystery[anID];
				if (thisActiveMystery.box == num) {
					thisID = anID;
					return anID;
				}
			}
			return thisID;
		}

		function clickForMysteryBoxDetails(num, status, whichDIV = "") {
			console.log("clickForMysteryBoxDetails(",num, status, whichDIV,")");

			let boxHTML = "<TABLE border=1 align=center><TR><TH>Box #</TH><TD>" + num + "</TD></TR>";
			let thisMystery = {};
			let arrayToCheck = allStatsData.solvedBoxes;
			if (whichDIV == "MyStats") {
				arrayToCheck = myStatsData.solvedBoxes;
			} else if (status == "Active") {
				arrayToCheck = allStatsData.activeBoxes;
			}

			for (var i = 0; i < arrayToCheck.length; i++) {
				if (arrayToCheck[i].bdBoxNum == num) {
					thisMystery = arrayToCheck[i];
					break;
				}
			}

			let theID = thisMystery.bdID;
			let theKey = thisMystery.bdKey;

			if (!theID) {
				console.log("NO ID found !");
			} else {
				theKey = theID + "-" + num;
			}

			console.log("Hello", num);

			let currentNum = document.getElementById("show"+whichDIV+"BoxDetailsBoxNumSPAN").innerText;

			if (currentNum == num) {
				document.getElementById("show"+ whichDIV +"BoxDetailsDIV").style.display = "none";
				document.getElementById("show"+whichDIV+"BoxDetailsBoxNumSPAN").innerText = "-";
				return;
			}

			document.getElementById("show"+whichDIV+"BoxDetailsBoxNumSPAN").innerText = num;

			
			// let thisMystery = currentMysteryBoxData.archiveMystery[theKey];
			// if (status == "Active") {
			// 	theID = findIDforBoxNum(num);
			// 	theKey = theID;
			// 	thisMystery = currentMysteryBoxData.activeMystery[theID];
			// }

			if (theKey > "") {

				console.log(thisMystery);

				boxHTML += "<TR><TD colspan=2>&nbsp;</TD></TR>";

				boxHTML += "<TR><TH>Level</TH><TD>" + thisMystery.bdLevel + "</TD></TR>";
				if (thisMystery.bdTime == 0) {
					boxHTML += "<TR><TH>Time</TH><TD>" + "anytime" + "</TD></TR>";
				} else {
					boxHTML += "<TR><TH>Time</TH><TD>" + thisMystery.bdTime + "th century</TD></TR>";
				}

				if (thisMystery.bdPlace == "") {
					boxHTML += "<TR><TH>Place</TH><TD>" + "anywhere" + "</TD></TR>";
				} else {
					boxHTML += "<TR><TH>Place</TH><TD>" + thisMystery.bdPlace + "</TD></TR>";
				}

				boxHTML += "<TR><TD colspan=2>&nbsp;</TD></TR>";

				boxHTML += "<TR><TH>Time Started</TH><TD>" + thisMystery.bdTimeStart + "</TD></TR>";
				boxHTML += "<TR><TH>Time Done</TH><TD>" + thisMystery.bdTimeEnd + "<BR/>" + thisMystery.bdTimeZone + "</TD></TR>";

				let numDaysTodaySince2020 = 365 * theRecentTime.getFullYear() + 31 * theRecentTime.getUTCMonth() + 1.0 * theRecentTime.getUTCDate();
				let numDaysTimeStartSince2020 = 365 * thisMystery.bdTimeStart.substr(0,4) + 31 * (thisMystery.bdTimeStart.substr(5,2) - 1) + 1.0 * thisMystery.bdTimeStart.substr(8,2);
				console.log("Box was started ", numDaysTodaySince2020 - numDaysTimeStartSince2020, "days ago.", numDaysTodaySince2020 , numDaysTimeStartSince2020);

				if (document.getElementById("AdoptMeDIV")){
					document.getElementById("AdoptMeDIV").style.display = "none";
					if ((currentUserStatus == "Open") && (numDaysTodaySince2020 - numDaysTimeStartSince2020) > 30) {
						document.getElementById("AdoptMeDIV").style.display = "block";
					}
				}


			} else {
				console.log("NO KEY found - no details on this mystery box");
				boxHTML += "<TR><TD colspan=2>NO user / mystery box record found<BR/>Sorry, no details on this mystery box</TD></TR>";
			}

			boxHTML += "</TABLE>";
			document.getElementById("show"+ whichDIV +"BoxDetailsInnerDIV").innerHTML = boxHTML;

			document.getElementById("show"+ whichDIV +"BoxDetailsDIV").style.display = "block";

		}

		var clickForArchiveBoxDetailsFunction = "clickForArchiveBoxDetails";
		var clickForActiveBoxDetailsFunction = "clickForActiveBoxDetails";
		var clickForMyStatsBoxDetailsFunction = "clickForMyStatsBoxDetails";


		function getNumBoxesSVG( num, boxDX = 0, boxDY = 0, size = 60 , onClickFn = null, onClickParameter = "") {
			let svgHTML = "";

				let frontClr =  "#008000";
				let topClr = "#25422d";//"#333333";// "#3B3C10";
				let sideClr = "#fcb815";// "#FFA500";
				let fontClr = "yellow";

			
					let dx = 0;
					if (num < 10) {
						dx = 5;
					} else if (num == 100) {
						dx = -5;
					}
					
					
					let i = 0;
					let j = 0;
					
					let spacing = size; //= 60;
					let side = size * 3/5; //= 36;
					let slant = size / 5; // 12;
					let down = size / 4 ; // 15;

					// onClickFn = "clickForArchiveBoxDetails";
					let onClickFnCode = ""; //
					if (onClickFn > "") {
						onClickFnCode = " onclick='" + onClickFn + "(\"" + /*num + ",\"" +*/ onClickParameter + "\", \"DatesSolvedBoxListDIV\");' ";
					}
						
					svgHTML +=	"<g id=boxGb" + num + onClickFnCode + " style='transform: translate(" + (boxDX + spacing*i) + "px, " + (boxDY + spacing*j) + "px); cursor:pointer; ' ><rect   fill=black x=10 y=10 width=40 height=25 stroke=orange rx=8 ry=8></rect>" 
						+ "<text fill=orange x=22 y=27 font-weight=bold>" + num + "</text> " + "</g>";
											

				return svgHTML;
			
		}

		function getSingleBoxSVG( num, boxDX = 0, boxDY = 0, size = 60 , onClickFn = null, onClickParameter = "") {
			let svgHTML = "";

				let frontClr =  "#008000";
				let topClr = "#25422d";//"#333333";// "#3B3C10";
				let sideClr = "#fcb815";// "#FFA500";
				let fontClr = "yellow";

			
					let dx = 0;
					if (num < 10) {
						dx = 5;
					} else if (num == 100) {
						dx = -5;
					}
					
					
					let i = 0;
					let j = 0;
					
					let spacing = size; //= 60;
					let side = size * 3/5; //= 36;
					let slant = size / 5; // 12;
					let down = size / 4 ; // 15;

					// onClickFn = "clickForArchiveBoxDetails";
					let onClickFnCode = ""; //
					if (onClickFn > "") {
						onClickFnCode = " onclick='" + onClickFn + "(" + num + ",\"" + onClickParameter + "\");' ";
					}
						
					svgHTML +=	"<g id=boxGb" + num + onClickFnCode + " style='transform: translate(" + (boxDX + spacing*i) + "px, " + (boxDY + spacing*j) + "px); cursor:pointer; ' ><path   fill=" + topClr + " d='M" + (8 ) + "," + (down ) + " " 
						+ (8 + side  ) + "," + (down ) + " " 
						+ (8 + side + slant ) + "," + ( down + 0 - slant ) + " " 
						+ (8 + slant ) + "," + ( down + 0  - slant ) + "' /></g>";
						
					svgHTML +=	"<g id=boxGc" + num + onClickFnCode + " style='cursor:pointer; transform: translate(" + (boxDX + spacing*i) + "px, " + (boxDY + spacing*j) + "px);' ><path  fill=" + sideClr + " d='M" + (8 + side ) + "," + (down + side ) + " " 
						+ (8 + side  ) + "," + ( down ) + " " 
						+ (8 + side + slant ) + "," + ( down + 0 - slant ) + " " 
						+ (8 + side + slant ) + "," + ( down + side  - slant ) + "' /></g>";

					svgHTML +=	"<g id=boxGa" + num + onClickFnCode + " style='transform: translate(" + (boxDX + spacing*i) + "px, " + (boxDY + spacing*j) + "px); cursor:pointer; '><path fill=" + frontClr + " d='M" + (8 ) + "," + (down ) + " " 
						+ (8 + side  ) + "," + (down ) + " " 
						+ (8 + side ) + "," + (down +  side) + " " 
						+ (8 ) + "," + ( down + side) + "' /></g>";
						
					svgHTML +=	"<text id=boxGd" + num + onClickFnCode +  " font-weight=bold x=" + (boxDX + (size/4) + dx ) + " y="+ (boxDY + down + (size/3) ) + " fill=" + fontClr + " style='cursor:pointer; transform: translate(" + (spacing*i) + "px, " + (spacing*j) + "px);'>" + num + "</text>";
				

				return svgHTML;
			
		}


		function loadMysteryBoxes() {
			updateStatusStuff();
			//alert("pick a box"):
			console.log("loadMysteryBoxes");
			document.getElementById("ResetAndSearch").style.display = "none";
			document.getElementById("ChooseBox").style.display = "block";
			document.getElementById("AllBoxes").style.height = "630px";

			if (currentUserStatus == "Practice") {
				// Do we do anything unusual here?
				// thisBoxNum = -1;
			}

			let theTimeFrame = thisBoxTime + "th century";
			if (thisBoxTime == "") {
				theTimeFrame = "anytime";
			}

			let thePlace = thisBoxPlace;
			if (thisBoxPlace == "") {
				thePlace = "anywhere";
			}


			thisBoxParams =  "<span class=boxParam>" + thisBoxLevel + "</span>"  + " &nbsp;&nbsp;&nbsp; "  + "<span class=boxParam>" + theTimeFrame + "</span>" + " &nbsp;&nbsp;&nbsp; "  + "<span class=boxParam>" + thePlace + "</span>" ;

			document.getElementById("ChooseMysteryBoxParams").innerHTML = thisBoxParams;
						
			showAvailableMysteryBoxesInDIV("AllBoxes");
		
			console.log("CURSOR: ", document.getElementById("AllBoxes").style.cursor);
		}



		function consoleClickNumber(num = -1) {
			console.log("You have clicked on ", num);
		}

		async function showAvailableMysteryBoxesInDIV(boxesDIV = "AllBoxes", clickFunctionName = "chooseBoxNum") {
			let svgHTML = "";
			let spacing = 60;
			let side = 36;
			let slant = 12;
			let down = 15;
			
			var boxesToHide = [];
			let firstBoxNum = 1;
			let firstBoxRowStarter = 0;
			// console.log("BOXES:",currentMysteryBoxData.takenBoxes);

			const theResponse = await retrieveFromSQL("ListBoxes");

			if (theResponse.firstMissing > 0 && theResponse.boxList.length > 0) {
				firstBoxNum = theResponse.firstMissing;
				boxesToHide = theResponse.boxList;
				
				firstBoxRowStarter = 10 * Math.floor((firstBoxNum - 1) / 10);
			}

			console.log("First Available BOX NUM is ", firstBoxNum,{firstBoxRowStarter});


			let frontClr =  "#008000";
			let topClr = "#25422d";//"#333333";// "#3B3C10";
			let sideClr = "#fcb815";// "#FFA500";
			let fontClr = "yellow";

			let num = 0;
			for (var j = 0; j < 10; j++) {
				for (var i = 0; i < 10; i++) {
					num = firstBoxRowStarter + i + 1 + j * 10;
					let dx = 0;
					if (num < 10) {
						dx = 5;
					} else if (num == 100) {
						dx = -5;
					}
					
					console.log("Search for ", num, " index:", boxesToHide.indexOf(num));

					if (boxesToHide.indexOf(num) == -1) {

						svgHTML +=	"<g id=boxGa" + num + " onclick='"+ clickFunctionName +"(" + num + ");' style='transform: translate(" + (spacing*i) + "px, " + (spacing*j) + "px); cursor:pointer; '><path fill=" + frontClr + " d='M" + (8 ) + "," + (down ) + " " 
							+ (8 + side  ) + "," + (down ) + " " 
							+ (8 + side ) + "," + (down +  side) + " " 
							+ (8 ) + "," + ( down + side) + "' /></g>";
							
						svgHTML +=	"<g id=boxGb" + num + " style='transform: translate(" + (spacing*i) + "px, " + (spacing*j) + "px); cursor:pointer; ' ><path fill=" + topClr + " d='M" + (8 ) + "," + (down ) + " " 
							+ (8 + side  ) + "," + (down ) + " " 
							+ (8 + side + slant ) + "," + ( down + 0 - slant ) + " " 
							+ (8 + slant ) + "," + ( down + 0  - slant ) + "' /></g>";
							
						svgHTML +=	"<g id=boxGc" + num + " style='cursor:pointer; transform: translate(" + (spacing*i) + "px, " + (spacing*j) + "px);' ><path fill=" + sideClr + " d='M" + (8 + side ) + "," + (down + side ) + " " 
							+ (8 + side  ) + "," + ( down ) + " " 
							+ (8 + side + slant ) + "," + ( down + 0 - slant ) + " " 
							+ (8 + side + slant ) + "," + ( down + side  - slant ) + "' /></g>";
							
						svgHTML +=	"<text id=boxGd" + num + " onclick='"+ clickFunctionName +"(" + num + ");'  font-weight=bold x=" + (16 + dx ) + " y="+ (down + 20 ) + " fill=" + fontClr + " style='cursor:pointer; transform: translate(" + (spacing*i) + "px, " + (spacing*j) + "px);'>" + num + "</text>";
					}
				}	
				console.log("DONE a row - now ", {num}, "good time to ++ firstBoxRowStarter if we have a blank row or two ???");
				let nextFreeBox = num + 1;
				while (boxesToHide.indexOf(nextFreeBox) != -1) {
					nextFreeBox++;
				}
				console.log("FOUND ", nextFreeBox);
				if (nextFreeBox - num > 20) {
					// we have a gap of AT LEAST 2 rows (21 - 30 == 2 row gap, 31 - 40 == 3 row gap) - so - we want to reduce that to a SINGLE row gap,
					// SO .. we need to increase NUM by the (# of rows) - 1  (x ten of course)
					// AND ... that can be done by increasing the firstBoxRowStarter
					let numRowsToSkip = Math.ceil ((nextFreeBox - num ) / 10) - 2;
					firstBoxRowStarter += 10 * numRowsToSkip;
				}


			}
			
			if (boxesDIV == "RETURN") {
				return svgHTML;
			}

			document.getElementById(boxesDIV).innerHTML = svgHTML;
			document.getElementById(boxesDIV).style.cursor = "pointer";
		}

		function chooseBoxNum(numChosen) {
			updateStatusStuff();
			thisBoxNum = numChosen;
			document.getElementById("chooseBoxMsg").innerHTML = "<BR/>You have chosen box # " + numChosen;

			document.getElementById("AllBoxes").style.height = "200px";
			document.getElementById("AllBoxes").innerHTML = getSingleBoxSVG(numChosen,210, 10, 180);

			updateJSONdata("AddBox", numChosen);
			document.getElementById("theProfilesChosen").innerHTML = "";
			document.getElementById('theProfilesChosen').style.display = "revert";
			chooseProfiles(numChosen);
		
		}		

		function changeOnwardButton(type=1) {
			let LocationSelect = document.getElementById("LocationSelect");
			let LocationRegionText = document.getElementById("LocationRegionText");
			let LocationOnwardButton = document.getElementById("LocationOnwardButton");

			if (LocationSelect.value == "?" && LocationRegionText.value == "") {
				LocationOnwardButton.style.display = "none";
			} else if (type==2 && LocationRegionText.value > "") {
				LocationOnwardButton.style.display = "revert";
				LocationOnwardButton.value = "Onward to "  + LocationRegionText.value;
				document.getElementById("LocationSelect").value = "?";
			} else if (type==1) {
				LocationOnwardButton.style.display = "revert";
				LocationOnwardButton.value = "Onward to "  + LocationSelect.value;
				document.getElementById("LocationRegionText").value = "";
			}
			updateStatusStuff();
		}

		function jumpToAcceptedBox() {

			document.getElementById('ResetAndSearch').style.display = "none";
			document.getElementById('need_login').style.display = "none";
			document.getElementById('ChooseBox').style.display = "none";
			document.getElementById('AcceptedDIV').style.display = "revert";
			document.getElementById('ReturnBoxDIV').style.display = "revert";

			if (currentUserStatus == "Practice") {
				document.getElementById('AcceptedDetailsH3').innerText = "Practice Box Details";				
			} else {
				currentUserStatus = "Active";
				document.getElementById('AcceptedDetailsH3').innerText = "Mystery Box Details";
			}

		}

		function showNextDIV(currDIV, nextDIV) {
			document.getElementById(currDIV).style.display = "none";
			document.getElementById(nextDIV).style.display = "revert";
			updateStatusStuff();
		}

		function chooseLevel(levelChosen) {
			thisBoxLevel = levelChosen;
			showNextDIV('LevelDIV','TimeDIV');
			updateStatusStuff();
		}

		function chooseTime(timeChosen) {
			thisBoxTime = timeChosen;
			showNextDIV('TimeDIV', 'PlaceDIV');
			updateStatusStuff();
		}

		function choosePlace(placeChosen) {
			if (placeChosen == "?") {
				if (document.getElementById("LocationRegionText").value > "") {
					thisBoxPlace = document.getElementById("LocationRegionText").value;
					thisPlaceType = "Location";
					
				} else {
					thisBoxPlace = document.getElementById("LocationSelect").value;
					thisPlaceType = "Country";

				}
			} else {
				thisBoxPlace = "";
			}

			// make sure to get the most recent version of the data !
			loadMysteryBoxJSON(false, true) ; //(doCheck = true, jump2PickBox = false);
			updateStatusStuff();
		}

		function acceptChallenge() {			
			updateStatusStuff();
			showNextDIV('ChooseBox','AcceptedDIV');
			showNextDIV('ChooseBox','ReturnBoxDIV');
			thisAcceptedTime = Date();
			updateJSONdata("Accept", thisUserID, thisBoxNum);
			if (currentUserStatus == "Practice") {
				document.getElementById('AcceptedDetailsH3').innerText = "Practice Box Details";				
			} else {
				currentUserStatus = "Active";
				document.getElementById('AcceptedDetailsH3').innerText = "Mystery Box Details";
			}
			updateFullDetails();
			updateStatusStuff();
		}


		async function returnMysteryBox() {		
			updateStatusStuff();	
			document.getElementById('ResetAndSearch').style.display = "revert";
			document.getElementById('TheStart').style.display = "revert";

			document.getElementById('need_login').style.display = "none";
			document.getElementById('ChooseBox').style.display = "none";
			document.getElementById('AcceptedDIV').style.display = "none";
			document.getElementById('ReturnBoxDIV').style.display = "none";

			document.getElementById('ChooseBoxButton').style.display = "revert";
			document.getElementById('GoToBoxButton').style.display = "none";
			document.getElementById('AcceptButtonSPAN').style.display = "none";
			document.getElementById('AcceptButton').style.display = "none";
			document.getElementById('theProfilesChosen').style.display = "none";
			document.getElementById('StartingNoteSPAN').innerText = "";

			updateJSONdata("Return", thisUserID, thisBoxNum, currentUserStatus);

			if (await hasUserDoneMysteryBoxForToday(thisUserID, updateStatusStuff) ) {
				currentUserStatus = "Done";
				document.getElementById("StartingNoteSPAN").innerHTML = CongratulationsPracticeBoxHTML;
				document.getElementById("GoToBoxButton").style.display = "none";
				document.getElementById("ChooseBoxButton").style.display = "none";
			} else {
				currentUserStatus = "Open";
				document.getElementById("ChooseBoxButton").style.display = "revert";
				document.getElementById("GoToBoxButton").style.display = "none";
				document.getElementById("StartingNoteSPAN").innerText = "";
			}

			updateStatusStuff();
		}

		function updateFullDetails( showInstructions = true ) {
			let theFullName = thisUserName;// wikitree.session.user_name;
			console.log("UPDATE FULL DETAILS: ", {thisAcceptedTime}, thisAcceptedTime.indexOf("GMT"));
			let theAcceptedDate = thisAcceptedTime.substring(0, thisAcceptedTime.indexOf("GMT"));
			
			if (thisAcceptedTime.indexOf("GMT") == -1) {
				theAcceptedDate = thisAcceptedTime;
			}					

			let theAcceptedTime = theAcceptedDate.substring( theAcceptedDate.indexOf(":") - 2);
			theAcceptedDate = theAcceptedDate.substring(0,  theAcceptedDate.indexOf(":") - 2);
			// let theBoxImage = "Box #" + thisBoxNum;
			let theBoxImage = "<svg width=70px height=70px>" + getSingleBoxSVG(thisBoxNum) + 	"</svg>";

			let theTimeFrame = thisBoxTime + "th century";
			if (thisBoxTime == "") {
				theTimeFrame = "anytime";
			}

			let thePlace = thisBoxPlace;
			if (thisBoxPlace == "") {
				thePlace = "anywhere";
			}

			let showChecked = "checked";
			let hideChecked = "";
			let displayNoneInstructions = "style='display:revert;'";

			if (showInstructions == false) {
				hideChecked = "checked";
				showChecked = "";
				displayNoneInstructions = "style='display:none;'";
			}

			let theHTML = "<table border=0 width=100% align=center><TR background-color=lime ><TD style='vertical-align: top;'><I>Challenge accepted<BR/>by</I><B> " + theFullName + "</B><BR/><I>on</I> " + theAcceptedDate + "<BR/><I>at</I> " + theAcceptedTime + "<BR/>" + thisAcceptedTimeZone  + "<BR>&nbsp; </TD>";
			theHTML += "<TD style='vertical-align: top;'>" + "<span class=boxParam>" + thisBoxLevel + "</span>"  + " &nbsp;&nbsp;&nbsp; "  + "<span class=boxParam>" + theTimeFrame + "</span>" + " &nbsp;&nbsp;&nbsp; "  + "<span class=boxParam>" + thePlace + "</span>"    + "</TD>";
			theHTML += "<TD style='vertical-align: top;'>"+theBoxImage+"</TD></TR>";
			theHTML += "<TR><TD colspan=2><B>Instructions:</B> <input type=radio name=showHideInstructions "+ showChecked +" onclick='document.getElementById(\"theInstructions\").style.display = \"revert\";'>Show  <input type=radio name=showHideInstructions "+ hideChecked + " onclick='document.getElementById(\"theInstructions\").style.display = \"none\";'>Hide<BR>"; 
			theHTML += "<DIV id=theInstructions "+ displayNoneInstructions +">For each of the profiles below:<OL>";
			theHTML +="<LI>Open up the profile and Fix the suggestion as indicated. <BR>(<I>Click on the Suggestion # to view a page on how to fix it.</I>)<BR>(<I>Feel free to fix other things while you're there too!</I>)";
			theHTML +="<LI>Indicate what you did in the comment,<BR/>and include  <B><I>Mystery Box #" + thisBoxNum + "</I></B>  as part of the comment."; 
			theHTML +="<LI>Also, choose the Suggestion menu item, and click the STATUS button and indicate the fix to the Suggestion.";
			theHTML +="<LI>Click the DONE button here to record that the profile has been dealt with.  Mystery solved.</OL>";
			theHTML +="If you need to, you can also SWAP out a profile and pick another random one, if the Suggestion is too hard.<BR><BR>You can also RETURN the BOX if you want to get a whole fresh batch of profiles, or you just don't have time to finish these ones off.<BR><BR>Note: You can always come back another time, later today, or another day, and finish off the profiles then.</DIV><BR>&nbsp;";

			theHTML +="</TD></TR>";
			theHTML +="</TABLE>";
			
			theHTML += "<table id=FullDetailsTable border=1 align=center>"; // "<TR background-color=lime ><TH>Name</TH><TH>Details</TH><TH>Suggestion</TH><TH colspan=2>Action</TH></TR>";

			theHTML += getFullDetailsTableInnerHTML();
			 
			theHTML += "</table>";
			document.getElementById("FullDetails").innerHTML = theHTML;
		}


		function updateFullDetailsTable() {
			if (document.getElementById("FullDetailsTable")){
						document.getElementById("FullDetailsTable").innerHTML = getFullDetailsTableInnerHTML();
					}
		}


		function getFullDetailsTableInnerHTML() {
			updateStatusStuff();
			var theHTML = "<TR background-color=lime ><TH>Name</TH><TH>Details</TH><TH>Suggestion</TH><TH colspan=2>Action</TH></TR>";

			for (var p = 0; p < 5; p++) {
				let thisPerson = mysteryProfiles[p].person;
				let thisStatus = mysteryProfiles[p].status;
				let thisTDcolour = (thisStatus == "done" )? "#00ff0080" : "white";
				let disabledTextIfNeeded = (thisStatus == "done" )? " disabled " : "";

				if (thisPerson) {
					theHTML += "<TR style='background-color:" + thisTDcolour + ";'><TD><A target=_blank href='https://wikitree.com/wiki/" + thisPerson.Name + "'>" + thisPerson.RealName + " " + thisPerson.LastNameAtBirth + "</A><BR/>(" + thisPerson.Name + ")</TD>" 
					theHTML += "<TD>b. " + thisPerson.BirthDate + " , "  + thisPerson.BirthLocation + "<BR/>d. " + thisPerson.DeathDate + " , "  + thisPerson.DeathLocation  + "</TD>";
					theHTML += "<TD><A target=_blank href='https://www.wikitree.com/wiki/Space:DBE_" + mysteryProfiles[p].suggestion + "'>" + mysteryProfiles[p].suggestion + "</A> : " + mysteryProfiles[p].description + "</TD>";
					theHTML += "<TD>&nbsp;&nbsp;<input " + disabledTextIfNeeded + "type=submit class='mediumInputButton' onclick='swapOutQuery("+p+");' value='Swap'></td><td>&nbsp;&nbsp;<input " + disabledTextIfNeeded + "type=submit class='mediumInputButton'  onclick='doneNowQuery("+p+");' value='DONE'></TD>";
				}
			}

			return theHTML;
		}

		function checkForALLDONEness() {
			updateStatusStuff();
			let allDone = true;
			for (var p = 0; p < 5 && allDone == true; p++) {
				if (mysteryProfiles[p].status != "done") {
					allDone = false;
				}
			}

			console.log("checkForALLDONEness:",{allDone});

			if (allDone == true) {
				document.getElementById("ReturnBoxDIV").style.display = "none";
				console.log("WE'RE ALL DONE NOW !! WOOHOOO!  LET THE FINAL CONGRATS SCREEN BE SHOWN !!!!");
				
				if (currentUserStatus == "Practice") {
					updateJSONdata("PracticeDone", thisUserID);

					currentUserStatus = "Done";
					functionConfirm("CONGRATULATIONS!\n\nYou have COMPLETED a Practice Box.\n\nBox # " + thisBoxNum + " will be returned to the pool for others to use." + "\nThis practice box will not be added to your stats.",
						function(){console.log("View My Stats "); viewMyStats(); },
						function() {console.log("Get Mystery Box Sticker "); getMySticker(); },
						function() {console.log("Admiring Completed Practice Box");  },
					"View My Stats", "Get Mystery Box Sticker", "Admire Completed Practice Box");

				} else {

					updateJSONdata("AllDone", thisUserID);

					currentUserStatus = "Done";
					functionConfirm("CONGRATULATIONS!\n\nYou have COMPLETED Mystery Box # " + thisBoxNum ,
						function(){console.log("View My Stats "); viewMyStats(); },
						function() {console.log("Get Mystery Box Sticker "); getMySticker(); },
						function() {console.log("Admiring Completed Mystery Box");  },
					"View My Stats", "Get Mystery Box Sticker", "Admire Completed Mystery Box");

				}
			}
		}

		// ================
		// ACTION BUTTON FUNCTIONS - to be used with ACTION buttons on Active Mystery page - SWAP / DONE / RETURN BOX
		// ================

		function swapOutQuery(p=0) {
			updateStatusStuff();
			let thisPerson = mysteryProfiles[p].person;
			functionConfirm("Are you SURE you want to SWAP this profile (" + thisPerson.RealName + " " + thisPerson.LastNameAtBirth  + ") out for another MYSTERY profile ?",
				function(){
					console.log("Sure thing! Have another suggestion # " + mysteryProfiles[p].suggestion); 
					reloadFreshProfileFromWikiTreePlus(p, mysteryProfiles[p].suggestion);
					functionConfirm("Loading a new profile of type # " + mysteryProfiles[p].suggestion, "", "", "");
					updateJSONdata("Sub", thisPerson.Id);
				},
				function() {
					console.log("Swap for a Random # from Bin " + p);  
					reloadFreshProfileFromWikiTreePlus(p, 0 - mysteryProfiles[p].suggestion);  updateFullDetailsTable();
					functionConfirm("Loading a new profile of mystery person # " + (p + 1), "", "", "");
					updateJSONdata("Sub", thisPerson.Id);
				},
				function() {console.log("sorry - didn't mean it - run away!!!!"); updateFullDetailsTable(); },
			"YES - Swap out for another Suggestion of the same type", "YES - Swap out for another RANDOM SUGGESTION", "NO - I've changed my mind - I'll keep this one.");
		}

		function doneNowQuery(p) {
			updateStatusStuff();
			let thisPerson = mysteryProfiles[p].person;
			functionConfirm("Are you DONE with this profile and resolved the Suggestion ?",
				function(){
					console.log("Sure thing! I'm DONE with this profile"); 
					mysteryProfiles[p].status = "done"; 
					updateFullDetailsTable(); 
					updateJSONdata("Done", thisPerson.Id, p, currentUserStatus);
					checkForALLDONEness(); 
				},
				function() {
					console.log("OK - we'll give you another profile to work on " );
					console.log("Swap for a Random # from Bin " + p);  
					reloadFreshProfileFromWikiTreePlus(p);  updateFullDetailsTable();
					functionConfirm("Loading a new profile of mystery person # " + (p + 1), "", "", "");
					updateJSONdata("Sub", thisPerson.Id);
				},
				function() {
					console.log("run away!!!! - clicked DONE by mistake - but - nothing to see here - don't change anything");},
			"YES - All done!", "NOT QUITE - The Suggestion had already been cleaned up by someone else. I need a new Profile to work on.", "NO - I'm not done yet - let me go back and fix it.");
		}

		function returnMysteryBoxQuery() {
			updateStatusStuff();
			let boxType = "MYSTERY";
        	if (currentUserStatus == "Practice") {
        		boxType = "PRACTICE";
        	}
			functionConfirm("Are you sure you want to RETURN this " + boxType + " BOX now, without finishing off these profiles ?", returnMysteryBox ,function() {console.log("NO WAY, JOSE!");},function() {console.log("run away!!!!");},"YES - please RETURN this " + boxType + " BOX", "NO - I'll go back and work some more on the profiles.", "CANCEL - Oops, I clicked this by accident.");
			updateStatusStuff();
		}

		function changeParametersOrLoadBox() {
			console.log("changeParametersOrLoadBox");
			if (currentUserStatus == "Open") {
				document.getElementById('TheStart').style.display = "none";
				document.getElementById('TimeDIV').style.display = "none";
				document.getElementById('PlaceDIV').style.display = "none";
				changeParameters() ;
			} else {
				document.getElementById('MyStickerDIV').style.display = "none";
				document.getElementById('MyStatsDIV').style.display = "none";
				document.getElementById('MysteryStatsDIV').style.display = "none";

				document.getElementById('InnerAcceptedDIV').style.display = "revert";
				if (currentUserStatus == "Practice") {
					document.getElementById('AcceptedDetailsH3').innerText = "Practice Box Details";				
				} else {
					currentUserStatus = "Active";
					document.getElementById('AcceptedDetailsH3').innerText = "Mystery Box Details";
				}

				document.getElementById('ReturnBoxDIV').style.display = "revert";
				updateFullDetails(false);
			}
			updateStatusStuff();
		}

		function updateStatusStuff() {
			console.log("updateStatusStuff:",currentUserStatus);
			document.getElementById('StatusDIV').innerHTML = "Status:" + currentUserStatus;
		}

		function doPracticeBox() {
			updateStatusStuff();
			document.getElementById('TheStart').style.display = "none";
			currentUserStatus = "Practice";
			changeParameters();
		}

		function changeParameters() {
			updateStatusStuff();
			document.getElementById('ResetAndSearch').style.display = "revert";
			document.getElementById('LevelDIV').style.display = "revert";

			document.getElementById('need_login').style.display = "none";
			document.getElementById('ChooseBox').style.display = "none";
			document.getElementById('AcceptedDIV').style.display = "none";
			document.getElementById('ReturnBoxDIV').style.display = "none";

			document.getElementById('ChooseBoxButton').style.display = "revert";
			document.getElementById('GoToBoxButton').style.display = "none";
			document.getElementById('AcceptButtonSPAN').style.display = "none";
			document.getElementById('AcceptButton').style.display = "none";
			document.getElementById('theProfilesChosen').style.display = "none";
			document.getElementById('StartingNoteSPAN').innerText = "";
			// showNextDIV('TheStart', 'LevelDIV');
			document.getElementById('MyStatsDIV').style.display = "none";
			document.getElementById('MyStickerDIV').style.display = "none";
			document.getElementById('InnerAcceptedDIV').style.display = "revert";

		}

		// CODE generated by https://www.codeconvert.ai/javascript-code-generator 
		// using this prompt:
		// I would like a Javascript function that will generate the HTML for a SVG graphic that displays the current month as a calendar.  Each cell in the calendar should be 80 pixels wide.  The month and year should be displayed at the top of the calendar.	
		function generateCalendarSVG( boxesInfoArray = [], dMonth = 0, calType = "MyStats") {
		    let date = new Date();
		    let month = date.getUTCMonth() + dMonth;
		    let year = date.getFullYear();
		    if (month < 0) {
		    	year += Math.floor(month / 12);
		    	while (month < 0) {
		    		month += 12;
		    	}

		    	date = new Date(year, month + 1, 0);
		    } else if (dMonth != 0) {
		    	date = new Date(year, month + 1, 0);
		    }

		    const daysInMonth = new Date(year, month + 1, 0).getUTCDate();
		    const firstDay = new Date(year, month, 1).getDay();
		    const cellSize = 80;
		    const svgWidth = cellSize * 7;
		    const svgHeight = cellSize * (Math.ceil((daysInMonth + firstDay) / 7) + 1);
		    
		    let svgContent = `<svg width="${svgWidth}" height="${svgHeight}" xmlns="http://www.w3.org/2000/svg">`;
		    svgContent += `<text x="${svgWidth / 2}" y="40" font-size="24" text-anchor="middle">${date.toLocaleString('default', { month: 'long' })} ${year}</text>`;
		    
		    for (let i = 0; i < daysInMonth; i++) {
		        const day = i + 1;
		        const x = ((i + firstDay) % 7) * cellSize;
		        const y = Math.floor((i + firstDay) / 7) * cellSize + 60;
		        svgContent += `<rect x="${x}" y="${y}" width="${cellSize}" height="${cellSize}" fill="lightgray" stroke="black"/>`;
		        // svgContent += `<text x="${x + cellSize / 2}" y="${y + cellSize / 2}" font-size="20" text-anchor="middle" alignment-baseline="middle">${day}</text>`; // ORIGINAL
		        svgContent += `<text x="${x + cellSize / 2}" y="${y + cellSize / 4}" font-size="18" text-anchor="middle" alignment-baseline="middle">${day}</text>`;
		    }

		    // NEW CODE to add Mystery Boxes
		    for (let b = 0; b < boxesInfoArray.length; b++) {
		    	  let i = boxesInfoArray[b].dayNum;
		        const day = i + 1;
		        const x = ((i + firstDay) % 7) * cellSize;
		        const y = Math.floor((i + firstDay) / 7) * cellSize + 60;
		        if (calType == "MyStats"){
	  		        svgContent += getSingleBoxSVG( boxesInfoArray[b].boxNum , x + 10 , y + 25 , 40, clickForArchiveBoxDetailsFunction, "MyStats");
	  		     } else {
	  		     		svgContent += getNumBoxesSVG( boxesInfoArray[b].boxNum , x + 10 , y + 25 , 40, "toggleDumpDatesDIV", boxesInfoArray[b].thisDoneDateDIV);
	  		     }
		    }
		        
		    
		    svgContent += `</svg>`;
		    return svgContent;
		}

		var mostRecentBox = null;

		function showChallStatsSection ( divPartial = "Overall") {
			let allSects = ['Overall', 'Active', 'Available', 'Solved'];
			for (var i = 0; i < allSects.length; i++) {
				document.getElementById('ChallengeStats' + allSects[i] + "DIV").style.display = "none";
				document.getElementById('showSectionBtn4' + allSects[i]).classList.remove("activeSection");
			}
			document.getElementById('ChallengeStats' + divPartial + "DIV").style.display = "revert";
			document.getElementById('showSectionBtn4' + divPartial ).classList.add("activeSection");
		}
			
		function showChallStatsSectionSolved ( divPartial = "Overall") {
			let allSects = ['All', 'Parameters', 'Place', 'SuperGrid','Date'];
			for (var i = 0; i < allSects.length; i++) {
				document.getElementById('SolvedChallengeStats' + allSects[i] + "DIV").style.display = "none";
				document.getElementById('SolvedshowSectionBtn4' + allSects[i]).classList.remove("activeSection");
			}
			document.getElementById('SolvedChallengeStats' + divPartial + "DIV").style.display = "revert";
			document.getElementById('SolvedshowSectionBtn4' + divPartial ).classList.add("activeSection");
		}
			
		function showChallStatsSectionActive ( divPartial = "Overall") {
			let allSects = ['All', 'Parameters', 'Place', 'ProfilesFixed'];
			for (var i = 0; i < allSects.length; i++) {
				document.getElementById('ActiveChallengeStats' + allSects[i] + "DIV").style.display = "none";
				document.getElementById('ActiveshowSectionBtn4' + allSects[i]).classList.remove("activeSection");
			}
			document.getElementById('ActiveChallengeStats' + divPartial + "DIV").style.display = "revert";
			document.getElementById('ActiveshowSectionBtn4' + divPartial ).classList.add("activeSection");
		}
			
		var ALLdayBoxComboForCalendar = [];
	
		function updateDayBoxComboForSolvedDates() {
			let thisDate = new Date();
			let yearToShow = thisDate.getFullYear() ;
			let monthToShow = thisDate.getUTCMonth() + currentDMonth;
			if (monthToShow < 0) {
		   	yearToShow += Math.floor(monthToShow/12);
		   	while (monthToShow < 0) {
		   		monthToShow += 12;
		   	}
		   }

			let thisPrefix = yearToShow + "-" + pad2digits(monthToShow + 1) ;
			console.log({thisPrefix});
			let thisBoxCombo = [];
			for (var i = 0; i < ALLdayBoxComboForCalendar.length; i++) {
				if (ALLdayBoxComboForCalendar[i].thisDoneDate.indexOf(thisPrefix) > -1) {
					thisBoxCombo.push(ALLdayBoxComboForCalendar[i]);
				}
			}
			return thisBoxCombo;
		}

		function adoptThisBox() {
			num = document.getElementById('showBoxDetailsBoxNumSPAN').innerText;
			console.log("You have just agreed to Adopt Box # ", num);

			document.getElementById("MysteryStatsDIV").style.display = "none";
			document.getElementById("InnerAcceptedDIV").style.display = "block";

			adoptBoxNowData(thisUserID, num).then(dataAdoptedBox => {
				if (dataAdoptedBox) {
					checkForMyMysteryBoxThenStartAfterSQL(dataAdoptedBox);
				}
			});

		}

		let dayBoxComboForCalendar = [];
		let currentDMonth = 0;

		// ========= Adjust Calendar for MY STATS  ========
		function showPrevMonth() {
			currentDMonth -= 1;
			updateDayBoxComboForCalendar();
			document.getElementById("MyStatsCalendarDIV").innerHTML = generateCalendarSVG(dayBoxComboForCalendar, currentDMonth);
		}

		function showThisMonth() {
			currentDMonth = 0;
			updateDayBoxComboForCalendar();
			document.getElementById("MyStatsCalendarDIV").innerHTML = generateCalendarSVG(dayBoxComboForCalendar, currentDMonth);
		}
			
		function showNextMonth() {
			currentDMonth += 1;
			updateDayBoxComboForCalendar();
			document.getElementById("MyStatsCalendarDIV").innerHTML = generateCalendarSVG(dayBoxComboForCalendar, currentDMonth);
		}
			
		// ========= Adjust Calendar for CHALLENGE STATS --> BY DATE SOLVED ========			
		function showPrevMonthSolved() {
			currentDMonth -= 1;
			dayBoxComboForCalendar = updateDayBoxComboForSolvedDates();
			document.getElementById("AllStatsCalendarDIV").innerHTML = generateCalendarSVG(dayBoxComboForCalendar, currentDMonth, "SolvedStats");
		}

		function showThisMonthSolved() {
			currentDMonth = 0;
			dayBoxComboForCalendar = updateDayBoxComboForSolvedDates();
			document.getElementById("AllStatsCalendarDIV").innerHTML = generateCalendarSVG(dayBoxComboForCalendar, currentDMonth, "SolvedStats");
		}
			
		function showNextMonthSolved() {
			currentDMonth += 1;
			dayBoxComboForCalendar = updateDayBoxComboForSolvedDates();
			document.getElementById("AllStatsCalendarDIV").innerHTML = generateCalendarSVG(dayBoxComboForCalendar, currentDMonth, "SolvedStats");
		}
			
		async function getMySticker() {
			console.log("INSIDE the getMySticker function now.");

			document.getElementById('MyStatsDIV').style.display = "none";
			document.getElementById('MyStickerDIV').style.display = "revert";
			document.getElementById('InnerAcceptedDIV').style.display = "none";
			document.getElementById('MysteryStatsDIV').style.display = "none";

			let numOfBoxesSolved = "0 boxes";

			

			let stickerData =  await retrieveFromSQL("GetSticker", ["ID="+thisUserID] );
			console.log("FOUND IT:", {stickerData});

			numOfBoxesSolved = stickerData.numSolved  + " ";
			if (stickerData.numSolved == 1) {
				numOfBoxesSolved += "box";
			} else {
				numOfBoxesSolved += "boxes";
			}
		

			let mostRecentBoxDetails = stickerData.recentDoneBox.bdLevel + (stickerData.recentDoneBox.bdTime > "" ? ", " + stickerData.recentDoneBox.bdTime + "th century":"") + (stickerData.recentDoneBox.bdPlace > "" ? ", " + stickerData.recentDoneBox.bdPlace :"") ;
			let mostRecentDate = new Date (stickerData.recentDoneBox.bdTimeEnd);
			let mostRecentDone = mostRecentDate.getUTCDate() + " " + mostRecentDate.toLocaleString('default', { month: 'long' }) + " " + mostRecentDate.getUTCFullYear();

			//+ "<BR> " + "solved " + mostRecentBox.timeEnd.replace("(","<BR/>(") + "</TD></TR>";

			let thisBoxSticker = "{{Community Event |image=WikiTree_Event_Images-44.png |text=completed Box '''# " + stickerData.recentDoneBox.bdBoxNum + "''' of " + mostRecentBoxDetails + " profiles on " + mostRecentDone + " |event=[[Space:Mystery Box Challenge|Mystery Box Challenge]] }}";
			let thisBoxStickerIMGtable = "<table border=1><TR><TD><img src='https://www.wikitree.com/photo.php/thumb/d/de/WikiTree_Event_Images-44.png/50px-WikiTree_Event_Images-44.png'></TD><TD width=200px>" + thisWikiTreerChallenger.RealName + " " + thisWikiTreerChallenger.LastNameAtBirth + " completed Box <B># " + stickerData.recentDoneBox.bdBoxNum + "</B> of " + mostRecentBoxDetails + " profiles on " + mostRecentDone + " in the <B>Mystery Box Challenge</B></TD></TR></TABLE>";

			document.getElementById('StickerDetails4Box').innerHTML = "<BR/><B>Copy this Sticker text:</B><BR/><TABLE border=0><TR><TD><textarea style='padding:2px;' rows=8 cols=30>" + thisBoxSticker + "</textarea></TD><TD valign=top>" + thisBoxStickerIMGtable + "</TD></TR></TABLE>";

			thisBoxSticker = "{{Community Event |image=WikiTree_Event_Images-44.png |text=has completed '''" + numOfBoxesSolved + "''' |event=[[Space:Mystery Box Challenge|Mystery Box Challenge]] }}";
			thisBoxStickerIMGtable = "<table border=1><TR><TD><img src='https://www.wikitree.com/photo.php/thumb/d/de/WikiTree_Event_Images-44.png/50px-WikiTree_Event_Images-44.png'></TD><TD width=200px>" + thisWikiTreerChallenger.RealName + " " + thisWikiTreerChallenger.LastNameAtBirth + " has completed <B>" + numOfBoxesSolved + "</B> in the <B>Mystery Box Challenge</B></TD></TR></TABLE>";

			document.getElementById('StickerDetails4Overall').innerHTML = "<BR/><B>Copy this Sticker text:</B><BR/><TABLE border=0><TR><TD><textarea style='padding:2px;' rows=8 cols=30>" + thisBoxSticker + "</textarea></TD><TD valign=top>" + thisBoxStickerIMGtable + "</TD></TR></TABLE>";

			
		}
		// ================
		// ASYNC FUNCTIONS - to be used when retrieving info from files ... because then we can AWAIT the results, since they are not instantaneous
		// ================


		async function updateJSONdata(action, ID, otherInfo, extraInfo = "") {

			let url = `https://apps.wikitree.com/apps/clarke11007/apps/mysteryBoxUpdateData.php?action=` + action + `&ID=` + ID;
			let url2 = '';

			// ===============================
			//
			// UPDATE THE JSON FILE
			//
			// ===============================

			if (action == "Accept") {
				url += "&box=" + otherInfo;
				url += "&status=" + currentUserStatus;

				theseProfilesString = "";
				for (var p = 0 ;   p < 5; p++) {
					theseProfilesString += mysteryProfiles[p].person.Id + "-" + mysteryProfiles[p].suggestion + "|";
				}
				thisAcceptedTime = Date();
				console.log({thisAcceptedTime});

				url += "&profiles=" + theseProfilesString;
				url += "&timeNow=" + thisAcceptedTime;
				url += "&params=" + thisBoxLevel + "|" + thisBoxTime + "|" + thisBoxPlace;

			} else if (action == "AllDone") {
				thisDoneTime = Date();
				url += "&timeNow=" + thisDoneTime;

			} else if ( action == "PracticeDone") {
				thisDoneTime = Date();
				url += "&timeNow=" + thisDoneTime;
				url += "&box=" + thisBoxNum;

			} else if (action == "Return") {
				url += "&box=" + otherInfo;

			} else if (action == "Done") {
				url += "&num=" + otherInfo;
				url += "&user=" + thisUserID;

			} else if (action == "SwapIn") {

				url += "&num=" + otherInfo['p'];
				url += "&sugg=" + otherInfo['sugg'];
				url += "&user=" + thisUserID;
			}
			

			console.log(url);
			// this.cancelFetchController = new AbortController();
			// const response = await fetch(url, { signal: this.cancelFetchController.signal });

			// ===============================
			//
			// UPDATE THE UPTERGROVE DATABASE
			//
			// ===============================

			let doURL2toUpdateUptergroveDB = false; 

			if (action == "AddBox") {
				let thisAddTime = new Date();
				let thisUTCoffset = thisAddTime.getHours() - thisAddTime.getUTCHours();
				if (thisUTCoffset > 14) {
					thisUTCoffset = thisUTCoffset - 24;
				}
				let thisDateNice = thisAddTime.getUTCFullYear() + "-" + pad2digits(thisAddTime.getUTCMonth() + 1) + "-" + pad2digits(thisAddTime.getUTCDate() ) + " " + pad2digits(thisAddTime.getUTCHours()) + "-" + pad2digits(thisAddTime.getUTCMinutes())  + "-" + pad2digits(thisAddTime.getUTCSeconds() );
				url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=AddBox&ID=` + thisUserID + `&box=` + thisBoxNum + `&timeUpdate=` + thisDateNice + `&timeOffset=` + thisUTCoffset;

				doURL2toUpdateUptergroveDB = true;

			} else  if (action == "Accept") {

				let thisAddTime = new Date();
				let thisAddTimeString = thisAddTime.toString();
				let thisUTCoffset = thisAddTime.getHours() - thisAddTime.getUTCHours();
				if (thisUTCoffset > 14) {
					thisUTCoffset = thisUTCoffset - 24;
				}

				let calculatedUTCoffset =  thisUTCoffset;
				if (thisAddTimeString.indexOf("GMT") > -1) {
					calculatedUTCoffset = thisAddTimeString.substring( thisAddTimeString.indexOf("GMT") + 3 , thisAddTimeString.indexOf("(") - 1) / 100;
				}
				let calculatedUTCtimezone = thisAddTimeString.substr(thisAddTimeString.indexOf("("));

				console.log({calculatedUTCoffset}, {calculatedUTCtimezone});

				let thisDateNice = thisAddTime.getUTCFullYear() + "-" + pad2digits(thisAddTime.getUTCMonth() + 1) + "-" + pad2digits(thisAddTime.getUTCDate() ) + " " + pad2digits(thisAddTime.getUTCHours()) + "-" + pad2digits(thisAddTime.getUTCMinutes())  + "-" + pad2digits(thisAddTime.getUTCSeconds() );

				
				if (currentUserStatus == "Done") {
					currentUserStatus = "Practice";
				} else {
					currentUserStatus = "Active";
				}

				theseProfilesString = "";
				for (var p = 0 ;   p < 5; p++) {
					theseProfilesString += mysteryProfiles[p].person.Id + "-" + mysteryProfiles[p].suggestion + "|";
				}

				url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=AcceptBox&ID=` + thisUserID + `&box=` + thisBoxNum + `&timeUpdate=` + thisDateNice + `&timeOffset=` + calculatedUTCoffset + `&timeZone=` + calculatedUTCtimezone + `&status=` + currentUserStatus + "&params=" + thisBoxLevel + "|" + thisBoxTime + "|" + thisBoxPlace + "&profiles=" + theseProfilesString;

				console.log("Accepted URL2 to use: ", url2);
				doURL2toUpdateUptergroveDB = true;
				
			} else  if (action == "AdoptBox" ) {
					//  ID = ID of new profile to be swapped into the Mystery Box in position # num (p) with suggestion # sugg
					// url += "&num=" + otherInfo['p'];
					// url += "&sugg=" + otherInfo['sugg'];
					// url += "&user=" + thisUserID;

					let thisAddTime = new Date();
					let thisAddTimeString = thisAddTime.toString();
					let thisUTCoffset = thisAddTime.getHours() - thisAddTime.getUTCHours();
					if (thisUTCoffset > 14) {
						thisUTCoffset = thisUTCoffset - 24;
					}

					let calculatedUTCoffset =  thisUTCoffset;
					if (thisAddTimeString.indexOf("GMT") > -1) {
						calculatedUTCoffset = thisAddTimeString.substring( thisAddTimeString.indexOf("GMT") + 3 , thisAddTimeString.indexOf("(") - 1) / 100;
					}
					let calculatedUTCtimezone = thisAddTimeString.substr(thisAddTimeString.indexOf("("));

					console.log({calculatedUTCoffset}, {calculatedUTCtimezone});

					let thisDateNice = thisAddTime.getUTCFullYear() + "-" + pad2digits(thisAddTime.getUTCMonth() + 1) + "-" + pad2digits(thisAddTime.getUTCDate() ) + " " + pad2digits(thisAddTime.getUTCHours()) + "-" + pad2digits(thisAddTime.getUTCMinutes())  + "-" + pad2digits(thisAddTime.getUTCSeconds() );


					url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=AdoptBox&ID=` + thisUserID + `&box=` + otherInfo  + `&timeUpdate=` + thisDateNice + `&timeOffset=` + calculatedUTCoffset + `&timeZone=` + calculatedUTCtimezone  ;
					doURL2toUpdateUptergroveDB = true;

			} else  if (action == "SwapIn" ) {
					//  ID = ID of new profile to be swapped into the Mystery Box in position # num (p) with suggestion # sugg
					// url += "&num=" + otherInfo['p'];
					// url += "&sugg=" + otherInfo['sugg'];
					// url += "&user=" + thisUserID;

					url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=SwapProfile&ID=` + thisUserID + `&box=` + thisBoxNum + `&status=` + currentUserStatus + "&params=" + ID + "|" + otherInfo['sugg'] + "|" + (1.0 + 1.0 * otherInfo['p']) ;
					doURL2toUpdateUptergroveDB = true;

			} else if (action == "Return") {
				// url += "&box=" + otherInfo;
					console.log({extraInfo});
					let thisBoxNum = otherInfo;
					let theStatusWeUse = currentUserStatus;
					if (extraInfo == "Done") {
						theStatusWeUse = "Active";
					} else if (extraInfo == "Open") {
						theStatusWeUse = "Practice";
					}

					url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=ReturnBox&ID=` + thisUserID + `&box=` + thisBoxNum + `&status=` + theStatusWeUse ;
					doURL2toUpdateUptergroveDB = true;

			} else  if (action == "Done" ) {
					//  ID = ID of new profile to be swapped into the Mystery Box in position # num (p) with suggestion # sugg
					// url += "&num=" + otherInfo['p'];
					// url += "&user=" + thisUserID;
					console.log({extraInfo});
					let theStatusWeUse = currentUserStatus;
					if (extraInfo == "Done") {
						theStatusWeUse = "Active";
					} else if (extraInfo == "Open") {
						theStatusWeUse = "Practice";
					}

					url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=DoneProfile&ID=` + thisUserID + `&box=` + thisBoxNum + `&status=` + theStatusWeUse + "&params=" + ID + "|" + (1.0 + 1.0 * otherInfo) ;
					doURL2toUpdateUptergroveDB = true;

			} else  if (action == "AllDone" || action == "PracticeDone") {

				let thisDoneTime = new Date();
				let thisUTCoffset = thisDoneTime.getHours() - thisDoneTime.getUTCHours();
				if (thisUTCoffset > 14) {
					thisUTCoffset = thisUTCoffset - 24;
				}
				let thisDateNice = thisDoneTime.getUTCFullYear() + "-" + pad2digits(thisDoneTime.getUTCMonth() + 1) + "-" + pad2digits(thisDoneTime.getUTCDate() ) + " " + pad2digits(thisDoneTime.getUTCHours()) + "-" + pad2digits(thisDoneTime.getUTCMinutes())  + "-" + pad2digits(thisDoneTime.getUTCSeconds() );

				theseProfilesString = "";
				for (var p = 0 ;   p < 5; p++) {
					theseProfilesString += mysteryProfiles[p].person.Id + "-" + mysteryProfiles[p].suggestion + "|";
				}

				let thisPreDoneStatus = "Active";
				if (action == "PracticeDone") {
					thisPreDoneStatus = "Practice";
				}
				url2 = `https://uptergrove.com/mysteryBox/mysteryBoxUpdater.php?action=DoneBox&ID=` + thisUserID + `&box=` + thisBoxNum + `&timeUpdate=` + thisDateNice + `&timeOffset=` + thisUTCoffset + `&status=` + thisPreDoneStatus;

				doURL2toUpdateUptergroveDB = true;

		   }

		  console.log({doURL2toUpdateUptergroveDB}, {url2});

	     if (doURL2toUpdateUptergroveDB == true ) {
		     	console.log("NEW URL 2 - and subsequent FETCH: ", url2);

				const myInit = {
					  method: 'POST',
					  mode: 'cors',
					  cache: 'default'
					};
				const response = await fetch(url2, myInit);

				let data;

				

		        try {
		            data = await response.text();
		            console.log("RESPONSE:", data);


		             
		         } catch (error) {
		            if (error.name !== "AbortError") {
		                console.error("Error when fetching Uptergrove:", error);
		            } else {
		                // return [true, null];
		            }
		        }
	     }

		}


		async function loadMysteryBoxJSON(doCheck = true, jump2PickBox = false) {
			// return;
			let url = `https://apps.wikitree.com/apps/clarke11007/apps/mysteryBoxData.json`;
        console.log(url);
        this.cancelFetchController = new AbortController();
        const response = await fetch(url, { signal: this.cancelFetchController.signal });
        let data;
        try {
            data = await response.json();
            console.log("JASON:", data);
            // currentMysteryBoxData = data;
            // currentMysteryBoxData = {info:null};

            if (doCheck == true){
               checkForMyMysteryBoxThenStart();
            } else if (jump2PickBox == true) {
            	showNextDIV('PlaceDIV','ChooseBox');
					loadMysteryBoxes();
            }

         } catch (error) {
            if (error.name !== "AbortError") {
                console.error("Error when fetching TAKEN list:", error);
            } else {
                return [true, null];
            }
        }

		}

		// Functions moved to apps/js/api/plusFunctions.js
		// Imported via main.js module and available globally


		function updateLoadingProfilesStatusMessage(p) {
			console.log("updateLoadingProfilesStatusMessage",p);
			let msgP = document.getElementById("StatusUpdatesWhileChoosingProfiles");

			let theMsg = "<I>Loading profiles now ... please be patient .... </I><BR><B>Currently searching for Profile # " + (p + 1) + "</B>";
			if (p == 0) {
				theMsg += "<BR><BR><I>Profiles are picked based on your preferred time frame and locations, where possible.<BR>If none can find that match both (or either) condition, then a random profile is chosen.</I>";
			} else if (p == 1) {
				theMsg += "<BR><BR><I>The type of Suggestions that are used in searching for Profiles is based on Difficulty Level chosen.<BR>These are the same categories used by the <A target=_blank href='https://www.wikitree.com/wiki/Project:Data_Doctors'>Data Doctors</A> project, generated by WikiTree Plus.</I>";
			} else if (p > 1 && p < 5) {
				theMsg += "<BR><BR><I>e.g.,";
				let listOfSuggIndices = [];
				while (listOfSuggIndices.length < 3) {
					let thisIndex = Math.floor( Math.random() * SuggestionsListObject[thisBoxLevel][p].length );
					if (listOfSuggIndices.indexOf(thisIndex) == -1) {
						listOfSuggIndices.push(thisIndex);
						theMsg += "<BR/>Suggestion # " + SuggestionsListObject[thisBoxLevel][p][thisIndex] + " : " + TheSuggestionsList[ SuggestionsListObject[thisBoxLevel][p][thisIndex] ];
					}
				}


			}

			if (p >= 5) {
				theMsg = "";
			}

			if (msgP){
				msgP.innerHTML = theMsg;
			}
		}

			

		function setupQueryStarterArray() {

			queryStarter = "";
 			queryStarterArray = [];
 			let notCondition = "not IsLiving not PPP";

 			let notLocationExceptions = {"Wales":"not 'New South Wales'"};
 			if (notLocationExceptions[thisBoxPlace] && notLocationExceptions[thisBoxPlace] > "") {
 				notCondition = notLocationExceptions[thisBoxPlace] ;
 			}
 			// let mustBeDeadBefore2000proviso = ' sql="([Default].[Death Date].AsNumber < 19800000)" ' + notCondition;
 			let mustBeDeadBefore2000proviso = ' not 1980s ' + notCondition;  // suggested by Ales as a better way to handle death date query

			if (thisBoxTime > 0 && thisBoxPlace > "") {
				
				
				queryStarterArray.push('open ' + thisPlaceType + '="' + thisBoxPlace +  '" '  + thisBoxTime +  'cen' + mustBeDeadBefore2000proviso );
			}
			if (thisBoxPlace > "") {
				queryStarterArray.push('open ' + thisPlaceType + '="' + thisBoxPlace +  '" '  + mustBeDeadBefore2000proviso);
			}
			if (thisBoxTime > 0) {
				queryStarterArray.push('open ' + thisBoxTime + 'cen' + mustBeDeadBefore2000proviso);
			}
				
			queryStarterArray.push( "open" + mustBeDeadBefore2000proviso);

			console.log({queryStarterArray});
			queryStarter = queryStarterArray[0];
		}

		

		async function updateTakenIDs() {
			currentMysteryBoxData.takenIDs = await retrieveFromSQL("ListTakenIDs" );
		}


		async function chooseProfiles(numChosen) {
			 // let query = `${locationBit}LastNameatBirth%3D"${surname}"+OR+${locationBit}CurrentLastName%3D"${surname}"+OR+${locationBit}LastNameOther%3D"${surname}"`;
        
            // Construct the query part for each variant
            // query = surnameVariants
            //     .map(
            //         (variant) =>
            //             `${locationBit}LastNameatBirth%3D"${variant}"+OR+${locationBit}CurrentLastName%3D"${variant}"+OR+${locationBit}LastNameOther%3D"${variant}"`
            //     )
            //     .join("+OR+");
        	let boxType = "Mystery";
        	if (currentUserStatus == "Practice") {
        		boxType = "Practice";
        	}

        	await updateTakenIDs();

			theProfilesChosenHTML = "<P id=StatusUpdatesWhileChoosingProfiles><I>Loading profiles now ... please be patient .... </I><BR><B>Currently searching for Profile # 1</B> <BR><BR><I>Profiles are picked based on your preferred time frame and locations, where possible.<BR>If none can find that match both (or either) condition, then a random profile is chosen.</I></P><P><B>Here are the Profiles chosen for your " + boxType + " Box:</B><br/><br/>";
			// loadTakenProfileIDs();
			
			numProfilesChosen = 0;

			setupQueryStarterArray();

			if (!SuggestionsListObject) {

				SuggestionsListObject = { 
				"easy":[ [469,470,821,822,823,824,863,943,944, 456, 462] , [403,452,454,721,722],[741,743,751,753] , [458,464,608,610,611],[637,638,640,641,668,671]  ], 
				"int": [ [468,861,862,864,866,961,962,965,966,967,971,853], [202,210,310,404,416,461,462] ,  [501,502,503,504,505,506,507,508,509,510,723,724,727,733,734,737,744,747,754,761,763,777,778,781,784,787,788], [457,463,474,601,602,604,605,631,632,634,635,661,662,663,664,676] , [571,572,574,576,577,579,581,586,587] ], 
				"adv": [ [831,835], [202,210,310,205] ,  [404,416,501,502,503,504,505,506,507,508,509,510,461], [723,724,727,733,734,737,744,747,754,761,763,777,778,781,784,787,788] , [203,205,303,305,407,408,409] ] };
			}



        // let query = "19cen+Clarke&MaxErrors=100&ErrorID=853"
        // let query2 = "19cen+Ontario&MaxErrors=100&ErrorID=571"


	     console.log("THE suggestions: ", TheSuggestionsList );

	     document.getElementById("theProfilesChosen").innerHTML = theProfilesChosenHTML;
	     updateLoadingProfilesStatusMessage(0);

        for (var p = 0; p < 5; p++) {
        		updateLoadingProfilesStatusMessage(0);
        	
        		let randSuggNum = Math.floor( SuggestionsListObject[thisBoxLevel][p].length * Math.random() );

        		loadProfileFromWikiTreePlus(p, randSuggNum, numChosen);
        }


        // updateLoadingProfilesStatusMessage(5);

        document.getElementById("theProfilesChosen").innerHTML = theProfilesChosenHTML;
        console.log({mysteryProfiles});

		}

		
		var theIDswappedIn = null;
		var theActualSuggestionSwapped = -1;


		// IF randSugNum < 0 - THEN - use ALL SUGGESTIONS - not just from [p]
      async function reloadFreshProfileFromWikiTreePlus(p, randSuggNum = -1) {
      	setupQueryStarterArray();
      	ignoreMeListOfProfileIDs.push( mysteryProfiles[p].person['Id']);

			// MAKE SURE that we have a valid takenIDs array to compare against, so we're not revisiting the same profiles over and over again			
      	if (currentMysteryBoxData.takenIDs.length == 0) {
      		await updateTakenIDs();
      	}

      	if (randSuggNum < -1) {
      		// send a -ve Suggestion Number into the loadProfileFromWikiTreePlus function to indicate any suggestion EXCEPT this one!
      		let randP = Math.floor(4.99 * Math.random() );
      		let oldRandSugg = -1 * randSuggNum;
      		while (randSuggNum == -1 * oldRandSugg || randSuggNum == oldRandSugg){
	      		randSuggNum =  Math.floor( SuggestionsListObject[thisBoxLevel][randP].length * Math.random() );
	      	}
      	}

      	if (randSuggNum > -1) {
      		randSuggNum = SuggestionsListObject[thisBoxLevel][p].indexOf(randSuggNum);
      	}
      	if (randSuggNum == -1) {
      		randSuggNum = Math.floor( SuggestionsListObject[thisBoxLevel][p].length * Math.random() );
      	} 

        	let TMPtheIDswappedIn = await loadProfileFromWikiTreePlus(p, randSuggNum, thisBoxNum);
        	console.log({theIDswappedIn});
        	updateJSONdata("SwapIn", theIDswappedIn,  { 'p' : p , 'sugg' : theActualSuggestionSwapped /*SuggestionsListObject[thisBoxLevel][p][randSuggNum]*/ } ) ;
      }


		function generateRandomList(n) {
		    const array = Array.from({ length: n }, (_, i) => i);
		    for (let i = array.length - 1; i > 0; i--) {
		        const j = Math.floor(Math.random() * (i + 1));
		        [array[i], array[j]] = [array[j], array[i]];
		    }
		    return array;
		}  

		// Function moved to apps/js/api/plusFunctions.js
		// Imported via main.js module and available globally

		async function loadProfileForWikiTreerChallenger() {
			console.log("LOAD PROFILES FOR WikiTreerChallenger");
			
			// thisUserID (all caps ID) = variable used in mysterBox app
			// thisUserId (cap I lowercase d) = variable used in WikiTreeAuthGPC.js (and others that now use this type of login routine)
			if (thisUserID == 0 || thisUserID == -1 || thisUserID == "") {
				if (thisUserId > 0) {
					thisUserID = thisUserId;
				}
			}

			url = API_URL + "?appId=mBox&action=getPeople&resolveRedirect=1&fields=Id,Name,RealName,LastNameAtBirth&keys=" + thisUserID;
			// wikitree.session.user_id;
	        console.log(url);
	        console.log({thisUserId});
	        this.cancelFetchController = new AbortController();
	        const theResponse = await fetch(url, { signal: this.cancelFetchController.signal });
	        let theData4WikiTreerChallenger;
	        try {
	            theData4WikiTreerChallenger = await theResponse.json();
	            console.log("GOT THE DATA HERE FOR THE PROFILES IN PROGRESS !!!!");
	            console.log(theData4WikiTreerChallenger);

	            thisWikiTreerChallenger = theData4WikiTreerChallenger[0].people[thisUserID];
	            document.getElementById("welcomeFullName").innerText = thisWikiTreerChallenger.RealName + " " + thisWikiTreerChallenger.LastNameAtBirth;
	            document.getElementById("welcomeWikiTreeID").innerText = thisWikiTreerChallenger.Name;

	        } catch (error) {
	            if (error.name !== "AbortError") {
	               console.error("Error when fetching WikiTreerChallenger:", error);
	  	            document.getElementById("welcomeFullName").innerText = "You !";
		            document.getElementById("welcomeWikiTreeID").innerText = thisUserName;

	            } else {
	                return [true, null];
	            }
	        }  

		}



		

		</script>
		<a name="top" ></a>
		<DIV align=center>
			<H1>Mystery Box Challenge</H1>
	
	<div id="confirm"><span id=message class=message></span><BR><BR><button id=yes class=yes>YES</button><BR><button id=no class=no>NO</button><BR><button id=cancel class=cancel>Cancel</button><BR></div>
	<div id="ResetAndSearch" align=center style="display:none;">		
		<P>This app will choose a Mystery Box of profiles to work on for the challenge, and also help you manage them.<br/>
		You can include this list in your WikiTree profile and share it with fellow family researchers.</P>
		<p>Check out the Free Space Page link at the <a href=#bot>bottom</a> of this page for more details.</p>

		<!-- <TABLE border=2><TR><TD><SPAN > <H4 align=center style='background-color: yellow;'>UPDATE</H4> </SPAN><BR>
Apparently the Mystery Box Challenge was so popular, <BR>you crashed the data file sometime in the middle of the first night of the challenge<BR> - AND - again another night! and again ... Yikes!<BR> - so - <BR>Each time that happens, I have to restore the DB from a backup, and there will be some temporary data loss.
</BR>(I AM working on a more permanent solution with a more robust database ... stay tuned ...)</BR></BR>Please contact Greg if you completed a Box and do not have credit for it in your stats.</BR></BR></TD></TR></TABLE>
 -->
		

				<div name="TheStart"  id="TheStart" style="background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
					<input type=hidden name=id id=Profile value="">
					<!-- <P>Enter the <A target=_blank href=https://www.wikitree.com/wiki/Help:WikiTree_ID>WikiTree ID</A> for the person entering the Challenge:<BR/>
					
					<P><B></B><br/> <BR><I>e.g. Marcoux-336<BR/></I></P> -->
					<H4>Welcome <span id=welcomeFullName>Your Name</span> !</H4>
					WikiTree ID: <B><span id=welcomeWikiTreeID>Lastname-1234</span></B><BR/><BR/>
							
					
					<HR>
					<input id=ChooseBoxButton style="display:none;"  class="button" type="submit" value="Choose Mystery Box" onclick="showNextDIV('TheStart','LevelDIV');"><BR>&nbsp;
					<span id=StartingNoteSPAN></span>
					<input id=GoToBoxButton style="display:none;" class="button" type="submit" value="Go to Mystery Box" onclick="jumpToAcceptedBox();"><BR>&nbsp;
				</div>


				<div id="LevelDIV" style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
					<P>What <B>LEVEL of Mystery</B> do you wish to solve ?<BR/>
					
					<P>
						<BR/> 
						<input class="button" type="submit" value="Easy Peasy" onclick="chooseLevel('easy');"><BR>&nbsp;
						<BR/><I>Simple Junk, Missing &lt;ref&gt;, relations, names, gender, locations<BR/></I>
					
						<BR/> 
						<input class="button" type="submit" value="Intermediate Sleuthing" onclick="chooseLevel('int');"><BR>&nbsp;
						<BR/><I>A little bit of everything<BR/></I>
					
						<BR/> 
						<input class="button" type="submit" value="Advanced Sherlock Enigmas" onclick="chooseLevel('adv');"><BR>&nbsp;
						<BR/><I>Tricky suggestions<BR/></I>
							
				</div>

 
				<div id="TimeDIV" style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
					<P>What <B>CENTURY</B>  do you wish to visit on your Mystery Challenge?<BR/>
					
					<P>

						<!-- <BR/> 
						<input class="button" type="submit" value="17th Century (1600-1699)" onclick="chooseTime(17);"><BR>&nbsp;					
					 -->
						<BR/> 
						<input class="button" type="submit" value="18th Century (1700-1799)" onclick="chooseTime(18);"><BR>&nbsp;					
					
						<BR/> 
						<input class="button" type="submit" value="19th Century (1800-1899)" onclick="chooseTime(19);"><BR>&nbsp;					

						<BR/> 
						<input class="button" type="submit" value="20th Century (1900-1999)" onclick="chooseTime(20);"><BR>&nbsp;					
					
						<BR/> 
						<input class="button" type="submit" value="ANY TIME at ALL!" onclick="chooseTime(0);"><BR>&nbsp;					

				</div>

				<div id="PlaceDIV" style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
					<P><B>WHERE</B> do you want to do your sleuthing ?<BR/>
					
					<P>
						
						<BR/> 
						
						<select id=LocationSelect class="button" type="submit" onchange="changeOnwardButton(1);">
							<option value="?">Click here to choose a Country for your Mystery Box profiles</option>
							<option value="Australia">Australia</option>
							<option value="Austria">Austria</option>
							<option value="Belgium">Belgium</option>
							<option value="Brazil">Brazil</option>
							<option value="Canada">Canada</option>
							<option value="Chile">Chile</option>
							<option value="Czech Republic">Czech Republic</option>
							<option value="Denmark">Denmark</option>
							<option value="England">England</option>
							<option value="Finland">Finland</option>
							<option value="France">France</option>
							<option value="Germany">Germany</option>
							<option value="Hungary">Hungary</option>
							<option value="Hungary">Hungary</option>
							<option value="India">India</option>
							<option value="Ireland">Ireland</option>
							<option value="Italy">Italy</option>
							<option value="Luxembourg">Luxembourg</option>
							<option value="Mexico">Mexico</option>
							<option value="Netherlands">Netherlands</option>
							<option value="New Zealand">New Zealand</option>
							<option value="Norway">Norway</option>
							<option value="Poland">Poland</option>
							<option value="Portugal">Portugal</option>
							<option value="Prussia">Prussia</option>
							<option value="Romania">Romania</option>
							<option value="Russia">Russia</option>
							<option value="Scotland">Scotland</option>
							<option value="Slovenia">Slovenia</option>
							<option value="South Africa">South Africa</option>
							<option value="Spain">Spain</option>
							<option value="Sweden">Sweden</option>
							<option value="Switzerland">Switzerland</option>
							<option value="United Kingdom">United Kingdom</option>
							<option value="United States">United States</option>						
							<option value="Wales">Wales</option>
						</select><BR/>
						<P align=center> - OR - </P>
						<B>Enter a Location to search in: </B><input type=text id=LocationRegionText onchange="changeOnwardButton(2);"><BR>
					

						<input id=LocationOnwardButton style="display:none;" class="button" type="submit" value="Onward to ..." onclick="choosePlace('?');"><BR>&nbsp;
					
						<BR/> 
						<P align=center> - OR - </P>
						<BR/> 
						<input class="button" type="submit" value="Surprise Me!" onclick="choosePlace('*');"><BR>&nbsp;
						
					
				
					
				</div>


				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;

			
			<BR/>
			<a name="bot" ></a>
			<p align=center><a href=#top>Top</a><br><br>
					</p>
	</div>
 
	<div id="need_login" align=center  style="display:none;">
		
	      <p>You are NOT currently logged in to apps.wikitree.com.<br/>
	      	To access the data in your Family Tree, you need to login.<br><br>
	         Login with the same information that you use for WikiTree.
	        <form action="<?php echo htmlspecialchars(API_URL); ?>" method="POST">
	        <input type="hidden" name="action" value="clientLogin">
	        <input type="hidden" name="appId" value="mBox">
	        <input type="hidden" name="returnURL" value="https://apps.wikitree.com/apps/clarke11007/appsGPCdev/mysteryBox.php">
	        <input type="submit" class="button" value="Client Login">
	        </form>
	      </p>
	      
	    </div>

	<div id="ChooseBox" align=center style="display:none; background:white ; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 5px; border-radius: 20px;">

		<div id="ChooseMysteryBoxParams" align=center></div>
		
		<span id="chooseBoxMsg"><p>Choose a Mystery Box by clicking on it:</p></span><br />		
		<svg id="AllBoxes" width=600px height=630px>			
		</svg>
		<span id="theProfilesChosen"></span><br />		
		<span id="AcceptButtonSPAN" style="display:none;" >
			<br />		
			<input id=AcceptButton class="button" type="submit" value="I Accept this Challenge !" onclick="acceptChallenge();"><BR>
			<I>More details about Profiles selected, and option to swap out profiles if needed.</I><BR><BR><BR>
			<input id=AcceptButton class="mediumInputButton" style="color:orange;" type="submit" value="change parameters" onclick="changeParameters();"><BR>
			
		</span>
	</div>

	<div id="AcceptedDIV" align=center style="display:none; background:white url('../pix/StockCake-VintageScholarsDesk_1726320685.jpg'); max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 5px; border-radius: 20px;">
		<div id="InnerAcceptedDIV" align=center style="background-color:white; max-width: 660px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
			<H3 id=AcceptedDetailsH3>Mystery Box Details</H3>
			<DIV id=FullDetails>... this part is still a work in progress ...</DIV>
		</div>
	</div>
	
		<div id="MyStatsDIV" align=center style="display:none; background-color:white; max-width: 660px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
			<H3>My Mystery Box Stats</H3>
			<DIV id=StatsDetails>... this part is still a work in progress ...</DIV>
			<BR><BR><BR>
			<button class=confirmLikeButton onclick="getMySticker();">Get Mystery Box Sticker</button><BR><BR>
			<button id=GoToBoxButton4MyStats class=confirmLikeButton onclick="changeParametersOrLoadBox();">Choose New Mystery Box</button><BR><BR>
			<button class=confirmLikeButton onclick="viewMysteryStats();">Challenge Stats</button><BR><BR>
			<span id=CongratulationsDIV4Stats></span>

		</div>
	
		<div id="MysteryStatsDIV" align=center style="display:none; background-color:white; max-width: 660px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
			<H3>Mystery Box Challenge Statistics</H3>
			<DIV id=MysteryStatsDetails>... this part is still a work in progress ...</DIV>
			<BR><BR><BR>
			<button class=confirmLikeButton onclick="viewMyStats();">View My Stats</button><BR><BR>
			<button class=confirmLikeButton onclick="getMySticker();">Get Mystery Box Sticker</button><BR><BR>
			<button id=GoToBoxButton4MysteryStats class=confirmLikeButton onclick="changeParametersOrLoadBox();">Choose New Mystery Box</button><BR><BR>			

		</div>

		<div id="AdoptingMeDIV" align=center style="display:none; background-color:white; max-width: 660px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
			<H3>Adopt This Profile</H3>
			<DIV id=AdoptingMeDetails>... this part is still a work in progress ...</DIV>
			<BR><BR><BR>

			<input id=AdoptMeButton class="button" type="submit" value="I will Adopt this Mystery Box !" onclick="adoptBox();"><BR>
			<BR><BR><BR>
			<input id=NotAdoptingButton class="mediumInputButton" style="color:orange;" type="submit" value="Sorry, not at this time" onclick="notAdopting();"><BR>	

		</div>
	
		<div id="MyStickerDIV" align=center style="display:none; background-color:white; max-width: 660px; padding: 20px; margin: 20px; border-color: orange; border-style: solid; border-width: 5px; border-radius: 20px;">
			<H3>My Mystery Box Sticker</H3>
			<DIV align=left>
			You can add this sticker to your Profile's Bio to indicate solving this Mystery Box:<BR>
			<DIV id=StickerDetails4Box>... once you've solved your first Mystery Box, you will see the sticker and the code for it here ...</DIV>
			<BR><BR>
			You can add this sticker to your Profile's Bio to show your overall Mystery Box stats:<BR>
			<DIV id=StickerDetails4Overall>... once you've solved your first Mystery Box, you will see the sticker and the code for it here ...</DIV>
			</DIV>
			<BR><BR><BR>
			<button class=confirmLikeButton onclick="viewMyStats();">View My Stats</button><BR><BR>
			<button id=GoToBoxButton4MyStickers class=confirmLikeButton onclick="changeParametersOrLoadBox();">Choose New Mystery Box</button><BR><BR>
			<span id=CongratulationsDIV4Stickers></span>


      <P>&nbsp;</P><P><font size=1 color=white>Photo by <a target=_blank href="https://stockcake.com/i/vintage-scholar-s-desk_731947_813070"><font size=1 color=yellow>Stockcake</font></a></font></P>
	</div>
	<DIV id=ReturnBoxDIV style="display: none;"><input id=ReturnBoxButton type=submit class=submit value="Return Box" onclick="returnMysteryBoxQuery();"></DIV>


<P align=center>

		&nbsp;&nbsp; •  &nbsp;&nbsp;<BR />
	<input type=submit class="submit mediumInputButton" id=showMyStats value="My Stats" onclick='viewMyStats();'><BR />
		&nbsp;&nbsp; •  &nbsp;&nbsp;<BR />
	<input type=submit class="submit mediumInputButton" id=showMyStats value="Challenge Stats" onclick='viewMysteryStats();'><BR />
		&nbsp;&nbsp; •  &nbsp;&nbsp;<BR />
	<A target=_blank href="https://www.wikitree.com/wiki/Space:Mystery_Box_Challenge">Mystery Box Challenge - Free Space Page</A><BR />
		&nbsp;&nbsp; •  &nbsp;&nbsp;<BR />

		&nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1812603/introducing-the-mystery-box-challenge>Find out more at the G2G post</A> 
		&nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.youtube.com/watch?v=hnQQMbhZ6kU&list=PLEqK4ICkQWXT66wnFSHdEEHdh6uiYP95v&ab_channel=WikiTree>YouTube demo</A> 
		&nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>Bug in the app? Contact the author</A> 
		&nbsp;&nbsp; •  &nbsp;&nbsp; 

		<br />
		<br />
<A target=_blank href=../index.php>Other apps by Greg Clarke</A> <BR><BR>
<DIV id=StatusDIV></DIV><BR><BR/>
App version 1.22b : 2026-Jan-21


</P>
</HTML>
