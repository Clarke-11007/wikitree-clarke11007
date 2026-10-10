<?php

// ======================
// SURNAME List Generator
// ======================

// App idea by Mags Galden
// Programmed by Greg Clarke

// Input - WikiTree Profile ID
// Output - List of Surnames of Ancestors, in alphabetical order, with the Earliest Known Ancestor (EKA) for each surname
//	 - short list will just be surnames, with each surname having the link embedded to the EKA
//	 - long list will show each surname, with name variations over time, and EKA explicitly listed, with birth date & place

// BOTH lists will be displayed as regular results on webpage and with additional WikiText markup appropriate for embedding in a WikiTree Profile
// Optionally - there will be a button that will automatically add this Surname List to the Bio of a Profile


session_start();
$_SESSION['app'] = "WikiTree"; // say something to initialize session
date_default_timezone_set('UTC');

// =========
// CONSTANTS
// =========


// =========
// VARIABLES
// =========


if (isset($_GET['ph'])) {
	$ph = $_GET['ph'];	
} else {
	$ph = -1;
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];	
	$_SESSION['id'] = $_GET['id'];
} else {
	$id = 0;
}
	
if (isset($_GET['maxGens'])) {
	$maxGens = $_GET['maxGens'];
	$_SESSION['maxGens'] = $maxGens;	
} else if (isset($_SESSION['maxGens'])) {
	$maxGens = $_SESSION['maxGens'];
} else {
	$maxGens = 6;
}
$_SESSION['maxGens'] = $maxGens;


if (isset($_GET['includeSingles'])) {
	$includeSingles = $_GET['includeSingles'];	
	$_SESSION['includeSingles'] = $includeSingles;
} else {
	$includeSingles = 'Y';
}

if (isset($_GET['includeGens'])) {
	$includeGens = $_GET['includeGens'];
} else {
	$includeGens = 'Y';
}
$_SESSION['includeGens'] = $includeGens;	

if (isset($_GET['colourize'])) {
	$colourize = $_GET['colourize'];	
	$_SESSION['colourize'] = $colourize;
} else {
	$colourize = 'N';
}
if (isset($_GET['listType'])) {
	$listType = $_GET['listType'];	
	$_SESSION['listType'] = $listType;
} else {
	$listType = 'B';
}

if (isset($_GET['sortByColour'])) {
	$sortByColour = $_GET['sortByColour'];	
} else {
	$sortByColour = 'N';
}
$_SESSION['sortByColour'] = $sortByColour;

if (isset($_GET['ask'])) {
	$ask = $_GET['ask'];	
} else {
	$ask = 'N';
}




?>

	<HTML>
		<HEAD>
		<TITLE>Surname Generator</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="css/base.css"/>
		<LINK REL="stylesheet" TYPE="text/css" HREF="WTapps.css"/>

		<!-- For convenience, use JQuery for our Ajax interactions with the API. Use the cookie module to grab any existing user id. --> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
		<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.js"></script>      -->
		<script src="/apps/jquery.cookie.js"></script>
		<!-- <script src="/apps/wikitree.js"></script> -->
		<script src="/apps/clarke11007/wikitree.js"></script>
		<script src="js/WikiTreeAuthGPC.js"></script>

	
      <script>
      	var appID = "surnamesGen";

         function functionConfirm(msg, myYes, myNo, cancel) {
            var confirmBox = $("#confirm");
            confirmBox.find(".message").text(msg);
            confirmBox.find(".yes,.no,.cancel").unbind().click(function() {
               confirmBox.hide();
            });
            confirmBox.find(".yes").click(myYes);
            confirmBox.find(".no").click(myNo);
            confirmBox.find(".cancel").click(cancel);
            confirmBox.show();
         }
      </script>

	<style>
         #confirm {
            display: none;
            background-color: #91FF00;
            border: 1px solid #aaa;
            position: fixed;
            width: 250px;
            left: 50%;
            margin-left: -100px;
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
            width: 160px;
            cursor: pointer;
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



		// Define the URL where our API requests will be POSTed.
		// var API_URL = 'https://apps.wikitree.com/apps/clarke11007/fanTest.php';
		var API_URL = 'https://api.wikitree.com/api.php';
		var theAppID = "surnamesGen";

		var allFatherIDs = [];		

		<?php 	echo "var ID = '" . $id . "'; "; 
			echo "var maxGens = '" . $maxGens . "'; "; 
			echo "var includeSingles = '" . $includeSingles . "'; "; 
			echo "var includeGens = '" . $includeGens . "'; "; 
			echo "var colourize = '" . $colourize . "'; "; 
			echo "var listType = '" . $listType . "'; "; 
			echo "var sortByColour = '" . $sortByColour . "'; "; 
			echo "var ask = '" . $ask . "'; "; 
			echo "var debugNow = '" . $_GET['d'] . "'; "; 
		
		?>

		var theDebugID = 'noOne-000';
		var theDebugIDsDad = 'noOne-0000';

		var surnamesFullEntryList = [];		 
		var decisionsArray = [];		 
		var decisionsIntervalsArray = [];	
		var personArray = [];
		var ahnenIndexArray = [];
		var bioIndexArray = [];
		var ahnenGenClrNameIndexArray = [];
		var bioGenClrNameIndexArray = [];
		var numInvestigations = 0;
		var deepestGen = -1;
		var numAtDeepestGen = 0;
		var oldestAncBDate = 9999;
		var oldestAnc = '';
		var TheNotes = '';
		var TheDebugNotes = '';
		var theRecentTime = new Date();

		var splitGenNumValue = 6;
		var subsortColourValue = 'N';
		var showOnlyNewNamesValue = 'N';
		var showAhnNumsValue = 'N';

		var PrivatePersonForArray =  {Name:"Private", LastNameAtBirth:"Private"};


		var WikiText4DetailedList = "<!-- Detailed Surnames added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php -->\n=== My Surnames ===\n:Legend:\n:Surname (''alternate spellings'') : First Person with surname in tree → EKA - Earliest Known Ancestor";
		
		var WikiText4CompactList = "<!-- Compact Surnames added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php -->\n=== My Surnames ===\n:''Click on Surname to view EKA - Earliest Known Ancestor''";
		
		

		function similar_text (first, second, percent) { // eslint-disable-line camelcase
		  //  discuss at: https://locutus.io/php/similar_text/
		  // original by: Rafał Kukawski (https://blog.kukawski.pl)
		  // bugfixed by: Chris McMacken
		  // bugfixed by: Jarkko Rantavuori original by findings in stackoverflow (https://stackoverflow.com/questions/14136349/how-does-similar-text-work)
		  // improved by: Markus Padourek (taken from https://www.kevinhq.com/2012/06/php-similartext-function-in-javascript_16.html)
		  //   example 1: similar_text('Hello World!', 'Hello locutus!')
		  //   returns 1: 8
		  //   example 2: similar_text('Hello World!', null)
		  //   returns 2: 0
		  if (first === null ||
		    second === null ||
		    typeof first === 'undefined' ||
		    typeof second === 'undefined') {
		    return 0
		  }
		  first += ''
		  second += ''
		  let pos1 = 0
		  let pos2 = 0
		  let max = 0
		  const firstLength = first.length
		  const secondLength = second.length
		  let p
		  let q
		  let l
		  let sum
		  for (p = 0; p < firstLength; p++) {
		    for (q = 0; q < secondLength; q++) {
		      for (l = 0; (p + l < firstLength) && (q + l < secondLength) && (first.charAt(p + l) === second.charAt(q + l)); l++) { // eslint-disable-line max-len
		        // @todo: ^-- break up this crazy for loop and put the logic in its body
		      }
		      if (l > max) {
		        max = l
		        pos1 = p
		        pos2 = q
		      }
		    }
		  }
		  sum = max
		  if (sum) {
		    if (pos1 && pos2) {
		      sum += similar_text(first.substr(0, pos1), second.substr(0, pos2))
		    }
		    if ((pos1 + max < firstLength) && (pos2 + max < secondLength)) {
		      sum += similar_text(
		        first.substr(pos1 + max, firstLength - pos1 - max),
		        second.substr(pos2 + max,
		          secondLength - pos2 - max))
		    }
		  }
		  if (!percent) {
		    return sum
		  }
		  return (sum * 200) / (firstLength + secondLength)
		}

		function switchTab( tabName ) {
			if (tabName == 'Compact' || tabName == 'Detailed') {
				if (tabName == 'Compact') {
					otherTab = 'Detailed';
				} else {
					otherTab = 'Compact';
				}
				document.getElementById(tabName + 'Btn').style = 'background-color:orange;';
				document.getElementById(otherTab + 'Btn').style = 'background-color:#25422d;';
				document.getElementById(tabName + 'Content').style.display = 'block';
				document.getElementById(otherTab + 'Content').style.display = 'none';

			} else if (tabName == 'BandW' || tabName == 'Clr2' || tabName == 'Clr4') {
				if (tabName == 'BandW') {
					otherTab = 'Clr2';
					otherTab2 = 'Clr4';
					colourize = 'N';
				} else if (tabName == 'Clr2') {
					otherTab = 'BandW';
					otherTab2 = 'Clr4';
					colourize = 2;
				} else if (tabName == 'Clr4') {
					otherTab = 'BandW';
					otherTab2 = 'Clr2';
					colourize = 4;
				}
				document.getElementById(tabName + 'Btn').style = 'background-color:orange;';
				document.getElementById(otherTab + 'Btn').style = 'background-color:#25422d;';
				document.getElementById(otherTab2 + 'Btn').style = 'background-color:#25422d;';

				if (tabName == 'BandW') {
					// document.getElementById('SortSurnameBtn').style.display = 'none';
					document.getElementById( 'SortColourBtn').style.display = 'none';
				} else {
					// document.getElementById('SortSurnameBtn').style.display = 'inline-block';
					document.getElementById( 'SortColourBtn').style.display = 'inline-block';
				}
				summarizeResults();

			} else if (tabName == 'SortSurname' || tabName == 'SortColour' || tabName == 'SortGen') {
				if (tabName == 'SortSurname') {
					otherTab = 'SortColour';
					otherTab2 = 'SortGen';
					sortByColour = 'N';
					document.getElementById( 'GenOptions').style.display = 'none';
				} else if (tabName == 'SortGen') {
					otherTab = 'SortColour';
					otherTab2 = 'SortSurname';
					sortByColour = 'Gen' + colourize;
					document.getElementById( 'GenOptions').style.display = 'block';

				} else {
					otherTab = 'SortSurname';
					otherTab2 = 'SortGen';
					sortByColour = 'Y';
					document.getElementById( 'GenOptions').style.display = 'none';
				}

				document.getElementById(otherTab2 + 'Btn').style = 'background-color:#25422d;';
				document.getElementById(otherTab + 'Btn').style = 'background-color:#25422d;';
				document.getElementById(tabName + 'Btn').style = 'background-color:orange;';

				if (colourize == 'N') {
					document.getElementById( 'SortColourBtn').style.display = 'none';
					// alert (tabName);
				}
				
				summarizeResults();
					// alert (tabName);

			}
		}

		function formatCompactListByGen() {
			// alert ("Starting the Compact List");
			var prevLetter = "";
			var theCompactList = "";
			var prevGenNames = [];
			var currGenNames = [];

			var endSpan = "</span>";

			if (colourize == '2') {
				AlphasSortIndexArray = [0,0,1,1];
				ColourLegendArray = ["<span style='background-color:#eef;' >","<span style='background-color:#eef;' >","<span style='background-color:#fee;' >" , "<span style='background-color:#fee;' >" ];

			} else if (colourize == '4') {
				AlphasSortIndexArray = [0,1,2,3];
				ColourLegendArray = ["<span style='background-color:#eef;' >","<span style='background-color:#dfd;' >","<span style='background-color:#fed8b1;' >" , "<span style='background-color:#fee;' >" ];				
			} else {
				AlphasSortIndexArray = [0,0,0,0];
				ColourLegendArray =[ "<span>", "<span>", "<span>", "<span>"];
			}

			if (subsortColourValue == false) {
				AlphasSortIndexArray = [0,0,0,0];
			}

			// if (allBioLastNames > "") {
			// 	theCompactList += "<br>* = BioParent surnames<BR>";
			// }

			if (splitGenNumValue > 1) {
				theCompactList += "<br><I>Surnames listed in Ahnentafel order</I><br>";
				WikiText4CompactList += "\n''Surnames listed in Ahnentafel order''\n";
			} else {
				theCompactList += "<br><I>Surnames sorted alphabetically within generation</I><br>";
				WikiText4CompactList += "\n''Surnames sorted alphabetically within generation''\n";
			}

			for (var g = 1; g <= (maxGens); g++) {
				// alert ("Gen " + g);
				if (splitGenNumValue > 1 && splitGenNumValue == g) {					
					theCompactList += "<br><br><I>Surnames sorted alphabetically within generation" +  (subsortColourValue == true?" by colour":"") +"</I>";
					WikiText4CompactList += "\n\n''Surnames sorted alphabetically within generation" +  (subsortColourValue == true?" by colour":"") +"''";
				}


				if (g>1) { theCompactList += "<br/>";}
				theCompactList += "<br>GENERATION # " + g + "<br>" ;
				WikiText4CompactList += "\n \n::GENERATION # " + g + "\n:" ;

				prevGenNames = currGenNames;
				currGenNames = [];

				startingAH = 2**(g-1);
				newNamesSinceLastBreak = false;
				if (g < splitGenNumValue) {
					// =======
					// DISPLAY THIS GENERATION USING AHNENTAFEL # ORDERING
					// ===================================================
					if (g>=6) {breakNumber = 2**(g-3);} else {breakNumber = 4;}
					// report in Ahnentafel order, colour coded as desired
					for (var ah = startingAH; ah < 2**g; ah++) {
						theSeparator = ", ";
						if (ah % breakNumber == 0 && ah >= 12) { 
							if (newNamesSinceLastBreak) {
								theCompactList += "<br>";
								WikiText4CompactList += "\n:";
								theSeparator = "";
								forceBoldFirstChar = true;
							} else {
								forceBoldFirstChar = false;
							}
							newNamesSinceLastBreak = false;
						} else {
							forceBoldFirstChar = true;
						}
						if (ah == startingAH) {
							theSeparator = "";
						}
						cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
						
						if (ahnenIndexArray[ ah ] ) {
							
							if (personArray[ ahnenIndexArray[ ah ] ]) {
								
								if (personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth) { 
									currLastName = personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth ;
									WikiID = personArray[ ahnenIndexArray[ ah ] ].Name ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									// pass all the hurdles ?  If so ... then add to the list !	
									if (showAhnNumsValue == true) { AhnNumTxt = "[" + ah + "]";} else {AhnNumTxt = "";}

									
									// if (needToUseVertAtoZlist) {
										thisLetter = currLastName[0];
										
										
										safeWikiID = WikiID.replace(/ /g, "%20").replace(/'/g, "&#39;");
										
										
										
										if (thisLetter == prevLetter && !forceBoldFirstChar) {
											WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID  +   ">"  + ColourLegendArray[cc] +   currLastName   + endSpan + "</A>";
											theCompactList +=  WikiProfileLinkLast + AhnNumTxt;
											WikiText4CompactList += ", [[" + personArray[ ahnenIndexArray[ ah ] ].Name +"|" + ColourLegendArray[cc] +   currLastName   + endSpan + "]] "+ AhnNumTxt;
										} else {
											WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID +   "><b>"  +   currLastName[0] +  "</b>"  + ColourLegendArray[cc] +  currLastName.substr(1)  + endSpan + "</A>";
											theCompactList += /*"<br/>" + */WikiProfileLinkLast + AhnNumTxt;
											WikiText4CompactList += /*"\n" +*/ theSeparator + "[[" + personArray[ ahnenIndexArray[ ah ] ].Name + "|" + "'''" + currLastName[0] +  "'''" + ColourLegendArray[cc] +  currLastName.substr(1)  + endSpan + "]]" + AhnNumTxt;					
											prevLetter = thisLetter;
										}

										theCompactList += ", ";
									// } else {
									// 	theCompactList +=  ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// 	WikiText4CompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// }


									// theCompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// WikiText4CompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									newNamesSinceLastBreak = true;
								}
							}
							// alert ("Done ah = " + ah);
						} else {
							// alert ("NO ahnenIndexArray[ ah ] ");
						}

						// ==================================================================
						// NOW ... let's see if there are any entries in the BIO index array
						// ==================================================================
						if (bioIndexArray[ ah ] && ah > 1) {
							
							if (personArray[ bioIndexArray[ ah ] ]) {
								
								if (personArray[ bioIndexArray[ ah ] ].LastNameAtBirth) { 
									currLastName = personArray[ bioIndexArray[ ah ] ].LastNameAtBirth ;
									WikiID = personArray[ bioIndexArray[ ah ] ].Name ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									// pass all the hurdles ?  If so ... then add to the list !	
									if (showAhnNumsValue == true) { AhnNumTxt = "[" + ah + "]";} else {AhnNumTxt = "";}

									
									// if (needToUseVertAtoZlist) {
										thisLetter = currLastName[0];
										
										
										safeWikiID = WikiID.replace(/ /g, "%20").replace(/'/g, "&#39;");
										
										
										
										if (thisLetter == prevLetter && !forceBoldFirstChar) {
											WikiProfileLinkLast = "*<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID  +   ">"  + ColourLegendArray[cc] +   currLastName   + endSpan + "</A>";
											theCompactList +=  WikiProfileLinkLast + AhnNumTxt;
											WikiText4CompactList += ", *[[" + personArray[ bioIndexArray[ ah ] ].Name +"|" + ColourLegendArray[cc] +   currLastName   + endSpan + "]] "+ AhnNumTxt;
										} else {
											WikiProfileLinkLast = "*<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID +   "><b>"  +   currLastName[0] +  "</b>"  + ColourLegendArray[cc] +  currLastName.substr(1)  + endSpan + "</A>";
											theCompactList += /*"<br/>" + */WikiProfileLinkLast + AhnNumTxt;
											WikiText4CompactList += /*"\n" +*/ theSeparator + " *[[" + personArray[ bioIndexArray[ ah ] ].Name + "|" + "'''" + currLastName[0] +  "'''" + ColourLegendArray[cc] +  currLastName.substr(1)  + endSpan + "]]" + AhnNumTxt;					
											prevLetter = thisLetter;
										}

										theCompactList += ", ";
									// } else {
									// 	theCompactList +=  ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// 	WikiText4CompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// }


									// theCompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// WikiText4CompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									newNamesSinceLastBreak = true;
								}
							}
							// alert ("Done ah = " + ah);
						} else {
							// alert ("NO bioIndexArray[ ah ] ");
						}
					}
					theCompactList = theCompactList.substr(0, theCompactList.length - 2);
				} else {
					// go through the multiple passes routine, as needed, based on colourize factor
					// =======
					// DISPLAY THIS GENERATION USING ALPHA ORDERING
					// ============================================
					list4Sorting = [];
					for (var ah = startingAH; ah < 2**g; ah++) {
						if (ahnenIndexArray[ ah ] ) {
							if (personArray[ ahnenIndexArray[ ah ] ]) {
								if (personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth) {
									currLastName = personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
									list4Sorting.push( AlphasSortIndexArray[cc] + currLastName  + "~" + ah);
								}
							}
						}
						if (bioIndexArray[ ah ] ) {
							if (personArray[ bioIndexArray[ ah ] ]) {
								if (personArray[ bioIndexArray[ ah ] ].LastNameAtBirth) {
									currLastName = personArray[ bioIndexArray[ ah ] ].LastNameAtBirth ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
									list4Sorting.push( AlphasSortIndexArray[cc] + currLastName  + "~" + ah + "B");
								}
							}
						}
					}
					list4Sorting.sort();

					if (subsortColourValue == false || colourize == 'N') {
						numDivs = 1;
					} else  {
						numDivs = colourize;
					}

					numPerDiv = startingAH / numDivs;

					if (numPerDiv > 8) {
						needToUseVertAtoZlist = true;
					} else {
						needToUseVertAtoZlist = false;
					}


					prevAlphaSortPrefix = 0;
					prevLetter = "";

					for (var i = 0; i < list4Sorting.length; i++) {
						theSeparator = ", ";
						var ah = list4Sorting[i].substr(list4Sorting[i].search("~") + 1);
						let isBioAhnen = ah.indexOf("B") > -1;
						if (isBioAhnen) {
							ah = ah.substring(0,ah.search("B"));
						}

						cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
						if (subsortColourValue && AlphasSortIndexArray[cc] > prevAlphaSortPrefix ) {
							theCompactList += "<br>";
							WikiText4CompactList += "\n:";
							forceBoldFirstChar = true;
							theSeparator = "";
						} else {
							forceBoldFirstChar = false;
						}
						if (i==0) {theSeparator = "";}
						prevAlphaSortPrefix = AlphasSortIndexArray[cc];

						if (isBioAhnen) {
							console.log({i},list4Sorting[i], {ah});
							if (bioIndexArray[ ah ] && personArray[ bioIndexArray[ ah ] ] && personArray[ bioIndexArray[ ah ] ].LastNameAtBirth){
								currLastName = personArray[ bioIndexArray[ ah ] ].LastNameAtBirth ;
								WikiID = personArray[ bioIndexArray[ ah ] ].Name ;
							}
						} else {
							currLastName = personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth ;
							WikiID = personArray[ ahnenIndexArray[ ah ] ].Name ;
						}

						// theCompactList +=   ah + " : ";
						if (showAhnNumsValue == true) { AhnNumTxt = "[" + ah + "]";} else {AhnNumTxt = "";}
						// if (needToUseVertAtoZlist) 
							thisLetter = currLastName[0];
							safeWikiID = WikiID.replace(/ /g, "%20").replace(/'/g, "&#39;");
							if (thisLetter == prevLetter && !forceBoldFirstChar) {
								WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID  +   ">"  + ColourLegendArray[cc] +   currLastName   + endSpan + "</A>";
								theCompactList +=  (isBioAhnen ?"*":"") + WikiProfileLinkLast + AhnNumTxt;
								WikiText4CompactList += ", "+(isBioAhnen ?"*":"")+"[[" + WikiID +"|" + ColourLegendArray[cc] +   currLastName   + endSpan + "]] "+ AhnNumTxt;
							} else {
								WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID +   "><b>"  +   currLastName[0] +  "</b>"  + ColourLegendArray[cc] +  currLastName.substr(1)  + endSpan + "</A>";
								theCompactList +=  (needToUseVertAtoZlist == true ? "<br/>" : "") + "<font color="+(isBioAhnen ?"black":"white")+ ">*</font>" + WikiProfileLinkLast + AhnNumTxt;
								WikiText4CompactList +=  (needToUseVertAtoZlist == true ? "\n:" : theSeparator)  + (isBioAhnen ?" *":"")  + "[[" + WikiID + "|" + "'''" + currLastName[0] +  "'''" + ColourLegendArray[cc] +  currLastName.substr(1)  + endSpan + "]]" + AhnNumTxt;					
								prevLetter = thisLetter;
							}
						// } else {
						// 	theCompactList +=  ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
						// 	WikiText4CompactList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
						// }
						theCompactList += ", ";

						newNamesSinceLastBreak = true;
					}
					theCompactList = theCompactList.substr(0, theCompactList.length - 2);


				}
			}

			// if (debugNow) {
			// 	theCompactList += "<br><h2>Debug List goes here</h2></br>";
			// 	for (var ah = 0; ah < 2** (maxGens ); ah++) {
			// 		if (ahnenIndexArray[ ah ] ) {
			// 			theCompactList += ah + " : " + ahnenIndexArray[ ah ] + "<br>";
			// 		} else {
			// 			theCompactList += ah + " :  - nada - <br>";
			// 		}
			// 	}
			// }

			// alert ("ENDING the Compact List");	 
			return theCompactList;

		}

		function getPersonDetails4Lists ( ah , ColourLegend , isBioAhnen = false) {
			var theDetails = ['LastName', 'Surname'];
			
			var ThePerson = personArray[ ahnenIndexArray[ ah ] ];
			if (isBioAhnen==true) {
				ThePerson = personArray[ bioIndexArray[ ah ] ];
			}
			
			var WikiID = ""; 
			if (ThePerson.Name) {WikiID = ThePerson.Name;};
			// alert (WikiID);

			
			theViewListWikiID = "";
			listOfAlArrayContents = "";
			for (var i = 0; i < surnamesFullEntryList.length; i++) {
				if (surnamesFullEntryList[i]) {
					if (surnamesFullEntryList[i][1]) {
						if (surnamesFullEntryList[i][1][5]) {
							var theArray = surnamesFullEntryList[i][1][5];
							
							if (theArray.indexOf(ah) > -1 ){
								theViewListWikiID = surnamesFullEntryList[i][1][1];
								break;
							}

						}
					}
				}
			}
					
			ThePersonLastNameAtBirth = "";
			if (theViewListWikiID > "" ) {
				ThePersonLastNameAtBirth += "<a onclick=viewListWithSurname('" + theViewListWikiID + "');>";
			}
			if (ThePerson.LastNameAtBirth){
				ThePersonLastNameAtBirthColoured = ColourLegend + "<b>" + ThePerson.LastNameAtBirth +"</b>" + "</span>";
				ThePersonLastNameAtBirth += ThePersonLastNameAtBirthColoured;
			} else {
				ThePersonLastNameAtBirthColoured = "Unknown";
			}
			if (theViewListWikiID > "" ) {
				ThePersonLastNameAtBirth += "</a>";
			}
			


			if (ThePerson.BirthDate) { TheBirthDate = ThePerson.BirthDate; } else { TheBirthDate = ""; }
			if (ThePerson.BirthLocation) { TheBirthLocation = ThePerson.BirthLocation; } else { TheBirthLocation = ""; }
			if (ThePerson.BirthDate || ThePerson.BirthLocation) {
				TheBirthInfo = ", b. " + TheBirthDate;
				if (ThePerson.BirthDate && ThePerson.BirthLocation) { TheBirthInfo += ", ";}
				TheBirthInfo += TheBirthLocation;
			} else {
				TheBirthInfo = "";
			}
			if (ThePerson.FirstName) {
				var ThePersonFirstName = ThePerson.FirstName;
			} else {
				var ThePersonFirstName = "Private";
			}
										
			PedigreeLink = " <A target=_blank href=https://www.wikitree.com/genealogy/" + WikiID.replace(/ /g, "%20").replace(/'/g, "&#39;").replace("-","-Family-Tree-") +   ">" + "<IMG SRC=https://www.wikitree.com/images/icons/pedigree.gif>"   + "</A> ";
			DescendantsLink = " <A target=_blank href=https://www.wikitree.com/genealogy/" + WikiID.replace(/ /g, "%20").replace(/'/g, "&#39;").replace("-","-Descendants-") +   ">" + "<IMG SRC=https://www.wikitree.com/images/icons/descendant-link.gif>"   + "</A> ";

			safeWikiID = WikiID.replace(/ /g, "%20").replace(/'/g, "&#39;");
			WikiProfileLinkFirst = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeWikiID  +   ">"  +    ThePersonFirstName    + "</A>";

			theDetailed =  ThePersonLastNameAtBirth + " : "  + WikiProfileLinkFirst  + PedigreeLink + TheBirthInfo + DescendantsLink ;
			WikiText4Detailed = "[[" + ThePerson.Name +"|" + ThePersonLastNameAtBirthColoured  + ": " +    ThePersonFirstName + "]] " + TheBirthInfo  ;

			return [theDetailed,WikiText4Detailed];
		}



		var LastTimeMadeIt = 0;
		var ThisTimeMadeIt = 0;


		function formatDetailedListByGen() {
			LastTimeMadeIt = ThisTimeMadeIt;
			// alert ("Starting the Detailed List - made it last time to # " + LastTimeMadeIt);

			var prevLetter = "";
			var theDetailedList = "";
			var prevGenNames = [];
			var currGenNames = [];

			var endSpan = "</span>";

			if (colourize == '2') {
				AlphasSortIndexArray = [0,0,1,1];
				ColourLegendArray = ["<span style='background-color:#eef;' >","<span style='background-color:#eef;' >","<span style='background-color:#fee;' >" , "<span style='background-color:#fee;' >" ];

			} else if (colourize == '4') {
				AlphasSortIndexArray = [0,1,2,3];
				ColourLegendArray = ["<span style='background-color:#eef;' >","<span style='background-color:#dfd;' >","<span style='background-color:#fed8b1;' >" , "<span style='background-color:#fee;' >" ];				
			} else {
				AlphasSortIndexArray = [0,0,0,0];
				ColourLegendArray =[ "<span>", "<span>", "<span>", "<span>"];
			}

			if (subsortColourValue == false) {
				AlphasSortIndexArray = [0,0,0,0];
			}

			// if (allBioLastNames > "") {
			// 	theDetailedList += "<br>* = BioParent surnames<BR>";
			// }

			if (splitGenNumValue > 1) {
				theDetailedList += "<br><I>Surnames listed in Ahnentafel order</I><br>";
				WikiText4DetailedList +="\n:''Surnames listed in Ahnentafel order''\n";
			} else {
				WikiText4DetailedList +="\n''Surnames sorted alphabetically within generation''\n";
				theDetailedList += "<br><I>Surnames sorted alphabetically within generation</I><br>";
			}
			for (var g = 1; g <= (maxGens); g++) {
				// alert ("Gen # " + g);
				if (splitGenNumValue > 1 && splitGenNumValue == g) {					
					theDetailedList += "<br><I>Surnames sorted alphabetically within generation" +  (subsortColourValue == true?" by colour":"") +"</I><br>";
					WikiText4DetailedList +="\n''Surnames sorted alphabetically within generation" + (subsortColourValue == true?" by colour":"")  +   " ''\n";
				}

				theDetailedList += "<br>GENERATION # " + g + "<br>" ;
				WikiText4DetailedList += "\n:\n::GENERATION # " + g + "\n:" ;

				prevGenNames = currGenNames;
				currGenNames = [];

				startingAH = 2**(g-1);
				newNamesSinceLastBreak = false;
				if (g < splitGenNumValue) {
					// =======
					// DISPLAY THIS GENERATION USING AHNENTAFEL # ORDERING
					// ===================================================
					if (g>=6) {breakNumber = 2**(g-3);} else {breakNumber = 4;}
					// report in Ahnentafel order, colour coded as desired
					for (var ah = startingAH; ah < 2**g; ah++) {						
						cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
						ThisTimeMadeIt = ah;
						if (ahnenIndexArray[ ah ] ) {
							if (personArray[ ahnenIndexArray[ ah ] ]) {
								if (personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth)
									currLastName = personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									// pass all the hurdles ?  If so ... then add to the list !	
									if (showAhnNumsValue == true) { AhnNumTxt = " [" + ah + "]";} else {AhnNumTxt = "";}

									// alert ("Person Details " + ah + " - ?");
									thisPersonsDetails = getPersonDetails4Lists( ah , ColourLegendArray[cc]);
									// alert ("Person Details " + ah + " - YES");
									
									theDetailedList += "<font color=white>*</font>" + thisPersonsDetails[0] + AhnNumTxt +  "<br>";
									WikiText4DetailedList += "<font color=white>*</font>"  + thisPersonsDetails[1] + AhnNumTxt + "\n:";
									

									// } else {
									// 	theDetailedList +=  ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// 	WikiText4DetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// }


									// theDetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// WikiText4DetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									
							}
						}
						if (bioIndexArray[ ah ] && ah > 1) {
							if (personArray[ bioIndexArray[ ah ] ]) {
								if (personArray[ bioIndexArray[ ah ] ].LastNameAtBirth)
									currLastName = personArray[ bioIndexArray[ ah ] ].LastNameAtBirth ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									// pass all the hurdles ?  If so ... then add to the list !	
									if (showAhnNumsValue == true) { AhnNumTxt = " [" + ah + "]";} else {AhnNumTxt = "";}

									// alert ("Person Details " + ah + " - ?");
									thisPersonsDetails = getPersonDetails4Lists( ah , ColourLegendArray[cc], true);
									// alert ("Person Details " + ah + " - YES");
									
									theDetailedList += "<font color=black>*</font>" + thisPersonsDetails[0] + AhnNumTxt +  "<br>";
									WikiText4DetailedList += "<font color=black>*</font>" + thisPersonsDetails[1] + AhnNumTxt + "\n:";
									

									// } else {
									// 	theDetailedList +=  ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// 	WikiText4DetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// }


									// theDetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									// WikiText4DetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
									
							}
						}
					}
					// theDetailedList = theDetailedList.substr(0, theDetailedList.length - 2);
				} else {
					// go through the multiple passes routine, as needed, based on colourize factor
					// =======
					// DISPLAY THIS GENERATION USING ALPHA ORDERING
					// ============================================
					list4Sorting = [];
					for (var ah = startingAH; ah < 2**g; ah++) {
						ThisTimeMadeIt = ah;
						if (ahnenIndexArray[ ah ] ) {
							if (personArray[ ahnenIndexArray[ ah ] ]) {
								if (personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth) {
									currLastName = personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
									list4Sorting.push( AlphasSortIndexArray[cc] + currLastName  + "~" + ah);
								}
							}
						}
						if (bioIndexArray[ ah ] && ah > 1) {
							if (personArray[ bioIndexArray[ ah ] ]) {
								if (personArray[ bioIndexArray[ ah ] ].LastNameAtBirth) {
									currLastName = personArray[ bioIndexArray[ ah ] ].LastNameAtBirth ;
									currGenNames.push( currLastName );
									if (showOnlyNewNamesValue) {
										if (prevGenNames.length > 0 && prevGenNames.indexOf(currLastName) > -1 ) {
											continue; // skip this name because it's not unique
										}
									}
									cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
									list4Sorting.push( AlphasSortIndexArray[cc] + currLastName  + "~" + ah + "B");
								}
							}
						}
					}
					list4Sorting.sort();

					if (subsortColourValue == false || colourize == 'N') {
						numDivs = 1;
					} else  {
						numDivs = colourize;
					}

					numPerDiv = startingAH / numDivs;

					if (numPerDiv > 8) {
						needToUseVertAtoZlist = true;
					} else {
						needToUseVertAtoZlist = false;
					}


					prevAlphaSortPrefix = 0;
					prevLetter = "";

					for (var i = 0; i < list4Sorting.length; i++) {
						var ah = list4Sorting[i].substr(list4Sorting[i].search("~") + 1);
						let isBioAhnen = false;
						if (ah.search("B")>-1) {
							isBioAhnen = true;
							ah = ah.replace("B","");
						}
						ah = 1.0 * ah;

						cc =  Math.floor( (ah - startingAH) / (startingAH/4) );
						// if (subsortColourValue && AlphasSortIndexArray[cc] > prevAlphaSortPrefix ) {
						// 	theDetailedList += "<br>";
						// 	WikiText4DetailedList += "\n:";
						// 	forceBoldFirstChar = true;
						// } else {
						// 	forceBoldFirstChar = false;
						// }
						prevAlphaSortPrefix = AlphasSortIndexArray[cc];
						let preNameStar = "<font color=white>*</font>";
						// let wikiStar = "";
						if (isBioAhnen) {
							currLastName = personArray[ bioIndexArray[ ah ] ].LastNameAtBirth ;	
							preNameStar = "<font color=black>*</font>";
							// wikiStar = "*";
						} else {
							currLastName = personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth ;
						}
						// theDetailedList +=   ah + " : ";
						if (showAhnNumsValue == true) { AhnNumTxt = "[" + ah + "]";} else {AhnNumTxt = "";}
						// if (needToUseVertAtoZlist) 
						thisPersonsDetails = getPersonDetails4Lists( ah, ColourLegendArray[cc] , isBioAhnen);
								
						theDetailedList += preNameStar + thisPersonsDetails[0] + AhnNumTxt +  "<br>";
						WikiText4DetailedList += preNameStar + thisPersonsDetails[1] + AhnNumTxt + "\n:";
						// } else {
						// 	theDetailedList +=  ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
						// 	WikiText4DetailedList += ColourLegendArray[cc] +  personArray[ ahnenIndexArray[ ah ] ].LastNameAtBirth + "</span>" +  AhnNumTxt + ", ";
						// }
						// theDetailedList += "<br>";
						// WikiText4DetailedList += "\n:";

						// newNamesSinceLastBreak = true;
					}
					// theDetailedList = theDetailedList.substr(0, theDetailedList.length - 2);


				}
			}
			// alert ("EDNING the Detailed List");	 
			return theDetailedList;

		}

		function formatCompactList(theList) {
			console.log({theList});
			var prevLetter = "";
			var theCompactList = "";
			const COLOURNUM = 4;

			if (sortByColour == 'N' || colourize == 'N') {
				numCCiterations = 1;
			} else {
				numCCiterations = 4;
			}

		for (var cc = 0; cc < numCCiterations; cc++) {
			prevLetter = "";
			if (colourize == '2' && sortByColour == 'Y' && (cc % 2 == 0) ) {

				ColourLegendArray = ["<span style='background-color:#eef;' >Paternal</span> ancestors","","<span style='background-color:#fee;' >Maternal</span> ancestors" , "" ];
				if (ColourLegendArray[cc] > ''){
					theCompactList += "<br/><br/>" + ColourLegendArray[cc] + "<br/>";
					WikiText4CompactList += "\n:\n*" + ColourLegendArray[cc] + "\n:";
				}

			} else if (colourize == '4' && sortByColour == 'Y') {
				ColourLegendArray = ["<span style='background-color:#eef;' >Paternal Grandfather</span> ancestors","<span style='background-color:#dfd;' >Paternal Grandmother</span> ancestors", "<span style='background-color:#fed8b1;' >Maternal Grandfather</span> ancestors", "<span style='background-color:#fee;' >Maternal Grandmother</span> ancestors" , "" ];
				theCompactList += "<br/><br/>" + ColourLegendArray[cc] + "<br/>";
				WikiText4CompactList += "\n:\n*" + ColourLegendArray[cc] + "\n:";
			}


			for (var i = 0; i < theList.length; i++) {
				theSurname = theList[i][0];
				LastPerson = theList[i][2];
				AltSurnames = theList[i][3];
				thisLetter = theSurname[0];

				// if (AltSurnames > '') {
				// 	AltSurnames = " <I>(" + AltSurnames + ")</I>";
				// }

				var startSpan = "<span ";
				var endSpan = "</span>";
				if (theList[i][1].length > 0 ) {
					theNewClrNum =  theList[i][1][COLOURNUM];
				} else {
					theNewClrNum = theList[i][5];
				}

				if (numCCiterations > 1){
					if (colourize == 'N') {theNewClrNum = 0;}
					else if (colourize == '2' && theNewClrNum < 2) {theNewClrNum = 0;}
					else if (colourize == '2' && theNewClrNum >= 2) {theNewClrNum = 2;}
					
	
					if (theNewClrNum != cc) { continue; }
				}


				if (colourize == 2)  {
					if (theNewClrNum < 2) {
						startSpan += " style='background-color:#eef;' >";
					} else {
						startSpan += " style='background-color:#fee;' >";
					}
					
				} else if (colourize == 4)  {
					colours4Span = ['#eef','#dfd', '#fed8b1', '#fee'];
					startSpan += " style='background-color:" + colours4Span[ theNewClrNum ] + ";' >";
					
				} else {
					startSpan += ">";
				}

				safeLastName = LastPerson.Name.replace(/ /g, "%20").replace(/'/g, "&#39;");
				let isInBioList = false;
				let leadingStar = "<font color=white>*</font>";
				// let wikiStar = "";
				if (allBioLastNames.search(LastPerson.LastNameAtBirth) > -1) {
					isInBioList = true;
					leadingStar = "<font color=black>*</font>";
					// wikiStar = " *";
				}

				if (thisLetter == prevLetter) {
					WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeLastName  +   ">"  + startSpan +   theSurname + AltSurnames  + endSpan + "</A>";
					theCompactList += ", " + leadingStar + WikiProfileLinkLast;
					WikiText4CompactList += ", "+leadingStar+"[[" + LastPerson.Name +"|" + startSpan +   theSurname   + endSpan + "]] ";
				} else {
					WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + safeLastName +   "><b>"  +   theSurname[0] +  "</b>"  + startSpan +  theSurname.substr(1) + AltSurnames + endSpan + "</A>";
					theCompactList += "<br/>" + leadingStar + WikiProfileLinkLast;
					WikiText4CompactList += "\n:"+leadingStar+"[[" + LastPerson.Name + "|" + "'''" + theSurname[0] +  "'''" + startSpan +  theSurname.substr(1) + AltSurnames + endSpan + "]]";					
					prevLetter = thisLetter;
				}
			}
		}

			return theCompactList;
		}


		function viewListWithSurname ( TheID ) {
			// alert (TheID); // the WikiTree ID = e.g.  Clarke-11007 
			for (var i = 0; i < surnamesFullEntryList.length; i++) {
				if (surnamesFullEntryList[i][1][1] == TheID) {
					// alert (TheID + " " + surnamesFullEntryList[i][1][0] + " : " + surnamesFullEntryList[i][1][5] );
					var fullEntry = surnamesFullEntryList[i];
					var theArray = surnamesFullEntryList[i][1][5];
					break;
				}
			}

			var surname = fullEntry[0];
			// var surnameLink = surname;
			// var FirstPerson = fullEntry[1];
			// var LastPerson = fullEntry[2];
			var AltSurnames = "<I>" + fullEntry[3] + "</I>";
			var WikiAltSurnames = "";
			if (fullEntry[3] > '') {
				WikiAltSurnames =  "''" + fullEntry[3] + "''";
			}

			 
			// var NumGens = fullEntry[4];
			// var AltClrNum = fullEntry[5];

			const FIRSTNAME = 0;
			const WikiTreeUserName = 1;
			const BIRTHDATE = 2;
			const LOCATION = 3;
			const COLOURNUM = 4;
			const LISTOFAHNUMS = 4;

			CloseButton = "<p align=right><input type=submit class=submit value='x' onclick=closeDIV('FloatingLineage');></p>";
			LineageDIV = "<h2 align=center>" + surname + " " + AltSurnames + "</h2><p align=center>";
			WikiLineageDIV = "==" + surname + " " + WikiAltSurnames + "==\n";

			if (theArray[0] == theArray[1]) {
				startingI = 1;
			} else {
				startingI = 0;
			}
			for (var i = startingI; i < theArray.length; i++) {
				var ThisPerson = personArray[ ahnenIndexArray[ theArray[i] ] ];
				if (i>startingI) {
					LineageDIV += "<br>	&darr;<br>";
					WikiLineageDIV += "\n:::&darr;\n";
				}
				ThisSafeName = ThisPerson.Name.replace(/ /g, "%20").replace(/'/g, "&#39;");
				NameWithLink = "<b><a target=_blank href=https://wikitree.com/wiki/" + ThisSafeName + ">"  + ThisPerson.FirstName + " " + ThisPerson.LastNameAtBirth + "</a></b>";
				WikiNameWithLink = ":'''[[" + ThisSafeName + "|"  + ThisPerson.FirstName + " " + ThisPerson.LastNameAtBirth + "]]'''\n";
				LineageDIV += NameWithLink + "<br>b. ";
				WikiLineageDIV += WikiNameWithLink + ":b. ";
				if (ThisPerson.BirthDate) {
					LineageDIV += ThisPerson.BirthDate;
					WikiLineageDIV += ThisPerson.BirthDate;
				}
				if (ThisPerson.BirthDate && ThisPerson.BirthLocation) {
					LineageDIV += ", ";
					WikiLineageDIV += ", ";
				}
				if (ThisPerson.BirthLocation) {
					LineageDIV += ThisPerson.BirthLocation;
					WikiLineageDIV += ThisPerson.BirthLocation;
				}
			}



			// var whichCheckBox = "show" + whichType + "TextArea";
			// var whichTextArea =  whichType + "Code";
			
			HTML4Surnames = "&lt;!-- Surname Lineage added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php --&gt;\n" + LineageDIV.replace("<","&lt;").replace(">","&gt;");
			WikiText4Surnames = "&lt;!-- Surname Lineage added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php --&gt;\n" + WikiLineageDIV ;

			LineageDIV += "<br><br><input type=submit class=submit onclick='copyToClipboard(\"Surname\");' value='Copy WikiText'>";

			LineageDIV += "<input type=submit class=submit onclick='copyToClipboard(\"SurnameHTML\");' value='Copy HTML'>";
			LineageDIV += "<input type=checkbox id=showSurnameTextArea value=N style='display:none;'> <input type=checkbox id=showSurnameHTMLTextArea value=N style='display:none;'> "

			LineageDIV += "<BR><textarea style='display:none;' id=SurnameCode cols=80 rows=10>" + WikiText4Surnames + "</textarea>";
			LineageDIV += "<textarea style='display:none;' id=SurnameHTMLCode cols=80 rows=10>" + HTML4Surnames + "</textarea>";

			document.getElementById('FloatingLineage').innerHTML = CloseButton + LineageDIV;
			document.getElementById('FloatingLineage').style.display = 'block';
		}

		function closeDIV(divName) {
			document.getElementById(divName).style.display = 'none';	
		}

		function formatLong( fullEntry ) {
			// alert("formatLong : " + fullEntry + " is "  + fullEntry.length);
			var surname = fullEntry[0];
			var surnameLink = surname;
			var FirstPerson = fullEntry[1];
			var LastPerson = fullEntry[2];
			var AltSurnames = fullEntry[3];
			var NumGens = fullEntry[4];
			var AltClrNum = fullEntry[5];

			const FIRSTNAME = 0;
			const WikiTreeUserName = 1;
			const BIRTHDATE = 2;
			const LOCATION = 3;
			const COLOURNUM = 4;
			const LISTOFAHNUMS = 4;

			var startSpan = "<span ";
			var endSpan = "</span>";

			
			//Peters,[ ,Peters-5165, , ,1] ,[object Object],,7 is 5

			if (FirstPerson.length > 0) {
				theColourNum = FirstPerson[COLOURNUM];
				WikiProfileLinkFirst = "<A target=_blank href=https://www.wikitree.com/wiki/" + FirstPerson[WikiTreeUserName].replace(/ /g, "%20").replace(/'/g, "&#39;") +   ">" +   FirstPerson[FIRSTNAME]  + "</A>";
				PedigreeLink = "<A target=_blank href=https://www.wikitree.com/genealogy/" + FirstPerson[WikiTreeUserName].replace(/ /g, "%20").replace(/'/g, "&#39;").replace("-","-Family-Tree-") +   ">" + "<IMG SRC=https://www.wikitree.com/images/icons/pedigree.gif>"   + "</A>";

				 if(FirstPerson[BIRTHDATE] ){	
				 	FirstPersonInfo =  ", b. " + FirstPerson[BIRTHDATE].substr(0,4) + ", " + FirstPerson[LOCATION]  ;
				 }

				 surnameLink = "<A onclick=viewListWithSurname('" + FirstPerson[WikiTreeUserName] + "');>"  + surname + "</a>";
			} else {
				surnameLink = surname;
				theColourNum = AltClrNum;
				WikiProfileLinkFirst = '';
				PedigreeLink = '';
				FirstPersonInfo = "";
			}

			if (colourize == 2)  {
				if (theColourNum < 2) {
					startSpan += " style='background-color:#eef;' >";
				} else {
					startSpan += " style='background-color:#fee;' >";
				}
				
			} else if (colourize == 4)  {
				colours4Span = ['#eef','#dfd', '#fed8b1', '#fee'];
				startSpan += " style='background-color:" + colours4Span[ theColourNum ] + ";' >";
				
			} else {
				startSpan += ">";
			}


			if (includeGens) {
				GensText = " (" + NumGens  + ")";
			} else {
				GensText = '';
			}

			
			DescendantsLink = "<A target=_blank href=https://www.wikitree.com/genealogy/" + LastPerson.Name.replace(/ /g, "%20").replace(/'/g, "&#39;").replace("-","-Descendants-") +   ">" + "<IMG SRC=https://www.wikitree.com/images/icons/descendant-link.gif>"   + "</A>";
			WikiProfileLinkLast = "<A target=_blank href=https://www.wikitree.com/wiki/" + LastPerson.Name.replace(/ /g, "%20").replace(/'/g, "&#39;") +   ">"  +   LastPerson.FirstName + " " + LastPerson.LastNameAtBirth  + "</A>";

			if (FirstPerson[WikiTreeUserName] == LastPerson.Name) {
				return startSpan + "<b>"  +  surnameLink +  "</b> "  + endSpan  + " " + AltSurnames + " : " +  WikiProfileLinkFirst + FirstPersonInfo + " " + PedigreeLink   + " "  + DescendantsLink + GensText;
			} else {
			
				return startSpan + "<b>"  +  surnameLink +  "</b> "  + endSpan  + " " + AltSurnames + " : " +  WikiProfileLinkFirst + FirstPersonInfo + " " + PedigreeLink  + " &rarr; " + WikiProfileLinkLast + ", b. " + LastPerson.BirthDate.substr(0,4) + ", " + LastPerson.BirthLocation  + " "  + DescendantsLink + GensText;
			}

			// alert ("s'alright?");
		}

		function formatLongWikiText( fullEntry ) {
			// alert("formatLongWikiText : " + fullEntry + " is "  + fullEntry.length);
			var surname = fullEntry[0];
			var FirstPerson = fullEntry[1];
			var LastPerson = fullEntry[2];
			var AltSurnames = fullEntry[3];
			var NumGens = fullEntry[4];
			var AltClrNum = fullEntry[5];

			const FIRSTNAME = 0;
			const WikiTreeUserName = 1;
			const BIRTHDATE = 2;
			const LOCATION = 3;
			const COLOURNUM = 4;

			let preSpan = "";
			if (allBioLastNames > "") {

				if( allBioLastNames.search(surname) > -1) {
					preSpan = "<font color=black>*</font>";
				} else {
					preSpan = "<font color=white>*</font>";
				} 
			}

			var startSpan = preSpan + "<span ";
			var endSpan = "</span>";

			
			if (AltSurnames > '') {
				AltSurnames = "''" + AltSurnames + "''";
			}

			if (FirstPerson.length > 0) {
				theColourNum = FirstPerson[COLOURNUM];
				safeWikiName = FirstPerson[WikiTreeUserName].replace(/ /g, "%20").replace(/'/g, "&#39;");
				WikiProfileLinkFirst = "[[" +   FirstPerson[WikiTreeUserName]  + "|" + FirstPerson[FIRSTNAME]  + "]]";
				PedigreeLink = "https://www.wikitree.com/images/icons/pedigree.gif [https://www.wikitree.com/genealogy/" + FirstPerson[WikiTreeUserName].replace(/ /g, "%20").replace(/'/g, "&#39;").replace("-","-Family-Tree-") +   " 	&#65719;]";
				if ( FirstPerson[BIRTHDATE] ) {
					FirstPersonInfo =  ", b. " + FirstPerson[BIRTHDATE].substr(0,4) + ", " + FirstPerson[LOCATION]  ;
				}
			} else {
				theColourNum = AltClrNum;
				WikiProfileLinkFirst = '';
				PedigreeLink = '';
				FirstPersonInfo = "";
			}

			if (colourize == 2)  {
				if (theColourNum < 2) {
					startSpan += " style='background-color:#eef;' >";
				} else {
					startSpan += " style='background-color:#fee;' >";
				}
				
			} else if (colourize == 4)  {
				colours4Span = ['#eef','#dfd', '#fed8b1', '#fee'];
				startSpan += " style='background-color:" + colours4Span[ theColourNum ] + ";' >";
				
			} else {
				startSpan += ">";
			}


			if (includeGens) {
				GensText = " (" + NumGens  + ")";
			} else {
				GensText = '';
			}

			safeLastName = LastPerson.Name.replace(/ /g, "%20").replace(/'/g, "&#39;"); 
			DescendantsLink = " https://www.wikitree.com/images/icons/descendant-link.gif [https://www.wikitree.com/genealogy/" + safeLastName.replace("-","-Descendants-") +   " &#8671;]";
			WikiProfileLinkLast = "[[" + LastPerson.Name + "|" +   LastPerson.FirstName + " " + LastPerson.LastNameAtBirth  + "]]";

			if (FirstPerson[WikiTreeUserName] == LastPerson.Name) {
				return "\n:" + startSpan + " '''"  +  surname +  "''' "  + endSpan  + " " + AltSurnames + " : " +  WikiProfileLinkFirst + FirstPersonInfo + " " + PedigreeLink   + " "  + DescendantsLink + GensText;
			} else {
				return "\n:" + startSpan + " '''"  +  surname +  "''' "  + endSpan  + " " + AltSurnames + " : " +  WikiProfileLinkFirst + FirstPersonInfo + " " + PedigreeLink  + " &rarr; " + WikiProfileLinkLast + ", b. " + LastPerson.BirthDate.substr(0,4) + ", " + LastPerson.BirthLocation  + " "  + DescendantsLink + GensText;
			}
		}

		function copyToClipboard( whichType ) {			
			var whichCheckBox = "show" + whichType + "TextArea";
			var whichTextArea =  whichType + "Code";
			var isChecked = (document.getElementById(whichCheckBox).checked == true);

			
			document.getElementById(whichTextArea).style.display = "block";
			
				var newLine = '\n'; 
			  /* Get the text field */
			  var copyText = document.getElementById(whichTextArea);		  
			  /* Select the text field */
			  copyText.select();
			  copyText.setSelectionRange(0, 99999); /*For mobile devices*/
			  var typeOfCode = whichTextArea.substr(0, whichTextArea.length - 4).toUpperCase();
			  /* Copy the text inside the text field */
			  document.execCommand('copy');
			  toggleTextArea(whichType);
			  /* Alert the copied text */

			  if (typeOfCode.search("HTML") > -1 ) {
			  	realTypeOfCode = typeOfCode.substr(0, typeOfCode.search("HTML") );
			  	alertMsg = "HTML code for " + realTypeOfCode + ' SURNAME LIST copied to your clipboard.' + newLine + newLine + 'Use CTRL + V (on a PC) or CMD + V (on a Mac)\nto paste this HTML code into your web page.';
			  } else {

			  	alertMsg =  typeOfCode + ' SURNAME LIST copied to your clipboard.' + newLine + newLine + 'Use CTRL + V (on a PC) or CMD + V (on a Mac)\nto paste this WikiText code into \nyour Profile or into a Free Space page.';
			  }

			  alert (alertMsg);
			  
			}

		function showExtra( YorN ) {
			if (YorN == true) {
				newState = "block";
			} else {
				newState = "none";
			}
			document.getElementById('DetailedExtra').style.display = newState;		  
		}
		function showExtraSort( YorN ) {
			if (YorN == true) {
				newState = "block";
			} else {
				newState = "none";
			}
			document.getElementById('ExtraSort').style.display = newState;		  
		}

		function toggleTextArea( whichType ) {
			var whichCheckBox = "show" + whichType + "TextArea";
			var whichTextArea =  whichType + "Code";
			var isChecked = (document.getElementById(whichCheckBox).checked == true);
			// alert ("TextArea " + whichType + " is " + isChecked);
			if (isChecked) {
				document.getElementById(whichTextArea).style.display = "block";
			} else {
				document.getElementById(whichTextArea).style.display = "none";
			}
		}

		function toggleFAQs() {
			var currentStyle = document.getElementById('FAQs').style.display ;
				// alert (currentStyle);
			if (currentStyle == 'none'){
				document.getElementById('showFAQs').style = 'background-color:orange;';
				document.getElementById('FAQs').style.display = 'block';
			} else {
				document.getElementById('showFAQs').style = 'background-color:#25422d;';
				document.getElementById('FAQs').style.display = 'none';
			}		
				
		}

		var allBioLastNames = "";

		function summarizeResults() {
			// alert("Time to Summarize the Results!");
			var TheColourScheme = "";
			var TheCompactReport = "";
			var TheDetailedReport = "";
			
			document.getElementById('DecisionDIV').style.display = 'none';
			// var isDone = functionConfirm("Do you like Football?", 
			// 	function yes() {  alert("Yes")  }, 
			// 	function no() {  alert("no")   },
			// 	function cancel() {  alert("Cancelled!")   }
			// 	);

			splitGenNumValue = ( document.getElementById('splitGenNum').value);
			subsortColourValue = ( document.getElementById('subsortColour').checked == true);
			showOnlyNewNamesValue = ( document.getElementById('showOnlyNewNames').checked == true);
			showAhnNumsValue = ( document.getElementById('showAhnNums').checked == true);

			WikiText4DetailedList = "<!-- Detailed Surnames added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php -->\n=== My Surnames ===\n:Legend:\n:Surname (''alternate spellings'') : First Person with surname in tree → EKA - Earliest Known Ancestor";
			if (includeGens == 'Y')	{WikiText4DetailedList +=  "\n:( # ) = number of generations from Primary person to EKA";}
			WikiText4DetailedList += "\n:https://www.wikitree.com/images/icons/pedigree.gif	𐂷 = pedigree chart of First Person / https://www.wikitree.com/images/icons/descendant-link.gif ⇟ = descendant list of EKA";
			
			if (colourize == '2' && (sortByColour == 'N' || sortByColour.length > 1) )	{
				ColourLegend = "\n:<span  style='background-color:#eef;' >Paternal</span> vs <span  style='background-color:#fee;' >Maternal</span> ancestors\n";
			} else if (colourize == '4' && (sortByColour == 'N' || sortByColour.length > 1) ) {
				ColourLegend = "\n:<span  style='background-color:#eef;' >Paternal Grandfather</span> , <span style='background-color:#dfd;' >Paternal Grandmother</span> vs <span style='background-color:#fed8b1;' >Maternal Grandfather</span> , <span style='background-color:#fee;' >Maternal Grandmother</span> ancestors\n";
			} else {
				ColourLegend = '';
			}

			// UPDATE the list of ALL BIO LAST NAMES
			allBioLastNames = "";
			for (let index = 2; index < bioIndexArray.length ; index++) {
			    if (bioIndexArray[index]) { 
			        let fullname = bioIndexArray[index]; 
			        let name = fullname.substring(0,fullname.search("-")); 
			        allBioLastNames += name + "|";
			     }
			}


			CompactLegend = "Click on Surname to view EKA - Earliest Known Ancestor";
			if (sortByColour.length > 1) { CompactLegend = "Click on Surname to view Ancestor"; }

			if (allBioLastNames > "") {
				CompactLegend = "* = BioParent surnames<BR><BR>" + CompactLegend;
			}

			WikiText4CompactList = "<!-- Compact Surnames added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php -->\n=== My Surnames ===\n:''" + CompactLegend + "''";
			
			if (sortByColour == 'N'){
				// WikiText4DetailedList += ColourLegend;
				// WikiText4CompactList += ColourLegend;
			}
			

			const COLOURNUM = 4;
				
			// alert("sortByColour:" + sortByColour + "(" + sortByColour.length+ ")\ncolourize:" + colourize)	;
			if (sortByColour == 'N' || sortByColour.length > 1) {

				if (colourize == 2)  {
					TheColourScheme = "<br/><span style='background-color:#eef;' >Paternal</span> vs <span style='background-color:#fee;' >Maternal</span><br/>";
					WikiText4DetailedList += ColourLegend;
					WikiText4CompactList += ColourLegend;
				} else if (colourize == 4)  {
					TheColourScheme = "<br/><span style='background-color:#eef;' >Paternal Grandfather</span> , <span style='background-color:#dfd;' >Paternal Grandmother</span> vs <span style='background-color:#fed8b1;' >Maternal Grandfather</span> , <span style='background-color:#fee;' >Maternal Grandmother</span><br/>";			
					WikiText4DetailedList += ColourLegend;
					WikiText4CompactList += ColourLegend;					
				} else {
					TheColourScheme = "<br/>";
				}
			} else {
				TheColourScheme  = "<br/>";
			}
			// alert("Summary 1");
			surnamesFullEntryList.sort();

			
			// ======= ======
			// COMPACT REPORT
			// ======= ======

				
				TheCompactReport = "<DIV style='background-color:white;' align=left><H2>Compact Surname List</H2><i>" + CompactLegend + "</i>" + TheColourScheme;

				if (sortByColour == 'Y' || sortByColour == 'N'){
					TheCompactReport +=   formatCompactList(surnamesFullEntryList);
				} else {
					TheCompactReport += formatCompactListByGen();
				}

				WikiText4CompactList += "\n<!-- end of Compact Surnames -->";

				TheCompactReport += "</DIV>";
				HTML4CompactList = "&lt;!-- Compact Surnames added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php --&gt;\n" + TheCompactReport.replace("<","&lt;").replace(">","&gt;");
				TheCompactReport += "<HR><input type=submit class=submit onclick='copyToClipboard(\"Compact\");' value='Copy Compact WikiText to Clipboard'>";
				TheCompactReport += "<br><i>then add to Profile or Free Space page</i>";
				TheCompactReport += "<BR><input type=checkbox onclick='toggleTextArea(\"Compact\");' id=showCompactTextArea value=Y> Show WikiText<br/>";
				TheCompactReport += "<BR><textarea style='display:none;' id=CompactCode cols=80 rows=10>" + WikiText4CompactList + "</textarea>";
				TheCompactReport += "<HR><input type=submit class=submit onclick='copyToClipboard(\"CompactHTML\");' value='Copy HTML'>";
				TheCompactReport += "<br><i>then add to web page</i>";
				TheCompactReport += "<BR><input type=checkbox onclick='toggleTextArea(\"CompactHTML\");' id=showCompactHTMLTextArea value=Y> Show HTML<br/>";
				TheCompactReport += "<BR><textarea style='display:none;' id=CompactHTMLCode cols=80 rows=10>" + HTML4CompactList + "</textarea>";
				
			
			
			// ======== ======
			// DETAILED REPORT
			// ======== ======

				TheDetailedReport = "<DIV style='background-color:white;' align=left><H2>Detailed Surname List</H2><I>Click on Surname to view Lineage List</I><br>";

				DetailedLegend = "<U>Legend</u>:<br>Surname (alternates) : First Person with surname in tree " + " &rarr; " + " EKA - Earliest Known Ancestor ";
				DetailedLegend2 = "";
				if (sortByColour.length > 1) { 
					DetailedLegend = "Click on First Name to view Ancestor";
					DetailedLegend2 = "<br>" + "<IMG SRC=https://www.wikitree.com/images/icons/pedigree.gif>"  + " = Pedigree chart , "  + "<IMG SRC=https://www.wikitree.com/images/icons/descendant-link.gif>"+ " = Descendants list"; 
				} else {
					if (includeGens) {
						DetailedLegend += "<br/>( # ) = number of generations from Primary person to EKA";
					}

				}
				if (allBioLastNames > "") {
					DetailedLegend += "<br>* = BioParent surnames<BR>";
				}


				TheDetailedReport +=  "<I>" + DetailedLegend + "</I>" + DetailedLegend2 + TheColourScheme;

				if (sortByColour == 'Y') {
					numCCiterations = 4;
				} else if (sortByColour.length > 1) {
					numCCiterations = 0;
					TheDetailedReport += formatDetailedListByGen();
				} else {
					numCCiterations = 1;
				}

				// alert("Summary 2A");
				for (var cc = 0; cc < numCCiterations; cc++) {
					// alert ("cc = " + cc);
					if (colourize == '2' && sortByColour == 'Y' && (cc % 2 == 0) ) {

						ColourLegendArray = ["<span style='background-color:#eef;' >Paternal</span> ancestors","","<span style='background-color:#fee;' >Maternal</span> ancestors" , "" ];
						if (ColourLegendArray[cc] > ''){
							TheDetailedReport += "<br/><br/>" + ColourLegendArray[cc] + "<br/>";
							WikiText4DetailedList += "\n:\n*" + ColourLegendArray[cc] + "\n:";
						}

					} else if (colourize == '4' && sortByColour == 'Y') {
						ColourLegendArray = ["<span style='background-color:#eef;' >Paternal Grandfather</span> ancestors","<span style='background-color:#dfd;' >Paternal Grandmother</span> ancestors", "<span style='background-color:#fed8b1;' >Maternal Grandfather</span> ancestors", "<span style='background-color:#fee;' >Maternal Grandmother</span> ancestors" , "" ];
						TheDetailedReport += "<br/><br/>" + ColourLegendArray[cc] + "<br/>";
						WikiText4DetailedList += "\n:\n*" + ColourLegendArray[cc] + "\n:";
					}
					// alert ("cc B = " + cc + " trying : " + surnamesFullEntryList.length);
					for (var i = 0; i < surnamesFullEntryList.length; i++) {
						if (allBioLastNames.search(surnamesFullEntryList[i][0])>0) {
							surnamesFullEntryList[i][6] = true;
						}
					
						if (surnamesFullEntryList[i][1].length > 0 ) {
							theNewClrNum =  surnamesFullEntryList[i][1][COLOURNUM];
						} else {
							theNewClrNum = surnamesFullEntryList[i][5];
						}

						if (numCCiterations > 1){
							if (colourize == 'N') {theNewClrNum = 0;}
							else if (colourize == '2' && theNewClrNum < 2) {theNewClrNum = 0;}
							else if (colourize == '2' && theNewClrNum >= 2) {theNewClrNum = 2;}
	
							if (theNewClrNum != cc) { continue; }
						}
						TheDetailedReport +=  "<BR/>" + "<font color=" + (surnamesFullEntryList[i][6] ? "black" :"white") + ">*</font> "+ formatLong(surnamesFullEntryList[i]);
						WikiText4DetailedList +=  formatLongWikiText(surnamesFullEntryList[i]);
					}
					// alert ("cc C = " + cc);
				}
				// alert("Summary 2B");
				WikiText4DetailedList += "\n<!-- end of Detailed Surnames -->";
				TheDetailedReport += "</DIV>";

				HTML4DetailedList = "&lt;!-- Detailed Surnames added by Surnames Generator app @ https://apps.wikitree.com/apps/clarke11007/surnames.php --&gt;\n" + TheDetailedReport.replace("<I>Click on Surname to view Lineage List</I><br>","").replace("<","&lt;").replace(">","&gt;");
				TheDetailedReport += "<HR><input type=submit class=submit onclick='copyToClipboard(\"Detailed\");' value='Copy Detailed WikiText to Clipboard'>";
				TheDetailedReport += "<br><i>then add to Profile or Free Space page</i>";
				TheDetailedReport += "<BR><input type=checkbox onclick='toggleTextArea(\"Detailed\");' id=showDetailedTextArea value=Y> Show WikiText<br/>";
				TheDetailedReport += "<BR><textarea style='display:none;' id=DetailedCode cols=80 rows=10>" + WikiText4DetailedList + "</textarea>";
				TheDetailedReport += "<HR><input type=submit class=submit onclick='copyToClipboard(\"DetailedHTML\");' value='Copy HTML'>";
				TheDetailedReport += "<br><i>then add to web page</i>";
				TheDetailedReport += "<BR><input type=checkbox onclick='toggleTextArea(\"DetailedHTML\");' id=showDetailedHTMLTextArea value=Y> Show HTML<br/>";
				TheDetailedReport += "<BR><textarea style='display:none;' id=DetailedHTMLCode cols=80 rows=10>" + HTML4DetailedList + "</textarea>";
				// alert("Summary 2C");
			
			// Old News
			// if (listType == 'B') { breaks = "<br/><br/>";} else {breaks = "";}
			// var TheReport = "<TABLE border=0><tr><td align=left  style='background-color:white;'>" + TheCompactReport + breaks + TheDetailedReport + "</td></tr></table>";


			// alert("Ready to populate the pieces ...");
			document.getElementById('SurnamesFoundReport').innerHTML = "";
			document.getElementById('TabHolder').style.display = "block";
			document.getElementById('CompactContent').innerHTML = TheCompactReport;
			document.getElementById('DetailedContent').innerHTML = TheDetailedReport;
			// alert ("... and ... Scene ")

		}

		// var theStartTime = null;
		// var theEndTime = null;

		function checkIfAncestorLooksupsAreDone(doneNow = false) {
			theEndTime = new Date();
			var sinceSeconds = (theEndTime - theRecentTime)/1000;
			if (numInvestigations <= 0 || doneNow == true) {
				// alert("Yay - Mom and Dad are both done with their homework.");
				clearInterval(myInterval); // stop the clock
				var totalSeconds = (theEndTime - theStartTime)/1000;

				// Assemble the Stats
				var totalMinutes = Math.floor(totalSeconds / 60);
				var netSeconds = totalSeconds - 60*totalMinutes;
				timeElapsed = "";
				if (totalMinutes > 0) { 
					timeElapsed = totalMinutes + " minute";
					if (totalMinutes > 1) { 	timeElapsed += "s "; } else { timeElapsed += " ";}
				}
				timeElapsed += netSeconds + " seconds";

				var searchParms = "Search for surnames up to Generation # " + maxGens + "<br>" + (2**(maxGens-1)) + " maximum possible, assuming full tree, all unique";
				var numNamesFound = surnamesFullEntryList.length + " surnames found ( " + Math.round(100*surnamesFullEntryList.length/(2**(maxGens-1))) + "% )";
				var deepestGenReport = "";
				if (numAtDeepestGen>0) {
					deepestGenReport = "Deepest dive: " + numAtDeepestGen + " ancestor" + (numAtDeepestGen > 1 ? "s" : "") + " found @ " + deepestGen + " generations back";}
				var oldestReport = "";
				if (oldestAnc > "") {
					oldestReport = "oldest ancestor = " + oldestAnc;}
				var theStats = "<b>Surnames for " + ThePrimaryPersonName + "</b></br>" +  searchParms + "<br/>" + "Time elapsed: " +  timeElapsed + "<br/>" + numNamesFound  + "<br/>" + deepestGenReport + "<br/>" + oldestReport ;


				if (debugNow == 'Y') {
				// 	theStats += "<br>" + TheNotes;
					// alert (TheNotes);
				}

				if (TheNotes > '') {
					theStats += "<br/>See notes at the bottom";
				}
					
				thisListNote = "<br><br>This list was generated using the ";
				if (ask=="Y") {
					notAsk = 'N';
					thisMode = 'Manual';
					otherMode = 'Automatic';
				 ReDoURL = "<br/><a href=?ph=1&id=" + ID + "&maxGens=" + maxGens + "&ask=Y>Redo Manual Surname mode</a>";
				} else {
					notAsk = 'Y';
					thisMode = 'Automatic';
					otherMode = 'Manual';
					ReDoURL = "";
				}
				var ReGenURLstarter = "<a href=?ph=1&id=" + ID + "&maxGens=" + maxGens + "&ask=" + notAsk + ">";
				thisListNote += thisMode + " Surname mode.  If you prefer, you can re-generate the list using " + ReGenURLstarter + otherMode + " Surname mode</a>." + ReDoURL;

				if (debugNow) {TheNotes += "<br><h2>Debug Notes</h2>" + TheDebugNotes  ;}
				document.getElementById('TheNotesP').innerHTML = thisListNote + "<br><br>" + TheNotes;


				document.getElementById('Prompts').innerHTML =   "<FORM><input type=hidden name=ph value=0><input type=submit  class=submit value='Reset'></FORM>" + "<br/>" + theStats;// "<HR><H1>ALL DONE !</H1>";
				summarizeResults();
			} else {
				randomMsgs = [ "Looking for surnames ....", "Still looking ...","I think I've found one ....", "Here's one ! ...", "Here's another one ...", "Look - there's one there! ...", "Yet another one ...","Still searching ...", "Better dig some more ...", "Almost there ?  ... ", "Maybe ...", "OK - here's one ...", "Et voilà! ...", "Encore une fois ...", "Still more ...", "Not done yet ..." , "Hey, I'm still thinking here ...", "I'm walking here ... ", "Just taking a wee CPU break ... back after a byte ... ", "What's a few nanoseconds between friends? ..." , "I'll be back ...", "Learning from the past ...", "Making a brighter future ...", "Learn from the past to make a brighter future ...", "Hey kids, get out of that Jello tree!", "Hasta la vista, baby! ...", "Mind the gap ...", "Be cool ... ", "At ease ...", "Always look on the bright side of life ..."];

				whichMsgNum = Math.floor(randomMsgs.length  * Math.random() );
				var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' , hour:"2-digit", minute:"2-digit", second:"2-digit"};

				theTime = new Date().toLocaleDateString("en-US", options);
				whichMsg = randomMsgs [whichMsgNum ] + "<br>" + surnamesFullEntryList.length + "/" + ( 2 ** maxGens) + "<br/>" +  theTime ;
				if (debugNow == 'Y'){
					 whichMsg  += "<br/>investigating " +  numInvestigations + " surnames ..." ;
				}
				document.getElementById('Prompts').innerHTML =  whichMsg /*+ " " + whichMsgNum + " " + numInvestigations */;

				if (sinceSeconds > 10) {
					document.getElementById('TheNotesP').innerHTML = "<input type=submit class=submit value='DONE' onclick='checkIfAncestorLooksupsAreDone(true);' >";	
				}
				if (debugNow == 'Y'){
					document.getElementById('TheNotesP').innerHTML = TheNotes + "<br>" + "<input type=submit class=submit value='DONE' onclick='checkIfAncestorLooksupsAreDone(true);' >"  + "<br>" + TheDebugNotes ;	
				}
			}
		}

		function investigateGrandparents (ThePerson, gen, clrNum , ahnNum ) {  //2 0 2  vs  2 2 3

			var newClrNum = clrNum + 1;


			

			


			if (ThePerson.Father > 0) {
				numInvestigations += 1;
				TheDebugNotes += "NumInc!  investigateGrandparents - GRAMPS<br>";
				theRecentTime = new Date();
				TheDebugNotes += theRecentTime + " : " + "Grandfather " + clrNum + "<br>";
				var request = $.ajax({
					url: API_URL,
					crossDomain: true,
					xhrFields: { withCredentials: true },
					type: 'POST',
					dataType: 'json',
					
					
					data: { 'action': 'getPeople', 'appId': theAppID ,'keys': ThePerson.Father ,'ancestors':1, 'fields': "Id,Name,FirstName,LastNameAtBirth,BirthDate,BirthLocation,Father,Mother,Gender,BioMother,BioFather,DataStatus", 'format': 'json' },

					// On successful POST return, check our data. Note from that data whether the login itself was
					// successful (setting session cookies if so). Call the user callback function when done.
					success: function(data) { 
						// alert("Success - in ready function!");						 
						var theJason = JSON.stringify(data);
						// alert(theJason);
						if (data[0]) {
							// alert ("There is data");

							let thePerson0ID = ThePerson.Father;
							if (data[0].resultByKey && data[0].resultByKey[thePerson0ID]) {
								thePerson0ID = data[0].resultByKey[thePerson0ID].Id;
							}

							if (data[0].people) {
								var Person0 = data[0].people[thePerson0ID];
								var Person1 = null;
								var Person2 = null;

								var ThePerson = Person0;
								var TheFather = null;
								var TheMother = null;

								if (Person0 && Person0.Father && data[0].people[Person0.Father]) {
									TheFather = data[0].people[Person0.Father];
								}
								if (Person0 && Person0.Mother && data[0].people[Person0.Mother]) {
									TheMother = data[0].people[Person0.Mother];
								}

							// alert ("in the people ...");
								var PersonParent = TheFather;

								if (PersonParent) {
									if (PersonParent.Name) {
										personArray[ PersonParent.Name ] = PersonParent;
										ahnenIndexArray[ 2*ahnNum ] = PersonParent.Name ;
									} else {
										ahnenIndexArray[ 2*ahnNum ] = "Private";
									}	
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum) + " = " + ahnenIndexArray[ 2*ahnNum ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum) + " NOT ASSIGNED !!!! "  ;}
								}

								investigateSurname(PersonParent, gen + 1, clrNum, [] , 2*ahnNum);
							}
						}
						numInvestigations -= 1;
						TheDebugNotes += "NumDec!<br>";
					},
					// On failed POST/server error, act like a failed login.
					error: function(xhr, status) { 
						// alert('OOPS - I missed this time! - ' + status);
						numInvestigations -= 1;
						TheDebugNotes += "NumDec!<br>";
					}
				});  // end var request";
			}

			if (ThePerson.Mother > 0) {
				numInvestigations += 1;
				
				TheDebugNotes += "NumInc! investigateGrandparents - GRAMMA <br>";
				theRecentTime = new Date();
				TheDebugNotes += theRecentTime + " : " + "Grandmother " + newClrNum + "<br>";
				// if (clrNum == 0 && gen == 1) {
				// 	newClrNum = 1;
				// } else if (clrNum == 2  && gen == 1) {
				// 	newClrNum = 3;
				// } else {
				// 	newClrNum = clrNum;
				// 	// if (gen < 3) {alert ("NO CHANGE in newClrNum : " + newClrNum);}
				// }
				// var newClrNum = clrNum + 1;

				var request = $.ajax({
					url: API_URL,
					crossDomain: true,
					xhrFields: { withCredentials: true },
					type: 'POST',
					dataType: 'json',
					
					
					data: { 'action': 'getPeople', 'appId': theAppID , 'keys': ThePerson.Mother ,'ancestors':1, 'fields': "Id,Name,FirstName,LastNameAtBirth,BirthDate,BirthLocation,Father,Mother,Gender,BioMother,BioFather", 'format': 'json' },

					// On successful POST return, check our data. Note from that data whether the login itself was
					// successful (setting session cookies if so). Call the user callback function when done.
					success: function(data) { 
						// alert("Success - in ready function!");						 
						var theJason = JSON.stringify(data);
						// alert(theJason);
						if (data[0]) {
							// alert ("There is data");
							let thePerson0ID = ThePerson.Mother;
							if (data[0].resultByKey && data[0].resultByKey[thePerson0ID]) {
								thePerson0ID = data[0].resultByKey[thePerson0ID].Id;
							}
							if (data[0].people) {
							// alert ("in the ancestors ...");
								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 987");}
								var Person0 = data[0].people[thePerson0ID];
								var Person1 = null;
								var Person2 = null;

								var TheSelf = Person0;
								var TheFather = null;
								var TheMother = null;

								if (Person0 && Person0.Father && data[0].people[Person0.Father]) {
									TheFather = data[0].people[Person0.Father];
								}
								if (Person0 && Person0.Mother && data[0].people[Person0.Mother]) {
									TheMother = data[0].people[Person0.Mother];
								}
							// alert ("in the people ...");
								var PersonParent = TheMother;

								if (PersonParent) {
									if (PersonParent.Name) {
										personArray[ PersonParent.Name ] = PersonParent;
										ahnenIndexArray[ 1 + 2*ahnNum ] = PersonParent.Name ;
									} else {
										ahnenIndexArray[ 1 + 2*ahnNum ] = "Private";
									}	
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum + 1) + " = " + ahnenIndexArray[ 2*ahnNum + 1 ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum) + " NOT ASSIGNED !!!! "  ;}
								}

								investigateSurname(PersonParent, gen + 1, newClrNum, [], 2*ahnNum + 1);
							}
						}
						numInvestigations -= 1;
						TheDebugNotes += "NumDec!<br>";
					},
					// On failed POST/server error, act like a failed login.
					error: function(xhr, status) { 
						// alert('OOPS - I missed this time! - ' + status);
						numInvestigations -= 1;
						TheDebugNotes += "NumDec!<br>";
					}
				});  // end var request";
			}
		}

		function getAltSurnames(currentList) {
			AltSurnames = "";
			if (currentList.length > 2) {
				AltSurnames = " (";
				for (var i  = 2; i <= currentList.length - 1; i++) {
					if (i>2) {AltSurnames += ", ";}
					AltSurnames += currentList[i];
				}
				AltSurnames += ") ";
			} 
			return AltSurnames;	
		}

		 
		function makeDecisionTime(TheKid, TheDad, TheMom, currentList) {
			var DIVtext = "<DIV id='D-" + TheKid.Name + "'> <br><br>";
			if (TheKid.Name == theDebugID) {alert("in makeDecisionTime  - line 867" );}
			AltSurnames = getAltSurnames(currentList);
			if (TheKid.Name == theDebugID) {alert("in makeDecisionTime  - line 869 - " + TheDad.Name + " / " + TheMom.Name );}
			var TheKidDeets = "\nResearching the <b>" + currentList[1] + "</b> <i>" + AltSurnames + "</i>line:<br>" + TheKid.FirstName + " " +  TheKid.LastNameAtBirth + "<br> b. " + TheKid.BirthDate ;
			var TheDadDeets = "NO FATHER";
			var TheMomDeets = "NO MOTHER";
			if (TheKid.Name == theDebugID) {alert("in makeDecisionTime  - line 871");}
			if (TheDad) { TheDadDeets = "<b>" + TheDad.FirstName + " " +  TheDad.LastNameAtBirth + "</b><br><i>b. " + TheDad.BirthDate + ", " + TheDad.BirthLocation + "</i>";}
			if (TheKid.Name == theDebugID) {alert("in makeDecisionTime  - line 873");}
			if (TheMom) {TheMomDeets = "<b>" + TheMom.FirstName + " " +  TheMom.LastNameAtBirth + "</b><br><i>b. " + TheMom.BirthDate + ", " + TheMom.BirthLocation + "</i>";}
			if (TheKid.Name == theDebugID) {alert("in makeDecisionTime  - line 875");}

			
			//DIVtext += "<table border=0 align=center  style='padding:10px; background-color: yellow;'><tr><td colspan=2 align=center>" + TheKidDeets + "<br>&nbsp;</td></tr>" + "<tr><td align=center>FATHER:<br>" + TheDadDeets + "</td><td align=center>MOTHER:<br>" + TheMomDeets + "</td></tr>" + "<tr><td align=center><input type=submit onclick='registerDecision(\"" + TheKid.Name + "|F\");' class=submit value='Follow FATHER'></td><td align=center><input type=submit onclick='registerDecision(\"" + TheKid.Name + "|M\");' class=submit value='Follow MOTHER'></td></tr>" + "<tr><td align=center colspan=2>&nbsp;<br/><input type=submit onclick='registerDecision(\"" + TheKid.Name + "|N\");' class=submit value='Follow Neither - Start 2 New Lines'></td></tr></table></DIV>";
			DLGtext = TheKidDeets + "\n" + "[F]ATHER: " + TheDadDeets + "\n[M]OTHER: " + TheMomDeets + "\nWhich line to follow? Enter [F]ather, [M]other, or [N]either :" ;
			if (TheKid.Name == theDebugID) {alert("in makeDecisionTime  - line 880");}

			var isDone = functionConfirm(DLGtext, 
										function yes() {  alert("FATHER chosen")  }, 
										function no() {  alert("MOTHER chosen")   },
										function cancel() {  alert("NEITHER chosen!")   }
										);



			var person = prompt(DLGtext, "");

			if (person.toUpperCase().indexOf("F") > -1 ) {
				return "F";
			} else if (person.toUpperCase().indexOf("M") > -1 ) {
				return "M"; 
			} else {
				return "N"; 
			} 
			
		}

		
		function registerDecision( nameAndResponse ) {
			// alert (nameAndResponse);
			// alert ( nameAndResponse.search('~') );
			var theWikiID = nameAndResponse.substr(0, nameAndResponse.search("~") );
			var theDIVname = "D-" + theWikiID;
			var theResponse =  nameAndResponse.substr( nameAndResponse.search("~")  + 1);
			if (theResponse == 'N'){
						// alert ("NAME: " + theDIVname + '\n' + "RESP: " + theResponse + '\n' + "theWikiID: " + theWikiID);
					}
			document.getElementById(theDIVname).style.display = 'none';
			foundWikiID = false;
			for (var i = 0; i < decisionsArray.length; i++) {
				if ( decisionsArray[i][0] == theWikiID ) {
					foundWikiID = true;
					// doneDecisionTime(theResponse , TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum);
					doneDecisionTime(theResponse , decisionsArray[i][1],decisionsArray[i][2],decisionsArray[i][3],decisionsArray[i][4],decisionsArray[i][5],decisionsArray[i][6],decisionsArray[i][7],decisionsArray[i][8],decisionsArray[i][9],decisionsArray[i][10] );
				}
			}
			if (foundWikiID == false) {
				alert ("Could NOT find the WikiID: " + theWikiID);
			}

		}

		function doneDecisionTime(whoFollows , TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum) {

			if (whoFollows == "M") {
				if (TheMother) {
					FollowLinePerson = TheMother;
					TheNotes +=  TheSelf.LastNameAtBirth  + ": You chose to follow the Mother's line - from " +   TheSelf.FirstName + " " + TheSelf.LastNameAtBirth + " [" + TheSelf.Name + "]  -> " + TheMother.FirstName + " " + TheMother.LastNameAtBirth + " [" + TheMother.Name + "]  .<br/>";
				}
				if (TheFather){
					NewLinePerson = TheFather;
				}

			} else if (whoFollows == "F") {
				if (TheFather){
					FollowLinePerson = TheFather;
					TheNotes +=  TheSelf.LastNameAtBirth  + ": You chose to follow the Father's line - from " +   TheSelf.FirstName + " " + TheSelf.LastNameAtBirth + " [" + TheSelf.Name + "]  -> " + TheFather.FirstName + " " + TheFather.LastNameAtBirth + " [" + TheFather.Name + "]  .<br/>";
				}

				if (TheMother) {
					NewLinePerson = TheMother;
				}

			} else if (whoFollows == "N") {
				TheNotes +=  TheSelf.LastNameAtBirth  + ": From "  +   TheSelf.FirstName + " " + TheSelf.LastNameAtBirth + " [" + TheSelf.Name + "]  -> You chose to follow new lines - ";//
				if (TheFather) {
					NewLinePerson = TheFather;
				 	TheNotes +=   TheFather.FirstName + " " + TheFather.LastNameAtBirth + " [" + TheFather.Name + "]  -  ";
				 	AddToReportAnotherEndOfLine (TheSelf, currentList, gen, true, ahnNum);
				}

				if (TheMother){
					NewLinePerson2 = TheMother;
				 	TheNotes += TheMother.FirstName + " " + TheMother.LastNameAtBirth + " [" + TheMother.Name + "] ";
				}
				TheNotes +=    " .<br/>";
			}

			// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1077");}

			if (!NewLinePerson) {
				// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1080");}
				AddToReportAnotherEndOfLine (TheSelf, currentList, gen, true, ahnNum);
			}

			FinishUpFollowThrough( TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum, ahnORbio);

		}

		function AddToDecisionArray( TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum) {
			var safeName = TheSelf.Name.replace(/ /g,"_");
			decisionsArray.push ( [safeName,TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum, ""]);
			whoFollows = makeDecisionTime2(TheSelf, TheFather, TheMother, currentList) ;

			
		}

		function makeDecisionTime2(TheKid, TheDad, TheMom, currentList) {
			var safeKidName = TheKid.Name.replace(/ /g,"_");
			var DIVtext = "<DIV id=\"D-" + safeKidName + "\"> <br><br>";
			if (safeKidName == theDebugID) {alert("in makeDecisionTime  - line 867" );}
			AltSurnames = getAltSurnames(currentList);
			if (safeKidName == theDebugID) {alert("in makeDecisionTime  - line 869 - " + TheDad.Name + " / " + TheMom.Name );}
			var TheKidDeets = "\nResearching the <b>" + currentList[1] + "</b> <i>" + AltSurnames + "</i>line from:<br><br><a target=_blank href=https://www.wikitree.com/wiki/" + safeKidName + ">" + TheKid.FirstName + " " +  TheKid.LastNameAtBirth + "</a><br>b. " + TheKid.BirthDate ;
			var TheDadDeets = "NO FATHER";
			var TheMomDeets = "NO MOTHER";
			// if (safeKidName == theDebugID) {alert("in makeDecisionTime  - line 871");}
			// if (TheDad) { TheDadDeets = TheDad.FirstName + " " +  TheDad.LastNameAtBirth + ", b. " + TheDad.BirthDate + ", " + TheDad.BirthLocation;}
			// if (safeKidName == theDebugID) {alert("in makeDecisionTime  - line 873");}
			// if (TheMom) {TheMomDeets = TheMom.FirstName + " " +  TheMom.LastNameAtBirth + ", b. " + TheMom.BirthDate + ", " + TheMom.BirthLocation;}
			// if (safeKidName == theDebugID) {alert("in makeDecisionTime  - line 875");}
			if (TheDad) { TheDadDeets = "<b>" + TheDad.FirstName + " " +  TheDad.LastNameAtBirth + "</b><br><i>b. " + TheDad.BirthDate + "<BR>" + TheDad.BirthLocation + "</i>&nbsp;&nbsp;";}

			if (TheMom) {TheMomDeets = "<b>" + TheMom.FirstName + " " +  TheMom.LastNameAtBirth + "</b><br><i>b. " + TheMom.BirthDate + "<BR>&nbsp;&nbsp;" + TheMom.BirthLocation + "</i>";}

			
			DIVtext += "<table border=0 align=center  style='padding:10px; background-color: yellow;'><tr><td colspan=2 align=center>" + TheKidDeets + "<br>&nbsp;</td></tr>" + "<tr><td align=center>FATHER:<br>" + TheDadDeets + "</td><td align=center>MOTHER:<br>" + TheMomDeets + "</td></tr>" + "<tr><td align=center><input type=submit onclick=registerDecision(\"" + safeKidName + "~F\"); class=submit value='Follow FATHER'></td><td align=center><input type=submit onclick=registerDecision(\"" + safeKidName + "~M\"); class=submit value='Follow MOTHER'></td></tr>" + "<tr><td align=center colspan=2>&nbsp;<br/><input type=submit onclick=registerDecision(\"" + safeKidName + "~N\"); class=submit value='Follow Neither - Start 2 New Lines'></td></tr></table></DIV>";
			// DLGtext = TheKidDeets + "\n" + "[F]ATHER: " + TheDadDeets + "\n[M]OTHER: " + TheMomDeets + "\nWhich line to follow? Enter [F]ather, [M]other, or [N]either :" ;
			if (safeKidName == theDebugID) {alert("in makeDecisionTime  - line 880");}

			// var isDone = functionConfirm(DLGtext, 
			// 							function yes() {  alert("FATHER chosen")  }, 
			// 							function no() {  alert("MOTHER chosen")   },
			// 							function cancel() {  alert("NEITHER chosen!")   }
			// 							);

			document.getElementById('DecisionDIV').innerHTML += DIVtext;
			document.getElementById('DecisionDIV').style.display = 'block';

			var person = 'F';//prompt(DLGtext, "");

			if (person.toUpperCase().indexOf("F") > -1 ) {
				return "F";
			} else if (person.toUpperCase().indexOf("M") > -1 ) {
				return "M"; 
			} else {
				return "N"; 
			} 
			
		}

		function ThePersonDeets( ThePerson) {
			// if (ThePerson.Name == theDebugIDsDad) {alert("Success - in ThePersonDeets function! - line 896");}
			var ThePersonFirstName = "";
			var ThePersonLastNameAtBirth = "";
			var ThePersonBirthDate = "";
			var ThePersonBirthLocation = "";
			// if (ThePerson.Name == theDebugIDsDad) {alert("Success - in ThePersonDeets function! - line 901");}
			if (ThePerson.FirstName) {ThePersonFirstName  = ThePerson.FirstName ; }
			if (ThePerson.LastNameAtBirth) {ThePersonLastNameAtBirth  = ThePerson.LastNameAtBirth ; }
			if (ThePerson.BirthDate) {ThePersonBirthDate  = ThePerson.BirthDate.substr(0,4) ; }
			if (ThePerson.BirthLocation) {ThePersonBirthLocation  = ThePerson.BirthLocation;}

			// if (ThePerson.Name == theDebugIDsDad) {alert("Success - in ThePersonDeets function! - line 907");}

			var TheDeets = ThePersonFirstName + " " + ThePersonLastNameAtBirth + ", b. " + ThePersonBirthDate + ", " + ThePersonBirthLocation;
			// if (ThePerson.Name == theDebugIDsDad) {alert("Success - in ThePersonDeets function! - line 908");}
			// if (ThePerson.Name == theDebugIDsDad) {alert("TheDeets = " + TheDeets);}
			return TheDeets;
		}

		function TheCurrentDeets( currentList) {
			var theDeets = "";
			if (currentList){
				if (currentList[0]) {
					if (currentList[0][0]) {
						theDeets += currentList[0][0];
					}
					if (currentList[0][2]) {
						theDeets += ", b. " + currentList[0][2].substr(0,4);
					}
					if (currentList[0][3]) {
						theDeets += ", " + currentList[0][3];
					}
				}
			}
			return theDeets;
		}

		function AddToReportAnotherEndOfLine (ThePersonAtEndOfLine,  currentList, gen, multiGens = true, ahnNum = 0) {
			TheDebugNotes += "Reporting on : " + ThePersonAtEndOfLine.Name + " - BEGIN<br>";

			if (gen > deepestGen) {
				deepestGen = gen;
				numAtDeepestGen = 1;
			} else if (gen == deepestGen) {
				numAtDeepestGen += 1;
			}

			if (ahnNum > 0 && currentList[0] && currentList[0][5] && currentList[0][5].indexOf(ahnNum) == -1) {
				currentList[0][5].push(ahnNum);
			} else if (ahnNum == 15) {
				// alert ("NOT adding " + ahnNum + " to current List : " + currentList[0][5]);
			}

			if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 896");}

			let isInBioList = false;
			if (allBioLastNames.search(currentList[1]) > -1) {
				isInBioList = true;
			}
			if (multiGens == true) {
				if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 898");}
				// END OF LINE after MULTIPLE GENERATIONS FOLLOWING THE SAME SURNAME  ... brick wall buddy
				TheNextReport = currentList[1];
				if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 901");}
				AltSurnames = "";
				if (currentList.length > 2) {
					if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 904");}
					AltSurnames = " (";
					for (var i  = 2; i <= currentList.length - 1; i++) {
						if (i>2) {AltSurnames += ", ";}
						AltSurnames += currentList[i];
					}
					AltSurnames += ") ";
					TheNextReport += AltSurnames;
				} 
				if (ThePersonAtEndOfLine.Name == theDebugID) {
					alert("Success - in AddToReportAnotherEndOfLine function! - line 913");
					alert ( "TheCurrentDeets = " + TheCurrentDeets(currentList) );
					alert ( "currentList = " + currentList);
					alert ( "currentList.length = " + currentList.length);
					alert ( "ThePersonAtEndOfLine = " + (ThePersonAtEndOfLine) );
					alert ( "ThePersonDeets = " + ThePersonDeets(ThePersonAtEndOfLine) );
				}
				TheNextReport +=  " : " + TheCurrentDeets(currentList) +  " &rarr; " + ThePersonDeets(ThePersonAtEndOfLine) + " (" + gen + ")<BR>";
				if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 915");}
				surnamesFullEntryList.push([ currentList[1], currentList[0] , ThePersonAtEndOfLine , AltSurnames, gen , newClrNum, isInBioList]); 
				if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 917");}
				// Original Surname, Details about Initial Person with surname , Final person with surname
				document.getElementById('SurnamesFoundReport').innerHTML += TheNextReport;				

				if (ThePersonAtEndOfLine &&  ThePersonAtEndOfLine.BirthDate && ThePersonAtEndOfLine.BirthDate.substr(0,4) != '0000' && ThePersonAtEndOfLine.BirthDate.substr(0,4) < oldestAncBDate) {
					oldestAncBDate = ThePersonAtEndOfLine.BirthDate.substr(0,4);
					oldestAnc = ThePersonAtEndOfLine.FirstName + " " + ThePersonAtEndOfLine.LastNameAtBirth + ", b. " + ThePersonAtEndOfLine.BirthDate.substr(0,4) + ", " + ThePersonAtEndOfLine.BirthLocation  + " (" + gen + " gens)";
				}	

			} else {
				 // END OF LINE - SOLO EFFECT - PETERED OUT AFTER ONLY ONE ANCESTOR ... sad ... Make Ancestors Great Again !
				 if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 924");}
				TheNextReport = " " + currentList[1];
				TheNextReport +=  " : " + ThePersonAtEndOfLine.FirstName + " " + ThePersonAtEndOfLine.LastNameAtBirth + ", b. " + ThePersonAtEndOfLine.BirthDate.substr(0,4) + ", " + ThePersonAtEndOfLine.BirthLocation + " (" + gen + ")<BR>";
				// surnamesFullEntryList.push(TheNextReport);
				if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 928");}
				// alert ("WARNING:  no currentList[0] added here  for ahnNum = " + ahnNum + " !!!!");
								// currentList = [ [ThePersonFirstName, ThePerson.Name, ThePersonBirthDate, ThePersonBirthLocation, clrNum, [ahnNum]] ,  ThePerson.LastNameAtBirth ];

				surnamesFullEntryList.push([ currentList[1], currentList[0] , ThePersonAtEndOfLine , "", gen , newClrNum, isInBioList ]); 
				if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 930");}
				document.getElementById('SurnamesFoundReport').innerHTML += TheNextReport;

			}
			if (ThePersonAtEndOfLine.Name == theDebugID) {alert("Success - in AddToReportAnotherEndOfLine function! - line 938");}
			TheDebugNotes += "Reporting on : " + ThePersonAtEndOfLine.Name + " - DONE <br>";
		}

		function investigateSurname(ThePerson, gen, clrNum , currentList = [] , ahnNum = 0, ahnORbio = "ahn") {
			TheDebugNotes +=  "Investigate: " + ThePerson.FirstName + " " + ThePerson.LastNameAtBirth + " clrNum " + clrNum + " @ gen " + gen + " ,  i" +  numInvestigations + "<br>";
			if (gen < 3) {
				// alert ("Investigate: " + ThePerson.FirstName + " " + ThePerson.LastNameAtBirth + " clrNum " + clrNum + " @ gen " + gen  + " && Ahnentafel # = " + ahnNum);
			}

			if (ThePerson.FirstName) {
				ThePersonFirstName = ThePerson.FirstName;
			} else {
				ThePersonFirstName = 'Private ';
				// TheNotes += "The Primary Person has a private profile, and is referred to as 'Private " + ThePerson.LastNameAtBirth + "'<br/>";
			}

			if (ThePerson.BirthDate) {
				ThePersonBirthDate = ThePerson.BirthDate;
			} else {
				ThePersonBirthDate = '';				
			}

			if (ThePerson.BirthLocation) {
				ThePersonBirthLocation = ThePerson.BirthLocation;
			} else {
				ThePersonBirthLocation = '';				
			}

			if (currentList.length == 0 ) {
				currentList = [ [ThePersonFirstName, ThePerson.Name, ThePersonBirthDate, ThePersonBirthLocation, clrNum, [ahnNum]] ,  ThePerson.LastNameAtBirth ];
				if (ahnNum == 2) {
					currentList[0][5] = [1,2];
				}
			}  else {
				currentList[0][5].push(ahnNum);
			}
				
			

			numInvestigations += 1;
			TheDebugNotes += "NumInc! " + ThePersonFirstName + " " + ThePerson.Name + "<br>";
			theRecentTime = new Date();
			if (ThePerson && ThePerson.Name){
						TheDebugNotes += theRecentTime + " : " + currentList[1] + " : <A target=_blank href=https://wikitree.com/wiki/" + ThePerson.Name.replace(/ /g, "%20").replace(/'/g, "&#39;") + ">"+ ThePersonFirstName + "</a>  [" + ThePerson.Name + " ] @ gen " + gen + "<br>";
					}

			var request = $.ajax({
					url: API_URL,
					crossDomain: true,
					xhrFields: { withCredentials: true },
					type: 'POST',
					dataType: 'json',
					
					
					data: { 'action': 'getPeople', 'appId': theAppID , 'keys': ThePerson.Id ,'ancestors':1, 'fields': "Id,Name,FirstName,LastNameAtBirth,BirthDate,BirthLocation,Father,Mother,Gender,BioMother,BioFather", 'format': 'json' },

					// On successful POST return, check our data. Note from that data whether the login itself was
					// successful (setting session cookies if so). Call the user callback function when done.
					success: function(data) { 
						// if (ThePerson.Name == theDebugID) {alert("Success - in ready function!");}
						var theJason = JSON.stringify(data);
						// alert(theJason);
						if (data[0]) {
							// alert ("There is data");
							let thePerson0ID = ThePerson.Id;
							if (data[0].resultByKey && data[0].resultByKey[thePerson0ID]) {
								thePerson0ID = data[0].resultByKey[thePerson0ID].Id;
							}
							if (data[0].people) {
							// alert ("in the ancestors ...");
								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 987");}
								var Person0 = data[0].people[thePerson0ID];
								var Person1 = null;
								var Person2 = null;

								var TheSelf = Person0;
								var TheFather = null;
								var TheMother = null;

								if (Person0 && Person0.Father && data[0].people[Person0.Father]) {
									TheFather = data[0].people[Person0.Father];
								}
								if (Person0 && Person0.Mother && data[0].people[Person0.Mother]) {
									TheMother = data[0].people[Person0.Mother];
								}

								// if (data[0].people.length > 0 && data[0].people[1]) {
								// 	Person1 = data[0].people[1];
								// 	if (Person0.Father && Person0.Father > 0 && Person1.Id == Person0.Father) {
								// 		TheFather = Person1;
								// 	} else if (Person0.Mother && Person0.Mother > 0 && Person1.Id == Person0.Mother) {
								// 		TheMother = Person1;
								// 	} 
								// }

								// if (data[0].people.length > 1 && data[0].people[2]) {
								// 	Person2 = data[0].people[2];
								// 	if (Person0.Mother && Person0.Mother > 0 && Person2.Id == Person0.Mother) {
								// 		TheMother = Person2;
								// 	} else if (Person0.Father && Person0.Father > 0 && Person2.Id == Person0.Father) {
								// 		TheFather = Person2;
								// 	} 
								// }
								
								// alert ("Before the inner ahnen asst");

								if (TheFather) {
									if (TheFather.Name) {
										personArray[ TheFather.Name ] = TheFather;
										if (ahnORbio == "ahn"){
											ahnenIndexArray[ 2*ahnNum ] = TheFather.Name ;
										} else {
											bioIndexArray[ 2*ahnNum ] = TheFather.Name ;
										}
									} else {
										if (ahnORbio == "ahn"){
											ahnenIndexArray[ 2*ahnNum ] ="Private";
										} else {
											bioIndexArray[ 2*ahnNum ] = "Private" ;
										}										
									}	
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum) + " = " + ahnenIndexArray[ 2*ahnNum ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum) + " NOT ASSIGNED !!!! "  ;}
								}

								if (TheMother) {
									if (TheMother.Name) {
										personArray[ TheMother.Name ] = TheMother;
										if (ahnORbio == "ahn"){
											ahnenIndexArray[ 1 + 2*ahnNum ] = TheMother.Name ;
										} else {
											bioIndexArray[ 1 + 2*ahnNum ] = TheMother.Name ;
										}

										
									} else {
										if (ahnORbio == "ahn"){
											ahnenIndexArray[ 1 + 2*ahnNum ] = "Private";
										} else {
											bioIndexArray[ 1 + 2*ahnNum ] = "Private";
										}
										
									}	
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum + 1) + " = " + ahnenIndexArray[ 2*ahnNum + 1 ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2*ahnNum) + " NOT ASSIGNED !!!! "  ;}
								}

								// alert ("After the inner ahnen asst");

								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1020");}
								// Determine the FOLLOW THE LINE person (carrying on the family name) and the NEW LINE person from parents
								// In the rare cases when needed, sometimes you need to start 2 new lines, in which case there will be a NewLinePerson2

								FollowLinePerson = null; // start out with null, in case you only find one or the other
								NewLinePerson = null;
								NewLinePerson2 = null;

								if (TheFather && TheFather.LastNameAtBirth == TheSelf.LastNameAtBirth) {
									// In ALL cases - if the FATHER's Surname is IDENTICAL to the CHILD's Surname - USE that and continue the research up the line
									// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1030");}
										FollowLinePerson = TheFather;
										if (TheMother) {
											NewLinePerson = TheMother;
										}

										FinishUpFollowThrough( TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum, ahnORbio);


								} else  {
									// NOW .. .this is where two paths divert in a Snowy Woods ...
									// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1037");}
									if (ask == 'Y') {
										// MANUAL MODE MOMMA !
										// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1040 - prepare to Make Decision!");}

										AddToDecisionArray( TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum);

										// whoFollows = makeDecisionTime2(TheSelf, TheFather, TheMother, currentList);
										// alert (isDone);

										// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1042 - made Decision: " + whoFollows);}

									 


									} else {
										// AUTO-PILOT PAPA !!

										if (TheMother && TheFather && TheMother.LastNameAtBirth == TheSelf.LastNameAtBirth && TheFather.LastNameAtBirth != TheSelf.LastNameAtBirth) {
											// Need to follow the MOTHER'S LINE back using the current List, and then start a NEW list with this new dad's surname (could be single mom / NPE )
																			
											FollowLinePerson = TheMother;
											NewLinePerson = TheFather;
											TheNotes += TheSelf.LastNameAtBirth  + ": following the Mother's line - from " +  TheSelf.FirstName + " " + TheSelf.LastNameAtBirth + " [" + TheSelf.Name + "]  -> " + TheMother.FirstName + " " + TheMother.LastNameAtBirth + " [" + TheMother.Name + "]  .<br/>";
										

										} else if (TheMother && TheFather) {
											// IF Both the Mother and Father exist, but neither has the IDENTICAL last name,
											// Go with the FATHER's LINE (assume it's a spelling variation or change in naming convention),
											// and thus carry on with the traditional patrilineal routine
											FollowLinePerson = TheFather;
											NewLinePerson = TheMother;

										} else if (TheFather) {									
											// Only the Father exists - so - definitely continue to follow the Father's line
											FollowLinePerson = TheFather;


										} else if (TheMother) {
											// ONLY the Mother exists.
											if (TheMother.LastNameAtBirth == TheSelf.LastNameAtBirth) {
												// IF identical last names - then follow the Mother
												FollowLinePerson = TheMother;
												TheNotes += TheSelf.LastNameAtBirth  + ": following the Mother's line - from " +  TheSelf.FirstName + " " + TheSelf.LastNameAtBirth + " [" + TheSelf.Name + "]  -> " + TheMother.FirstName + " " + TheMother.LastNameAtBirth + " [" + TheMother.Name + "]  .<br/>";
											} else {
												// OTHERWISE ... start up a new line of research with the Mother
												NewLinePerson = TheMother;
												AddToReportAnotherEndOfLine (TheSelf, currentList, gen, true, ahnNum);
											}
										} else if (!TheFather && !TheMother) {
											// NO PARENTS EXIST for this poor SELF
											AddToReportAnotherEndOfLine (TheSelf, currentList, gen, true, ahnNum);
										}

										FinishUpFollowThrough( TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum, ahnORbio);


									}

								}


								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1130");}
								
						}


						}
						// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1237");}
						
						TheDebugNotes += theRecentTime + " : " + currentList[1] + " : "  +  ThePerson.FirstName + "</a>  [" + ThePerson.Name + " ]  - DONE <br>";
						TheDebugNotes += "NumDec!" + " " + ThePerson.Name + "<br>";

					},

					

					// On failed POST/server error, act like a failed login.
					error: function(xhr, status) { 
						// alert('OOPS - I missed this time! - ' + status);
						// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1249");}
						numInvestigations -= 1;
						TheDebugNotes += theRecentTime + " : " + currentList[1] + " : "  +  ThePerson.FirstName + "</a>  [" + ThePerson.Name + " ]  - DONE in ERROR!!! <br>";
						TheDebugNotes += "NumDec!" + " " + ThePerson.Name + "<br>";
					}
				});  // end var request";

			TheDebugNotes +=  "Investigate: " + ThePersonFirstName + " " + ThePerson.LastNameAtBirth + " clrNum " + clrNum + " @ gen " + gen + " ,  i" +  numInvestigations + " - DONE <br>";	
			// if (ThePerson.Name == theDebugID) {alert("Success - in ready function! - line 1257");}
		}


		function FinishUpFollowThrough( TheSelf, TheFather, TheMother, FollowLinePerson, NewLinePerson, NewLinePerson2, ahnNum, gen, currentList, clrNum, ahnORbio = "ahn") {
							if (FollowLinePerson) {	
								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1136");}
								if (FollowLinePerson == TheFather) {
									FollowAhnenNumber =  2*ahnNum;
								} else {
									FollowAhnenNumber = 1 + 2*ahnNum;
								}
								if (currentList.indexOf(FollowLinePerson.LastNameAtBirth) == -1) {
									currentList.push( FollowLinePerson.LastNameAtBirth);
								}
								// alert ("There is ancestor data - "+ data[0].status);

								if (allFatherIDs.indexOf(FollowLinePerson.Id) == -1 &&  FollowLinePerson.LastNameAtBirth.toUpperCase() != "UNKNOWN") {
									// == -1 means NOT a repeat ancestor already counted in a different surname line ...
									allFatherIDs.push(FollowLinePerson.Id);
									// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1153");}
									if (FollowLinePerson.Father || FollowLinePerson.Mother ) {
											// still has at least one parent to continue investigating
											// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - ready to INVESTIGATE ! - line 1156");}
											investigateSurname(FollowLinePerson, gen + 1, clrNum, currentList, FollowAhnenNumber, ahnORbio);
									
									} else {
											// end of the line - time to add to the report
											// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function!  - ADDING to Report - line 1161");}
											// if (ThePerson.Name == theDebugID) {alert("Name of FollowLine Person after Peters-5169 = " + FollowLinePerson.Name + "  - line 1162");}
											AddToReportAnotherEndOfLine (FollowLinePerson, currentList, gen + 1, true, FollowAhnenNumber);	
									}

								} else if (allFatherIDs.indexOf(FollowLinePerson.Id) == -1 ) {
										// end of the line - time to add to the report
										// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1167");}
											AddToReportAnotherEndOfLine (FollowLinePerson, currentList, gen + 1, true, FollowAhnenNumber);	
 								
								}
							}  
							// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1172");}

							// NEW LINE PERSON ROUTINE :
							if (NewLinePerson) {	
								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1176");}
								if (NewLinePerson == TheFather) {
									NewAhnenNumber =  2*ahnNum;
								} else {
									NewAhnenNumber = 1 + 2*ahnNum;
								}

								newClrNum = clrNum;

								if (clrNum == 0 && gen == 2) {
									newClrNum = 1;
								} else if (clrNum == 2  && gen == 2) {
									newClrNum = 3;
								} else {
									newClrNum = clrNum;
									// if (gen < 3) {alert ("NO CHANGE in newClrNum : " + newClrNum);}
								}
								
								// if (gen < 3) alert ("VAR TheNextPerson  newClrNum = " + newClrNum + " for " + TheNextPerson.FirstName + " " + TheNextPerson.LastNameAtBirth + " mother of " + ThePerson.FirstName + " " + ThePerson.LastNameAtBirth);

								thisCurrentList = [ [NewLinePerson.FirstName, NewLinePerson.Name, NewLinePerson.BirthDate, NewLinePerson.BirthLocation, newClrNum , [NewAhnenNumber] ] ,  NewLinePerson.LastNameAtBirth ];
								
								// alert ("There is ancestor data - "+ data[0].status);

								if (NewLinePerson.Father  || NewLinePerson.Mother) {
									if (allFatherIDs.indexOf(NewLinePerson.Id) == -1 && gen < maxGens &&  NewLinePerson.LastNameAtBirth.toUpperCase() != "UNKNOWN") {
										allFatherIDs.push(NewLinePerson.Id);
										// if (gen < 3) alert ("About to investigate newClrNum = " + newClrNum + " for " + NewLinePerson.FirstName + " " + NewLinePerson.LastNameAtBirth + " mother of " + ThePerson.FirstName + " " + ThePerson.LastNameAtBirth);
										investigateSurname(NewLinePerson, gen + 1, newClrNum, thisCurrentList, NewAhnenNumber,ahnORbio);
									} //
								} else {
									if ( gen < maxGens &&  NewLinePerson.LastNameAtBirth.toUpperCase() != "UNKNOWN" && includeSingles == 'Y') {
										AddToReportAnotherEndOfLine (NewLinePerson, thisCurrentList, gen + 1, false, NewAhnenNumber);	

									}

								}

							}

							if (NewLinePerson2) {	
								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1207");}
								if (NewLinePerson2 == TheFather) {
									NewAhnenNumber2 =  2*ahnNum;
								} else {
									NewAhnenNumber2 = 1 + 2*ahnNum;
								}

								newClrNum = clrNum;

								if (clrNum == 0 && gen == 1) {
									newClrNum = 1;
								} else if (clrNum == 2  && gen == 1) {
									newClrNum = 3;
								} else {
									newClrNum = clrNum;
									// if (gen < 3) {alert ("NO CHANGE in newClrNum : " + newClrNum);}
								}
								
								// if (gen < 3) alert ("VAR TheNextPerson  newClrNum = " + newClrNum + " for " + TheNextPerson.FirstName + " " + TheNextPerson.LastNameAtBirth + " mother of " + ThePerson.FirstName + " " + ThePerson.LastNameAtBirth);

								thisCurrentList = [ [NewLinePerson2.FirstName, NewLinePerson2.Name, NewLinePerson2.BirthDate, NewLinePerson2.BirthLocation, newClrNum, [NewAhnenNumber2]] ,  NewLinePerson2.LastNameAtBirth ];
								
								// alert ("There is ancestor data - "+ data[0].status);

								if (NewLinePerson2.Father) {
									if (allFatherIDs.indexOf(NewLinePerson2.Id) == -1 && gen < maxGens &&  NewLinePerson2.LastNameAtBirth.toUpperCase() != "UNKNOWN") {
										allFatherIDs.push(NewLinePerson2.Id);
										// if (gen < 3) alert ("About to investigate newClrNum = " + newClrNum + " for " + NewLinePerson2.FirstName + " " + NewLinePerson2.LastNameAtBirth + " mother of " + ThePerson.FirstName + " " + ThePerson.LastNameAtBirth);
										investigateSurname(NewLinePerson2, gen + 1, newClrNum, thisCurrentList, NewAhnenNumber2,ahnORbio);
									} //
								} else {
									if ( gen < maxGens &&  NewLinePerson2.LastNameAtBirth.toUpperCase() != "UNKNOWN" && includeSingles == 'Y') {

										AddToReportAnotherEndOfLine (NewLinePerson2, thisCurrentList, gen + 1, false, NewAhnenNumber2);	

										// TheNextReport = " " + thisCurrentList[1];
										 
										// TheNextReport +=  " : " + NewLinePerson2.FirstName + " " + NewLinePerson2.LastNameAtBirth + ", b. " + NewLinePerson2.BirthDate.substr(0,4) + ", " + NewLinePerson2.BirthLocation + " (" + gen + ")<BR>";
										// // surnamesFullEntryList.push(TheNextReport);
										// surnamesFullEntryList.push([ thisCurrentList[1], [] , NewLinePerson2 , "", gen , newClrNum ]); 
										// document.getElementById('SurnamesFoundReport').innerHTML += TheNextReport;
									}

								}

							}

							numInvestigations -= 1;
			// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1248");}
		}
		// 					// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 1250");}


		function showManualInfo() {
			DIVtext = "<H1>You chose MANUAL Surname Mode</H1><P>Here's what that really means:</P>";
			DIVtext = "<table><tr><td align=left><UL>    <Li>The app will start at the primary person and follow their ancestors' surnames back in time.</LI>    <Li>At each ancestor, if the Father has the exact same surname as the Child, <br>then the search will continue back in time automatically up through the Father.</LI>    <Li>If that is NOT the case (the Father's surname is NOT an exact match), <br>then you will be ASKED which line to follow, if any.        <UL>            <Li>You will be given a pop-up box that will list:                <UL>                    <Li> the Surname Line that is being researched</LI>                    <LI> The name and date of birth for the Child, Father and Mother</LI>      <LI> You will be asked a question:  Which line to follow ?                        <UL>                            <Li> You must click the appropriate button.</LI>                                </UL>                            </LI>                        </UL>                    </LI>                       </UL>            </LI>        </UL>    </LI>       </UL><I>Note: If this process isn't what you're looking for, <br>scroll down the page and click the RESET button to go back to Automatic Mode</i><br><br><input type=submit class=submit value='Begin Manual Surname Generation Now!' onclick='generateSurnames();'><br><br>FYI - In case you're wondering how this is different from Automatic Mode,<UL>    <Li>In AUTOMATIC mode, if the Father's surname IS an exact match, <br>the app automatically follows the surname up through the Father</LI>    <Li>If the Father's surname is NOT an exact match, but the Mother's surname IS an exact match, <br>then the app follows the Mother, and starts a new surname search using the Father's surname.</LI>    <Li>If NEITHER parent have the exact match, the app will follow up through the Father <br>(and record the new surname as a variant), <br>and start a new surname search using the Mother's  surname.</LI></UL></td></tr></table>";

			document.getElementById('DecisionDIV').style.display ="block";
			document.getElementById('DecisionDIV').innerHTML += DIVtext;

		}

		function generateSurnames() {
			// alert ('Ready to Generate Surnames NOW for ' + ID + ' !!!');
			var TheReport = '';
			TheNotes = '';
			var hasError = false;
			document.getElementById('DecisionDIV').style.display ="none";
			document.getElementById('DecisionDIV').innerHTML ="<H1>Decision Time</H1>";

			deepestGen = -1;
			oldestAncBDate = 9999;
			oldestAnc = '';

			let params = { 'action': 'getPeople', 'appId': theAppID , 'keys': ID ,'ancestors':1, 'fields': "Id,Name,FirstName,LastNameAtBirth,BirthDate,BirthLocation,Father,Mother,BioFather,BioMother,DataStatus", 'format': 'json' };
			console.log(params);
			var request = $.ajax({
					url: API_URL,
					crossDomain: true,
					xhrFields: { withCredentials: true },
					type: 'POST',
					dataType: 'json',
					
					//data: { 'action': 'getPerson', 'key': primaryID, 'fields': "Id, Name, FirstName, MiddleName, IsLiving, LastNameAtBirth, LastNameCurrent, Nicknames, LastNameOther, RealName, Prefix, Suffix, BirthLocation, DeathLocation, Gender, BirthDate, DeathDate,Father,Mother, Privacy, DataStatus", 'format': 'json' },
					data: { 'action': 'getPeople', 'appId': theAppID , 'keys': ID ,'ancestors':1, 'fields': "Id,Name,FirstName,LastNameAtBirth,BirthDate,BirthLocation,Father,Mother,BioFather,BioMother,DataStatus", 'format': 'json' },

					// On successful POST return, check our data. Note from that data whether the login itself was
					// successful (setting session cookies if so). Call the user callback function when done.
					success: function(data) { 
						// alert("Success - in ready function!");						 
						var theJason = JSON.stringify(data);
						// alert(theJason);

							
							
							// alert ("in the ancestors ...");
								// if (ThePerson.Name == theDebugID) {alert("Success - in investigateSurname function! - line 987");}
								

						if (data[0]) {
							// alert ("There is data");
							let thePerson0ID = ID;
							if (data[0].resultByKey && data[0].resultByKey[thePerson0ID]) {
								thePerson0ID = data[0].resultByKey[thePerson0ID].Id;
							}

							if (data[0].people) {
								var Person0 = data[0].people[thePerson0ID];
								var Person1 = null;
								var Person2 = null;

								var ThePerson = Person0;
								var TheFather = null;
								var TheMother = null;
								var TheBioFather = null;
								var TheBioMother = null;

								console.log(data[0].people);
								console.log("Person0 = ", Person0);
								if (Person0 && Person0.Father && data[0].people[Person0.Father]) {
									TheFather = data[0].people[Person0.Father];
								}
								if (Person0 && Person0.Mother && data[0].people[Person0.Mother]) {
									TheMother = data[0].people[Person0.Mother];
								}
								if (Person0 && Person0.BioFather && data[0].people[Person0.BioFather]) {
									TheBioFather = data[0].people[Person0.BioFather];
								}
								if (Person0 && Person0.BioMother && data[0].people[Person0.BioMother]) {
									TheBioMother = data[0].people[Person0.BioMother];
								}
								
								// alert ("There is ancestor data - "+ data[0].status);
								if (ThePerson.FirstName) {
									ThePersonFirstName = ThePerson.FirstName;
								} else {
									ThePersonFirstName = 'Private ';
									TheNotes += "The Primary Person has a private profile, and is referred to as 'Private " + ThePerson.LastNameAtBirth + "'<br/>";
								}

								personArray ["Private"] = PrivatePersonForArray;

								personArray[ ThePerson.Name ] = ThePerson;
								ahnenIndexArray[ 1 ] = ThePerson.Name ;
								bioIndexArray[ 1 ] = ThePerson.Name ;

								if (debugNow) {TheDebugNotes += "<br>AHNEN " + (1) + " = " + ahnenIndexArray[ 1 ] ;}

								if (TheFather) {
									if (TheFather.Name) {
										// alert ("Before the array assignment ");
										personArray[ TheFather.Name ] = TheFather;
										// alert ("After the array assignment ");
										ahnenIndexArray[ 2 ] = TheFather.Name ;
									} else {
										ahnenIndexArray[ 2 ] = "Private";
									}	
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2) + " = " + ahnenIndexArray[ 2 ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (2) + " NOT ASSIGNED @ BEGINNING !!! " ;}
								}

								if (TheMother) {
									if (TheMother.Name) {
										personArray[ TheMother.Name ] = TheMother;
										ahnenIndexArray[ 3 ] = TheMother.Name ;
									} else {
										ahnenIndexArray[ 3 ] = "Private";
									}	
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (3) + " = " + ahnenIndexArray[ 3 ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>AHNEN " + (3) + " NOT ASSIGNED @ BEGINNING !!! " ;}
								}

								if (TheBioFather) {
									if (TheBioFather.Name) {
										// alert ("Before the array assignment ");
										personArray[ TheBioFather.Name ] = TheBioFather;
										// alert ("After the array assignment ");
										bioIndexArray[ 2 ] = TheBioFather.Name ;
									} else {
										bioIndexArray[ 2 ] = "Private";
									}	
									if (debugNow) {TheDebugNotes += "<br>BIO " + (2) + " = " + bioIndexArray[ 2 ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>BIO " + (2) + " NOT ASSIGNED @ BEGINNING !!! " ;}
								}

								if (TheBioMother) {
									if (TheBioMother.Name) {
										personArray[ TheBioMother.Name ] = TheBioMother;
										bioIndexArray[ 3 ] = TheBioMother.Name ;
									} else {
										bioIndexArray[ 3 ] = "Private";
									}	
									if (debugNow) {TheDebugNotes += "<br>bio " + (3) + " = " + bioIndexArray[ 3 ] ;}
								} else {
									if (debugNow) {TheDebugNotes += "<br>bio " + (3) + " NOT ASSIGNED @ BEGINNING !!! " ;}
								}

								// alert (personArray);
								// alert (ahnenIndexArray);
 								ThePrimaryPersonName = ThePersonFirstName + " " + ThePerson.LastNameAtBirth;
								TheReportHeader = "<B>Surnames for " + ThePersonFirstName + " " + ThePerson.LastNameAtBirth + ":</B><BR>";
								ListOfSurnames = "";//TheFather.LastNameAtBirth +  "<br/>" + TheMother.LastNameAtBirth  + "<br/>";

								document.getElementById('SurnamesFoundReport').innerHTML = TheReportHeader + ListOfSurnames;
								
								if (TheFather && TheFather.Id > 0) {
									investigateSurname(TheFather , 2 , 0, [],  2);
								} else if (TheFather && (TheFather.Father > 0 || TheFather.Mother > 0)) {
									TheNotes += "The Primary Person's Father has a private profile.<br/>";
									investigateGrandparents(TheFather, 2, 0, 2);
								}

								if (TheMother && TheMother.Id > 0) {
									 investigateSurname(TheMother , 2 , 2, [], 3);
								} else if (TheMother && (TheMother.Father > 0 || TheMother.Mother > 0)) {
									TheNotes += "The Primary Person's Mother has a private profile.<br/>";
									investigateGrandparents(TheMother, 2, 2, 3);
								} 

								if (TheBioFather && TheBioFather.Id > 0) {
									investigateSurname(TheBioFather , 2 , 0, [],  2, 'bio');
								}

								if (TheBioMother && TheBioMother.Id > 0) {
									 investigateSurname(TheBioMother , 2 , 2, [], 3, 'bio');
								}


							} else {
								alert (data[0].status);
								hasError = true;
								clearInterval(myInterval);
								window.location.assign("https://apps.wikitree.com/apps/clarke11007/surnames.php?ph=0");
							}
						}

					},

					// On failed POST/server error, act like a failed login.
					error: function(xhr, status) { 
						// alert('OOPS - I missed this time! - ' + status);
					}
				});  // end var request";

			// alert ("Done request " + numInvestigations);
			if (hasError == false) {
				theStartTime = theTime = new Date();
				myInterval = setInterval(checkIfAncestorLooksupsAreDone,1000);
			}
				
		}	


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
					// transfer values from wikiTreeAuth
					wikitree.session.loggedIn = thisUserIsLoggedIn;
					wikitree.session.user_name = getUserName();
					wikitree.session.user_id = getUserId();
					// alert("in checkLogin " + wikitree.session.user_name + ":" +wikitree.session.loggedIn);
					if (wikitree.session.loggedIn) { 
						/* We're already logged in and have a valid session. */
						// alert("Logged In @ " + wikitree.session.user_name);

						// DO NOT NEED THE LOGIN DIV 
						document.getElementById('need_login').style.display = 'none';
						<?php 
								
							if ($ph == 0 && $_SESSION['id'] > '') {
								// Fill in form with username to make it quick and easy to generate your own surnames list

								echo "document.TheForm.Profile.value = '" . $_SESSION['id'] . "';";
								echo "\ndocument.getElementById('ResetAndSearch').style.display = 'block';" ;
							} else if ($ph === 0) {
								// Fill in form with username to make it quick and easy to generate your own surnames list
								echo "\ndocument.getElementById('ResetAndSearch').style.display = 'block';" ;
								echo "document.TheForm.Profile.value = wikitree.session.user_name;";
							} else if ($ph == 1 && $_SESSION['id'] > '') {
								// start the process to actually find the surnames and build the list
								echo "\ndocument.getElementById('ResetAndSearch').style.display = 'none';" ;
								if ($ask == 'Y') {
									echo "showManualInfo();";
								} else {
									echo "generateSurnames();";
								}

							} else {
								echo "\ndocument.getElementById('ResetAndSearch').style.display = 'block';" ;
								echo "document.TheForm.Profile.value = wikitree.session.user_name;";	
								echo "\ndocument.getElementById('need_login').style.display = 'none';";
							}

						?>	
						// document.TheForm.Profile.value = wikitree.session.user_name;
						// document.TheForm.WikiTreeUserName.value = wikitree.session.user_name;
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


						<?php 
								
							if ( $_SESSION['id'] > '') {
								// Fill in form with username to make it quick and easy to generate your own surnames list
								echo "var id = '" . $_SESSION['id'] . "';";
							} else  {
								echo "var id = 'none';";
							}
							if ( $_SESSION['maxGens'] > '') {
								// Fill in form with username to make it quick and easy to generate your own surnames list
								echo "var maxGens = '" . $_SESSION['maxGens'] . "';";
							} else  {
								echo "var maxGens = 0;";
							}
							
						?>	


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
										document.TheForm.Profile.value = wikitree.session.user_name;

										if (id != 'none' && maxGens > 0) {

											window.location.assign('https://apps.wikitree.com/apps/clarke11007/surnames.php?ph=1&id=' + id + "&maxGens=" + maxGens);

										} else {
											window.location.assign('https://apps.wikitree.com/apps/clarke11007/surnames.php?ph=0');
										}

									}
									else { 
										document.getElementById('need_login').style.display = 'block';
										//window.location = "https://api.wikitree.com/api.php?action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/surnames.php";
										// alert ("has authcode but seemingly not logged in ???");
									}
								});
						} else { 
							document.getElementById('need_login').style.display = 'block';
							// alert ("has no authcode and seemingly not logged in ???");
							//window.location = "https://api.wikitree.com/api.php?action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/surnames.php";
							//alert ("NOT logged !!");
							// $('#need_login').show();
							// $('#logged_in').hide();
						}
					}
				});
			//alert ("After session.checkLogin");


		});
		</script>
		<a name="top" ></a>
		<DIV align=center>
			<H1>Surnames Generator</H1>
	<?php

	if ($ph == 0) {

	?>

	

			

	<?php 			
	} elseif ($ph == 1) {
		// SHOW THE RESULTS
	?>

	<DIV id=Prompts>Looking for surnames ....</DIV><BR/>
		<DIV id=DecisionDIV  align=center style="display: none; padding:10px; background-color: lightyellow;"><H3>Manual Surname Mode</H3></DIV>
		<DIV id=SurnamesFoundReport></DIV>
		
			<DIV id=TabHolder style="display:none; background-color: #dFd; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px; max-width: 800px;">
				<DIV id=TheTabs><input type=submit class=submit id=CompactBtn value="COMPACT" style="background-color: orange;" onclick="switchTab('Compact');"> <input type=submit class=submit id=DetailedBtn value="DETAILED" onclick="switchTab('Detailed');"></DIV><hr>
				<DIV id=TheStyleTabs><input type=submit class=submit id=BandWBtn value="B&W" style="background-color: orange;" onclick="switchTab('BandW');"> 
					<input type=submit class=submit id=Clr2Btn value="2 colours" onclick="switchTab('Clr2');">
					<input type=submit class=submit id=Clr4Btn value="4 colours" onclick="switchTab('Clr4');">  <br/> &nbsp; <br/>
					<input type=submit class=submit id=SortSurnameBtn value="Sort by surname"  style="background-color: orange;" onclick="switchTab('SortSurname');">
					<input type=submit class=submit id=SortColourBtn value="Sort by colour" style="display: none;" onclick="switchTab('SortColour');">
					<input type=submit class=submit id=SortGenBtn value="Sort by generation" onclick="switchTab('SortGen');">
					<DIV id=GenOptions  style="display: none;"><br>Split Ahnentafel ordering and Alpha ordering at Generation # <input type='number' id='splitGenNum' name='splitGenNum' min='0' max='99' value=6  onchange="summarizeResults();"><br>
						<input type='checkbox' id='subsortColour' name='subsortColour' onclick="summarizeResults();"> Sub-Sort by Colour when alpha ordering <br>
						<input type='checkbox' id='showOnlyNewNames' name='showOnlyNewNames' onclick="summarizeResults();"> Only show New Surnames at each generation <br>
						<input type='checkbox' id='showAhnNums' name='showAhnNums' onclick="summarizeResults();"> Show Ahnentafel #s<br>
					</DIV>
				</DIV><hr><DIV id=FloatingLineage style="display: none;">Lineage goes here</DIV>
				<DIV id=CompactContent><H2>COMPACT Surnames List info</H2>
				Lots of  COMPACT stuff will go in here.</DIV>
				<DIV id=DetailedContent style="display: none;"><H2>DETAILED Surnames List info</H2>
				Lots of VERY VERY DETAILED stuff will go in here.</DIV>
			</DIV>			
		
			



		<HR>
		<P id=TheNotesP align=center></P>
		<input type=submit class=submit id=showFAQs value='FAQs' onclick='toggleFAQs();'><br/><br/>
		<FORM>
			<input type=hidden name=ph value=0>	
			<input type=submit id=ResetBtnPh1  class=submit value='Reset'>
		</FORM>

	<?php 	
	} else {
	
		// NOT LOGGED IN AT ALL
		// in which case the need_login will show up
		
	}


// echo "<BR> All done!";
	
?>	
	<div id="ResetAndSearch" align=center style="display:none;">
		<P>This app will generate a List of Surnames, in alphabetical order, corresponding to the ancestors in your family tree.<br/>
		You can include this list in your WikiTree profile and share it with prospective cousins and fellow family researchers.</P>
		<p>Check out the FAQs at the <a href=#bot>bottom</a> of this page for more details.</p>

		

				<form name="TheForm" style="background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>Enter the <A target=_blank href=https://www.wikitree.com/wiki/Help:WikiTree_ID>WikiTree ID</A> for the profile needing the Surnames list.<BR/>
				
				<P><B>PROFILE:</B><br/> <input type=text name=id id=Profile value=""><BR><I>e.g. Marcoux-336<BR/></I></P>
				
				<input type=hidden name=ph value=1>	
				<HR><B>MAXIMUM # GENERATIONS:</B><br/> <input type=text name=maxGens value=<?php echo $_SESSION['maxGens']; ?> >	
				<BR><I>i.e. how far back do you want to go to collect surnames for the list?<BR/></I>				
				
				<HR>
				<input class="button" type="submit" value="Generate Surnames List" ><BR>&nbsp;
				</form>
				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;

			
			<BR/>
			<a name="bot" ></a>
			<p align=center><a href=#top>Top</a><br><br>
				<input type=submit class=submit id=showFAQs value="FAQs" onclick='toggleFAQs();'>
			</p>
			

	</div>		

<DIV id=FAQs  style="display:none;">
<table border=0><tr><td width=25%>&nbsp;</td>
<td width=50% align=left><P>
<b>FREQUENTLY ASKED QUESTIONS</b><br/><br/>
<b>Q. Who came up with the idea for this app?</b><br/>
A. The original design and idea for this app is thanks to <a target=_blank href=https://www.wikitree.com/wiki/Gaulden-7>Mags Gaulden</a>, WikiTree guru.  <a target=_blank href=https://www.wikitree.com/wiki/Clarke-11007>Greg Clarke</a> has programmed it and (hopefully) turned her vision into a reality.

<br><br><b>Q. What's the primary purpose of this app?</b><br/>
A. The primary purpose of this app is to generate a list of Surnames related to a person's ancestors.  This list can then be shared with relatives and family researchers, and posted on a WikiTree Profile page or Free Space page.

<br><br><b>Q. How does it determine which surnames to list?</b><br/>
A. You specify a number of generations, and the app finds all the ancestors back to that level in your tree.  Those are the surnames that will be listed.  

<br><br><b>Q. Does it go any further back in time than that?</b><br/>
A. Once it reaches the desired generation, it follow each of those specific surnames back in time as far as WikiTree will go with a direct ancestor (of the same surname).  Thus, for each of those core surnames, the app will find the Earliest Known Ancestor (EKA) who bore that surname.

<br><br><b>Q. How is the EKA (Earliest Known Ancestor) shared?</b><br/>
A. In the Compact version of the Surnames List, the EKA link is embedded in the Surname - you can click on the surname, and the link will take you that EKA's profile page.

<br><br><b>Q. What is the Detailed Surnames List?</b><br/>
A. Whereas the Compact List is just the list of surnames (with embedded links to the EKAs), the Detailed List gives a larger picture of each surname.  For each surname it gives the name of the First Person in the Family Tree that bore that surname, and their birth date and location, as well as the EKA and their birth date and location, as those facts are listed on WikiTree.

<br><br><b>Q. Does this app handle Bio Parents?</b><br/>
A. Yes. If the primary person has a BioFather or BioMother (or both) attached to their profile, then their surnames, and those of their ancestors will be included in the Surnames List (both Compact and Detailed lists).  The BioParent surnames will be preceeded by a * to indicate their origin.

<br><br><b>Q. What are the funny symbols in the Detailed Surnames List?</b><br/>
A1. <img src=https://www.wikitree.com/images/icons/pedigree.gif> 𐂷  is a link to the First Person's pedigree chart, which will show their family tree going back in time.  <br>
A2. <img src=https://www.wikitree.com/images/icons/descendant-link.gif> ⇟ is a link to the EKA's Descendant List, which will show their descendants going forward in time.  

<br><br><b>Q. What happens if you click on a Surname in the Detailed Surnames List?</b><br/>
A. In the Detailed List, clicking on the surname will show a window on the screen with the full lineage of that surname, from First Person to EKA, with names, and birth dates & locations. <i>(In the Compact List, clicking on the surname opened up the Profile of the EKA. )</i>

<br><br><b>Q. Wait a second ... the numbering is off?  I'm confused ...</b><br/>
A. Ah ... good eyes!  The first version of this app used a slightly different numbering system.  Now, in this current version, I have synced the numbering system so that it works exactly the same as with the Fan Chart app.  Both apps now count the Primary Person as Generation # 1.  Your parents are at Generation #2, grandparents at Generation # 3, and so on.  This consistency will make it easier to go back and forth from one app to the other, and compare the same set of names.

<br><br><b>Q. Are there other options for formatting the Surnames?</b><br/>
A. You can choose to have all of your surnames listed in black and white <br> - OR - colour coded  in 2 ways by <span  style='background-color:#eef;' >Paternal</span> and <span  style='background-color:#fee;' >Maternal</span> ancestors <br> - OR - colour coded in 4 ways, using a unique colour for the ancestors of the <span  style='background-color:#eef;' >Paternal Grandfather</span> , <span style='background-color:#dfd;' >Paternal Grandmother</span> ,  <span style='background-color:#fed8b1;' >Maternal Grandfather</span> , and <span style='background-color:#fee;' >Maternal Grandmother</span>.<br>As well, for any coloured list, there is an additional option to break up the sorting by colour. With that option, for example, all the Paternal ancestors would be listed first in a group (alphabetically), followed by a blank line then the Maternal ancestors listed.

<br><br><b>Q. What is that "Sort by Generations" button about?</b><br/>
A. An alternate way to look at surnames that was requested - to list the surnames as they appear at each generation.  For each generation, the surnames from that generation only are listed.  For many surnames, you'll see them listed over and over again.  You'll also see them morph as you go back in time.

<br><br><b>Q. What are those checkboxes about when I click "Sort by Generations" ?</b><br/>
A. Since this list shows every ancestor, you can choose to show the unique Ahnentafel numbers associated with each ancestor. Also, there is the option to show only the NEW surnames that appear as you go back each generation.  There is also the option to sub-sort the list by colour, if you have also chosen the 2 colours or 4 colours option.

<br><br><b>Q. What is the 'Split Ahntntafel ordering ..' input box for in "Sort by Generation" mode ?</b><br/>
A. At each generation, the names are sorted using either Ahnentafel ordering, or sorted Alphabetically.  This input box will determine when it changes from one sorting routine to the other.  By default, the early generations use the Ahnentafel ordering, which is how you see the surnames as you go around a ring of a Fan Chart, for example, progressing from the Father's Father's line around to the Mother's Mother's line.  With a low number of names in a generation, it's easy to find a name in that list, while simultaneously showing you how they fit into the tree.  However, after a certain point, the number of names are too many to easily scan, and the app switches to alphabetical sorting, which makes it easier to find a surname.  By changing the value in the input box, you can adjust where that split or switch over takes place.  If you prefer one system exclusively, you can change the number to 0 so that ALL the surnames are listed alphabetically - OR - change it to 99 (or any number greater than the number of generations you entered), and the names will continue to be listed in Ahnentafel order.

<br><br><b>Q. What happens if I click on names in "Sort by Generation" mode ?</b><br/>
A. If you are viewing the Compact Surname List, clicking on the Surname will take you to that specific Ancestor's profile page.  In the Detailed Surname List, the same thing is accomplished by clicking on the First Name link.   Similar to the other Detailed Surname Lists, clicking on a Surname will give you a Lineage report from the First Ancestor to EKA with that surname. Also, in the Detailed Surname List, there are the special <img src=https://www.wikitree.com/images/icons/pedigree.gif> and <img src=https://www.wikitree.com/images/icons/descendant-link.gif> symbols that will show the Pedigree and Descendants List for that ancestor.  

<br><br><b>Q. At the bottom of the list, I notice a note about "Manual Surname Mode" - does that mean there is an "Automatic" mode?</b><br/>
A. I have introduced the option to go through your ancestors using a "Manual Surname Mode".  The default is always "Automatic Surname Mode".  In AUTOMATIC mode, the app follows each surname up through the Father's line.  The one exception, in Automatic Mode, is if the Father's surname is NOT the same as the Child's surname, but the Mother's surname is an exact match - then - for that generation - it follows up through the Mother.  (And the Father's surname, which was different, will start a new surname search.)

<br><br><b>Q. So, what does "Manual Surname Mode" do then?</b><br/>
A. In "Manual Surname Mode", if the Father's surname is identical to the Child's surname, the app follows that line up.  However, if the Father's surname is NOT IDENTICAL to the Child's surname, then a dialog will popup and you will have to manually indicate whether the app should follow this surname up through the Father's line, or, up through the Mother's line - OR - you can choose Neither - in which case it will start two new surname lines of research.

<br><br><b>Q. Why the "Manual Surname Mode" ?  This seems complicated ...</b><br/>
A. You're right, it is a bit, and  - fair warning here - can be time-consuming, and possibly annoying since most of the decisions will likely be - to pick the Father - and there is just a slight variation in name spelling causing the popup.  HOWEVER, for some family trees, this is the only way to actually get an accurate surname report. There are too many variables - adoptions / last names at birth = mother's maiden name because the couple wasn't married yet / NPE'S.  It's not possible to program for all those different possibilities - so - this is my way of making it possible, where you know what's best for your family.  For the MAJORITY of people, though, the Automatic Surname Mode will work just fine.  This Manual method is to help out those with the exceptions that break it.

<br><br><b>Q. I'd like something like this, but with more people listed, with some additional information, maybe organized differently ..</b><br/>
A. Sounds cool, but, maybe you want to check out another app.  There is an existing Ancestor List on WikiTree, as well as the excellent <a target=_blank href=https://apps.wikitree.com/apps/ashley1950/ancestorexplorer/>Ancestor Explorer</a> app by Chase Ashley, the <a target=_blank href=https://apps.wikitree.com/apps/beacall6/templates.php>Profile Explorer</a> app by Ian Beacall, or the <a target=_blank href=https://apps.wikitree.com/apps/sands1865/biocheck/>BioCheck app</a> by Kay Knight, or even the <a target=_blank href=https://apps.wikitree.com/apps/clarke11007/fan.php>Fan Chart</a> by the same author as this app.  Maybe one of these will work for you?

<br><br><b>Q. It takes a long time to generate some of these surname lists .. I like the changing messages!</b><br/>
A. Thanks.  That's not so much a question as a compliment - but I'll take it!  If you'd like to contribute your own suggestion(s) of quippy catch-phrases, one-liners, waiting messages, ec..  to add to the list, feel free to contact Greg Clarke, the app developer.
<br><br>If you have other questions you'd like answered, please contact the author!

</td> <td width=25%></td></tr></table>
</DIV>

	<div id="need_login" align=center  style="display:none;">
		
	      <p>You are NOT currently logged in to apps.wikitree.com.<br/>
	      	To access the data in your Family Tree, you need to login.<br><br>
	         Login with the same information that you use for WikiTree.
	        <form action="https://api.wikitree.com/api.php" method="POST">
	        <input type="hidden" name="action" value="clientLogin">
	        <input type="hidden" name="appId" value="clientLogin">
	        <input type="hidden" name="returnURL" value="https://apps.wikitree.com/apps/clarke11007/surnames.php">
	        <input type="submit" class="button" value="Client Login">
	        </form>
	      </p>
	      
	    </div>


<P align=center>
	<A target=_blank href=index.php>Other apps by Greg Clarke</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>Contact the author</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1175252/new-app-surnames-generator-for-your-wikitree-profile>G2G post 1</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1183346/update-to-surnames-generator-app>G2G post 2</A>
	<BR>Last updated 27 March 2026
</P>

      