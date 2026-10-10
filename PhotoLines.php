<?php

// ====================================
// PHOTO LINES app - for Bio Building
// ====================================

// 
// Programmed by Greg Clarke

// Input - WikiTree Profile IDs
// Output - Photo Lines table to include in a Biography of a WikiTree Profile

/*

	APP LOGIC FLOW
	==============

	STEP 1 - enter WikiTree ID

	STEP 2 - allow customization

				- choose Paternal or Maternal Photo Line or Both at once

			 	- stepper: # of generations (by default, take as many as there are photos)
			 	- radio btns: show no names / first names / full names
			 	- checkbox: show dates (year ranges only)
			 	- radio btns + textbox:  size on page - s, m, # of pixels
			 	- textbox: title
			 	- direction: left to right / right to left / top down / bottom up

			 	- EVENTUALLY - drop down option for profiles with multiple images attached

	STEP 3 - autogenerate code for inclusion in profile, and add COPY button, show instructions on what to do



*/


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
	$_SESSION['id'] = '';
}



?>

	<HTML>
		<HEAD>
		<TITLE>PhotoLines</TITLE>
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


		<script type="text/javascript">
		var appID = "PhotoLines";
		var API_URL = 'https://api.wikitree.com/api.php';
		var theID = '';

		var personArray = {};
		var ahnenIndexArray = [];

		var numGens = 4;
		var maxNumOfSpacesInNames = 0;
		var currentSettings = {};

		var listOfIDsForPhotos = [];
		var listOfIDnumsForPhotos = [];
		var ancestorPhotoAlbums = {};
		var currentPhotoNum = 0;
		
		var thePrimaryID =  0;
        const SVGbtnDOWN =
            '<SVG width=18 height=14 ><polyline points="0,0 18,0 9,14 0,0" fill="blue" stroke="blue"/><polyline points="5,7 13,7" fill="none" stroke="white" stroke-width=2 /></SVG>';
        const SVGbtnUP =
            '<SVG width=18 height=14 ><polyline points="0,14 18,14 9,0 0,14" fill="red" stroke="red"/><polyline points="5,8 13,8" fill="none" stroke="white" stroke-width=2 /> <polyline points="9,3 9,13" fill="none" stroke="white" stroke-width=2 /> </SVG>';

	
		// ========== =========
		// PROCESSING FUNCTIONS
		// ========== =========
		
		function consoleLogDIV(input) {
			let theDIV  = document.getElementById("ConsoleLogDIV");

			 // theDIV.innerHTML += "<br/>" + input;
		}

		
		// ========================================================================================
		//
		// LOADING ANCESTORS
		//
		// ========================================================================================
		function loadAncestors() {
			// alert ("loadAncestors");
			personArray = {}; // start with a fresh Person Array
			isDirectAncestor = false;
			theTestPerson = null;
			theProfilePerson = null;
			theProfilePerson2 = null;
			theProfilePerson3 = null;
			ahnenIndexArray = [];
			ahnenIndexArray2 = [];
			ahnenIndexArray3 = [];

			theAncsAhnNum3 = 0;
			// reset the regular MiniTree
			let theID = document.getElementById("WikiID").value;

			let LoadingDetailsDIV = document.getElementById("LoadingDetails");
			LoadingDetailsDIV.innerHTML = "Loading Ancestors now ... please hold ...";
			

			// ========================================================================================
			// (1)  LOAD up to SIX GENERATIONS of ANCESTORS for Tester # 1  (theID)
			// ========================================================================================
			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				//data: { 'action': 'getPerson', 'key': primaryID, 'fields': "Id, Name, FirstName, MiddleName, IsLiving, LastNameAtBirth, LastNameCurrent, Nicknames, LastNameOther, RealName, Prefix, Suffix, BirthLocation, DeathLocation, Gender, BirthDate, DeathDate,Father,Mother, Privacy, DataStatus", 'format': 'json' },
				data: { 'action': 'getPeople', 'appId':'PhotoLines' , 'keys': theID ,'ancestors':4, 'fields': "Id,Name,FirstName,RealName,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,Photo,BirthDate,DeathDate,BirthDateDecade,DeathDateDecade",'format': 'json' },

				// On successful POST return,check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					// alert(theJason);

					
					// if (data[0].status != 0) {
					if (data[0].resultByKey[theID].Id == null) {
						// NO ID - WARNING !!!!

						// // alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						
					} else if (data[0]) {
						// THERE BE DATA HERE ... 
						// console.log ("There is data : " + data[0].people);

						
						// STORE the PRIMARY ID
						thePrimaryID = data[0].resultByKey[theID].Id;

						// COLLECT ALL of the PEOPLE (6 gens of ancestors of PrimaryID)
						for (var i in data[0].people) {
							personArray[ data[0].people[i].Id ] = data[0].people[i];
							console.log("Person " + i,data[0].people[i].Id );
						}
						
						// STORE the PRIMARY PERSON into [theProfilePerson] object (full record, not just ID)
						theProfilePerson = personArray[thePrimaryID];
						console.log({theProfilePerson}, {theID}, {thePrimaryID});

						
						// POPULATE the Ahnentafel Table for the PRIMARY PERSON
						populateAhnenTable(personArray,  thePrimaryID , 1, 1);
						populateAhnenTable(personArray,  thePrimaryID , 1, 2, "", "Bio");
						// alert("DONE Populating ...");
						// listOfAhns = "";
						// for (var i = 1; i < 32; i++) {
						// 	listOfAhns += " "  + ahnenIndexArray[i];
						// }
						//alert (theProfilePerson.FirstName );
						// alert(theTestTaker);


						console.log(personArray);
						console.log(ahnenIndexArray);
						let findMoreIDs = [];

						if (ahnenIndexArray[16] && personArray[ ahnenIndexArray[16] ] &&  personArray[ ahnenIndexArray[16] ].Father && personArray[ ahnenIndexArray[16] ].Father > 0) {
							console.log("DELVE FURTHER BACK ON THE MALE LINE", personArray[ ahnenIndexArray[16] ].Father );
							findMoreIDs.push(personArray[ ahnenIndexArray[16] ].Father);
						}

						if (ahnenIndexArray[31] && personArray[ ahnenIndexArray[31] ] &&  personArray[ ahnenIndexArray[31] ].Mother && personArray[ ahnenIndexArray[31] ].Mother > 0) {
							console.log("DELVE FURTHER BACK ON THE FEMALE LINE", personArray[ ahnenIndexArray[31] ].Mother);
							findMoreIDs.push(personArray[ ahnenIndexArray[31] ].Mother);
						}
						
						if (findMoreIDs.length == 0){
							reCustomize();
							loadPhotoAlbums();

						} else {
							findMoreAncestors(findMoreIDs, 4);
						}						

					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
			
		}

		function findMoreAncestors(findMoreIDsArray, lastNumGensLoaded) {
			console.log("findMoreAncestors", findMoreIDsArray, lastNumGensLoaded);
			consoleLogDIV("FIND more Ancestors: " + lastNumGensLoaded);
			const numberOfMoreGenerationsToRetrieve = 2;
			const maxMaleAhenNumberThisRound = 2 ** (lastNumGensLoaded + numberOfMoreGenerationsToRetrieve);
			let findMoreIDs = findMoreIDsArray[0];
			if (findMoreIDsArray.length > 1) {
				findMoreIDs += "," + findMoreIDsArray[1];
			}
			// ========================================================================================
			// (1)  LOAD up to TWO MORE GENERATIONS of ANCESTORS for Male or Female lines (as needed)
			// ========================================================================================
			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				//data: { 'action': 'getPerson', 'key': primaryID, 'fields': "Id, Name, FirstName, MiddleName, IsLiving, LastNameAtBirth, LastNameCurrent, Nicknames, LastNameOther, RealName, Prefix, Suffix, BirthLocation, DeathLocation, Gender, BirthDate, DeathDate,Father,Mother, Privacy, DataStatus", 'format': 'json' },
				data: { 'action': 'getPeople', 'appId':'PhotoLines' , 'keys': findMoreIDs ,'ancestors':numberOfMoreGenerationsToRetrieve, 'fields': "Id,Name,FirstName, RealName,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,Photo,BirthDate,DeathDate,BirthDateDecade,DeathDateDecade",'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					// alert(theJason);

					
					// if (data[0].status != 0) {
					if (data[0]) {
						// THERE BE DATA HERE ... 
						// console.log ("There is data : " + data[0].people);

						
						
						// COLLECT ALL of the PEOPLE (6 gens of ancestors of PrimaryID)
						for (var i in data[0].people) {
							personArray[ data[0].people[i].Id ] = data[0].people[i];
							console.log("Person " + i,data[0].people[i].Id );
						}
						
						
						// POPULATE the Ahnentafel Table for the PRIMARY PERSON
						populateAhnenTable(personArray,  thePrimaryID , 1, 1);
						populateAhnenTable(personArray,  thePrimaryID , 1, 2, "", "Bio");
						// alert("DONE Populating ...");
						// listOfAhns = "";
						// for (var i = 1; i < 32; i++) {
						// 	listOfAhns += " "  + ahnenIndexArray[i];
						// }
						//alert (theProfilePerson.FirstName );
						// alert(theTestTaker);


						console.log(personArray);
						console.log(ahnenIndexArray);
						let findSomeMoreIDs = [];

						if (ahnenIndexArray[maxMaleAhenNumberThisRound] && personArray[ ahnenIndexArray[maxMaleAhenNumberThisRound] ] &&  personArray[ ahnenIndexArray[maxMaleAhenNumberThisRound] ].Father && personArray[ ahnenIndexArray[maxMaleAhenNumberThisRound] ].Father > 0) {
							console.log("DELVE FURTHER BACK ON THE MALE LINE", personArray[ ahnenIndexArray[maxMaleAhenNumberThisRound] ].Father );
							findSomeMoreIDs.push(personArray[ ahnenIndexArray[maxMaleAhenNumberThisRound] ].Father);
						}

						let femaleMax = 2 * maxMaleAhenNumberThisRound - 1;
						if (ahnenIndexArray[femaleMax] && personArray[ ahnenIndexArray[femaleMax] ] &&  personArray[ ahnenIndexArray[femaleMax] ].Mother && personArray[ ahnenIndexArray[femaleMax] ].Mother > 0) {
							console.log("DELVE FURTHER BACK ON THE FEMALE LINE", personArray[ ahnenIndexArray[femaleMax] ].Mother);
							findSomeMoreIDs.push(personArray[ ahnenIndexArray[femaleMax] ].Mother);
						}
						
						if (findSomeMoreIDs.length == 0){
							reCustomize();
							loadPhotoAlbums();
						} else {
							findMoreAncestors(findSomeMoreIDs, lastNumGensLoaded + numberOfMoreGenerationsToRetrieve);
						}						

					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
			
		}			

  
  		// LOADING ANCESTORS' Photo Albums ... done AFTER all the Ancestors are well and truly loaded ...

  		function loadPhotoAlbumNow( albumNum = 0) {
			console.log ("loadPhotoAlbumNow", albumNum);
			
			let theID = listOfIDsForPhotos[albumNum];
			let theIDnum = listOfIDnumsForPhotos[albumNum];

			let LoadingDetailsDIV = document.getElementById("LoadingDetails");
			if (personArray[theIDnum]) {
				LoadingDetailsDIV.innerHTML = "Looking for Photos for ... who was that? ... right ... " + getBestFirstName(personArray[theIDnum]) + " " + personArray[theIDnum].LastNameAtBirth;
				console.log( "Looking for Photos for ... who was that? ... right ... " + getBestFirstName(personArray[theIDnum]) + " " + personArray[theIDnum].LastNameAtBirth);
			}

			// ========================================================================================
			// (1)  LOAD up to SIX GENERATIONS of ANCESTORS for Tester # 1  (theID)
			// ========================================================================================
			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				//data: { 'action': 'getPerson', 'key': primaryID, 'fields': "Id, Name, FirstName, MiddleName, IsLiving, LastNameAtBirth, LastNameCurrent, Nicknames, LastNameOther, RealName, Prefix, Suffix, BirthLocation, DeathLocation, Gender, BirthDate, DeathDate,Father,Mother, Privacy, DataStatus", 'format': 'json' },
				data: { 'action': 'getPhotos', 'appId':'PhotoLines' , 'key': theID, 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					// alert(theJason);
					console.log("STATUS:",data[0].status);
					console.log(data[0].photos);
					if (data[0].photos){
						ancestorPhotoAlbums[albumNum] = data[0].photos;
					} else {
						// No Photos ... possibly Permission Denied - check for PhotoData
						if (personArray[theIDnum].PhotoData) {							
							ancestorPhotoAlbums[albumNum] = 
							[{
								"URL_300" : personArray[theIDnum].PhotoData.path ,
								"ImageName" : personArray[theIDnum].Photo
							}];
						}
					}

					if (albumNum < listOfIDsForPhotos.length - 1) {
						loadPhotoAlbumNow(albumNum + 1);
					} else {
						console.log("ALL DONE ALBUMS!");
						console.log({ancestorPhotoAlbums});
					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			})
		}

		// ====== ========= - ============ === ===
		// HELPER FUNCTIONS - Manipulating the DOM
		// ====== ========= - ============ === ===

		function closeDIV(divName) {
			document.getElementById(divName).style.display = 'none';	
			if (currentDIV > '') {
				document.getElementById(currentDIV).style.display = 'block';		
				currentDIV = '';
			}
		}
		function showDIV(divName, swapDIV = "") {
			document.getElementById(divName).style.display = 'block';	
			if (swapDIV > "") {
				document.getElementById(swapDIV).style.display = 'none';	
				currentDIV = swapDIV;
			}
		}

		function toggleFAQs() {
			var currentStyle = document.getElementById('FAQs').style.display ;
				// alert (currentStyle);
			if (currentStyle == 'none'){
				document.getElementById('showFAQs').style = 'background-color:orange;';
				document.getElementById('FAQs').style.display = 'block';
				document.getElementById('FAQsScrollNote').style.display = 'block';
			} else {
				document.getElementById('showFAQs').style = 'background-color:#25422d;';
				document.getElementById('FAQs').style.display = 'none';
				document.getElementById('FAQsScrollNote').style.display = 'none';
			}		
				
		}

		function toggleWhatsNew() {
			var currentStyle = document.getElementById('WhatsNew').style.display ;
				// alert (currentStyle);
			if (currentStyle == 'none'){
			
				document.getElementById('WhatsNew').style.display = 'block';
				document.getElementById('WhatsNewScrollNote').style.display = 'block';
			} else {
			
				document.getElementById('WhatsNew').style.display = 'none';
				document.getElementById('WhatsNewScrollNote').style.display = 'none';
			}		
				
		}

		function copyToClipboard( whichTextArea ) {			
			// var whichCheckBox = "show" + whichType + "TextArea";
			// var whichTextArea =  whichType + "Code";
			// var isChecked = (document.getElementById(whichCheckBox).checked == true);

			
			// document.getElementById(whichTextArea).style.display = "block";
			
			var newLine = '\n'; 
			/* Get the text field */
			var copyText = document.getElementById(whichTextArea);		  
			/* Select the text field */
			copyText.select();
			copyText.setSelectionRange(0, 99999); /*For mobile devices*/
			// var typeOfCode = whichTextArea.substr(0, whichTextArea.length - 4).toUpperCase();
			/* Copy the text inside the text field */
			document.execCommand('copy');
			// toggleTextArea(whichType);
			/* Alert the copied text */

			  // if (typeOfCode.search("HTML") > -1 ) {
			  // 	realTypeOfCode = typeOfCode.substr(0, typeOfCode.search("HTML") );
			  // 	alertMsg = "HTML code for " + realTypeOfCode + ' DNA source citation copied to your clipboard.' + newLine + newLine + 'Use CTRL + V (on a PC) or CMD + V (on a Mac)\nto paste this HTML code into your web page.';
			  // } else {

			  	alertMsg =  /*typeOfCode +*/ ' Text has been copied to your clipboard.' + newLine + newLine + 'Use CTRL + V (on a PC) or CMD + V (on a Mac)\nto paste this WikiText code into \nyour Profile or into a Free Space page.';
			  // }

				if (whichTextArea == "rawHTML") {
								  	alertMsg =  ' Text has been copied to your clipboard.' + newLine + newLine + 'Use CTRL + V (on a PC) or CMD + V (on a Mac)\nto paste this HTML code into \nyour Web Page or into a blog post.';

				}

			  alert (alertMsg);
			  
		}


		function prepThePhotoPicker(ahnNum) {

			// pickAltPhoto
			// NameOfAltPhotoPeep
			// AltPhotoGallery
			let maleGIFfullURL = "https://www.wikitree.com/photo.php/8/83/Clarke-11007_G2G_pictures-1.gif";
			let femaleGIFfullURL = "https://www.wikitree.com/photo.php/4/43/Clarke-11007_G2G_pictures-3.gif";

			let thisPerson = personArray[ahnenIndexArray[ahnNum]];
			let thisName = getBestFirstName(thisPerson) + " "  + thisPerson.LastNameAtBirth;
			let whereInList = listOfIDnumsForPhotos.indexOf(thisPerson.Id);
			let thisPersonsPhotos = ancestorPhotoAlbums[whereInList];
			document.getElementById('pickAltPhoto').style.display = "block";
			document.getElementById('NameOfAltPhotoPeep').innerText = thisName;

			console.log(thisPerson);

			
			let starterURL = "";

			if (ahnNum == 1) {
				if (thisPerson.Gender == "Male") {
					starterURL =  "<img onclick=swapPhoto(" + ahnNum + "," + whereInList + ",-1) height=75px src=" + maleGIFfullURL + ">";
				} else if (thisPerson.Gender == "Female") {
					starterURL =  "<img onclick=swapPhoto(" + ahnNum + "," + whereInList + ",-2) height=75px src=" + femaleGIFfullURL + ">";
				} else {
					starterURL =  "<img onclick=swapPhoto(" + ahnNum + "," + whereInList + ",-1) height=75px src=" + maleGIFfullURL + ">" + "<img  onclick=swapPhoto(" + ahnNum + "," + whereInList + ",-2) height=75px src=" + femaleGIFfullURL + ">";
				}
			} else if (ahnNum % 2 == 0) {
				starterURL =  "<img onclick=swapPhoto(" + ahnNum + "," + whereInList + ",-1) height=75px src=" + maleGIFfullURL + ">";
			} else if (ahnNum % 2 == 1) {
				starterURL =  "<img onclick=swapPhoto(" + ahnNum + "," + whereInList + ",-2) height=75px src=" + femaleGIFfullURL + ">";
			}

			let theGalleryHTML = starterURL;

			if (thisPersonsPhotos){
						console.log(thisPersonsPhotos);
			
						for (var i = 0; i < thisPersonsPhotos.length; i++) {
							let thisPhotoObject =thisPersonsPhotos[i];
							theGalleryHTML +=  "<img  onclick=swapPhoto(" + ahnNum + "," + whereInList + "," + i + ") height=75px src='https://wikitree.com" + thisPhotoObject.URL_300 + "'>";
						}
			} else {
				console.log(" NO thisPersonsPhotos for THIS PERSON ! AhnNum #: ", ahnNum);
			}
			document.getElementById('AltPhotoGallery').innerHTML = theGalleryHTML;

		}

		function swapPhoto(ahnNum, whereInList, photoNum) {

			document.getElementById('pickAltPhoto').style.display = "none";
			if (photoNum == -1) {
				let maleGIFfullURL = "https://www.wikitree.com/photo.php/8/83/Clarke-11007_G2G_pictures-1.gif";
				document.getElementById('previewPic' + ahnNum).setAttribute("src", maleGIFfullURL);
			} else if (photoNum == -2) {
				let femaleGIFfullURL = "https://www.wikitree.com/photo.php/4/43/Clarke-11007_G2G_pictures-3.gif";
				document.getElementById('previewPic' + ahnNum).setAttribute("src", femaleGIFfullURL);
			} else {
				document.getElementById('previewPic' + ahnNum).setAttribute("src", "https://wikitree.com" + ancestorPhotoAlbums[whereInList][photoNum]["URL_300"]);
			}

			let thisPerson = personArray[ahnenIndexArray[ahnNum]];
			thisPerson['photoNumChosen'] = photoNum;
		}

		function findPhotoURL(thisPerson) {
			let whereInList = listOfIDnumsForPhotos.indexOf(thisPerson.Id);
			let photoNum = thisPerson.photoNumChosen;

			thePhotoURL = "https://wikitree.com" + ancestorPhotoAlbums[whereInList][photoNum]["URL_300"];

			return thePhotoURL;
		}

		function findWikiPhoto(thisPerson) {
			let whereInList = listOfIDnumsForPhotos.indexOf(thisPerson.Id);
			let photoNum = thisPerson.photoNumChosen;

			thePhotoURL = ancestorPhotoAlbums[whereInList][photoNum]["ImageName"];

			return thePhotoURL;
		}

		// When the document is ready, see if we have a user session with checkLogin().
		// If no, then show the "need login" div with the form and button to post to the API clientLogin() function.
		// If yes, then hide that and show our function content.
		$(document).ready(function(){ 
			// alert ("Ready");


			wikitree.init({});
			// alert("Init done");
			// wikitree.session.checkLogin({})
			
			login()
				.then(function(data) { 
					// transfer values from wikiTreeAuth
					wikitree.session.loggedIn = thisUserIsLoggedIn;
					wikitree.session.user_name = getUserName();
					wikitree.session.user_id = getUserId();

					// alert("in checkLogin");
					if (wikitree.session.loggedIn) { 
						/* We're already logged in and have a valid session. */
						// alert("Logged In @ " + wikitree.session.user_name);

						// DO NOT NEED THE LOGIN DIV 
						document.getElementById('need_login').style.display = 'none';
						document.getElementById('WikiID').value = wikitree.session.user_name;
						console.log("328");
						<?php 
								
							if ($_SESSION['id'] > '') {
								// Fill in form with username to make it quick and easy to generate your own surnames list

								echo "document.getElementById('WikiID').value = '" . $_SESSION['id'] . "';";
								

								
							} else   {
								// Fill in form with username to make it quick and easy to generate your own surnames list
							
																
							}  

							echo "console.log('id = " . $_SESSION['id'] . "')";

						?>	

						console.log("348");

						
						// document.getElementById('FormDIV2').style.display = 'none';
						// document.getElementById('FormDIV3').style.display = 'none';
						// document.getElementById('FormDIV4').style.display = 'none';
						// document.getElementById('FormDIV5').style.display = 'none';
						// document.getElementById('FormDIV6').style.display = 'none';
						// document.getElementById('FormDIV7').style.display = 'none';
						

						// document.TheForm.Profile.value = wikitree.session.user_name;
						// document.TheForm.WikiTreeUserName.value = wikitree.session.user_name;
						$('#need_login').hide();
						// $('#logged_in').show();
					} 
					else { 
						/* We're not yet logged in, but maybe we've been returned-to with an auth-code */
						// alert ("Not logged in yet - in process");

						// document.getElementById('AllTheSteps').style.display = 'none';
						// if (document.getElementById('ResetBtnPh1')){
						// 	document.getElementById('ResetBtnPh1').style.display = 'none';
						// 	document.getElementById('Prompts').style.display = 'none';
						// }


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
						console.log({queryParams});
						if (queryParams.has('authcode')) { 
							var authcode = queryParams.get('authcode');
							wikitree.session.clientLogin( {'authcode': authcode}  )
								.then(function(data) {
									if (wikitree.session.loggedIn) { 
										/* If the auth-code was good, redirect back to ourselves without the authcode in the URL (don't want it bookmarked, etc). */
										// alert("auth code is OK after all ?");
										
										document.getElementById('need_login').style.display = 'none';
										document.getElementById('WikiID').value = wikitree.session.user_name;
										

										if (id != 'none' ) {

											window.location.assign('https://apps.wikitree.com/apps/clarke11007/PhotoLines.php?id=' + id );

										} else {
											window.location.assign('https://apps.wikitree.com/apps/clarke11007/PhotoLines.php');
										}

									}
									else { 
										document.getElementById('need_login').style.display = 'block';
										//window.location = "https://api.wikitree.com/api.php?action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/surnamesTest.php";
										// alert ("has authcode but seemingly not logged in ???");
									}
								});
						} else { 
							document.getElementById('need_login').style.display = 'block';
							// alert ("has no authcode and seemingly not logged in ???");
							//window.location = "https://api.wikitree.com/api.php?action=clientLogin&returnURL=https://apps.wikitree.com/apps/clarke11007/surnamesTest.php";
							//alert ("NOT logged !!");
							// $('#need_login').show();
							// $('#logged_in').hide();
						}
					}
				});
			//alert ("After session.checkLogin");




		});

		
		function populateAhnenTable(personArray, ID, ahnNum, tbl = 1, thisGender = '', doBio = "") {
			console.log ("populateAhnenTable(personArray,  " + ID + " , " + ahnNum + " , " + tbl + " , " + thisGender + ")");
			var maxLength = Math.max(ahnenIndexArray.length, ahnenIndexArray2.length, ahnenIndexArray3.length)
			if (ahnNum == 1) {
				for (var i = 0; i < maxLength; i++) {
					if (tbl==2){
						ahnenIndexArray2[i] = -1;
					} else if (tbl==3){
						ahnenIndexArray3[i] = -1;
					} else {
						ahnenIndexArray[i] = -1;
					} 
					
				}
			}
			if (tbl==2){
				ahnenIndexArray2[ahnNum] = ID;
			} else if (tbl==3){
				ahnenIndexArray3[ahnNum] = ID;
			} else {
				ahnenIndexArray[ahnNum] = ID;
			} 
			//ahnenIndexArray[ahnNum] =  ID;
			if (personArray[ID]){
				if (personArray[ID].Gender == undefined && thisGender > '') {
					personArray[ID].Gender = thisGender;
					// personArray[ID].LastNameAtBirth = "Idunno";
					// alert (personArray[ID].Name + " " + ID + " # " + personArray[ID].Id+ " : now  = " +personArray[ID].Gender + " : " + thisGender);
				}

				// IF we are looking for Y DNA or autosomal DNA (Simple or Triangulation) we will ALWAYS want the FATHER in our AhenTable
				// BUT ... if we are looking for an mtDNA match - then never
				// AND ... if we are looking for an X-Chromosome match, we only want the Father in our AhnenTable IF the child (personArray[ID]) is themselves a daughter who could have inherited X from the father
				if (personArray[ID].BioFather && doBio == "Bio") {
					populateAhnenTable(personArray, personArray[ID].BioFather, 2*ahnNum, tbl, 'Male', doBio); 
					
				} else if (personArray[ID].Father) {
					populateAhnenTable(personArray, personArray[ID].Father, 2*ahnNum, tbl, 'Male', doBio); 
					
				}

				// IF we are looking for mtDNA or autosomal DNA (Simple or Triangulation) or an X-Chromosome match we will ALWAYS want the MOTHER in our AhenTable
				// BUT ... if we are looking for a Y DNA match - then never
				if (personArray[ID].BioMother && doBio == "Bio") {
					populateAhnenTable(personArray, personArray[ID].BioMother, 2*ahnNum + 1, tbl, 'Female', doBio); 
					
				} else if (personArray[ID].Mother ) {
					populateAhnenTable(personArray, personArray[ID].Mother, 2*ahnNum + 1, tbl, 'Female');
				}
				
			}

		}

		


		function showNextButton(num) {
			if (document.getElementById('NextButton' + num) ) {document.getElementById('NextButton' + num).style.display = 'block';}
		}
			

		function resetApp() {
			document.getElementById("FormStep3").style.display = "none";
			document.getElementById("FormStep2").style.display = "none";
			document.getElementById("FormStep1").style.display = "block";

			document.getElementById("LoadingDetails").innerHTML = "";

		}

		function generateWikiText() {
			document.getElementById("FormStep1").style.display = "none";
			document.getElementById("FormStep2").style.display = "none";
			document.getElementById("FormStep3").style.display = "block";
			showPreview(3);
			showWikiText();
			showHTMLtext();
		}

		function reCustomize() {
			document.getElementById("FormStep1").style.display = "none";
			document.getElementById("FormStep3").style.display = "none";
			document.getElementById("FormStep2").style.display = "block";
			showPreview();
		}

		function loadPhotoAlbums() {
			// return;

			console.log("loadPhotoAlbums");

			listOfIDsForPhotos = [ personArray[ahnenIndexArray[1]].Name ];
			listOfIDnumsForPhotos = [ personArray[ahnenIndexArray[1]].Id ];
			
			let currGenNum = 1;
			let foundInLastGen = true;

			while (foundInLastGen) {
				foundInLastGen = false;
				if (ahnenIndexArray[2 ** currGenNum] && ahnenIndexArray[2 ** currGenNum] > 0) {
					listOfIDsForPhotos.push(personArray[ahnenIndexArray[2 ** currGenNum]].Name);
					listOfIDnumsForPhotos.push(personArray[ahnenIndexArray[2 ** currGenNum]].Id);
					foundInLastGen = true;
				}
				if (ahnenIndexArray[2 ** (currGenNum + 1) - 1] && ahnenIndexArray[2 ** (currGenNum + 1) - 1] > 0) {
					listOfIDsForPhotos.push(personArray[ahnenIndexArray[2 ** (currGenNum + 1) - 1]].Name);
					listOfIDnumsForPhotos.push(personArray[ahnenIndexArray[2 ** (currGenNum + 1) - 1]].Id);
					foundInLastGen = true;
				}
				currGenNum++;
			}

			console.log("LIST of Ancestors that EXIST and are Worth looking into:", {listOfIDsForPhotos});
			currentPhotoNum = 0;
			ancestorPhotoAlbums = {};
			loadPhotoAlbumNow(0);
		}

		// function return2Step2() {
		// 	// alert ("Return to Step 2");
		// 	document.getElementById('FormDIV3').style.display = 'none';
		// 	document.getElementById('FormDIV2').style.display = 'block';
		// }
		 
		function changeNumGens( dGens ) {
			numGens += 1.0 * dGens;
			if (numGens < 2) { numGens = 2;}
			document.getElementById("numGensInPhotoLines").innerText = numGens + " generations";
			showPreview();
		}

		function getAhnNum4Type(countingNum=2, PatMatType="pat") {
			if (PatMatType == "pat") {
				return 2 ** countingNum ;
			} else {
				return 2 ** (countingNum + 1) - 1 ;
			}
		}

		function getBestFirstName(person) {
			if (person.FirstName) {
				return person.FirstName;
			} else if (person.RealName) {
				return person.RealName;
			} else {
				return "Private";
			}
		}

		function getLifeSpan(person) {
			if (!person.DeathDate || person.DeathDate.substring(0,4) == "0000") {
				if (person.BirthDate) {
					return "(b." + person.BirthDate.substring(0,4) + ")";
				} else {
					return "(b." + person.BirthDateDecade + ")";
				}
			} else if (person.BirthDate && person.DeathDate) {
				return "(" + person.BirthDate.substring(0,4) + " - " + person.DeathDate.substring(0,4) + ")";
			} else if (person.BirthDate) {
				return "(b." + person.BirthDate.substring(0,4) + ")";
			} else if (person.DeathDate) {
				return "(d." + person.DeathDate.substring(0,4) + ")";
			} else if (person.BirthDateDecade) {
				return "(b." + person.BirthDateDecade + ")";
			} else {
				return "";
			}
		}

		function showPreview(where = 2) {
			let liveLinks = true;
			let useBio = false;
			if (document.getElementById ("liveLinksNo").checked) {
				liveLinks = false;
			}
			if (document.getElementById ("famTypeBio").checked) {
				useBio = true;
			}

			let previewHTML = "";
			let previewTableRowPix = "<table border=0><tr bgcolor=white><td align=center colspan=XXCOLXX><B>" + document.getElementById("photoTitle").value + "</B></td><tr bgcolor=white>";

			if (liveLinks) {
				previewTableRowPix = "<table border=0><tr bgcolor=white><td align=center colspan=XXCOLXX><A target=_blank href=PhotoLines.php><B>" + document.getElementById("photoTitle").value + "</B></A></td><tr bgcolor=white>";
			}
			let previewTableRowText = "<tr bgcolor=white>";
			let numCols = 0;

			let maleGIF = "Clarke-11007_G2G_pictures-1.gif";
			let femaleGIF = "Clarke-11007_G2G_pictures-3.gif";

			let maleGIFfullURL = "https://www.wikitree.com/photo.php/8/83/Clarke-11007_G2G_pictures-1.gif";
			let femaleGIFfullURL = "https://www.wikitree.com/photo.php/4/43/Clarke-11007_G2G_pictures-3.gif";
			maxNumOfSpacesInNames = 0;

			let previewDIV = document.getElementById("preview");
			let printDIV = document.getElementById("printPreview");

			if (where == 3) {
				previewDIV = document.getElementById("previewOn3");
			}

			let style = "pat";

			document.getElementById ("WithWhom").innerText = "Dad";

			if (document.getElementById ("styleMat").checked) {
				style = "mat";
				document.getElementById ("WithWhom").innerText = "Mom";
			} else if (document.getElementById ("styleCombo").checked) {
				style = "both";
				document.getElementById ("WithWhom").innerText = "Mom";
			}

			let names = 'none';
			if (document.getElementById ("namesFirst").checked) {
				names = "first";
			} else if (document.getElementById ("namesFull").checked) {
				names = "full";
			}

			let showDates = document.getElementById("showDates").checked ;

			let size = "s";
			let sizeInPx = "55px";

			document.getElementById ("pixels").style.color = "lightgrey";
			if (document.getElementById ("sizesM").checked) {
				size = "m";
				sizeInPx = "100px";
			} else if (document.getElementById ("sizesCustom").checked) {
				document.getElementById ("pixels").style.color = "black";
				let numPixels = document.getElementById ("pixels").value;
				if (numPixels > 0) {
					size = Math.round(numPixels) + "px";
				} else {
					size = "75px";
				}
				sizeInPx = size;
			}

			let direction = "R";
			if (document.getElementById ("directionL").checked) {
				direction = "L";
			}

			let noPhoto = "Generic";
			if (document.getElementById ("noPhotoHide").checked) {
				noPhoto = "Hide";
			}


			
			previewHTML +=    style + " " + names + " "+ showDates + " "+ size + " "+ direction + " "+ numGens + " ";
			// previewHTML +=  document.getElementById("namesFirst").checked + " ";
			// previewHTML +=  document.getElementById("sizesS").checked + " ";

			previewHTML += "<br/>";



			for (var g = 0; g < numGens  ; g++) {
				let i = g;
				if (direction == "R") {
					i = numGens - g - 1;
				}

				let thisStyle = style;
				if (style == "both") {
					i = numGens - g - 1;
					thisStyle = "pat";
					if (direction == "R") {
						thisStyle = "mat";
					}
				}

				let ahnNum = getAhnNum4Type(i, thisStyle);
				let thisPerson = personArray[ahnenIndexArray[ahnNum]];
				if (useBio == true) { 
					thisPerson = personArray[ahnenIndexArray2[ahnNum]];
				}
				if (!thisPerson) {					
					continue;
				}

				let thisName = getBestFirstName(thisPerson);
				let theseDates = "";

				if (names == "none") {
					thisName = "";
				} else if (names == "full") {
					thisName = getBestFirstName(thisPerson) + " "  + thisPerson.LastNameAtBirth;
				}
				let foundSpaces = thisName.match(/ /g);
				if (foundSpaces && foundSpaces.length > 0) {
					maxNumOfSpacesInNames = Math.max(maxNumOfSpacesInNames , thisName.match(/ /g).length );
				}

				// console.log("Max#Spaces: ", maxNumOfSpacesInNames );


				if (showDates == true) {
					theseDates = getLifeSpan(thisPerson);
					// console.log(theseDates);
				} else {
					// console.log("no dates");
				}

				let openLink = "";
				let closeLink = "";
				if (liveLinks) {
					openLink = "<A target=_blank href=https://wikitree.com/wiki/" + encodeURIComponent(thisPerson.Name) + ">";
					closeLink = "</A>";
				}

				let imgOnClick = "id=previewPic" + ahnNum + " onclick='prepThePhotoPicker(" + ahnNum + ");' ";

				if (!thisPerson.PhotoData || thisPerson.Photo == "" )  {
					previewHTML += "NO PROFILE PIC for # " + (i+1) + " - Ahnentafel # " + ahnNum + " : " + thisName + " " + theseDates + "<br/>";
					if (noPhoto == "Hide") {
						continue;
					} else {
						numCols++;
						
						previewTableRowPix += "<td align=center><img " + imgOnClick + " height='" + sizeInPx + "' src=" + (ahnNum % 2 == 0 ? maleGIFfullURL : femaleGIFfullURL) + "></td>";
						previewTableRowText += "<td align=center>" + openLink +thisName.replace(/ /g,"<br/>") + closeLink + "<br/>" + theseDates  + "</td>";
					}
				} else {
					
					numCols++;					
				
					previewHTML += "Show Pic # " + (i+1) + " - Ahnentafel # " + ahnNum + " : " + thisName + " " + theseDates + "<br/>";

					let thePhotoPath = "https://wikitree.com" +thisPerson.PhotoData.path;
					console.log("Person photoNum:", thisPerson.photoNumChosen);
					if (thisPerson.photoNumChosen != undefined) {
						if (thisPerson.photoNumChosen == -1) {
							thePhotoPath = maleGIFfullURL;
						} else if (thisPerson.photoNumChosen == -2) {
							thePhotoPath = femaleGIFfullURL;
						} else {
							thePhotoPath = findPhotoURL(thisPerson);
						}
					}
					previewTableRowPix += "<td align=center><img  " + imgOnClick + "  height='" + sizeInPx + "' src=" + thePhotoPath + "></td>";
					previewTableRowText += "<td align=center>" + openLink +thisName.replace(/ /g,"<br/>") + closeLink + "<br/>" + theseDates  + "</td>";
				}
			}

			if (style == "both") {
				for (var g = 1; g < numGens; g++) {
					let i = g;
					 
					thisStyle = "mat";
					if (direction == "R") {
						thisStyle = "pat";
					}
					let ahnNum = getAhnNum4Type(i, thisStyle);
					let thisPerson = personArray[ahnenIndexArray[ahnNum]];
					if (useBio == true) { 
						thisPerson = personArray[ahnenIndexArray2[ahnNum]];
					}
					if (!thisPerson) {					
						continue;
					}

					let thisName = getBestFirstName(thisPerson);
					if (names == "none") {
						thisName = "";
					} else if (names == "full") {
						thisName = getBestFirstName(thisPerson) + " "  + thisPerson.LastNameAtBirth;
					}
					let foundSpaces = thisName.match(/ /g);
					if (foundSpaces && foundSpaces.length > 0) {
						maxNumOfSpacesInNames = Math.max(maxNumOfSpacesInNames , thisName.match(/ /g).length );
					}
					// console.log("Max#Spaces: ", maxNumOfSpacesInNames );


					let theseDates = "";
					if (showDates == true) {
						theseDates = getLifeSpan(thisPerson);
					}

					let openLink = "";
					let closeLink = "";
					if (liveLinks) {
						openLink = "<A target=_blank href=https://wikitree.com/wiki/" + encodeURIComponent(thisPerson.Name) + ">";
						closeLink = "</A>";
					}

					let imgOnClick = "id=previewPic" + ahnNum + " onclick='prepThePhotoPicker(" + ahnNum + ");' ";

					if (!thisPerson.PhotoData || thisPerson.Photo == "" )  {
						previewHTML += "NO PROFILE PIC for # " + (i+1) + " - Ahnentafel # " + ahnNum + " : " + thisName + " " + theseDates + "<br/>";

						if (noPhoto == "Hide") {
							continue;
						} else {
							numCols++;
							previewTableRowPix += "<td align=center><img  " + imgOnClick + " height='" + sizeInPx + "' src=" + (ahnNum % 2 == 0 ? maleGIFfullURL : femaleGIFfullURL) + "></td>";
							previewTableRowText += "<td align=center>" + openLink + thisName.replace(/ /g,"<br/>") + closeLink + "<br/>" + theseDates  + "</td>";
						}

					} else {
						numCols++;
						previewHTML += "Show Pic # " + (i+1) + " - Ahnentafel # " + ahnNum + " : " + thisName + " " + theseDates  + "<br/>";

						let thePhotoPath = "https://wikitree.com" +thisPerson.PhotoData.path;
						console.log("Person photoNum:", thisPerson.photoNumChosen);
						if (thisPerson.photoNumChosen != undefined) {
							if (thisPerson.photoNumChosen == -1) {
								thePhotoPath = maleGIFfullURL;
							} else if (thisPerson.photoNumChosen == -2) {
								thePhotoPath = femaleGIFfullURL;
							} else {
								thePhotoPath = findPhotoURL(thisPerson);
							}
						}

						previewTableRowPix += "<td align=center><img  " + imgOnClick + " height='" + sizeInPx + "' src='" + thePhotoPath + "'></td>";
						previewTableRowText += "<td align=center>" + openLink + thisName.replace(/ /g,"<br/>") + closeLink + "<br/>" + theseDates  + "</td>";
					}

				}
			}

			previewTableRowPix += "</tr>";
			previewTableRowText += "</tr></table>";

			previewTableRowPix = previewTableRowPix.replace("XXCOLXX", numCols);

			if (where == 0*2) {
				previewHTML += "<br/></br>" + previewTableRowPix + previewTableRowText;
			} else {
				previewHTML =  previewTableRowPix + previewTableRowText;
			}


			previewDIV.innerHTML = previewHTML;
			printDIV.innerHTML = previewHTML.replace(/\<img /g, `<img  onclick="togglePrintPreview(0);" `);
			currentSettings = {
			 	"style":style ,  "names":names , "showDates":showDates , "size":size , "sizeInPx":sizeInPx , "direction":direction , "noPhoto":noPhoto , "liveLinks":liveLinks
			};
		}

		function showHTMLtext() {
			let previewDIV = document.getElementById("previewOn3");
			let theRawHTML = previewDIV.innerHTML.replace("PhotoLines.php","https://apps.wikitree.com/apps/clarke11007/PhotoLines.php");

			theRawHTML = theRawHTML.replace(/onclick=\".*?;\"/g, "");

			document.getElementById("rawHTML").innerText = theRawHTML;
		}


		function showWikiText() {
			console.log("Max#Spaces: ", maxNumOfSpacesInNames );
			console.log({currentSettings});

			let maleGIF = "Clarke-11007_G2G_pictures-1.gif";
			let femaleGIF = "Clarke-11007_G2G_pictures-3.gif";

			let wikiDIV = document.getElementById("rawWikiText");
			let newLine = '\n';

			let openLink = "";
			let closeLink = "";
			if (currentSettings.liveLinks == true) {
				openLink = "[https://apps.wikitree.com/apps/clarke11007/PhotoLines.php ";
				closeLink = "] ";
			}
			let wikiTableRowPix = "{|" + newLine + "|+"+ openLink +"'''" + document.getElementById("photoTitle").value  + "'''" + closeLink + newLine + "|-" + newLine;
			let wikiTableRowText = "<tr>";

			let style = currentSettings["style"] ;
			let names = currentSettings["names"] ;
			let showDates = currentSettings["showDates"] ;
			let size = currentSettings["size"] ;
			let sizeInPx = currentSettings["sizeInPx"] ;
			let direction = currentSettings["direction"] ;
			let noPhoto = currentSettings["noPhoto"] ;

			for (var g = 0; g < numGens; g++) {
				let i = g;
				if (direction == "R") {
					i = numGens - g - 1;
				}

				let thisStyle = style;
				if (style == "both") {
					i = numGens - g - 1;
					thisStyle = "pat";
					if (direction == "R") {
						thisStyle = "mat";
					}
				}

				let ahnNum = getAhnNum4Type(i, thisStyle);
				let thisPerson = personArray[ahnenIndexArray[ahnNum]];
				if (!thisPerson) {					
					continue;
				}

				let thisName = getBestFirstName(thisPerson);
				let theseDates = "";

				if (names == "none") {
					thisName = "";
				} else if (names == "full") {
					thisName = getBestFirstName(thisPerson) + " "  + thisPerson.LastNameAtBirth;
				}

				if (showDates == true) {
					theseDates = getLifeSpan(thisPerson);
				} 
	
				let foundSpaces = thisName.match(/ /g);
				let numActualSpaces = 0;
				let extraBRs = "";
				if (foundSpaces && foundSpaces.length > 0) {
					numActualSpaces = foundSpaces.length;
				}
				if (numActualSpaces < maxNumOfSpacesInNames) {
					extraBRs = "<br> ".repeat(maxNumOfSpacesInNames - numActualSpaces + 1) ;
				}					

				openLink = "";
				closeLink = "";
				if (currentSettings.liveLinks == true) {
					openLink = "[[" + thisPerson.Name + "|";
					closeLink = "]] ";
				}

				if (!thisPerson.PhotoData || thisPerson.Photo == "" )  {
					if (noPhoto == "Hide") {
						continue;
					} else {
						wikiTableRowPix += "| {{Image|file=" + (ahnNum % 2 == 0 ? maleGIF : femaleGIF) + "| caption=" + openLink +thisName.replace(/ /g,"<br>") + closeLink + "<br>" + theseDates  + extraBRs + "|size=" + size + "}}" + newLine;
					}
				} else {
					let thisPersonPhoto = thisPerson.Photo;
					if (thisPerson.photoNumChosen != undefined) {
						if (thisPerson.photoNumChosen == -1 ) {
							thisPersonPhoto = maleGIF;
						} else if (thisPerson.photoNumChosen == -2 ) {
							thisPersonPhoto = femaleGIF;
						} else {
							thisPersonPhoto = findWikiPhoto(thisPerson);
						}
					}

					wikiTableRowPix += "| {{Image|file=" + thisPersonPhoto + "| caption=" + openLink + thisName.replace(/ /g,"<br>") + closeLink + "<br>" + theseDates  + extraBRs + "|size=" + size + "}}" + newLine;
				}
				// wikiTableRowPix += "<td align=center><img height='" + sizeInPx + "' src=https://wikitree.com" + thisPerson.PhotoData.path + "></td>";
				// wikiTableRowText += "<td align=center>" + thisName.replace(" ","<br/>") + "<br/>" + theseDates  + "</td>";
			}

			if (style == "both") {
				for (var g = 1; g < numGens; g++) {
					let i = g;
					 
					thisStyle = "mat";
					if (direction == "R") {
						thisStyle = "pat";
					}
					let ahnNum = getAhnNum4Type(i, thisStyle);
					let thisPerson = personArray[ahnenIndexArray[ahnNum]];
					if (!thisPerson) {					
						continue;
					}

					let thisName = getBestFirstName(thisPerson);
					if (names == "none") {
						thisName = "";
					} else if (names == "full") {
						thisName = getBestFirstName(thisPerson) + " "  + thisPerson.LastNameAtBirth;
					}

					let theseDates = "";
					if (showDates == true) {
						theseDates = getLifeSpan(thisPerson);
					}

					let foundSpaces = thisName.match(/ /g);
					let numActualSpaces = 0;
					let extraBRs = "";
					if (foundSpaces && foundSpaces.length > 0) {
						numActualSpaces = foundSpaces.length;
					}
					if (numActualSpaces < maxNumOfSpacesInNames) {
						extraBRs = "<br> ".repeat(maxNumOfSpacesInNames - numActualSpaces + 1) ;
					}	

					openLink = "";
					closeLink = "";
					if (currentSettings.liveLinks == true) {
						openLink = "[[" + thisPerson.Name + "|";
						closeLink = "]] ";
					}

					if (!thisPerson.PhotoData || thisPerson.Photo == "" )  {
						if (noPhoto == "Hide") {
							continue;
						} else {
							wikiTableRowPix += "| {{Image|file=" + (ahnNum % 2 == 0 ? maleGIF : femaleGIF) + "| caption=" + openLink + thisName.replace(/ /g,"<br>") + closeLink + "<br>" + theseDates  + extraBRs + "|size=" + size + "}}" + newLine;
						}
					} else {
						let thisPersonPhoto = thisPerson.Photo;
						if (thisPerson.photoNumChosen != undefined) {
							if (thisPerson.photoNumChosen == -1 ) {
								thisPersonPhoto = maleGIF;
							} else if (thisPerson.photoNumChosen == -2 ) {
								thisPersonPhoto = femaleGIF;
							} else {
								thisPersonPhoto = findWikiPhoto(thisPerson);
							}
						}

						wikiTableRowPix += "| {{Image|file=" + thisPersonPhoto + "| caption=" + openLink + thisName.replace(/ /g,"<br>") + "<br>" + closeLink + theseDates  + extraBRs + "|size=" + size + "}}" + newLine;
					}

					// previewHTML += "Show Pic # " + (i+1) + " - Ahnentafel # " + ahnNum + " : " + thisName + " " + theseDates  + "<br/>";

					// wikiTableRowPix += "<td align=center><img height='" + sizeInPx + "' src=https://wikitree.com" + thisPerson.PhotoData.path + "></td>";
					// wikiTableRowText += "<td align=center>" + thisName.replace(" ","<br/>") + "<br/>" + theseDates  + "</td>";

				}
			}

			wikiTableRowPix += "|}";
			// wikiTableRowText += "</tr></table>";


			wikiDIV.innerHTML = wikiTableRowPix ;//+ wikiTableRowText;
		}

		function togglePrintPreview( inPrintPreview = true) {
			 // class=DoNotPrint 
			let DoNotPrintDIVs = document.getElementsByClassName("DoNotPrint");
			let printPreviewDIV = document.getElementById("printPreview");

			console.log(DoNotPrintDIVs);
			if (inPrintPreview == true) {
				printPreviewDIV.style.display = "block";
			} else {
				printPreviewDIV.style.display = "none";
			}

			for (var i = 0; i < DoNotPrintDIVs.length; i++) {
				let div = DoNotPrintDIVs[i];
				console.log(div);

				if (inPrintPreview == true) {
					div.style.display = "none";
				} else {
					div.style.display = "revert";
				}
			}

		}
		</script>

	</HEAD>


	<BODY>		
		<DIV align=center>
		<DIV class=DoNotPrint>
		<h1>Photo Lines app</h1>
		<DIV id=versionDesc>
		<span align=center>Version 1.05 - updated 19 December 2025<br>&nbsp;</span>
		</DIV>
		<span id=TypeOfConfTitle></span>
		<DIV id=DNAhelp><P>This app is designed to help you create Photo Lines that can be added to any WikiTree Profile.<br>The standard use would be to visually document your paternal or maternal lineage.
		<P>Some members might like to add a <B>=== Photo Lines ===</B> subsection, but this is optional. <br/>
			<B>NOTE:</B> The current version of this app only retrieves the photo attached as the Profile Picture for each person.
	
		</DIV>

		</DIV>

		<DIV id=FormStep1 align=center style="display:block; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 1:  Whose Photo Lines do you want to create?<HR>
				<span id=FormStep1P1err name=FormStep1P1err style="display:none; background-color:yellow;"></span>
				<P><B>YOUR WIKITREE ID:</B><br/> <input type=text name=id id=WikiID value=""><BR><I>(e.g. Marcoux-336)<BR/></I>
				
					
				
				<HR>
				<input id=NextButton1  class="button" type="submit" value="NEXT" onclick="loadAncestors();" ><BR>&nbsp;
				<SPAN id=LoadingDetails></SPAN>

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		<DIV id=FormStep2 align=center style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 2:  Customize your Photo Lines<HR>
				<span id=FormStep1P1err name=FormStep1P1err style="display:none; background-color:yellow;"></span>
				<P>
				<DIV align=left>
				<B>TITLE:</B>	
					<input type=text onchange="showPreview();" name=photoTitle size=30 id=photoTitle value="My Photo Lines" style="padding:2px;">	
					<BR>

				<B>STYLE:</B>					
					<label><input type=radio onchange="showPreview();" name=style id=stylePat value="pat"  checked> Paternal</label>
					<label><input type=radio onchange="showPreview();" name=style id=styleMat value="mat"> Maternal</label>
					<label><input type=radio onchange="showPreview();" name=style id=styleCombo value="combo"> Combo					</label>
					<BR>
				<B>FAMILY TYPE:</B>					
					<label><input type=radio onchange="showPreview();" name=famType id=famTypeA1 value="A1"  checked> Primary/Adoptive</label>
					<label><input type=radio onchange="showPreview();" name=famType id=famTypeBio value="Bio"> Biological</label>
					
					<BR><BR>
					
				<B>NAMES:</B>	
					<label><input type=radio onchange="showPreview();" name=names id=namesFirst value="first" checked> First names</label>
					<label><input type=radio onchange="showPreview();" name=names id=namesFull value="full"> Full names</label>
					<label><input type=radio onchange="showPreview();" name=names id=namesNone value="none"> no names					</label>
					<BR>

				<B>DATES:</B>	
					<label><input type=checkbox onchange="showPreview();" name=dates id=showDates value="lifespan"> Show Lifespan 				</label>
					<BR>
					
				<B>IMAGE SIZES:</B>	
					<label><input type=radio onchange="showPreview();" name=sizes id=sizesS value="s"  checked> Small</label>
					<label><input type=radio onchange="showPreview();" name=sizes id=sizesM value="m"> Medium</label>
					<label><input type=radio onchange="showPreview();" name=sizes id=sizesCustom value="custom"> Custom:	</label>
							<input type=text  onchange="showPreview();" name=pixels id=pixels size=3 value="75" style="padding:2px; color:lightgrey;">px			
					<BR>

				<B>DIRECTION:</B>	
					<label><input type=radio onchange="showPreview();" name=direction id=directionL value="L"> You on the Left</label>
					<label><input type=radio onchange="showPreview();" name=direction id=directionR value="R" checked> You to the Right of <span id=WithWhom>Dad</span></span>
					<BR>
				<B>NO PROFILE PHOTO?:</B>	
					<label><input type=radio onchange="showPreview();" name=noPhoto id=noPhotoHide value="Hide"> Hide ancestor</label>
					<label><input type=radio onchange="showPreview();" name=noPhoto id=noPhotoGeneric value="Generic" checked> Use generic silhouette</label>
					<BR>
				<B>SHOW LIVE LINKS?:</B>	
					<label><input type=radio onchange="showPreview();" name=liveLinks id=liveLinksYes value="Yes" checked> YES - link names to profiles</label>
					<label><input type=radio onchange="showPreview();" name=liveLinks id=liveLinksNo value="No"> NO thanks.  Plain text is fine.</label>
					<BR><BR>
				

				<B>HOW MANY:</B>	
           		 <button class="btnSVG" onclick="changeNumGens(-1);"> <SVG width=18 height=14 ><polyline points="0,0 18,0 9,14 0,0" fill="blue" stroke="blue"/><polyline points="5,7 13,7" fill="none" stroke="white" stroke-width=2 /></SVG> </button>
            		[ <span id=numGensInPhotoLines>4 generations</span> ]
            	 <button class="btnSVG" onclick="changeNumGens(+1);"> <SVG width=18 height=14 ><polyline points="0,14 18,14 9,0 0,14" fill="red" stroke="red"/><polyline points="5,8 13,8" fill="none" stroke="white" stroke-width=2 /> <polyline points="9,3 9,13" fill="none" stroke="white" stroke-width=2 /> </SVG> </button> 
					<BR>					<BR>	

				<div align=right><I>Click on photo to choose alternate.</I></div>
				</DIV>
				<HR>

				<DIV id=preview></DIV>
					 
				<DIV id=pickAltPhoto style="display:none;">
					<HR/>
					<DIV>
						<B>Choose an alternate photo for <span id=NameOfAltPhotoPeep></span></B><BR/><BR/>

						<DIV id=AltPhotoGallery></DIV>
					</DIV>					 

				</DIV>
				
				<HR>
				<input id=PrevButton2  class="button" type="submit" value="BACK" onclick="resetApp();" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id=NextButton2  class="button" type="submit" value="NEXT" onclick="generateWikiText();" ><BR>&nbsp;
		

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		<DIV id=FormStep3 class=DoNotPrint align=center style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 3:  Use the WikiText below to Add to your Profile<HR>
				<span id=FormStep1P1err name=FormStep1P1err style="display:none; background-color:yellow;"></span>
				<P><B>Copy and Paste this WikiText into your Profile (in Edit mode):</B><br/> 
					<textarea id=rawWikiText rows=10 cols=100></textarea>
					<input id=PrevButton3  class="button" type="submit" value="COPY" onclick="copyToClipboard('rawWikiText');" ><BR>&nbsp;
					
				<HR>
				<p align=right><I>Click <A onclick="togglePrintPreview(1);">&#128424;</A> to print preview<br>then click on image to return to this screen.</I></p>
				<DIV id=previewOn3></DIV>
				
				<HR>

				<input id=PrevButton3  class="button" type="submit" value="BACK" onclick="reCustomize();" ><BR>&nbsp;
		

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;

				<P><B>Copy and Paste this HTML into any webpage or blog post:</B><br/> 
					<textarea id=rawHTML rows=10 cols=100></textarea>
					<input id=PrevButton3  class="button" type="submit" value="COPY" onclick="copyToClipboard('rawHTML');" ><BR>&nbsp;
					

		</DIV>	
		<!-- p align=right id=printPreviewReturn ><img  onclick="togglePrintPreview(0);" height=16px src="https://apps.wikitree.com/apps/clarke11007/pix/WikiTreeNewLogoFlag.png" /> </p -->
		<DIV id=printPreview style="display: none;"></DIV>


					

		<DIV id=FormDIV4Experimental align=center  style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">				
									
		</DIV>
		
	
		
		<DIV id=ResetDIV style="display:none;">
			<P>If you wish to start all over again ...<BR/>... click the RESET button below:</P>
			<input class="button" type="submit" value="RESET" onclick="resetApp('live');"><BR>&nbsp;
		</DIV>
		</DIV>
	    </DIV>


		<div id="need_login" align=center  style="display:block;">
		
	      <p>You are NOT currently logged in to apps.wikitree.com.<br/>
	      	To access the data in your Family Tree, you need to login.<br><br>
	         Login with the same information that you use for WikiTree.
	        <form action="https://api.wikitree.com/api.php" method="POST">
	        <input type="hidden" name="action" value="clientLogin">
	        <input type="hidden" name="returnURL" value="https://apps.wikitree.com/apps/clarke11007/PhotoLines.php">
	        <input type="submit" class="button" value="Client Login">
	        </form>
	      </p>
	      
	    </div>


<P align=center class=DoNotPrint>
	<SPAN id=WhatsNewScrollNote  style="display:none;">Scroll down to read about What's New<br><br></SPAN>
	<A target=_blank href=index.php>Other apps by Greg Clarke</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>Contact the author</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1729609/new-app-photo-lines>G2G post</A>  •  &nbsp;&nbsp; 
	<A onclick=toggleWhatsNew() ><u>What's New?</u></A> <br/>

	

	<!-- <BR><A onclick="resetApp('debug');">debug</A> -->
	<br><br>
	<!-- <input type="radio" id=resetRadioBtn name=appMode onclick="resetApp('live');" >reset live version  <input type="radio" name=appMode onclick="resetApp('debug');" >debug version<br> -->
	<SPAN id=FAQsScrollNote  style="display:none;">Scroll down to read the FAQs<br></SPAN>
	<input type=submit class=submit id=showFAQs value='FAQs' onclick='toggleFAQs();'><br/><br/>
</P>

<DIV id=FAQs style="display:none;">
<table border=0><tr><td width=25%>&nbsp;</td>
<td width=50% align=left><P>
<b>FREQUENTLY ASKED QUESTIONS</b><br/><br/>
<b>Q. What's the primary purpose of this app?</b><br/>
A. The primary purpose of this app is to allow WikiTreers to easily create a Photo Lineup of their direct male or female ancestors that they can then easily add to their Bio on their profile page..

<br><br>
<b>Q. Where do you get the pictures?</b><br/>
A. The pictures for each ancestor come from the photo attached to their Profile page, the one set as their Primary Photo.  Also, any images uploaded to the ancestor's profile page - OR - that have the ancestor tagged into them are available for swapping into the Photo Line.

<br><br>
<b>Q. What if I want a different picture for an ancestor?</b><br/>
A. As of April 25, 2024, you can now click on any picture (in Step 2 - Customize) and choose an alternate picture. You no longer have to change the Primary Photo.<BR><BR>Note: If you did change the Primary Photo set to an ancestor, the app will use the new one as the default photo the next time you run the app.<UL><LI>Go to your Images tab, <LI>upload a new photo, or select one that is already there. <LI>Then, scroll down to the section where you can click on the person's name and hit the button to SET AS PRIMARY PHOTO. </UL>


<br><br>
<b>Q. What is COMBO mode ?</b><br/>
A. Combo mode will show both your Paternal AND your Maternal lines in one row - with YOU being the monkey in the middle, and your parents, and grandparents, etc.., radiating out from you.

<br><br>
<b>Q. Why are you showing me generic silhouettes of some of my ancestors?</b><br/>
A. Those ancestors don't have Primary Photos attached to them - either they don't exist :-( OR you just haven't uploaded them yet and set as primary.

<br><br>
<b>Q. What if my ancestor's picture is really blurry, or is not a photo but a source?</b><br/>
A. In that case, you can click on the photo when you're in STEP 2 : Customize, and choose the generic Silhouette image instead, or any other image attached to your ancestor.

<br><br>
<b>Q. Do I have to show those generic silhouettes ?</b><br/>
A. No, you can select the option to HIDE ANCESTOR in the NO PROFILE PHOTO? setting, and then only those with actual Primary Photos attached will show up in your Photo Lines.


<br><br>
<b>Q. So ... how do I get this beautiful Photo Line into my Profile ?</b><br/>
A. Once you've gone through steps 1,2,3 of the app, and customized the Photo Lines the way you want, then 
<UL><LI>at Step 3, </LI>
	<LI>click the COPY button to select all of the WikiText that has been generated for you.  
		<UL><LI>(You can also use your mouse to select and copy it, if you want to go old-school.)</LI></UL>
	</LI>
	<LI>Now, switch tabs in your browser to your Profile page</LI>
	<LI>and go into EDIT mode.</LI>
	<LI>Find the spot where you want your Photo Lines to appear, and then PASTE it. (CTRL + V or CMD + V, or right-click in the edit box and choose PASTE from the pop-up menu)</LI>
	<LI><I>OPTIONAL: You might like to add a </I> <B>=== Photo Lines ===</B><I> subsection header before the Photo Lines WikiText so that it appears in its own area of your profile.</I>
</UL>


<br><br>
<b>Q. Wait a second ... one of my parents is coming up Private .. what's with that, and how can I fix that ?</b><br/>
A. If your parent's (or grandparent's) profile is a Private WikiTree profile (i.e. they are Living people, but maybe not active WikiTreers), then an image uploaded to their profile page will also be marked as private.  However, if they are OK with you using their image, there is a way you can make their picture public so that it shows up on your page. (Thanks to Sandy Patak who passed along these steps.)
<UL><LI>Add your parents profile photo to a Personal Space Page</LI>
		<UL>
			<LI>The personal space page must have Privacy set to Open.  Once that's done, then any image included on it will also automatically be made open or public.</LI>
			<LI>To create a personal space page, <A target=_blank href=https://www.wikitree.com/index.php?action=newspace>click here</A>.</LI>
		</UL>
	<LI>To add the photo to the space page, take the image id and add it anywhere on the space page.<br/>
		example:   [[Image:Dodrill-70.jpg|250px|William Dodrill]]
	</LI>

	
	<LI><I>ALTERNATIVELY: You could upload your parents photo to your own profile, and then edit the WikiText that is created by the app to use that photo, or any photo from WikiTree.</I>

	<LI>
</UL>


<br><br>
<b>Q. There's a photo that has my ancestor in it, that's uploaded somewhere else in WikiTree - can I use that in my Photo Lines ?</b><br/>
A. IF your ancestor is tagged in that photo, then it should popup as an option when you click on your ancestor's photo in STEP 2 : Customize <I>(new feature as of 25 April 2024, option to swap alternate photos for your ancestor)</I>.<BR><UL><LI>Tag your ancestor in a photo by<UL>
	<LI>Clicking on the photo you found on WikiTree, to open up to the Photo information page.</LI>
	<LI>Add to the "In this image:" section by clicking on the [add/edit] link, </LI>
	<LI>This should auto-scroll you down to the EDIT IMAGE DETAILS section ..</LI>
	<LI>Find the <B>Add a person or thing:</B> line, and enter your ancestor's WikiTree ID in there</LI>
	<LI>THEN (don't forget this part!) - click the SAVE CHANGES TO IMAGE DETAILS button</LI>
</UL>
</LI>
</UL>



<br><br>
<b>Q. Sometimes my row of photos don't appear even or all photos the same size, why is that ?</b><br/>
A. IF you have a lot of photos in a row, or a narrow screen, the Wiki Table that holds the photos will try and adjust itself to optimize what you can see on the screen with a minimum of scrolling.  To do that, it can't always keep everything at the same size, or in view.  Viewing the same profile on a wider screen will probably look fine.   IF there are noticeable problems that don't seem to be caused by auto-formatting, contact Greg, the author, so it can be fixed.

<br><br>
<b>Q. There's something new on the bottom of the page in STEP 3 - what's that ?</b><br/>
A. Thank you for noticing.  That is the same code that generates the Photo Lines in HTML format.  If you want to share your Photo Lines across the web, on your own website, or in a blog post, you can copy that HTML and it will show the same Photo Lines that you just customized.



 
<br><br>If you have other questions you'd like answered, please <A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>contact the author!</A>

</td> <td width=25%></td></tr></table>
&nbsp;<br><br>
</DIV>
      
<DIV id=WhatsNew  style="display:none;">
<table border=0 align=center><tr><td width=25%>&nbsp;</td>
<td width=50% align=left><P align=center>
<span style="background-color: orange;"><b>&nbsp;What's New / App Updates&nbsp;</b></span><br/><br/>
<I>Many thanks to the consultation and feedback<BR>provided by Sandy Patak, Azure Robinson, Betsy Ko, Mags Gaulden, and Eowyn the Forest Elf,<BR>Patty LaPlante, Gerry Hopkins<BR>Thanks also to beta-testers and bug-finders<BR>(your name could go here too!) ...</I><BR>
<br/><b>Version 1.05 - updated 19 December 2025</b><br/>
* Added appId to API calls. 
* Made radio button text clickable<br/>
<br/><b>Version 1.04 - updated 28 April 2024</b><br/>
* Fixed bug where Client Login button persisted (even after being logged in)<br/>
<br/><b>Version 1.03 - updated 27 April 2024</b><br/>
* Fixed bug found by Patty (not generating WikiText on right side of combo mode properly for swapped pix)<br/>
* Added option for printer-friendly view (just the bare Photo Lines) in Step 3. (Gerry's suggestion)<br/>
<br/><b>Version 1.02 - updated 25 April 2024</b><br/>
* Added option to swap photos for alternates.<br/>
* Added option to copy the raw HTML, to include in web page or blog post.<br/>
<br/><b>Version 1.01 - updated 9 April 2024</b><br/>
* Added option for Live Links to app and to ancestor profiles.<br/>
* Added optional parameter (id=WikiTreeID) to auto-start the app using the provided WikiTreeID.<br/>
<br/><b>Version 1.00 - created 8 April 2024</b><br/>
* Initial version created.<br/>

<br><br><br><A onclick=toggleWhatsNew() ><u>Close up What's New?</u></A> <br/>

</td> <td width=25%></td></tr></table>
&nbsp;<br><br>
</DIV>
     
     <DIV id=ConsoleLogDIV class=DoNotPrint ></DIV>

	</BODY>

</HTML>