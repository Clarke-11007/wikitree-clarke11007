<?php

// ====================================
// DNA Confirmation Citation Maker app
// ====================================

// 
// Programmed by Greg Clarke

// Input - WikiTree Profile IDs
// Output - DNA Confirmation source citations

/*

	APP LOGIC FLOW
	==============

	INITIAL LOAD : when DOCUMENT READY: wikiTree . checkLogin function run --> displays USER LOGIN button if needed

			function resetApp --> takes you back to Step 0

	Step 0 - Main Menu - Choose what type of DNA Confirmation you want to do
		Simple (2 people, up to 3rd cousins)
		Triangulation (3 people - or more if needed - all or at least some beyond 3rd cousins)
		mtDNA - female direct line
		YDNA - male direct line
		XDNA - two or three people matching on the X Chromosome path on a common X Chromosome segment

			function beginConf( parameter if not simple ) leads to appropriate screen for Step 1

	Step 1 - Enter the WikiTree ID of the DNA testers.  Optionally, use an Alias for a non-WikiTree tester.  
		Ideally, even a non-tester will have an ancestor on WikiTree, in which case, enter THAT person's WikiTree ID in the slot for ID # 2, and identify the relationship to the DNA tester

			* appropriate DIV is loaded with input fields for WikiTree ID #s, and Continue button ... which triggers the appropriate ..

			function loadSimpleInfo()
			function loadTriangulationInfo()
			etc... 

			Simple: --> loads ID # 1 + 6gens of ancestors; if valid, then gets DNA Kit #s if available, and sets up AhnenTable
					--> then tries to do the same for ID # 2.
						--> if successful on both accounts, checks for direct ancestor relationship, and if not, then tries to find matching MRCA(s)
						-->	perform genderCheckFromSimple, either loads form for manual entry if Gender if private and can't be read / OR / advances directly to ....

			function followUpAfterLoadSimple() --> will skip steps 2 and 3 and jump straight to step 4 ....


	Step 2 - Find the MRCA(s).  Use getPeople to load ancestors (up to ancestors:6 for Simple Conf, 3xGG, and up to ancestors:8  for Triangulation)
		* This step automated if all DNA test takers are WikiTree members and have public profiles
	 	* In the case of an ALIAS, user must manually choose Ancestor(s) and then  ...

	Step 3 - HOW related ?
		* This step automated if all DNA test takers are WikiTree members and have public profiles
		* In the case of an ALIAS ... then ... identify HOW the other DNA tester is related to the Common Ancestor(s)

	Step 4 - Build / Show the MiniTree (identifying connections between all DNA test takers and MRCAs)
	
			function updateMiniTree() (for Simple )


	Step 5 - Questions: Confirm / Answer YES to key criteria

	Step 6 - Enter details of DNA match

	Step 7 - Generate DNA Confirmation source citation statements



	===================
	KEY DATA STRUCTURES
	===================

	personArray : Array of Objects returned from getPeople calls, indexed by ID #

	DNAtesttakerList : Array of Objects 
		{ShortName (first name or alias), FullName,ID (#), WikiID (Clarke-11007), numRemovedGens, Gender}
		Note: numRemovedGens used to show relationships between Aliased testtakers and closest WikiID profile on WikiTree, stored in object

	ahnenIndexArrays : Array of Arrays

	MRCAlist : Array of personArray ID#s, sorted by collective distance from testtakers if more than 1 in list

	ahnNum2MRCAmatrix [ TestTakerNum, MRCAnum ] : 2 dimensional array

	relationshipMatrix [ TestTakerA, TestTakerB ] : 2 dimensional square array , based on # of DNAtesttakers (Simple = 2, Tri = 3 or more)


	function pronounOf( TestTakerNum , caseType ) -> function that returns he / she / they or He / She / They
	function possessiveOf( TestTakerNum ) -> function that returns his / her / their
	
	

		// ========== =========
		// PROCESSING FUNCTIONS
		// ========== =========
		
		// INITIALIZATION FUNCTIONS
		function resetApp(typeMode = 'live')
		function beginConf(whichType)
		function initKitNums()

		// ADVANCING TO NEXT STEP FUNCTIONS
		function doneForm1()
		function doneForm1Gender()
		function doneForm1Tri()
		function doneForm1Xpair()
		function doneForm1Xtrio()
		function doneForm2()
		function doneForm3()
		function doneForm4()
		function doneForm5()
		function doneForm6()
		function doneForm7()

		// LOAD INFO
		function loadSimpleInfo()
		function loadComplexInfo()
		function loadComplexInfo2()
		function loadComplexInfo3()
		function loadMTInfo()
		function loadTriangulationInfo()
		function loadTriInfo( whereAfter = 3)
		function loadTriInfo2()
		function loadXInfo()
		function verifyCoupleHoodOKforXmatch( anc1, anc2, anc3=0)
		function loadYInfo()


		// GENDER CHECKS
		function setupGenderDIV(P1, P2, P3, isP2object, isP3object)
		function genderCheck()
		function genderCheckFromSimple( whereToNext = 0)
		function genderCheckFromComplex( whereToNext = 0)


		=============================================

		function closeDIV(divName)
		function showDIV(divName, swapDIV = "")
		function toggleFAQs()
		function toggleWhatsNew()
		function copyToClipboard( whichTextArea )

		function populateAhnenTable(personArray, ID, ahnNum, tbl = 1, thisGender = '')
		
		function resetAhnenIndices()

		function initKitNums()
		function genderCheck()

		function doneForm1()
		function doneForm1Tri()
		function doneForm1Xpair()
		function doneForm1Xtrio()
		function loadTriangulationInfo()
		function loadXInfo()
		function loadYInfo()
		function loadMTInfo()

		function updateNextSetOfPeeps()
		function updateNextSetOfPeepsPlusSome(currentSet)
		function updateNextSetOfPeepsPlusSome2(currentSet, primeID)

		function verifyCoupleHoodOKforXmatch( anc1, anc2, anc3=0)

		function newWikiTreeIDentered(num)
		function theIDbIsINtheArray(AhNum, thisID, num )

		function reportAllPeeps()

		function setupCustomCitationFields()

		function howManyAncestorLevelsInCommon( theAncs1, theAncs2 , theArray1, theArray2)
		function changeCousinFormat( numType , doDoneForm6 = true)
		function defineRelationshipsBetweenSimpleAliasOnly( theRel1, theRel2, GenderP1,GenderP2)
		function defineRelationshipsBetween(theAncs1, theAncs2, numGens1, numGens2 , arrayNum1, arrayNum2)
		function getRelationshipReportLine(OrigID1, OrigID2, Person1,Person2,NumGensFromWiki1, NumGensFromWiki2, relOfP2toP1, relOfP1toP2, clr = 'black')

		function prepDIV6Duo()
		function prepDIV6Uni()
		function prepDIV6Trio()

		function changedChromoNum()
		function changedXmatchAmount()

		function customizeCitations()
		function customizeCitationsClose()
		function customizeChromosomes()
		function customizeChromosomesClose()
		function addNote()
		function addNoteClose()

		function relationshipAtGen( gen )
		function relationshipAtGenRemoved( gen, removed, isHalf = false )

		function howGrandIsIt( num )
		function addBRandRemoved(howMuch)

		function followUpNextSetOfPeeps(gen = 1, tbl = 1)

		function loadDNAkitNumbers( theID4kit, num)
		function loadComplexInfo()
		function loadComplexInfo2()
		function loadComplexInfo3()

		function chooseAlternateWikiTreeID4Tester(whichFunction, whichIDnum, whichID, statusMsg)

		function fixedIDandTryAgain(fixType)		
		function setupWarningRePersonNWikiTreeID(N, whichForm, whichID, statusMsg)
		(prev) function setupWarningRePerson1WikiTreeID(whichForm, whichID, statusMsg)
		(prev) function setupWarningRePerson2WikiTreeID(whichForm, whichID, statusMsg)

		function loadTriInfo( whereAfter = 3)
		function loadTriInfo2()

		function howGreatIsThisOrNot( theRel )
		function SonOrDaughter( person )
		function SonOrDaughter2( theGender )
		function HisOrHer( person )
		function HisOrHer2( theGender )
		function FatherOrMother( person )
		function FatherOrMother2( theGender )

		function showDirectAncestorMiniTree()

		function addToIgnoreArray(theI, ignoreTheseArray, maxAhnentafelNumber)

		function changeSimpleMRCAs()

		function buildHTMLcodeForTable( matchInfoArray )

		function loadSimpleInfo()

		function setupGenderDIV(P1, P2, P3, isP2object, isP3object)
		function genderCheckFromSimple( whereToNext = 0)
		function genderCheckFromComplex( whereToNext = 0)

		function switchToSimple(p1, p2)
		function followUpAfterLoadSimple()
		function followUpAfterLoadComplex()

		function doneForm1Gender()

		function howRelated2WikiTreeIDsWithMRCAfound( whichStep = 3)

		function changeRelationship()
		function changeAncestor()
		function updateMiniTree()

		function loadDescendantsOfMRCA(theValue)

		function hideAllDropDownsInMiniTree()
		function getAncPeepAtDropDown( num )
		function updatePeepDDs( selectNum, howManyGens)

		function howRelated( whichStep = 3)
		function howRemoved( whichStep = 3)
		function changeP2CAsubGender(level, gender)

		function doneForm2()
		function doneForm3()
		function doneForm4()
		function doneForm5()

		function changedDNAcompany()
		function changedTriDNAcompany()
		function changedXDNAcompany()
		function changedYDNAcompany()
		function changedMTDNAcompany()

		function compareKitsNote(DNAcompany, Kit1, Kit2, DNAtype)

		function assembleCitation(theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false)
		function assembleCitation2(theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false)
		function assembleCitation3(theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false)
		function assembleExtraCitations(DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction)

		function theBestFirstName( thePerson )
		function theNextBestFirstName( thePerson )
		function theBestLastName( thePerson )
		function theNextBestLastName( thePerson )
		function relationshipBetween4inLineage( testTakerFullName, testTakerFirstName, testTakerGender, theChild, thePerson, theAncestor, childsAhnNum, AncsAhnNum, testTakerAlias = '', numGensFromWikiTree = 0)
		function relationshipBetween4inLineage4SimpleAliases( testTakerFullName, testTakerFirstName, testTakerGender, theChild, thePerson, theAncestor, RelNum1 , RelNum2 , testTakerAlias = '', numGensFromWikiTree = 0)

		function swapBonusInfo( which = 2 )
		function showHideExtraInfo()

		function doneForm6()
		function doneForm7()
		function hideAllDIVs()
		function showNextButton(num)
		function return2Step2()
		function return2Step(whichStep)
		function updateGoBackLinks(whichStep)

		function resetApp(typeMode = 'live')

		function beginConf(whichType)

		function fillInID4TT1spawn()

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
}



?>

	<HTML>
		<HEAD>
		<TITLE>DNAconf</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="css/base.css"/>
		<LINK REL="stylesheet" TYPE="text/css" HREF="WTapps.css"/>

		<!-- For convenience, use JQuery for our Ajax interactions with the API. Use the cookie module to grab any existing user id. --> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
		<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.js"></script>      -->
		<script src="/apps/jquery.cookie.js"></script>
		<!-- <script src="/apps/wikitree.js"></script> -->
		<script src="/apps/clarke11007/wikitree.js"></script>
		<script src="./js/WikiTreeAuthGPC.js"></script>

		<script type="text/javascript">

		var appID = "DNAconf";
		var API_URL = 'https://api.wikitree.com/api.php';
		var SNP_Tree_Explorer_URLstarter = 'http://scaledinnovation.com/gg/treeExplorer.html?tab=subtree&query=';
		var SNP_Tracker_URLstarter = 'http://scaledinnovation.com/gg/snpTracker.html?tab=snps&snp=';

		var maxNumAncestorsForTriangulation = 10; // will allow up to 6th cousins in the mix
		var maxAhnentafelNumber4TriangulationMRCA = 127; // 2**7 - 1; 4th Great Grandparent level
		var doExtendedAncestorSearch4Triangulation = false;
		var listOfExtendedAncsToLoad = [];
		var theEndProviso = "";
		var GenerationsBreakpointForSharedCM = 25; // 25 cM is the breakpoint at which (for Triangulation) we are OK with 10 generations vs 14 generations
		var testerNamesForCitations = [];
		var showDeathWarning = false;

		var versionNum = "version 3.12 (updated 14.Oct.2026)";

		var theID = '';
		var theID2 = '';
		var theID2b = '';
		var theID3 = '';
		var theID3b = '';
		var theOriginalID2 = '';
		var theOriginalID3 = '';

		var personArray = {};
		var ahnenIndexArrays = [ [], [], [] ];
		var theDNAtesttakerList = [{}, {}, {}];
		var theMRCAlist = [];
		var falsePositiveRateList = [ 100, 99.96, 99.64, 96.98, 81.49, 47.83, 19.74, 9.72, 6.45, 3.33, 2.03, 1.36, 0.55];
		// var theProfilePersons = [null, null, null, null];
		var relationshipMatrix = {};
		var ahnNum2MRCAmatrix = [];
		var yayTestersList = [];
		var theTesterFullnessInfo = [];

		var currentSetOfPeeps = [];
		var nextSetOfPeeps = [];
		var ahnenIndexArray = [];
		var ahnenIndexArray2 = [];
		var ahnenindexarray3 = [];
		var theRel = "";
		var theTestTaker = "";
		var theAncsAhnNums = [];
		var theAncsAhnNum = 0;
		var theAncsAhnNum2 = 0;
		var theAncsAhnNum3 = 0;
		var theTT1isActually = 'Self';
		var theTypeOfRel = "";
		var P1peepsGender = [-1,-1, -1, -1, -1, -1,-1,-1, -1, -1, -1, -1];
		var P2peepsGender = [-1,-1, -1, -1, -1, -1,-1,-1, -1, -1, -1, -1];
		var P3peepsGender = [-1,-1, -1, -1, -1, -1,-1,-1, -1, -1, -1, -1];

		var commonAncestorDetails = []; // array of {Id:ID #, isCouple:TrueFalse, Ahnens:Array of ahnenIndexOccurrences Arrays} objects
		var currentPrivateIDstarter = 50000000;

		var currentDIV = '';
		var theDNAconfType = '';
		var whereToGoAfterChoosingAlternateWikiTreeID = 0	;
		var whichIDnumForAlternateWikiTreeID = 0;
		var numGensFromWikiTreeID2toCousin = 0;
		var numGensFromWikiTreeID3toCousin = 0;

		var theMarkedVerifiedCheckbox = "<div align=right><input onclick='doVerifyMarkDone(this);' textareaID=123  type=checkbox>Marked. Citation added. DONE !</div>";
		var theMVCheckboxMarked = "<div align=right><input onclick='doVerifyMarkDone(this);'  textareaID=123 type=checkbox>Marked. Citation added. DONE !</div>";
		var theMVCheckboxVerified = "<div align=right><input onclick='doVerifyMarkDone(this);'  textareaID=123 type=checkbox>Verified. DONE !</div>";

		var nonBiologicalRelationshipFound = false; // used to track whether a non-biological (adoptive parent) relationship is part of the tree (and thus not useable for DNA)
		var floatingMiniTreeTableX = "<span align=right><button style='padding:5px; background-color:red;' onclick='hideMiniTree();'>&nbsp;x&nbsp;</button></span><BR/>";
		var floatingMiniTreeTableHTML = "<TABLE border=0 style='background-color:aliceblue;'>"; 
		var nonBioList = "";

		var isHalfRelationship = false;

		function setNonBioMode( newValue, childID, parentID) {
			console.log("setNonBioMode", newValue, childID, parentID);
			nonBiologicalRelationshipFound = newValue;
			if (childID > 0 && parentID > 0 && personArray[1.0*childID] && personArray[1.0*parentID] ) {
				nonBioList += personArray[childID].RealName + " " + personArray[childID].LastNameAtBirth + " is non-biological child of " + personArray[parentID].RealName + " " + personArray[parentID].LastNameAtBirth + "<BR/>";
			} else {
				nonBioList += "ID # " + childID + " is non bio child of ID # " + parentID + "<BR/>";
			}

			document.getElementById("NonBioListOfPeeps").innerHTML = nonBioList;
			document.getElementById("WarningNonBioFound").style.display = (newValue == true ? "revert" : "none");

		}

		function doVerifyMarkDone( chkbox ) {
			console.log( chkbox , chkbox.checked);

			let theIDnum = chkbox.getAttribute("textareaID");

			if (chkbox.checked) {
				if (document.getElementById("theInfoPara" + theIDnum)) {document.getElementById("theInfoPara" + theIDnum).style.backgroundColor = "lime";}
				if (document.getElementById("theInfoXPara" + theIDnum)) {document.getElementById("theInfoXPara" + theIDnum).style.backgroundColor = "lime";}
				if (document.getElementById("ExtraCitation" + theIDnum)) {document.getElementById("ExtraCitation" + theIDnum).style.backgroundColor = "lime";}
				if (document.getElementById("ExtraCitationX" + theIDnum)) {document.getElementById("ExtraCitationX" + theIDnum).style.backgroundColor = "lime";}
				if (theIDnum == "Top") {
					if (document.getElementById("CAsourceCitation" )) {document.getElementById("CAsourceCitation" ).style.backgroundColor = "lime";}
				}
			} else {
				if (document.getElementById("theInfoPara" + theIDnum)) {document.getElementById("theInfoPara" + theIDnum).style.backgroundColor = document.getElementById("theInfoPara" + theIDnum).getAttribute("bgClr");}
				if (document.getElementById("theInfoXPara" + theIDnum)) {document.getElementById("theInfoXPara" + theIDnum).style.backgroundColor = document.getElementById("theInfoXPara" + theIDnum).getAttribute("bgClr");}
				if (document.getElementById("ExtraCitation" + theIDnum)) {document.getElementById("ExtraCitation" + theIDnum).style.backgroundColor = "white";}
				if (document.getElementById("ExtraCitationX" + theIDnum)) {document.getElementById("ExtraCitationX" + theIDnum).style.backgroundColor = "white";}
				if (theIDnum == "Top") {
					if (document.getElementById("CAsourceCitation" )) {document.getElementById("CAsourceCitation" ).style.backgroundColor = "white";}
				}
			}
			

		}

		var VerifiedByABC = '';
		var whichPathAfterGenderCheck = 0;	
		var kitNums = new Array();
		var currentNegative = -1;
		var testTaker2Gender = '';
		var testTaker3Gender = '';
		var postMiniTreeRelationshipReport = '';
		var cousinFormat = 'M';
		var showGenderWarning4XMatchPair = false;

		var showRelConnLink = true;
		var showGEDcomCompareLink = true;
		var showHaplogroupLink = true;
		var showDNAconfAppLink = false;
		var showWhitePaperLink = false;
		var credit4DNAconfAppPreamble = "";

		var mrcaDescendantsArray = [];
		var aliasClosest2isMRCA = false;
		var aliasClosest3isMRCA = false;

		var Person1FullName4Citation = '';
		var Person1FirstName4Citation = '';
		var Person2FullName4Citation = '';
		var Person2FullName4CitationFirstMention = '';
		var Person2FirstName4Citation = '';
		var Person3FullName4Citation = '';
		var Person3FirstName4Citation = '';
		var altWikiNameForPerson2 = "";

		var theCurrentMatchesList = [];
		var theCurrentMatchNum = 0;
		var generationsFromMRCA = 0;

		var DNApainterRelationship = "";

		var statusCodesInMiniTreeTracker = [];  // used to track which status codes are used, and hence, which belong in the Legend

		let thisAppCurrentSettings = {
			cousinFormat : "M",
			showRelConnLink : true,
			showGEDcomCompareLink : true,
			showHaplogroupLink : true,
		}

 	const SVGbtnCLOSE = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2ZM15.36 14.3C15.65 14.59 15.65 15.07 15.36 15.36C15.21 15.51 15.02 15.58 14.83 15.58C14.64 15.58 14.45 15.51 14.3 15.36L12 13.06L9.7 15.36C9.55 15.51 9.36 15.58 9.17 15.58C8.98 15.58 8.79 15.51 8.64 15.36C8.35 15.07 8.35 14.59 8.64 14.3L10.94 12L8.64 9.7C8.35 9.41 8.35 8.93 8.64 8.64C8.93 8.35 9.41 8.35 9.7 8.64L12 10.94L14.3 8.64C14.59 8.35 15.07 8.35 15.36 8.64C15.65 8.93 15.65 9.41 15.36 9.7L13.06 12L15.36 14.3Z" fill="red"/>
        </svg>`;

    const SVGbtnSETTINGS = `<svg height="16" width="16" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
            viewBox="0 0 512 512"  xml:space="preserve">
        <style type="text/css">
            .st0{fill:#000000;}
        </style>
        <g>
            <path class="st0" d="M499.453,210.004l-55.851-2.58c-5.102-0.23-9.608-3.395-11.546-8.103l-11.508-27.695
                c-1.937-4.728-0.997-10.145,2.455-13.914l37.668-41.332c4.718-5.188,4.546-13.205-0.421-18.182l-46.434-46.443
                c-4.986-4.967-13.003-5.159-18.2-0.412l-41.312,37.668c-3.778,3.443-9.206,4.402-13.924,2.436l-27.694-11.488
                c-4.718-1.946-7.864-6.454-8.094-11.565l-2.589-55.831C301.675,5.534,295.883,0,288.864,0h-65.708
                c-7.02,0-12.831,5.534-13.156,12.562l-2.571,55.831c-0.23,5.111-3.376,9.618-8.094,11.565L171.64,91.447
                c-4.737,1.966-10.165,1.007-13.924-2.436l-41.331-37.668c-5.198-4.746-13.215-4.564-18.201,0.412L51.769,98.198
                c-4.986,4.977-5.158,12.994-0.422,18.182l37.668,41.332c3.452,3.769,4.373,9.186,2.416,13.914l-11.469,27.695
                c-1.956,4.708-6.444,7.873-11.564,8.103l-55.832,2.58c-7.019,0.316-12.562,6.118-12.562,13.147v65.699
                c0,7.019,5.543,12.83,12.562,13.148l55.832,2.579c5.12,0.229,9.608,3.394,11.564,8.103l11.469,27.694
                c1.957,4.728,1.036,10.146-2.416,13.914l-37.668,41.313c-4.756,5.217-4.564,13.224,0.403,18.201l46.471,46.443
                c4.967,4.977,12.965,5.15,18.182,0.422l41.312-37.677c3.759-3.443,9.207-4.392,13.924-2.435l27.694,11.478
                c4.719,1.956,7.864,6.464,8.094,11.575l2.571,55.831c0.325,7.02,6.136,12.562,13.156,12.562h65.708
                c7.02,0,12.812-5.542,13.138-12.562l2.589-55.831c0.23-5.111,3.376-9.619,8.094-11.575l27.694-11.478
                c4.718-1.957,10.146-1.008,13.924,2.435l41.312,37.677c5.198,4.728,13.215,4.555,18.2-0.422l46.434-46.443
                c4.967-4.977,5.139-12.984,0.421-18.201l-37.668-41.313c-3.452-3.768-4.412-9.186-2.455-13.914l11.508-27.694
                c1.937-4.709,6.444-7.874,11.546-8.103l55.851-2.579c7.019-0.318,12.542-6.129,12.542-13.148v-65.699
                C511.995,216.122,506.472,210.32,499.453,210.004z M256.01,339.618c-46.164,0-83.622-37.438-83.622-83.612
                c0-46.184,37.458-83.622,83.622-83.622s83.602,37.438,83.602,83.622C339.612,302.179,302.174,339.618,256.01,339.618z"/>
        </g>
        </svg>`;


		// COOKIE PROCEDURES


// setCookie("wtapps_DNAconf", JSON.stringify(thisAppCurrentSettings), {
// setCookie("wtapps_DNAconf", JSON.stringify(thisAppCurrentSettings), {
// setCookie("wtapps_DNAconf", JSON.stringify(thisAppCurrentSettings), {

	function getCookie(name) {
        console.log("Welcome to cookie4thisApp - getCookie:",name);
        return cookie4thisApp(name) || null;
    }

    function setCookie(name, value, options) {
    	console.log("Welcome to cookie4thisApp - setCookie:",name);
        return cookie4thisApp(name, value, options);
    }
/**
    **
 * Utility function to get/set cookie data.
 * Adapted from https://github.com/carhartl/jquery-cookie which is obsolete and has been
 * superseded by https://github.com/js-cookie/js-cookie. The latter is a much more complete cookie utility.
 * Here we just want to get and set some simple values in limited circumstances to track an API login.
 * So we'll use a stripped-down function here and eliminate a prerequisite. This function should not be used
 * in complex circumstances.
 *
 * @param {*} key The name of the cookie to set/read. If reading and no key, then array of all key/value pairs is returned.
 * @param {*} value The value to set the cookie to. If undefined, the value is instead returned. If null, cookie is deleted.
 * @param {*} options Used when setting the cookie:
 *            - options.expires = Date or number of days in the future (converted to Date for cookie)
 *            - options.path, e.g. "/"
 *            - options.domain, e.g. "apps.wikitree.com"
 *            - options.secure, if true then cookie created with ";secure"
 * @returns
 */

function cookie4thisApp (key, value, options) {
    if (options === undefined) {
        options = {};
    }

    // If we have a value, we're writing/setting the cookie.
    if (value !== undefined) {
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === "number") {
            var days = options.expires;
            options.expires = new Date();
            options.expires.setDate(options.expires.getDate() + days);
        }
        value = String(value);
        return (document.cookie = [
            encodeURIComponent(key),
            "=",
            value,
            options.expires ? "; expires=" + options.expires.toUTCString() : "",
            options.path ? "; path=" + options.path : "",
            options.domain ? "; domain=" + options.domain : "",
            options.secure ? "; secure" : "",
        ].join(""));
    }

    // We're not writing/setting the cookie, we're reading a value from it.
    var cookies = document.cookie.split("; ");

    var result = key ? null : {};
    for (var i = 0, l = cookies.length; i < l; i++) {
        var parts = cookies[i].split("=");
        var name = parts.shift();
        name = decodeURIComponent(name.replace(/\+/g, " "));
        var value = parts.join("=");
        value = decodeURIComponent(value.replace(/\+/g, " "));

        if (key && key === name) {
            result = value;
            break;
        }
        if (!key) {
            result[name] = value;
        }
    }
    return result;
};



		// ========== =========
		// PROCESSING FUNCTIONS
		// ========== =========
		
		// INITIALIZATION FUNCTIONS

		// RESET to STEP 0
		function resetApp(typeMode = 'live') {
			// alert(typeMode);
			allDIVs = ['TooDistant', 'FormDIV1ab', 'FormDIV1abc',  'FormDIV1err' , 'FormDIV1abP1err','FormDIV1abP2err','FormDIV1abcP1err','FormDIV1abXP1err','FormDIV1abcXP1err', 'FormDIV1abX', 'FormDIV1b','FormDIV1contacting', 'FormDIV1gender', 'FormDIV1YGenderWarning', 'FormDIV2', 'FormDIV3', 'FormDIV4TooFar4SimpleWarning','FormDIV4MultipleMRCAs' ,'FormDIV4','FormDIV4direct','FormDIV4abc', 'FormDIV5', 'FormDIV6' ,'FormDIV6MT','FormDIV6X','FormDIV6Y','FormDIV6TRI', 'FormDIV7', 'ResetDIV' ];
			document.getElementById('DIV5warning').style.display = 'none';
			document.getElementById('versionDesc').style.display = 'block';
			document.getElementById('FormDIV1Warning').style.display = 'none';
			document.getElementById('FormDIV1WarningDeathDates').style.display = 'none';
			document.getElementById('TypeOfConfTitle').innerHTML = '';
			document.getElementById('FormDIV1WarningDeadTesters').innerText = '';
			document.getElementById('YourDNA').innerHTML = '';
			document.getElementById('ListOfNamesDIV').innerHTML = "";
			document.getElementById('GoBackLinks').innerHTML = "";			

			document.getElementById('DNAhelp').innerHTML = "<P>This app is designed to help you create proper source citations to add to your profiles to record your <a target=_blank href='https://www.wikitree.com/wiki/Help:DNA_Confirmation'>DNA Confirmations</a>.</P><P><B>How to Add Source Citations</B><BR>Every time you mark a child's relationship to their parent as <A target=_blank href=https://www.wikitree.com/wiki/Help:Confirmed_with_DNA>Confirmed with DNA</A><BR>you must add a source citation to the <B>== Sources ==</B> section<BR> of the <B><I>child's</I></B> profile that explains how you made the DNA-confirmed conclusion.</P><P><A target=_blank href=https://www.wikitree.com/wiki/Help:Sources>Sources</A> are essential for our mission to create an accurate <A target=_blank href=https://www.wikitree.com/wiki/Help:Collaborative_Family_Tree>single family tree</A>.<BR>If there is <U>not</U> a proper source citation for a DNA-confirmed conclusion,<BR>then, the <A target=_blank href=https://www.wikitree.com/wiki/Help:Relationship_Status>relationship status</A> should be changed by you or another member to <A target=_blank href=https://www.wikitree.com/wiki/Help:Confident>Confident</A>.</P><P>Some members also like to add a <B>=== DNA ===</B> subsection<BR/>under the <B>== Biography ==</B> section, but this is optional.<BR/>It is like a Research Notes section for genetic genealogy.</P>";

			hideMiniTree(true);

			if (typeMode == 'live') {
				for (var i = 0; i < allDIVs.length; i++) {
					document.getElementById(allDIVs[i]).style.display = 'none';
				}
				document.getElementById('ChooseDNAconfType').style.display = 'block';
				// document.getElementById('resetRadioBtn').checked = true;
				document.getElementById('GoBackLinks').innerHTML = "";
				for (var x = 1; x <= 4; x++) {
					document.getElementById('P2peepRadios' + x).style.display = 'revert';
					document.getElementById('P2peepRealNameRel' + x).textContent = '';
				}
			} else if (typeMode == 'debug') {
				for (var i = 0; i < allDIVs.length; i++) {
					document.getElementById(allDIVs[i]).style.display = 'block';
				}
			} 
		} 

		// MOVE from STEP 0 to STEP 1, based on whichType, open up proper DIV for entering WikiTree IDs
		function beginConf(whichType) {
			// alert("Time to begin DNA Confirmation : " + whichType);
			theDNAconfType = whichType;
			whichForm1 = "FormDIV1ab";

			document.getElementById('ChooseDNAconfType').style.display = 'none';
			document.getElementById('ResetDIV').style.display = 'block';
			document.getElementById('versionDesc').style.display = 'none';
			document.getElementById('FormDIV4direct').style.display = 'none';
			document.getElementById('FormDIV5SuchAs').textContent = 'GEDmatch';
			document.getElementById('TT1isSelfCHK').checked = true;

			if (whichType == '') {
				document.getElementById('TypeOfConfTitle').innerHTML = '<H2>Simple Autosomal DNA Confirmation</H2>';
				document.getElementById('DNAhelp').innerHTML = "WikiTree Help page for <a target=_blank href='https://www.wikitree.com/wiki/Help:DNA_Confirmation'>DNA Confirmation</a>.";
				theDNAconfType = '1';
				fillInID4TT1spawn();
				document.getElementById('TT1chooseSelfChildGrandKidRADIOS').style.display = "block";


			} else if (whichType == 'Tri') {
				document.getElementById('TypeOfConfTitle').innerHTML = '<H2>Autosomal DNA Confirmation via Triangulation</H2>';
				document.getElementById('DNAhelp').innerHTML = "WikiTree Help page for <a target=_blank href='https://www.wikitree.com/wiki/Help:Triangulation'>Triangulation</a>.";
				document.getElementById('FormDIV1ab').style.display = 'none';
				whichForm1 = "FormDIV1abc";
			} else if (whichType == 'x') {
				document.getElementById('TypeOfConfTitle').innerHTML = '<H2>X-chromosome DNA Confirmation</H2>';
				document.getElementById('DNAhelp').innerHTML = "WikiTree Help page for <a target=_blank href='https://www.wikitree.com/wiki/Help:Advanced_DNA_Confirmations'>Advanced DNA Confirmation</a>.";
				whichForm1 = "FormDIV1abX";
				// document.getElementById('SpecialXnote').style.display = 'block';
			} else if (whichType == 'y') {
				document.getElementById('TypeOfConfTitle').innerHTML = '<H2>Y DNA Confirmation</H2>';
				document.getElementById('DNAhelp').innerHTML = "WikiTree Help page for <a target=_blank href='https://www.wikitree.com/wiki/Help:Y-Chromosome_DNA_Confirmation'>Y-chromosome DNA Confirmation</a>.";
				document.getElementById('FormDIV5SuchAs').textContent = 'mitoYDNA.org';
				document.getElementById('TT1chooseSelfChildGrandKidRADIOS').style.display = "none";
			} else if (whichType == 'mt') {
				document.getElementById('TypeOfConfTitle').innerHTML = '<H2>Mitochondrial DNA (mtDNA) Confirmation</H2>';
				document.getElementById('DNAhelp').innerHTML = "WikiTree Help page for <a target=_blank href='https://www.wikitree.com/wiki/Help:Advanced_DNA_Confirmations'>Advanced DNA Confirmation</a>.";
				document.getElementById('FormDIV5SuchAs').textContent = 'mitoYDNA.org';
				document.getElementById('TT1chooseSelfChildGrandKidRADIOS').style.display = "none";
			} 

			document.getElementById(whichForm1).style.display = 'block';
			updateGoBackLinks(0);
		}
	

		// ADVANCING TO NEXT STEP FUNCTIONS


		function doneForm1() {
			// alert("Done 1");
			theID = document.getElementById('TTaker1').value ;
			theID2 = document.getElementById('TTaker2').value ;
			theID2b = '';
			theID3 = '';
			theID3b = '';
			numGensFromWikiTreeID2toCousin = 0;
			numGensFromWikiTreeID3toCousin = 0;
			generationsFromMRCA = 0;
			resetAhnenIndices();
			
			theTT1isActually = 'self';
			// console.log("DONE FORM 1: radio buttons are:" , document.getElementById('TT1isSelfCHK').checked , document.getElementById('TT1isChildCHK').checked , document.getElementById('TT1isGrandChildCHK').checked );
			if (document.getElementById('TT1isChildCHK').checked) {
				theTT1isActually = 'child';
			} else if (document.getElementById('TT1isGrandChildCHK').checked) {
				theTT1isActually = 'grandchild';
			}

			theOriginalID2 = theID2;

			theTestTakerID = '';
			theAncsAhnNum4TT = 0;
			document.getElementById('FormDIV1ab').style.display = 'none';
			document.getElementById('FormDIV1abP1err').style.display = 'none';
			document.getElementById('FormDIV1contacting').style.display = 'block';
			document.getElementById('SearchPN').textContent = 1;
			document.getElementById('SearchPM').textContent = 2;
			document.getElementById('ListOfNamesDIV').innerHTML = "";

			document.getElementById("relationship3").value = 'Choose relationship';
			document.getElementById("Div3CA2P2").textContent = '';
			document.getElementById("P2peepRealNameRel1").textContent = '';
			document.getElementById("P2peepRealNameRel2").textContent = '';
			document.getElementById("P2peepRealNameRel3").textContent = '';
			document.getElementById("P2peepRealNameRel4").textContent = '';
			document.getElementById("P2peepRealNameRel5").textContent = '';

			initKitNums();
			maxFollowupNumOfGens = 5;
			if (theDNAconfType == 1) {
				loadSimpleInfo();
			} else if (theDNAconfType == 3) {
				maxFollowupNumOfGens = 8;
				document.getElementById('SearchPM').textContent = 3;
				loadTriangulationInfo();
			} else if (theDNAconfType == 'x') {
				maxFollowupNumOfGens = 8;
				loadXInfo();
			} else if (theDNAconfType == 'y') {
				maxFollowupNumOfGens = 14;
				loadYInfo();
			} else if (theDNAconfType == 'mt') {
				maxFollowupNumOfGens = 14;
				loadMTInfo();
			}
		}

		function doneForm1Tri() {
			// alert("Done 1");
			closeEnough4Simple = [false, false, false];
			ahnenIndexArrays[3] = [];
			for (var i = 4; i < ahnenIndexArrays.length; i++) {
				ahnenIndexArrays[i] = [];
			}

			document.getElementById('SearchPN').textContent = 1;
			document.getElementById('SearchPM').textContent = 3;
			theID = document.getElementById('TTakerA').value ;
			theID2 = document.getElementById('TTakerB').value ;
			theID3 = document.getElementById('TTakerC').value ;
			numGensFromWikiTreeID2toCousin = 0;
			numGensFromWikiTreeID3toCousin = 0;
			resetAhnenIndices();

			theOriginalID2 = theID2;
			theOriginalID3 = theID3;

			theTestTakerID = '';
			theAncsAhnNum4TT = 0;
			document.getElementById('FormDIV1abc').style.display = 'none';
			document.getElementById('FormDIV1abcP1err').style.display = 'none';
			document.getElementById('FormDIV1contacting').style.display = 'block';
			document.getElementById('ListOfNamesDIV').innerHTML = "";

			initKitNums();
			if (theDNAconfType == 'Tri') {
				maxFollowupNumOfGens = 9;
				loadTriangulationInfo();
			} 
		}

		function doneForm1Xpair() {
			// alert("Done 1");
			document.getElementById('SearchPN').textContent = 1;
			theID = document.getElementById('TTakerAX2').value ;
			theID2 = document.getElementById('TTakerBX2').value ;
			theID3 = "";
			numGensFromWikiTreeID2toCousin = 0;
			numGensFromWikiTreeID3toCousin = 0;
			resetAhnenIndices();

			theOriginalID2 = theID2;
			theOriginalID3 = theID3;

			theTestTakerID = '';
			theAncsAhnNum4TT = 0;
			document.getElementById('FormDIV1abc').style.display = 'none';
			document.getElementById('FormDIV1abX').style.display = 'none';
			document.getElementById('FormDIV1abXP1err').style.display = 'none';
			document.getElementById('FormDIV1abcXP1err').style.display = 'none';
			document.getElementById('FormDIV1contacting').style.display = 'block';
			document.getElementById('ListOfNamesDIV').innerHTML = "";

			initKitNums();
			
			maxFollowupNumOfGens = 9;
			document.getElementById('SearchPM').textContent = 2;
			loadXInfo();
		
		}

		function doneForm1Xtrio() {
			// alert("Done 1");
			document.getElementById('SearchPN').textContent = 1;
			theID = document.getElementById('TTakerAX3').value ;
			theID2 = document.getElementById('TTakerBX3').value ;
			theID3 = document.getElementById('TTakerCX3').value ;
			numGensFromWikiTreeID2toCousin = 0;
			numGensFromWikiTreeID3toCousin = 0;
			resetAhnenIndices();

			theOriginalID2 = theID2;
			theOriginalID3 = theID3;

			theTestTakerID = '';
			theAncsAhnNum4TT = 0;
			document.getElementById('FormDIV1abc').style.display = 'none';
			document.getElementById('FormDIV1abX').style.display = 'none';
			document.getElementById('FormDIV1abXP1err').style.display = 'none';
			document.getElementById('FormDIV1abcXP1err').style.display = 'none';
			document.getElementById('FormDIV1contacting').style.display = 'block';
			document.getElementById('ListOfNamesDIV').innerHTML = "";

			initKitNums();
			if (theID3 > '') {
				maxFollowupNumOfGens = 9;
				document.getElementById('SearchPM').textContent = 3;
				// loadTriangulationInfo();
				loadXInfo();
			}  else {
				maxFollowupNumOfGens = 9;
				document.getElementById('SearchPM').textContent = 2;
				loadXInfo();
			}
		}



		function doneForm1Gender() {
			// alert ("All done Gender Check");
			// alert (document.getElementById('GenderFormP1isMale').style.display + " & " + document.getElementById('GenderFormP2isMale').style.display)

			P1isOK = document.getElementById('GenderFormP1isMale').style.display == 'revert' || document.getElementById('GenderFormP1isFemale').style.display == 'revert';
			P2isOK = document.getElementById('GenderFormP2isMale').style.display == 'revert' || document.getElementById('GenderFormP2isFemale').style.display == 'revert';
			P3isOK = document.getElementById('GenderFormP3isMale').style.display == 'revert' || document.getElementById('GenderFormP3isFemale').style.display == 'revert';

			if (document.getElementById('GenderFormP1isUndefined').style.display == 'revert'  || document.getElementById('GenderFormP1isUndefined').style.display == '' ) {
				if ( document.getElementById('GenderFormP1radioMale').checked ) {
					theProfilePerson.Gender = 'Male';
					P1isOK = true;
				} else if ( document.getElementById('GenderFormP1radioFemale').checked ) {
					theProfilePerson.Gender = 'Female';
					P1isOK = true;
				} 
			}
			theProfilePersonGENDER = theProfilePerson.Gender;
			// alert (theProfilePersonGENDER);
			if (document.getElementById('GenderFormP2isUndefined').style.display == 'revert' ) {
				if (theProfilePerson2){
					if ( document.getElementById('GenderFormP2radioMale').checked ) {
						theProfilePerson2.Gender = 'Male';
						P2isOK = true;
					} else if ( document.getElementById('GenderFormP2radioFemale').checked ) {
						theProfilePerson2.Gender = 'Female';
						P2isOK = true;
					} 
					theProfilePerson2GENDER = theProfilePerson2.Gender;
				} else {
					 
					if ( document.getElementById('GenderFormP2radioMale').checked ) {
						theTestPersonGender = 'Male';
						P2isOK = true;
					} else if ( document.getElementById('GenderFormP2radioFemale').checked ) {
						theTestPersonGender = 'Female';
						P2isOK = true;
					}
					theProfilePerson2GENDER = theTestPersonGender;
				}
			} else {

				theProfilePerson2GENDER = theProfilePerson2.Gender;
				
			}
			// theProfilePerson2GENDER = theProfilePerson2.Gender;
			// alert (theProfilePersonGENDER + " " + theProfilePerson2GENDER);  alert(3501);
			// alert(document.getElementById('GenderFormShowP3').style.display );
			// alert(document.getElementById('GenderFormP3isUndefined').style.display );
			// alert(3504);
			// alert(document.getElementById('GenderFormShowP3').style.display == 'revert' && document.getElementById('GenderFormP3isUndefined').style.display == 'revert' );
			// alert(3506);
			theProfilePerson3GENDER = '';
			// alert(3508);

			var showP3 = (document.getElementById('GenderFormShowP3').style.display == 'revert') ;
			var showP3undefineed = (document.getElementById('GenderFormP3isUndefined').style.display == 'revert') ;

			if ((document.getElementById('GenderFormShowP3').style.display == 'revert') ) {
				// alert ('GenderFormShowP3 - T');
				if (document.getElementById('GenderFormP3isUndefined').style.display == 'revert' ) {		
					// alert ('GenderFormP3isUndefined - T');

				} else {
					// alert ('GenderFormP3isUndefined - F');

				}
			} else {
				// alert ('GenderFormShowP3 - F');
				if (document.getElementById('GenderFormP3isUndefined').style.display == 'revert' ) {		
					// alert ('GenderFormP3isUndefined 2 - T');

				} else {
					// alert ('GenderFormP3isUndefined 2 - F');

				}

			}
			// alert ("shows: " + showP3  + " && " + showP3undefineed);
			// alert ("shows: " + (showP3  && showP3undefineed));
			var theShows = (showP3 && showP3undefineed);

			if (theShows == true) {
				// alert (3538);
				if (theProfilePerson3){
					if ( document.getElementById('GenderFormP3radioMale').checked ) {
						theProfilePerson3.Gender = 'Male';
						P3isOK = true;
					} else if ( document.getElementById('GenderFormP3radioFemale').checked ) {
						theProfilePerson3.Gender = 'Female';
						P3isOK = true;
					} 
					theProfilePerson3GENDER = theProfilePerson3.Gender;
				} else {
					 
					if ( document.getElementById('GenderFormP3radioMale').checked ) {
						theTestPersonGender = 'Male';
						P3isOK = true;
					} else if ( document.getElementById('GenderFormP3radioFemale').checked ) {
						theTestPersonGender = 'Female';
						P3isOK = true;
					}
					theProfilePerson3GENDER = theTestPersonGender;
				}
			} else if (theProfilePerson3) {
				// alert(3526);
				theProfilePerson3GENDER = theProfilePerson3.Gender;
				// alert(3528);

			} else {
				// alert(3531);
				
			}

			

			if (document.getElementById('GenderFormShowP3').style.display == 'none') {
				P3isOK = true;
			}

			if (P1isOK && P2isOK && P3isOK) {
				// alert ("You PASSED - all genders have now been revealed!  Go forth and DNA confirmify !");
				// if (theDNAconfType == '1') {
					followUpAfterLoadSimple();
				// } else {
				// 	followUpAfterLoadComplex();
				// }

			} else {
				alert("Please select Male or Female for each DNA tester.\n" + P1isOK + " : " + P2isOK + " : " + P3isOK);
			}

			
		}

		function doneForm2() {
			// alert("Done 2");
			document.getElementById('FormDIV2').style.display = 'none';
			document.getElementById('FormDIV3').style.display = 'block';
			updateGoBackLinks(2);
		}

		function doneForm3() {
			updateGoBackLinks(3);
			// alert("Done 3");
			document.getElementById('FormDIV1').style.display = 'none';
			document.getElementById('FormDIV2').style.display = 'block';
		}

		function doneForm4() {
			updateGoBackLinks(4);
			// alert("Done 4");
			document.getElementById('FormDIV3').style.display = 'none';
			document.getElementById('FormDIV4').style.display = 'none';
			document.getElementById('FormDIV1WarningDeathDates').style.display = 'none';
			document.getElementById('FormDIV4MultipleMRCAs').style.display = 'none';
			document.getElementById('FormDIV4direct').style.display = 'none';
			document.getElementById('FormDIV5').style.display = 'block';
			
		}

		function doneForm5() {
			updateGoBackLinks(5);
			// alert("Done 5");
			//traditionalYes , DNAtestYes, DNAcompareYes
			Y1 = (document.getElementById('traditionalYes').checked == true);
			Y2 = (document.getElementById('DNAtestYes').checked == true);
			Y3 = (document.getElementById('DNAcorrespondYes').checked == true);
			
			if (Y1 && Y2 && Y3){ 
				document.getElementById('FormDIV5').style.display = 'none';

									// if (ahnenIndexArrays[3].length > 1) { 
					// alert ( "DNA kits\n" +
					// 	kitNums['1-ftdna'] + " & " + kitNums['1-gedmatch'] + "\n" + 
					// 	kitNums['2-ftdna'] + " & " + kitNums['2-gedmatch'] + "\n" + 
					// 	kitNums['3-ftdna'] + " & " + kitNums['3-gedmatch'] + "\n" 
					// 	);
					// }

				if(theDNAconfType =="y" ){
					document.getElementById('FormDIV6Y').style.display = 'block';
					document.getElementById('YDNAcompany').value = 'Choose company';
					document.getElementById('YdistanceTooMuch').style.display = 'none';
				} else if(theDNAconfType =="mt" ){
					document.getElementById('FormDIV6MT').style.display = 'block';
					document.getElementById('MTDNAcompany').value = 'Choose company';
					document.getElementById('MTDNAinfoMitoY').style.display = 'none';
					document.getElementById('MTDNAinfoFTDNA').style.display = 'none';
					document.getElementById('MTmismatchVerif').style.display = 'none';
					document.getElementById('MTmismatchTooMuch').style.display = 'none';
					document.getElementById('MTminorMismatchConfirmed').checked = false;
				} else if(theDNAconfType =="x" ){
					document.getElementById('FormDIV6X').style.display = 'block';
					document.getElementById('XDNAcompany').value = 'Choose company';
					document.getElementById('Xnote4GEDmatch').style.display = 'none';					
					document.getElementById('Xnote23andMe').style.display = 'none';					
					document.getElementById('Xverification').style.display = 'none';					
					document.getElementById('XPAkitGMA').value = kitNums['1-gedmatch'];	
					document.getElementById('XPAkitGMB').value = kitNums['2-gedmatch'];	
					document.getElementById('XPAkitGMC').value = kitNums['3-gedmatch'];	

				} else if(theDNAconfType == "Tri" ){
					document.getElementById('FormDIV6TRI').style.display = 'block';
					document.getElementById('DNAcompany3').value = 'Choose company';
					document.getElementById('TRInote4GEDmatch').style.display = 'none';					
					document.getElementById('TRInote23andMe').style.display = 'none';					
					document.getElementById('TRInoteMyHeritage').style.display = 'none';					
					document.getElementById('TRIverification').style.display = 'none';	
					// document.getElementById('TriPAkitGMA').value = kitNums['1-gedmatch'];	
					// document.getElementById('TriPAkitGMB').value = kitNums['2-gedmatch'];	
					// document.getElementById('TriPAkitGMC').value = kitNums['3-gedmatch'];	
				} else {
					document.getElementById('DNAcompany').value = 'Choose company';
					document.getElementById('FormDIV6').style.display = 'block';
					document.getElementById('DNAnote4GEDmatch').style.display = 'none';
					document.getElementById('DNAkitGMA').value = kitNums['1-gedmatch'];	
					document.getElementById('DNAkitGMB').value = kitNums['2-gedmatch'];	

					document.getElementById('DNAGMpA').textContent = theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent;

					if (theTestTakerID > ''  ) {
					document.getElementById('DNAGMpB').textContent =  theNextBestFirstName( theTestPerson) + " " + theTestPerson.LastNameCurrent ;
				
					} else {
						document.getElementById('DNAGMpB').textContent =  theTestTaker ;
					}

					// DNAGMpA
					// DNAkitGMA
				}
				document.getElementById('DNApainterLink').innerHTML = '';

				document.getElementById('DIV5warning').style.display = 'none';
			} else {
				document.getElementById('DIV5warning').style.display = 'block';
			}
			
			if (theDNAconfType == "Tri"){
				if (ahnenIndexArrays.length > 3) {
					document.getElementById("NeedCustomCiteName3").style.display = "block";
					if (ahnenIndexArrays.length > 4) {
						document.getElementById("NeedCustomCiteNameN").style.display = "block";
						let theCustomCiteNameNspanHTML = "";

						for (var testerNum = 4; testerNum < ahnenIndexArrays.length; testerNum++) {
							theCustomCiteNameNspanHTML += '<br/><B>DNA Test Taker # 3:</B><BR><span id=SpanCiteName3Old><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName3  id=RadioCiteName3Old value="Old">use name at birth: <span id=CustomCiteName3Old></span><BR></span><span id=SpanCiteName3New><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName3 id=RadioCiteName3New value="New" checked>use current name: <span id=CustomCiteName3New></span><BR></span><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName3 id=RadioCiteName3Cust value="Cust">use alias: <input type=text  onchange="changeCousinFormat(0);" id=CustomCiteName3Cust><BR><span id=SpanWikiLink3><input type=checkbox checked onclick="changeCousinFormat(0);" name=UseWikiLink3 id=UseWikiLink3 value="Y">link to profile <span id=WikiTreeProfile3></span><BR></span>'.replace(/3/g,testerNum);
						}

						document.getElementById("NeedCustomCiteNameN").innerHTML = theCustomCiteNameNspanHTML;

					}
				}

				for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
					let theNextBest = theNextBestFirstNameLastName(ahnenIndexArrays[testerNum][1]);
					let theLastBest = theLastBestFirstNameLastName(ahnenIndexArrays[testerNum][1]);
					let theWikiName = personArray[ahnenIndexArrays[testerNum][1]].Name;

					document.getElementById("CustomCiteName"+testerNum+"Old").innerText = theNextBest;
					document.getElementById("CustomCiteName"+testerNum+"New").innerText = theLastBest;
					document.getElementById("CustomCiteName"+testerNum+"Cust").value = "";

					if (theWikiName.indexOf("Priv8") > -1) {
						document.getElementById("SpanCiteName"+testerNum+"Old").style.display = "none";
						document.getElementById("SpanCiteName"+testerNum+"New").style.display = "none";
						document.getElementById("RadioCiteName"+testerNum+"Cust").checked = true;
						document.getElementById("CustomCiteName"+testerNum+"Cust").value = personArray[ahnenIndexArrays[testerNum][1]].FirstName;

					} else {
						if (theNextBest == theLastBest ) {
							document.getElementById("SpanCiteName"+testerNum+"Old").style.display = "none";
							document.getElementById("SpanCiteName"+testerNum+"New").style.display = "block";
							document.getElementById("RadioCiteName"+testerNum+"New").checked = true;
						} else {
							document.getElementById("SpanCiteName"+testerNum+"Old").style.display = "block";
							document.getElementById("SpanCiteName"+testerNum+"New").style.display = "block";
							document.getElementById("RadioCiteName"+testerNum+"Old").checked = true;
						}

					}
				}
			}
		}

		function doneForm6() {
			// alert("Done 6");
			updateGoBackLinks(6);
			// alert("Done 6b");
			var OK2continue = true;

			DNAcompany = "The DNA Testing Company";
			matchAmount = 100;
			matchPerCent = 1.42;
			matchUnits = "cM";
			NumSegments = 7;
			relationshipPrediction = "cousins";
			// alert("Done 6c");
			// theAdditionalNote = '';
			theAdditionalNote = document.getElementById('DIV6addedNote' + theDNAconfType).value;
			setupCitationAppCreditPreamble();

			if (theDNAconfType == '1') {
			// alert("Done 6d - ChooseDNAconfType = 1");
				DNAcompany = document.getElementById('DNAcompany').value;
				matchAmount = document.getElementById('matchAmount').value;
				matchPerCent = document.getElementById('matchPerCent').value;
				matchUnits = "cM";
				NumSegments = document.getElementById('NumSegments').value;
				relationshipPrediction = document.getElementById('relationshipPrediction').value;

				if (DNAcompany != "23andMe") {
					matchPerCent = "";
				 	if (DNAcompany == "GEDmatch") {				 		
						let thisGEDmatchInput = document.getElementById("DNAkitGMA");						
						if (thisGEDmatchInput) {
							let thisGEDmatchNum = thisGEDmatchInput.value;						
							if (thisGEDmatchNum > "") {
								// yay! valid GEDmatch # found!
							} else {
								// boo!!! No GEDmatch number found!
								OK2continue = false;
								alert("You must enter a GEDmatch # for every DNA tester.");
							}
						}
						thisGEDmatchInput = document.getElementById("DNAkitGMB");						
						if (OK2continue && thisGEDmatchInput) {
							let thisGEDmatchNum = thisGEDmatchInput.value;						
							if (thisGEDmatchNum > "") {
								// yay! valid GEDmatch # found!
							} else {
								// boo!!! No GEDmatch number found!
								OK2continue = false;
								alert("You must enter a GEDmatch # for every DNA tester.");
							}
						}
					
				
				 	}
				} 

				if (matchAmount == '') {
					OK2continue = false;
					alert("You must enter the quantity of cM (centiMorgans) of your DNA Match.");
				} else if (matchAmount < 7) {
					OK2continue = false;
					alert("You must match at least 7 cM (centiMorgans) for a valid DNA Match.");
				}

				// if (matchUnits == '?') {
				// 	OK2continue = false;
				// 	alert("You must choose whether the match amount is measured in centiMorgans or as a % of DNA.");
				// }
				if (NumSegments > 0) {
					// excellent
				} else {
					OK2continue = false;
					alert("You must enter the number of segments in the DNA Match.");
				}

				if (relationshipPrediction == '') {
					OK2continue = false;
					alert("You must enter the Predicted Relationship as reported by the DNA testing company.");
				}
			} else if (theDNAconfType == 'y') {
				DNAcompany = document.getElementById('YDNAcompany').value;	
				var NumMarkers = document.getElementById('Markers').value;	
				if (NumMarkers == 0) {
					alert("You must choose the # of markers for the Y-DNA match.");
					OK2continue = false;
				} else if (document.getElementById('genDistance').value > Math.ceil((NumMarkers - 3)/10)) {
					OK2continue = false;
					document.getElementById('YdistanceTooMuch').style.display = 'revert';
				} 
					
			} else if (theDNAconfType == 'mt') {
				DNAcompany = document.getElementById('MTDNAcompany').value;	
				theGenDistance4MT = 0;
				if(DNAcompany == 'mitoYDNA') {
					var theGenDistance4MT = 1.0 * document.getElementById('diffHVR1').value +  1.0 * document.getElementById('diffHVR2').value +  1.0 * document.getElementById('diffCR').value ;
				} else if(DNAcompany == 'Family Tree DNA') {
					var theGenDistance4MT = document.getElementById('genDistanceMT').value;
				}

				// alert ("Company: " + DNAcompany + " / genDistanceMT : " + theGenDistance4MT);
				if (theGenDistance4MT >  2 ) {
					OK2continue = false;
					document.getElementById('MTmismatchTooMuch').style.display = 'block';
					document.getElementById('MTmismatchVerif').style.display = 'none';
				} else if (theGenDistance4MT ==  2 ) {
				 	var isMMconfirmed = document.getElementById('MTminorMismatchConfirmed').checked;
					if (isMMconfirmed == true) {
						// hurray - it's been checked off the list
						OK2continue = true;
						document.getElementById('MTmismatchVerif').style.display = 'none';
						document.getElementById('MTmismatchTooMuch').style.display = 'none';
						document.getElementById('MTminorMismatchConfirmed').checked = false;
					} else {
						OK2continue = false;
						document.getElementById('MTmismatchVerif').style.display = 'block';
						document.getElementById('MTmismatchTooMuch').style.display = 'none';
					}


				}
			} else if (theDNAconfType == 'x') {
				DNAcompany = document.getElementById('XDNAcompany').value;	
				var XmatchAmount = document.getElementById('XmatchAmount').value;	
				
				if (XmatchAmount > '') {
						// hurray !  Segment length filled in

					// NOW ... to check to see if we need to check for verified checkboxes
					if ( theID3 > '' && (DNAcompany == 'Family Tree DNA' || DNAcompany == "MyHeritageDNA") ) {
						// YES, Virginia, we need to check for the checkboxes  

						var VerifABbyA = document.getElementById('XVerifABbyA').checked;	
						var VerifABbyB = document.getElementById('XVerifABbyB').checked;	
						var VerifBCbyB = document.getElementById('XVerifBCbyB').checked;	
						var VerifBCbyC = document.getElementById('XVerifBCbyC').checked;	
						var VerifCAbyC = document.getElementById('XVerifCAbyC').checked;	
						var VerifCAbyA = document.getElementById('XVerifCAbyA').checked;	

						if ((VerifABbyA || VerifABbyB) && (VerifBCbyB || VerifBCbyC )&& (VerifCAbyC ||VerifCAbyA)) {
							// hurray - enough checkmarks in all the right places
						} else {
							alert ("You must check to verify that each combination has been independently verified by one of the DNA testers.");
							OK2continue = false;
						}

					}

					if (XmatchAmount < 7) {
						OK2continue = false;
						alert("You must match at least 7 cM (centiMorgans) for a valid DNA Match.");
					}

				} else {
					alert ("You must enter the length of the shared segment.")
					OK2continue = false;
				}
				
			} else if (theDNAconfType == 'Tri') {
				DNAcompany = document.getElementById('DNAcompany3').value;	
				var ChromoNum= document.getElementById('ChromoNum').value;	
				var TriMatchAmount = document.getElementById('TriMatchAmount').value;	

				if (ChromoNum > 0) {
					// hurray ! Chromosome # filled in
					if (TriMatchAmount > '') {
						// hurray !  Segment length filled in

						// NOW ... to check to see if we need to check for verified checkboxes
						if (DNAcompany == 'Family Tree DNA' /*|| DNAcompany == "MyHeritageDNA"*/) {
							// YES, Virginia, we need to check for the checkboxes
							let verifiersList = [0,0,0,0,0,0,0,0,0,0,0];
							let missingAcheckmark = false;
							for (var i = 1; i < ahnenIndexArrays.length - 1; i++) {
								for (var j = i + 1; j < ahnenIndexArrays.length; j++) {
									var VerifABbyA = document.getElementById("Verif" + i + j + "by" + i).checked;
									var VerifABbyB = document.getElementById("Verif" + i + j + "by" + j).checked;
									let foundCheck = false;
									if (VerifABbyA == true) {
										verifiersList[i] ++;
										foundCheck = true;
									}
									if (VerifABbyB == true) {
										verifiersList[j] ++;
										foundCheck = true;
									}
									if (!foundCheck) {
										missingAcheckmark = true;
										break;
									}
								}
								if (missingAcheckmark) {
									break;
								}
							}


							// if ((VerifABbyA || VerifABbyB) && (VerifBCbyB || VerifBCbyC )&& (VerifCAbyC ||VerifCAbyA)) {
								// hurray - enough checkmarks in all the right places
							// } else {
							if (missingAcheckmark) {
								alert ("You must check to verify that each combination has been independently verified by one of the DNA testers.");
								OK2continue = false;
							}
						}

						if (TriMatchAmount < 7) {
							OK2continue = false;
							alert("You must match at least 7 cM (centiMorgans) for a valid DNA Match.");
						}


					} else {
						alert ("You must enter the length of the triangulated segment.")
						OK2continue = false;
					}
				} else {
					alert ("You must choose a chromosome number for the triangulated segment.")
					OK2continue = false;
				}


					
			}
				
			if (DNAcompany == '' || DNAcompany == 'Choose company' || DNAcompany == '?') {
				OK2continue = false;
				alert("You must choose a DNA company to continue.");
			
			} else {
				// alert ("Company = *" + DNAcompany + "*");
				if (OK2continue && DNAcompany == "GEDmatch") {

					for (var i = 1; OK2continue && (i < ahnenIndexArrays.length); i++) {
						let thisGEDmatchInput = document.getElementById("DNAkitGM"+i);						
						if (thisGEDmatchInput) {
							let thisGEDmatchNum = thisGEDmatchInput.value;						
							if (thisGEDmatchNum > "") {
								// yay! valid GEDmatch # found!
							} else {
								// boo!!! No GEDmatch number found!
								OK2continue = false;
								alert("You must enter a GEDmatch # for every DNA tester.");
							}
						}
					}
				}
			}
			// alert("Done 6e - OK2continue = " + OK2continue);
			
			if (OK2continue) {
				 // alert("OK 2 Continue : is Couple = " + isCouple + " and P2 is P1's : " + relOfP2toP1);

				// SETUP (or reinforce) who is theAncestor
				if (theAncsAhnNum == -1 && isDirectAncestor) {
					console.log("DANGER DANGER - undefined DIRECT ANCESTOR scenario !!!");
					theAncsAhnNum = ahnenIndexArrays[1].indexOf(ahnenIndexArrays[2][1]);
					// let howRelatedPhraseDirect = setRelationshipsFromMatch(theCurrentMatchesList[0], 0);
					// console.log({howRelatedPhraseDirect});
					// defineRelationshipsBetween(theAncs1, theAncs2, numGens1, numGens2 , arrayNum1, arrayNum2)
				}

				theAncestor = personArray[ ahnenIndexArrays[1][ theAncsAhnNum]];
				theProfilePerson = personArray[ ahnenIndexArrays[1][ 1]];
				console.log("theAncestor @ ", theAncsAhnNum, " is " , theAncestor);
				// let theAncestorAlt = personArray[ commonAncestorDetails[0].Ahnens[1][0] ];
				// console.log("theAncestor from commonAncestorDetails  is " , theAncestorAlt);

				// SETUP the options for DNA TEST TAKER # 1 - aka - theProfilePerson
				console.log("Person1FullName : B4: " + Person1FullName4Citation, theProfilePerson);
				theNames = setupOptionsForDNATestTaker(1 ,  theProfilePerson);
				Person1FullName4Citation = theNames[0];
				Person1FirstName4Citation = theNames[1];

				console.log ("Person1FullName : Aft:" + Person1FullName4Citation);
				hasWikiTreeID2 = false;
				hasWikiTreeID3 = false;

				if (isCouple) {
					document.getElementById('Div7CAVerifyRelationship').innerText = "Paternal relationship and the Maternal relationship (one joint one statement, or two separate ones)";
				} else if (theAncsAhnNum % 2 == 0)  {
					document.getElementById('Div7CAVerifyRelationship').innerText = "Paternal relationship";
				} else {
					document.getElementById('Div7CAVerifyRelationship').innerText = "Maternal relationship";
				}

				let childFullName = theNextBestFirstNameLastName( ahnenIndexArrays[1][Math.floor(theAncsAhnNum / 2)] );
				document.getElementById('Div7CAVerifyKid').innerText = childFullName;

				if (theTestPerson){
					hasWikiTreeID2 = true;
					console.log("Person2FullName : 2a : B4: " + Person2FullName4Citation, theTestPerson);
					theNames = setupOptionsForDNATestTaker(2 ,  theTestPerson);
					Person2FullName4Citation = theNames[0];
					Person2FirstName4Citation = theNames[1];
					console.log("Person2FullName : Aft: " + Person2FullName4Citation);
					

				}

				if ( theProfilePerson2) {
					hasWikiTreeID2 = true;
					// alert("B4 2b: " + Person2FullName4Citation);
					console.log("Person2FullName : 2b : B4: " + Person2FullName4Citation, theProfilePerson2);
					theNames = setupOptionsForDNATestTaker(2 ,  theProfilePerson2);
					Person2FullName4Citation = theNames[0];
					Person2FirstName4Citation = theNames[1];
					console.log("Person2FullName : Aft: " + Person2FullName4Citation);
					
					theTestTakerFromOrigForm = document.getElementById('TTaker2').value ;
					if (Person2FullName4Citation == theTestTakerFromOrigForm) {
						hasWikiTreeID2 = false;
					}
				}
				
				Person2FullName4CitationFirstMention = Person2FullName4Citation;
				altWikiNameForPerson2 = "";

				console.log("OK2continue from DONE FORM 6: ", {hasWikiTreeID2}, {generationsFromMRCA}, {theTestTakerID}, {theDNAconfType});

				if (hasWikiTreeID2 == false) {
					// document.getElementById('CustomCiteName2Cust').value = theOriginalID2;
					// document.getElementById('RadioCiteName2Cust').checked = true;
					// document.getElementById('SpanCiteName2Old').style.display =  'none';
					// document.getElementById('SpanCiteName2New').style.display =  'none';
					theTestTaker = document.getElementById('TTaker2').value ;
					document.getElementById('CustomCiteName2New').innerText = theTestTaker ;
					Person2FullName4Citation = theTestTaker;
					Person2FirstName4Citation = theTestTaker;
					console.log("Person2FullName : 2c : B4: " + Person2FullName4Citation, document.getElementById('CustomCiteName2Cust').value, theTestTaker);
					if (document.getElementById('RadioCiteName2Cust').checked){
						Person2FullName4Citation = document.getElementById('CustomCiteName2Cust').value;
						Person2FirstName4Citation = document.getElementById('CustomCiteName2Cust').value;					
					}

					Person2FullName4CitationFirstMention = Person2FullName4Citation;

					// ADD CODE to append (ChildOrGrandChildRelationship to [closest WikiTree Ancestor ON WikiTree]) to the FullName4Citation

					TTlevel = generationsFromMRCA;

					if (TTlevel === undefined) {
						console.log("FINDING NEMO: TTlevel is undefined ");
					} else {
						console.log("FINDING NEMO: TTlevel = ",TTlevel);
					}

					let thisPeep = null;
					let lastPeep = null;
					let lastPeepLevel = -1;

					if (theDNAconfType == 1 && theTestTakerID == '' ) {
						lastPeepLevel = 0;
						lastPeep = personArray[ ahnenIndexArrays[2][1]];
						for (var sNum = 1; sNum <= 5; sNum++) {
							thisPeep = getAncPeepAtDropDown(sNum);
							if (thisPeep) {
								console.log("FINDING NEMO Peep: ", thisPeep);
								lastPeep = thisPeep;
								lastPeepLevel = sNum;

							} else {
								console.log("FINDING NEMO: CAN'T GO to LEVEL " + sNum);
							}
						}
					} else if ((theDNAconfType == 'y' || theDNAconfType == 'mt') && theTestTaker != theTestTakerID ) { 
						lastPeep = theProfilePerson2;
						TTlevel = 1.0 * numGensFromWikiTreeID2toCousin + 1.0;
						lastPeepLevel = 1;
						console.log("DO THIS ? Y not ? ", {theDNAconfType},{theTestTaker}, "!=", {theTestTakerID}," => ",{lastPeep}, {TTlevel},  {lastPeepLevel}, {numGensFromWikiTreeID2toCousin});
						
					} else {
						console.log("Can't do FINDING NEMO !");
						console.log("CANNOT DO THIS ? Y not ? ", {theDNAconfType},{theTestTaker}, "!=", {theTestTakerID}," => ",{lastPeep}, {TTlevel},  {lastPeepLevel});
					}

					

					if (lastPeepLevel > -1 && TTlevel > -1) {
						
						let lastPeepCitation = "[["+ lastPeep.Name + "|" + theNextBestFirstName(lastPeep) + " "  + lastPeep.LastNameAtBirth +"]]";
						
						let relationshipKidTypes = ["self","child","grandchild","great-grandchild","great-great-grandchild","great-great-great-grandchild","great-great-great-great-grandchild"];

						Person2FullName4CitationFirstMention += " (" + relationshipKidTypes[TTlevel - lastPeepLevel] + " of " + lastPeepCitation + ")";
						altWikiNameForPerson2 = lastPeep.Name;
					}


					console.log("Person2FullName : Aft: " + Person2FullName4Citation);
					console.log({Person2FullName4CitationFirstMention});
				} else {
					// document.getElementById('SpanCiteName2New').style.display =  'revert';
				}


				if ( theProfilePerson3) {
					// alert("B4 3a: " + Person3FullName4Citation);
					theNames = setupOptionsForDNATestTaker(3 ,  theProfilePerson3);
					Person3FullName4Citation = theNames[0];
					Person3FirstName4Citation = theNames[1];
					
					// document.getElementById('NeedCustomCiteName3').style.display =  'revert';

				} else{
					// alert("B4 3b: " + Person3FullName4Citation);
					Person3FullName4Citation = "" ;
					Person3FirstName4Citation = "" ;
					// alert("B4 3b: " + Person3FullName4Citation);
					// document.getElementById('NeedCustomCiteName3').style.display =  'none';
				}

				// LET's make SURE that we have the proper cousin format (and relationships)
				changeCousinFormat(0, false);

				console.log("ABOUT to assembleCitation", {theAncestor});
				if (theDNAconfType == 'Tri') {
					document.getElementById("ResearchNotesH2").style.display = "block";
					setupAllYayTesterVariables();
					assembleTriangulationCitationStarters();
					assembleTheEndProviso();

					console.log("TRI - theAncestor = ", theAncestor);
					if (isCouple == true) {
						theAncestor2 = personArray[ ahnenIndexArrays[theFirstYayTesterNum][ commonAncestorDetails[0].Ahnens[theFirstYayTesterNum][0] + 1 ]];
					}

					let theChildNum = ahnenIndexArrays[theFirstYayTesterNum][ Math.floor( commonAncestorDetails[0].Ahnens[theFirstYayTesterNum][0] / 2 ) ];
					let theChildPerson = personArray[ theChildNum ];
					let childWikiName = theChildPerson.Name;
					let childFullName = theNextBestFirstNameLastName(theChildPerson.Id);

					document.getElementById('Div7CAkid').innerHTML =  "<A target=_blank href=https://www.wikitree.com/wiki/" +  childWikiName.replace(" ","_").replace(" ","_") +">" + childFullName + "</A>";
					document.getElementById('Div7CAkid2').innerHTML = "<B>" + childFullName  + "</B> (" + childWikiName+ ")";
					document.getElementById('Div7CAkid3').textContent =  childFullName;


					theCitation = assembleCitationN(theFirstYayTesterNum , theAncestor, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, isCouple)


					document.getElementById('floatingMiniTreeInTable').innerHTML = floatingMiniTreeTableX +  floatingMiniTreeTableHTML + document.getElementById('NewImprovedMiniTreeTABLE').innerHTML  + "</TABLE>";					



				 
					for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
						let theNextBest = theNextBestFirstNameLastName(ahnenIndexArrays[testerNum][1]);
						let theLastBest = theLastBestFirstNameLastName(ahnenIndexArrays[testerNum][1]);
						let theWikiName = personArray[ahnenIndexArrays[testerNum][1]].Name;

						document.getElementById("CustomCiteName"+testerNum+"Old").innerText = theNextBest;
						document.getElementById("CustomCiteName"+testerNum+"New").innerText = theLastBest;
						
						if (theWikiName.indexOf("Priv8") > -1) {
							document.getElementById("SpanCiteName"+testerNum+"Old").style.display = "none";
							document.getElementById("SpanCiteName"+testerNum+"New").style.display = "none";

						} else {
							if (theNextBest == theLastBest ) {
								document.getElementById("SpanCiteName"+testerNum+"Old").style.display = "none";
								document.getElementById("SpanCiteName"+testerNum+"New").style.display = "block";
							} else {
								document.getElementById("SpanCiteName"+testerNum+"Old").style.display = "block";
								document.getElementById("SpanCiteName"+testerNum+"New").style.display = "block";
							}

						}
					}
				


					hideMiniTree();

				} else {
					document.getElementById("ResearchNotesH2").style.display = "none";
					document.getElementById("NeedCustomCiteName3").style.display = "none";
					document.getElementById("NeedCustomCiteNameN").style.display = "none";
					HideResearchNotes();
					theCitation = assembleCitation(theAncestor, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, isCouple);

					// whichMiniTree2Use = "DUOminiTree"; / SimpleMiniTreeTable
					console.log(whichMiniTree2Use, document.getElementById(whichMiniTree2Use), document.getElementById("UNIminiTree"));
					if (!document.getElementById(whichMiniTree2Use) && document.getElementById("UNIminiTree")) {
						whichMiniTree2Use = "UNIminiTree";
					}

					let miniTreeHTML = document.getElementById(whichMiniTree2Use).innerHTML;
					if (whichMiniTree2Use == "SimpleMiniTreeTable") {
						miniTreeHTML = getMiniTreeHTMLForStep7(whichMiniTree2Use);
					}

					document.getElementById('floatingMiniTreeInTable').innerHTML = floatingMiniTreeTableX +  floatingMiniTreeTableHTML + miniTreeHTML + "</TABLE>";

					hideMiniTree();

				}
				console.log("DONE to assembleCitation");
				
				
				document.getElementById('CAsourceCitation').rows = 12;


				document.getElementById('FormDIV6').style.display = 'none';
				document.getElementById('FormDIV6TRI').style.display = 'none';
				document.getElementById('FormDIV6X').style.display = 'none';
				document.getElementById('FormDIV6Y').style.display = 'none';
				document.getElementById('FormDIV6MT').style.display = 'none';
				document.getElementById('MTmismatchVerif').style.display = 'none';
				document.getElementById('MTmismatchTooMuch').style.display = 'none';
				document.getElementById('MTminorMismatchConfirmed').checked = false;
				document.getElementById('YdistanceTooMuch').style.display = 'none';
				
				document.getElementById('FormDIV7').style.display = 'block';

				document.getElementById('CAsourceCitation').innerHTML = theCitation;
				document.getElementById('CAsourceCitationCustom').innerHTML = theCitation;

				 // alert("Assembled3");
				assembleExtraCitations(DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction); 
				 // alert("Assembled4");
			}
			 // alert("Done 6F = Finished !");

		}

		function doneForm7() {
			// alert("Done 7"); 
		}

		function UnderstoodMultipleWarning() {
			document.getElementById("NextButton4").style.display = "revert";	
			document.getElementById("UnderstandWarningBanner").style.display = "none";	
			document.getElementById("UnderstandMultipleButton").style.display = "none";	
			document.getElementById("PressTheButtonSPAN").style.display = "none";	
		}


		// LOAD INFO


		// ========================================================================================
		//
		// LOADING and FINDING MRCA for a SIMPLE CONFIRMATION (2 people ; <= 3rd cousins)
		//
		// ========================================================================================
		function loadSimpleInfo() {
			console.log ("loadSimpleInfo");

			personArray = {}; // start with a fresh Person Array
			
			theDNAtesttakerList = [{}, {}, {}];
			ahnenIndexArrays = [ [], [], [] ];
			theMRCAlist = [];
			relationshipMatrix = {};
			ahnNum2MRCAmatrix = [];

			// 	theProfilePersons = [null, null, null, null];

			isDirectAncestor = false;
			theTestPerson = null;
			theProfilePerson = null;
			theProfilePerson2 = null;
			theProfilePerson3 = null;
			ahnenIndexArray = [];
			ahnenIndexArrays[2] = [];
			ahnenIndexArrays[3] = [];

			theAncsAhnNum3 = 0;
			// reset the regular MiniTree
			document.getElementById('IDgender').style.display = "block";

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
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID ,'ancestors':6, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					 console.log(theJason);

					
					// if (data[0].status != 0) {
					if (data[0].resultByKey[theID].Id == null) {
						// NO ID - WARNING !!!!

						// // alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						setupWarningRePersonNWikiTreeID(1,'ab',theID, data[0].status);
					} else if (data[0].resultByKey[theID].status > "") {
						console.log("Don't go there:" , data[0].resultByKey[theID].status);
						setupWarningRePersonNWikiTreeID(1,'ab',theID, data[0].resultByKey[theID].status);
					} else if (data[0]) {
						// THERE BE DATA HERE ... 
						console.log ("There is data : " , data[0].people);

						
						// STORE the PRIMARY ID
						let thePrimaryID = data[0].resultByKey[theID].Id;

						// COLLECT ALL of the PEOPLE (6 gens of ancestors of PrimaryID)
						for (var i in data[0].people) {
							personArray[ data[0].people[i].Id ] = data[0].people[i];
							// console.log("Person " + i,data[0].people[i].Id );
						}
						
						// STORE the PRIMARY PERSON into [theProfilePerson] object (full record, not just ID)
						theProfilePerson = personArray[thePrimaryID];
						theDNAtesttakerList[0] = personArray[thePrimaryID];

						console.log({theProfilePerson}, {theID}, {thePrimaryID});

						// Search for DNA kit #s for Primary Person
						loadDNAkitNumbers( theProfilePerson.Name, 1);

						// POPULATE the Ahnentafel Table for the PRIMARY PERSON
						populateAhnenTable(personArray,  thePrimaryID , 1, 1);

						// alert("DONE Populating ...");
						// listOfAhns = "";
						// for (var i = 1; i < 32; i++) {
						// 	listOfAhns += " "  + ahnenIndexArrays[1][i];
						// }
						//alert (theProfilePerson.FirstName );
						// alert(theTestTaker);


						// Prepare for the search for the second DNA Test Taker
						theTestTaker = document.getElementById('TTaker2').value ;
						

						// FILL OUT some of the spots in the MiniTree and other forms while we've got the info and it's nice and fresh and steaming ...
						document.getElementById('Div2P1a').textContent = theNextBestFirstName( theProfilePerson) ;
						document.getElementById('Div2P1b').textContent = theNextBestFirstName( theProfilePerson) ;
						document.getElementById('Div2P2a').textContent = theTestTaker;
						// document.getElementById('Div3P2a').textContent = theTestTaker;
						document.getElementById('Div3P2a').textContent = theTestTaker;
						document.getElementById('Div3P2b').textContent = theTestTaker;
						// document.getElementById('Div3P1a').textContent = theProfilePerson.FirstName ;
						// document.getElementById('Div3P1a').textContent = theProfilePerson.FirstName ;
						// document.getElementById('Div3P1b').textContent = theProfilePerson.FirstName ;
												
						document.getElementById('YourDNA').innerHTML =  getDNAConfirmationAidHTML ( theProfilePerson);												
						// if (theProfilePerson.LastNameAtBirth == theProfilePerson.LastNameCurrent) {
						// 	document.getElementById('YourDNA').innerHTML = "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameAtBirth + "</A>";
						// } else {
						// 	document.getElementById('YourDNA').innerHTML = "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( theProfilePerson) + " (" + theProfilePerson.LastNameAtBirth + ") " + theProfilePerson.LastNameCurrent + "</A>";
						// }
						
						// ==================================================================================================
						// OK - we've just got the Profile Person .... let's see if theTestTaker also has a valid WikiTree ID
						// ==================================================================================================

						if (theTestTaker.indexOf('-') > 1) {
							// still OK - a WikiTree ID has to have a - in the middle of it
							var allDigits = '0123456789';
							if (allDigits.indexOf(theTestTaker.charAt(-1)) > -1) {
								// still OK
								// SO ... we know that the TestTaker has a dash and ends with at least one digit ... so ... that COULD be a WikiTree ID ... let's go for it!
								// alert ("Going to request2");
								document.getElementById('SearchPN').textContent = 2;


								// ========================================================================================
								// (2)  LOAD up to SIX GENERATIONS of ANCESTORS for Tester # 2  (theTestTaker)
								// ========================================================================================

								var request2 = $.ajax({
									url: API_URL,
									crossDomain: true,
									xhrFields: { withCredentials: true },
									type: 'POST',
									dataType: 'json',
									
									//data: { 'action': 'getPerson', 'key': primaryID, 'fields': "Id, Name, FirstName, MiddleName, IsLiving, LastNameAtBirth, LastNameCurrent, Nicknames, LastNameOther, RealName, Prefix, Suffix, BirthLocation, DeathLocation, Gender, BirthDate, DeathDate,Father,Mother, Privacy, DataStatus", 'format': 'json' },
									data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theTestTaker ,'ancestors':6, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,DeathDate,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

									// On successful POST return, check our data. Note from that data whether the login itself was
									// successful (setting session cookies if so). Call the user callback function when done.
									success: function(data) { 
										var theJason = JSON.stringify(data);
										console.log("STATUS for  " + theTestTaker + ":" , data[0].status);
										console.log(data);
										// alert("Success - in ready function for TestTaker! Status:" +  data[0].status);						 
										// alert(theJason);
										// console.log("@ line 4374: data[0].status = ", data[0].status, data[0].status == 0, data[0].status == "",data[0].status === 0, data[0].status === "");
										// console.log("@ line 4375: data[0] = ", data[0]);

										if (data[0].resultByKey[theTestTaker].status > "") {
											console.log("Don't go there:" , data[0].resultByKey[theTestTaker].status);
											setupWarningRePersonNWikiTreeID(2,'ab',theTestTaker, data[0].resultByKey[theTestTaker].status);

										} else if (data[0] && data[0].resultByKey && data[0].resultByKey[theTestTaker].Id > 0) {
											// NO ISSUES with STATUS - ie - THERE IS DATA of SOME SORT

											// alert (data[0].status);
											// alert ("There is data x " + data[0].people.length);

											
											

											var theMatchesList = [];


											// STORE the SECOND DNA TEST TAKER's  ID
											let theTestPersonID = data[0].resultByKey[theTestTaker].Id;

											// COLLECT ALL of the PEOPLE (6 gens of ancestors of theTestPersonID)
											for (var i in data[0].people) {
												personArray[ data[0].people[i].Id ] = data[0].people[i];
											}
												
											// STORE the SECONDARY PERSON into [theTestPerson] object (full record, not just ID)
											theTestPerson = personArray[theTestPersonID];

											// TEST to SEE if the Test Taker is in fact a Direct Ancestor
											// IF SO ... then they would already exist in the Ahnen Table of the Primary Person
											if (ahnenIndexArrays[1].indexOf( theTestPersonID ) > -1) {
												// DIRECT ANCESTOR of person 1 is the 2nd Test Taker
												// alert("Found Direct Ancestor");
												isDirectAncestor = true;
											}

											console.log("the OTHER test taker person is:", theTestTaker, theTestPersonID, theTestPerson);

											// STORE the ID # of the SECONDARY PERSON
											theTestTakerID = theTestTaker;
											// SEARCH for DNA Kits for SECONDARY PERSON
											if (theTestPerson && theTestPerson.Name) {
												loadDNAkitNumbers( theTestPerson.Name, 2);
											}

											// POPULATE the Ahnentafel Table for the SECONDARY PERSON
											populateAhnenTable(personArray,  theTestPersonID, 1, 2);

											let isDirectDescendant = false;
											// TEST to SEE if the Test Taker # 2 is in fact a Direct Descendant of the Primary Test Taker
											// IF SO ... then PRIMARY would  exist in the Ahnen Table of the SECONDARY Person
											if (ahnenIndexArrays[2].indexOf( theProfilePerson.Id ) > -1) {
												// DIRECT DESCENDANT of person 1 is the 2nd Test Taker
												console.log("Found Direct DESCENDANT");

												// SO ... let's switch everything else around so we can use the logic for Direct Ancestor
												let  TMPahnenIndexArray = ahnenIndexArrays[1];
												ahnenIndexArrays[1] = ahnenIndexArrays[2];
												ahnenIndexArrays[2] = TMPahnenIndexArray;

												let TMPperson = theProfilePerson;
												theProfilePerson = theTestPerson;
												theTestPerson = TMPperson;

												theID = theProfilePerson.Id;
												theTestTakerID = theTestPerson.Id;

												isDirectAncestor = true;
												isDirectDescendant = true;
											}

											// if (ahnenIndexArray.indexOf( data[0].people[0].Id ) > -1) {
											if (isDirectAncestor == true) {
												// ========================================================================================
												// DIRECT ANCESTOR is the 2nd Test Taker (if not originally, then after a swap of IDs)
												// alert("Found Direct Ancestor");

												// NO NEED TO GO ANY FURTHER WITH SEARCHING - FOUND the MRCA !!!  KUDOS !
												// Assign the proper values to variables needed in subsequent sections and in MiniTree ....
												// ======================================================================================== 

												isDirectAncestor = true;
												MRCA = ahnenIndexArrays[2][1];
												theAncsAhnNum = ahnenIndexArrays[1].indexOf( theTestTakerID );
												if (isDirectDescendant == true) {
													// console.log("isDirectDescendant --> theTestTakerID = ", theTestTakerID, "\nahnIndexArray:", ahnenIndexArray);
													theAncsAhnNum = ahnenIndexArrays[1].indexOf( theTestTakerID );
													// console.log("theAncsAhnNum", theAncsAhnNum);
												}
												theAncsAhnNum4TT = 1;
												MatchingIndices = [theAncsAhnNum4TT, theAncsAhnNum];
												theAncestor = personArray[MRCA];
												isCouple = false;

												// Q.E.D.

											} else {
												// ========================================================================================
												// COUSIN of some flavour is the 2nd Test Taker  - we hope ... depends on search for MRCA
												// ========================================================================================

												listOfAhns = "";
												var MRCA = -1;
												var MRCA2 = -1;
												var MatchingIndices = [];
												var MRCA1list = [];
												var MRCA2list = [];
												var MRCA1 = -1;
												
												var ignoreTheseIs = [];
												

												var maxAhnentafelNumber = 4*64;

												// console.log("NOT DIRECTS", ahnenIndexArray, ahnenIndexArrays[2]);
												console.log("TWO AhnenArrays for Consideration:", ahnenIndexArrays[1], ahnenIndexArrays[2]);
												// alert ("Length is " + ahnenIndexArrays[2].length);


												// CYCLE THROUGH ALL of the AHNENTAFEL for DNA TESTER # 1 (Primary Person)

												for (var i = 1; i <= maxAhnentafelNumber /*&& MRCA == -1*/; i++) {
													// AND ... if there IS an entry at this Ahnentafel # ....	
													if (ahnenIndexArrays[1][i] > -1 && ignoreTheseIs.indexOf(i) == -1) {
														// NOTE:  ahnenIndexArrays[1][i] == the WikiTree ID # for a person (unique for each profile), and i == the location in the Ahnentafel (2 = dad, 3 = mom, 4 = granddad1, etc..)

														// SEE if there is an entry for the SAME PERSON in the OTHER DNA TESTER's Ahnentafel
														let theJ = ahnenIndexArrays[2].indexOf(ahnenIndexArrays[1][i]); 														

														// Putting a WHILE LOOP around this so that if there is MORE THAN ONE occurrence of this ancestor, we want to capture all possibilities, 
														// instead of being satisfied with first come / first served
														while (theJ > -1 && theJ <= maxAhnentafelNumber ) {
															console.log("SEE - I found  @ i=",i,ahnenIndexArrays[1][i], "and @ j=",theJ,ahnenIndexArrays[2][theJ]);
															if  (i % 2 == 0) {
																// MALE Ancestor
																if ( i+1 < ahnenIndexArrays[1].length && ahnenIndexArrays[1][i + 1] && theJ + 1 < ahnenIndexArrays[2].length && ahnenIndexArrays[2][theJ + 1] && ahnenIndexArrays[1][i + 1] == ahnenIndexArrays[2][theJ + 1]) {
																	// IF there is a match with both of these Male Ancestors wives - then - it's a double match - ie - a Married couple is the MRCA
																	theMatchesList.push ([2, i,theJ]);
																	addToIgnoreArray(i, ignoreTheseIs, maxAhnentafelNumber)
																	addToIgnoreArray(i+1, ignoreTheseIs, maxAhnentafelNumber)

																} else {
																	// ELSE ... it is a single MRCA ancestor
																	theMatchesList.push ([1, i,theJ]);
																	addToIgnoreArray(i, ignoreTheseIs, maxAhnentafelNumber)
																}
															} else if  (i % 2 == 1) {
																// FEMALE Ancestor
																if ( ahnenIndexArrays[1][i - 1] &&  ahnenIndexArrays[2][theJ - 1] && ahnenIndexArrays[1][i - 1] == ahnenIndexArrays[2][theJ - 1]) {
																	// IF there is a match with both of these Female Ancestors' husbands - then - it's a double match - ie - a Married couple is the MRCA
																	// AND ... we would have already caught it at the previous instance of i ... so we don't need to re-record it here
																	// theMatchesList.push ([2, i,theJ]);
																} else {
																	// ELSE ... it is a single MRCA ancestor, and we DO need to record it !
																	theMatchesList.push ([1, i,theJ]);
																	addToIgnoreArray(i, ignoreTheseIs, maxAhnentafelNumber)
																}
															}

															// Let's see if there are OTHER occurrences of the same ancestor in the other Ahnentafel .. (repeat ancestors / pedigree collapse / endogamy)
															theJ = ahnenIndexArrays[2].indexOf(ahnenIndexArrays[1][i], theJ + 1); 
														}

													}


												}

												console.log("After all is said and done - here are the matches:");
												console.log(theMatchesList);
												console.log("Ignore these Ahnentafel # s:");
												console.log(ignoreTheseIs);


												theCurrentMatchesList = theMatchesList;
												theCurrentMatchNum = 0;
											}

											let theMID = 0;				
											if (theMatchesList.length > 0) {
												if (theMatchesList.length > 1) {
													// OK _ we have MORE THAN ONE MATCH ... SO ...
													// We have to start with the one with the LOWEST SUM of Ahenentafel #s (as our best match)
													let currMinID = 0;
													let currMinSum = theMatchesList[theMID][1] + theMatchesList[theMID][2];
													for (var m = 1; m <theMatchesList.length; m++) {
														let newSum = theMatchesList[m][1] + theMatchesList[m][2];
														if (newSum < currMinSum) {
															currMinID = m;
															currMinSum = newSum;
														}
													}

													theMID = currMinID;
												}								

												MRCA = ahnenIndexArrays[1][ theMatchesList[theMID][1] ];
												theAncsAhnNum = theMatchesList[theMID][1];
												theAncestor = personArray[MRCA];
												if (theMatchesList[theMID][0] == 2) {
													// we have an Ancestral Couple
													MRCA2 = ahnenIndexArrays[1][ theMatchesList[theMID][1] + 1 ];
													isCouple = true;
													theAncsAhnNum4TT = theMatchesList[theMID][2];
													theAncestor2 = personArray[MRCA2];
												} else {
													isCouple = false;
													MRCA2 = -1;
												}
											}

											console.log("MRCAlists:", {MRCA1list}, {MRCA2list});
											
											// CREATE THE DROP DOWN LIST & the LIST OF MULTIPLE ANCESTORS

											let theRelationshipsList = "";
											let numSimple = 0;
											let numComplex = 0;
											let repeatMRCAnameTracker = {};

											if (theMatchesList.length > 1) {
												document.getElementById('FormDIV4MultipleMRCAs').style.display = "block";
												let multipleNote = "<B>MULTIPLE COMMON ANCESTORS FOUND !</B><br/>";
												let multipleDropDown = "<SELECT onchange='changeSimpleMRCAs();' id=MultipleMRCAselector>";
												for (var m = 0; m <theMatchesList.length; m++) {
													let mID = theMatchesList[m];
													let MRCAnames = "";

													multipleNote += "* " ; //+ mID + " : " ;
													MRCAnames += theNextBestFirstName(personArray[ahnenIndexArrays[1][mID[1]]]) + " " + theBestLastName(personArray[ahnenIndexArrays[1][mID[1]]]) ;

													let statusSoloPair = " &nbsp; &nbsp; &nbsp; &nbsp;";
													if (mID[0] == 2) {
														MRCAnames += " + " + theNextBestFirstName(personArray[ahnenIndexArrays[1][mID[1] + 1]]) + " " + theBestLastName(personArray[ahnenIndexArrays[1][mID[1] + 1]]) ;
														// statusSoloPair = " (pair)";
													}

													if (multipleNote.indexOf(MRCAnames) > -1) {
														if (repeatMRCAnameTracker[MRCAnames]) {
															repeatMRCAnameTracker[MRCAnames] += 1;
														} else {
															repeatMRCAnameTracker[MRCAnames] = 2;
															multipleNote = multipleNote.replace(MRCAnames, MRCAnames + " (path 1)");
															multipleDropDown = multipleDropDown.replace(MRCAnames, MRCAnames + " (path 1)");
														}
														MRCAnames += " (path "+ repeatMRCAnameTracker[MRCAnames] +")";
													}

													multipleNote +=  MRCAnames + "<br/>";
													let theCode = mID[0] + "|" + mID[1] + "|" + mID[2] ;
													

													multipleDropDown += "<OPTION value='" + theCode + "' " + (m == theMID ? "selected":"") + ">" + MRCAnames + statusSoloPair + "</OPTION>";

													// DEFINE the RELATIONSHIP
													var tmpRelationshipArray =  defineRelationshipsBetween(mID[1], mID[2], 0, 0 , 1, 2);			
													var possessiveLessRelationship = tmpRelationshipArray[0].replace("his","").replace("her","").replace("their","");
													if (tmpRelationshipArray[2] == true){
														theRelationshipsList  += "<font color=blue>" + possessiveLessRelationship + "</font><br/>";
														numSimple++;
													} else {
														theRelationshipsList  += "<I>" + possessiveLessRelationship + " - too distant for Simple DNA Confirmation</I><br/>";
														numComplex++;
													}

												}

												multipleDropDown += "</SELECT>";
												let bottomIunderstandHTML = "";

												if (numSimple == 0) {
													console.log("NO SIMPLE CONFIRMATIONS - MUST USE TRIANGULATION");
													document.getElementById("NextButton4").style.display = "none";
													bottomIunderstandHTML = "<P id=UnderstandWarningBanner style='background-color:yellow;'>&nbsp;<B>WARNING !!!</B>&nbsp;<P>This indicates that the two test takers are related in multiple ways.<br/>" + theRelationshipsList + "<br/><B>NONE of these relationships are close enough for a Simple DNA Confirmation</B>.<br/><br/>You will have to use TRIANGULATION to confirm this relationship, and find a third DNA tester to compare segments on the same choromosome.";


													if (numComplex > 1) {
														console.log("AND ... FURTHERMORE ... will need a 3rd person (or more) to Zero IN on a SINGLE COMMON ANCESTOR or COUPLE");
													}

												} else if (numSimple == 1) {
													console.log("ONE SIMPLE CONFIRMATION - Ignore the others - DO NOT USE TRIANGULATION");
													// document.getElementById("NextButton4").style.display = "none";
													bottomIunderstandHTML = "<P id=UnderstandWarningBanner style='background-color:cyan;'>&nbsp;<B>NOTE:</B>&nbsp;<P>This indicates that the two test takers are related in multiple ways.<br/>" + theRelationshipsList + "<br/><B>You can use DNA Confirmation for the closer relationship.</B> ";

													if (numComplex > 0) {
														console.log("MUST DO the SIMPLE CONFIRMATION.  Other relationship(s) too distant for a simple confirmation between just two test takers");
													}
													
												} else if (numSimple > 1) {
													console.log("MULTIPLE SIMPLE CONFIRMATIONS - Be Careful - Say I UNDERSTAND!");
													document.getElementById("NextButton4").style.display = "none";
													bottomIunderstandHTML = "<P id=UnderstandWarningBanner style='background-color:yellow;'>&nbsp;<B>WARNING !!!</B>&nbsp;<P>This indicates that the two test takers are related in multiple ways.<br/>" + theRelationshipsList + "<br/><B>IF</B> each of the ways is documented, genealogically, with sources,<br> - <B>AND</B> - the amount of DNA in common supports the multiple relationships claim, <BR/><B>THEN</B> you may proceed with DNA Confirmation. (One relationship at a time).<BR/><br/><span id=PressTheButtonSPAN>Press the button below to indicate you have read and understand this warning.</span></P><button id=UnderstandMultipleButton onclick='UnderstoodMultipleWarning();'>I Understand</button>";


													if (numComplex > 0) {
														console.log("MUST DO the SIMPLE CONFIRMATION.  Other relationship(s) too distant for a simple confirmation between just two test takers");
													}
													
												}

												

												document.getElementById('FormDIV4MultipleMRCAs').innerHTML = multipleNote + "<BR/>" + multipleDropDown + bottomIunderstandHTML;
											}

											
											// if (MRCA2list.length > 0) {
											// 	MRCA2 = MRCA2list[0];
											// }
											// alert (listOfAhns + " " + MRCA + " " + MRCA2);
											if (MRCA > -1 && MRCA2 > -1) {
												// alert ("COMMON Ancestors : " + personArray[ MRCA ].FirstName + " " + personArray[ MRCA ].LastNameAtBirth + " & " + personArray[ MRCA2 ].FirstName + " " + personArray[ MRCA2 ].LastNameAtBirth);  
												document.getElementById('AncS4').textContent = "S";
												hideAllDropDownsInMiniTree();
											} else if (MRCA > -1) {
												document.getElementById('AncS4').textContent = "";
												hideAllDropDownsInMiniTree();
												// alert ("COMMON Ancestor : " + personArray[ MRCA ].FirstName + " " + personArray[ MRCA ].LastNameAtBirth);
											} else {
												// alert ("Did NOT find a common ancestor");
											}
											// alert(theTestTaker);
											document.getElementById('Div2P2a').textContent = theNextBestFirstName(theTestPerson);
											// alert("hide / show forms");
											// document.getElementById('Div3P2a').textContent = theNextBestFirstName(theTestPerson);
											document.getElementById('Div3P2a').textContent = theNextBestFirstName(theTestPerson);
											document.getElementById('Div3P2b').textContent = theNextBestFirstName(theTestPerson);

											theTestTaker = theNextBestFirstName(theTestPerson); // change that so that in miniTree is updated with the person's first name and not the WikiTree ID

											if (MRCA > -1) {
												//alert(1312);
												// document.getElementById('Div2P1a').textContent = theNextBestFirstName(theTestPerson) ;
												// document.getElementById('Div2P1b').textContent = theProfilePerson.FirstName ;

												// document.getElementById('Div3P1a').textContent = theProfilePerson.FirstName ;
												// document.getElementById('Div3P1a').textContent = theProfilePerson.FirstName ;
												// document.getElementById('Div3P1b').textContent = theProfilePerson.FirstName ;
												// alert("hide / show forms");
												// document.getElementById('FormDIV1').style.display = 'none';
												// document.getElementById('FormDIV2').style.display = 'block';

												//document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theTestTakerID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameCurrent + "</A>";

												document.getElementById('YourDNA').innerHTML += "<br/>" + getDNAConfirmationAidHTML ( theTestPerson);
												// if (theTestPerson.LastNameAtBirth == theTestPerson.LastNameCurrent) {
												// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theTestTakerID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameAtBirth + "</A>";
												// } else {
												// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theTestTakerID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName(theTestPerson) + " (" + theTestPerson.LastNameAtBirth + ") " + theTestPerson.LastNameCurrent + "</A>";
												// }
												

												// $('#Div2P1').text('newtext');
												// Div2P1

												// COMPARE AHNENTAFEL TABLES

												// IF there's a MATCH
												// change the DIV showing to be the one with the MiniTree

												// IF there's NOT a match
												// Show WARNING about this, and the suggestion that they are not cousins - or - not close enough cousins, and might need a triangulation - OR - not fully documented cousins, with some missing links on WikiTree
												// 
												// updateGoBackLinks(4);

												document.getElementById('FormDIV1contacting').style.display = "none";
												document.getElementById('FormDIV2').style.display = "none";

												genderCheckFromSimple(1);

											} else {
												
												document.getElementById('IDgender').style.display = "block";

												var listOfGGGparents = "";
												for (var i = 16; i < 32 && MRCA == -1; i++) {
													if (ahnenIndexArrays[1][i] ) {
														if (listOfGGGparents >'') { listOfGGGparents += ",";}
														listOfGGGparents += ahnenIndexArrays[1][i];
													}
													if (ahnenIndexArrays[2][i] ) {
														if (listOfGGGparents >'') { listOfGGGparents += ",";}
														listOfGGGparents += ahnenIndexArrays[2][i];
													}
												}
												// alert("GGGparents: " + listOfGGGparents);

												var request3 = $.ajax({
													url: API_URL,
													crossDomain: true,
													xhrFields: { withCredentials: true },
													type: 'POST',
													dataType: 'json',
													
													//data: { 'action': 'getPerson', 'key': primaryID, 'fields': "Id, Name, FirstName, MiddleName, IsLiving, LastNameAtBirth, LastNameCurrent, Nicknames, LastNameOther, RealName, Prefix, Suffix, BirthLocation, DeathLocation, Gender, BirthDate, DeathDate,Father,Mother, Privacy, DataStatus", 'format': 'json' },
													data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theTestPersonID ,'ancestors':6, 'fields': "Id,Name,FirstName,RealName,BirthDate,LastNameCurrent,LastNameAtBirth,DeathDate,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

													// On successful POST return, check our data. Note from that data whether the login itself was
													// successful (setting session cookies if so). Call the user callback function when done.
													success: function(data) { 
														// alert("Success - in ready function!");						 
														var theJason = JSON.stringify(data);
														// alert(theJason);
														console.log("STATUS: " , data[0].status);
														if (data[0] && (data[0].status == 0 || data[0].status == "" || data[0].resultByKey[theTestPersonID].status == "")) {
															// alert (data[0].status);
															// alert ("There is data x " + data[0].people.length);
															
															// alert (data[0].items[0].person.FirstName);
															// document.getElementById('SurnamesFoundReport').innerHTML += data[0].items[0].person.FirstName + "<br>";

															for (var i in data[0].people) {
																// i = the Id of the object returned by the API ... perfect!
																personArray[ i ] = data[0].people[i];
															}
																

															theTestTakerID = data[0].resultByKey[ theTestPersonID ].Id 
															theTestPerson = data[0].people[theTestTakerID];
															

															// ========================
															// HOW DID WE GET HERE ????
															// ========================

															console.log("Populating ...", theTestPerson, theTestPersonID, theTestTaker,  data[0].resultByKey);
															
															populateAhnenTable(personArray,  theTestTakerID , 1, 2);
															loadDNAkitNumbers( theTestPerson.Name, 2);
															// alert("DONE Populating ...");
															
															listOfAhns = "";
															var MRCA = -1;
															var MRCA2 = -1;
															var MatchingIndices = [];


															for (var i = 1; i < 32 && MRCA == -1; i++) {
																if (ahnenIndexArrays[2][i] ) {
																	if (personArray[ ahnenIndexArrays[2][i] ] && personArray[ ahnenIndexArrays[2][i] ].FirstName ) {
																		listOfAhns += " "  + theNextBestFirstName(personArray[ ahnenIndexArrays[2][i] ]);
																		for (var j = 1; j < 32 && MRCA == -1; j++) {
																			if (ahnenIndexArrays[1][j] ) {
																				if (ahnenIndexArrays[1][j] == ahnenIndexArrays[2][i]) {
																					// we've found the MRCA
																					MRCA = ahnenIndexArrays[2][i];
																					theAncsAhnNum = j;
																					theAncsAhnNum4TT = i;
																					MatchingIndices = [j,i];
																					theAncestor = personArray[MRCA];
																					isCouple = false;
																					if (ahnenIndexArrays[1][j+1] == ahnenIndexArrays[2][i+1]) {
																						MRCA2 =ahnenIndexArrays[2][i + 1];
																						theAncsAhnNum2 = j + 1;
																						
																						theAncestor2 = personArray[MRCA2];
																						isCouple = true;
																						console.log("isCouple = TRUE : line 4026");
																						// alert ("is Couple True for : " + ahnenIndexArrays[1][j+1] );
																					}
																				}
																			}
																		}
																	}
																}

															}

															// alert (listOfAhns + " " + MRCA + " " + MRCA2);
															if (MRCA > -1 && MRCA2 > -1) {
																// alert ("COMMON Ancestors : " + personArray[ MRCA ].FirstName + " " + personArray[ MRCA ].LastNameAtBirth + " & " + personArray[ MRCA2 ].FirstName + " " + personArray[ MRCA2 ].LastNameAtBirth);  
																document.getElementById('AncS4').textContent = "S";
															} else if (MRCA > -1) {
																document.getElementById('AncS4').textContent = "";
																// alert ("COMMON Ancestor : " + personArray[ MRCA ].FirstName + " " + personArray[ MRCA ].LastNameAtBirth);
															}
															// alert(theTestTaker);

															// update the various forms that use the first name of theTestPerson
															document.getElementById('Div2P2a').textContent = theNextBestFirstName(theTestPerson);
															document.getElementById('Div3P2a').textContent = theNextBestFirstName(theTestPerson);
															document.getElementById('Div3P2b').textContent = theNextBestFirstName(theTestPerson);

															theTestTaker = theNextBestFirstName(theTestPerson); // change that so that in miniTree is updated with the person's first name and not the WikiTree ID

															if (MRCA > -1) {
																// document.getElementById('Div2P1a').textContent = theNextBestFirstName(theTestPerson) ;
																// document.getElementById('Div2P1b').textContent = theProfilePerson.FirstName ;

																// document.getElementById('Div3P1a').textContent = theProfilePerson.FirstName ;
																// document.getElementById('Div3P1a').textContent = theProfilePerson.FirstName ;
																// document.getElementById('Div3P1b').textContent = theProfilePerson.FirstName ;
																// alert("hide / show forms");
																// document.getElementById('FormDIV1').style.display = 'none';
																// document.getElementById('FormDIV2').style.display = 'block';

																//document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theTestTakerID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameCurrent + "</A>";

																document.getElementById('YourDNA').innerHTML += "<br/>" + getDNAConfirmationAidHTML ( theTestPerson);

																// if (theTestPerson.LastNameAtBirth == theTestPerson.LastNameCurrent) {
																// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theTestTakerID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameAtBirth + "</A>";
																// } else {
																// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theTestTakerID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName(theTestPerson) + " (" + theTestPerson.LastNameAtBirth + ") " + theTestPerson.LastNameCurrent + "</A>";
																// }

																// $('#Div2P1').text('newtext');
																// Div2P1

																// COMPARE AHNENTAFEL TABLES

																// IF there's a MATCH
																// change the DIV showing to be the one with the MiniTree

																// IF there's NOT a match
																// Show WARNING about this, and the suggestion that they are not cousins - or - not close enough cousins, and might need a triangulation - OR - not fully documented cousins, with some missing links on WikiTree

																// 
																// updateGoBackLinks(4);
																genderCheckFromSimple(1);

																// document.getElementById('FormDIV1contacting').style.display = "none";
																// document.getElementById('FormDIV2').style.display = "none";
																// document.getElementById('FormDIV4').style.display = "block";
																// howRelated2WikiTreeIDsWithMRCAfound( );
																// updateMiniTree();
															} else {

																var listOfGGGparents = "";
																for (var i = 16; i < 32 && MRCA == -1; i++) {
																	if (ahnenIndexArrays[1][i] ) {
																		if (listOfGGGparents >'') { listOfGGGparents += ",";}
																		listOfGGGparents += ahnenIndexArrays[1][i];
																	}
																	if (ahnenIndexArrays[2][i] ) {
																		if (listOfGGGparents >'') { listOfGGGparents += ",";}
																		listOfGGGparents += ahnenIndexArrays[2][i];
																	}
																}
																// alert("GGGparents: " + listOfGGGparents);

																
																// NO MRCA has been FOUND ... therefore ... valid WikiTree IDs that don't intersect ancestors 3rd cousin or closer
																// TIME to show off the WARNING about that

																document.getElementById('FormDIV1contacting').style.display = "none";
																document.getElementById('FormDIV2').style.display = "none";

																document.getElementById('Warning1P1').textContent = theNextBestFirstName( theProfilePerson)  + " " + theProfilePerson.LastNameCurrent ;
																document.getElementById('Warning1P2').textContent = theNextBestFirstName(theTestPerson)  + " " + theTestPerson.LastNameCurrent ;
																document.getElementById('Warning1P1b').textContent = theNextBestFirstName( theProfilePerson)  + " " + theProfilePerson.LastNameCurrent ;
																document.getElementById('Warning1P2b').textContent = theNextBestFirstName(theTestPerson)  + " " + theTestPerson.LastNameCurrent ;

																document.getElementById('FormDIV1Warning').style.display = "block";
															}
														} else {
															// alert ("NO DATA");
															document.getElementById('FormDIV1contacting').style.display = "none";
															document.getElementById('FormDIV2').style.display = "none";
															document.getElementById('Warning1P1').textContent = theNextBestFirstName( theProfilePerson)  + " " + theProfilePerson.LastNameCurrent ;
															document.getElementById('Warning1P2').textContent = theTestTaker;
															document.getElementById('Warning1P1b').textContent = theNextBestFirstName( theProfilePerson)  + " " + theProfilePerson.LastNameCurrent ;
															document.getElementById('Warning1P2b').textContent = theTestTaker;

															document.getElementById('FormDIV1Warning').style.display = "block";
														}

													},

													// On failed POST/server error, act like a failed login.
													error: function(xhr, status) { 
														alert('OOPS - something wrong ! - ' + status);
													}
												});  // end var request";


												// NO MRCA has been FOUND ... therefore ... valid WikiTree IDs that don't intersect ancestors 3rd cousin or closer
												// TIME to show off the WARNING about that

												document.getElementById('FormDIV1contacting').style.display = "none";
												document.getElementById('FormDIV2').style.display = "none";

												document.getElementById('Warning1P1').textContent = theNextBestFirstName( theProfilePerson)  + " " + theProfilePerson.LastNameCurrent ;
												document.getElementById('Warning1P2').textContent = theNextBestFirstName(theTestPerson)  + " " + theTestPerson.LastNameCurrent ;
												document.getElementById('Warning1P1b').textContent = theNextBestFirstName( theProfilePerson)  + " " + theProfilePerson.LastNameCurrent ;
												document.getElementById('Warning1P2b').textContent = theNextBestFirstName(theTestPerson)  + " " + theTestPerson.LastNameCurrent ;

												document.getElementById('FormDIV1Warning').style.display = "block";
											}
										} else {
											// WARNING - NO DATA FOR THE TEST TAKER (# 2) !!!!
											setupWarningRePersonNWikiTreeID(2,'ab',theTestTaker, data[0].resultByKey[theTestTaker].status);
											// alert ("NO DATA");											
										}

									},

									// On failed POST/server error, act like a failed login.
									error: function(xhr, status) { 
										alert('OOPS - something wrong ! - ' + status);
									}
								});  // end var request";


								
							} else {
								// NOT A VALID WIKITREE ID with NUMBERS AFTER THE "-"
								document.getElementById('FormDIV1contacting').style.display = 'none';

								genderCheckFromSimple(2);

								// // alert ("test for Gender need  " + theProfilePerson.Gender + " vs " + theTestPerson.Gender );
								// if (theProfilePerson.Gender == undefined ||  theTestPerson.Gender == undefined) {
								// 	document.getElementById('FormDIV1gender').style.display = 'block';
								// } else {
								// 	document.getElementById('FormDIV2').style.display = 'block';
								// }

							}

						} else {
							// NOT A VALID WIKITREE ID with a "-" in the middle of it !!!!

							document.getElementById('FormDIV1contacting').style.display = 'none';
							//theProfilePerson & theTestTaker

							genderCheckFromSimple(2);
							// document.getElementById('FormDIV1gender').style.display = 'block';
							// document.getElementById('FormDIV2').style.display = 'block';
						}




					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
			updateGoBackLinks(1);
		}



	function loadTriangulationInfo() {
			// alert("loadTriangulationInfo");
			loadTriInfo(3);
		}
		function loadXInfo() {
			// alert("loadXInfo");
			loadTriInfo(4);
			// loadComplexInfo();
		}
		function loadYInfo() {
			// alert("loadYInfo");
			loadTriInfo(4);
			// loadComplexInfo();
		}
		function loadMTInfo() {
			// alert("loadMTInfo");
			loadTriInfo(4);
			// loadComplexInfo();
		}


		function loadComplexInfo() {
			personArray = {}; // start with a fresh Person Array
			currentNegative = -5000;
			personParentsNegativeTranslation = [];

			theTestPerson = null;
			theProfilePerson = null;
			theProfilePerson2 = null;
			theProfilePerson3 = null;
			isDirectAncestor = false;

			// alert ("ready now for loadComplexInfo");

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID ,'ancestors':3, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.

								
					
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					// alert(theJason);
					console.log("STATUS: " , data[0].status);

					// if (data[0].status != 0) {
					if (data[0].resultByKey[theID].Id == null) {
						// // alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						if (theDNAconfType == 'x') {
							setupWarningRePersonNWikiTreeID(1,'abX',theID, data[0].status);

						} else if (theDNAconfType == 'Tri') {
							setupWarningRePersonNWikiTreeID(1,'abc',theID, data[0].status);

						} else {
							setupWarningRePersonNWikiTreeID(1,'ab',theID, data[0].status);
						}

					} else 
					
					if (data[0]) {
						// alert ("There is data x " + data[0].people.length);						
						// for (var i = 0; i < data[0].people.length; i++) {
						// 	personArray[ data[0].people[i].Id ] = data[0].people[i];
						// }
						theProfilePerson = data[0].people[ data[0].resultByKey[theID].Id];
						if (!theProfilePerson.Gender || theProfilePerson.Gender == undefined) {
							theProfilePerson.Gender = theProfilePersonGENDER;
						}
						personArray[ theProfilePerson.Id ] = theProfilePerson ;

						currentSetOfPeeps = [];
						currentSetOfPeeps.push(theProfilePerson.Id);
						loadDNAkitNumbers( theProfilePerson.Name, 1);

						updateNextSetOfPeeps();

						if ( currentSetOfPeeps.length == 1 && nextSetOfPeeps.length == 0) {
							// didn't get anything .. could be because parents are too private to reveal their secrets ...
							tempSetOfPeeps = [];
							for (var i in  data[0].people) {
								tempSetOfPeeps.push( data[0].people[i]);
							}
							// updateNextSetOfPeepsPlusSome2(tempSetOfPeeps, theProfilePerson.Id);
							updateNextSetOfPeepsPlusSome(tempSetOfPeeps);
						}


						theTestTaker = document.getElementById('TTaker2').value ;
						
						// alert("Populating ...");
						for (var i = 0; i < personParentsNegativeTranslation.length; i++) {
							personArray[ personParentsNegativeTranslation[i][0] ].Father = personParentsNegativeTranslation[i][1];
							personArray[ personParentsNegativeTranslation[i][0] ].Mother =  personParentsNegativeTranslation[i][2];
						}

						populateAhnenTable(personArray,  theProfilePerson.Id , 1, 1);
						

						document.getElementById('Div2P1a').textContent = theNextBestFirstName( theProfilePerson) ;
						document.getElementById('Div2P1b').textContent = theNextBestFirstName( theProfilePerson) ;

						document.getElementById('Div2P2a').textContent = theTestTaker;
						document.getElementById('Div3P2a').textContent = theTestTaker;
						document.getElementById('Div3P2b').textContent = theTestTaker;

						document.getElementById('YourDNA').innerHTML =   getDNAConfirmationAidHTML ( theProfilePerson);

						// if (theProfilePerson.LastNameAtBirth == theProfilePerson.LastNameCurrent) {
						// 	document.getElementById('YourDNA').innerHTML = "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameAtBirth + "</A>";
						// } else {
						// 	document.getElementById('YourDNA').innerHTML = "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( theProfilePerson) + " (" + theProfilePerson.LastNameAtBirth + ") " + theProfilePerson.LastNameCurrent + "</A>";
						// }
						

						
						// OK - we've just got the Profile Person .... let's see if the TestTaker also has a valid WikiTree ID
						

						document.getElementById('FormDIV1contacting').style.display = 'block';
			 			// alert("About to follow up");
						followUpNextSetOfPeeps(1);
			 			// alert("Did the follow up");
			



					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
			updateGoBackLinks(1);
		}

		function loadComplexInfo2() {
			// alert ("loading loadComplexInfo2  - " + theID2 + " in " + ahnenIndexArray.length + " array");
			document.getElementById('FormDIV1err').style.display = 'none';
			document.getElementById('SearchPN').textContent = 2;

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID2 ,'ancestors':3, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert(theJason);
					// if (data[0].status == 'Permission denied' || data[0].status == 'Illegal WikiTree ID' || data[0].status == 'Invalid user') {
					// if (data[0].status != 0) {
					if (data[0].resultByKey[theID2].Id == null) {
						// // alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						chooseAlternateWikiTreeID4Tester(2,2 , theID2, data[0].status);
					} else 
					if (data[0]) {
						// alert ("There is data x " + data[0].people.length + " in loadComplexInfo2");						
						// for (var i = 0; i < data[0].people.length; i++) {
						// 	personArray[ data[0].people[i].Id ] = data[0].people[i];
						// }
						theProfilePerson2 = data[0].people[ data[0].resultByKey[theID2].Id ];
						console.log({theProfilePerson2});
						if (!theProfilePerson2.Gender || theProfilePerson2.Gender == undefined) {
							theProfilePerson2.Gender = theProfilePerson2GENDER;
						}
						personArray[ theProfilePerson2.Id ] = theProfilePerson2 ;
						loadDNAkitNumbers( theProfilePerson2.Name, 2);

						currentSetOfPeeps = [];
						currentSetOfPeeps.push(theProfilePerson2.Id);

						updateNextSetOfPeeps();
						
						if ( currentSetOfPeeps.length == 1 && nextSetOfPeeps.length == 0) {
							// didn't get anything .. could be because parents are too private to reveal their secrets ...
							tempSetOfPeeps = [];
							for (var i in data[0].people) {
								tempSetOfPeeps.push( data[0].people[i]);
							}
							// updateNextSetOfPeepsPlusSome2(tempSetOfPeeps, theProfilePerson.Id);
							updateNextSetOfPeepsPlusSome(tempSetOfPeeps);
						}
						
						for (var i = 0; i < personParentsNegativeTranslation.length; i++) {
							console.log({i}, {personParentsNegativeTranslation});
							if (personParentsNegativeTranslation[i][0] && personParentsNegativeTranslation[i][0] > -1 && personParentsNegativeTranslation[i][1]) {
								personArray[ personParentsNegativeTranslation[i][0] ].Father = personParentsNegativeTranslation[i][1];
							}
								
							if (personParentsNegativeTranslation[i][0] && personParentsNegativeTranslation[i][0] > -1 && personParentsNegativeTranslation[i][2]) {
								personArray[ personParentsNegativeTranslation[i][0] ].Mother = personParentsNegativeTranslation[i][2];
							}

						}
						// alert("Populating ... table # 2 in loadComplexInfo2");
						populateAhnenTable(personArray,  theProfilePerson2.Id , 1, 2);
						

						document.getElementById('Div2P2a').textContent = theNextBestFirstName(theProfilePerson2);
						document.getElementById('Div3P2a').textContent = theNextBestFirstName(theProfilePerson2);
						document.getElementById('Div3P2b').textContent = theNextBestFirstName(theProfilePerson2);

						//document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID2 + "/899\">" + "DNA Ancestor Confirmation Aid for " + theProfilePerson2.RealName + " " + theProfilePerson2.LastNameAtBirth + "</A>";

						document.getElementById('YourDNA').innerHTML = "<br/>" +  getDNAConfirmationAidHTML ( theProfilePerson2);
						// if (theProfilePerson2.LastNameAtBirth == theProfilePerson2.LastNameCurrent) {
						// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID2 + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( theProfilePerson2)  + " " + theProfilePerson2.LastNameAtBirth + "</A>";
						// } else {
						// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID2 + "/899\">" + "DNA Ancestor Confirmation Aid for " +theNextBestFirstName(  theProfilePerson2)  + " (" + theProfilePerson2.LastNameAtBirth + ") " + theProfilePerson2.LastNameCurrent + "</A>";
						// }

						
						// OK - we've just got the Profile Person .... let's see if the TestTaker also has a valid WikiTree ID
						

						document.getElementById('FormDIV1contacting').style.display = 'block';
			 			// alert("About to follow up in loadComplexInfo2");
						followUpNextSetOfPeeps(1,2);
			 			// alert("Did the follow up from loadComplexInfo2");
			



					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
			updateGoBackLinks(1);
		}

		function loadComplexInfo3() {
			// alert ("loading loadComplexInfo3  - " + theID3 + " in " + ahnenIndexArrays[3].length + " array");
			document.getElementById('FormDIV1err').style.display = 'none';
			document.getElementById('SearchPN').textContent = 3;

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID3 ,'ancestors':3, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert(theJason);
					// if (data[0].status == 'Permission denied' || data[0].status == 'Illegal WikiTree ID' || data[0].status == 'Invalid user') {
					// if (data[0].status != 0) {
					if (data[0].resultByKey[theID3].Id == null) {
						// // alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						chooseAlternateWikiTreeID4Tester(3.1 , 3 , theID3, data[0].status);
					} else 
					if (data[0]) {
						// alert ("There is data x " + data[0].people.length + " in loadComplexInfo2");						
						// for (var i = 0; i < data[0].people.length; i++) {
						// 	personArray[ data[0].people[i].Id ] = data[0].people[i];
						// }
						theProfilePerson3 = data[0].people[data[0].resultByKey[theID3].Id];
						if (!theProfilePerson3.Gender || theProfilePerson3.Gender == undefined) {
							theProfilePerson3.Gender = theProfilePerson3GENDER;
						}
						personArray[ theProfilePerson3.Id ] = theProfilePerson3 ;
						loadDNAkitNumbers( theProfilePerson3.Name, 3);

						currentSetOfPeeps = [];
						currentSetOfPeeps.push(theProfilePerson3.Id);

						updateNextSetOfPeeps();
						
						if ( currentSetOfPeeps.length == 1 && nextSetOfPeeps.length == 0) {
							// didn't get anything .. could be because parents are too private to reveal their secrets ...
							tempSetOfPeeps = [];
							for (var i in data[0].people) {
								tempSetOfPeeps.push( data[0].people[i]);
							}
							// updateNextSetOfPeepsPlusSome2(tempSetOfPeeps, theProfilePerson.Id);
							updateNextSetOfPeepsPlusSome(tempSetOfPeeps);
						}
						
						for (var i = 0; i < personParentsNegativeTranslation.length; i++) {
							personArray[ personParentsNegativeTranslation[i][0] ].Father = personParentsNegativeTranslation[i][1];
							personArray[ personParentsNegativeTranslation[i][0] ].Mother = personParentsNegativeTranslation[i][2];
						}
						// alert("Populating ... table # 3 in loadComplexInfo3");
						populateAhnenTable(personArray,  theProfilePerson3.Id  , 1, 3);
						// alert ("done populationg");
						
						document.getElementById('Div1bP3').textContent = theNextBestFirstName(theProfilePerson3);
						// document.getElementById('Div3P3a').textContent = theNextBestFirstName(theProfilePerson3);
						// document.getElementById('Div3P3b').textContent = theNextBestFirstName(theProfilePerson3);

						//document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID2 + "/899\">" + "DNA Ancestor Confirmation Aid for " + theProfilePerson2.RealName + " " + theProfilePerson2.LastNameAtBirth + "</A>";

						document.getElementById('YourDNA').innerHTML = "<br/>" +  getDNAConfirmationAidHTML ( theProfilePerson3);

						// if (theProfilePerson3.LastNameAtBirth == theProfilePerson3.LastNameCurrent) {
						// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID3 + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( theProfilePerson3)  + " " + theProfilePerson3.LastNameAtBirth + "</A>";
						// } else {
						// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + theID3 + "/899\">" + "DNA Ancestor Confirmation Aid for " +theNextBestFirstName(  theProfilePerson3)  + " (" + theProfilePerson3.LastNameAtBirth + ") " + theProfilePerson3.LastNameCurrent + "</A>";
						// }

						
						// OK - we've just got the Profile Person .... let's see if the TestTaker also has a valid WikiTree ID
						

						document.getElementById('FormDIV1contacting').style.display = 'block';
			 			// alert("About to follow up in loadComplexInfo3");
						followUpNextSetOfPeeps(1,3);
			 			// alert("Did the follow up from loadComplexInfo3");
			



					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
			updateGoBackLinks(1);
		}



		function loadTriInfo( whereAfter = 3) {
			console.log ("loadTriInfo  " + whereAfter + " " + theDNAconfType, "ajax request: theID = " + theID);
			theProfilePerson = null;
			theProfilePerson2 = null;
			theProfilePerson3 = null;

			isDirectAncestor = false;
			document.getElementById('SearchPN').textContent = 0;
			document.getElementById('FormDIV1err').style.display = 'none';
			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID ,'ancestors':1, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// gg("Success - in  loadTriInfo ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("loadTriInfo: theID :", theID  , "STATUS: " , data[0].status);

					// if (data[0].status != 0) {
					if (data[0].resultByKey[theID].Id == null) {
						// // alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						if (theDNAconfType == 'x') {
							console.log('id3 = ', theID3);
							if (theID3 && theID3 > ''){
								setupWarningRePersonNWikiTreeID(1,'abcX',theID, data[0].status);
							} else {
								setupWarningRePersonNWikiTreeID(1,'abX',theID, data[0].status);
							}


						} else if (theDNAconfType == 'Tri') {
							setupWarningRePersonNWikiTreeID(1,'abc',theID, data[0].status);

						} else {
							setupWarningRePersonNWikiTreeID(1,'ab',theID, data[0].status);
						}

					} else {
						// theID is OK
						// alert ("ID 1 is OK !");
						// document.getElementById('SearchPN').textContent = 2;
						let theResultByKey = data[0].resultByKey;
						console.log({theResultByKey});

						theProfilePerson = data[0].people[theResultByKey[theID].Id]; //data[0].people[0];
						loadDNAkitNumbers( theID, 1); // find the DNA kit info for Person # 1

						console.log("PERSON # 1 : ", theID, data[0].resultByKey[theID],  theProfilePerson);

						var request2 = $.ajax({
							url: API_URL,
							crossDomain: true,
							xhrFields: { withCredentials: true },
							type: 'POST',
							dataType: 'json',
							
							data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID2 ,'ancestors':1, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

							// On successful POST return, check our data. Note from that data whether the login itself was
							// successful (setting session cookies if so). Call the user callback function when done.
							success: function(data) { 
								// gg("Success - in  loadTriInfo ready function!");						 
								var theJason = JSON.stringify(data);
								console.log("STATUS: " , data[0].status);
								let thisDataObj = data[0];
								console.log("loadTriInfo: theID2 :", theID2  , data[0], "resultByKey:", data[0].resultByKey[theID2].status, data[0].resultByKey[theID2].Id == null,  {thisDataObj});
								// if (data[0].status != 0) { /// this test used to work ... no longer (as of API change, January 2024)
								if (data[0].resultByKey[theID2].Id == null) {
									// // alert ("Don't go there !");
									console.log("Don't go there:" , data[0].status);
									chooseAlternateWikiTreeID4Tester(3, 2,theID2, data[0].status);
								} else {
									// theID is OK
									// alert ("ID 2 is OK !");
									theProfilePerson2 = data[0].people[data[0].resultByKey[theID2].Id];
									console.log("PERSON # 2 : ", theID2, data[0].resultByKey[theID2], theProfilePerson2);

									if (theDNAconfType == 'y' && (theProfilePerson.Gender != "Male" || theProfilePerson2.Gender != "Male")) {
										let nonMaleTesters = [];
										if (theProfilePerson.Gender != "Male") {
											nonMaleTesters.push("Person #1 (" + (theProfilePerson.Name || theID) + "): " + (theProfilePerson.Gender || "gender not specified"));
										}
										if (theProfilePerson2.Gender != "Male") {
											nonMaleTesters.push("Person #2 (" + (theProfilePerson2.Name || theID2) + "): " + (theProfilePerson2.Gender || "gender not specified"));
										}
										document.getElementById("YGenderWarningDetails").textContent = nonMaleTesters.join("; ");
										document.getElementById("FormDIV1contacting").style.display = "none";
										document.getElementById("FormDIV1YGenderWarning").style.display = "block";
										return;
									}

									loadDNAkitNumbers( theID2, 2); // find the DNA kit info for Person # 2									

									if (theID3 > '') {

									  var request3 = $.ajax({
										url: API_URL,
										crossDomain: true,
										xhrFields: { withCredentials: true },
										type: 'POST',
										dataType: 'json',
										
										data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': theID3 ,'ancestors':1, 'fields': "Id,Name,FirstName,RealName,BirthDate,DeathDate,LastNameCurrent,LastNameAtBirth,Father,Mother,Gender,BioFather,BioMother,DataStatus", 'format': 'json' },

										// On successful POST return, check our data. Note from that data whether the login itself was
										// successful (setting session cookies if so). Call the user callback function when done.
										success: function(data) { 
											// gg("Success - in  loadTriInfo ready function!");						 
											var theJason = JSON.stringify(data);
											console.log("loadTriInfo: theID3 :", theID3  , data[0].status);
											// if (data[0].status != 0) {
											if (data[0].resultByKey[theID3].Id == null) {
												// // alert ("Don't go there !");
												console.log("Don't go there:" , data[0].status);
												chooseAlternateWikiTreeID4Tester(3, 3, theID3, data[0].status);
											} else {
												// theID is OK
												// alert ("ID 3 is OK !");
												theProfilePerson3 = data[0].people[data[0].resultByKey[theID3].Id];

												console.log("PERSON # 3 : ", theID3, data[0].resultByKey[theID3],  theProfilePerson3);

												loadDNAkitNumbers( theID3, 3); // find the DNA kit info for Person # 3
												// alert("Going to Gender Check after 3 searches");
												genderCheckFromComplex( whereAfter );
												// loadTriInfo2();
											}

										}
									  });
									} else {
										// alert("Going to Gender Check after ONLY 2 searches");
										genderCheckFromComplex( whereAfter );
									}
								}

							}
						});
					}

				}
			});			
			// alert ("DONE Tri info");
		}

		function loadTriInfo2GetPeople(whichNum, startAt=0) {
			console.log("function loadTriInfo2GetPeople(",whichNum, startAt,")");
			let thisID = theID;
			if (whichNum == 1) {
				thisID = theID;
			}else if (whichNum == 2) {
				thisID = theID2;
			}else if (whichNum == 3) {
				thisID = theID3;
			}
			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': thisID ,'ancestors':maxNumAncestorsForTriangulation, 'fields': "Id,Name,FirstName,LastNameAtBirth,RealName,BirthDate,DeathDate,LastNameCurrent,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'start':startAt  ,'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// gg("Success - in  loadTriInfo ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert(theJason);
					if (data[0]) {
						// alert ("There is data x " + data[0].people.length);	
						let thisProfilePersonID = data[0].resultByKey[thisID].Id;					
						for (var i in data[0].people) {
							// if ( data[0].people[i].Name == "Leger-743") { alert ("Charles Alert !!!");}
							personArray[ i ] = data[0].people[i];
							// if (personArray[i].Name == thisID || i == thisID) {
							// 	thisProfilePersonID = i;
							// }
						}

						let thisProfilePerson = data[0].people[thisProfilePersonID];
						if (!thisProfilePerson) {
							thisProfilePerson = personArray[thisProfilePersonID];
						}

						if (thisProfilePerson && (!thisProfilePerson.Gender || thisProfilePerson.Gender == undefined)) {
							thisProfilePerson.Gender = thisProfilePersonGENDER;
						}
						
						// alert("Populating ...");
						populateAhnenTable(personArray,  thisProfilePersonID , 1, whichNum);
						// alert("DONE Populating ...");
					 	// document.getElementById('Div2P' + whichNum + 'a').textContent = theNextBestFirstName( thisProfilePerson) ;
						// document.getElementById('Div2P' + whichNum + 'b').textContent = theNextBestFirstName( thisProfilePerson) ;
						
						// alert("hide / show forms");
						document.getElementById('FormDIV1contacting').style.display = 'block';

						if (data[0].status.indexOf("Maximum number of profiles") > -1) {
							// we need to run this routine again to grab some more profiles
							loadTriInfo2GetPeople(whichNum, startAt + 1000);
						} else {
							// we're done at this level, ready to move onto the next Profile Person, or end this Tri Info getPeople routine
							// reportAllPeeps();

							let newALIAS = "";
							let howManyGensAway = -1;

							if (theDNAconfType == "Tri" && whichNum ==2 && numGensFromWikiTreeID2toCousin > 0) {
								console.log("DO SOMETHING AMAZING HERE SO THAT WE GET OUR CORRECT MINI TREE AND PEEPS FOR PERSON # 2");
								newALIAS = document.getElementById("TTakerB").value;
								howManyGensAway = numGensFromWikiTreeID2toCousin;
								numGensFromWikiTreeID2toCousin = 0;
							} else if (theDNAconfType == "Tri" && whichNum ==3 && numGensFromWikiTreeID3toCousin > 0) {
								console.log("DO SOMETHING AMAZING HERE SO THAT WE GET OUR CORRECT MINI TREE AND PEEPS FOR PERSON # 3");
								newALIAS = document.getElementById("TTakerC").value;
								howManyGensAway = numGensFromWikiTreeID3toCousin;
								numGensFromWikiTreeID3toCousin = 0;
							}

							if (theDNAconfType == "Tri" && newALIAS > "") {
								let newPersonGender = "Unknown";
								if (whichNum == 2 && testTaker2Gender > "") {
									newPersonGender = testTaker2Gender;
								} else if (whichNum == 3 && testTaker3Gender > "") {
									newPersonGender = testTaker3Gender;
								}
								let newPersonID = addNewPlaceholderPersonToPersonArray({FirstName:newALIAS, closestAnc1Name:thisProfilePerson.Name, closestAnc2Name:'',  closestAnc1ID:thisProfilePersonID, closestAnc2ID:0,numGensAway:howManyGensAway, Gender:newPersonGender});
								let childOfNewProfilePersonID = newPersonID;
								console.log({newPersonID}, personArray[newPersonID]);

								if(howManyGensAway == 2) {
									childOfNewProfilePersonID = addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth:"Parent"});
									personArray[newPersonID].Father = childOfNewProfilePersonID;
								} else if(howManyGensAway > 2) {
									let prefix = "Gt".repeat(howManyGensAway - 3) + "Grand";
									childOfNewProfilePersonID = addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth: prefix + "Parent"});

									let tmpParentID = childOfNewProfilePersonID;
									let tmpChildID = childOfNewProfilePersonID ; //addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth:"Parent"});
									// personArray[tmpChildID].Father = tmpParentID;


									for (var i = howManyGensAway - 3; i >= 0; i--) {
										tmpParentID = tmpChildID;
										if (i == 0) {
											prefix = "";
										} else {
											prefix = "Gt".repeat(i - 1) + "Grand";
										}

										tmpChildID = addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth:prefix + "Parent"});
										personArray[tmpChildID].Father = tmpParentID;
									}
										

									personArray[newPersonID].Father = tmpChildID;
								}

								let thisNewProfilePerson = thisProfilePerson;
								let thisNewProfilePersonID = thisProfilePersonID;

								if (thisNewProfilePerson) {
									if (thisNewProfilePerson.Gender && thisNewProfilePerson.Gender == "Male") {
										personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID;
										// if (thisNewProfilePerson2) {
										// 		personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID2;
										// }
											
									} else if (thisNewProfilePerson.Gender && thisNewProfilePerson.Gender == "Female") {
										personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID;
										// if (thisNewProfilePerson2) {
										// 	personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID2;
										// }
									} else {

										// if (thisNewProfilePerson2 && thisNewProfilePerson2.Gender && thisNewProfilePerson2.Gender == "Male") {
										// 		personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID2;
										// 		personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID;
										// } else  {
											personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID;
										// 	if (thisNewProfilePerson2) {
										// 		personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID2;
										// 	}
										// }
									

									}
								}

								console.log("function loadTriInfo2GetPeople - inside - ADDING TO POP AHNEN TABLE: ", newPersonID , 1, whichNum);
								populateAhnenTable(personArray,  newPersonID , 1, whichNum);
								// addNewTestTakerToCommonAncestorDetails(whichNum);

							}


							// LET's MOVE the report All Peeps portion to HERE ... AFTER we've cooked up the books on the ahnenTable !
							doExtendedAncestorSearch4Triangulation = document.getElementById("useGens4Tri14").checked;
							if (whichNum == 3 && doExtendedAncestorSearch4Triangulation == true) {
								loadExtraAncestorsBeforeFinalReportAllPeeps();
							} else {
								reportAllPeeps();
							}


							if (whichNum < 3 ){
								document.getElementById('SearchPN').textContent = whichNum + 1;
								loadTriInfo2GetPeople(whichNum + 1, 0);
							} else {
								// LAST THING TO DO:  Update the Confirmation Add links up top
								document.getElementById('YourDNA').innerHTML = "";
								for (var i = 1; i <= 3; i++) {
									let thisProfilePerson = personArray[ ahnenIndexArrays[i][1] ];
									// CHECK HERE FOR PRIV8 PERSON - AND - IF SO, LOAD THE Conf Aid for CLOSEST RELATIVE
									if (thisProfilePerson) {							
										document.getElementById('YourDNA').innerHTML += "<BR/>" + getDNAConfirmationAidHTML ( thisProfilePerson);
									}
								}
							}

						}

					}  
				},
				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}

			} );

		}

		
		function loadExtraAncestorsBeforeFinalReportAllPeeps(whichNum = 0) {
			listOfExtendedAncsToLoad = [];
			let startI = 1;
			let endI = 3;
			if (whichNum > 0) {
				startI = whichNum;
				endI = whichNum;
			}

			for (var i = startI; i <= endI; i++) {
				for (var ahnNum = 2 ** maxNumAncestorsForTriangulation; ahnNum < 2 ** (maxNumAncestorsForTriangulation+1); ahnNum++) {
					if (ahnenIndexArrays[i][ahnNum] && ahnenIndexArrays[i][ahnNum] > 0) {
						let endGenAnc = personArray [ ahnenIndexArrays[i][ahnNum] ];
						if (endGenAnc) {
							if (endGenAnc.Father && endGenAnc.Father > 0) {
								if (listOfExtendedAncsToLoad.indexOf(endGenAnc.Father) == -1) {
									listOfExtendedAncsToLoad.push(endGenAnc.Father);
								}

							}
							if (endGenAnc.Mother && endGenAnc.Mother > 0) {
								if (listOfExtendedAncsToLoad.indexOf(endGenAnc.Mother) == -1) {
									listOfExtendedAncsToLoad.push(endGenAnc.Mother);
								}

							}
						}
					}
				}
			}
			console.log("EXTENDED PREP:  We need to load an Extended Bunch of Ancestors, starting at Gen # 11 with ", listOfExtendedAncsToLoad.length, " of them.");
			console.log({listOfExtendedAncsToLoad});

			if (listOfExtendedAncsToLoad.length > 0) {
				doLoadExtendedAncestors(whichNum);
			} else {
				reportAllPeeps();
			}
		}

		function doLoadExtendedAncestors(whichNum) {
			document.getElementById("ListOfNamesDIV").style.display = "block";

			document.getElementById("ListOfNamesDIV").innerHTML = "<span style='background-color:darkorange; color:white; font-size:large; font-weight;bold;'>&nbsp;&nbsp;LOADING EXTENDED ANCESTORS ..." + listOfExtendedAncsToLoad.length + " to go ... (plus their parents, grandparents and great-grandparents) ...&nbsp;&nbsp;</span>";
			let listOfIDs = "";
			for (var i = 0; i < 66 ; i++) {
				if (i > 0) {
					listOfIDs += ",";
				}
				listOfIDs+=  listOfExtendedAncsToLoad.pop();
				if (listOfExtendedAncsToLoad.length == 0) {
					break;
				}
			}

			console.log("Ready to load EXTENDED list with this group of ideally 66 souls:", listOfIDs);
			

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': listOfIDs ,'ancestors':3, 'fields': "Id,Name,FirstName,LastNameAtBirth,RealName,BirthDate,DeathDate,LastNameCurrent,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// gg("Success - in  loadTriInfo ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert(theJason);
					if (data[0]) {
						// alert ("There is data x " + data[0].people.length);	
									
						for (var i in data[0].people) {
							// if ( data[0].people[i].Name == "Leger-743") { alert ("Charles Alert !!!");}
							personArray[ i ] = data[0].people[i];
							// if (personArray[i].Name == thisID || i == thisID) {
							// 	thisProfilePersonID = i;
							// }
						}
										
						if (listOfExtendedAncsToLoad.length > 0) {
							doLoadExtendedAncestors(whichNum);
						} else {
							let startI = 1;
							let endI = 3;
							if (whichNum > 0) {
								startI = whichNum;
								endI = whichNum;
							}

							for (var i = startI; i <= endI; i++) {
								for (var ahnNum = 2 ** (maxNumAncestorsForTriangulation - 1); ahnNum < 2 ** (maxNumAncestorsForTriangulation * 1); ahnNum++) {
									if (ahnenIndexArrays[i][ahnNum] && ahnenIndexArrays[i][ahnNum] > 0 && personArray[ahnenIndexArrays[i][ahnNum]]) {
										populateAhnenTable(personArray,  ahnenIndexArrays[i][ahnNum] , ahnNum, i);
									}

								}
							}

							if (whichNum > 0) {
								addNewTestTakerToCommonAncestorDetails(whichNum);
								loadDNAkitNumbers( ahnenIndexArrays[whichNum][1], whichNum);
							} else {
								reportAllPeeps();
							}
							document.getElementById("ListOfNamesDIV").innerHTML = "";

						}

						
					}  
				},
				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}

			} );


		}


		function getDNAConfirmationAidHTML ( thisProfilePerson) {
			console.log("function getDNAConfirmationAidHTML", thisProfilePerson);
			// console.trace();

			let theAidHTML = "";

			let thisID = thisProfilePerson.Name;
			
			// IF a PRIVATE NAME ID --> THEN WE ARE DEALING WITH AN ALIAS ... so ... provide link to CLOSEST ANC (and a relationship descriptor)
			if (thisID.indexOf("Priv8") > -1) {
				let thisClosestAncPerson = personArray[ thisProfilePerson.closestAnc1ID ];
				let relationshipOfAnc = "";
				if (thisProfilePerson.numGensAway == 1) {
					relationshipOfAnc = ", parent of ";	
				} else if (thisProfilePerson.numGensAway == 2) {
					relationshipOfAnc = ", grandparent of ";	
				} else if (thisProfilePerson.numGensAway > 2) {
					relationshipOfAnc =  ", great-".repeat(thisProfilePerson.numGensAway - 2) +"grandparent of ";	
				}
				relationshipOfAnc += thisProfilePerson.FirstName;

				if (thisClosestAncPerson.LastNameAtBirth == thisClosestAncPerson.LastNameCurrent) {
					theAidHTML += "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + thisProfilePerson.closestAnc1Name + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( thisClosestAncPerson) + " " + thisClosestAncPerson.LastNameAtBirth + "</A>" + relationshipOfAnc;
				} else {
					theAidHTML += "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + thisProfilePerson.closestAnc1Name + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( thisClosestAncPerson) + " (" + thisClosestAncPerson.LastNameAtBirth + ") " + thisClosestAncPerson.LastNameCurrent + "</A>" + relationshipOfAnc;
				}

			// ELSE .... just provide the regular link to the PROFILE PERSON provided
				// Regular LastName version
			} else if (thisProfilePerson.LastNameAtBirth == thisProfilePerson.LastNameCurrent) {
				theAidHTML += "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + thisID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( thisProfilePerson) + " " + thisProfilePerson.LastNameAtBirth + "</A>";

				// LastNameAtBirth in brackets in case of switch up
			} else {
				theAidHTML += "<A target=_blank href=\"https://www.wikitree.com/treewidget/" + thisID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( thisProfilePerson) + " (" + thisProfilePerson.LastNameAtBirth + ") " + thisProfilePerson.LastNameCurrent + "</A>";
			}

			return theAidHTML;

		}

		function loadTriInfo2() {
			console.log ("loadTriInfo2  "  + theDNAconfType, "ajax request: theID = " + theID, "DEPTH: " + maxNumAncestorsForTriangulation);
			personArray = {}; // start with a fresh Person Array
			theTestPerson = null;
			theProfilePersonGENDER = undefined; if (theProfilePerson && theProfilePerson.Gender) { theProfilePersonGENDER = theProfilePerson;};
			theProfilePerson2GENDER = undefined; if (theProfilePerson2 && theProfilePerson2.Gender) { theProfilePerson2GENDER = theProfilePerson2;};
			theProfilePerson3GENDER = undefined; if (theProfilePerson3 && theProfilePerson3.Gender) { theProfilePerson3GENDER = theProfilePerson3;};
			// alert (theProfilePerson.Gender + " "  + theProfilePerson2.Gender + " "  +theProfilePerson3.Gender  );
			theProfilePerson = null;
			theProfilePerson2 = null;
			theProfilePerson3 = null;

			document.getElementById('FormDIV1contacting').style.display = 'block';
			document.getElementById('SearchPN').textContent = 1;

			loadTriInfo2GetPeople(1,0);
	

			updateGoBackLinks(1);
		}


		function loadDNAkitNumbers( theID4kit, num) {
			console.log("loading for "  + theID4kit + " : " + num);
			var theID2use = theID4kit;
			var theNum = num;
			var firstFTDNA = '';
			var firstGEDmatch = '';
			var mitoydna4MT = '';
			var mitoydna4Y = '';
			var haplo = '';
			var haploM = '';

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getDNATestsByTestTaker', 'appId':'DNAconf' ,'key': theID2use  , 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.

								
					
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert(theJason);

					if (data[0].status != 0) {
						// alert ("Don't go there !");
						console.log("Don't go there:" , data[0].status);
						
					} else 
					
					if (data[0]) {
						// alert ("There's some DNA kit data here for " + theID2use + " : " + data[0].page_name + " : " + data[0].dnaTests.length);

						firstFTDNA = '';
						firstGEDmatch = '';
						mitoydna4MT = '';
						mitoydna4Y = '';
						haplo = '';
						haploM = '';
						for (var i = 0; i < data[0].dnaTests.length; i++) {

							if (data[0].dnaTests[i].ftdna > '' && firstFTDNA == '' /*&& data[0].dnaTests[i].taker.Name == theID2use*/) { 
								// alert ("FTDNA # " + theNum + " : " + data[0].dnaTests[i].ftdna + " from " +  data[0].dnaTests[i].assignedBy);
								firstFTDNA =   data[0].dnaTests[i].ftdna;
							}
							if (data[0].dnaTests[i].gedmatch > '' && firstGEDmatch == '' /*&& data[0].dnaTests[i].taker.Name == theID2use*/) { 
								// alert ("GEDmatch: " + theNum + " : " + data[0].dnaTests[i].gedmatch  + " from " +  data[0].dnaTests[i].assignedBy);
								firstGEDmatch =   data[0].dnaTests[i].gedmatch;
							}

							if (data[0].dnaTests[i].dna_id == 7 &&   data[0].dnaTests[i].mitoydna  > '' ) {
								mitoydna4MT = data[0].dnaTests[i].mitoydna;
								haploM = data[0].dnaTests[i].haplom;
							}
							if (data[0].dnaTests[i].dna_id == 8 &&   data[0].dnaTests[i].mitoydna  > '' ) {
								mitoydna4Y = data[0].dnaTests[i].mitoydna;
								haplo = data[0].dnaTests[i].haplo;
							}
						}
						// alert ("Done tests");
						kitNums[ theNum + "-ftdna"] =   firstFTDNA;
						kitNums[ theNum + "-gedmatch"] =   firstGEDmatch;
						kitNums[ theNum + "-mitoydna4MT"] =   mitoydna4MT;
						kitNums[ theNum + "-mitoydna4Y"] =   mitoydna4Y;
						kitNums[ theNum + "-haplo"] =   haplo;
						kitNums[ theNum + "-haploM"] =  haploM;

						console.log("Update kit # " + theNum , firstFTDNA, firstGEDmatch, mitoydna4MT, mitoydna4Y, haplo, haploM);
					}

				}

			});

		}		

		function loadDescendantsOfMRCA(theValue) {
			mrcaDescendantsArray = [];
			console.log("loadDescendantsOfMRCA : START looking for Descendants of " , theAncestor.Name, theAncestor.Id);
			if (isCouple == true) {
				console.log(" and " , theAncestor2.Name, theAncestor2.Id);
			}

				var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				// data: { 'action': 'getDescendants', 'key': theAncestor.Id  , 'depth':5 , 'fields':'Id,Name,Gender,RealName,LastNameAtBirth,Mother,Father',  'format': 'json' },
				data: { 'action': 'getPeople', 'appId':'DNAconf', 'keys': theAncestor.Id  , 'descendants':5 , 'fields':'Id,Name,Gender,BirthDate,DeathDate,RealName,LastNameAtBirth,Mother,Father,BioMother,BioFather,DataStatus',  'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.

								
					
				success: function(data) { 
					// alert("Success - in ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert(theJason);

					// if (data[0].status != 0) {
					if (data[0].resultByKey[ theAncestor.Id ].Id == null) {
						console.log ("loadDescendantsOfMRCA : Don't go there !");
						
					} else 
					
					if (data[0]) {
						// alert ("There's some DNA kit data here for " + theID2use + " : " + data[0].page_name + " : " + data[0].dnaTests.length);

						if (!data[0].people) {
							console.log("loadDescendantsOfMRCA :  NO DESCENDANTS object found")
						} else {
							// console.log("loadDescendantsOfMRCA :  found " + data[0].people.length + "  DESCENDANTS !!!")

							
							for (var i in data[0].people) {
								let thisD = data[0].people[i];
								mrcaDescendantsArray.push(thisD);
								console.log("loadDescendantsOfMRCA : ", thisD.RealName, thisD.LastNameAtBirth);
								 
							}
							// alert ("Done tests");
						}
						 

						// console.log("loadDescendantsOfMRCA : Update kit # " + theNum , firstFTDNA, firstGEDmatch, mitoydna4MT, mitoydna4Y, haplo, haploM);
					}

					let pickAncDDownHTML = "<select id=Peep2AncDDown1 onchange='updatePeepDDs(1," + theValue + ");'><option>Choose one:</option>";

					var leftSideChildsAhnNum = Math.floor(theAncsAhnNum / 2);
					var leftSideChildsID = ahnenIndexArrays[1][leftSideChildsAhnNum];
					for ( i = 0; i < mrcaDescendantsArray.length; i++) {
						let thisD = mrcaDescendantsArray[i];
						if (isCouple == false && (thisD.Father == theAncestor.Id || thisD.Mother == theAncestor.Id) ) {
							console.log("loadDescendantsOfMRCA : FIRST DROP DOWN : ", thisD.RealName, thisD.LastNameAtBirth, thisD.Id , leftSideChildsID);
							if (thisD.Id != leftSideChildsID) {
								// can't use the same person as an option on the Right Side as is already populated as a child of the MRCA on the Left Side
								pickAncDDownHTML += "<option value=" + thisD.Id + ">" + thisD.RealName + " " + thisD.LastNameAtBirth + "</option>";
							}
						} else if (isCouple == true && (thisD.Father == theAncestor.Id && thisD.Mother == theAncestor2.Id) ) {
							console.log("loadDescendantsOfMRCA : FIRST DROP DOWN : ", thisD.RealName, thisD.LastNameAtBirth, thisD.Id , leftSideChildsID);
							if (thisD.Id != leftSideChildsID) {
								// can't use the same person as an option on the Right Side as is already populated as a child of the MRCA on the Left Side
								pickAncDDownHTML += "<option value=" + thisD.Id + ">" + thisD.RealName + " " + thisD.LastNameAtBirth + "</option>";
							}
						}

					}

					pickAncDDownHTML += "<option value=-3>" + howGreatIsThisOrNot(theValue) + "mother not on WikiTree</option>";
					pickAncDDownHTML += "<option value=-2>" + howGreatIsThisOrNot(theValue) + "father not on WikiTree</option>";
					pickAncDDownHTML += "</select>";
					document.getElementById('P2peepDDown1').innerHTML = pickAncDDownHTML;
					document.getElementById('P2peepDDown1').style.display = "revert";
					for (i = 2; i <= 5; i++) {
						document.getElementById('P2peepDDown' + i).innerHTML = "";	
					}

				}

			});
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

			  	alertMsg =  /*typeOfCode +*/ ' DNA source citation copied to your clipboard.' + newLine + newLine + 'Use CTRL + V (on a PC) or CMD + V (on a Mac)\nto paste this WikiText code into \nyour Profile or into a Free Space page.';
			  // }

			  alert (alertMsg);
			  
		}

		// When the document is ready, see if we have a user session with checkLogin().
		// If no, then show the "need login" div with the form and button to post to the API clientLogin() function.
		// If yes, then hide that and show our function content.
		$(document).ready(function(){ 
			// alert ("Ready");

			// LOAD COOKIES
			let theCookieString = getCookie("wtapps_DNAconf");
			console.log({theCookieString});


			let theSavedCookieSettings = JSON.parse(theCookieString);
			console.log(theSavedCookieSettings);

			if (theSavedCookieSettings && theSavedCookieSettings.cousinFormat  && theSavedCookieSettings.cousinFormat > "" ) {
				cousinFormat = theSavedCookieSettings.cousinFormat;
				document.getElementById("cousinFormat" + cousinFormat).checked = true;
			}
			if (theSavedCookieSettings && (theSavedCookieSettings.showRelConnLink || theSavedCookieSettings.showRelConnLink === false)  ) {
				showRelConnLink = theSavedCookieSettings.showRelConnLink;
				document.getElementById("showRelConnLink").checked = showRelConnLink;
			}
			if (theSavedCookieSettings && (theSavedCookieSettings.showGEDcomCompareLink || theSavedCookieSettings.showGEDcomCompareLink === false)  ) {
				showGEDcomCompareLink = theSavedCookieSettings.showGEDcomCompareLink;
				document.getElementById("showGEDcomCompareLink").checked = showGEDcomCompareLink;
			}
			if (theSavedCookieSettings && (theSavedCookieSettings.showHaplogroupLink || theSavedCookieSettings.showHaplogroupLink === false)  ) {
				showHaplogroupLink = theSavedCookieSettings.showHaplogroupLink;
				document.getElementById("showHaplogroupLink").checked = showHaplogroupLink;
			}

			wikitree.init({});
			// alert("Init done");
			// wikitree.session.checkLogin({})

			// Call the LOGIN function from the WikiTreeAuthGPC.js file
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
						<?php 
								
							if ($_SESSION['id'] > '') {
								// Fill in form with username to make it quick and easy to generate your own surnames list

								echo "document.getElementById('TTaker1').value = '" . $_SESSION['id'] . "';";
								

								echo "\ndocument.getElementById('AllTheSteps').style.display = 'block';" ;
							} else   {
								// Fill in form with username to make it quick and easy to generate your own surnames list
								echo "\ndocument.getElementById('AllTheSteps').style.display = 'block';" ;
								echo "document.getElementById('TTaker1').value = wikitree.session.user_name;";
								
								echo "document.getElementById('TTakerA').value = wikitree.session.user_name;";
							}  

						?>	

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

						document.getElementById('AllTheSteps').style.display = 'none';
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
						if (queryParams.has('authcode')) { 
							var authcode = queryParams.get('authcode');
							wikitree.session.clientLogin( {'authcode': authcode}  )
								.then(function(data) {
									if (wikitree.session.loggedIn) { 
										/* If the auth-code was good, redirect back to ourselves without the authcode in the URL (don't want it bookmarked, etc). */
										// alert("auth code is OK after all ?");
										document.getElementById('AllTheSteps').style.display = 'block';
										document.getElementById('need_login').style.display = 'none';
										document.getElementById('TTaker1').value = wikitree.session.user_name;
										fillInID4TT1spawn();
										document.getElementById('TTakerA').value = wikitree.session.user_name;

										if (id != 'none' ) {
											// changed from DNAconf.php to DNAconf.php --> will need to revert when upgrading.  OK ?
											window.location.assign('https://apps.wikitree.com/apps/clarke11007/DNAconf.php?id=' + id );

										} else {
											window.location.assign('https://apps.wikitree.com/apps/clarke11007/DNAconf.php');
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

		
		function populateAhnenTable(personArray, ID, ahnNum, tbl = 1, thisGender = '') {
			// alert ("populateAhnenTable(personArray,  " + ID + " , " + ahnNum + " , " + tbl + ")");

			// IF the ahnenNumber == 1, that means we're starting over from scratch, so initialize the tbl from the array initially
			if (ahnNum == 1) {
				ahnenIndexArrays[tbl] = [];
			}

			// ADD the ID to the appropriate spot in the table
			ahnenIndexArrays[tbl][ahnNum] = ID;

			// IF that person exists with its own record in the personArray collection ... then we can use recursive logic to build back the Ahnentafel table further			 
			if (personArray[ID]){
				if (personArray[ID].Gender && personArray[ID].Gender == undefined && thisGender > '') {
					personArray[ID].Gender = thisGender;
					// personArray[ID].LastNameAtBirth = "Idunno";
					// alert (personArray[ID].Name + " " + ID + " # " + personArray[ID].Id+ " : now  = " +personArray[ID].Gender + " : " + thisGender);
				}

				// IF we are looking for Y DNA or autosomal DNA (Simple or Triangulation) we will ALWAYS want the FATHER in our AhenTable
				// BUT ... if we are looking for an mtDNA match - then never
				// AND ... if we are looking for an X-Chromosome match, we only want the Father in our AhnenTable IF the child (personArray[ID]) is themselves a daughter who could have inherited X from the father
				

				if (personArray[ID].BioFather) {
					if (theDNAconfType == 'y' || theDNAconfType == 1  || theDNAconfType == 3 ||  theDNAconfType == "Tri" || (theDNAconfType == 'x' && personArray[ID].Gender == "Female") ){
						populateAhnenTable(personArray, personArray[ID].BioFather, 2*ahnNum, tbl, 'Male'); 
					}

				} else 
				if (personArray[ID].Father) {
					// console.log("Status: ", personArray[ID].DataStatus.Father, personArray[ID].DataStatus.Mother);
					if (personArray[ID].DataStatus && personArray[ID].DataStatus.Father && personArray[ID].DataStatus.Father == 5) {
						// can't go any further - this is a NON-BIOLOGICAL PARENT ... cannot use for DNA Confimation (requires biology)
						setNonBioMode(true, ID, personArray[ID].Father );
					} else 
					if (theDNAconfType == 'y' || theDNAconfType == 1  || theDNAconfType == 3 ||  theDNAconfType == "Tri" || (theDNAconfType == 'x' && personArray[ID].Gender == "Female") ){
						populateAhnenTable(personArray, personArray[ID].Father, 2*ahnNum, tbl, 'Male'); 
					}
				}

				// IF we are looking for mtDNA or autosomal DNA (Simple or Triangulation) or an X-Chromosome match we will ALWAYS want the MOTHER in our AhenTable
				// BUT ... if we are looking for a Y DNA match - then never
				if (personArray[ID].BioMother &&  theDNAconfType != 'y') {
					populateAhnenTable(personArray, personArray[ID].BioMother, 2*ahnNum + 1, tbl, 'Female');

				} else if (personArray[ID].Mother &&  theDNAconfType != 'y') {
					// console.log("Status: ", personArray[ID].DataStatus.Father, personArray[ID].DataStatus.Mother);
					if (personArray[ID].DataStatus && personArray[ID].DataStatus.Mother && personArray[ID].DataStatus.Mother == 5) {
						// can't go any further - this is a NON-BIOLOGICAL PARENT ... cannot use for DNA Confimation (requires biology)
						setNonBioMode(true, ID, personArray[ID].Father);
					} else {
						populateAhnenTable(personArray, personArray[ID].Mother, 2*ahnNum + 1, tbl, 'Female');
					}
				}
				
			}

		}

		 

		function resetAhnenIndices() {
			ahnenIndexArray = [];
			ahnenIndexArrays[2] = [];
			ahnenIndexArrays[3] = [];
			setNonBioMode(false);
			nonBioList = "";
		}

		var tmpAhnenTafel = [];
		function buildTempAhnenTafel (currentSet, currID, currAhnNum) {
			// alert ("currID = " + currID);
				tmpAhnenTafel[currAhnNum] = currID;
				if (currentSet[currID] && currentSet[currID].Father) {
					// alert (currentSet[currID].Father);
					buildTempAhnenTafel(currentSet, currentSet[currID].Father, 2*currAhnNum);
				}
				if (currentSet[currID] && currentSet[currID].Mother) {
					buildTempAhnenTafel(currentSet, currentSet[currID].Mother, 1.0 + 2*currAhnNum );
				}
		}


		function initKitNums() {
			kitNums['1-ftdna']  = "";
			kitNums['1-gedmatch']  = "";
			kitNums['2-ftdna']  = "";
			kitNums['2-gedmatch']  = "";
			kitNums['3-ftdna']  = "";
			kitNums['3-gedmatch']  = "";

			kitNums['1-haplo']  = "";
			kitNums['2-haplo']  = "";
			kitNums['3-haplo']  = "";
			kitNums['1-haploM']  = "";
			kitNums['2-haploM']  = "";
			kitNums['3-haploM']  = "";
		}

		function genderCheck() {
			// alert ("Time for a Gender Check");
		}


	
		function updateNextSetOfPeeps() {
			console.log("FUNCTION: updateNextSetOfPeeps");
			nextSetOfPeeps = [];
			for (var i = 0; i < currentSetOfPeeps.length; i++) {
				if (theDNAconfType == 1 || theDNAconfType == 3) {
					if (personArray[ currentSetOfPeeps[i] ].BioFather > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].BioFather);
					} else if (personArray[ currentSetOfPeeps[i] ].Father > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].Father);
					}
					if (personArray[ currentSetOfPeeps[i] ].BioMother > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].BioMother);
					} else if (personArray[ currentSetOfPeeps[i] ].Mother > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].Mother);
					}

				} else if (theDNAconfType ==  'x') {
					if (personArray[ currentSetOfPeeps[i] ].BioMother > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].BioMother);
					} else if (personArray[ currentSetOfPeeps[i] ].Mother > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].Mother);
					}
					if (personArray[ currentSetOfPeeps[i] ].Gender == 'Female' ) {
						if (personArray[ currentSetOfPeeps[i] ].BioFather > 0) {
							nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].BioFather);
						} else if (personArray[ currentSetOfPeeps[i] ].Father > 0) {
							nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].Father);
						}
					}
				} else if (theDNAconfType ==  'mt') {
					if (personArray[ currentSetOfPeeps[i] ].BioMother > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].BioMother);
					} else if (personArray[ currentSetOfPeeps[i] ].Mother > 0) {
						nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].Mother);
					}

				} else if (theDNAconfType ==  'y') {
					if (personArray[ currentSetOfPeeps[i] ].Gender == 'Male' ) {
						if (personArray[ currentSetOfPeeps[i] ].BioFather > 0) {
							nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].BioFather);
						} else if (personArray[ currentSetOfPeeps[i] ].Father > 0) {
							nextSetOfPeeps.push(personArray[ currentSetOfPeeps[i] ].Father);
						}
					}
				}
				
			}

		}

		function updateNextSetOfPeepsPlusSome(currentSet) {
			console.log("FUNCTION: updateNextSetOfPeepsPlusSome");
			nextSetOfPeeps = [];
			negativeTranslationTable = [];


			for (var i = 0; i < currentSet.length; i++) {
				if (currentSet[i].Id  > 0){
					personArray[ currentSet[i].Id ] = currentSet[i] ;
				} else if (currentSet[i].Id  < 0){
					currentNegative -= 1;
					negativeTranslationTable.push ( [ currentSet[i].Id , currentNegative  ]);

					personArray[ currentNegative ] = currentSet[i] ;
				}

				if (currentSet[i].Father < 0 || currentSet[i].Mother < 0) {
					personParentsNegativeTranslation.push ([ currentSet[i].Id  ,currentSet[i].Father, currentSet[i].Mother ]);
				}


				if (theDNAconfType == 1 || theDNAconfType == 3) {
					if (currentSet[i].BioFather > 0) {
						nextSetOfPeeps.push(currentSet[i].BioFather);
					} else if (currentSet[i].Father > 0) {
						nextSetOfPeeps.push(currentSet[i].Father);
					}
					if (currentSet[i].Mother > 0) {
						nextSetOfPeeps.push(currentSet[i].Mother);
					}

				} else if (theDNAconfType ==  'x') {
					if (currentSet[i].BioMother > 0) {
						nextSetOfPeeps.push(currentSet[i].BioMother);
					} else if (currentSet[i].Mother > 0) {
						nextSetOfPeeps.push(currentSet[i].Mother);
					}
					if (currentSet[i].Gender == 'Female' ) {
						if (currentSet[i].BioFather > 0) {
							nextSetOfPeeps.push(currentSet[i].BioFather);
						} else if (currentSet[i].Father > 0) {
							nextSetOfPeeps.push(currentSet[i].Father);
						}
					}
				} else if (theDNAconfType ==  'mt') {
					if (currentSet[i].BioMother > 0) {
						nextSetOfPeeps.push(currentSet[i].BioMother);
					} else if (currentSet[i].Mother > 0) {
						nextSetOfPeeps.push(currentSet[i].Mother);
					}

				} else if (theDNAconfType ==  'y') {
					if (currentSet[i].Gender == 'Male' ) {
						if (currentSet[i].BioFather > 0) {
							nextSetOfPeeps.push(currentSet[i].BioFather);
						} else if (currentSet[i].Father > 0) {
							nextSetOfPeeps.push(currentSet[i].Father);
						}
					}
				}
				
			}

			// alert (negativeTranslationTable);

			if (negativeTranslationTable.length > 0) {
				// alert (personParentsNegativeTranslation);
				for (var i = 0; i < personParentsNegativeTranslation.length; i++) {
					for (var j = 0; j < negativeTranslationTable.length; j++) {
						if (personParentsNegativeTranslation[i][1] == negativeTranslationTable[j][0]) {
							personParentsNegativeTranslation[i][1] = negativeTranslationTable[j][1];
						} else if (personParentsNegativeTranslation[i][2] == negativeTranslationTable[j][0]) {
							personParentsNegativeTranslation[i][2] = negativeTranslationTable[j][1];
						}
					}

				}
			}
				// alert (personParentsNegativeTranslation);

			
		}


		function updateNextSetOfPeepsPlusSome2(currentSet, primeID) {
			console.log("FUNCTION: updateNextSetOfPeepsPlusSome2");
			tmpAhnenTafel = [];
			buildTempAhnenTafel(currentSet, primeID, 1);

			// alert ("There are " + tmpAhnenTafel.length + " entries in the buildTempAhnenTafel");

			nextSetOfPeeps = [];
			nextSetOfPeeps.push (primeID);
			
				
				if (theDNAconfType == 1 || theDNAconfType == 3) {

					for (var j = 1; j < 32; j++) {
						if (tmpAhnenTafel[j] ) {
							nextSetOfPeeps.push(tmpAhnenTafel[j]);
						}
					}
					
					

				} else if (theDNAconfType ==  'x') {
					var mtMaleList = [1,3,6,7,13,14,15,26,27,29,30,31];
					var mtFemaleList = [2,5,10,11,21,22,23];
					for (var k = 0; k < mtMaleList.length; k++) {
						j = mtMaleList[k];
						if (tmpAhnenTafel[j] ) {
							nextSetOfPeeps.push(tmpAhnenTafel[j]);
						}
					}
					if (currentSet[0].Gender == 'Female') {
						for (var k = 0; k < mtFemaleList.length; k++) {
							j = mtFemaleList[k];
							if (tmpAhnenTafel[j] ) {
								nextSetOfPeeps.push(tmpAhnenTafel[j]);
							}
						}	
					}
						


				} else if (theDNAconfType ==  'mt') {
					nextSetOfPeeps.push(primeID);
					for (var k = 1; k <= 5; k++) {
						j = 2**k - 1; 
						if (tmpAhnenTafel[j] ) {
							nextSetOfPeeps.push(tmpAhnenTafel[j]);
						}
					}

				} else if (theDNAconfType ==  'y') {
					for (var k = 0; k < 5; k++) {
						j = 2**k ; 
						personArray[ tmpAhnenTafel[j] ] = currentSet[ tmpAhnenTafel[j] ] ;
						if (tmpAhnenTafel[j] > 0 ) {
							nextSetOfPeeps.push(tmpAhnenTafel[j]);
						}
					}
				}


				
			alert(nextSetOfPeeps);
			
		}

		function verifyCoupleHoodOKforXmatch( anc1, anc2, anc3=0) {
			// alert ("verifyCoupleHoodOKforXmatch : ");
			child1 = Math.floor(anc1/2);
			child2 = Math.floor(anc2/2);
			child3 = Math.floor(anc3/2);
			var Gend1 = personArray[ ahnenIndexArrays[1][child1] ].Gender;
			var Gend2 = personArray[ ahnenIndexArrays[2][child2] ].Gender;
			var Gend3 = '';
			if (child3 > 0) {Gend3 = personArray[ ahnenIndexArrays[3][child3] ].Gender;}
			 
			 if (ahnenIndexArrays[3].length > 0 && anc3 > 0) {
			 			 
			 			// alert ("ANC: " + anc1 + " vs " + anc2 + " vs " + anc3 + "\n" + personArray[ ahnenIndexArrays[1][anc1] ].FirstName + " "  + personArray[ ahnenIndexArrays[2][anc2] ].FirstName + " " + personArray[ ahnenIndexArrays[3][anc3] ].FirstName + "\n"  + "\nKID: " + child1 + " vs " + child2 + " vs " + child3 + "\n" + personArray[ ahnenIndexArrays[1][child1] ].FirstName + " "  + personArray[ ahnenIndexArrays[2][child2] ].FirstName + " " + personArray[ ahnenIndexArrays[3][child3] ].FirstName + "\n" + "\nGEND:" + Gend1 + " vs " + Gend2 + " vs " + Gend3);
			 }
			if (Gend1 == 'Male' || Gend2 == 'Male' || Gend3 == 'Male') {
				// alert ("SHOULD NOT BE A COUPLE FOR THIS X-CHROMOSOME MATCH!!!");
				theAncsAhnNum += 1;
				theAncsAhnNum2 += 1;

				if (anc3>0){
					theAncsAhnNum3 += 1;
				}
				
				isCouple = false;
			}
			// alert ("DONE verifyCoupleHoodOKforXmatch");
		}

		function newWikiTreeIDentered(num) {
			if (document.getElementById('FormDIV1errWikiTreeID' + num).value == "") {
				if (num == 1) {
					document.getElementById('FormDIV1errWikiTreeClose' + num).textContent = "the Closest Ancestor";
				} else if (num == 2) {
					document.getElementById('FormDIV1errWikiTreeClose' + num).textContent = "MRCA";
				} else if (num == "2b") {
					if (document.getElementById('FormDIV1errWikiTreeID' + 2).value == "") {
						document.getElementById('FormDIV1errWikiTreeClose' + 2).textContent = "MRCA";
					} else {
						document.getElementById('FormDIV1errWikiTreeClose' + 2).textContent = document.getElementById('FormDIV1errWikiTreeID' + 2).value;
					}
				}
			} 

			else {

				if (num == 1) {
					document.getElementById('FormDIV1errWikiTreeClose' + num).textContent = document.getElementById('FormDIV1errWikiTreeID' + num).value;
				} else  {
					if (document.getElementById('FormDIV1errWikiTreeID' + "2b").value == "") {
						document.getElementById('FormDIV1errWikiTreeClose' + 2).textContent = document.getElementById('FormDIV1errWikiTreeID' + 2).value;
					} else {
						document.getElementById('FormDIV1errWikiTreeClose' + 2).textContent = document.getElementById('FormDIV1errWikiTreeID' + 2).value + " & " + document.getElementById('FormDIV1errWikiTreeID' + "2b").value;
					}
				}
			
			}

		}

		function theIDbIsINtheArray(AhNum, thisID, num ) {
			for (var i = 0; i < personArray.length; i++) {
				if(personArray[i] && personArray[i].Name && personArray[i].Name > "" ) {
					if (personArray[i].Name == thisID) {
						if ( num == 1) {
							return (Math.abs(ahnenIndexArray.indexOf( personArray[i].Id )  - AhNum) == 1);
						} else if ( num == 2) {
							console.log("isCouple (num=2) theIDbIsINtheArray : " , ahnenIndexArray.indexOf( ahnenIndexArrays[3][AhNum]  ), ahnenIndexArray.indexOf( personArray[i].Id ), 
								AhNum, ahnenIndexArrays[3].indexOf( personArray[i].Id ) );
							return (Math.abs(ahnenIndexArrays[2].indexOf( personArray[i].Id )  - AhNum) == 1);
							// return (ahnenIndexArrays[2].indexOf( personArray[i].Id ) > -1 );
						} else if ( num == 3) {

							console.log("isCouple (num=3) theIDbIsINtheArray : " , ahnenIndexArray.indexOf( ahnenIndexArrays[3][AhNum]  ), ahnenIndexArray.indexOf( personArray[i].Id ), 
								AhNum, ahnenIndexArrays[3].indexOf( personArray[i].Id ) );
							return (Math.abs(ahnenIndexArrays[3].indexOf( personArray[i].Id )  - AhNum) == 1);
							// return (ahnenIndexArrays[3].indexOf( personArray[i].Id ) > -1 );
						}
						
					}
				}
			}
			return false;
		}


		function getIndexMatchesOfAncestorWithID( ancID, arrayNum) {
			let matchesArray = [];
			for (var i = 0; i < ahnenIndexArrays[arrayNum].length; i++) {
				if (ahnenIndexArrays[arrayNum][i] == ancID) {
					matchesArray.push(i);
				}

			}
			return matchesArray;
		}
		

		function getUniqueSpouseIDsForAncestorWithID(matchesArray, isHusband = true) {
			// input is the indexMatches from array 1
			let uniqueArray = [];
			let spouseDiff = 1; // if husband, then going to go PLUS 1 to get to Wife's Ahnentafel #
			if (isHusband == false) {
				spouseDiff = -1; // if wife, then go MINUS 1 to get to Husband's Ahnentafel #
			}
			for (var i = 0; i < matchesArray.length; i++) {
				let matchAhnenNum = matchesArray[i];
				if (ahnenIndexArrays[1][matchAhnenNum + spouseDiff] && ahnenIndexArrays[1][matchAhnenNum + spouseDiff] > 0 && uniqueArray.indexOf(ahnenIndexArrays[1][matchAhnenNum + spouseDiff]) == -1 ) {
					uniqueArray.push(ahnenIndexArrays[1][matchAhnenNum + spouseDiff]);
				}

			}
			return uniqueArray;

		}

		function commonAncestorDetailsContainsThisCouple(thisSpouseID1, thisSpouseID2) {
			for (var i = 0; i < commonAncestorDetails.length; i++) {
				let theseDetails = commonAncestorDetails[i];
				if (theseDetails.isCouple == true && theseDetails.Id == thisSpouseID1 &&  theseDetails.Id2 == thisSpouseID2) {
					return true;
				}
			}
			return false;
		}


		function pickAnotherPathToCommonAncestorFromDetails(whichBranchNum) {
			let whichCA = document.getElementById("AnotherCommonAncestorFromDetailsPicker").value;
			let thisCommonAncDetails = commonAncestorDetails[whichCA];
			theAncsAhnNum = thisCommonAncDetails.Ahnens[1][0];
			theAncsAhnNum2 = thisCommonAncDetails.Ahnens[2][0];
			theAncsAhnNum3 = thisCommonAncDetails.Ahnens[3][0];

			if (document.getElementById("WhichPathToCommonAncestorFromDetailsPicker1") && document.getElementById("WhichPathToCommonAncestorFromDetailsPicker1").value > 0) {
				theAncsAhnNum = thisCommonAncDetails.Ahnens[1][document.getElementById("WhichPathToCommonAncestorFromDetailsPicker1").value];
			}
			if (document.getElementById("WhichPathToCommonAncestorFromDetailsPicker2") && document.getElementById("WhichPathToCommonAncestorFromDetailsPicker2").value > 0) {
				theAncsAhnNum2 = thisCommonAncDetails.Ahnens[2][document.getElementById("WhichPathToCommonAncestorFromDetailsPicker2").value];
			}
			if (document.getElementById("WhichPathToCommonAncestorFromDetailsPicker3") && document.getElementById("WhichPathToCommonAncestorFromDetailsPicker3").value > 0) {
				theAncsAhnNum3 = thisCommonAncDetails.Ahnens[3][document.getElementById("WhichPathToCommonAncestorFromDetailsPicker3").value];
			}

			let theseAncsAhnNums = [-1, theAncsAhnNum, theAncsAhnNum2, theAncsAhnNum3];
			for (var i = 4; i<= thisCommonAncDetails.Ahnens.length - 1; i++) {
				if (document.getElementById("WhichPathToCommonAncestorFromDetailsPicker" + i) && document.getElementById("WhichPathToCommonAncestorFromDetailsPicker" + i).value > 0) {
					theseAncsAhnNums[i] = thisCommonAncDetails.Ahnens[i][document.getElementById("WhichPathToCommonAncestorFromDetailsPicker" + i).value];
				} else {
					theseAncsAhnNums[i] = thisCommonAncDetails.Ahnens[i][0];
				}

			}

			listOfNames = doMiniTreeTrio();
			let betaMiniTree = newImprovedMultiMiniTreeHTML();
			document.getElementById("MiniTreeInsideDIV4FormDirect").innerHTML = /*listOfNames + */  betaMiniTree;

			prepDIV6Trio();

			let Ender = '<HR><input id=NextButton4tri  class="button" type="submit" value="NEXT" onclick="doneForm4();" ><BR>&nbsp;<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;';

			console.log(postMiniTreeRelationshipReport);
			document.getElementById("postMiniTreeRelationshipReportDIV").innerHTML = generateAllRelationshipsForMultiTesters() + "<br/><br/><SPAN id=Legend4ArrowsDirect></SPAN>";//+ Ender;

			// UPDATE the YAY OR NAY and tthe TESTER TREE FULLNESS sections
			let yayOrNayHTML =  "";// "<TR id=yayOrNayTR>";
			let testerTreeFullnessHTML = "" ;// /*"<TR id=testerTreeFullnessTR>"*/ "<TD align=center colspan=" + thisCommonAncDetails.Ahnens.length + "><HR/><I>Family Tree completeness<BR/>(% of Direct Ancestors @ generation )</I></TD></TR><TR>";
			let numOfYays = 0;

			for (var i = 1; i<= thisCommonAncDetails.Ahnens.length - 1; i++) {
				let thisYayOrNayHTML = "";
				let thisAhncsAhnNum =theseAncsAhnNums[i];
				
				// numPathsHTML += "<TD align=center style='padding:15px;'>";
				// if (i > 1) { numPathsHTML += " | ";} // not needed now that we're using a table to separated paths
				let numPaths = thisCommonAncDetails.Ahnens[i].length;
				
				if (numPaths > 1) { 
					thisYayOrNayHTML += "<font color=red>&cross; : multi-paths</font>";
				}


				if (thisAhncsAhnNum > maxAhnentafelNumber4TriangulationMRCA )
					// || ( i == 2 && theAncsAhnNum2 * (2 ** numGensFromWikiTreeID2toCousin) > maxAhnentafelNumber4TriangulationMRCA )
					{

					if (thisYayOrNayHTML == ""){
						thisYayOrNayHTML += "<font color=red>&cross; : beyond 4th great grand</font>";
					} else {
						thisYayOrNayHTML = thisYayOrNayHTML.replace("</font>", ",<BR/>beyond 4th great grand</font>");
					}

				}
				
				let targetGrandChildID = ahnenIndexArrays[i][ Math.floor(Math.floor(thisAhncsAhnNum / 2) / 2 ) ];
				let numTargetGrandKids = 0;
				for (var j = 1; j<= thisCommonAncDetails.Ahnens.length - 1; j++) {
					let thisGrandChildID = ahnenIndexArrays[j][ Math.floor(Math.floor(theseAncsAhnNums[j] / 2) / 2 ) ];
					if (thisGrandChildID == targetGrandChildID) {
						numTargetGrandKids++;
					}
				}

				if (numTargetGrandKids > 1) {
					if (thisYayOrNayHTML == ""){
						thisYayOrNayHTML += "<font color=red>&cross; : repeat grandchild</font>";
					} else {
						thisYayOrNayHTML = thisYayOrNayHTML.replace("</font>", ",<BR/>repeat grandchild</font>");
					}
				}


				// numPathsHTML += "<BR/>to<BR/><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[i][1]] ) + "</B>";
				// numPathsHTML += "</TD>";

				testerTreeFullnessHTML += "<TD align=center style='padding:15px;'>"  + testerTreeFullnessIndicator(thisCommonAncDetails, i) + "</TD>";

				if (thisYayOrNayHTML == "") {
					// NO objections found - super!
					thisYayOrNayHTML += "<font color=green>&check; OKAY !</font>";
					numOfYays++;
					yayTestersList[i] = true;
				} else {
					yayTestersList[i] = false;
				}
				yayOrNayHTML +=  "<TD align=center>" + thisYayOrNayHTML + "</TD>";

				
			}

			if (numOfYays >= 3) {			
				console.log("YOU CAN DO DNA CONF");
				if (numOfYays < thisCommonAncDetails.Ahnens.length - 1) {
					console.log("BUT YOU ALSO WILL WANT A RESEARCH NOTE TOO");
				}
			}

			if (document.getElementById("NextButton4tri")){
				document.getElementById("NextButton4tri").style.display = "none";
			}

			if (numOfYays >= 3) {
				if (commonAncestorDetails.length == 1 ) {
					goAheadWithDNAConfTriangulationHTML = "You have " + numOfYays + " DNA testers that meet all the criteria.<BR/><B>You can proceed to do a DNA Confirmation via Triangulation.</B>";
					if (document.getElementById("NextButton4tri")){
						document.getElementById("NextButton4tri").style.display = "block";
					} else {
						console.log ("NO NEXT BUTTON 4 TRI FOUND !!!!");
					}
					if (numOfYays < thisCommonAncDetails.Ahnens.length - 1) {
						let numBadTesters = thisCommonAncDetails.Ahnens.length - 1 - numOfYays;
						goAheadWithDNAConfTriangulationHTML += "<BR/><BR/>The other "+( numBadTesters == 1 ? "DNA tester" : numBadTesters + " DNA testers") +" cannot be used in the DNA Confirmation.<BR/>However, you could include them in a === DNA Research === sub-section<BR/>inside a == Research Notes == section.";
					}
				} else {
					goAheadWithDNAConfTriangulationHTML = "<span style='background-color:yellow;'>You cannot proceed with a DNA Confirmation because there are multiple common ancestors.</span>";
					goAheadWithDNAConfTriangulationHTML += "<BR/><BR/>However, you could include these details in a === DNA Research === sub-section<BR/>inside a == Research Notes == section.";
				}

			} else {
				goAheadWithDNAConfTriangulationHTML = "You do not have 3 DNA testers that meet all the criteria.<BR/>Three (3) is the minimum needed to do a DNA Confirmation via Triangulation.";

			}
			document.getElementById("goAheadWithDNAConfTriangulation").innerHTML = goAheadWithDNAConfTriangulationHTML;

			document.getElementById("testerTreeFullnessTR").innerHTML =  testerTreeFullnessHTML;
			document.getElementById("yayOrNayTR").innerHTML = yayOrNayHTML ;
			updateLegend4Arrows();
		}

	


		// Returns an array [x , y] that corresponds to the endpoint of rθ from (centreX,centreY)
		 function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
		    angleInRadians = (angleInDegrees * Math.PI) / 180.0;

		    return [centerX + radius * Math.cos(angleInRadians), centerY + radius * Math.sin(angleInRadians)];
		    // };
		};

		// Returns an array of letters and numbers that correspond to g graphics drawing commands to create an Arc
		function describeArc(x, y, radius, startAngle, endAngle) {
		    start = polarToCartesian(x, y, radius, endAngle);
		    end = polarToCartesian(x, y, radius, startAngle);

		    largeArcFlag = (720 + endAngle - startAngle) % 360 <= 180 ? "0" : "1";
		    // largeArcFlag = 1;

		    d = ["M", start[0], start[1], "A", radius, radius, 0, largeArcFlag, 0, end[0], end[1]];

		    return d;
		};		

		// Returns an attribute object that can be attached to a <path> object to create an Wedge
		// IF the Wedge is being redefined, only the "d" attribute need be updated
		// Note 1: the radius parameter refers to the OUTER edge of the Wedge, furthest from the centre.
		// Note 2:  the wedgeRadius parameter refers to the INNER edge of the Wedge, closest to the centre.
		// Note 3: a Wedge is a 2 dimensional area, with an Arc for its outer edge, 2 partial radii for its side, and a straight line for its inner edge, closest to the centre of the circle at x,y (It is not pointy)
		 function getSVGforWedge ( x, y, radius, wedgeRadius, startAngle, endAngle, id = "arc", clr = "blue", thickness = 2, fillClr = "none") {
		    if (startAngle == endAngle) {
		        startAngle = 0.0001;
		        endAngle = -0.0001;
		    }
		    theSVGpath = "";
		    arc = describeArc(x, y, radius, startAngle, endAngle);

		    for (i = 0; i < arc.length; i++) {
		        theSVGpath += arc[i] + " ";
		    }
		    // theSVGpath .= " L x y ";
		    endWedge = polarToCartesian(x, y, radius - wedgeRadius, endAngle);
		    startWedge = polarToCartesian(x, y, radius - wedgeRadius, startAngle);
		    startPoint = polarToCartesian(x, y, radius, endAngle);
		    theSVGpath += " L " + startWedge[0] + " " + startWedge[1] + " ";
		    theSVGpath += " L " + endWedge[0] + " " + endWedge[1] + " ";
		    theSVGpath += " L " + startPoint[0] + " " + startPoint[1] + " ";

		    let tempAttributes = {
		        "id": id,
		        "fill": fillClr,
		        "stroke": clr,
		        "stroke-width": thickness,
		        "d": theSVGpath,
		    };
		    console.log(tempAttributes);

		    let tempAttributesCode =  "<PATH id='" +  id + "' fill='" +  fillClr + "' stroke='" +  clr + "' stroke-width='" + thickness + "' d='" +  theSVGpath + "' />"; 
		    
		    return tempAttributesCode;
		};


		function testerTreeFullnessIndicator(thisCommonAncDetails , num) {
			let numAncsFound = 0;
			let numExtraAncsFound = 0;
			// let maxAhnNum = -1;
			let theAncsAhnNum = thisCommonAncDetails.Ahnens[num][0];
			let theAncsGen = Math.floor(Math.log2(theAncsAhnNum));
			let maxAhnNum = 2 ** (theAncsGen + 1) ;
			let maxAhnNumExtra = 2 ** (10 + 1) ;
			let firstMiss = maxAhnNum;

			for (var i = 1; i < maxAhnNum; i++) {
				if (ahnenIndexArrays[num][i] && ahnenIndexArrays[num][i] > 0) {
					numAncsFound++;
				} else {
					if (firstMiss == maxAhnNum) {
						firstMiss = i;
					}
				}
			}
					
			if (firstMiss == maxAhnNum) {
				// if firstMiss still == maxAhnNum, that means it was never re-assigned
				// so ... we have to up our game now!
				firstMiss = maxAhnNumExtra ;
			}


			for (var i = maxAhnNum; i < maxAhnNumExtra; i++) {
				if (ahnenIndexArrays[num][i] && ahnenIndexArrays[num][i] > 0) {
					numExtraAncsFound++;
				} else {
					if (firstMiss == maxAhnNumExtra) {
						firstMiss = i;
					}
				}
			}

			let lastFullGen = Math.floor(Math.log2(firstMiss));
			let MRCAconfidence = Math.floor(100 * numAncsFound / (maxAhnNum - 1) );
			let percent10GenFull = Math.min(100, Math.floor(100 * (numAncsFound + numExtraAncsFound) / (maxAhnNumExtra - 1)));

			theTesterFullnessInfo[num]= {lastFullGen:lastFullGen, MRCAgenCompleteness:MRCAconfidence};

			if (maxAhnNum > maxAhnNumExtra) {
				percent10GenFull = MRCAconfidence;
			}

			// let numAncsPastFound = 0;
			// let numAncsFoundPastMRCA = [];
			// let EndogamyShieldPastMRCA = [];
			// for (var g = 0; g < 3; g++) {
			// 	numAncsPastFound = 0;
			// 	numAncsFoundPastMRCA[g] = 0;
			// 	EndogamyShieldPastMRCA[g] = 0;
			// 	// for (var i = 2** (theAncsGen + g); i < 2** (theAncsGen + g + 1); i++) {
			// 	// 	if (ahnenIndexArrays[num][i] && ahnenIndexArrays[num][i] > 0) {
			// 	// 		numAncsPastFound++;					
			// 	// 	}
			// 	// }
			// 	numAncsFoundPastMRCA[g] = numAncsPastFound;
			// 	console.log("Tester found ",numAncsPastFound);
			// 	EndogamyShieldPastMRCA[g] = Math.floor(100 * numAncsPastFound / (2** (theAncsGen + g) ));
			// }


			console.log("TESTER # " + num + " is " + MRCAconfidence + "% complete, full at generation " + lastFullGen + " of " + theAncsGen + ".",
				numAncsFound + " of " + maxAhnNum + " found // " + numExtraAncsFound + " additional of " + maxAhnNumExtra + " found"
				//,  "Endogamy Shield @  " + EndogamyShieldPastMRCA[0] + "% / " + EndogamyShieldPastMRCA[1] + "% / " + EndogamyShieldPastMRCA[2] + "% ! "
				);


			let summaryStatement =  MRCAconfidence + "% @ gen. of<BR/>Common Anc.<BR/>" ;//+ "Endogamy Shields<BR/>@  " + EndogamyShieldPastMRCA[0] + "% / " + EndogamyShieldPastMRCA[1] + "% / " + EndogamyShieldPastMRCA[2] ;

			let summarySVG = "<SVG width=100 height=100>";
			//getSVGforWedge ( x, y, radius, wedgeRadius, startAngle, endAngle, id = "arc", clr = "blue", thickness = 2, fillClr = "none") {

			
			// Create outer shell, regardless of how much will be filled inside
			summarySVG += "<circle cx=50 cy=50 r=48 fill=white stroke=black stroke-width=1 />";

			// let listOfNums = Object.keys(ahnenIndexArrays[num]);
			// let percent10GenFull = getPercentBelowOrEqual(listOfNums, 2047); 

			if (percent10GenFull == 0) {
				// already got the empty shell, no more needed at this point
			} else if (percent10GenFull == 100) {
				summarySVG += "<circle cx=50 cy=50 r=48 fill=yellow stroke=black stroke-width=1 />";
			} else {
				summarySVG += getSVGforWedge ( 50, 50, 48, 40, 0, percent10GenFull * 3.6,  "w" + num + "e3",  "black", 1,  "yellow");
			}

			summaryStatement += Math.round(percent10GenFull) + "% of 10 generations";//<BR/>(" + listOfNums.length + " / " + (2**11 - 1) + ")";
			// if (EndogamyShieldPastMRCA[2] == 0) {
			// 	// already got the empty shell, no more needed at this point
			// } else if (EndogamyShieldPastMRCA[2] == 100) {
			// 	summarySVG += "<circle cx=50 cy=50 r=48 fill=yellow stroke=black stroke-width=1 />";
			// } else {
			// 	summarySVG += getSVGforWedge ( 50, 50, 48, 40, 0, EndogamyShieldPastMRCA[2] * 3.6,  "w" + num + "e3",  "black", 1,  "yellow");
			// }

			// if (EndogamyShieldPastMRCA[1] == 0) {
			// 	summarySVG += "<circle cx=50 cy=50 r=44 fill=white stroke=black stroke-width=1 />";
			// } else if (EndogamyShieldPastMRCA[1] == 100) {
			// 	summarySVG += "<circle cx=50 cy=50 r=44 fill=yellow stroke=black stroke-width=1 />";
			// } else {
			// 	summarySVG += getSVGforWedge ( 50, 50, 44, 40, 0, EndogamyShieldPastMRCA[1] * 3.6,  "w" + num + "e2",  "black", 1,  "yellow");
			// }

			// if (EndogamyShieldPastMRCA[0] == 0) {
			// 	summarySVG += "<circle cx=50 cy=50 r=40 fill=white stroke=black stroke-width=1 />";
			// } else if (EndogamyShieldPastMRCA[0] == 100) {
			// 	summarySVG += "<circle cx=50 cy=50 r=40 fill=yellow stroke=black stroke-width=1 />";
			// } else {
			// 	summarySVG += getSVGforWedge ( 50, 50, 40, 36, 0, EndogamyShieldPastMRCA[0] * 3.6,  "w" + num + "e1",  "black", 1,  "yellow");
			// }

			if (MRCAconfidence == 0) {
				summarySVG += "<circle cx=50 cy=50 r=36 fill=white stroke=blue stroke-width=2 />";
			} else if (MRCAconfidence == 100) {
				summarySVG += "<circle cx=50 cy=50 r=36 fill=lime stroke=blue stroke-width=2 />";
			} else {
				summarySVG += getSVGforWedge ( 50, 50, 36, 36, -90, MRCAconfidence * 3.6 - 90,  "w" + num + "mrca",  "blue", 2,  "lime");
			}


			
			
			

			summarySVG += "</SVG><BR/>";

			return summarySVG + summaryStatement;

		}

		function getPercentBelowOrEqual(theList, theMax) {
			let thePercent = -1; // 100 * (Math.min(1, 1 * (theList.length / (2**11 - 1) ) ));

			let theStartingIndex = theList.length;

			if (theList.length > theMax) {
				theStartingIndex = theMax + 1;
			}

			for (var i = theStartingIndex - 1; i >= 0; i--) {
				if (theList[i] < theMax) {
					thePercent = 100 * (Math.min(1, 1 * (i / (theMax) ) ));
					console.log("getPercentBelowOrEqual",{theStartingIndex}, {i},{thePercent},{theMax}, theList);
					break;
				}
			}
			
			return thePercent ; 
		}


		function pickAnotherCommonAncestorFromDetails() {
			let whichCA = document.getElementById("AnotherCommonAncestorFromDetailsPicker").value;
			if (!whichCA || whichCA == "") {
				whichCA = 0;
			}
			let thisCommonAncDetails = commonAncestorDetails[whichCA];
			theAncsAhnNum = thisCommonAncDetails.Ahnens[1][0];
			theAncsAhnNum2 = thisCommonAncDetails.Ahnens[2][0];
			theAncsAhnNum3 = thisCommonAncDetails.Ahnens[3][0];

			listOfNames = doMiniTreeTrio();

			let numPathsHTML = "<table border=0 align=center style='background-color:white;'><TR>";
			let yayOrNayHTML = "<TR id=yayOrNayTR>";
			let testerTreeFullnessHTML = "<TR><TD align=center colspan=" + thisCommonAncDetails.Ahnens.length + "><HR/><span id=goAheadWithDNAConfTriangulation></span><HR/><U>Family Tree completeness</U><I><BR/><span style='background-color:lime;'>&nbsp;%&nbsp;</span> of Direct Ancestors @ generation of Common Ancestor"+ (isCouple?"s":"") +
				"<BR/><span style='background-color:yellow;'>&nbsp;%&nbsp;</span> of Direct Ancestors up to 10 generations from DNA tester</I></TD></TR><TR id=testerTreeFullnessTR>";
			let numOfYays = 0;

			for (var i = 1; i<= thisCommonAncDetails.Ahnens.length - 1; i++) {
				let thisYayOrNayHTML = "";
				numPathsHTML += "<TD align=center style='padding:15px;'>";
				// if (i > 1) { numPathsHTML += " | ";} // not needed now that we're using a table to separated paths
				let numPaths = thisCommonAncDetails.Ahnens[i].length;
				
				if (numPaths > 1) { 
					let thisPathPickerHTML = "<select id=WhichPathToCommonAncestorFromDetailsPicker"+i+" style='padding:5px;' onchange=pickAnotherPathToCommonAncestorFromDetails();>";
					for (var p = 0; p < numPaths; p++) {
						thisPathPickerHTML += "<option value=" + p + " >Path " + (p+1) + " of " + numPaths + "</option>";
					}
					thisPathPickerHTML += "</select>";
					numPathsHTML += thisPathPickerHTML;
					thisYayOrNayHTML += "<font color=red>&cross; : multi-paths</font>";
				} else {
					numPathsHTML += numPaths + " path";
				}


				if ((i == 1 && theAncsAhnNum > maxAhnentafelNumber4TriangulationMRCA )
					|| ( i == 2 && theAncsAhnNum2 * (2 ** numGensFromWikiTreeID2toCousin) > maxAhnentafelNumber4TriangulationMRCA )
					|| ( i == 3 && theAncsAhnNum3 * (2 ** numGensFromWikiTreeID3toCousin) > maxAhnentafelNumber4TriangulationMRCA )
					|| ( i > 3 && thisCommonAncDetails.Ahnens[i][0] * (2 ** 0) > maxAhnentafelNumber4TriangulationMRCA ) ) {

					if (thisYayOrNayHTML == ""){
						thisYayOrNayHTML += "<font color=red>&cross; : beyond 4th great grand</font>";
					} else {
						thisYayOrNayHTML = thisYayOrNayHTML.replace("</font>", ",<BR/>beyond 4th great grand</font>");
					}

				}
				
				let targetGrandChildID = ahnenIndexArrays[i][ Math.floor(Math.floor(thisCommonAncDetails.Ahnens[i][0] / 2) / 2 ) ];
				let numTargetGrandKids = 0;
				for (var j = 1; j<= thisCommonAncDetails.Ahnens.length - 1; j++) {
					let thisGrandChildID = ahnenIndexArrays[j][ Math.floor(Math.floor(thisCommonAncDetails.Ahnens[j][0] / 2) / 2 ) ];
					if (thisGrandChildID == targetGrandChildID) {
						numTargetGrandKids++;
					}
				}

				if (numTargetGrandKids > 1) {
					if (thisYayOrNayHTML == ""){
						thisYayOrNayHTML += "<font color=red>&cross; : repeat grandchild</font>";
					} else {
						thisYayOrNayHTML = thisYayOrNayHTML.replace("</font>", ",<BR/>repeat grandchild</font>");
					}
				}


				numPathsHTML += "<BR/>to<BR/><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[i][1]] ) + "</B>";
				numPathsHTML += "</TD>";

				testerTreeFullnessHTML += "<TD align=center style='padding:15px;'>"  + testerTreeFullnessIndicator(thisCommonAncDetails, i) + "</TD>";

				if (thisYayOrNayHTML == "") {
					// NO objections found - super!
					thisYayOrNayHTML += "<font color=green>&check; OK !</font>";
					numOfYays++;
					yayTestersList[i] = true;
				} else {
					yayTestersList[i] = false;
				}
				yayOrNayHTML +=  "<TD align=center>" + thisYayOrNayHTML + "</TD>";

				
			}
			testerTreeFullnessHTML += "</TR>";
			yayOrNayHTML += "</TR>";
			numPathsHTML += "</TR>" + yayOrNayHTML +  testerTreeFullnessHTML + "</TABLE>";

			document.getElementById("NumPathsPerBranchOfMiniTreeInsideDIV4FormDirect").innerHTML = numPathsHTML;
			if (document.getElementById("NextButton4tri")){
				document.getElementById("NextButton4tri").style.display = "none";
			}

			let goAheadWithDNAConfTriangulationHTML = "";
			if (numOfYays >= 3) {
				if (commonAncestorDetails.length == 1 ) {
					goAheadWithDNAConfTriangulationHTML = "You have " + numOfYays + " DNA testers that meet all the criteria.<BR/><B>You can proceed to do a DNA Confirmation via Triangulation.</B>";

					if (document.getElementById("NextButton4tri")){
						document.getElementById("NextButton4tri").style.display = "block";
					} else {
						console.log ("NO NEXT BUTTON 4 TRI FOUND !!!!");
					}

					if (numOfYays < thisCommonAncDetails.Ahnens.length - 1) {
						let numBadTesters = thisCommonAncDetails.Ahnens.length - 1 - numOfYays;
						goAheadWithDNAConfTriangulationHTML += "<BR/><BR/>The other "+( numBadTesters == 1 ? "DNA tester" : numBadTesters + " DNA testers") +" cannot be used in the DNA Confirmation.<BR/>However, you could include them in a === DNA Research === sub-section<BR/>inside a == Research Notes == section.";
					}
				} else {
					goAheadWithDNAConfTriangulationHTML = "<span style='background-color:yellow;'>You cannot proceed with a DNA Confirmation because there are multiple common ancestors.</span>";
					goAheadWithDNAConfTriangulationHTML += "<BR/><BR/>However, you could include these details in a === DNA Research === sub-section<BR/>inside a == Research Notes == section.";
				}

			} else {
				goAheadWithDNAConfTriangulationHTML = "You do not have 3 DNA testers that meet all the criteria.<BR/>Three (3) is the minimum needed to do a DNA Confirmation via Triangulation.";

			}
			document.getElementById("goAheadWithDNAConfTriangulation").innerHTML = goAheadWithDNAConfTriangulationHTML;

			let betaMiniTree = newImprovedMultiMiniTreeHTML();
			document.getElementById("MiniTreeInsideDIV4FormDirect").innerHTML = /*listOfNames +*/ betaMiniTree;

			prepDIV6Trio();

			console.log(postMiniTreeRelationshipReport);
			// document.getElementById("postMiniTreeRelationshipReportDIV").innerHTML = postMiniTreeRelationshipReport;
			document.getElementById("postMiniTreeRelationshipReportDIV").innerHTML = generateAllRelationshipsForMultiTesters() + "<br/><br/><SPAN id=Legend4ArrowsDirect></SPAN>";

			updateLegend4Arrows();
		}

		function nullOutIndexMatchesForTesterStartingAt( indx, whichArray) {
			return;
			console.log("nullOutIndexMatchesForTesterStartingAt(", {indx}, {whichArray}, ahnenIndexArrays[whichArray].length , ")")

			let currIndex = 2 * indx;

			if (ahnenIndexArrays[whichArray].length > currIndex){ahnenIndexArrays[whichArray][currIndex] = -1;}
			if (ahnenIndexArrays[whichArray].length > currIndex + 1){ahnenIndexArrays[whichArray][currIndex + 1] = -1;}

			if (2 * currIndex  < ahnenIndexArrays[whichArray].length ) {
				nullOutIndexMatchesForTesterStartingAt( currIndex, whichArray);
				nullOutIndexMatchesForTesterStartingAt( currIndex + 1, whichArray);
			}


			// }
		}

		// isSamePersonAtNminus1 - will return TRUE iff there is only 1 path to each test taker, and they all start to go through the same person
		// .... this scenario happens when you have an MRCA before the end of the indexArray (e.g at generation 4), and then at generation 5, their parents are also flagged as Common Ancestors (just not Most RECENT ones - so the non-recent ones which are redundant is what we're getting rid of here)
		function isSamePersonAtNminus1(theDetails) {
			// NOT going to give MULTIPLE PATHS peeps a pass ... need to check EACH path, and if ALL paths always start with the same Person, then YOU'RE OUTTA HERE !!!

			// if there are multiple paths to MRCA from Test Taker # 1, then, FALSE
			// if (theDetails.Ahnens[1].length > 1) {
			// 	return false;
			// }

			// return false;  // WHILE
			
			// THIS is the WikiTree ID # of the Child of the MRCA (from Test Taker # 1) that we will be using to compare everyone else to
			let currChildIDnum = ahnenIndexArrays[1][ Math.floor(theDetails.Ahnens[1][0] / 2) ];

			// IF there is ANY PATH that starts with a DIFFERENT ID than this, then, we must KEEP this MRCA in the bundle (and return FALSE)

			for (var i = 1; i < theDetails.Ahnens.length; i++) { // i = testerNum, go through this loop for each DNA Test Taker (bail out early if we fail and return false)
				for (var j = 0; j < theDetails.Ahnens[i].length ; j++) { // j = path # , since some testers have multiple paths to get to the Common Ancestor - need to go through each one
					let nextChildIDnum = ahnenIndexArrays[i][ Math.floor(theDetails.Ahnens[i][j] / 2) ];
					if (currChildIDnum != nextChildIDnum) {
						// FAIL if child of MRCA for Test Taker i is NOT the same as for Test Taker # 1's child of the MRCA
						return false;
					}
				}
			}

			// WELL ... if none of those checks flagged this as a false , then I guess it's TRUE - that we have a same person as the child of the MRCA for all Test Takers
			return true;

		}

		// isSamePersonAtNminusN - will return TRUE iff there is only 1 path to each test taker, and they all start to go through the same person at ANY point between the MRCA and Testers
		// .... this scenario happens when you have a Common Ancestor in the middle of the path to a Common Ancestor further out
		// .... visually, the MiniTree will look like an hour glass 
		// .....       - starting wide at the bottom - narrowing into a single Common Ancestor - then widening out again to muliple paths, then back to Top Ancestor(s)
	
		function isSamePersonAtNminusN(theDetails) {
			console.log("isSamePersonAtNminusN");
			// (A) FOR each Test Taker - create a list of BottleNeck IDs - those are IDs that exist in ALL paths from the Top Common Ancestor to the Test Taker.
			// ...	if the Test Taker only has one path, then that will be everyone in the path except the top dog
			// ...	if the Test Taker has more than one path, it is only those IDs that are in all paths

			// (B) THEN ... if there are any TestTakers with an EMPTY BottleNeck list - that implies they have paths with totally unique people - so - FALSE

			// (C) BUT ... if all TestTakers have some BottleNecks - then - we need to see if the SAME BottleNeck is in each list

			// (D) AND ... we return TRUE or FALSE depending on whether a Same BottleNeck is found.


			
			// (A) Create list of BottleNeck IDs (unique bottlenecks per test taker)

			var BottleNeckLists = [ [] , [] , [] , [] , [] , [] , [] , [] , [] , [] , [] , [] , [] , [] , [] , [] ];

			for (var i = 1; i < theDetails.Ahnens.length; i++) {  	// theDetails.Ahnens is the array of arrays of Ahnentafel #s for Top Common Ancestor
																	// i = testerNum, go through this loop for each DNA Test Taker 
				console.log("isSamePersonAtNminusN", {BottleNeckLists});
				for (var j = 0; j < theDetails.Ahnens[i].length ; j++) { // j = path # , since some testers have multiple paths to get to the Common Ancestor - need to go through each one

					if (j == 0) { // first time around, we put all of the IDS into the list, from Top Common Ancestor down to this DNA Test Taker ...
						// copy all of IDs into BottleNeckLists
						BottleNeckLists[i] = [] ;
						let tmpAhn = Math.floor(theDetails.Ahnens[i][0] / 2); // find the child of the Top Common Ancestor, 
						console.log("isSamePersonAtNminusN", i , j , {BottleNeckLists});
						console.log("isSamePersonAtNminusN", i , j , BottleNeckLists[i]);
						console.log("isSamePersonAtNminusN", i , j , BottleNeckLists[i].length);
						console.log("isSamePersonAtNminusN - ahnenIndexArrays", i , ahnenIndexArrays[i], {tmpAhn});

						while (tmpAhn > 1) {
							console.log({tmpAhn});
							BottleNeckLists[i].push(ahnenIndexArrays[i][tmpAhn]); // and add them to start off the BottleNeck List
							tmpAhn = Math.floor( tmpAhn / 2); // find the child of the person just added ...
						} // rinse, lather, repeat, until down to Ahnentafel # 1 - the Test Taker themselves

					} else {
						// if there are MULTIPLE paths - then - for this path # J (where J = 1..2..3.. N paths)
						//  .. we only KEEP those that appear in current BottleNeckLists[i]
						let tmpJointBottleNeckList = [];

						let tmpAhn = Math.floor(theDetails.Ahnens[i][j] / 2); // find the child of the Top Common Ancestor, 

						while (tmpAhn > 1) {
							let tmpID = ahnenIndexArrays[i][tmpAhn];
							// CHECK to see if this tmpID already exists in the current version of BottleNeckLists[i]
							if (BottleNeckLists[i].indexOf(tmpID) > -1) {
								// yay = it exists = SO = we have a bottleneck in common
								// therefore - let's add this to our subset of joint bottle necks
								tmpJointBottleNeckList.push(tmpID);
							} 
							
							tmpAhn = Math.floor(tmpAhn / 2); // find the child of the person just tested (and potentially added to tmpJointBottleNeckList) ...
						} // rinse, lather, repeat, until down to Ahnentafel # 1 - the Test Taker themselves

						if (tmpJointBottleNeckList.length == 0) {
							// that means we found NO ONE in common between the two paths - THUS - we have a totally unique path from Top Common Ancestor to DNA Test Taker # i
							// so - we can fail and stop mucking about.
							return false
						} else {
							// replace the current BottleNeckLists[i] with the temporary Joint BottleNeck (presumably more refined)
							BottleNeckLists[i] = tmpJointBottleNeckList;

						}


					}

				} // for : j : end the run through multiple paths of test taker # i

			} // for : i : end the run through all test takers

			console.log("isSamePersonAtNminusN", {BottleNeckLists});
			// (B) THEN ... if there are any TestTakers with an EMPTY BottleNeck list --> return FALSE

			//		Presumably, this would have been checked by the logic inside loop (A), but let's do it again, just in case
			for (var i = 1; i < theDetails.Ahnens.length; i++) {
				if (BottleNeckLists[i].length == 0) {
					return false;
				}
			}

			// (C) CHECK to see if we have the same Bottle Neck in all lists

			let allBottleNecksList = BottleNeckLists[1];

			for (var i = 2; i < theDetails.Ahnens.length; i++) { // start at Test Taker # 2, and compare its bottle neck list to that of test taker # 1
				let tmpJointBottleNeckList = [];

				for (var k = 0; k < allBottleNecksList.length; k++) { // go through the current list of BottleNecks in Common to see if they exist in Test Taker # i
					// allBottleNecksList[k]
					if (BottleNeckLists[i].indexOf(allBottleNecksList[k]) > -1) { // yay - this BottleNeck is in common!
						tmpJointBottleNeckList.push(allBottleNecksList[k]);
					}
				}

				// when that is done - let's see - do we still have a list of bottlenecks - or were there none in common ?  If None - then FAIL and return!
				if (tmpJointBottleNeckList.length == 0) {
					return false;
				} else {
					allBottleNecksList = tmpJointBottleNeckList;
				}
				
			}

			// (D) AND ... we return TRUE or FALSE depending on whether a Same BottleNeck is found.
			//		If we've made it this far through the loop, presumably we never failed, so there must be a at least one entry in the Joint Bottle Neck List
			// but .. just to be sure, we'll return the check of whether it's empty or not

			if (allBottleNecksList.length > 0) {
				// TRUE - ie. Same Person at CommonAncestor minus N
				for (var i = 0; i < allBottleNecksList.length; i++) {
					console.log("BOTTLE NECK ANCESTOR: ", personArray[allBottleNecksList[i]] );
				}
				return true;
			} else {
				return false
			}
		}

		function getSpanFrom( theHTML ) {
			let whereColSpan = theHTML.indexOf("colspan");
			if (whereColSpan == -1) {
				return 0;
			} else {
				let howColSpan = theHTML.substr(whereColSpan + 8,1);
				return howColSpan
			}
		}

		function newImprovedMultiMiniTreeHTML() {
			let theHTML = ""; // "still ... under development!<BR/>Test away and let me know what you find!";

			theHTML += "<br/><br/><TABLE id=NewImprovedMiniTreeTABLE border=0 style='background-color:aliceblue;'>";

			let numTesters = commonAncestorDetails[0].Ahnens.length - 1;
			console.log("There are: ", {numTesters});
			let whichCA = 0;
			if (document.getElementById("AnotherCommonAncestorFromDetailsPicker") && document.getElementById("AnotherCommonAncestorFromDetailsPicker").value) {
				whichCA = document.getElementById("AnotherCommonAncestorFromDetailsPicker").value;
			}

			let thisCommonAncDetails = commonAncestorDetails[whichCA];
			let currAhnNums = []; // storing the current ahnentafel #s we'r working on
			let prevAhnNums = []; // storing their parents (previous rows #s)

			let sortingStrings = [];

			let columnsAncestorTable = {}; // object to store wikiTreeID#s and currColumn #s

			let DNAtesttakerSpan = "<BR/><SPAN style='background-color:yellow;'>DNA test taker</SPAN><br/>&nbsp;";
			
			// BEFORE we begin - we need to SORT the columns by Children/ Grandchildren/ GreatGrandchildren, sorted in that order by birth
			// NOTE:  This means that Tester #1 will not always be at the bottom of the 1st column - but - will ensure an organized mini-tree!
			for (var i = 1; i <= numTesters; i++) {
				if (document.getElementById("WhichPathToCommonAncestorFromDetailsPicker" + i) && document.getElementById("WhichPathToCommonAncestorFromDetailsPicker" + i).value > 0) {
					theAncsAhnNums[i] = thisCommonAncDetails.Ahnens[i][document.getElementById("WhichPathToCommonAncestorFromDetailsPicker" + i).value];

				} else {
					theAncsAhnNums[i] = thisCommonAncDetails.Ahnens[i][0];
				}
				currAhnNums[i] = Math.floor(theAncsAhnNums[i] / 2);
				prevAhnNums[i] = theAncsAhnNums[i] ;
				console.log("Sorting String from Ancestor #", prevAhnNums[i]," to child #", currAhnNums[i]);
				if (personArray[ahnenIndexArrays[i][currAhnNums[i]]] && !personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate) {
					personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate = "0000-00-00";
				}
				if (currAhnNums[i] > 0 && personArray[ahnenIndexArrays[i][currAhnNums[i]]]) {
					sortingStrings[i] = personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate + ":" + personArray[ahnenIndexArrays[i][currAhnNums[i]]].Id + "";
				}

				let tmpAhnNum = Math.floor(currAhnNums[i]/2);
				for (var j = 1; j <= 7 && tmpAhnNum > 1; j++) {
					if (tmpAhnNum >= 1) {
						if (personArray[ahnenIndexArrays[i][tmpAhnNum]] && !personArray[ahnenIndexArrays[i][tmpAhnNum]].BirthDate) {
							personArray[ahnenIndexArrays[i][tmpAhnNum]].BirthDate = "0000-00-00";
						}
						if (personArray[ahnenIndexArrays[i][tmpAhnNum]] && personArray[ahnenIndexArrays[i][tmpAhnNum]].BirthDate) {
							sortingStrings[i] += "|" + personArray[ahnenIndexArrays[i][tmpAhnNum]].BirthDate + ":" + personArray[ahnenIndexArrays[i][tmpAhnNum]].Id;
						} else {
							sortingStrings[i] += "|-";
						}
					} else {
						sortingStrings[i] += "|-";
					}
					tmpAhnNum = Math.floor(tmpAhnNum/2);
				}

				sortingStrings[i] += "[" + i;
					
			}
			console.log({sortingStrings});
			let sortedStrings = sortingStrings.sort();
			console.log({sortedStrings});

			// OK - so - now we have the strings sorted ... we will use that order from here on out.

			// BUT FIRST - Let's start by building the top cell - the Common Ancestor(s)
			theHTML += "<TR><TD align=center style='background-color:lime;' colspan=" + (2 * numTesters  - 1) + ">"+
			"<SPAN style='background-color:yellow;'>COMMON ANCESTOR" + (thisCommonAncDetails.isCouple == true ? "S":"") + "</SPAN><BR/>"
			+"<B>" ;
			let theMRCAname = theNextBestFirstName( personArray[thisCommonAncDetails.Id]) + " " + theBestLastName( personArray[thisCommonAncDetails.Id]);
			if (thisCommonAncDetails.isCouple == true && thisCommonAncDetails.Id2 > 0) {
				theMRCAname += "<br/>&<br/>" +  theNextBestFirstName( personArray[thisCommonAncDetails.Id2]) + " " + theBestLastName( personArray[thisCommonAncDetails.Id2]) + " ";
			}
			theHTML += theMRCAname + "</B></TD></TR>";
				

			// AND .... let's add the MRCA's relationship to the Testers

			theHTML += "<TR><TD align=center style='background-color:aliceblue;' colspan=" + (2 * numTesters  - 1) + ">";
			for (var i = 1; i <= numTesters; i++) {
				theHTML += "" + theNextBestFirstName( personArray[ahnenIndexArrays[i][1]]) + " " + theBestLastName( personArray[ahnenIndexArrays[i][1]]) + "'s "; 
				let thisRel =  1.0 + Math.floor(Math.log2(theAncsAhnNums[i]));	
				theHTML += "<font color=blue>" + howGreatIsThisOrNot(thisRel) + (thisCommonAncDetails.isCouple == true ? "parents" : (theAncsAhnNums[i] % 2 == 0 ? "father":"mother" )) + "</font><br/>";
			}
			theHTML +=  "</TD></TR>";


			// OK ... now the routine for the FIRST Row (and all subsequent rows)

			// WE are going to use this routine over and over again, until we get to the point where there are no people left to make a miniTree out of
			let allAreNotONE = true;
			let numTimesThroughWhileLoop = 0;
			while (allAreNotONE == true  && numTimesThroughWhileLoop < 15) {
				numTimesThroughWhileLoop++; // added this looop counter during debugging to catch when I inadvertently sent myself into an endless looop ... PROBABLY not needed ...

				// let's make this while loop work for the opportunity to repeat itself ...
				allAreNotONE = false; // (refers to whether all the columns have been reduced down to Ahnentafel number 1, meaning the primary, the test taker at the bottom)
				// as long as there is at least one column that needs to continue adding names, we keep going.

				// There is a ROW for the Ancestors (PersonRow variable), and a ROW for the Arrows (pointing down from one row to the next, parent to child)
				// They both get started with an open <TR> ... and then get built bit by bit ...
				let thePersonRowHTML = "<TR>";
				let theArrowsRowHTML = "<TR>";


				// As you go across the row, we are dealing with the children / grandchildren / greatgrandchildren ... etc... of the MRCA 
				// At the TOP of the miniTree, some of those may be repeats (e.g. 3 testers who descend from only 2 children (but then two descend from 2 diff. grandkids))
				// --> in THOSE cases, we need to be able to collapse neighbouring columns so we don't have the same child listed twice, but only once, then break out in next row
				// At the BOTTOM of the miniTree, we may be dealing with uneven generations, if one of the Testers is a generation or more away from the MRCA than others

				// SO ... we assemble the pieces going across (one generation of silbings / cousins at a time) in temporary variables,
				// AND only at the END of the row do we actually add the pieces to theHTML variable

				// AS we go across the columns, we are constantly comparing the current column's person to the previous column's person
				let prevPersonInRowID = -911; // store the WikiTree ID # of the previous column's person - use -911 at first, since no one there.
				let prevParentOfPersonInRowID = -911; // store the WikiTree ID # of the previous column's person's parent - use -911 at first, since no one there.
				let currParentOfPersonInRowID = -911; // store the WikiTree ID # of the current column's person's parent - use -911 at first, since no one there.

				// 3 main components for each person in the miniTree ...
				// --> the buffer that comes BEFORE their name (if any)  - prevPersonsBeforeBuffer - is non-trivial if we have collapsed columns (same child twice in a row)
				// --> the person's NAME cell (TD html) - prevPersonsTDhtml
				// --> the person's relationship with the person next door  - postPersonsRelationship -  (if any)

				let prevPersonsBeforeBuffer = "";
				let prevPersonsTDhtml = "";
				let postPersonsRelationshipTD = "";
			
				// 3 components are mirrored and use the same logic for the row of arrows
				// --> prevArrowsBeforeBuffer , prevArrowsTDhtml (same location as name below it), postArrowsBufferTD (placement of empty cell same as relationship descriptor)
				let prevArrowsBeforeBuffer = "";
				let prevArrowsTDhtml = "";
				let postArrowsBufferTD = "";

				// keep track of which column a person is placed, so that the arrows can be pointed in the right direction afterwards
				let currColumn = 1;
				let currColumn4Arrows = 0;

				console.log ("* New Arrow ColSpan *");
				for (var ii = 1; ii <= numTesters; ii++) {
					// use the sortingStrings to determine the effective column we work on, at any given time
					let i =  sortingStrings[ii - 1].substr(-1) ; //ii;  // assumes < 10 testers !!!!

					// console.log("sort out ii:" + ii );

					// get the current person's WikiTree ID #
					let currPersonInRowID = -1 * ii;
					let currPerson = null;
					if (currAhnNums[i] >= 1){
						currPerson = personArray[ahnenIndexArrays[i][currAhnNums[i]]];
						if (currPerson){
							currPersonInRowID = personArray[ahnenIndexArrays[i][currAhnNums[i]]].Id;
							if (prevAhnNums[i] && ahnenIndexArrays[i][prevAhnNums[i]] && personArray[ahnenIndexArrays[i][prevAhnNums[i]]] ){
								currParentOfPersonInRowID = personArray[ahnenIndexArrays[i][prevAhnNums[i]]].Id;
							}
						}
					}
					
					if (currPersonInRowID < 1) { // would happen if this tester's column has come to the end of the line (tester) or going below
							// currColumn not used ....
							// if (ii > 1) {
							// 	currColumn+=1; // skip a head
							// }
							thePersonRowHTML += prevPersonsBeforeBuffer + prevPersonsTDhtml;
							theArrowsRowHTML += prevArrowsBeforeBuffer + prevArrowsTDhtml;

							currColumn4Arrows +=  1.0 * getSpanFrom(prevArrowsBeforeBuffer) + 1.0 * getSpanFrom(prevArrowsTDhtml) ;
							console.log("Arrow ColSpan:"  , getSpanFrom(prevArrowsBeforeBuffer), getSpanFrom(prevArrowsTDhtml) , currColumn4Arrows);
							// prevPersonsRelationshipTD = "<TD align=center colspan=1>" + "← siblings →" + "</TD>"; 
							// console.log("sort out ii:" + ii, "**", prevPersonsBeforeBuffer);
							// (2) Reset values, making the Current Name now the Previous, to be used in the next iteration of this loop
							prevPersonsTDhtml = "<TD align=center>" + '&nbsp;' + "</TD>";
							prevArrowsTDhtml  = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>";

							// AND ... NORMALLY would transfer the POST variables to the Pre (Buffer) variables (Relationship & ArrowsBuffer)
							// BUT ... in this case, both of those should be 
							prevPersonsBeforeBuffer = postPersonsRelationshipTD.replace("← siblings →","&nbsp;");
							prevArrowsBeforeBuffer = postArrowsBufferTD;

							// RESET the Post Variables to the standard defaults
							postPersonsRelationshipTD = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>";
							postArrowsBufferTD = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>";


					} else if (ii == 1) {
						// FIRST time through the loop .... need to do some variable setup 
						// currColumn = 1;
						prevPersonsBeforeBuffer = "";
						console.log("personArray # ", ahnenIndexArrays[i][currAhnNums[i]]);
						if (!personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate) {
							personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate = "0000-00-00";
						}
						prevPersonsTDhtml = "<TD  style='background-color:lime;' align=center><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[i][currAhnNums[i]]]) + "<BR/>" + theBestLastName( personArray[ahnenIndexArrays[i][currAhnNums[i]]]) + "</B><br/>b. " + personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate.substring(0,4)  + (currAhnNums[i] == 1 ? DNAtesttakerSpan : "") + "</TD>";
						postPersonsRelationshipTD = "<TD align=center colspan=1><font color=blue><I>" + "← siblings →" + "</I></font></TD>";

						prevArrowsBeforeBuffer = ""; // in most cases, there is no buffer needed before the first name -unless - the first two columns have the same names ... see else ...

						prevArrowsTDhtml  = "<TD align=center colspan=1>" + "&darr;" + "</TD>";
						if (numTimesThroughWhileLoop == 1) {
							let downArrows = "&darr;&darr;"
							if (thisCommonAncDetails.isCouple == true) {
								if (currPerson && currPerson.DataStatus) {						
										if (currPerson.DataStatus.BioFather) {
											downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.BioFather,currPerson); 
										} else {
											downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Father,currPerson); 
										}
										if (currPerson.DataStatus.BioMother) {
											downArrows += colouredArrowInMiniTree("S",currPerson.DataStatus.BioMother,currPerson);
										} else {
											downArrows += colouredArrowInMiniTree("S",currPerson.DataStatus.Mother,currPerson);
										}
								} else {
									downArrows = "&darr;&darr;";
								}
							} else {
								if (currPerson && currPerson.DataStatus) {	
									if (theAncsAhnNums[1] % 2 == 0) {
										if (currPerson.DataStatus.BioFather) {
											downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.BioFather,currPerson) ;
										} else {
											downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Father,currPerson) ;
										}
									} else {
										if (currPerson.DataStatus.BioMother) {
											downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.BioMother,currPerson) ;
										} else {
											downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Mother,currPerson) ;
										}

									}
								} else {
									downArrows = "&darr;";
								}

							}

							prevArrowsTDhtml  = "<TD align=center colspan=1>" + downArrows + "</TD>";
						}

						postArrowsBufferTD = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>";

					} else {
						// NOT the first time through the loop - onto the 2nd or more'th column ... so ... 

						// COMPARE the current to the previous WikiTree ID #
						if (currPersonInRowID == prevPersonInRowID) {
							// SAME ??  - we have a repeater here!!!
							currColumn++; // slide over a column

							if (prevPersonsBeforeBuffer == "") { // only happens if we are at the left edge ... 
								// make the buffer variables non-trivial now !
								prevPersonsBeforeBuffer = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>"; 
								prevArrowsBeforeBuffer = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>"; 

							} else if (prevPersonsBeforeBuffer > "" && prevPersonsBeforeBuffer.indexOf("colspan") > -1) {
								// SO ... there's already something in the before buffer ... 
								// THEN ... we need to increase the column span of the buffer so that it can slide the name over
								let whereColSpan = prevPersonsBeforeBuffer.indexOf("colspan");
								let howColSpan = prevPersonsBeforeBuffer.substr(whereColSpan + 8,1);
								//console.log("Now sorted:", {howColSpan});
								// apply to the person and the arrow variables
								prevPersonsBeforeBuffer = prevPersonsBeforeBuffer.replace("colspan=" + howColSpan, "colspan=" + (1.0 * howColSpan + 1));
								prevArrowsBeforeBuffer = prevArrowsBeforeBuffer.replace("colspan=" + howColSpan, "colspan=" + (1.0 * howColSpan + 1));

							}

							// LIKEWISE ... we need to embiggen the POST variables (for the postRelationship descriptor and the postArrowBuffer)
							let whereColSpan2 = postPersonsRelationshipTD.indexOf("colspan");
							let howColSpan2 = postPersonsRelationshipTD.substr(whereColSpan2 + 8,1);
							//console.log("Now sorted:", {whereColSpan2}, {howColSpan2}, {postPersonsRelationshipTD});
							postPersonsRelationshipTD = postPersonsRelationshipTD.replace("colspan=" + howColSpan2 ,"colspan=" + (1.0 * howColSpan2 + 1));
							postArrowsBufferTD = postArrowsBufferTD.replace("colspan=" + howColSpan2 ,"colspan=" + (1.0 * howColSpan2 + 1));

						} else {
							// NOT THE SAME ... SO ... 
							// (1) Add the PREVIOUS PERSON and ARROW info to the RowHTML variables

							// CHECK to see if the arrow should actually be pointing SouthWest or SouthEast
							console.log("ArrowCheck: " , prevParentOfPersonInRowID, columnsAncestorTable[prevParentOfPersonInRowID] ,  currColumn);
							let thisPrevPerson = personArray[prevPersonInRowID];
							if ( columnsAncestorTable[prevParentOfPersonInRowID] > currColumn) {
								let southWestArrow = "↙";
								if (thisPrevPerson && thisPrevPerson.DataStatus) {	
									if (theAncsAhnNums[i] % 2 == 0) {
										if (thisPrevPerson.DataStatus.BioFather){
											southWestArrow = colouredArrowInMiniTree("SW",thisPrevPerson.DataStatus.BioFather,thisPrevPerson) ;
										} else {
											southWestArrow = colouredArrowInMiniTree("SW",thisPrevPerson.DataStatus.Father,thisPrevPerson) ;
										}
									} else {
										if (thisPrevPerson.DataStatus.BioMother){
											southWestArrow = colouredArrowInMiniTree("SW",thisPrevPerson.DataStatus.BioMother,thisPrevPerson) ;
										} else {
											southWestArrow = colouredArrowInMiniTree("SW",thisPrevPerson.DataStatus.Mother,thisPrevPerson) ;
										}

									}
								}				
								console.log("PREV ARROW before SouthWest" + prevArrowsTDhtml)				;
								prevArrowsTDhtml = prevArrowsTDhtml.replace("&darr;", southWestArrow).replace("↓", southWestArrow);
								console.log("PREV ARROW after SouthWest sub" + prevArrowsTDhtml)				;


							} else if ( columnsAncestorTable[prevParentOfPersonInRowID] < currColumn) {
								let southEastArrow = "↘";
								if (thisPrevPerson && thisPrevPerson.DataStatus) {	
									if (theAncsAhnNums[i] % 2 == 0) {
										if (thisPrevPerson.DataStatus.BioFather){
											southEastArrow = colouredArrowInMiniTree("SE",thisPrevPerson.DataStatus.BioFather,thisPrevPerson) ;
										} else {
											southEastArrow = colouredArrowInMiniTree("SE",thisPrevPerson.DataStatus.Father,thisPrevPerson) ;
										}
									} else {
										if (thisPrevPerson.DataStatus.BioMother){
											southEastArrow = colouredArrowInMiniTree("SE",thisPrevPerson.DataStatus.BioMother,thisPrevPerson) ;
										} else {
											southEastArrow = colouredArrowInMiniTree("SE",thisPrevPerson.DataStatus.Mother,thisPrevPerson) ;
										}

									}
								}	
								console.log("PREV ARROW before SouthEast" + prevArrowsTDhtml)				;
								prevArrowsTDhtml = prevArrowsTDhtml.replace("&darr;",southEastArrow).replace("↓",southEastArrow);								
								console.log("PREV ARROW after SouthEast sub" + prevArrowsTDhtml)				;

							}

							thePersonRowHTML += prevPersonsBeforeBuffer + prevPersonsTDhtml;
							theArrowsRowHTML += prevArrowsBeforeBuffer + prevArrowsTDhtml;

							currColumn4Arrows +=  1.0 * getSpanFrom(prevArrowsBeforeBuffer) + 1.0 * getSpanFrom(prevArrowsTDhtml) ;
							console.log("Arrow ColSpan:"  , getSpanFrom(prevArrowsBeforeBuffer), getSpanFrom(prevArrowsTDhtml) , currColumn4Arrows, "(" + currColumn + ")");

							console.log("AT COLUMN (1) arrow", currColumn, currColumn4Arrows, ":", theNextBestFirstNameLastName( prevPersonInRowID ), prevPersonInRowID , "from " + columnsAncestorTable[prevParentOfPersonInRowID]);
							columnsAncestorTable[prevPersonInRowID] = currColumn4Arrows;
							// prevPersonsRelationshipTD = "<TD align=center colspan=1>" + "← siblings →" + "</TD>"; 
							// console.log("sort out ii:" + ii, "**", prevPersonsBeforeBuffer);
							// (2) Reset values, making the Current Name now the Previous, to be used in the next iteration of this loop
							console.log(personArray[ahnenIndexArrays[i][currAhnNums[i]]]);
							if (!personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate) {
								personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate = "?";
							}
							prevPersonsTDhtml = "<TD  style='background-color:lime;' align=center><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[i][currAhnNums[i]]]) + "<BR/>" +   theBestLastName( personArray[ahnenIndexArrays[i][currAhnNums[i]]])  + "</B><br/>b. " + personArray[ahnenIndexArrays[i][currAhnNums[i]]].BirthDate.substring(0,4)   + (currAhnNums[i] == 1 ? DNAtesttakerSpan : "") + "</TD>";

							let theDownArrow ="&darr;" ;
							if (currPerson && currPerson.DataStatus) {	
								let thisArrowDirection = "S";
								// if ( columnsAncestorTable[prevParentOfPersonInRowID] > currColumn) {
								// 	thisArrowDirection ="SW";
								// } else if ( columnsAncestorTable[prevParentOfPersonInRowID] < currColumn) {
								// 	thisArrowDirection ="SE";
								// }
								if (theAncsAhnNums[i] % 2 == 0) {
									if (currPerson.DataStatus.BioFather) {
									 theDownArrow = colouredArrowInMiniTree(thisArrowDirection,currPerson.DataStatus.BioFather) ;
									} else {
									 theDownArrow = colouredArrowInMiniTree(thisArrowDirection,currPerson.DataStatus.Father) ;
									}
								} else {
									if (currPerson.DataStatus.BioMother) {
									 theDownArrow = colouredArrowInMiniTree(thisArrowDirection,currPerson.DataStatus.BioMother) ;
									} else {
									 theDownArrow = colouredArrowInMiniTree(thisArrowDirection,currPerson.DataStatus.Mother) ;
									}

								}
							// } else {
							// 	if ( columnsAncestorTable[prevParentOfPersonInRowID] > currColumn) {
							// 		prevArrowsTDhtml.replace("&darr;","SWest");
							// 	} else if ( columnsAncestorTable[prevParentOfPersonInRowID] < currColumn) {
							// 		prevArrowsTDhtml.replace("&darr;","SEast");
							// 	}
							}	


							prevArrowsTDhtml  = "<TD align=center colspan=1>" + theDownArrow + "</TD>";
							if (numTimesThroughWhileLoop == 1) {
								let downArrows = "&darr;&darr;"
								if (thisCommonAncDetails.isCouple == true) {
									if (currPerson && currPerson.DataStatus) {	
										if (currPerson.DataStatus.BioFather) {
											 downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.BioFather,currPerson) ;
											} else {
											 downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Father,currPerson) ;
											}
											if (currPerson.DataStatus.BioMother) {
											 downArrows += colouredArrowInMiniTree("S",currPerson.DataStatus.BioMother,currPerson) ;
											} else {
											 downArrows += colouredArrowInMiniTree("S",currPerson.DataStatus.Mother,currPerson) ;
											}

											// downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Father,currPerson)  + colouredArrowInMiniTree("S",currPerson.DataStatus.Mother,currPerson);
									} else {
										downArrows = "&darr;&darr;";
									}
								} else {
									if (currPerson && currPerson.DataStatus) {	
										if (theAncsAhnNums[1] % 2 == 0) {
											if (currPerson.DataStatus.BioFather) {
											 downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.BioFather,currPerson) ;
											} else {
											 downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Father,currPerson) ;
											}
										} else {
											if (currPerson.DataStatus.BioMother) {
											 downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.BioMother,currPerson) ;
											} else {
											 downArrows = colouredArrowInMiniTree("S",currPerson.DataStatus.Mother,currPerson) ;
											}

										}
									} else {
										downArrows = "&darr;";
									}

								}

								prevArrowsTDhtml  = "<TD align=center colspan=1>" + downArrows + "</TD>";
							}

							// AND ... transferring the POST variables to the Pre (Buffer) variables (Relationship & ArrowsBuffer)
							prevPersonsBeforeBuffer = postPersonsRelationshipTD;
							prevArrowsBeforeBuffer = postArrowsBufferTD;

							// RESET the Post Variables to the standard defaults
							postPersonsRelationshipTD = "<TD align=center colspan=1><font color=blue><I>" + "← siblings →" + "</I></font></TD>";
							postArrowsBufferTD = "<TD align=center colspan=1>" + "&nbsp;" + "</TD>";


							let iB4 =  sortingStrings[ii - 2].substr(-1) ; //ii;
							console.log({numTimesThroughWhileLoop}, {iB4}, theAncsAhnNums[iB4] , currAhnNums[iB4], {i} , theAncsAhnNums[i], currAhnNums[i]);
							let tmp1 = theAncsAhnNums[iB4];
							let tmp2 = theAncsAhnNums[i];
							for (var nt = 0; nt < numTimesThroughWhileLoop; nt++) {
								console.log("numTimes Compare ", nt, " : ", ahnenIndexArrays[iB4][tmp1], " v ", ahnenIndexArrays[i][tmp2], ahnenIndexArrays[iB4][tmp1] - ahnenIndexArrays[i][tmp2], Math.round(ahnenIndexArrays[iB4][tmp1] - ahnenIndexArrays[i][tmp2]) == 0);
								if (Math.round(ahnenIndexArrays[iB4][tmp1] - ahnenIndexArrays[i][tmp2]) == 0) {
									// even steven - siblings
								} else {
									let nTH = (numTimesThroughWhileLoop - nt) + "th";
									let nTHarray = ['','1st', '2nd', '3rd'];
									if ((numTimesThroughWhileLoop - nt) < 4) {
										nTH = nTHarray[(numTimesThroughWhileLoop - nt)];
									}
									prevPersonsBeforeBuffer = prevPersonsBeforeBuffer.replace("sibling", nTH + "<br/>cousin");
									break;
								}
								tmp1 = Math.floor(tmp1/2);
								tmp2 = Math.floor(tmp2/2);
							}


							let whereColSpan2 = prevPersonsBeforeBuffer.indexOf("colspan");
							let howColSpan2 = prevPersonsBeforeBuffer.substr(whereColSpan2 + 8,1);
							currColumn += 1.0 + 1.0 * howColSpan2;
							
						}
					}

					// AND ... now remember what the PREVIOUS ID was for the next iteration ...
					prevPersonInRowID = currPersonInRowID;
					prevParentOfPersonInRowID = currParentOfPersonInRowID;
				 	console.log("sort out ii:" + ii, "Af", prevPersonsBeforeBuffer);

				 	// FINALLY ... just before leaving this iteration, let's half this current Ahnentafel Number to be ready for the next name in the column
				 	prevAhnNums[i] = currAhnNums[i] ;
				 	currAhnNums[i] = Math.floor(currAhnNums[i] / 2);
				 	if (currAhnNums[i] >= 1) {
						allAreNotONE = true;
					}

				}


				currColumn4Arrows +=  1.0 * getSpanFrom(prevArrowsBeforeBuffer) + 1.0 * getSpanFrom(prevArrowsTDhtml) ;
				console.log("Arrow ColSpan:"  , getSpanFrom(prevArrowsBeforeBuffer), getSpanFrom(prevArrowsTDhtml) , currColumn4Arrows, "(2) (" + currColumn + ")");

				console.log("AT COLUMN v2 arrow", currColumn, currColumn4Arrows, ":", theNextBestFirstNameLastName(  prevPersonInRowID ) , prevPersonInRowID, "from " + columnsAncestorTable[prevParentOfPersonInRowID]);
				columnsAncestorTable[prevPersonInRowID] = currColumn4Arrows;

				if ( columnsAncestorTable[prevParentOfPersonInRowID] > currColumn4Arrows) {
					prevArrowsTDhtml = prevArrowsTDhtml.replace("&darr;","↙").replace("↓","↙");
				} else if ( columnsAncestorTable[prevParentOfPersonInRowID] < currColumn4Arrows) {
					prevArrowsTDhtml = prevArrowsTDhtml.replace("&darr;","↘").replace("↓","↘");
				} else if ( columnsAncestorTable[prevParentOfPersonInRowID] == currColumn4Arrows) {
					prevArrowsTDhtml = prevArrowsTDhtml.replace("↘","↓").replace("↘","↓");
				} else  {
					prevArrowsTDhtml = prevArrowsTDhtml.replace("↘","↓").replace("↘","↓");
				}

				// AFTER having gone through the loop ... we need to output the final person's info (stuck in the Previous Person/Arrow variables)
				thePersonRowHTML += prevPersonsBeforeBuffer + prevPersonsTDhtml + "</TR>";
				theArrowsRowHTML += prevArrowsBeforeBuffer + prevArrowsTDhtml + "</TR>";


				theHTML += theArrowsRowHTML + thePersonRowHTML;// + "<TR>";

			}

			 

			theHTML += "</TABLE>";
			// theHTML += "</TR></TABLE>";

			theHTML += "<HR/>";

			theHTML += "<H4>Enter a New DNA Test Taker:</H4><DIV align=center><span id=FormDIV1triPNerr style='display:none; background-color:yellow;'></span><input type=text id=NewDNAtestTakerWikiTreeID><br/><button onclick=addNewTestTakerToTreeStart();>Add New DNA Test Taker now</button></DIV>";


			theHTML += "<br/><br/><H4>Remove DNA Test Taker:</H4><DIV align=center><select id=DNAtestTakerNUMtoRemove><option value=0>Pick name ...</option>";

			for (var ii = 2; ii <= numTesters; ii++) {
				theHTML += "<option value=" + ii + ">" + theNextBestFirstName( personArray[ahnenIndexArrays[ii][1]] ) + " "  + theBestLastName( personArray[ahnenIndexArrays[ii][1]] ) + "</option>";
			}
			theHTML += "</select><br/><button onclick=removeTestTakerFromTree();>Remove DNA Test Taker now</button></DIV>";


			theHTML += "<HR/>";

			console.log({columnsAncestorTable});

			return theHTML;
		}

		function consoleCommonAncestorDetails(num = -1) {
			let startI = 0;
			let endI = commonAncestorDetails.length;
			if (num > -1) {
				startI = num;
				endI = num + 1;
			}

			for (var i = startI; i < endI; i++) {
				let theseDetails = [];
				theseDetails = commonAncestorDetails[i];
				console.log("=====================");
				console.log("commonAncestorDetails[ " + i + " ]");
				console.log(theseDetails['Id'],theseDetails['Id2'],theseDetails['isCouple'],theseDetails['Ahnens']);
				console.log( theNextBestFirstNameLastName(theseDetails['Id']) + (theseDetails['isCouple'] == true ? " & " + theNextBestFirstNameLastName(theseDetails['Id2']) : "" ) );
				let numPaths = "";
				for (var j = 1; j < theseDetails.Ahnens.length; j++) {
					if (j > 1) {numPaths += " | ";}
					numPaths += theseDetails.Ahnens[j].length;
					if (theseDetails.Ahnens[j].length > 1) {
						numPaths += " paths";
					}
				}	
				console.log(numPaths);

				for (var j = 1; j < theseDetails.Ahnens.length; j++) {
					console.log("Test Taker # ",j)
					for (var k = 0; k < theseDetails.Ahnens[j].length; k++) {
						let tmpAhnNum = theseDetails.Ahnens[j][k];
						let tmpID = ahnenIndexArrays[j][tmpAhnNum];
						let startAncs = theNextBestFirstNameLastName(tmpID);

						tmpAhnNum = Math.floor(tmpAhnNum / 2);
						tmpID = ahnenIndexArrays[j][tmpAhnNum];
						startAncs += " > " + theNextBestFirstNameLastName(tmpID);

						tmpAhnNum = Math.floor(tmpAhnNum / 2);
						tmpID = ahnenIndexArrays[j][tmpAhnNum];
						startAncs += " > " + theNextBestFirstNameLastName(tmpID);

						console.log( String.fromCharCode(65 + k) + " : " ,  startAncs);
					}	
				}
			}
		}

		function removeTestTakerFromTree() {
			let theDNAtestTakerNUMtoRemove = document.getElementById('DNAtestTakerNUMtoRemove').value;
			console.log({theDNAtestTakerNUMtoRemove});
			ahnenIndexArrays.splice(theDNAtestTakerNUMtoRemove, 1); // remove the redundant match
			console.log({ahnenIndexArrays});
			// reCalculateCommonAncestorDetails();
			reportAllPeeps();

			for (var i = 4; i < ahnenIndexArrays.length; i++) {
				addNewTestTakerToCommonAncestorDetails(i);
			}
			
		}


		function generateAllRelationshipsForMultiTesters() {
			let numTesters = commonAncestorDetails[0].Ahnens.length - 1;
			let fullReport = "";
			for (var i = 1; i < numTesters; i++) {
				for (var j = i + 1; j <= numTesters; j++) {
					fullReport += newAllInOneRelationshipLineForMultiTesters(i,j);
				}
				fullReport += "<br/>";
			}
			return  fullReport;
		}

		function newAllInOneRelationshipLineForMultiTesters(a, b) {

			let tmp1 = theAncsAhnNums[a];
			let tmp2 = theAncsAhnNums[b];
			let numSteps1 = Math.floor(Math.log2(tmp1));			
			let numSteps2 = Math.floor(Math.log2(tmp2));	
			let numTimesThroughWhileLoop = Math.min(numSteps1,numSteps2);
			
			let rel = "siblings";
			for (var nt = 0; nt < numTimesThroughWhileLoop; nt++) {
				console.log("numTimes Compare ", nt, " : ", ahnenIndexArrays[a][tmp1], " v ", ahnenIndexArrays[b][tmp2], ahnenIndexArrays[a][tmp1] - ahnenIndexArrays[b][tmp2], Math.round(ahnenIndexArrays[a][tmp1] - ahnenIndexArrays[b][tmp2]) == 0);
				if (Math.round(ahnenIndexArrays[a][tmp1] - ahnenIndexArrays[b][tmp2]) == 0) {
					// even steven - siblings
				} else {
					let nTH = (numTimesThroughWhileLoop - nt) + "th";
					let nTHarray = ['','1st', '2nd', '3rd'];
					if ((numTimesThroughWhileLoop - nt) < 4) {
						nTH = nTHarray[(numTimesThroughWhileLoop - nt)];
					}
					rel = rel.replace("sibling", nTH + " cousin");

					if (numSteps1 != numSteps2) {
						rel += ", " +  Math.abs(numSteps1 - numSteps2) + "x removed";
					}
					break;
				}
				tmp1 = Math.floor(tmp1/2);
				tmp2 = Math.floor(tmp2/2);
			}

			let reportLine = theNextBestFirstName(personArray[ ahnenIndexArrays[a][1]]) + " " + theBestLastName(personArray[ ahnenIndexArrays[a][1]]) + " and " +  theNextBestFirstName(personArray[ ahnenIndexArrays[b][1]]) + " " + theBestLastName(personArray[ ahnenIndexArrays[b][1]]) + " are <font color=blue>" + rel + "</font>.<br/>";
			return reportLine;

		}

		function addNewAliasToTree(newID) {
			console.log ("function addNewAliasToTree");
			setupAliasRePersonNWikiTreeID("N", newID);
		}

		function addNewTestTakerToTreeStart( ) {
			console.log ("function addNewTestTakerToTreeStart");
			let newID = document.getElementById("NewDNAtestTakerWikiTreeID").value;
			let IDokay = true;
			if (newID.indexOf("-") > -1) {
				let shouldBeDigits = newID.substring(1.0 + newID.indexOf("-"));
				if (shouldBeDigits.length < 1) {
					IDokay = false;
				} else {
					for (var i = 0; i < shouldBeDigits.length; i++) {
						if (shouldBeDigits[i] >= "0" && shouldBeDigits[i] <= "9") {
							// OK
						} else {
							IDokay = false;
							break;
						}
					}
				}

			} else {
				IDokay = false;
			}

			if (IDokay) {
				addNewTestTakerToTree();
			} else {
				addNewAliasToTree(newID);
			}

		}

		function checkIDforNewTestTakerToTree(newID, statusMsg) {
			console.log("STATUS:  checkIDforNewTestTakerToTree",{newID});
			setupWarningRePersonNWikiTreeID("N","tri", newID, statusMsg);
			// FormDIV1triPNerr
		}


		function addNewTestTakerToTree(startAt = 0) {
			let newID = document.getElementById("NewDNAtestTakerWikiTreeID").value;
			console.log("SORTING out NEW: " , newID, "@ " + startAt);
			let newTesterNum = commonAncestorDetails[0].Ahnens.length;

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': newID ,'ancestors':maxNumAncestorsForTriangulation, 'fields': "Id,Name,FirstName,LastNameAtBirth,RealName,BirthDate,DeathDate,LastNameCurrent,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'start':startAt, 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// gg("Success - in  loadTriInfo ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status, data[0].resultByKey[newID].status);
					console.log("data[0]", data[0]);
					// alert(theJason);


					if (data[0] && data[0].resultByKey[newID] && data[0].resultByKey[newID].status && (data[0].resultByKey[newID].status.indexOf( "Invalid") > -1  || data[0].resultByKey[newID].status.indexOf( "Illegal") > -1 )) {

						checkIDforNewTestTakerToTree(newID, data[0].resultByKey[newID].status);

					} else if (data[0]) {
						// alert ("There is data x " + data[0].people.length);	
						let thisNewProfilePersonID = data[0].resultByKey[newID].Id;					
						for (var i in data[0].people) {
							// if ( data[0].people[i].Name == "Leger-743") { alert ("Charles Alert !!!");}
							personArray[ i ] = data[0].people[i];
							// if (personArray[i].Name == theID || i == theID) {
							// 	theProfilePersonID = i;
							// }
						}

						let thisNewProfilePerson = null;
						if (data[0].people[thisNewProfilePersonID]) {
						 	thisNewProfilePerson = data[0].people[thisNewProfilePersonID];
						 	console.log({newID} , {thisNewProfilePersonID},{thisNewProfilePerson});
							if (!thisNewProfilePerson.Gender || thisNewProfilePerson.Gender == undefined) {
								thisNewProfilePerson['Gender'] = "Unknown";
							}
						} else {
							thisNewProfilePerson = personArray[thisNewProfilePersonID];
						}

						
						// alert("Populating ...");
						populateAhnenTable(personArray,  thisNewProfilePersonID , 1, newTesterNum);
						// alert("DONE Populating ...");
					 	document.getElementById('Div2P1a').textContent = theNextBestFirstName( theProfilePerson) ;
						document.getElementById('Div2P1b').textContent = theNextBestFirstName( theProfilePerson) ;
						
						// alert("hide / show forms");
		
						if (data[0].people[thisNewProfilePersonID]) {
								document.getElementById('YourDNA').innerHTML += "<br/>" + getDNAConfirmationAidHTML ( thisNewProfilePerson);


							// if (thisNewProfilePerson.LastNameAtBirth == thisNewProfilePerson.LastNameCurrent) {
							// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + newID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( thisNewProfilePerson) + " " + thisNewProfilePerson.LastNameAtBirth + "</A>";
							// } else {
							// 	document.getElementById('YourDNA').innerHTML += "<br/><A target=_blank href=\"https://www.wikitree.com/treewidget/" + newID + "/899\">" + "DNA Ancestor Confirmation Aid for " + theNextBestFirstName( thisNewProfilePerson) + " (" + thisNewProfilePerson.LastNameAtBirth + ") " + thisNewProfilePerson.LastNameCurrent + "</A>";
							// }
						}
						

						// reportAllPeeps();	
						console.log("DONE ... check out what is sorted now:", ahnenIndexArrays[newTesterNum]);

						if (data[0].status.indexOf("Maximum number of profiles") > -1) {
							// we need to run this routine again to grab some more profiles
							addNewTestTakerToTree(startAt + 1000);
						} else if (ahnenIndexArrays[newTesterNum].length > 1) {
							
							if (doExtendedAncestorSearch4Triangulation == true) {
								loadExtraAncestorsBeforeFinalReportAllPeeps(newTesterNum);
							} else {
								addNewTestTakerToCommonAncestorDetails(newTesterNum);
								loadDNAkitNumbers( newID, newTesterNum);
							}
						}

					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";


		}

		function addNewPlaceholderPersonToPersonArray( initObj = {} ) {
			let thePerson = {};

			currentPrivateIDstarter++;
			thePerson["Id"] = currentPrivateIDstarter;

	        thePerson["Name"] = "Priv8-" + thePerson.Id; // use Priv8 as the "Name" prefix to avoid any conflicts with Private which is used for a few WikiTreer last names
            thePerson["FirstName"] = "Private";
            thePerson["LastNameAtBirth"] = "";
            thePerson["BirthDate"] = "0000-00-00";
            thePerson["DeathDate"] = "0000-00-00";
            thePerson["Mother"] = 0;
            thePerson["Father"] = 0;
            thePerson["DataStatus"] = {};
            thePerson["Gender"] = "Unknown";

            for (var i in initObj) {
            	thePerson[i] = initObj[i];
            }

            personArray[ thePerson.Id ] = thePerson;

            return thePerson.Id;
		}
            

		function addAncestorsToNewTestTakerToTree(newID, newID2, numGensAway , startAt = 0) {
			let newALIAS = document.getElementById("NewDNAtestTakerWikiTreeID").value;
			let newTesterNum = commonAncestorDetails[0].Ahnens.length;
			console.log("SORTING out NEW addAncestorsToNewTestTakerToTree: " , {newALIAS},{newTesterNum} ,{newID}, {newID2}, {numGensAway});

			let newIDs = newID;
			if (newID2 > "") {
				newIDs += "," + newID2;
			}

			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				data: { 'action': 'getPeople', 'appId':'DNAconf' , 'keys': newIDs ,'ancestors':(maxNumAncestorsForTriangulation - 1), 'fields': "Id,Name,FirstName,LastNameAtBirth,RealName,BirthDate,DeathDate,LastNameCurrent,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'start':startAt, 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// gg("Success - in  loadTriInfo ready function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status, data[0].resultByKey[newID]);
					if (newID > "") {
						console.log("STATUS2: " ,  data[0].resultByKey[newID2]);
					}
					console.log("data[0]", data[0]);
					// alert(theJason);


					if (data[0] && data[0].resultByKey[newID] && data[0].resultByKey[newID].status && (data[0].resultByKey[newID].status.indexOf( "Invalid") > -1  || data[0].resultByKey[newID].status.indexOf( "Illegal") > -1 )) {

						alert("BAD MOJO from " + newID + "\n\nEnter valid WikiTree ID for Closest Direct Ancestor");
						return;
						// checkIDforNewTestTakerToTree(newID, data[0].resultByKey[newID].status);
					} else if (newID2 > "" && data[0] && data[0].resultByKey[newID2] && data[0].resultByKey[newID2].status && (data[0].resultByKey[newID2].status.indexOf( "Invalid") > -1  || data[0].resultByKey[newID2].status.indexOf( "Illegal") > -1 )) {

						alert("BAD MOJO from " + newID2+ "\n\nEnter valid WikiTree ID for other Direct Ancestor from Ancestral Couple");
						return;
						// checkIDforNewTestTakerToTree(newID, data[0].resultByKey[newID].status);

					} else if (data[0]) {
						console.log("YAY = we should be able to go forth and multiply ...");
						// return;
						// alert ("There is data x " + data[0].people.length);	
						let thisNewProfilePersonID = data[0].resultByKey[newID].Id;				
						let thisNewProfilePersonID2 = "";

						if (newID2 > "" && data[0].resultByKey[newID2] && data[0].resultByKey[newID2].Id && data[0].resultByKey[newID2].Id > "") {
							thisNewProfilePersonID2 = data[0].resultByKey[newID2].Id;				
						}

						for (var i in data[0].people) {
							// if ( data[0].people[i].Name == "Leger-743") { alert ("Charles Alert !!!");}
							personArray[ i ] = data[0].people[i];
							// if (personArray[i].Name == theID || i == theID) {
							// 	theProfilePersonID = i;
							// }
						}

						let thisNewProfilePerson = null;
						if (data[0].people[thisNewProfilePersonID]) {
						 	thisNewProfilePerson = data[0].people[thisNewProfilePersonID];
						 	console.log({newID} , {thisNewProfilePersonID},{thisNewProfilePerson});
							if (!thisNewProfilePerson.Gender || thisNewProfilePerson.Gender == undefined) {
								thisNewProfilePerson['Gender'] = "Unknown";
							}
						} else {
							thisNewProfilePerson = personArray[thisNewProfilePersonID];
						}

						let thisNewProfilePerson2 = null;
						if (newID2 > "") {
							if (data[0].people[thisNewProfilePersonID2]) {
							 	thisNewProfilePerson2 = data[0].people[thisNewProfilePersonID2];
							 	console.log({newID} , {thisNewProfilePersonID2},{thisNewProfilePerson2});
								if (!thisNewProfilePerson2.Gender || thisNewProfilePerson2.Gender == undefined) {
									thisNewProfilePerson2['Gender'] = "Unknown";
								}
							} else {
								thisNewProfilePerson2 = personArray[thisNewProfilePersonID2];
							}
						}

						
						// alert("Populating ...");
						// alert("DONE Populating ...");
					 	// document.getElementById('Div2P1a').textContent = theNextBestFirstName( theProfilePerson) ;
						// document.getElementById('Div2P1b').textContent = theNextBestFirstName( theProfilePerson) ;
						
						// alert("hide / show forms");
						
						// MAYBE WE DO THIS - LATER - AFTER the PLACEHOLDER RECORD IS CREATED ... ??? Just a thought ...

						// if (data[0].people[thisNewProfilePersonID]) {
						// 	document.getElementById('YourDNA').innerHTML += "<br/>" + getDNAConfirmationAidHTML ( thisNewProfilePerson);
						// }

							 

						// reportAllPeeps();	
						console.log("DONE ... check out what is sorted now:", ahnenIndexArrays[newTesterNum]);

						if (data[0].status.indexOf("Maximum number of profiles") > -1) {
							// we need to run this routine again to grab some more profiles
							addAncestorsToNewTestTakerToTree(newID, newID2, numGensAway ,startAt + 1000);
						} else /*if (ahnenIndexArrays[newTesterNum].length > 1)*/ {

							// create private placeholder persons in the personArray so that we can connect everyone with a proper Ahnentafel table
							let newPersonID = addNewPlaceholderPersonToPersonArray({FirstName:newALIAS, closestAnc1Name:newID, closestAnc2Name:newID2,  closestAnc1ID:thisNewProfilePersonID, closestAnc2ID:thisNewProfilePersonID2,numGensAway:numGensAway});
							let childOfNewProfilePersonID = newPersonID;

							if(numGensAway == 2) {
								childOfNewProfilePersonID = addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth:"Parent"});
								personArray[newPersonID].Father = childOfNewProfilePersonID;
							} else if(numGensAway > 2) {
								let prefix = "Gt".repeat(numGensAway - 3) + "Grand";
								childOfNewProfilePersonID = addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth: prefix + "Parent"});

								let tmpParentID = childOfNewProfilePersonID;
								let tmpChildID = childOfNewProfilePersonID ; //addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth:"Parent"});
								// personArray[tmpChildID].Father = tmpParentID;


								for (var i = numGensAway - 3; i >= 0; i--) {
									tmpParentID = tmpChildID;
									if (i == 0) {
										prefix = "";
									} else {
										prefix = "Gt".repeat(i - 1) + "Grand";
									}

									tmpChildID = addNewPlaceholderPersonToPersonArray({FirstName:"Private",LastNameAtBirth:prefix + "Parent"});
									personArray[tmpChildID].Father = tmpParentID;
								}
									

								personArray[newPersonID].Father = tmpChildID;
							}

							if (thisNewProfilePerson) {
								if (thisNewProfilePerson.Gender && thisNewProfilePerson.Gender == "Male") {
									personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID;
									if (thisNewProfilePerson2) {
											personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID2;
									}
										
								} else if (thisNewProfilePerson.Gender && thisNewProfilePerson.Gender == "Female") {
									personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID;
									if (thisNewProfilePerson2) {
										personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID2;
									}
								} else {

									if (thisNewProfilePerson2 && thisNewProfilePerson2.Gender && thisNewProfilePerson2.Gender == "Male") {
											personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID2;
											personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID;
									} else  {
										personArray[childOfNewProfilePersonID].Father = thisNewProfilePersonID;
										if (thisNewProfilePerson2) {
											personArray[childOfNewProfilePersonID].Mother = thisNewProfilePersonID2;
										}
									}
								

								}
							}


							// now - right before adding common ancestor details, actually calculate the Ahnentafel table for this Alias tester w their WikiTree ID'd ancestors
							populateAhnenTable(personArray,  newPersonID , 1, newTesterNum);

							if (personArray[newPersonID]) {
								document.getElementById('YourDNA').innerHTML += "<br/>" + getDNAConfirmationAidHTML ( personArray[newPersonID] );
							}
						
							/// AND .... finally ... return to regularly scheduled programming ... and check out the Common Ancestor Details
							addNewTestTakerToCommonAncestorDetails(newTesterNum);
							loadDNAkitNumbers( newALIAS, newTesterNum);
						}

					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";


		}

		function addNewTestTakerToCommonAncestorDetails( newTesterNum ) {
			if (ahnenIndexArrays[newTesterNum].length > 1) {
				console.log("addNewTestTakerToCommonAncestorDetails --> ahnenIndexArrays[ "+ newTesterNum + " ]", "starting with", commonAncestorDetails.length + " common ancestors/couples");
				// alert(801 + " reportall peeps # 3 : length = " + ahnenIndexArrays[3].length);
				// IF there is a 3rd set of Ahnen Trable indices, we'd better restart our common ancnestors list
				
				for (var i = commonAncestorDetails.length - 1; i >= 0; i--) {
					let theseDetails = commonAncestorDetails[i];

					let allIndexMatchesForTesterN = getIndexMatchesOfAncestorWithID( theseDetails.Id, newTesterNum); 
					console.log("Matches with ", theseDetails.Id, {allIndexMatchesForTesterN});
					// list of all Ahnentafel #s relative to TestTaker C that the primary ID in this commonAncestor/Couple who is a match between A and B

					if (allIndexMatchesForTesterN.length > 0) {
						// if there ARE any matches for the primary - then - we can continue to investigate to see if it is a true match
						if (theseDetails.isCouple == true) {
							// NEED to confirm that BOTH PEOPLE in the Couple Match (as a couple) to test taker C
							let coupleIndexMatchesForTesterC = [];
							for (var c = 0; c < allIndexMatchesForTesterN.length; c++) {
								let husbandAhnenNum = allIndexMatchesForTesterN[c];
								if (ahnenIndexArrays[newTesterNum][husbandAhnenNum + 1] == theseDetails.Id2 ) {
									// we have a match!
									coupleIndexMatchesForTesterC.push(husbandAhnenNum);
								}
							}
							if (coupleIndexMatchesForTesterC.length > 0) {
								// YAY - the Couple Passed the test and exists in Test Taker C's pedigree as an MRCA couple as well 
								commonAncestorDetails[i].Ahnens[newTesterNum] = coupleIndexMatchesForTesterC;

								// CHECK to see if this commonAncestor match is a TRUE match - OR - it is a match for the parents or grandparents of a match, so Common, but not MRCA
								if (isSamePersonAtNminus1(commonAncestorDetails[i])) {
									commonAncestorDetails.splice(i, 1); // remove the redundant match
								} else if (isSamePersonAtNminusN(commonAncestorDetails[i])) {
									console.log("SHOULD remove this Common Ancestor Details: 5963 ",commonAncestorDetails[i])
									commonAncestorDetails.splice(i, 1); // remove the redundant match
								}
							} else {
								// NO MATCHES to TestTaker C --> THEREFORE - must REMOVE this match from Common Ancestor Details array - not a 3 way match
								console.log("REMOVE commonAncestorDetails # " + i);
								commonAncestorDetails.splice(i, 1); // remove the non-match
							}

						} else {
							// NOT a COUPLED Match - THEREFORE - Can be used as a Match for Test Taker C whenever they appear
							commonAncestorDetails[i].Ahnens[newTesterNum] = allIndexMatchesForTesterN;

							// CHECK to see if this commonAncestor match is a TRUE match - OR - it is a match for the parents or grandparents of a match, so Common, but not MRCA
							if (isSamePersonAtNminus1(commonAncestorDetails[i])) {
								commonAncestorDetails.splice(i, 1); // remove the redundant match
							} else if (isSamePersonAtNminusN(commonAncestorDetails[i])) {
								console.log("SHOULD remove this Common Ancestor Details: 5980 ",commonAncestorDetails[i])
								commonAncestorDetails.splice(i, 1); // remove the redundant match
							}
						}

					} else {
						// NO MATCHES at all to TestTaker C --> THEREFORE - must REMOVE this match from Common Ancestor Details array - not a 3 way match
						console.log("REMOVE commonAncestorDetails # " + i);
						commonAncestorDetails.splice(i, 1); // remove the non-match
					}
				}

				console.log("NOW, AFTER NEW TestTaker investigation with the commonAncestorDetails, there are " + commonAncestorDetails.length + " common ancestors/couples betweeen TestTakers A, B, and C ... N");

				updatePreambleAndDropDownsForTriangulation();
				 
			}

		}

		function updatePreambleAndDropDownsForTriangulation() {
			console.log("Need to updatePreambleAndDropDownsForTriangulation");

			// PreambleMultiBeforeFormDIV4direct
			let multiplePathsToCommonAncestor = (commonAncestorDetails[0].Ahnens[1].length > 1) || (commonAncestorDetails[0].Ahnens[2].length > 1) || (commonAncestorDetails[0].Ahnens[3].length > 1);
			for (var i = 4; multiplePathsToCommonAncestor == false && i < commonAncestorDetails[0].Ahnens.length; i++) {
				multiplePathsToCommonAncestor = multiplePathsToCommonAncestor || commonAncestorDetails[0].Ahnens[i].length > 1;
			}

			let preamble =  "<H4 style='background-color:yellow;'>WARNING: MULTIPLE COMMON ANCESTORS FOUND</H4>";

			if (commonAncestorDetails.length == 0) {
				preamble =  "<H4 style='background-color:yellow;'>NO COMMON ANCESTORS FOUND</H4>The current set of DNA test takers do not have a common ancestor within the autosomal DNA testing range.<BR><BR>Start with a different set of DNA testers.";
			}

			if (commonAncestorDetails.length > 1 || multiplePathsToCommonAncestor == true) {
			// showEnder = false;
				if (commonAncestorDetails.length == 1 && multiplePathsToCommonAncestor == true) {
					preamble = "<H4 style='background-color:yellow;'>WARNING: MULTIPLE PATHS TO COMMON ANCESTOR FOUND</H4>";
					preamble +="DNA Confirmation via Triangulation can ONLY be done if there is a UNIQUE path from each DNA test taker to the Most Recent Common Ancestor (or Couple).  <BR>With MULTIPLE PATHS to the common ancestor(s) found, that is not possible. <BR><BR>Only DNA test takers with a unique path to the MRCA, 4th great grandchild or closer, <BR>can be used for a triangulated DNA confirmation statement,<br/>and each DNA test taker must descend from a unique grandchild of the MRCA.<br/><br/>You can add an additional Test Taker who matches on the same segment.";

					// preamble += ""
				} else {
					preamble +="DNA Confirmation via Triangulation can ONLY be done if there is a UNIQUE path from each DNA test taker to the Most Recent Common Ancestor (or Couple).  With MULTIPLE common ancestors found, that is not possible.<BR><BR>Only DNA test takers with a unique path to the MRCA, 4th great grandchild or closer, <BR>can be used for a triangulated DNA confirmation statement,<br/>and each DNA test taker must descend from a unique grandchild of the MRCA.<BR><BR>Start with a different trio of DNA testers<br/>- OR -<br/>add an additional Test Taker who matches on the same segment.";
					// preamble += "<br/><br/>Each DNA test taker must be descended from a unique child or grandchild of the MRCA."
				}


			} else if (commonAncestorDetails.length == 1 && multiplePathsToCommonAncestor == false) {
				preamble = "<H4 style='background-color:lime;'>MOST RECENT COMMON "+ (commonAncestorDetails[0].isCouple == true ? "ANCESTRAL COUPLE":"ANCESTOR")+" FOUND</H4>";
			}

			document.getElementById("PreambleMultiBeforeFormDIV4direct").innerHTML = preamble;


			// AnotherCommonAncestorFromDetailsPicker
			multipleAncestorsDropDownHTML = "";
			for (var ca = 0; ca < commonAncestorDetails.length; ca++) {
				let thisCA = commonAncestorDetails[ca];
				let thisNames = theNextBestFirstName( personArray[thisCA.Id] ) + " " + theBestLastName( personArray[thisCA.Id] );

				let thisCArelScore =  0 ;

				for (var i = 1; i < commonAncestorDetails[0].Ahnens.length; i++) {
					thisCArelScore += Math.floor(Math.log2(thisCA.Ahnens[i][0]));
				}
				thisCArelScore = Math.round ( 100 * (thisCArelScore) / (commonAncestorDetails[0].Ahnens.length - 1) ) / 100;	

				if (thisCA.isCouple == true ) {
					thisNames += " & "  + theNextBestFirstName( personArray[thisCA.Id2] ) + " " + theBestLastName( personArray[thisCA.Id2] );
				}
				multipleAncestorsDropDownHTML += "<option value=" + ca + ">" + thisNames + " (" + thisCArelScore + ")  </option>";

			}
			document.getElementById("AnotherCommonAncestorFromDetailsPicker").innerHTML = multipleAncestorsDropDownHTML;


			// NumPathsPerBranchOfMiniTreeInsideDIV4FormDirect
			pickAnotherCommonAncestorFromDetails();
		}


		function addParentsOfThisPersonToThisList(thisID , thisList) {
			let thisWorkingList = thisList;
			if (personArray[thisID] && personArray[thisID].Father && personArray[thisID].Father > 0) {
				thisWorkingList.push(personArray[thisID].Father);
				thisWorkingList = addParentsOfThisPersonToThisList(personArray[thisID].Father , thisWorkingList);
			}
			if (personArray[thisID] && personArray[thisID].Mother && personArray[thisID].Mother > 0) {
				thisWorkingList.push(personArray[thisID].Mother);
				thisWorkingList = addParentsOfThisPersonToThisList(personArray[thisID].Mother , thisWorkingList);
			}
			return thisWorkingList;
		}

		var whichMiniTree2Use = "DUOminiTree";

		function reportAllPeeps() {

			console.log("function reportAllPeeps (start) :", "theID2:" +  theID2, "(" + theOriginalID2+ ")" ,"theID2b:" +  theID2b, "theID3:" +  theID3, "(" + theOriginalID3+ ")" ,"theID3b:" +  theID3b);
			// console.trace();
			// alert(personParentsNegativeTranslation);
			// for (var i = 0; i < personParentsNegativeTranslation.length; i++) {
			// 	personArray[ personParentsNegativeTranslation[i][0] ].Father = personParentsNegativeTranslation[i][1];
			// 	personArray[ personParentsNegativeTranslation[i][0] ].Mother = personParentsNegativeTranslation[i][2];
			// }
			// alert (ahnenIndexArray);
			// body...
			// alert("Reporting All Peeps " + ahnenIndexArray.length + " " + ahnenIndexArrays[2].length + " "  + ahnenIndexArrays[3].length);

			//var emptyTable = "<TABLE align=center> 	<TR><TD colspan=8 align=center> #1 </TD></TR> 	<TR><TD colspan=8 align=center>&nbsp;</TD></TR> <TR><TD colspan=4 align=center> #2 </TD><TD colspan=4 align=center> #3 </TD></TR> 	<TR><TD colspan=8 align=center>&nbsp;</TD></TR> <TR><TD colspan=2 align=center> #4 </TD><TD colspan=2 align=center> #5 </TD><TD colspan=2 align=center> #6 </TD><TD colspan=2 align=center> #7 </TD></TR> 	<TR><TD colspan=8 align=center>&nbsp;</TD></TR> <TR> 		<TD align=center> #8 </TD> 		<TD align=center> #9 </TD> 		<TD align=center> #10 </TD> 		<TD align=center> #11 </TD> 		<TD align=center> #12 </TD> 		<TD align=center> #13 </TD> 		<TD align=center> #14 </TD> 		<TD align=center> #15 </TD> 	</TR> 	<TR><TD colspan=8 align=center>&nbsp;</TD></TR> <TR> 		<TD align=center> #16 <BR> #17 </TD> 		<TD align=center> #18 <BR> #19 </TD> 		<TD align=center> #20 <BR> #21 </TD> 		<TD align=center> #22 <BR> #23 </TD> 		<TD align=center> #24 <BR> #25 </TD> 		<TD align=center> #26 <BR> #27 </TD> 		<TD align=center> #28 <BR> #29 </TD> 		<TD align=center> #30 <BR> #31 </TD>		 	</TR> </TABLE> 	";
			var emptyTable = "<TABLE align=center> 		<TR><TD colspan=8 align=center>Family Tree of DNA test taker # 1<BR><B> #1 </B></TD></TR> 		<TR><TD colspan=4 align=center> &LowerLeftArrow; </TD><TD colspan=4 align=center> &LowerRightArrow; </TD></TR> 	<TR><TD colspan=4 align=center> #2 </TD><TD colspan=4 align=center> #3 </TD></TR> 	<TR><TD colspan=2 align=center> &LowerLeftArrow; </TD><TD colspan=2 align=center> &LowerRightArrow; </TD><TD colspan=2 align=center> &LowerLeftArrow; </TD><TD colspan=2 align=center> &LowerRightArrow; </TD></TR> 	<TR><TD colspan=2 align=center> #4 </TD><TD colspan=2 align=center> #5 </TD><TD colspan=2 align=center> #6 </TD><TD colspan=2 align=center> #7 </TD></TR> 	<TR> 		<TD align=center> &LowerLeftArrow; </TD> 		<TD align=center> &LowerRightArrow; </TD> 		<TD align=center> &LowerLeftArrow; </TD> 		<TD align=center> &LowerRightArrow; </TD> 		<TD align=center> &LowerLeftArrow; </TD> 		<TD align=center> &LowerRightArrow; </TD> 		<TD align=center> &LowerLeftArrow; </TD> 		<TD align=center> &LowerRightArrow; </TD> 	</TR> 	<TR> 		<TD align=center> #8 </TD> 		<TD align=center> #9 </TD> 		<TD align=center> #10 </TD> 		<TD align=center> #11 </TD> 		<TD align=center> #12 </TD> 		<TD align=center> #13 </TD> 		<TD align=center> #14 </TD> 		<TD align=center> #15 </TD> 	</TR> 	<TR> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD> 		<TD align=center> &darr; </TD>		 	</TR>  	<TR> 		<TD align=center> #16 <BR>+<BR> #17 </TD> 		<TD align=center> #18 <BR>+<BR> #19 </TD> 		<TD align=center> #20 <BR>+<BR> #21 </TD> 		<TD align=center> #22 <BR>+<BR> #23 </TD> 		<TD align=center> #24 <BR>+<BR> #25 </TD> 		<TD align=center> #26 <BR>+<BR> #27 </TD> 		<TD align=center> #28 <BR>+<BR> #29 </TD> 		<TD align=center> #30 <BR>+<BR> #31 </TD>		 	</TR> </TABLE> 	";
			var listOfNames = "<tr valign=top><td style='padding:10px;'>";
			var commonNames = "";
			var commonAncestors = []; // array of IDs
			commonAncestorDetails = []; // array of {Id:ID #, isCouple:TrueFalse, Ahnens:Array of ahnenIndexOccurrences Arrays} objects
			var AhnenTracker = -3;
			isCouple = false;

			theAncsAhnNum = 0;
			theAncsAhnNum2 = 0;
			theAncsAhnNum3 = 0;

			theAncsAhnNumAB1 = 0;
			theAncsAhnNumAB2 = 0;
			theAncsAhnNumBC1 = 0;
			theAncsAhnNumBC2 = 0;
			theAncsAhnNumCA1 = 0;
			theAncsAhnNumCA2 = 0;

			listOfWikiIDs = '';
			for (var i = 0; i < ahnenIndexArrays[1].length; i++) {
				if (ahnenIndexArrays[1][i]  > 0 && personArray[ ahnenIndexArrays[1][i] ]) {
					// if (i==0) {
					// 	thisPersonsName = theNextBestFirstName( personArray[ ahnenIndexArrays[1][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[1][i] ]);

					// } else  {
						thisPersonsName = theNextBestFirstName( personArray[ ahnenIndexArrays[1][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[1][i] ]);						
					// }

					emptyTable = emptyTable.replace(" #" + i + " ", thisPersonsName);
					listOfNames += " " + 	thisPersonsName + "<br/>";
					listOfWikiIDs += " " + personArray[ ahnenIndexArrays[1][i] ].Name;
				}
			}
			listOfNames += "</td><td style='padding:10px;'>";
			// alert ("Loop 2 : \n" + listOfWikiIDs);
			// console.log("reportAllPeeps --> ahnenIndexArrays[2]:", ahnenIndexArrays[2], {AhnenTracker});
			// var commonNames = ""; // list of Common Ancestors with first & last names (<BR/>)
			// var commonAncestors = []; // array of IDs
			// var commonAncestorDetails = []; // array of objects {Id:ID #, isCouple:TrueFalse, Ahnens:Array of ahnenIndexOccurrences Arrays} objects

			// GO THROUGH MALE (even) ANCESTORS in AhnenIndexArray (and record them as a Couple Male + Female, or Singleton Male-Onlys)
			for (var i = 0; i < ahnenIndexArrays[2].length; i+=2) {
				if (ahnenIndexArrays[2][i] > 0 && personArray[ ahnenIndexArrays[2][i] ]) {
					// Found valid Ancestor for Tester # 2 in the personArray (may or may not be a Common one)
					// console.log("reportAllPeeps --> FOUND @ ", i, ":",ahnenIndexArrays[2][i], {AhnenTracker});
					listOfNames += " " + theNextBestFirstName(	personArray[ ahnenIndexArrays[2][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[2][i] ]) + "<br/>";
					// if (i==0) {
					// } else {
					// 	listOfNames += " " + theNextBestFirstName(	personArray[ ahnenIndexArrays[2][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[2][i] ]) + "<br/>";
					// }

					let thisCommonAncestorID = ahnenIndexArrays[2][i];

					if (thisCommonAncestorID > -1 && ahnenIndexArrays[1].indexOf( thisCommonAncestorID ) > -1 && commonAncestors.indexOf( thisCommonAncestorID ) == -1 /* && (AhnenTracker == -3 || i < AhnenTracker+2)*/ ) {
						// IF there IS a match between Tester # 2's ancestors AND Tester # 1's ancestors - AND  - this ancestor is not already IN the commonAncestors array ...
						//... go right ahead!

						commonAncestors.push( thisCommonAncestorID );

						let allIndexMatchesForTesterA = getIndexMatchesOfAncestorWithID( thisCommonAncestorID, 1);
						let allIndexMatchesForTesterB = getIndexMatchesOfAncestorWithID( thisCommonAncestorID, 2);
						let allSpousesUniqueIDs = getUniqueSpouseIDsForAncestorWithID(allIndexMatchesForTesterA);

						let usedMatchesA = [];
						let usedMatchesB = [];
						let lengthCommonAncestorDetailsB4 = commonAncestorDetails.length;

						for (var sp = 0; sp < allSpousesUniqueIDs.length; sp++) {
							let thisSpouseID = allSpousesUniqueIDs[sp];

							let indexMatchesForCommonAncAndThisSpouse4TesterA = [];
							for (var mA = 0; mA < allIndexMatchesForTesterA.length; mA++) {
								let husbandAhnenNum = allIndexMatchesForTesterA[mA];
								if (husbandAhnenNum > -1 && ahnenIndexArrays[1][1.0 * husbandAhnenNum + 1.0] == thisSpouseID) {
									indexMatchesForCommonAncAndThisSpouse4TesterA.push(husbandAhnenNum);	
									usedMatchesA.push(mA);
									// allIndexMatchesForTesterA[mA] = -1;
								} else {

								}
							}
							let indexMatchesForCommonAncAndThisSpouse4TesterB = [];
							for (var mB = 0; mB < allIndexMatchesForTesterB.length; mB++) {
								let husbandAhnenNum = allIndexMatchesForTesterB[mB];
								if (husbandAhnenNum > -1 && ahnenIndexArrays[2][husbandAhnenNum + 1] == thisSpouseID) {
									indexMatchesForCommonAncAndThisSpouse4TesterB.push(husbandAhnenNum);	
									usedMatchesB.push(mB);
									// allIndexMatchesForTesterB[mB] = -1;
								}
							}

							console.log("TRUEcommonAncestorDetails data:", {i}, {thisCommonAncestorID}, {thisSpouseID}, {allIndexMatchesForTesterA}, {allIndexMatchesForTesterB}, {usedMatchesA},{usedMatchesB});

							if (indexMatchesForCommonAncAndThisSpouse4TesterA.length > 0 && indexMatchesForCommonAncAndThisSpouse4TesterB.length > 0) {
								// create the commonAncestorDetails object for this happy couple with descendants
								commonAncestorDetails.push ( {Id:thisCommonAncestorID, Id2:thisSpouseID, isCouple:true, Ahnens:[[], indexMatchesForCommonAncAndThisSpouse4TesterA, indexMatchesForCommonAncAndThisSpouse4TesterB]});
								console.log({i:i, Id:thisCommonAncestorID, Id2:thisSpouseID, isCouple:true});

								// NOW ... let's remove the indexMatches from the list for testerA and testerB from those arrays
								for (var a = 0; a < usedMatchesA.length; a++) {
									let mA = usedMatchesA[a];
									let indexToClear = allIndexMatchesForTesterA[mA];
									allIndexMatchesForTesterA[mA] = -1;
									
									console.log("WANT to CLEAR OUT index: ", indexToClear, " from ", allIndexMatchesForTesterA, " PLUS ANCESTORS INDEXES");
									nullOutIndexMatchesForTesterStartingAt(indexToClear, 1);
								}
								for (var b = 0; b < usedMatchesB.length; b++) {
									let mB = usedMatchesB[b];
									let indexToClear = allIndexMatchesForTesterB[mB];
									allIndexMatchesForTesterB[mB] = -1;

									console.log("WANT to CLEAR OUT index: ", indexToClear, " from ", allIndexMatchesForTesterB, " PLUS ANCESTORS INDEXES");
									nullOutIndexMatchesForTesterStartingAt(indexToClear, 2);
								}
								usedMatchesA = [];
								usedMatchesB = [];

							}
						}

						console.log("LENGTH OF commonAncestorDetails from ", lengthCommonAncestorDetailsB4, " to ", commonAncestorDetails.length);

						// OKAY - so - after all the HUSBAND / WIFE combos for thisCommonAncestor guy have been made (and a commonAncestorDetails object with isCouple:true created)
						// it's NOW time to add any leftovers to a commonAncestorDetails object with isCouple:false 

						// any MatchesFor TesterA and TesterB unaccounted for ? (i.e. not already turned to -1)
						let indexMatchesForCommonAnc4TesterA = [];
						for (var mA = 0; mA < allIndexMatchesForTesterA.length; mA++) {
							let husbandAhnenNum = allIndexMatchesForTesterA[mA];
							if (husbandAhnenNum > -1 ) {
								indexMatchesForCommonAnc4TesterA.push(husbandAhnenNum);	
								// allIndexMatchesForTesterB[mB] = -1; // we could do this for completeness, but not really necessary
							}
						}



						let indexMatchesForCommonAnc4TesterB = [];
						for (var mB = 0; mB < allIndexMatchesForTesterB.length; mB++) {
							let husbandAhnenNum = allIndexMatchesForTesterB[mB];
							if (husbandAhnenNum > -1 ) {
								indexMatchesForCommonAnc4TesterB.push(husbandAhnenNum);	
								// allIndexMatchesForTesterB[mB] = -1; // we could do this for completeness, but not really necessary
							}
						}

						// AND .. thus ... if there ARE in fact some solo matches that are leftover - for both TesterA and TesterB, then we can create another commonAncestor details object for this ancestor alone (not as a couple)

						if (indexMatchesForCommonAnc4TesterA.length > 0 && indexMatchesForCommonAnc4TesterB.length > 0) {
							// create the commonAncestorDetails object for this happy couple with descendants
							commonAncestorDetails.push ( {Id:thisCommonAncestorID,  isCouple:false, Ahnens:[[], indexMatchesForCommonAnc4TesterA, indexMatchesForCommonAnc4TesterB]});	
							console.log({i:i, Id:thisCommonAncestorID, Id2:null, isCouple:false});
						}


						/*
							BRAINSTORMING:

							IF i % 2 == 0 --> MALE ANCESTOR we've found:

							listOfIndexMatchesforT1 = findIndexesOfMatches( ahnenArray , ID to match) --> will return
		

						*/

						commonNames += " " +  theNextBestFirstName(	personArray[ thisCommonAncestorID ]) + " " + theBestLastName(personArray[ thisCommonAncestorID ]) + "<br/>";
						console.log("reportAllPeeps : ",{AhnenTracker},  personArray[ thisCommonAncestorID ], {allIndexMatchesForTesterA}, {allIndexMatchesForTesterB}, {allSpousesUniqueIDs});
						if (AhnenTracker == -3) {
							AhnenTracker = i;
							theAncsAhnNum = ahnenIndexArrays[1].indexOf( thisCommonAncestorID );
							theAncsAhnNum2 = i;
							theAncsAhnNumAB1 = theAncsAhnNum;
							theAncsAhnNumAB2 = theAncsAhnNum2;

							// theID2b is defined ONLY if you've entered TWO WikiTree IDs on the Alias form (enter closest ancestor(s) already in WikiTree, when Alias is not a WT member)
							if (theID2b > "") {
								isCouple = theIDbIsINtheArray(theAncsAhnNum , theID2b,1);
								console.log("isCouple = ", isCouple, " : line 887 : ARRAY for # 2 of 3 ");
							}
						} else if (AhnenTracker > 1) {
							isCouple = true;  // not sure why this assumption is being made .... possible logic error here ???  Maybe setup true as default, and subsequent call to coupleHood function will negate it ???
							console.log("isCouple = TRUE : line 886");
							if (theDNAconfType == 'x') {
								verifyCoupleHoodOKforXmatch(theAncsAhnNum, AhnenTracker);
							}
							// alert ("is Couple True for : " + AhnenTracker );
						} 
					}
				}
			}

			// THEN GO THROUGH FEMALE (even) ANCESTORS in AhnenIndexArray (and skip over previously recorded Couple Male + Female, and add NEW Singleton Female-Only Common Ancestors)
			for (var i = 1; i < ahnenIndexArrays[2].length; i+=2) {
				if (ahnenIndexArrays[2][i] > 0 && personArray[ ahnenIndexArrays[2][i] ]) {
					// Found valid Ancestor for Tester # 2 in the personArray (may or may not be a Common one)
					// console.log("reportAllPeeps --> FOUND @ ", i, ":",ahnenIndexArrays[2][i], {AhnenTracker});
					listOfNames += " " + theNextBestFirstName(	personArray[ ahnenIndexArrays[2][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[2][i] ]) + "<br/>";
					// if (i==0) {
					// } else {
					// 	listOfNames += " " + theNextBestFirstName(	personArray[ ahnenIndexArrays[2][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[2][i] ]) + "<br/>";
					// }
					
					let thisCommonAncestorID = ahnenIndexArrays[2][i];

					if (ahnenIndexArrays[1].indexOf( thisCommonAncestorID ) > -1 && commonAncestors.indexOf( thisCommonAncestorID ) == -1 /* && (AhnenTracker == -3 || i < AhnenTracker+2)*/ ) {
						// IF there IS a match between Tester # 2's ancestors AND Tester # 1's ancestors - AND  - this ancestor is not already IN the commonAncestors array ...
						//... go right ahead!

						commonAncestors.push( thisCommonAncestorID );

						let allIndexMatchesForTesterA = getIndexMatchesOfAncestorWithID( thisCommonAncestorID, 1);
						let allIndexMatchesForTesterB = getIndexMatchesOfAncestorWithID( thisCommonAncestorID, 2);
						let allSpousesUniqueIDs = getUniqueSpouseIDsForAncestorWithID(allIndexMatchesForTesterA, false); // false parameter is to set isHusband:false, so it knows a wife

						let usedMatchesA = [];
						let usedMatchesB = [];

						for (var sp = 0; sp < allSpousesUniqueIDs.length; sp++) {
							let thisSpouseID = allSpousesUniqueIDs[sp];
							let indexMatchesForCommonAncAndThisSpouse4TesterA = [];

							if (commonAncestorDetailsContainsThisCouple(thisSpouseID, thisCommonAncestorID)) {
								// we need to find all matches to this husband and disqualify them, because they would already be accounted for when we went through the Husbands matches

								for (var mA = 0; mA < allIndexMatchesForTesterA.length; mA++) {
									let wifeAhnenNum = allIndexMatchesForTesterA[mA];
									if (wifeAhnenNum > -1 && ahnenIndexArrays[1][wifeAhnenNum - 1] == thisSpouseID) {
										// indexMatchesForCommonAncAndThisSpouse4TesterA.push(wifeAhnenNum);	
										// usedMatchesA.push(mA);
										allIndexMatchesForTesterA[mA] = -1;
									}
								}
								let indexMatchesForCommonAncAndThisSpouse4TesterB = [];
								for (var mB = 0; mB < allIndexMatchesForTesterB.length; mB++) {
									let wifeAhnenNum = allIndexMatchesForTesterB[mB];
									if (wifeAhnenNum > -1 && ahnenIndexArrays[2][wifeAhnenNum - 1] == thisSpouseID) {
										// indexMatchesForCommonAncAndThisSpouse4TesterB.push(wifeAhnenNum);	
										// usedMatchesB.push(mB);
										allIndexMatchesForTesterB[mB] = -1;
									}
								}

							}							
							 
						}

						// OKAY - so - after all the HUSBAND / WIFE combos for thisCommonAncestor guy have been made (and a commonAncestorDetails object with isCouple:true created)
						// it's NOW time to add any leftovers to a commonAncestorDetails object with isCouple:false 

						// any MatchesFor TesterA and TesterB unaccounted for ? (i.e. not already turned to -1)
						let indexMatchesForCommonAnc4TesterA = [];
						for (var mA = 0; mA < allIndexMatchesForTesterA.length; mA++) {
							let wifeAhnenNum = allIndexMatchesForTesterA[mA];
							if (wifeAhnenNum > -1 ) {
								indexMatchesForCommonAnc4TesterA.push(wifeAhnenNum);	
								// allIndexMatchesForTesterB[mB] = -1; // we could do this for completeness, but not really necessary
							}
						}

						let indexMatchesForCommonAnc4TesterB = [];
						for (var mB = 0; mB < allIndexMatchesForTesterB.length; mB++) {
							let wifeAhnenNum = allIndexMatchesForTesterB[mB];
							if (wifeAhnenNum > -1 ) {
								indexMatchesForCommonAnc4TesterB.push(wifeAhnenNum);	
								// allIndexMatchesForTesterB[mB] = -1; // we could do this for completeness, but not really necessary
							}
						}

						// AND .. thus ... if there ARE in fact some solo matches that are leftover - for both TesterA and TesterB, then we can create another commonAncestor details object for this ancestor alone (not as a couple)

						if (indexMatchesForCommonAnc4TesterA.length > 0 && indexMatchesForCommonAnc4TesterB.length > 0) {
							// create the commonAncestorDetails object for this happy couple with descendants
							commonAncestorDetails.push ( {Id:thisCommonAncestorID,  isCouple:false, Ahnens:[[], indexMatchesForCommonAnc4TesterA, indexMatchesForCommonAnc4TesterB]});	
							console.log({i:i, Id:thisCommonAncestorID, Id2:null, isCouple:false});
						}


						/*
							BRAINSTORMING:

							IF i % 2 == 0 --> MALE ANCESTOR we've found:

							listOfIndexMatchesforT1 = findIndexesOfMatches( ahnenArray , ID to match) --> will return
		

						*/

						commonNames += " " +  theNextBestFirstName(	personArray[ thisCommonAncestorID ]) + " " + theBestLastName(personArray[ thisCommonAncestorID ]) + "<br/>";
						console.log("reportAllPeeps : ",{AhnenTracker},  personArray[ thisCommonAncestorID ], {allIndexMatchesForTesterA}, {allIndexMatchesForTesterB}, {allSpousesUniqueIDs});
						if (AhnenTracker == -3) {
							AhnenTracker = i;
							theAncsAhnNum = ahnenIndexArrays[1].indexOf( thisCommonAncestorID );
							theAncsAhnNum2 = i;
							theAncsAhnNumAB1 = theAncsAhnNum;
							theAncsAhnNumAB2 = theAncsAhnNum2;

							// theID2b is defined ONLY if you've entered TWO WikiTree IDs on the Alias form (enter closest ancestor(s) already in WikiTree, when Alias is not a WT member)
							if (theID2b > "") {
								isCouple = theIDbIsINtheArray(theAncsAhnNum , theID2b,1);
								console.log("isCouple = ", isCouple, " : line 887 : ARRAY for # 2 of 3 ");
							}
						} else if (AhnenTracker > 1) {
							isCouple = true;  // not sure why this assumption is being made .... possible logic error here ???  Maybe setup true as default, and subsequent call to coupleHood function will negate it ???
							console.log("isCouple = TRUE : line 886");
							if (theDNAconfType == 'x') {
								verifyCoupleHoodOKforXmatch(theAncsAhnNum, AhnenTracker);
							}
							// alert ("is Couple True for : " + AhnenTracker );
						} 
					}
				}
			}

			// ** AT THIS POINT ** COMMON ANCESTOR DETAILS object contains ALL the Common Ancestors that Tester # 1 and Tester # 2 have in common, not just the Most Recent ones, ALL repeats

			console.log({commonAncestorDetails});
			console.log("AFTER investigation with the commonAncestorDetails, there are " + commonAncestorDetails.length + " common ancestors/couples betweeen TestTaker1 and TestTaker2");
			// alert ("/ Loop 2 - with theAncsAhnNumbers : " +   theAncsAhnNumAB1  + " & " + theAncsAhnNumAB2);
			listOfNames += "</td>";
			if (ahnenIndexArrays[3].length > 1) {
				consoleCommonAncestorDetails();
				// console.log("reportAllPeeps --> ahnenIndexArrays[3]:", ahnenIndexArrays[3], {AhnenTracker}, commonAncestors);
				// alert(801 + " reportall peeps # 3 : length = " + ahnenIndexArrays[3].length);
				// IF there is a 3rd set of Ahnen Trable indices, we'd better restart our common ancnestors list
				commonNames = '';
				// commonAncestors = [];

				// BEFORE we go through and check for matches with TESTER # 3 - let's CULL the commonAncestorDetails array for redundant ancestors
				// i.e.  (ancestors of previously included common Ancestors)

				console.log("function reportAllPeeps - inside - ahnenIndexArrays[3] {1,2,4,5} BEFORE the getIndexMatchesOfAncestorWithID:",ahnenIndexArrays[3][1],ahnenIndexArrays[3][2],ahnenIndexArrays[3][4],ahnenIndexArrays[3][5]);

				let listOfRedundantAncestorIDs = [];
				let listOfRedundantCommonAncestorDetailIndex = [];

				for (var i = 0; i < commonAncestorDetails.length ; i++) {
					console.log("commonAncestorDetails",i, commonAncestorDetails[i]);
					let theseDetails = commonAncestorDetails[i];
					let thisDetailIsRedundant = false;

					let allIndexMatchesForTesterC = getIndexMatchesOfAncestorWithID( theseDetails.Id, 3); 
					// list of all Ahnentafel #s relative to TestTaker C that the primary ID in this commonAncestor/Couple who is a match between A and B
					console.log("Found matches: ", {allIndexMatchesForTesterC});

					// DEPENDING on # OF MATCHES FOUND (NONE or SOME) ...
					if (allIndexMatchesForTesterC.length == 0) {
						// NO MATCHES FOUND with TESTER C - THEREFORE - GET RID of this COMMON ANCESTOR DETAILS record
						listOfRedundantCommonAncestorDetailIndex.push(i);
						// but do not add parents of this match to redundant list because they may reappear somewhere

					} else  {
						// There IS a THREE WAY MATCH HERE ... so ... LET'S OPTIMIZE IT, and get rid of REDUNDANT ANCESTORS of MATCH
					

						// if the match is a COUPLE, then we have to process as a couple
						if (theseDetails.isCouple == true) {
							if (listOfRedundantAncestorIDs.indexOf(theseDetails.Id) > -1 && listOfRedundantAncestorIDs.indexOf(theseDetails.Id2) > -1) {
								thisDetailIsRedundant = true;
							}

						} else {
							// ELSE ... process as a singleton
							if (listOfRedundantAncestorIDs.indexOf(theseDetails.Id) > -1) {
								thisDetailIsRedundant = true;
							}
						}

						// IF redundant, then we just add the index to the list ... and then we'll come back to it later
						if (thisDetailIsRedundant == true) {
							listOfRedundantCommonAncestorDetailIndex.push(i);
							console.log("Do NOT WANT: "+i+" : Redundant Common Ancestor Details featuring " + theNextBestFirstNameLastName(theseDetails.Id )  + (theseDetails.isCouple?" and wife":" solo") );
						} else {
							// ELSE ... 

							// 		OLD ALGORITHM METHOD: we would add all of the ancestors OF this commonAncestorDetails to the Redundant list, so we don't keep extra deadwood around
							// 		NEW  - as of mid July 2024 - : we KEEP all of those ancestors because there may be a Common Ancestor match through a different line further up, and we don't want to miss it !

							console.log("Had " + listOfRedundantAncestorIDs.length + " people in Redundant list");

							// 	As of Mid July 2024 - COMMENT OUT the addParent routine to add more redundant ancestors
							// --> BUT ... later on we're going to have to do a CHECK to ensure we have not now allowed too many redundancies to enter into the list

							// listOfRedundantAncestorIDs = addParentsOfThisPersonToThisList(theseDetails.Id , listOfRedundantAncestorIDs);
							// if (theseDetails.isCouple == true) {
							// 	listOfRedundantAncestorIDs = addParentsOfThisPersonToThisList(theseDetails.Id2 , listOfRedundantAncestorIDs);
							// }
							console.log("NOW have " + listOfRedundantAncestorIDs.length + " people in Redundant list");
							console.log("KEEP: "+i+" : Common Ancestor Details featuring " + theNextBestFirstNameLastName(theseDetails.Id )  + (theseDetails.isCouple?" and wife":" solo") );

							if (i <2) {
								let theTmpListToShow = "REDUNDANTS:  ";
								for (var x = listOfRedundantAncestorIDs.length - 1; x >= 0; x--) {
									theTmpListToShow += theNextBestFirstNameLastName(  listOfRedundantAncestorIDs[x]  ) + " / ";
								}
								console.log({theTmpListToShow});

							}

						}
					}

				}


				console.log("AT the END OF THE SEARCH (before the culling), we have :", commonAncestorDetails.length, " common ancestor details");
				console.log({listOfRedundantAncestorIDs});
				console.log({listOfRedundantCommonAncestorDetailIndex});
				listOfRedundantCommonAncestorDetailIndex = listOfRedundantCommonAncestorDetailIndex.sort(function(a, b){return ( a - b ); });

				let theTmpListToShow = "REDUNDANTS:  ";
				for (var x = listOfRedundantAncestorIDs.length - 1; x >= 0; x--) {
					theTmpListToShow += theNextBestFirstNameLastName(  listOfRedundantAncestorIDs[x]  ) + " / ";
				}
				console.log({theTmpListToShow});

				// MAGIC LOOP (backwards) to SPLICE AWAY all of those REDUNDANT COMMON ANCESTORS .... bye bye !!!!
				// .... and at THIS point - the word REDUNDANT here means those Common Ancestors that Tester # 1 and Tester # 2 share - but Tester # 3 DOES NOT SHARE
				for (var x = listOfRedundantCommonAncestorDetailIndex.length - 1; x >= 0; x--) {
					let theIndex = listOfRedundantCommonAncestorDetailIndex[x];
					console.log("Suggestion to SPLICE away this one: ", theIndex, commonAncestorDetails[theIndex]);
					commonAncestorDetails.splice(theIndex, 1); // remove the redundant match
				}

				console.log("AFTER THE CULLING, we have :", commonAncestorDetails.length, " common ancestor details");

				// ** AT THIS POINT ** COMMON ANCESTOR DETAILS object contains ALL the Common Ancestors that Tester # 1 and Tester # 2 and Tester # 3 have in common
				//						ALL of them, not just the Most Recent ones, ALL repeats

				consoleCommonAncestorDetails();

				AhnenTracker = 0;
				listOfNames += "<td style='padding:10px;'>";
				isCouple = false;

				for (var i = commonAncestorDetails.length - 1; i >= 0; i--) {
					let theseDetails = commonAncestorDetails[i];
					console.log("CHECK New #  "+i+" : Common Ancestor Details featuring " + theNextBestFirstNameLastName(theseDetails.Id )  + (theseDetails.isCouple?" and wife":" solo") );
					let allIndexMatchesForTesterC = getIndexMatchesOfAncestorWithID( theseDetails.Id, 3); 

					// strange phenomenon had crept in for redundant cases causing crashes - needed this variable to counteract it ... should come back to this and diagnose
					let secondPersonForTesterB = ahnenIndexArrays[2][ Math.floor( theseDetails.Ahnens[2][0] / 2) ]; 

					// list of all Ahnentafel #s relative to TestTaker C that the primary ID in this commonAncestor/Couple who is a match between A and B
					console.log("Found matches: ", {allIndexMatchesForTesterC},{secondPersonForTesterB});

					if (allIndexMatchesForTesterC.length > 0 && secondPersonForTesterB != undefined) {
						// if there ARE any matches for the primary - then - we can continue to investigate to see if it is a true match
						if (theseDetails.isCouple == true) {
							// NEED to confirm that BOTH PEOPLE in the Couple Match (as a couple) to test taker C
							let coupleIndexMatchesForTesterC = [];
							for (var c = 0; c < allIndexMatchesForTesterC.length; c++) {
								let husbandAhnenNum = allIndexMatchesForTesterC[c];
								if (ahnenIndexArrays[3][husbandAhnenNum + 1] == theseDetails.Id2 ) {
									// we have a match!
									coupleIndexMatchesForTesterC.push(husbandAhnenNum);
								}
							}
							if (coupleIndexMatchesForTesterC.length > 0) {
								// YAY - the Couple Passed the test and exists in Test Taker C's pedigree as an MRCA couple as well 
								commonAncestorDetails[i].Ahnens[3] = coupleIndexMatchesForTesterC;

								// CHECK to see if this commonAncestor match is a TRUE match - OR - it is a match for the parents or grandparents of a match, so Common, but not MRCA
								if (isSamePersonAtNminus1(commonAncestorDetails[i])) {
									commonAncestorDetails.splice(i, 1); // remove the redundant match
								} else if (isSamePersonAtNminusN(commonAncestorDetails[i])) {
									console.log("SHOULD remove this Common Ancestor Details: 6558 ",commonAncestorDetails[i])
									commonAncestorDetails.splice(i, 1); // remove the redundant match
								}
							} else {
								// NO MATCHES to TestTaker C --> THEREFORE - must REMOVE this match from Common Ancestor Details array - not a 3 way match
								console.log("REMOVE commonAncestorDetails # " + i);
								commonAncestorDetails.splice(i, 1); // remove the non-match
							}

						} else {
							// NOT a COUPLED Match - THEREFORE - Can be used as a Match for Test Taker C whenever they appear
							commonAncestorDetails[i].Ahnens[3] = allIndexMatchesForTesterC;

							// CHECK to see if this commonAncestor match is a TRUE match - OR - it is a match for the parents or grandparents of a match, so Common, but not MRCA
							if (isSamePersonAtNminus1(commonAncestorDetails[i])) {
								commonAncestorDetails.splice(i, 1); // remove the redundant match
							} else if (isSamePersonAtNminusN(commonAncestorDetails[i])) {
								console.log("SHOULD remove this Common Ancestor Details: 6575 ",commonAncestorDetails[i])
								commonAncestorDetails.splice(i, 1); // remove the redundant match
							}
						}

					} else {
						// NO MATCHES at all to TestTaker C --> THEREFORE - must REMOVE this match from Common Ancestor Details array - not a 3 way match
						console.log("REMOVE commonAncestorDetails # " + i);
						commonAncestorDetails.splice(i, 1); // remove the non-match
					}
				}

				console.log("NOW, AFTER TestTakerC investigation with the commonAncestorDetails, there are " + commonAncestorDetails.length + " common ancestors/couples betweeen TestTakers A, B, and C");

				consoleCommonAncestorDetails();

				for (var i = 0; i < ahnenIndexArrays[3].length; i++) {
					if (ahnenIndexArrays[3][i]  > 0 && personArray[ ahnenIndexArrays[3][i] ]) {
						// alert(personArray[ ahnenIndexArrays[3][i] ].FirstName + " " + personArray[ ahnenIndexArrays[3][i] ].LastNameAtBirth );
						console.log("After TestTakerC investigateion - for ahnenIndexArrays[3] loop : ", i);
						if (i==0) {
							listOfNames += " " + 	theNextBestFirstName(personArray[ ahnenIndexArrays[3][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[3][i] ]) + "<br/>";
						} else {
							listOfNames += " " + 	theNextBestFirstName(personArray[ ahnenIndexArrays[3][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[3][i] ]) + "<br/>";
						}

						if (ahnenIndexArrays[1].indexOf( ahnenIndexArrays[3][i] ) > -1 && theAncsAhnNumCA1 == 0  ) {
								theAncsAhnNumCA1 = ahnenIndexArrays[1].indexOf( ahnenIndexArrays[3][i] );
								theAncsAhnNumCA2 = i;
							
						}

						if (ahnenIndexArrays[2].indexOf( ahnenIndexArrays[3][i] ) > -1 && theAncsAhnNumBC1 == 0  ) {
								theAncsAhnNumBC1 = ahnenIndexArrays[2].indexOf( ahnenIndexArrays[3][i] );
								theAncsAhnNumBC2 = i;
							
						}

						if (ahnenIndexArrays[1].indexOf( ahnenIndexArrays[3][i] ) > -1 && ahnenIndexArrays[2].indexOf( ahnenIndexArrays[3][i] ) > -1 && (AhnenTracker == 0 || i < AhnenTracker+2) ) {
							commonAncestors.push(ahnenIndexArrays[3][i]);
							commonNames += " " + 	theNextBestFirstName(personArray[ ahnenIndexArrays[3][i] ]) + " " + theBestLastName(personArray[ ahnenIndexArrays[3][i] ]) + "<br/>";
							console.log("isCouple (v3) : ",AhnenTracker,  commonNames);
							if (AhnenTracker == 0) {
								AhnenTracker = i;
								theAncsAhnNum = ahnenIndexArrays[1].indexOf( ahnenIndexArrays[3][i] );
								theAncsAhnNum2 = ahnenIndexArrays[2].indexOf( ahnenIndexArrays[3][i] );
								theAncsAhnNum3 = i;
								if (theID2b > "" && theID3b > "") {
									isCouple = theIDbIsINtheArray(theAncsAhnNum , theID2b,1) && theIDbIsINtheArray(theAncsAhnNum, theID3b, 1);
									console.log("isCouple = ", isCouple, " : line 945 : ARRAY for # 3 of 3 ");
								} else if (theID2b > "" ) {
									isCouple = theIDbIsINtheArray(theAncsAhnNum , theID2b,1) && theIDbIsINtheArray(theAncsAhnNum3, theID2b, 3);
									console.log("isCouple = ", isCouple, " : line 948 : ARRAY for # 3 of 3 ");
								} else if (theID3b > "" ) {
									isCouple = theIDbIsINtheArray(theAncsAhnNum , theID3b,1) && theIDbIsINtheArray(theAncsAhnNum2, theID3b, 2);
									console.log("isCouple = ", isCouple, " : line 951 : ARRAY for # 3 of 3 ");
								}
							} else {
								isCouple = true;
								console.log("isCouple = TRUE : line 908");
								if (theID2b > "" && (theID2 != theOriginalID2) && theID3b > "" && (theID3 != theOriginalID3)) {
									isCouple = theIDbIsINtheArray(theAncsAhnNum , theID2b,1) && theIDbIsINtheArray(theAncsAhnNum, theID3b, 1);
									console.log("isCouple = ", isCouple, " : line 958 : ARRAY for # 3 of 3 ");
								} else if (theID2b > "" && (theID2 != theOriginalID2) ) {
									isCouple = theIDbIsINtheArray(theAncsAhnNum , theID2b,1) && theIDbIsINtheArray(theAncsAhnNum3, theID2b, 3);
									console.log("isCouple = ", isCouple, " : line 961 : ARRAY for # 3 of 3 ");
								} else if (theID3b > ""  && (theID3 != theOriginalID3)) {
									isCouple = theIDbIsINtheArray(theAncsAhnNum , theID3b,1) && theIDbIsINtheArray(theAncsAhnNum2, theID3b, 2);
									console.log("isCouple = ", isCouple, " : line 965 : ARRAY for # 3 of 3 ");
								} else if (theID2b == "" && (theID2 != theOriginalID2) && aliasClosest2isMRCA) {
									isCouple = false;
									console.log("isCouple = ", isCouple, " : line 967 : ARRAY for # 3 of 3 ");
								} else if (theID3b == "" && (theID3 != theOriginalID3)  && aliasClosest3isMRCA ) {
									isCouple = false;
									console.log("isCouple = ", isCouple, " : line 970 : ARRAY for # 3 of 3 ");
								}
								if (theDNAconfType == 'x') {
									verifyCoupleHoodOKforXmatch(theAncsAhnNum, theAncsAhnNum2,theAncsAhnNum3);
								}
								// alert ("is Couple True for : " + ahnenIndexArrays[1][j+1] );
							}
						}
					}
				}

				listOfNames += "</td>";
				// alert(commonNames);
			}

			listOfNames += "</tr>";
			
			showEnder = true;
			if (commonNames == '') {
				// alert("No Common Names");
				updateGoBackLinks(1);
				if ( ahnenIndexArrays[2].length > 0  ) {
					let thisColSpan = 2;

					if ( ahnenIndexArrays[3].length > 0  ) {
						thisColSpan = 3;
					}

					thisHeader = "<tr valign=top><td colspan="+ thisColSpan +" align=center style='background-color:white; border: solid 3px lime;'><BR/><B>NO COMMON ANCESTORS</B><BR>&nbsp; <BR>&nbsp; Click a link above to Return to Step 1, or to the Menu (Reset)<BR>&nbsp; <BR>&nbsp; </td></tr>";

					// thisHeader = "<tr valign=top><td colspan=2 align=center>NO COMMON ANCESTORS<br> <input class=button type=submit value=RESET onclick=resetApp('live');><BR>&nbsp; </td></tr>";
					// document.getElementById('GoBackLinks').innerHTML = "";					
				} else {
					thisHeader ="";
				}
				listOfNames = "<table align=center>" + thisHeader + listOfNames + "</table>";
				if (ahnenIndexArrays[2].length > 1) {

					// if (ahnenIndexArrays[3].length > 1) { alert ( "DNA kits\n" +
					// 	kitNums['1-ftdna'] + " & " + kitNums['1-gedmatch'] + "\n" + 
					// 	kitNums['2-ftdna'] + " & " + kitNums['2-gedmatch'] + "\n" + 
					// 	kitNums['3-ftdna'] + " & " + kitNums['3-gedmatch'] + "\n" 
					// 	);
					// }
						
					document.getElementById('ListOfNamesDIV').innerHTML =  listOfNames;
				} else {
					document.getElementById('ListOfNamesDIV').innerHTML = emptyTable ;
				}

				if (ahnenIndexArrays[3].length > 1) {
					document.getElementById('FormDIV1contacting').style.display = 'none';
				}
				
			} else {
				// alert("COMMON names exist");
				document.getElementById('Div7ConfirmedConfident').textContent =  "confirmed with DNA";

				if (isCouple == true) {
					document.getElementById('Div7ConfirmedConfident').textContent =  "confident";
				}

				comparingAhnenNums = ""; //theAncsAhnNum + " | " + theAncsAhnNum2 + " | " + theAncsAhnNum3 + "<BR/>" + theAncsAhnNumAB1  + " vs " + theAncsAhnNumAB2 + " | " + theAncsAhnNumBC1 + " vs " + theAncsAhnNumBC2 + " | " + theAncsAhnNumCA1 + " vs " + theAncsAhnNumCA2 + "<BR/>" ;
				// alert ("Create listFromMRCAdown");
				listFromMRCAdown = "<TR valign=top><TD style='padding:10px;'>" + createListFromMRCAdown(1) + "</TD><TD style='padding:10px;'>" + createListFromMRCAdown(2) + "</TD>";
				// alert ("DONE listFromMRCAdown");
				if (ahnenIndexArrays[3].length > 0) {
					listFromMRCAdown +=  "<TD style='padding:10px;'>" + createListFromMRCAdown(3) + "</TD>";
				}
				listFromMRCAdown += "</TR>";
				if (isCouple) {
					addS4Couple = "S";
						theAncestor2 = personArray[ ahnenIndexArrays[1][ theAncsAhnNum - 0 + 1]];
				} else {
					addS4Couple = "";
				}
				listOfNames = "<table align=center><tr valign=top><td colspan=2 align=center>Found the COMMON ANCESTOR" + addS4Couple + "<BR/>for DNA test taker # 1 and DNA test taker # 2:<BR><BR>"  /* + commonNames*/ +"<BR>" + comparingAhnenNums + "</td></tr>"  + listFromMRCAdown /*+ listOfNames*/ + "</table>";


				if (theProfilePerson2 && numGensFromWikiTreeID2toCousin == 0) {
						testTaker2Gender = theProfilePerson2.Gender;
					}
				if (theProfilePerson3 && numGensFromWikiTreeID3toCousin == 0) {
						testTaker3Gender = theProfilePerson3.Gender;
					}


				let warningMissingSpouse = "";
				if (isCouple == false) {					
					let p1 = personArray[ ahnenIndexArrays[1][Math.floor(theAncsAhnNum/2)] ];
					let showMissingSpouseWarning = false;
					console.log("WARNING MISSING SPOUSE 1 calc: " , p1.FirstName, p1.LastNameAtBirth, p1.Father, p1.Mother);
					if (p1 && (p1.Father == 0 || p1.Mother == 0)) {
						showMissingSpouseWarning = true;
					}

					if (theAncsAhnNum2){
						let p2 = personArray[ ahnenIndexArrays[2][Math.floor(theAncsAhnNum2/2)] ];
						if (p2) {
							console.log("WARNING MISSING SPOUSE 2 calc: " , p2.FirstName, p2.LastNameAtBirth, p2.Father, p2.Mother);
							if (p2.Father == 0 || p2.Mother == 0) {
								showMissingSpouseWarning = true;
							}
						}
					}
					if (theAncsAhnNum3){
						let p3 = personArray[ ahnenIndexArrays[3][Math.floor(theAncsAhnNum3/2)] ];
						if (p3){
							console.log("WARNING MISSING SPOUSE 3 calc: " , p3.FirstName, p3.LastNameAtBirth, p3.Father, p3.Mother);
							if (p3.Father == 0 || p3.Mother == 0) {
								showMissingSpouseWarning = true;
							}
						}
					}
					if (showMissingSpouseWarning == true) {
						warningMissingSpouse = "<P style='background-color:orange; text-align:center;'>Warning: MISSING PARENT<BR>Consider adding the Spouse of the Common Ancestor to WikiTree.<BR>Half relationships may not be accurate if the MRCA is a couple, and not a single person.</P>";
					}

				}

				// we're still inside reportAllPeeps() here ...
				if (theDNAconfType == 'Tri') {
					let tooDistant4Triangulation = false;
					if (ahnenIndexArrays[3].length > 0) {
						// alert("B4 doMiniTreeTrio");
						// listOfNames = doMiniTreeTrio();

						console.log("CHECK TESTERS for DISTANCE from ahnen [1] ", numGensFromWikiTreeID2toCousin, numGensFromWikiTreeID3toCousin);
						// if (arrayOfTesters.length > 0) {setWarningDiedTooSoonForDNAtesting(arrayOfTesters);}
						let arrayOfTesters = [personArray[ahnenIndexArrays[1][1]]];
						if ( numGensFromWikiTreeID2toCousin == 0) {
							arrayOfTesters.push(personArray[ahnenIndexArrays[2][1]]);
						}
						if ( numGensFromWikiTreeID3toCousin == 0) {
							arrayOfTesters.push(personArray[ahnenIndexArrays[3][1]]);
						}
						setWarningDiedTooSoonForDNAtesting(arrayOfTesters);

						let multiplePathsToCommonAncestor = commonAncestorDetails[0].Ahnens[1].length > 1 || commonAncestorDetails[0].Ahnens[2].length > 1 || commonAncestorDetails[0].Ahnens[3].length > 1;
						if (commonAncestorDetails.length > 1 || multiplePathsToCommonAncestor) {
							let multipleAncestorsDropDownHTML = "";
							multipleAncestorsDropDownHTML = "<DIV id=PreambleMultiBeforeFormDIV4direct><H4 style='background-color:yellow;'>WARNING: MULTIPLE COMMON ANCESTORS FOUND</H4>";

							if (commonAncestorDetails.length == 1 && multiplePathsToCommonAncestor == true) {
								multipleAncestorsDropDownHTML = "<H4 style='background-color:yellow;'>WARNING: MULTIPLE PATHS TO COMMON ANCESTOR FOUND</H4>";
							}

							multipleAncestorsDropDownHTML +="DNA Confirmation via Triangulation can ONLY be done if there is a UNIQUE path from each DNA test taker to the Most Recent Common Ancestor (or Couple).  With MULTIPLE common ancestors found, that is not possible. <BR><BR>Only DNA test takers with a unique path to the MRCA, 4th great grandchild or closer, <BR>can be used for a triangulated DNA confirmation statement,<br/>and each DNA test taker must descend from a unique grandchild of the MRCA.<BR><BR>Start with a different trio of DNA testers<br/>- OR -<br/>add an additional Test Taker who matches on the same segment.</DIV><br/><br/>";
							multipleAncestorsDropDownHTML +="<select id=AnotherCommonAncestorFromDetailsPicker onchange=pickAnotherCommonAncestorFromDetails();>";

							showEnder = false;

							for (var ca = 0; ca < commonAncestorDetails.length; ca++) {
								let thisCA = commonAncestorDetails[ca];
								let thisNames = theNextBestFirstName( personArray[thisCA.Id] ) + " " + theBestLastName( personArray[thisCA.Id] );

								let thisCArelScore = Math.round ( 100 * (Math.floor(Math.log2(thisCA.Ahnens[1][0])) +  Math.floor(Math.log2(thisCA.Ahnens[2][0])) +  Math.floor(Math.log2(thisCA.Ahnens[3][0]))) / 3 ) / 100;	

								if (thisCA.isCouple == true ) {
									thisNames += " & "  + theNextBestFirstName( personArray[thisCA.Id2] ) + " " + theBestLastName( personArray[thisCA.Id2] );
								}
								multipleAncestorsDropDownHTML += "<option value=" + ca + ">" + thisNames + " (" + thisCArelScore + ")  </option>";

							}

							multipleAncestorsDropDownHTML +="</select>";
							multipleAncestorsDropDownHTML +="<DIV id=NumPathsPerBranchOfMiniTreeInsideDIV4FormDirect>1 path | 1 path | 1 path</DIV>";

							multipleAncestorsDropDownHTML += "<hr/>";

							let betaMiniTree = newImprovedMultiMiniTreeHTML();
							listOfNames = multipleAncestorsDropDownHTML + "<DIV id=MiniTreeInsideDIV4FormDirect>" + /*listOfNames + */  betaMiniTree + "</DIV>";


							
						} else if (commonAncestorDetails.length == 1 && multiplePathsToCommonAncestor == false) {
							console.log("SHOULD BE CHECKING TO SEE WHAT THE AHNEN #s ARE:" , commonAncestorDetails[0].Ahnens);

							if (commonAncestorDetails[0].Ahnens[1][0] > maxAhnentafelNumber4TriangulationMRCA 
								|| commonAncestorDetails[0].Ahnens[2][0] * (2 ** numGensFromWikiTreeID2toCousin) > maxAhnentafelNumber4TriangulationMRCA 
								|| commonAncestorDetails[0].Ahnens[3][0] * (2 ** numGensFromWikiTreeID3toCousin) > maxAhnentafelNumber4TriangulationMRCA) {

								document.getElementById('FormDIV4MultipleMRCAs').style.display = 'block';

								let thisWarningHTML =  "<span style='background-color:yellow;'>&nbsp;<B>WARNING: Too Distant for Autosomal DNA match</B>&nbsp;</span><BR/><BR/>";
								thisWarningHTML += "One (or more) of the DNA Test Takers is <BR/><B>further</B> than 4th great grandchild of the Common Ancestor"+ (commonAncestorDetails[0].isCouple ? "s":"") +".<BR/>That distance, genealogically, is too far for reliable autosomal DNA matches<BR/>(at this time).<BR/><BR/>"

								if (commonAncestorDetails[0].Ahnens[1][0] > maxAhnentafelNumber4TriangulationMRCA ) {
									thisWarningHTML += theNextBestFirstNameLastName(  ahnenIndexArrays[1][1] ) + " is a " + (Math.floor(Math.log2(commonAncestorDetails[0].Ahnens[1][0])) - 2) + "th great grandchild<BR/>";
								}
								if (commonAncestorDetails[0].Ahnens[2][0] * (2 ** numGensFromWikiTreeID2toCousin) > maxAhnentafelNumber4TriangulationMRCA ) {
									if (numGensFromWikiTreeID2toCousin > 0) {
										thisWarningHTML += theOriginalID2;
									} else {
										thisWarningHTML += theNextBestFirstNameLastName(  ahnenIndexArrays[2][1] );
									}
									thisWarningHTML +=  " is a " + (1.0 * numGensFromWikiTreeID2toCousin + Math.floor(Math.log2(commonAncestorDetails[0].Ahnens[2][0])) - 2) + "th great grandchild<BR/>";
								}
								if (commonAncestorDetails[0].Ahnens[3][0] * (2 ** numGensFromWikiTreeID3toCousin) > maxAhnentafelNumber4TriangulationMRCA ) {
									if (numGensFromWikiTreeID3toCousin > 0) {
										thisWarningHTML += theOriginalID3;
									} else {
										thisWarningHTML += theNextBestFirstNameLastName(  ahnenIndexArrays[3][1] );
									}
									thisWarningHTML += " is a " + (1.0 * numGensFromWikiTreeID3toCousin + Math.floor(Math.log2(commonAncestorDetails[0].Ahnens[3][0])) - 2) + "th great grandchild<BR/>";
								}
								thisWarningHTML += "<BR/><BR/>Choose a different trio of DNA testers and try again.<BR/><BR/>";

								tooDistant4Triangulation = true;
							
								document.getElementById('FormDIV4MultipleMRCAs').innerHTML = thisWarningHTML;
								showEnder = false;

							} else {
								document.getElementById('FormDIV4MultipleMRCAs').style.display = 'none';
								tooDistant4Triangulation = false;
								showEnder = true;
							}

							multipleAncestorsDropDownHTML = "<DIV id=PreambleMultiBeforeFormDIV4direct>" + "<H4 style='background-color:lime;'>MOST RECENT COMMON "+ (commonAncestorDetails[0].isCouple == true ? "ANCESTRAL COUPLE":"ANCESTOR")+" FOUND</H4>" + "</DIV>" + "<select id=AnotherCommonAncestorFromDetailsPicker onchange=pickAnotherCommonAncestorFromDetails();></SELECT>" + "<DIV id=NumPathsPerBranchOfMiniTreeInsideDIV4FormDirect>1 path | 1 blah blah blah path | 1 path</DIV>" + "<hr/>";

							let betaMiniTree = newImprovedMultiMiniTreeHTML();
							listOfNames =  multipleAncestorsDropDownHTML + "<DIV id=MiniTreeInsideDIV4FormDirect>" + /*listOfNames + */  betaMiniTree + "</DIV>";



						}


						// alert("Aft doMiniTreeTrio");
						document.getElementById('FormDIV1contacting').style.display = 'none';
						// alert("B4 prepDIV6Trio");
						prepDIV6Trio();
						// alert("Aft prepDIV6Trio");

						if( listOfNames.indexOf('TreeUnsteady') > 0) {
							showEnder = false;
						}


						// if (commonAncestorDetails.length > 1) {
						// 	document.getElementById("AnotherCommonAncestorFromDetailsPicker").value = 0; // set default
						// 	pickAnotherCommonAncestorFromDetails();
						// }
						// alert ( "DNA kits\n" +
						// kitNums['1-ftdna'] + " & " + kitNums['1-gedmatch'] + "\n" + 
						// kitNums['2-ftdna'] + " & " + kitNums['2-gedmatch'] + "\n" + 
						// kitNums['3-ftdna'] + " & " + kitNums['3-gedmatch'] + "\n" 
						// );

					} else {
						showEnder = false;
					}
				} else {
					if (theAncsAhnNumAB1 == 1 || theAncsAhnNumAB2 == 1) {
						// alert ("WE HAVE A SINGLE FILE DIRECT ANCESTOR TREE HERE");
						if (numGensFromWikiTreeID2toCousin > 0) {
							listOfNames = doMiniTreeDuo();
							prepDIV6Duo();
							whichMiniTree2Use = "DUOminiTree";
						} else {
							listOfNames = doMiniTreeUni();
							prepDIV6Uni();
							whichMiniTree2Use = "UNIminiTree";
						}

					} else if (theDNAconfType == 'x' && ahnenIndexArrays[3].length > 0) {
						listOfNames = doMiniTreeTrio();
						document.getElementById('FormDIV1contacting').style.display = 'none';
						prepDIV6Trio();
						whichMiniTree2Use = "TRIOminiTree";
						if( listOfNames.indexOf('TreeUnsteady') > 0) {
							showEnder = false;
						}
					} else {
						listOfNames = doMiniTreeDuo();
						prepDIV6Duo();
						whichMiniTree2Use = "DUOminiTree";
						showGenderWarning4XMatchPair = false;
						console.log("theOriginalID3 = *" + theOriginalID3 + "*" , (theOriginalID3 == ''), (theOriginalID3 == ""));

						if (theDNAconfType == 'x' && theOriginalID3 > '') {
						 	showEnder = false;
						} else if (theDNAconfType == 'x' && theOriginalID3 == '') {
						 	console.log("DO THE GENDER TESTING RIGHT HERE !!!!");
						 	console.log(theProfilePerson.Gender,testTaker2Gender, testTaker3Gender);
						 	showEnder = (theProfilePerson.Gender == 'Male' && testTaker2Gender == 'Male');
						 	showGenderWarning4XMatchPair = !showEnder;
						 	console.log("1017 - showEnder:", showEnder);
						}	
					}

					
				}

				Starter = "<P>STEP 4:  Is this mini tree right ? <HR><span style=background-color:lime;>&nbsp;<B>YES</B>&nbsp;</span> : <BR>Double-check, then scroll down and click NEXT button.<BR><span style='background-color:red;'>&nbsp;<B><font color=white>NO</font></B>&nbsp;</span> : <BR>Click a link above to Return to Step 1, or to the Menu (Reset)<HR>";
				Ender = '<HR><input id=NextButton4tri  class="button" type="submit" value="NEXT" onclick="doneForm4();" ><BR>&nbsp;<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;';
				Ender4Triangulation = '<HR><input id=NextButton4tri  style="display:none;" class="button" type="submit" value="Do Triangulation Match" onclick="doneForm4();" ><BR>&nbsp;<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;';

				// SETUP DIV 7 - initial DNA confirmation
				childNum = Math.floor(theAncsAhnNum/2);
				childWikiName = personArray[ ahnenIndexArrays[1][childNum] ].Name 
				childFullName = theNextBestFirstName(personArray[ ahnenIndexArrays[1][childNum] ]) + " " + personArray[ ahnenIndexArrays[1][childNum] ].LastNameAtBirth;

				if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus) {
					console.log("SETUP for VERIFY / MARK 1 : ", personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Father, personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Mother, isCouple, theAncsAhnNum);
				}

				document.getElementById('Div7CAkid').innerHTML =  "<A target=_blank href=https://www.wikitree.com/wiki/" +  childWikiName.replace(" ","_").replace(" ","_") +">" + childFullName + "</A>";
				document.getElementById('Div7CAkid2').innerHTML = "<B>" + childFullName  + "</B> (" + childWikiName+ ")";
				document.getElementById('Div7CAkid3').textContent =  childFullName;

				if (anyoneCloseEnough4Simple && theDNAconfType == 'Tri') {
					// showEnder = false;
					console.log("5698 - showEnder:", showEnder);
				}

				// alert("showEnder = " + showEnder);

				// Reset the Background Colour to WHITE - just in case we're coming from somewhere weird.
				document.getElementById('FormDIV4direct').style.backgroundColor = 'white';
				console.log("5705 - showEnder:", showEnder);

				if (showEnder){
					if ( theDNAconfType == 'Tri') {
						document.getElementById('FormDIV4direct').innerHTML = Starter + listOfNames  + warningMissingSpouse + Ender4Triangulation + "<DIV id=postMiniTreeRelationshipReportDIV>" + postMiniTreeRelationshipReport + "</DIV>" ;
					} else {
						document.getElementById('FormDIV4direct').innerHTML = Starter + listOfNames  + warningMissingSpouse + Ender + "<DIV id=postMiniTreeRelationshipReportDIV>" + postMiniTreeRelationshipReport + "</DIV>" ;

					}


					setupCustomCitationFields();

					if ( theDNAconfType == 'Tri') {
						updatePreambleAndDropDownsForTriangulation();
					}

				} else {
					if (anyoneCloseEnough4Simple && ahnenIndexArrays[3].length > 0) {
						// 3 peeps are related, but at least one pair of them are 3rd cousins or closer
						var halfCloseExample = "";
						if (isCouple == false) {
							halfCloseExample = "<BR>(half 2nd cousins 1x removed or closer, half 1st cousin 2x removed or closer),"
						}

						var simpleMatchExplanation = "<P>A DNA Confirmation by Triangulation<BR/><I>(comparing matching segments on the same chromosome for 3 DNA test takers)</I><BR> is only NEEDED if all 3 test takers are greater than 3rd cousins apart. </P><P>If <U>any pair</U> of the three are in fact third cousins or closer<BR>(2nd cousins 2x removed or closer, 1st cousin 3x removed or closer), " + halfCloseExample + "<BR>THEN,<BR>you could use a SIMPLE DNA Confirmation between that pairing.</P>";

						var doSimpleMatchButtonsCode = '';
						whichPathAfterGenderCheck = 1
						var numNonWikiTreers2 = numGensFromWikiTreeID2toCousin;
						var numNonWikiTreers3 = numGensFromWikiTreeID3toCousin;

						if (closeEnough4Simple[0] == true) {
							var btnName2 = theNextBestFirstName(theProfilePerson2);
							if (numNonWikiTreers2 > 0) {
								btnName2 = theOriginalID2;
							}
							doSimpleMatchButtonsCode += "<input type=submit onclick='switchToSimple(1,2);' value='Do Simple DNA Match: " + theNextBestFirstName(theProfilePerson) + " and "  + btnName2 + "' ><BR/><BR/>";
						}
						if (closeEnough4Simple[1] == true) {
							var btnName2 = theNextBestFirstName(theProfilePerson2);
							if (numNonWikiTreers2 > 0) {
								btnName2 = theOriginalID2;
							}
							var btnName3 = theNextBestFirstName(theProfilePerson3);
							if (numNonWikiTreers3 > 0) {
								btnName3 = theOriginalID3;
							}
							doSimpleMatchButtonsCode += "<input type=submit onclick='switchToSimple(2,3);' value='Do Simple DNA Match: " + btnName2 + " and "  + btnName3 + "' ><BR/><BR/>";
						}
						if (closeEnough4Simple[2] == true) {
							var btnName3 = theNextBestFirstName(theProfilePerson3);
							if (numNonWikiTreers3 > 0) {
								btnName3 = theOriginalID3;
							}
							doSimpleMatchButtonsCode += "<input type=submit onclick='switchToSimple(3,1);' value='Do Simple DNA Match: " + btnName3 + " and "  + theNextBestFirstName(theProfilePerson) + "' >";
						}


 
						document.getElementById('FormDIV4direct').innerHTML = Starter + listOfNames + "<BR><P>" +  "<DIV id=postMiniTreeRelationshipReportDIV>" + postMiniTreeRelationshipReport + "</DIV>" + "<BR><h3>Option: Do a Simple Match!</h3>" + simpleMatchExplanation + doSimpleMatchButtonsCode + Ender4Triangulation;

						setupCustomCitationFields();

					} else {
						// 3 peeps are not related / cannot go forward with a triangulation
						document.getElementById('FormDIV4direct').innerHTML = /*Starter +*/ listOfNames + "<BR/><DIV id=postMiniTreeRelationshipReportDIV>" + postMiniTreeRelationshipReport + "</DIV>" + Ender4Triangulation;

						console.log("ahnenIndexArrays[3].length = ", ahnenIndexArrays[3].length);

						console.log (theDNAconfType, theOriginalID3,  showGenderWarning4XMatchPair, theDNAconfType == 'x' , theOriginalID3 == '' , theOriginalID3 == false );
						console.log( "showGenderWarning4XMatchPair:", showGenderWarning4XMatchPair);
						console.log("showEnder:", showEnder);
						console.log( theDNAconfType == 'x' && (theOriginalID3 == '') && showGenderWarning4XMatchPair);

						if (theDNAconfType == 'x' && (theOriginalID3 == '') && showGenderWarning4XMatchPair) {
							var genderExplanation = "<P>A X-Chromosome DNA Confirmation for exactly <B>2 people</B> <BR/>is only valid if <B>both DNA test takers are male</B>.<BR/><BR/>If one or both of the DNA test takers are female, then a third DNA test taker is required, and triangulation is needed to verify the X-chromosome match.<BR><BR/><I>(The reason for this is because of how the X-chromosome is passed to males via only one parent, thus eliminating many possible ancestors automatically.)</I></P>";

							let doTriXMatchButtonsCode = "<input type=submit onclick='beginConf(\"x\");' value='Triangulated X-Chromosome DNA Match' >";

							document.getElementById('FormDIV4direct').innerHTML = /*Starter +*/ listOfNames  + "<BR><P>" + "<DIV id=postMiniTreeRelationshipReportDIV>" + postMiniTreeRelationshipReport + "</DIV>" + "<BR><h3>Do a Triangulated X-Chromosome Match!</h3>" +  genderExplanation + doTriXMatchButtonsCode;

						} else if (ahnenIndexArrays[3].length == 0) {

							// We're not actually ready to show all 3 branches - this is only temporary, so let's grey out the background colour
							document.getElementById('FormDIV4direct').style.backgroundColor = '#F1F1F1';
						}
					}
				}

				document.getElementById('FormDIV4direct').style.display = "block";

				if (commonAncestorDetails.length > 1 && document.getElementById("AnotherCommonAncestorFromDetailsPicker") ) {
					document.getElementById("AnotherCommonAncestorFromDetailsPicker").value = 0; // set default
					pickAnotherCommonAncestorFromDetails();
				} else if (theDNAconfType == 'Tri' && ahnenIndexArrays[3].length > 0) {
					pickAnotherCommonAncestorFromDetails();
				} 

				updateGoBackLinks(4);
			}
			// alert ("All done report all peeps");
		}
			
		function setupCustomCitationFields() {
					hasWikiTreeID2 = false;
					hasWikiTreeID3 = false;

					if (theTestPerson){
						hasWikiTreeID2 = true;
					}
						

					if ( theProfilePerson2) {
						hasWikiTreeID2 = true;
					}
					
					document.getElementById('RadioCiteName1New').checked = true;	
					document.getElementById('CustomCiteName1Cust').value = ""
					document.getElementById('UseWikiLink1').checked = true;
					
					if (hasWikiTreeID2 == false) {
						document.getElementById('CustomCiteName2Cust').value = theOriginalID2;
						document.getElementById('RadioCiteName2Cust').checked = true;
						document.getElementById('SpanCiteName2Old').style.display =  'none';
						document.getElementById('SpanCiteName2New').style.display =  'none';
						document.getElementById('SpanWikiLink2').style.display =  'none';
						Person2FullName4Citation = theOriginalID2;
						Person2FirstName4Citation = theOriginalID2;		


					} else {
						if (numGensFromWikiTreeID2toCousin > 0) {
							document.getElementById('SpanCiteName2New').style.display =  'none';
							document.getElementById('SpanCiteName2Old').style.display =  'none';
							document.getElementById('SpanWikiLink2').style.display =  'none';
							document.getElementById('CustomCiteName2Cust').value = theOriginalID2;
							document.getElementById('RadioCiteName2Cust').checked = true;
							Person2FullName4Citation = theOriginalID2;
							Person2FirstName4Citation = theOriginalID2;	

							

						} else {
							document.getElementById('SpanCiteName2New').style.display =  'revert';
							document.getElementById('SpanWikiLink2').style.display =  'revert';
							document.getElementById('CustomCiteName2Cust').value = "";
							document.getElementById('RadioCiteName2New').checked = true;
							document.getElementById('UseWikiLink2').checked = true;
							

						}
					}

					if ( theProfilePerson3) {
						document.getElementById('NeedCustomCiteName3').style.display =  'revert';
						document.getElementById('CustomCiteName3Cust').value = "";

						if (numGensFromWikiTreeID3toCousin > 0) {
							document.getElementById('SpanCiteName3New').style.display =  'none';
							document.getElementById('SpanWikiLink3').style.display =  'none';
							document.getElementById('CustomCiteName3Cust').value = theOriginalID3;
							Person2FullName4Citation = theOriginalID3;
							Person2FirstName4Citation = theOriginalID3;	
							document.getElementById('RadioCiteName3Cust').checked = true;
						} else {
							document.getElementById('SpanCiteName3New').style.display =  'revert';
							document.getElementById('SpanWikiLink3').style.display =  'revert';
							document.getElementById('CustomCiteName3Cust').value = "";
							document.getElementById('RadioCiteName3New').checked = true;
							document.getElementById('UseWikiLink3').checked = true;
						}

					} else{
						document.getElementById('NeedCustomCiteName3').style.display =  'none';
						
					}

					if (theDNAconfType == 1 && (document.getElementById('cousinFormatS').checked || document.getElementById('cousinFormatL').checked )) {
						//oops ... better reformat the relationships that come from the initial miniTree
						changeCousinFormat(0, false);
					}
		}

		
		var closeEnough4Simple = [false,false,false];
		var anyoneCloseEnough4Simple = false;
		var postMiniTreeRelationshipReport = '';
			
	
		function howManyAncestorLevelsInCommon( theAncs1, theAncs2 , theArray1, theArray2) {
			console.log("howManyAncestorLevelsInCommon:", theAncs1, theAncs2 , theArray1, theArray2);
			var theNum = 0;
			var stillSameAncestor = true;
			var Child1 = theAncs1;
			var Child2 = theAncs2;
			while ( stillSameAncestor && Child1 > 1 && Child2 > 1 && theArray2.length > 0){
				Child1 = Math.floor(Child1/2);
				Child2 = Math.floor(Child2/2);
				console.log("Children: " + Child1 + " , " + Child2);
				if (theArray2[Child2] && theArray2[Child2] > 0 && personArray[ theArray1[Child1] ] && personArray[ theArray2[Child2]] && personArray[ theArray1[Child1] ].Name != personArray[ theArray2[Child2]].Name ) {
					stillSameAncestor = false;
					// return theNum;
				} else if ( !theArray2[Child2] )  {
					stillSameAncestor = false;
				} else {
					theNum+=1;
				}
			}
			// alert ("DONE CHILDREN");
			return theNum;
		}
			
		function changeCousinFormat( numType , doDoneForm6 = true) {
			// alert("Hi there !");
			ltrType = ['','S','M','L'];
			cousinFormat = ltrType[numType];
			console.log("CHANGE cousinFormat to NEW value: ", {cousinFormat}, {numType});
			showRelConnLink = document.getElementById('showRelConnLink').checked;
			showGEDcomCompareLink  = document.getElementById('showGEDcomCompareLink').checked;
			showHaplogroupLink  = document.getElementById('showHaplogroupLink').checked;
			showDNAconfAppLink  = document.getElementById('showDNAconfAppLink').checked;
			showWhitePaperLink  = document.getElementById('showWhitePaperLink').checked;

			if (cousinFormat == '') {
				if (document.getElementById('cousinFormatS').checked) {
					cousinFormat = 'S';
				}
				if (document.getElementById('cousinFormatM').checked) {
					cousinFormat = 'M';
				}
				if (document.getElementById('cousinFormatL').checked) {
					cousinFormat = 'L';
				}
			}

			if (theDNAconfType == 1) {
				// this might require a different tack
				var isTestPerson = false;
				var isProfilePerson2 = false;
				var theValue2 =  document.getElementById('relationship').value - 1;
				var theValue3 =  document.getElementById('relationship3').value;
				if (theTestPerson) {
					isTestPerson = true;
				}
				if (theProfilePerson2) {
					isProfilePerson2 = true;
				}

				// alert ("theAncsAhnNum4TT = "  + theAncsAhnNum4TT +   " / isTestPerson = "  + isTestPerson +   " / isProfilePerson2 = "  + isProfilePerson2  + "  / Gender for P2 = " + testTaker2Gender );

				if (isTestPerson && isProfilePerson2) {
					let TT1ancMultiplier = 1;
					if (theTT1isActually == "child") {
						TT1ancMultiplier = 2;
					} else if (theTT1isActually == "grandchild") {
						TT1ancMultiplier = 4;
					}
					var relationshipsArray =  defineRelationshipsBetween(TT1ancMultiplier * theAncsAhnNum, theAncsAhnNum4TT, 0, numGensFromWikiTreeID2toCousin , 1, 2);

				}	else {
					let TT1ancAddend = 0;
					if (theTT1isActually == "child") {
						TT1ancAddend = 1;
					} else if (theTT1isActually == "grandchild") {
						TT1ancAddend = 2;
					}

					let theProfilePersonGender = "Unknown";
					// let theProfilePersonGender = "Unknown";
					if (theProfilePerson && theProfilePerson.Gender) {
						theProfilePersonGender = theProfilePerson.Gender;
					} 
					var relationshipsArray =  defineRelationshipsBetweenSimpleAliasOnly(1*theValue2 + 1*TT1ancAddend, theValue3, theProfilePersonGender, testTaker2Gender);
				}			


				// alert (relationshipsArray);
				relOfP2toP1 = relationshipsArray[0];
				relOfP1toP2 = relationshipsArray[1];
				relationshipMatrix["1|2"] = relOfP2toP1.replace("his ","").replace("her ","").replace("their ","");
				relationshipMatrix["2|1"] = relOfP1toP2.replace("his ","").replace("her ","").replace("their ","");

				console.log("AFTER changeCousinFormat:","*" + relOfP2toP1 + "*", "#" + relOfP1toP2 + "#");

			} else {
				console.log("IN AN ELSEWORLD: TheAncsAhnNums : ",theAncsAhnNum, theAncsAhnNum2,theAncsAhnNum3);
				var relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum, theAncsAhnNum2, 0, numGensFromWikiTreeID2toCousin , 1, 2);
				// alert (relationshipsArray);
				relOfP2toP1 = relationshipsArray[0];
				relOfP1toP2 = relationshipsArray[1];
				// alert(845);		
				if (theAncsAhnNum3 && theAncsAhnNum3 > 0) {
					relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum2, theAncsAhnNum3, numGensFromWikiTreeID2toCousin, numGensFromWikiTreeID3toCousin , 2, 3);
					relOfP3toP2 = relationshipsArray[0];
					relOfP2toP3 = relationshipsArray[1];
		
					relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum3, theAncsAhnNum, numGensFromWikiTreeID3toCousin, 0 , 3, 1);
					relOfP1toP3 = relationshipsArray[0];
					relOfP3toP1 = relationshipsArray[1];
				}


			}

			thisAppCurrentSettings.cousinFormat = cousinFormat;
			thisAppCurrentSettings.showRelConnLink = showRelConnLink; 
			thisAppCurrentSettings.showGEDcomCompareLink = showGEDcomCompareLink; 
			thisAppCurrentSettings.showHaplogroupLink = showHaplogroupLink; 
			thisAppCurrentSettings.showDNAconfAppLink = showDNAconfAppLink; 
			thisAppCurrentSettings.showWhitePaperLink = showWhitePaperLink; 
		


			// setCookie("wtapps_DNAconf", thisAppCurrentSettings);
			setCookie("wtapps_DNAconf", JSON.stringify(thisAppCurrentSettings) );

			if (doDoneForm6  == true) {
				console.log("DO Form 6 with ", {cousinFormat});
				doneForm6();
			}
		}


		function defineRelationshipsBetweenSimpleAliasOnly( theRel1, theRel2, GenderP1,GenderP2){
			 console.log("defineRelationshipsBetweenSimpleAliasOnly : theRel1, theRel2, GenderP1,GenderP2 =\n" , theRel1, theRel2, GenderP1,GenderP2)
			var ordinals = ['0th', '1st', '2nd', '3rd'];
			var rOfP1toP2 = '';
			var rOfP2toP1 = '';
			var GenderP2 = '';

			theMax = Math.max(theRel1, theRel2);
			theMin = Math.min(theRel1, theRel2);

			var howCousin = theMin - 1;
			var howFarOut = theMax - theMin;

				

			if (cousinFormat == "S") { // 2C3R
				rOfP1toP2 = howCousin + "C";
				if (howFarOut > 0) {
					rOfP1toP2 += howFarOut + "R";
				}
			} else if (cousinFormat == "L") { // second cousin three times removed
				ordinalWords = ['zeroeth', 'first', 'second', 'third','fourth','fifth', 'sixth','seventh','eighth','ninth', 'tenth','eleventh','twelfth','thirteenth','fourteenth','fifteenth','sixteenth','seventeenth','eighteenth','nineteenth','twentieth'];
				countingWords = ['zero', 'once', 'twice', 'three','four','five', 'six','seven','eight','nine', 'ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen','twenty'];
				
				rOfP1toP2 = ordinalWords[howCousin] + " cousin";
				
				if (howFarOut > 0) {
					if (howFarOut < 3) {
						rOfP1toP2 += " " + countingWords[howFarOut] + " removed";
					} else {
						rOfP1toP2 += " " + countingWords[howFarOut] + " times removed";
					}
				}

			} else {
				// DEFAULT - Medium Format - 2nd cousin 3x removed
				if (howCousin < ordinals.length) {
					rOfP1toP2 = ordinals[howCousin] + " cousin";
				} else {
					rOfP1toP2 = howCousin + "th cousin";
				}

				if (howFarOut > 0) {
					rOfP1toP2 += " " + howFarOut + "x removed";
				}
			}
				

			rOfP2toP1 = rOfP1toP2;

			if (theRel1 == 1 && theRel2 == 1) {
				rOfP1toP2 =  "sibling";				
				rOfP2toP1 =  "sibling";				
			} else if (theRel1 <= 1) {
				rOfP1toP2 = howGrandIsIt(howFarOut) + "pibling";				
				rOfP2toP1 = howGrandIsIt(howFarOut) + "nibling";				
			} else if (theRel2  <= 1) {
				rOfP1toP2 = howGrandIsIt(howFarOut) + "nibling";				
				rOfP2toP1 = howGrandIsIt(howFarOut) + "pibling";				
			} else if (howCousin == 0) {
				// alert ("0th cousin alert ... though theRel1 and theRel2 are " + theRel1 + " & " + theRel2  + " or " + theRel2b);
				if (theRel1 <= theRel2) {
					rOfP1toP2 = howGrandIsIt(howFarOut) + "pibling";				
					rOfP2toP1 = howGrandIsIt(howFarOut) + "nibling";				
				} else   {
					rOfP1toP2 = howGrandIsIt(howFarOut) + "nibling";				
					rOfP2toP1 = howGrandIsIt(howFarOut) + "pibling";				
				} 
			}

			if (rOfP2toP1 == 'grandpibling') { rOfP2toP1 = 'great pibling';}
			if (rOfP1toP2 == 'grandpibling') { rOfP1toP2 = 'great pibling';}
			
			var halfPrefix = "";
			if (isHalfRelationship == true ) {
				halfPrefix = "half ";
			}

			rOfP1toP2 = HisOrHer2( GenderP2 ) + " " + halfPrefix + rOfP1toP2;
			rOfP2toP1 = HisOrHer2( GenderP1 ) + " " + halfPrefix + rOfP2toP1;
			
			// if (howCousin == 0) {
				if ( GenderP1 == "Female") {
					rOfP1toP2 = rOfP1toP2.replace("nibling", "niece").replace("pibling", "aunt").replace("sibling", "sister");
				} else if ( GenderP1 == "Male") {
					rOfP1toP2 = rOfP1toP2.replace("nibling", "nephew").replace("pibling", "uncle").replace("sibling", "brother");
				}

				if ( GenderP2 == "Female") {
					rOfP2toP1 = rOfP2toP1.replace("nibling", "niece").replace("pibling", "aunt").replace("sibling", "sister");
				} else if ( GenderP2 == "Male") {
					rOfP2toP1 = rOfP2toP1.replace("nibling", "nephew").replace("pibling", "uncle").replace("sibling", "brother");
				}

			// } 
			// alert ("DONE defineRelationshipsBetween");
			return [rOfP2toP1, rOfP1toP2];
			
		}


		function defineRelationshipsBetween(theAncs1, theAncs2, numGens1, numGens2 , arrayNum1, arrayNum2){
			 console.log("get to defineRelationshipsBetween (pibling alert) : theAncs1, theAncs2 ,  numGens1, numGens2 , arrayNum1, arrayNum2 =\n" +  theAncs1 + " , " + theAncs2  + " , " +  numGens1 + " , " + numGens2  + " , " + arrayNum1 + " , " + arrayNum2, {isDirectAncestor})
			var ordinals = ['0th', '1st', '2nd', '3rd'];
			var theArray1 = ahnenIndexArrays[1]; 
			var theArray2 = ahnenIndexArrays[2]; 	
			var rOfP1toP2 = '';
			var rOfP2toP1 = '';
			var GenderP1 = '';
			var GenderP2 = '';
			var numGensFromWikiTreeIDNtoCousin = 0;// numGensFromWikiTreeID2toCousin;

			var isHalf = false;
			isHalfRelationship = isHalf;

			theArray1 = ahnenIndexArrays[arrayNum1]; 
			theArray2 = ahnenIndexArrays[arrayNum2]; 

			// if (arrayNum1 == 2) { 
			// 	// theArray1 = ahnenIndexArrays[2]; 
			// 	GenderP1 = testTaker2Gender;
			// } else if (arrayNum1 == 3) { 
			// 	GenderP1 = testTaker3Gender;
			// } else {
			// 	GenderP1 = theProfilePerson.Gender;
			// }

			if (arrayNum2 == 1) { 
				// theArray2 = ahnenIndexArray; 
				// GenderP2 = theProfilePerson.Gender;
			} else if (arrayNum2 == 3) { 
				// theArray2 = ahnenIndexArrays[3]; 
				// GenderP2 = testTaker3Gender;
				numGensFromWikiTreeIDNtoCousin = numGensFromWikiTreeID3toCousin;
			} else {
				// GenderP2 = testTaker2Gender;
				numGensFromWikiTreeIDNtoCousin = numGensFromWikiTreeID2toCousin;
			}


			GenderP1 = personArray[ theArray1[1]].Gender;
			if (theArray2.length > 0 && theArray2[1] &&  personArray[ theArray2[1]] &&  personArray[ theArray2[1]].Gender) {
				GenderP2 = personArray[ theArray2[1]].Gender;
			} else {
				GenderP2 = "Unknown";
			}


			if (isCouple == true) {
				// BOTH people come from a common couple - so - FULL cousins - no questions asked
				isHalf = false;
			} else {
				if (theAncs1 == 1 || (theAncs2 == 1 && numGens2 == 0)) {
					// ONE of the Ancestor's Ahnentafel # is 1 - which means - it's a single ancestor - so - definitely a half cousinship 
					isHalf = true;
					// UNLESS it's REALLY a DIRECT ANCESTOR:
					if (isDirectAncestor == true) {
						isHalf = false;
					}

				} else {
					// OTHERWISE, the Ancs1 and Ancs2 MUST both be > 1, so, that means that we can compare them ... initially they should both point to the same single person
					// What we want to do with this WHILE structure is to see how far down we have to go (if at all) before the ancestors diverge
					// AND ... when they DO diverge, then we compare the SPOUSES of the common ancestor - if the SPOUSES are the same, then in fact we have FULL cousins
					let last1 = theAncs1;
					let last2 = theAncs2;
					let next1 = last1;
					let next2 = last2;
					console.log("get to Check for IS HALF ", theArray1[theAncs1], theArray2[theAncs2], " spouses: ", theArray1[theAncs1 - 0 + 1], theArray2[theAncs2 - 0 + 1], {theAncs1}, {theAncs2});
					while (theArray1[next1] == theArray2[next2]) {
						last1 = next1;
						last2 = next2;
						next1 = Math.floor(last1/2);
						next2 = Math.floor(last2/2);
					}
					console.log("Last Ones: ", last1, last2);
					console.log("theArray Ones: ", theArray1, theArray2);

					let halfLast1 = Math.floor(last1/2);
					let halfLast2 = Math.floor(last2/2);
					isHalf = true;

					if (personArray[theArray1[halfLast1]] && personArray[theArray1[halfLast1]].Father && personArray[theArray1[halfLast1]].Mother &&  personArray[theArray1[halfLast1]].DataStatus.Father != 5 && personArray[theArray1[halfLast1]].DataStatus.Mother != 5) {
						if (personArray[theArray2[halfLast2]] && personArray[theArray2[halfLast2]].Father && personArray[theArray2[halfLast2]].Mother && personArray[theArray2[halfLast2]].DataStatus.Father != 5 && personArray[theArray2[halfLast2]].DataStatus.Mother != 5) {
							if (personArray[theArray1[halfLast1]].Father == personArray[theArray2[halfLast2]].Father && personArray[theArray1[halfLast1]].Mother == personArray[theArray2[halfLast2]].Mother) {
								console.log("WE HAVE A COMMON COUPLE",halfLast1, halfLast2, theArray1[halfLast1], theArray2[halfLast2], "*", personArray[theArray1[halfLast1]].Father , personArray[theArray2[halfLast2]].Father , personArray[theArray1[halfLast1]].Mother , personArray[theArray2[halfLast2]].Mother);
								isHalf = false;
							} else {
								console.log("Not a match");
							}

						} else {
							console.log("More Not a match");

						}

					} else {
							console.log("REALLY More Not a match");
					}

					// let theDiff = 1;
					// if (last1 % 2 == 1) {
					// 	theDiff = -1;
					// }
					// if (theArray1[last1 - 0 + theDiff] == theArray2[last2 - 0 + theDiff] && theArray1[last1 - 0 + theDiff] > 0 && theArray2[last2 - 0 + theDiff]) {
					// 	isHalf = false;
					// } else {
					// 	isHalf = true;
					// }

				}

				// else if (arrayNum1 * arrayNum2 == 2) {
				// 	isHalf = (ahnenIndexArrays[2][theAncsAhnNum2 - 0 + (theAncsAhnNum2  % 2 == 0 ? 1 : -1 )] != ahnenIndexArrays[1][theAncsAhnNum - 0 + (theAncsAhnNum % 2 == 0 ? 1 : -1 )] );
				// } else if (arrayNum1 * arrayNum2 == 3) {
				// 	isHalf = (ahnenIndexArrays[3][theAncsAhnNum3 - 0 + (theAncsAhnNum3  % 2 == 0 ? 1 : -1 )] != ahnenIndexArrays[1][theAncsAhnNum - 0 + (theAncsAhnNum % 2 == 0 ? 1 : -1 )] );
				// } else if (arrayNum1 * arrayNum2 == 6) {
				// 	isHalf = (ahnenIndexArrays[2][theAncsAhnNum2 - 0 + (theAncsAhnNum2  % 2 == 0 ? 1 : -1 )] != ahnenIndexArrays[3][theAncsAhnNum3 - 0 + (theAncsAhnNum3 % 2 == 0 ? 1 : -1 )] );
				// }

			}

			console.log("get to isHalf = ", isHalf);

			var halfPrefix = "";
			if (isHalf == true ) {
				halfPrefix = "half ";
			}

			isHalfRelationship = isHalf;

			// alert ("Genders are : " + GenderP1 + " & " + GenderP2);
			// alert (personArray[ theArray1[ theAncs1]].Name + " = " + personArray[ theArray2[ theAncs2]].Name);
			// alert ("howManyCommon ??");
			var howManyCommon = howManyAncestorLevelsInCommon( theAncs1, theAncs2 , ahnenIndexArrays[arrayNum1], ahnenIndexArrays[arrayNum2]);
			console.log ("How many common : "+ howManyCommon);

			theRel1 = Math.floor(Math.log2(theAncs1));			
			theRel2 = Math.floor(Math.log2(theAncs2));	
			theRel1b = 0.0 + 1*theRel1 + 1*numGens1;
			theRel2b = 0.0 + 1*theRel2 + 1*numGens2;

			var minRel = Math.min(theRel1b, theRel2b) - 1 - howManyCommon;

			if (minRel == -1 ) {
				// alert ("minRel == -1 because theRel1b + theRel2b = " + theRel1b + " + " + theRel2b + " from Ancs #s " + theAncs1 + " and " + theAncs2);
			}
			console.log("rels:"+theRel+" "+ theRel2+" "+  numGensFromWikiTreeID2toCousin +" "+minRel);

			var howFarOut = Math.abs(theRel1b - theRel2b); // HOW FAR OUT == HOW MANY GENERATIONS REMOVED FROM EACH OTHER / HOW MANY GENERATIONS OF DIFFERENCE BETWEEN THEM
			// HOW FAR OUT == 0 IMPLIES PURE COUSIN (1st cousins / 2nd cousins), HOW FAR OUT == 2 --> NthCousin TWO times Removed

			console.log("get this: theRel1, theRel2, theRel1b, theRel2b, minRel, howFarOut:",theRel1, theRel2, theRel1b, theRel2b, minRel, howFarOut);

			// THIS IS WHERE WE DETERMINE IF A MATCH IS TOO CLOSE FOR TRIANGULATION / CLOSE ENOUGH FOR SIMPLE CONFIRMATION
			// 3C is OK, but 3C1R, not so much
			// 2C2R is OK / 1C3R is OK - those are the outer limits for cousinships (and of course any flavour of 4C is way out)
			var closeEnoughForSimpleConf = true;

			if (isHalf == true ) {
				if (minRel > 2) {
					closeEnoughForSimpleConf = false;
				} else if (minRel == 2 && howFarOut > 1) {
					closeEnoughForSimpleConf = false;
				} else if (minRel == 1 && howFarOut > 2) {
					closeEnoughForSimpleConf = false;			
				}

			} else {

				if (minRel > 3) {
					closeEnoughForSimpleConf = false;
				} else if (minRel == 3 && howFarOut > 0) {
					closeEnoughForSimpleConf = false;
				} else if (minRel == 2 && howFarOut > 2) {
					closeEnoughForSimpleConf = false;
				} else if (minRel == 1 && howFarOut > 3) {
					closeEnoughForSimpleConf = false;
				}
			}

			DNApainterRelationship = minRel + "C";
			if (howFarOut > 0) {
				DNApainterRelationship += howFarOut + "R";
			}
			console.log({DNApainterRelationship});
			if (cousinFormat == "S") { // S = SHORT format: 2C3R
				rOfP1toP2 = DNApainterRelationship ; //minRel + "C";
				// if (howFarOut > 0) {
				// 	rOfP1toP2 += howFarOut + "R";
				// }
			} else if (cousinFormat == "L") { // L = LONG format: second cousin three times removed
				ordinalWords = ['zeroeth', 'first', 'second', 'third','fourth','fifth', 'sixth','seventh','eighth','ninth', 'tenth','eleventh','twelfth','thirteenth','fourteenth','fifteenth','sixteenth','seventeenth','eighteenth','nineteenth','twentieth'];
				countingWords = ['zero', 'once', 'twice', 'three','four','five', 'six','seven','eight','nine', 'ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen','twenty'];
				
				rOfP1toP2 = ordinalWords[minRel] + " cousin";
				
				if (howFarOut > 0) {
					if (howFarOut < 3) {
						rOfP1toP2 += " " + countingWords[howFarOut] + " removed";
					} else {
						rOfP1toP2 += " " + countingWords[howFarOut] + " times removed";
					}
				}

			} else {
				// DEFAULT - Medium Format - 2nd cousin 3x removed
				if (minRel < ordinals.length) {
					rOfP1toP2 = ordinals[minRel] + " cousin";
				} else {
					rOfP1toP2 = minRel + "th cousin";
				}

				if (howFarOut > 0) {
					rOfP1toP2 += " " + howFarOut + "x removed";
				}
			}

			if (minRel == 0 && howFarOut == 0) {
				rOfP1toP2 = "sibling";
			}				

			rOfP2toP1 = rOfP1toP2;

			console.log("BEFORE pibling/nibling swap out etc... ",rOfP2toP1, "/", rOfP1toP2);

			if (rOfP1toP2 == "sibling") {
				// do nothing else ... just hang tight
				DNApainterRelationship = "Sibling";
			} else if (theRel1b <= 1) {
				rOfP1toP2 = howGrandIsIt(howFarOut) + "pibling";				
				rOfP2toP1 = howGrandIsIt(howFarOut) + "nibling";				
			// } else if (theRel2 + numGensFromWikiTreeIDNtoCousin <= 1) {
			} else if (theRel2b <= 1) {
				rOfP1toP2 = howGrandIsIt(howFarOut) + "nibling";				
				rOfP2toP1 = howGrandIsIt(howFarOut) + "pibling";				
			} else if (minRel == 0) {
				// alert ("0th cousin alert ... though theRel1b and theRel2 are " + theRel1b + " & " + theRel2  + " or " + theRel2b);
				if (theRel1b <= theRel2b) {
					rOfP1toP2 = howGrandIsIt(howFarOut) + "pibling";				
					rOfP2toP1 = howGrandIsIt(howFarOut) + "nibling";				
				} else   {
					rOfP1toP2 = howGrandIsIt(howFarOut) + "nibling";				
					rOfP2toP1 = howGrandIsIt(howFarOut) + "pibling";				
				} 
			}

			console.log("Directing Ancestor - converting niblings and piblings - or TRYING ...", GenderP1, GenderP2,{rOfP1toP2},{rOfP2toP1});
			if (isDirectAncestor == true) {
				// if (!GenderP2) {
					GenderP2 = (theAncs1 % 2 == 0 ? "Male" : "Female");
				// }
				if ( GenderP1 == "Female") {
					rOfP1toP2 = rOfP1toP2.replace("pibling", "daughter").replace("nibling", "mother").replace("sibling", "sister");
				} else if ( GenderP1 == "Male") {
					rOfP1toP2 = rOfP1toP2.replace("pibling", "son").replace("nibling", "father").replace("sibling", "brother");
				} else {
					rOfP1toP2 = rOfP1toP2.replace("pibling", "child").replace("nibling", "parent");
				}
				if ( GenderP2 == "Female") {
					rOfP2toP1 = rOfP2toP1.replace("pibling", "daughter").replace("nibling", "mother").replace("sibling", "sister");
				} else if ( GenderP2 == "Male") {
					rOfP2toP1 = rOfP2toP1.replace("pibling", "son").replace("nibling", "father").replace("sibling", "brother");
				} else {
					rOfP2toP1 = rOfP2toP1.replace("pibling", "child").replace("nibling", "parent");
				}

			}

			if (rOfP2toP1 == 'grandpibling') { rOfP2toP1 = 'great pibling';}
			if (rOfP1toP2 == 'grandpibling') { rOfP1toP2 = 'great pibling';}
			

			// if (minRel == 0) {
				if ( GenderP1 == "Female") {
					rOfP1toP2 = rOfP1toP2.replace("nibling", "niece").replace("pibling", "aunt").replace("sibling", "sister");
				} else if ( GenderP1 == "Male") {
					rOfP1toP2 = rOfP1toP2.replace("nibling", "nephew").replace("pibling", "uncle").replace("sibling", "brother");
				}

				if ( GenderP2 == "Female") {
					rOfP2toP1 = rOfP2toP1.replace("nibling", "niece").replace("pibling", "aunt").replace("sibling", "sister");
				} else if ( GenderP2 == "Male") {
					rOfP2toP1 = rOfP2toP1.replace("nibling", "nephew").replace("pibling", "uncle").replace("sibling", "brother");
				}

			// } 
			// alert ("DONE defineRelationshipsBetween");

			rOfP2toP1 = halfPrefix + rOfP2toP1;
			rOfP1toP2 = halfPrefix + rOfP1toP2;

			DNApainterRelationship = halfPrefix + DNApainterRelationship;

			relationshipMatrix[arrayNum1 + "|" + arrayNum2] = rOfP2toP1;
			relationshipMatrix[arrayNum2 + "|" + arrayNum1] = rOfP1toP2;

			 
			rOfP1toP2 = HisOrHer2( GenderP2 ) + " " +  rOfP1toP2;
			rOfP2toP1 = HisOrHer2( GenderP1 ) + " " +  rOfP2toP1;
			
			console.log("get to DEFINED: " , rOfP2toP1, rOfP1toP2, closeEnoughForSimpleConf, DNApainterRelationship);
			return [rOfP2toP1, rOfP1toP2, closeEnoughForSimpleConf];
			
		}

		

		function getRelationshipReportLine(OrigID1, OrigID2, Person1,Person2,NumGensFromWiki1, NumGensFromWiki2, relOfP2toP1, relOfP1toP2, clr = 'black') {
			console.log("getRelationshipReportLine:", OrigID1, OrigID2, Person1,Person2,NumGensFromWiki1, NumGensFromWiki2, relOfP2toP1, relOfP1toP2);
			var Person1forReport = '';
			var Person2forReport = '';
			if (NumGensFromWiki1 > 0) {
				Person1forReport = OrigID1;
			} else {
				Person1forReport = theNextBestFirstName(Person1) + " " + theBestLastName(Person1);
			}

			if (NumGensFromWiki2 > 0) {
				Person2forReport = OrigID2;
			} else {
				Person2forReport = theNextBestFirstName(Person2) + " " + theBestLastName(Person2);
			}

			var relOfP2toP1pronounLess = relOfP2toP1.replace("his ","").replace("her ","").replace('their ', '');
			var relOfP1toP2pronounLess = relOfP1toP2.replace("his ","").replace("her ","").replace('their ', '');

			console.log(" = "  + relOfP1toP2pronounLess);

			if (relOfP2toP1pronounLess == relOfP1toP2pronounLess) {
				return Person1forReport + " and "  + Person2forReport + " are " + "<font color=" + clr + ">" + relOfP1toP2pronounLess.replace('cousin','cousins') + "</font>.";

			} else {

				return Person1forReport + " is "  + Person2forReport + "'s " + "<font color=" + clr + ">" + relOfP1toP2pronounLess + "</font>.";
			}


		}

		function prepDIV6Duo(){
			// alert("prepDIV6Duo");
			var relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum, theAncsAhnNum2, 0, numGensFromWikiTreeID2toCousin , 1, 2);
			

			relOfP2toP1 = relationshipsArray[0];
			relOfP1toP2 = relationshipsArray[1];

			
			theTestTakerID = personArray[ ahnenIndexArrays[2][1] ].Name;
			theTestPerson = personArray[ ahnenIndexArrays[2][1] ];
			theTestTaker = theNextBestFirstName(theTestPerson);  


			postMiniTreeRelationshipReport = getRelationshipReportLine(theNextBestFirstName(theProfilePerson) + " " + theBestLastName(theProfilePerson), theOriginalID2, theProfilePerson, theProfilePerson2, 0, numGensFromWikiTreeID2toCousin, relOfP2toP1, relOfP1toP2);

			if (isCouple){
				relCA2P1 =  howGreatIsThisOrNot( theRel + 1) + "parents";
				console.log("relCA2P1 = ", relCA2P1);

			} else {
				relCA2P1 =  howGreatIsThisOrNot( theRel + 1) + FatherOrMother( personArray[ ahnenIndexArrays[1][theAncsAhnNum] ] );
				console.log("relCA2P1 = ", relCA2P1);
			}

		}


		function prepDIV6Uni(){
			
		}

		function prepDIV6Trio(){
			console.log("prepDIV6Trio");
			postMiniTreeRelationshipReport = '';

			var FNa = theNextBestFirstName(personArray[ ahnenIndexArrays[1][1] ]);
			var FNb = theNextBestFirstName(personArray[ ahnenIndexArrays[2][1] ]);
			var FNc = theNextBestFirstName(personArray[ ahnenIndexArrays[3][1] ]);
			var FNLNa = FNa + " "  + theBestLastName(personArray[ ahnenIndexArrays[1][1] ]);
			var FNLNb = FNb + " "  + theBestLastName(personArray[ ahnenIndexArrays[2][1] ]);
			var FNLNc = FNc + " "  + theBestLastName(personArray[ ahnenIndexArrays[3][1] ]);

			theProfilePerson = personArray[ ahnenIndexArrays[1][1] ];

			if( numGensFromWikiTreeID2toCousin > 0) {
				FNb = theOriginalID2;
				FNLNb = theOriginalID2;
			} else {
				theProfilePerson2 = personArray[ ahnenIndexArrays[2][1] ];
			}

			if( numGensFromWikiTreeID3toCousin > 0) {
				FNc = theOriginalID3;
				FNLNc = theOriginalID3;
			} else {
				theProfilePerson3 = personArray[ ahnenIndexArrays[3][1] ];
			}
			// alert(792);
			// document.getElementById('TriPAfn1').textContent = FNa;
			// document.getElementById('TriPAfn2').textContent = FNa;
			// document.getElementById('TriPBfn1').textContent = FNb;
			// document.getElementById('TriPBfn2').textContent = FNb;
			// document.getElementById('TriPCfn1').textContent = FNc;
			// document.getElementById('TriPCfn2').textContent = FNc;
			
			// document.getElementById('TriPAfnln1').textContent = FNLNa;
			// document.getElementById('TriPAfnln2').textContent = FNLNa;
			// document.getElementById('TriPBfnln1').textContent = FNLNb;
			// document.getElementById('TriPBfnln2').textContent = FNLNb;
			// document.getElementById('TriPCfnln1').textContent = FNLNc;
			// document.getElementById('TriPCfnln2').textContent = FNLNc;

			// document.getElementById('TriGMpA').textContent = FNLNa;
			// document.getElementById('TriGMpB').textContent = FNLNb;
			// document.getElementById('TriGMpC').textContent = FNLNc;

			document.getElementById('TRInote23andMeCompares').innerHTML = 
						"	<B>Compare</B>  " + FNLNa + "  <B>With</B> " + FNLNb + " and " + FNLNc + ".<BR>													<B>Compare</B>  " + FNLNb + "  <B>With</B> " + FNLNc + " and " + FNLNa + ".<BR>													<B>Compare</B>  " + FNLNc + "  <B>With</B> " + FNLNa + " and " + FNLNb + ".";						
			// alert(813);
			document.getElementById('XPAfn1').textContent = FNa;
			document.getElementById('XPAfn2').textContent = FNa;
			document.getElementById('XPBfn1').textContent = FNb;
			document.getElementById('XPBfn2').textContent = FNb;
			document.getElementById('XPCfn1').textContent = FNc;
			document.getElementById('XPCfn2').textContent = FNc;
			
			document.getElementById('XPAfnln1').textContent = FNLNa;
			document.getElementById('XPAfnln2').textContent = FNLNa;
			document.getElementById('XPBfnln1').textContent = FNLNb;
			document.getElementById('XPBfnln2').textContent = FNLNb;
			document.getElementById('XPCfnln1').textContent = FNLNc;
			document.getElementById('XPCfnln2').textContent = FNLNc;

			document.getElementById('XGMpA').textContent = FNLNa;
			document.getElementById('XGMpB').textContent = FNLNb;
			document.getElementById('XGMpC').textContent = FNLNc;

			document.getElementById('Xnote23andMeCompares').innerHTML = 
						"	<B>Compare</B>  " + FNLNa + "  <B>With</B> " + FNLNb + " and " + FNLNc + ".<BR>													<B>Compare</B>  " + FNLNb + "  <B>With</B> " + FNLNc + " and " + FNLNa + ".<BR>													<B>Compare</B>  " + FNLNc + "  <B>With</B> " + FNLNa + " and " + FNLNb + ".";						
						

			// alert(836);		

			if (isCouple == true) {
				document.getElementById('Div7ConfirmedConfident').textContent =  "confident";
			}
			// alert(841);		
			closeEnough4Simple = [false,false,false];
			anyoneCloseEnough4Simple = false;
			// alert("theAncsAhnNum, theAncsAhnNum2, numGensFromWikiTreeID2toCousin = \n"+theAncsAhnNum + " , " +  theAncsAhnNum2 + " , " +  numGensFromWikiTreeID2toCousin );
			var relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum, theAncsAhnNum2, 0, numGensFromWikiTreeID2toCousin , 1, 2);
			// alert (relationshipsArray);
			relOfP2toP1 = relationshipsArray[0];
			relOfP1toP2 = relationshipsArray[1];
			closeEnough4Simple[0] = relationshipsArray[2];
			anyoneCloseEnough4Simple = anyoneCloseEnough4Simple ||  relationshipsArray[2];
			// alert(845);		
			relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum2, theAncsAhnNum3, numGensFromWikiTreeID2toCousin, numGensFromWikiTreeID3toCousin , 2, 3);
			relOfP3toP2 = relationshipsArray[0];
			relOfP2toP3 = relationshipsArray[1];
			closeEnough4Simple[1] = relationshipsArray[2];
			anyoneCloseEnough4Simple = anyoneCloseEnough4Simple ||  relationshipsArray[2];

			// alert(849);		
			relationshipsArray =  defineRelationshipsBetween(theAncsAhnNum3, theAncsAhnNum, numGensFromWikiTreeID3toCousin, 0 , 3, 1);
			relOfP1toP3 = relationshipsArray[0];
			relOfP3toP1 = relationshipsArray[1];
			closeEnough4Simple[2] = relationshipsArray[2];
			anyoneCloseEnough4Simple = anyoneCloseEnough4Simple ||  relationshipsArray[2];

			theOriginalID1 = theNextBestFirstName(theProfilePerson) + " " + theBestLastName(theProfilePerson);

 
 			let rptClr1 = 'red';
 			let rptClr2 = 'green';
 			let rptClr3 = 'blue';

			if (ahnenIndexArrays[1][Math.floor (theAncsAhnNum / 2)] == ahnenIndexArrays[3][Math.floor (theAncsAhnNum3 / 2)]) {
				// alert ("I think we've found a SWAP A ROO candidate here"), and thus B and C are swapped positions
				rptClr1 = 'blue';
				rptClr2 = 'green';
	 			rptClr3 = 'red';
			}
				 



			postMiniTreeRelationshipReport = getRelationshipReportLine(FNLNa, FNLNb, theProfilePerson, theProfilePerson2, 0, numGensFromWikiTreeID2toCousin, relOfP2toP1, relOfP1toP2, rptClr1) + "<BR>" ;
			postMiniTreeRelationshipReport += getRelationshipReportLine(FNLNb, FNLNc, theProfilePerson2, theProfilePerson3, numGensFromWikiTreeID2toCousin, numGensFromWikiTreeID3toCousin, relOfP3toP2, relOfP2toP3, rptClr2) + "<BR>" ;
			postMiniTreeRelationshipReport += getRelationshipReportLine(FNLNc, FNLNa, theProfilePerson3, theProfilePerson, numGensFromWikiTreeID3toCousin, 0, relOfP1toP3, relOfP3toP1, rptClr3) + "<BR>" ;

			// alert (relOfP1toP2  + " "  + relOfP2toP3 + " "  + relOfP3toP1);
			// if (anyoneCloseEnough4Simple) {
				// alert("You'd better show the WARNING and DO SIMPLE div now!");
				// document.getElementById('NextButton4').style.display = 'none';
			// }

			console.log("post prep:",{postMiniTreeRelationshipReport});

		}

		
		function changedChromoNum() {
			var CNum = document.getElementById('ChromoNum').value;
			var CMamount = document.getElementById('TriMatchAmount').value;
			// alert(CNum);
			// document.getElementById('ChromoN1').innerHTML = (CNum);
			// document.getElementById('ChromoN2').innerHTML = (CNum);
			// document.getElementById('ChromoN3').innerHTML = (CNum);
			document.getElementById('TriStdStmtSegment').innerHTML = (CNum);
			document.getElementById('TriStdStmtAmount').innerHTML = (CMamount);
			document.getElementById('TriStdStmtAmount').innerHTML = (CMamount);
			document.getElementById('TriNoteMyHeritageChromoN').innerHTML = (CNum);

			if (document.getElementById('TriSegmentMatchStmtStd').checked){
				document.getElementById('TriCustomStmt').value = (CMamount) + " cM segment on chromosome " + (CNum);
			}


			if (CMamount >= 0 && CMamount < 12) {

				let amountCM = Math.round(CMamount);
				let theNote =  "<BR/><span style='background-color:#FFFDD0;'>NOTE: Segments of " + amountCM + " cM have a false positive rate of " + falsePositiveRateList[amountCM] + "%,<BR/>according to a Family Tree DNA " +  "<A target=_blank href=https://blog.familytreedna.com/wp-content/uploads/2021/08/Family_Finder_Matching_WhitePaper.pdf>white paper</A>" + " from 2021.</span><BR><BR>";
				if (CMamount < 7) {
					theNote += "<span style='background-color:yellow;'>WARNING: Minimum of 7 cM match required!<BR/>(and more is highly recommended!!)</span><BR/> " ;
				}

				document.getElementById('FormDIV6FalsePercentageNote').innerHTML = theNote;
			} else {
				document.getElementById('FormDIV6FalsePercentageNote').innerHTML = "";
			}

			

			setupFTDNAspan();

		}

		function updateRequiredToContinue4TriStep1() {
			var CNum = document.getElementById('Step1ChromoNum').value;
			var CMamount = document.getElementById('Step1TriMatchAmount').value;
			var TTakerA = document.getElementById("TTakerA").value;
			var TTakerB = document.getElementById("TTakerB").value;
			var TTakerC = document.getElementById("TTakerC").value;

			let header = "<B><U>REQUIRED to CONTINUE:</U></B><BR/>";
			let theList = "";
			let theWhitePaperNote = "";

			if (TTakerA == "") {
				theList += "WikiTree ID for Test Taker A <BR/>";
			}
			if (TTakerB == "") {
				theList += "WikiTree ID or Alias for Test Taker B <BR/>";
			}
			if (TTakerC == "") {
				theList += "WikiTree ID or Alias for Test Taker C <BR/>";
			}
			if (CNum == "" || CNum == "?") {
				theList += "Chromosome # of Shared Segment <BR/>";
			}
			if (CMamount == "") {
				theList += "Amount  of Shared Segment <BR/>";
			} else if (CMamount >= 0 && CMamount < 12) {
				let amountCM = Math.round(CMamount);
				theWhitePaperNote= "<span style='background-color:#FFFDD0;'>NOTE: Segments of " + amountCM + " cM have a false positive rate of " + falsePositiveRateList[amountCM] + "%,<BR/>according to a Family Tree DNA " +  "<A target=_blank href=https://blog.familytreedna.com/wp-content/uploads/2021/08/Family_Finder_Matching_WhitePaper.pdf>white paper</A>" + " from 2021.</span><BR><BR>";
				let theDIV6FalsePercentageNote = "<BR/>" + theWhitePaperNote;
				if (CMamount < 7) {
					theList = "<span style='background-color:yellow;'>WARNING: Minimum of 7 cM match required!<BR/>(and more is highly recommended!!)</span><BR/> "  + theList;
					theDIV6FalsePercentageNote += "<span style='background-color:yellow;'>WARNING: Minimum of 7 cM match required!<BR/>(and more is highly recommended!!)</span><BR/> " ;
				}
				document.getElementById('FormDIV6FalsePercentageNote').innerHTML = theDIV6FalsePercentageNote;
			} else {
				document.getElementById('FormDIV6FalsePercentageNote').innerHTML = "";
			}

			if (theList > "") {
				document.getElementById("required4NextButton1abc").innerHTML = theWhitePaperNote + header + theList;
				document.getElementById("showNextButton1abc").style.display = "none" ;
			} else {
				document.getElementById("required4NextButton1abc").innerHTML = theWhitePaperNote + "";
				document.getElementById("showNextButton1abc").style.display = "revert" ;
			}
					
			console.log({CNum}, CNum, CNum == "?");
					
		}

		function changedStep1ChromoNum(typeAmount = 0) {
			var CNum = document.getElementById('Step1ChromoNum').value;
			var origCMamount = document.getElementById('Step1TriMatchAmount').value;
			var CMpercent = document.getElementById('Step1TriMatchPercent').value;
			var CMamount = origCMamount;
			// CHECK to see if a % HAS BEEN ENTERED (23andMe testers)
			// and DO the conversions if needed
			if (typeAmount == 23) {
				CMamount = Math.round( 74.4 * CMpercent);
				document.getElementById('Step1TriMatchAmount').value = CMamount;
				origCMamount = CMamount;
			} else if (typeAmount == 0 && CMpercent > "") {
				CMpercent = Math.round( 100 *  CMamount / 74.4) / 100;
				document.getElementById('Step1TriMatchPercent').value = CMpercent;
			}


			// TRANSFER these values to the STEP 5 area for future reference
			document.getElementById('ChromoNum').value = CNum;
			document.getElementById('TriMatchAmount').value = CMamount;

			// CHECK AMOUNT of shared cM, and if LESS THAN the GenerationsBreakpointForSharedCM, then force extended 14 generation search
			if (origCMamount > "" && CMamount < GenerationsBreakpointForSharedCM) {
				document.getElementById('useGens4Tri10').checked = false;
				document.getElementById('showUseGens4Tri10').style.display = "none";
				document.getElementById('useGens4Tri14').checked = true;
				console.log("SWITCH to 14 Gens");
			} else {
				document.getElementById('showUseGens4Tri10').style.display = "revert";
			}


			// alert(CNum);
			// document.getElementById('ChromoN1').innerHTML = (CNum);
			// document.getElementById('ChromoN2').innerHTML = (CNum);
			// document.getElementById('ChromoN3').innerHTML = (CNum);
			document.getElementById('TriStdStmtSegment').innerHTML = (CNum);
			document.getElementById('TriStdStmtAmount').innerHTML = (CMamount);
			document.getElementById('TriStdStmtAmount').innerHTML = (CMamount);
			document.getElementById('TriNoteMyHeritageChromoN').innerHTML = (CNum);

			if (document.getElementById('TriSegmentMatchStmtStd').checked){
				document.getElementById('TriCustomStmt').value = (CMamount) + " cM segment on chromosome " + (CNum);
			}

			setupFTDNAspan();
			updateRequiredToContinue4TriStep1();

		}

		function changedXmatchAmount() {
			
			var CMamount = document.getElementById('XmatchAmount').value;
			// alert(CNum);
			document.getElementById('xStdStmtAmount').innerHTML = (CMamount);
			

			if (document.getElementById('xSegmentMatchStmtStd').checked){
				document.getElementById('xCustomStmt').value = (CMamount) + " cM segment on the X chromosome";
			}
		}

		
		function customizeCitations() {
			// alert("time to Customize Citations me-ole-mucker");
			
			document.getElementById('FormDIV7CustomCitations').style.display = 'revert';
			document.getElementById('FormDIV7CustomCitationsLink').style.display = 'none';
		}
		
		function customizeCitationsClose() {
			// alert("time to Customize Citations me-ole-mucker");
			
			document.getElementById('FormDIV7CustomCitationsLink').style.display = 'revert';
			document.getElementById('FormDIV7CustomCitations').style.display = 'none';
		}


		function customizeChromosomes() {
			// alert("time to Customize Chromosomes me-ole-mucker");
			
			document.getElementById('FormDIV6Custom' + theDNAconfType.toUpperCase() + 'Chromosomes').style.display = 'revert';
			document.getElementById('FormDIV6Custom' + theDNAconfType.toUpperCase() + 'ChromosomesLink').style.display = 'none';
		}
		
		function customizeChromosomesClose() {
			// alert("time to Customize Chromosomes me-ole-mucker");
			
			document.getElementById('FormDIV6Custom' + theDNAconfType.toUpperCase() + 'ChromosomesLink').style.display = 'revert';
			document.getElementById('FormDIV6Custom' + theDNAconfType.toUpperCase() + 'Chromosomes').style.display = 'none';
		}
		function addNote() {
			// alert("time to Customize Chromosomes me-ole-mucker");
			
			document.getElementById('FormDIV6AddNote' + theDNAconfType ).style.display = 'revert';
			document.getElementById('FormDIV6AddNote' + theDNAconfType + 'Link').style.display = 'none';
		}
		
		function addNoteClose() {
			// alert("time to Customize Chromosomes me-ole-mucker");
			
			document.getElementById('FormDIV6AddNote' + theDNAconfType + 'Link').style.display = 'revert';
			document.getElementById('FormDIV6AddNote' + theDNAconfType ).style.display = 'none';
		}
		function relationshipAtGen( gen ) {
			if (gen == 0) {
				return "siblings";
			} else {
				if (gen == 1 ) {
					return "1st cousins";
				} else if (gen == 2 ) {
					return "2nd cousins";
				} else if (gen == 3 ) {
					return "3rd cousins";
				} else {
					return gen + "th cousins";
				}
			}
		}

		function relationshipAtGenRemoved( gen, removed, isHalf = false ) {
			let theHalf = "";
			if (isHalf == true) {
				theHalf = "half ";
			}
			if (gen == 0 && removed == 0) {
				return theHalf + "siblings";
			} else if (gen <= 0 && removed > 0) {
				return theHalf + howGrandIsIt( gen + removed ) + "<BR>nibling / <BR> pibling";
			} else {
				if (gen == 1 ) {
					typeCousin =  "1st cousins";
				} else if (gen == 2 ) {
					typeCousin =  "2nd cousins";
				} else if (gen == 3 ) {
					typeCousin =  "3rd cousins";
				} else {
					typeCousin =  gen + "th cousins";
				}
				return theHalf + typeCousin + addBRandRemoved(removed);
			}


			// // REMOVED ROUTINE
			// if (removed > 0) {
			// 	return "<br>" + removed + "x removed";						
			// } else {				
			// 	return "";
			// }

			// // HOW GRAND routine
			// if (num == 1) {
			// 	return "";
			// } else if (num == 2) {
			// 	return "grand";


		}


		function howGrandIsIt( num ) {
			if (num == 1) {
				return "";
			} else if (num == 2) {
				return "grand";
			} else if (num == 3) {
				return "great grand";
			} else if (num > 3) {
				return (num-2) + "x great grand";
			} else if (num == 0) {
				return  "child's ";
			}
		}

		function addBRandRemoved(howMuch) {
			if (howMuch > 0) {
				return "<br>" + howMuch + "x removed";						
			} else {				
				return "";
			}
		}
			
		function doMiniTreeTrio () {
			// alert("Start MiniTree Trio: theAncsAhnNum3 = " + theAncsAhnNum3);
			// var kitNumz = loadDNAkitNumbers("Clarke-11007");
			// alert("K:" + kitNumz);

			var startingNum1 = 0;			
			var startingNum2 = 0;			
			var startingNum3 = 0;			
			var theList = "";
			var numNonWikiTreers2 = numGensFromWikiTreeID2toCousin;
			var numNonWikiTreers3 = numGensFromWikiTreeID3toCousin;
			// alert(numNonWikiTreers2 + " v  " + numNonWikiTreers3);
			var isHalfAB = false;
			var isHalfBC = false;
			var isHalfAC = false;
			
			startingNum1 = theAncsAhnNum;
			startingNum2 = theAncsAhnNum2;				
			startingNum3 = theAncsAhnNum3;			
			relCA2P3 = '';

			MRCAbase = 'parent';
			if (isCouple) {
				addS4Couple = "S";
				// MRCAbase = 'parents';
				ancestorType = "parent";
			} else {
				addS4Couple = "";
				if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Male') {
					MRCAbase = 'father';
				} else if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Female') {
					MRCAbase = 'mother';
				}
				ancestorType = MRCAbase;
			}	



			theRel = Math.floor(Math.log2(theAncsAhnNum));			
			theRel2 = Math.floor(Math.log2(theAncsAhnNum2));			
			theRel3 = Math.floor(Math.log2(theAncsAhnNum3));	
			console.log ("The Rels are : " + theRel + "  / " + theRel2 + " / " + theRel3 );

			 MRCArelationship = theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + "'s " + howGrandIsIt(theRel) + MRCAbase + "<BR><I>and</I><BR>";
			console.log("MRCArelationship", MRCArelationship);

			relCA2P1 = howGrandIsIt( theRel) + MRCAbase ;
			console.log("relCA2P1 = ", relCA2P1);

			if (numNonWikiTreers2 > 0) {
				MRCArelationship +=   theOriginalID2 + "'s " + howGrandIsIt( 2 + 1*theRel2 + 1*numNonWikiTreers2 - 2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( 2 + 1*theRel2 + 1*numNonWikiTreers2 - 2) + MRCAbase ;
			} else {
				MRCArelationship +=  theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]) + "'s " + howGrandIsIt(theRel2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( theRel2) + MRCAbase ;
				console.log("relCA2P2", relCA2P2);
			}

			

			


			MRCArelationship += "<BR><I>and</I><BR>";
			
			if (numNonWikiTreers3 > 0) {
				relCA2P3 = howGrandIsIt( 2 + 1*theRel3 + 1*numNonWikiTreers3 - 2) + MRCAbase ;
				MRCArelationship +=   theOriginalID3 + "'s " + howGrandIsIt( 3 + 1*theRel3 + 1*numNonWikiTreers3 - 3) + MRCAbase ;
				console.log("numNonWikiTreers3:",numNonWikiTreers3 , "theRel3",theRel3);
			} else {
				relCA2P3 = howGrandIsIt( theRel3) + MRCAbase ;
				MRCArelationship += theNextBestFirstName( personArray[ahnenIndexArrays[3][1]]) + "'s " + howGrandIsIt(theRel3) + MRCAbase ;
				console.log("relCA2P3", relCA2P3);
			}

			if (numNonWikiTreersC > 0) {
			} else {
			}

			// MRCArelationship += personArray[ahnenIndexArrays[2][1]].FirstName + "'s " + howGrandIsIt(theRel2) + MRCAbase + "<BR><I>and</I><BR>";
			// MRCArelationship += personArray[ahnenIndexArrays[3][1]].FirstName + "'s " + howGrandIsIt(theRel3) + MRCAbase ;


			theList =  "<table  id=TRIOminiTree align=center><TR><TD colspan=5 align=center><table border=0 align=center><tr><td >&nbsp;</td><td  align=center style='background-color:lime; padding:10px;'><span style=background-color:yellow;>COMMON ANCESTOR" + addS4Couple + "</span><BR><B>" +  theNextBestFirstName(personArray[ahnenIndexArrays[1][startingNum1]] )+ " " + theBestLastName  (personArray[ahnenIndexArrays[1][startingNum1]]) ;
			if (isCouple) {
				theList += "<BR/>&<BR/>" + theNextBestFirstName(personArray[ahnenIndexArrays[1][startingNum1+1]]) + " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1+1]]) ;
			}

			theList += "</b></td><td >&nbsp;</td></tr>"
			theList += "<tr><td colspan=3 align=center>" + MRCArelationship + "</td></tr>";
			theList += "</table> </td></tr>" ;
			// theList += "</b></td><td >&nbsp;</td></tr></table> </td></tr>" ;

			nextNum1 = Math.floor (startingNum1 / 2);
			nextNum2 = Math.floor (startingNum2 / 2);
			nextNum3 = Math.floor (startingNum3 / 2);

			var colBnum = 2;
			var colCnum = 3;
			var ahnenIndexArrayB = ahnenIndexArrays[2];
			var ahnenIndexArrayC = ahnenIndexArrays[3];
			var nextNumB = nextNum2;
			var nextNumC = nextNum3;
			console.log("@line 2090 : nextNumC = ", nextNumC);
			var numNonWikiTreersB = numNonWikiTreers2;
			var numNonWikiTreersC = numNonWikiTreers3;
			var theOriginalIDB = theOriginalID2;
			var theOriginalIDC = theOriginalID3;

			if (ahnenIndexArrays[1][nextNum1] == ahnenIndexArrays[3][nextNum3]) {
				// alert ("I think we've found a SWAP A ROO candidate here");
				colBnum = 3;
				colCnum = 2;
				ahnenIndexArrayB = ahnenIndexArrays[3];
				ahnenIndexArrayC = ahnenIndexArrays[2];
				nextNumB = nextNum3;	
				nextNumC = nextNum2;
				console.log("@line 2104 : nextNumC = ", nextNumC);
				numNonWikiTreersB = numNonWikiTreers3;
				numNonWikiTreersC = numNonWikiTreers2;			
				theOriginalIDB = theOriginalID3;
				theOriginalIDC = theOriginalID2;
			}

			let parent2StartingDX =  + 1;
			if (isCouple == false &&  startingNum1 % 2 == 1)  {
				parent2StartingDX =  - 1;
			}

			if (isCouple == false) {
				console.log("@line 2112 : colBnum:",colBnum);
				isHalfBC = ((ahnenIndexArrays[2][startingNum2 - 0 + parent2StartingDX] != ahnenIndexArrays[3][startingNum3 - 0 + parent2StartingDX]));
				if (ahnenIndexArrays[2][startingNum2 - 0 + parent2StartingDX] == -1 || (ahnenIndexArrays[3][startingNum3 - 0 + parent2StartingDX] == -1)) {
					isHalfBC = true;
				}				
				console.log("isHalfBC:", isHalfBC, "ahnNums:", ahnenIndexArrays[2][startingNum2 - 0 + parent2StartingDX] , ahnenIndexArrays[3][startingNum3 - 0 + parent2StartingDX] , "indexes:", startingNum2 - 0 + parent2StartingDX , startingNum3 - 0 + parent2StartingDX);
				if (colBnum == 2) {

					


					isHalfAB = ((ahnenIndexArrays[2][startingNum2 + parent2StartingDX] != ahnenIndexArrays[1][startingNum1 + parent2StartingDX]));
					isHalfAC = ((ahnenIndexArrays[1][startingNum1 + parent2StartingDX] != ahnenIndexArrays[3][startingNum3 + parent2StartingDX]));

					if (ahnenIndexArrays[2][startingNum2 - 0 + parent2StartingDX] == -1 || (ahnenIndexArrays[1][startingNum1 - 0 + parent2StartingDX] == -1)) {
						isHalfAB = true;
					}
					if (ahnenIndexArrays[1][startingNum1 - 0 + parent2StartingDX] == -1 || (ahnenIndexArrays[3][startingNum3 - 0 + parent2StartingDX] == -1)) {
						isHalfAC = true;
					}
					console.log("isHalfAB:", isHalfAB, "ahnNums:", ahnenIndexArrays[2][startingNum2 - 0 + parent2StartingDX] , ahnenIndexArrays[1][startingNum1 - 0 + parent2StartingDX] , "indexes:", startingNum2 - 0 + parent2StartingDX , startingNum1 - 0 + parent2StartingDX);
					console.log("isHalfAC:", isHalfAC, "ahnNums:", ahnenIndexArrays[1][startingNum1 - 0 + parent2StartingDX] , ahnenIndexArrays[3][startingNum3 - 0 + parent2StartingDX] , "indexes:", startingNum1 - 0 + parent2StartingDX , startingNum3 - 0 + parent2StartingDX);
				} else {
					isHalfAC = (ahnenIndexArrays[2][startingNum2 + parent2StartingDX] != ahnenIndexArrays[1][startingNum1 + parent2StartingDX]);
					isHalfAB = (ahnenIndexArrays[1][startingNum1 + parent2StartingDX] != ahnenIndexArrays[3][startingNum3 + parent2StartingDX]);
				}
			}
			console.log("starting 1,2,3 : ", startingNum1, startingNum2, startingNum3);
			console.log("startings inside ahnenIndexArrays : ", ahnenIndexArrays[1][startingNum1], ahnenIndexArrays[2][startingNum2], ahnenIndexArrays[3][startingNum3]);
			console.log("startings + 1 inside ahnenIndexArrays : ", ahnenIndexArrays[1][startingNum1 - 0 + parent2StartingDX], ahnenIndexArrays[2][startingNum2 - 0 + parent2StartingDX], ahnenIndexArrays[3][startingNum3 - 0 + parent2StartingDX]);
			console.log("startings + IsHalfs  : ", isHalfAB, isHalfBC, isHalfAC);


			thisGen = 0; 
			thisGenAB = 0; 
			thisGenBC = 0; 
			thisGenAC = 0; 
			thisRemovedAB = 0;
			thisRemovedBC = 0;
			thisRemovedAC = 0;
			addSiblingsRemovedNote = false;
			//alert(812);
			var isFirstTime = true;
			var leanFrom = '';

			 
			// alert("start List");
			if (ahnenIndexArrays[1][nextNum1] == ahnenIndexArrayB[nextNumB]) {
			  while (ahnenIndexArrays[1][nextNum1] == ahnenIndexArrayB[nextNumB]) {
				//alert ("A and B are siblings!");
				//alert(nextNum1 + " | " +nextNumB + " | " +nextNum3 );
				addSiblingsRemovedNote = true;

				// theList += "<TR><TD colspan=5>&nbsp;</TD></TR>";
				if (isFirstTime) {
					theList += "<TR><TD>&nbsp;</TD><TD align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=left valign=bottom><B>&LowerRightArrow;</B></TD></TR>";

					let parent2StartingNum = startingNum1 + 1;
					if (isCouple == false && startingNum1 % 2 == 1)  {
						parent2StartingNum = startingNum1 - 1;
					}
					console.log("ZEE place for ",{parent2StartingNum}, {startingNum1});
					theList += "<TR><TD>&nbsp;</TD><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrays[1][nextNum1]]) + " are:</b><BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1]]) + "&nbsp;&<BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[1][parent2StartingNum]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[1][parent2StartingNum]]) 
					+ "</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrayC[nextNumC]]) + " are:</b><BR>" ;
					
					if (nextNumC > 1) {
						theList +=  theNextBestFirstName( personArray[ahnenIndexArrayC[nextNumC*2 ]]) +  " " + theBestLastName (personArray[ahnenIndexArrayC[nextNumC*2 ]]) + "&nbsp;&<BR>" +  
							theNextBestFirstName( personArray[ahnenIndexArrayC[nextNumC*2  + 1]]) +  " " + theBestLastName (personArray[ahnenIndexArrayC[nextNumC*2  + 1]]) ;
					}	else {
						theList +=  theNextBestFirstName( personArray[ahnenIndexArrayC[1 - nextNumC ]]) +  " " + theBestLastName (personArray[ahnenIndexArrayC[1 - nextNumC ]]) + "&nbsp;&<BR>" +  
							theNextBestFirstName( personArray[ahnenIndexArrayC[1 - nextNumC  + 1]]) +  " " + theBestLastName (personArray[ahnenIndexArrayC[1 - nextNumC  + 1]]) ;
					}								
					theList += "</TD></TR>";
					isFirstTime = false;

				} else {
					theList += "<TR><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD></TR>";
				}
					
					
				leanFrom = 'left';
			
				startingNum1 = nextNum1;
				startingNum2 = nextNumB;
				startingNum3 = nextNumC;				

				// PERSON A&B are the same for the first level, so instead of PERSON -> relationship <- PERSON  do this:  space -> PERSON <- space
				if (startingNum1 == 1) {
					theList += "<TR><TD align=center>" +  "&nbsp;"  + "</TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1]])  + "</b>";
					theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
				} else {
					use4FirstName = theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) ;
					
					theList += "<TR><TD align=center>" +  "&nbsp;"  + "</TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  use4FirstName + " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1]])  + "</b>";
				}
				theList += "</TD>";
				// theList += "<TD align=center>" +  "&nbsp;"  + "</TD>";
			
				// add in PERSON C as per usual, starting with her/his relationship with the person in column B (which in this case is the same as A)
				if (startingNum3 > 0) {
					if (startingNum3 == 1) {
						Person3FullNameStarter = "<TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theNextBestFirstName( personArray[ahnenIndexArrayC[startingNum3]]) + " " + theBestLastName(personArray[ahnenIndexArrayC[startingNum3]]) + "</b>";

					} else {
						use4FirstName = theNextBestFirstName( personArray[ahnenIndexArrayC[startingNum3]]);
						
						Person3FullNameStarter = "<TD  align=center style='background-color:lime; padding:10px;' ><B>" + use4FirstName + " " + theBestLastName(personArray[ahnenIndexArrayC[startingNum3]]) + "</b>";
					}

				} else {
					if (startingNum3 == 0) {
						// we have no other WikiTree ancestors between the top MRCA and the DNAtester # 3, who must be an Alias
						Person3FullNameStarter = "<TD  align=center style='background-color:lime; padding:10px;' ><B>" +     theOriginalIDC + "'s<BR/>" + howGrandIsIt(numNonWikiTreersC - 1) + ancestorType +  "</b>";
						
						numNonWikiTreersC-= 1;

					} else {
						Person3FullNameStarter = "<TD  align=center style='background-color:white; padding:10px;' ><font color=blue>&uarr;</font>";
					}

				}
				// theList += "<TD align=center colspan=2>&larr; " +  relationshipAtGen(thisGenBC) + addBRandRemoved(thisRemovedBC) + " &rarr;</TD>" +  Person3FullNameStarter ;
				theList += "<TD align=center colspan=2><font color=blue>&larr; <I>" +  relationshipAtGenRemoved(thisGenBC,thisRemovedBC, isHalfBC) + "</I> &rarr;</font></TD>" +  Person3FullNameStarter ;
				if (startingNum3 == 1) {
					theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
				}
				theList += "</TD></TR>";


				nextNum1 = Math.floor (startingNum1 / 2);
				nextNumB = Math.floor (startingNum2 / 2);
				nextNumC = Math.floor (startingNum3 / 2);
				console.log("@line 2207 : nextNumC = ", nextNumC);

				thisGen++;
				// thisGenAB++;
				thisGenBC++;
				if (nextNumC == 0) {
					thisGenBC--;
					thisRemovedBC++;
				}
			  } // end while A & B are siblings 

			  // Separation between A & C will be the same as between B & C, since A and B are siblings
			  if (theRel3 == 0 || theRel3 == 1) {
			  	thisRemovedBC = 0;
			  	thisGenBC = 1;
			  }
			  thisGenAC = thisGenBC;
			  thisRemovedAC = thisRemovedBC;
			  let partnerDiff = 1; // difference between person in Tree and their spouse, ahnenTafelly speaking ... +1 = if the person is Male and partner is female (2N + 1)
			  if (startingNum1 % 2 == 1) {
			  	// BUT .... if the person in the tree is Female (odd ahnentafel # ), THEN, the diff is -1 (going backwards)
			  	partnerDiff = -1;
			  }

			  isHalfAB = ((ahnenIndexArrays[2][startingNum2 - 0 + partnerDiff] != ahnenIndexArrays[1][startingNum1 - 0 + partnerDiff]));
			  console.log("AFTER the JOINT ancestors, and A and B split: isHalfAB = ", isHalfAB, ahnenIndexArrays[2][startingNum2 - 0 + partnerDiff] , ahnenIndexArrays[1][startingNum1 - 0 + partnerDiff] , startingNum2 - 0 + partnerDiff, startingNum1 - 0 + partnerDiff , "Comparing:", ahnenIndexArrays[1][startingNum1 ], ahnenIndexArrays[2][startingNum2 ], personArray[ahnenIndexArrays[1][startingNum1 ]], personArray[ahnenIndexArrays[2][startingNum2 ]], {partnerDiff});


			} else if (nextNumB > 0 && (ahnenIndexArrayC[nextNumC] == ahnenIndexArrayB[nextNumB]) ) {
				// alert ("B and C are siblings! " + nextNumB + " / " + nextNumC);
			  while (ahnenIndexArrayC[nextNumC] == ahnenIndexArrayB[nextNumB] && nextNumB > 0 && nextNumC > 0) {
				addSiblingsRemovedNote = true;

				// theList += "<TR><TD colspan=5>&nbsp;</TD></TR>";
				if (isFirstTime) {
					let parent2StartingNum = startingNum1 + 1;
					if (isCouple == false && startingNum1 % 2 == 1)  {
						parent2StartingNum = startingNum1 - 1;
					}
					console.log("ZEE place for ",{parent2StartingNum}, {startingNum1});

					theList += "<TR><TD align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=left valign=bottom><B>&LowerRightArrow;</B></TD><TD>&nbsp;</TD></TR>";
					theList += "<TR><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrays[1][nextNum1]]) + " are:</b><BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1]]) + "&nbsp;&<BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[1][parent2StartingNum]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[1][parent2StartingNum]]) 
					+ "</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrayB[nextNumB]]) + " are:</b><BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrayB[nextNumB*2]]) +  " " + theBestLastName (personArray[ahnenIndexArrayB[nextNumB*2]]) + "&nbsp;&<BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrayB[nextNumB*2 + 1]]) +  " " + theBestLastName (personArray[ahnenIndexArrayB[nextNumB*2 + 1]]) 
					+ "</TD><TD>&nbsp;</TD></TR>";

					isFirstTime = false;
				} else {
					theList += "<TR><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD></TR>";
				}
					
				leanFrom = 'right';
				

				startingNum1 = nextNum1;
				startingNum2 = nextNumB;
				startingNum3 = nextNumC;				


				// add in PERSON A as per usual
				if (startingNum1 > 0) {
					if (startingNum1 == 1) {
						Person1FullNameStarter = "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + theBestLastName(personArray[ahnenIndexArrays[1][startingNum1]]) + "</b>";
					} else {
						console.log("line 1989 - startingNum1 = " + startingNum1);
						console.log(ahnenIndexArrays[1][startingNum1]);

						use4FirstName = theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) ;
						
						Person1FullNameStarter = "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" + use4FirstName + " " + theBestLastName(personArray[ahnenIndexArrays[1][startingNum1]]) + "</b>";

					}
				} else {
					Person1FullNameStarter =  "<TR><TD  align=center style='background-color:white; padding:10px;' ><font color=blue>&uarr;</font>";
				}
				theList +=  Person1FullNameStarter ;
				if (startingNum1 == 1) {
					theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
				}
				theList += "</TD>";
			
				
				// theList += "<TD align=center colspan=2>&larr; " +  relationshipAtGen(thisGenAB) + addBRandRemoved(thisRemovedAB) + " &rarr;</TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  personArray[ahnenIndexArrayB[startingNum2]].FirstName + " " + personArray[ahnenIndexArrayB[startingNum2]].LastNameAtBirth + "</b>";
				if (startingNum2 == 1) {
					theList += "<TD align=center colspan=2><font color=blue>&larr; <I> " +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theNextBestFirstName( personArray[ahnenIndexArrayB[startingNum2]]) + " " + theBestLastName(personArray[ahnenIndexArrayB[startingNum2]]) + "</b>";
						if (numNonWikiTreersB == 0 && numNonWikiTreersC == 0){
							theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
						}

				} else {

						console.log("line 2013 - startingNum2 = " + startingNum2);
						console.log(ahnenIndexArrayB[startingNum2]);
						
						if (startingNum2 < 1) {
							use4FirstName = theOriginalIDB;
							use4LastName = '';
						} else {
							use4FirstName = theNextBestFirstName( personArray[ahnenIndexArrayB[startingNum2]] );
							use4LastName = theBestLastName( personArray[ahnenIndexArrayB[startingNum2]] );
						}

						

					theList += "<TD align=center colspan=2><font color=blue>&larr; <I> " +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  use4FirstName + " " + use4LastName + "</b>";

				}
				theList += "</TD>&nbsp;</TD></TR>";

			
				nextNum1 = Math.floor (startingNum1 / 2);
				nextNumB = Math.floor (startingNum2 / 2);
				nextNumC = Math.floor (startingNum3 / 2);
				console.log("@line 2306 : nextNumC = ", nextNumC);

				thisGen++;
				thisGenAB++;
				// thisGenBC++;
				if (nextNum1 == 0 ) {
					thisGenAB--;
					thisRemovedAB++;
				}
				
			  }

			  // Separation between A and C will be the same as between A and B, since B and C are siblings

			  thisGenAC = thisGenAB;
			  thisRemovedAC = thisRemovedAB;
			}

		  
			console.log ("thisGen / AB / BC : " + thisGen + " " + thisGenAB + " " + thisGenBC);
			var tooTipsyToTriangulate = false;
			if (thisGen > 2 &&  theDNAconfType == 'Tri') {
				tooTipsyToTriangulate = true;
			}

			while (nextNum1 > 0 || (nextNumB + numNonWikiTreersB) > 0 || (nextNumC + numNonWikiTreersC) > 0) {
				// alert(nextNum1 + " | " +nextNumB + " | " +nextNumC );
				if (isFirstTime) {

					let parent2StartingDX =  + 1;
					if (isCouple == false &&  startingNum1 % 2 == 1)  {
						parent2StartingDX =  - 1;
					}
					console.log("ZEE 7152 place for ",{parent2StartingDX}, {startingNum1});
				theList += "<TR><TD align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD><TD align=left valign=bottom><B>&LowerRightArrow;</B></TD></TR>";

				theList += "<TR><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrays[1][nextNum1]]) + " are:</b><BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1]]) + "&nbsp;&<BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1 + parent2StartingDX]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[1][startingNum1 + parent2StartingDX]]) 

					+ "</TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrays[2][nextNum2]]) + " are:</b><BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[2][startingNum2]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[2][startingNum2]]) + "&nbsp;&<BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[2][startingNum2 + parent2StartingDX]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[2][startingNum2 + parent2StartingDX]]) 

					+ "</TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>Parents of "+  theNextBestFirstName( personArray[ahnenIndexArrays[3][nextNum3]]) + " are:</b><BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[3][startingNum3]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[3][startingNum3]]) + "&nbsp;&<BR>" 
					+  theNextBestFirstName( personArray[ahnenIndexArrays[3][startingNum3 + parent2StartingDX]]) +  " " + theBestLastName (personArray[ahnenIndexArrays[3][startingNum3 + parent2StartingDX]]) 
					+ "</TD></TR>";


					if ( personArray[ahnenIndexArrays[1][startingNum1 + parent2StartingDX]] ==  personArray[ahnenIndexArrays[2][startingNum2 + parent2StartingDX]] && personArray[ahnenIndexArrays[2][startingNum2 + parent2StartingDX]] == personArray[ahnenIndexArrays[3][startingNum3 + parent2StartingDX]] ) {
						// theList+= "<TR><TD align=center colspan = 5>ALL CLEAR HERE - NOTHING TO SEE HERE - MOVE ALONG NOW</td></tr>";						
					} else {
						// theList+= "<TR><TD align=center colspan = 5>DANGER DANGER WILL ROBINSON!  Need to FIGURE out if we're dealing with a SINGLETON !!!</td></tr>";						
					}
					isFirstTime = false;
				} else if (leanFrom == 'left'){
					theList += "<TR><TD align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD>&nbsp;</TD><TD align=left valign=bottom><B>&LowerRightArrow;</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD></TR>";
					leanFrom = '';
				} else if (leanFrom == 'right'){
					theList += "<TR><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD><TD align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD>&nbsp;</TD><TD align=left valign=bottom><B>&LowerRightArrow;</B></TD></TR>";
					leanFrom = '';
				} else {
					theList += "<TR><TD align=center valign=bottom><B>" 
					
					if (nextNum1 > 0) {theList += "&darr;" } else { theList += "&nbsp;";}
					theList += "</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>" 

					if (nextNumB + numNonWikiTreersB > 0) {theList += "&darr;" } else { theList += "&nbsp;";}
					theList += "</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>" 

					if (nextNumC + numNonWikiTreersC > 0) {theList += "&darr;" } else { theList += "&nbsp;";}
					theList += "</B></TD></TR>";
				}

				// SETUP the STARTING NUMs ... and DETERMINE if the GENERATIONS / NUM REMOVEDS needs to be adjusted.				
				startingNum1 = nextNum1;
				startingNum2 = nextNumB;
				startingNum3 = nextNumC;
				
				let doUpArrowFor1 = "";
				let doUpArrowFor3 = "";
				if (startingNum1 == 0 || (startingNum3 + numNonWikiTreersC) == 0) {
					thisGenAC--;
					thisRemovedAC++;
					if (startingNum1 == 0) {
						doUpArrowFor1 = "&uarr;<BR>";
					} else if ( (startingNum3 + numNonWikiTreersC) == 0) {
						doUpArrowFor3 = "&uarr;<BR>";
					}
				}

				if (startingNum1 == 0 || (startingNum2 + numNonWikiTreersB) == 0) {
					thisGenAB--;
					thisRemovedAB++;
				}
				if (startingNum1 == 0 && (startingNum2 + numNonWikiTreersB) == 0) {
					thisRemovedAB = 999;
				}
				if ((startingNum2 + numNonWikiTreersB) == 0 || (startingNum3 + numNonWikiTreersC) == 0) {
					thisGenBC--;
					thisRemovedBC++;
				}
				if ((startingNum2 + numNonWikiTreersB) == 0 && (startingNum3 + numNonWikiTreersC) == 0) {
					thisRemovedBC = 999;
				}


				// ADD the BLUE RELATIONSHIP DESCRIPTIONS BETWEEN the 1st and 3rd columns
				if (startingNum1 > 0 || (startingNum3 + numNonWikiTreersC) > 0) {
					theList += "<TR><TD align=left valign=bottom><font color=blue>" + doUpArrowFor1 + relationshipAtGenRemoved(thisGenAC , thisRemovedAC, isHalfAC) + "&nbsp;&rarr;</font></TD><TD>&nbsp;</TD><TD align=center valign=bottom>&nbsp;</TD><TD>&nbsp;</TD><TD align=right valign=bottom><font color=blue>" + doUpArrowFor3 + "&larr;&nbsp;" +relationshipAtGenRemoved(thisGenAC , thisRemovedAC, isHalfAC) + "</font></TD></TR>";
				}


				console.log("Blue Cousin Calc: ", "thisGenAC = " + thisGenAC, "thisRemovedAC = " + thisRemovedAC, "startingNum1 = " + startingNum1, "startingNum3 = " + startingNum3, "numNonWikiTreersC = " + numNonWikiTreersC , relationshipAtGenRemoved(thisGenAC , thisRemovedAC, isHalfAC));

				thisGenAC++;
				



				if (startingNum1 > 0) {
					if (startingNum1 == 1) {
						theList += "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameCurrent  + "</b>";
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {

						use4FirstName = theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]);
						
						theList += "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  use4FirstName + " " + theBestLastName(personArray[ahnenIndexArrays[1][startingNum1]])  + "</b>";
					}
					theList += "</TD>";
				} else {
					if (startingNum2 + numNonWikiTreersB > 0) {
						theList += "<TR><TD align=center>" +  "<font color=red>&uarr;</font><font color=white>&larr;</font><BR/><font color=red>&rarr;</font>"  + "</TD>";
					} else {
						theList += "<TR><TD align=center>" +  "&nbsp;"  + "</TD>";
					}
				}

				if (startingNum2 > 0) {
					if (startingNum2 == 1 && numNonWikiTreersB == 0) {
						theList += "<TD align=center><font color=red>&larr; <I>" +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrayB[startingNum2]]) + " " + personArray[ahnenIndexArrayB[startingNum2]].LastNameCurrent + "</b>";
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {

						use4FirstName = theNextBestFirstName(personArray[ahnenIndexArrayB[startingNum2]]);
						
						theList += "<TD align=center><font color=red>&larr; <I>" +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  use4FirstName + " " + theBestLastName(personArray[ahnenIndexArrayB[startingNum2]]) + "</b>";
					}

					theList += "</TD>";
				} else if (startingNum2 == 0 && numNonWikiTreersB > 0) {
					if (numNonWikiTreersB == 1) {
						theList += "<TD align=center><font color=red>&larr; <I>" +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +   theOriginalIDB + "</b>";		
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {

						theList += "<TD align=center><font color=red>&larr; <I>" +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' >" +    theOriginalIDB + "'s<BR/>" + howGrandIsIt(numNonWikiTreersB - 1) + ancestorType ;		
					}			
						
					theList += "</TD>";
					numNonWikiTreersB -= 1;
				
				} else if (thisRemovedAB == 999){
					theList += "<TD align=center>&nbsp; </TD><TD align=center>&nbsp;</TD>";
				} else {
					theList += "<TD align=center><font color=red>&larr; <I>" +  relationshipAtGenRemoved(thisGenAB , thisRemovedAB, isHalfAB) + "</I> &rarr;</font></TD><TD align=center><font color=red>&uarr;</font><font color=white>&larr;</font><BR/><font color=red>&rarr;</font></TD>";
				}

				if (startingNum3 > 0) {
					if (startingNum3 == 1 && numNonWikiTreersC == 0) {
						theList += "<TD align=center><font color=green>&larr; <I>" +  relationshipAtGenRemoved(thisGenBC , thisRemovedBC, isHalfBC) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrayC[startingNum3]]) + " " + personArray[ahnenIndexArrayC[startingNum3]].LastNameCurrent + "</b>";
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {
						use4FirstName = theNextBestFirstName( personArray[ahnenIndexArrayC[startingNum3]]) ;
						
						theList += "<TD align=center><font color=green>&larr; <I>" +  relationshipAtGenRemoved(thisGenBC , thisRemovedBC, isHalfBC) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  use4FirstName + " " + theBestLastName(personArray[ahnenIndexArrayC[startingNum3]]) + "</b>";

					}
					theList += "</TD></TR>";
					
				} else if (startingNum3 == 0 && numNonWikiTreersC > 0) {
					if (numNonWikiTreersC == 1) {
						theList += "<TD align=center><font color=green>&larr; <I>" +  relationshipAtGenRemoved(thisGenBC , thisRemovedBC, isHalfBC) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' ><B>" +   theOriginalIDC + "</b>";		
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {
						theList += "<TD align=center><font color=green>&larr; <I>" +  relationshipAtGenRemoved(thisGenBC , thisRemovedBC, isHalfBC) + "</I> &rarr;</font></TD><TD  align=center style='background-color:lime; padding:10px;' >" +    theOriginalIDC + "'s<BR/>" + howGrandIsIt(numNonWikiTreersC - 1) + ancestorType;
					}			
						
					theList += "</TD></TR>";
					numNonWikiTreersC -= 1;
				
				} else if (thisRemovedBC == 999){
					theList += "<TD align=center>&nbsp; </TD><TD align=center>&nbsp;</TD></TR>";
				} else {
					theList += "<TD align=center><font color=green>&larr; <I>" +  relationshipAtGenRemoved(thisGenBC , thisRemovedBC, isHalfBC) + "</I> &rarr;</font></TD><TD align=center><font color=white>&larr;</font><font color=green>&uarr;<BR/>&larr;</font></TD></TR>";
				}
				nextNum1 = Math.floor (startingNum1 / 2);
				nextNumB = Math.floor (startingNum2 / 2);
				nextNumC = Math.floor (startingNum3 / 2);
				console.log("@line 2500 : nextNumC = ", nextNumC);

				thisGen++;
				thisGenAB++;
				thisGenBC++;
			}
			theList += "</table>";

			if (addSiblingsRemovedNote == true) {
				if(theList.indexOf('nibling') > 0 || theList.indexOf('pibling') > 0) {
					theList += "<BR><HR><I>nibling / pibling =  niece/nephew  / aunt/uncle combo (parent sibling = pibling)<BR>grand nibling/pibling = grand niece / grand nephew / great aunt / great uncle<BR>  etc..</I>";
				}
			}

			if (tooTipsyToTriangulate){
				theList += '<span id=TreeUnsteady style="display:block; text-align: left; background-color: yellow;"><P align=center><B>TRIANGULATION TREE TOO TIPSY</B></P>								This mini-tree is not stable enough to verify the triangulated connection.<BR><BR>The ideal connection for a triangulated confirmation is from DNA testers each descended from unique children of the Most Recent Common Ancestor(s).<BR><BR>If not all children of the MRCA have DNA descendants, as long as each DNA tester descends from a unique grandchild, that will also be acceptable.<BR><BR><BR>HOWEVER, in this tree, the distance between the MRCA and the branching out into distinct lines is too great for the triangulation to be confirmed.</span>';
			}
			//alert("End MiniTree Trio");
			return theList;
		}


				
		function doMiniTreeDuo () {
			var startingNum1 = 0;			
			var startingNum2 = 0;			
			var theList = "";
			var numNonWikiTreers = numGensFromWikiTreeID2toCousin;
			var isHalf = false;
			
			
			startingNum1 = theAncsAhnNum;
			startingNum2 = theAncsAhnNum2;				
			relCA2P3 = '';

			if (numGensFromWikiTreeID2toCousin > 0) {
				// alert ("Need some extra GENS here because  = " + numGensFromWikiTreeID2toCousin);
			} else {
				// alert ("Not sure - but numGensFromWikiTreeID2toCousin  = " + numGensFromWikiTreeID2toCousin);
			}
			
			console.log("IS COUPLE in DO MINI TREE DUO : ", isCouple);
			if (isCouple == false && (theDNAconfType == 1 || theDNAconfType == 3 || theDNAconfType == 'x')) {
				document.getElementById('TreeCAsub1Half').textContent = "half ";
				document.getElementById('TreeCAsub2Half').textContent = "half ";
				document.getElementById('TreeCAsub3Half').textContent = "half ";
				document.getElementById('TreeCAsub4Half').textContent = "half ";
				document.getElementById('TreeCAsub5Half').textContent = "half ";
				document.getElementById('HalfRelsTooFarExamples').textContent = ",<BR>half 3rd cousins,<BR>half 2nd cousins twice or more times removed,<BR>half 1st cousins 3x or more removed";
				document.getElementById('HalfRelsTooFarExamples2').textContent = ",<BR>half 3rd cousins,<BR>half 2nd cousins twice or more times removed,<BR>half 1st cousins 3x or more removed";

			} else {
				document.getElementById('TreeCAsub1Half').textContent = "";
				document.getElementById('TreeCAsub2Half').textContent = "";
				document.getElementById('TreeCAsub3Half').textContent = "";
				document.getElementById('TreeCAsub4Half').textContent = "";
				document.getElementById('TreeCAsub5Half').textContent = "";
				document.getElementById('HalfRelsTooFarExamples').textContent = "";
				document.getElementById('HalfRelsTooFarExamples2').textContent = "";
			}
			// document.getElementById('HalfRelsTooFarExamples2').textContent = document.getElementById('HalfRelsTooFarExamples').textContent ;

			if (theDNAconfType == 'y' || theDNAconfType == 'mt') {
				isCouple = false;
			}

			MRCAbase = 'parent';
			if (isCouple) {
				addS4Couple = "S";
				MRCAbase = 'parents';
				ancestorType = 'parent';
			} else {
				addS4Couple = "";
				if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Male') {
					MRCAbase = 'father';
				} else if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Female') {
					MRCAbase = 'mother';
				}
				ancestorType = MRCAbase;
			}	

			theRel = Math.floor(Math.log2(theAncsAhnNum));			
			theRel2 = Math.floor(Math.log2(theAncsAhnNum2));			
			MRCArelationship = theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + "'s " + howGrandIsIt(theRel) + MRCAbase + "<BR><I>and</I><BR>";
			if (numNonWikiTreers > 0) {
				MRCArelationship +=   theOriginalID2 + "'s " + howGrandIsIt( 2 + 1*theRel2 + 1*numNonWikiTreers - 2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( 2 + 1*theRel2 + 1*numNonWikiTreers - 2) + MRCAbase ;
			} else {
				MRCArelationship += theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]) + "'s " + howGrandIsIt(theRel2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( theRel2) + MRCAbase ;
			}

			let yellowCommonAncestor = "COMMON ANCESTOR" + addS4Couple;
			if (theDNAconfType == 'y' ) {
				yellowCommonAncestor = "COMMON PATERNAL ANCESTOR"
			} else if ( theDNAconfType == 'mt') {
				yellowCommonAncestor = "COMMON MATERNAL ANCESTOR"
			}



			theList =  "<table id=DUOminiTree align=center><TR><TD colspan=3 align=center><table border=0 align=center><tr><td> &nbsp;</td><td  align=center style='background-color:lime; padding:10px;'><span style=background-color:yellow;>" + yellowCommonAncestor + "</span><BR><B>" +  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameAtBirth ;
			if (isCouple) {
				theList += "<BR/>&<BR/>" + theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1+1]]) + " " + theNextBestLastName(personArray[ahnenIndexArrays[1][startingNum1+1]])   ;
			}
			theList += "</b></td><td >&nbsp;</td></tr>"
			theList += "<tr><td colspan=3 align=center>" + MRCArelationship + "</td></tr>";
			theList += "</table> </td></tr>" ;

			nextNum1 = Math.floor (startingNum1 / 2);
			nextNum2 = Math.floor (startingNum2 / 2);
			thisGen = 0;
			thisRemoved = 0;

			var isFirstTime = true;
			var doIncludeNiblingNote = false;

			while (nextNum1 > 0 || (nextNum2 + numNonWikiTreers) > 0) {
				//alert(nextNum1 + "|" + nextNum2);
				// theList += "<TR><TD colspan=3>&nbsp;</TD></TR>";
				if (isFirstTime) {
					theList += "<TR><TD align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD>&nbsp;</TD><TD align=left valign=bottom><B>&LowerRightArrow;</B></TD></TR>";
					isFirstTime = false;
				} else {
					if (nextNum1 > 0 && nextNum2 + numNonWikiTreers > 0) {
						theList += "<TR><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD></TR>";
					} else if (nextNum1 > 0 ) {
						theList += "<TR><TD align=center valign=bottom><B>&darr;</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&nbsp;</B></TD></TR>";
					} else if (nextNum2 + numNonWikiTreers > 0) {
						theList += "<TR><TD align=center valign=bottom><B>&nbsp;</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&darr;</B></TD></TR>";
					} else  {
						theList += "<TR><TD align=center valign=bottom><B>&nbsp;</B></TD><TD>&nbsp;</TD><TD align=center valign=bottom><B>&nbsp;</B></TD></TR>";
					}

				}

				startingNum1 = nextNum1;
				startingNum2 = nextNum2;
				if (startingNum1 == 0 || (startingNum2 + numNonWikiTreers) == 0) {
					thisGen--;
					thisRemoved++;
				}
				if (startingNum1 > 0) {
					if (startingNum1 == 1) {
						theList += "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameCurrent  + "</b>";
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {
						theList += "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameAtBirth  + "</b>";

					}
					theList += "</TD>";
				} else {
					theList += "<TR><TD  align=center><font color=blue>&uarr;</font></TD>";
				}

				let thisRelationshipAtGenRemoved = relationshipAtGenRemoved(thisGen, thisRemoved, isHalf) ;
				if (theDNAconfType == 'y') {
					thisRelationshipAtGenRemoved = thisRelationshipAtGenRemoved.replace("nibling","nephew").replace("pibling","uncle");
				}

				theList += "<TD align=center><font color=blue>&larr; <I>" +  thisRelationshipAtGenRemoved;
				// theList += "<TD align=center>&larr; " +  relationshipAtGen(thisGen) ;
				// if (thisRemoved > 0) {
				// 	theList += "<br>" + thisRemoved + "x removed";						
				if (thisRelationshipAtGenRemoved.indexOf("nibling") > -1) {
					doIncludeNiblingNote = true;
				}
				// }
				theList += "</I> &rarr;</font></TD>";
					
				if (startingNum2 > 0) {
					if (startingNum2 == 1 && numNonWikiTreers == 0) {
						theList += "<TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[2][startingNum2]]) + " " + personArray[ahnenIndexArrays[2][startingNum2]].LastNameCurrent + "</b>";
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {
						theList += "<TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[2][startingNum2]]) + " " + personArray[ahnenIndexArrays[2][startingNum2]].LastNameAtBirth + "</b>";

					}
					theList += "</TD></TR>";
				} else if (startingNum2 == 0 && numNonWikiTreers > 0) {
					if (numNonWikiTreers == 1) {
						theList += "<TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theOriginalID2 + "</b>";		
						theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
					} else {
						theList += "<TD  align=center style='background-color:lime; padding:10px;' >" +  theOriginalID2 + "'s<BR/>" + howGrandIsIt(numNonWikiTreers - 1) + ancestorType ;		
						
					}			
					theList += "</TD></TR>";
					numNonWikiTreers -= 1;

				} else {
					theList += "<TD align=center><font color=blue>&uarr;</font><TD></TR>";
				}
				nextNum1 = Math.floor (startingNum1 / 2);
				nextNum2 = Math.floor (startingNum2 / 2);
				thisGen++;
			}
			
			if (doIncludeNiblingNote) {
				theList += "<tr><td colspan=3 align=center>&nbsp;<BR>&nbsp;<BR><I>nibling = niece/nephew<br>pibling = aunt/uncle (<B>p</b>arent's s<B>ibling</B>)</I></td></tr>";
			}
			theList += "</table>";

			return theList;
		}

		function doMiniTreeUni () {
			var startingNum1 = 0;			
			var startingNum2 = 0;			
			var theList = "";
			
			startingNum1 = theAncsAhnNum;
			startingNum2 = theAncsAhnNum2;				

			MRCAbase = 'parent';
			if (isCouple) {
				addS4Couple = "S";
				MRCAbase = 'parents';
			} else {
				addS4Couple = "";
				if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Male') {
					MRCAbase = 'father';
				} else if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Female') {
					MRCAbase = 'mother';
				}
			}	

			// alert("AhnNums " + theAncsAhnNum + "  & " + theAncsAhnNum2);

			theRel = Math.floor(Math.log2(theAncsAhnNum));			
			theRel2 = Math.floor(Math.log2(theAncsAhnNum2));		
			if (theAncsAhnNum > 1){	
				MRCArelationship = theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + "'s " + howGrandIsIt(theRel) + MRCAbase  ;
			} else if (theAncsAhnNum2 > 1){	
				MRCArelationship = theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]) + "'s " + howGrandIsIt(theRel2) + MRCAbase ;
			}

			theList =  "<table  id=UNIminiTree align=center><TR><TD colspan=1 align=center><table border=0 align=center><tr><td> &nbsp;</td><td  align=center style='background-color:lime; padding:10px;'><span style=background-color:yellow;>COMMON ANCESTOR" + addS4Couple + "</span><BR><B>" +  theNextBestFirstName(personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameAtBirth ;
			whichMiniTree2Use = "UNIminiTree";

			if (isCouple) {
				theList += "<BR/>&<BR/>" + theNextBestFirstName(personArray[ahnenIndexArrays[1][startingNum1+1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1+1]].LastNameAtBirth   ;
			}
			theList += "</b></td><td >&nbsp;</td></tr>"
			theList += "<tr><td colspan=3 align=center>" + MRCArelationship + "</td></tr>";
			theList += "</table> </td></tr>" ;

			nextNum1 = Math.floor (startingNum1 / 2);
			nextNum2 = Math.floor (startingNum2 / 2);
			thisGen = 0;
			thisRemoved = 0;
			

			while (nextNum1 > 0 || nextNum2 > 0) {
				// alert(nextNum);
				theList += "<TR><TD colspan=1>&nbsp;</TD></TR>";
				startingNum1 = nextNum1;
				startingNum2 = nextNum2;
				if (startingNum1 == 0 || startingNum2 == 0) {
					thisGen--;
					thisRemoved++;
				}
				if (theAncsAhnNum > 1){	
					if (startingNum1 > 0) {
						if (startingNum1 == 1) {
							theList += "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameCurrent + "</b>";
							theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
						} else {
							theList += "<TR><TD  align=center style='background-color:lime; padding:10px;' ><B>" +  theNextBestFirstName( personArray[ahnenIndexArrays[1][startingNum1]]) + " " + personArray[ahnenIndexArrays[1][startingNum1]].LastNameAtBirth  + "</b>";

						}
						theList += "</TD></TR>";
					} 
				} 

				
				if (theAncsAhnNum2 > 1){		
					if (startingNum2 > 0) {
						if (startingNum2 == 1) {
							theList += "<TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[2][startingNum2]]) + " " + personArray[ahnenIndexArrays[2][startingNum2]].LastNameCurrent + "</b>";
							theList += "<br><span style=background-color:yellow;>DNA test taker</span>";
						} else {
							theList += "<TD  align=center style='background-color:lime; padding:10px;' ><B>" + theNextBestFirstName( personArray[ahnenIndexArrays[2][startingNum2]]) + " " + personArray[ahnenIndexArrays[2][startingNum2]].LastNameAtBirth + "</b>";

						}
						theList += "</TD></TR>";
					}
				}

				nextNum1 = Math.floor (startingNum1 / 2);
				nextNum2 = Math.floor (startingNum2 / 2);
				thisGen++;
			}
			theList += "</table>";
			return theList;
		}

		function createListFromMRCAdown (tbl = 1) {
			var startingNum = 0;
			var theArrayToUse = [];
			var theList = "";
			if (tbl == 1) {
				startingNum = theAncsAhnNum;
				theArrayToUse = ahnenIndexArrays[1];
			} else if (tbl == 2) {
				startingNum = theAncsAhnNum2;
				theArrayToUse = ahnenIndexArrays[2];
			} else if (tbl == 3) {
				startingNum = theAncsAhnNum3;
				theArrayToUse = ahnenIndexArrays[3];
			}
			// alert ("createListFromMRCAdown using tbl: " + tbl + " & startingNum: " + startingNum);
			if (!personArray[theArrayToUse[startingNum]]) {
				// alert ("person " + startingNum + " may not exist!");
			}

			if (personArray[theArrayToUse[startingNum]].FirstName == undefined) {
				if (personArray[theArrayToUse[startingNum]].RealName == undefined) {
					theList =   "Private " + personArray[theArrayToUse[startingNum]].LastNameCurrent ;
				} else 	{
					theList = theNextBestFirstName( personArray[theArrayToUse[startingNum]]) + " " + personArray[theArrayToUse[startingNum]].LastNameCurrent ;
				}

			} else {

				theList =  theNextBestFirstName( personArray[theArrayToUse[startingNum]]) + " " + personArray[theArrayToUse[startingNum]].LastNameAtBirth ;
			}

			// alert (theList);
			// alert ("isCouple "+ isCouple);

			if (isCouple && personArray[theArrayToUse[startingNum+1]] ) {
				if (personArray[theArrayToUse[startingNum]].FirstName == undefined) {
					if (personArray[theArrayToUse[startingNum]].RealName == undefined) {
						theList += "<BR/>+<BR/>" +   "Private " + personArray[theArrayToUse[startingNum+1]].LastNameCurrent + "<BR>&darr;" ;
					} else 	{
						theList += "<BR/>+<BR/>" + theNextBestFirstName( personArray[theArrayToUse[startingNum+1]]) + " " + personArray[theArrayToUse[startingNum+1]].LastNameCurrent + "<BR>&darr;" ;
					}

				} else {

					theList += "<BR/>+<BR/>" + theNextBestFirstName( personArray[theArrayToUse[startingNum+1]]) + " " + personArray[theArrayToUse[startingNum+1]].LastNameAtBirth + "<BR>&darr;" ;
				}

				// theList += "<BR/>+<BR/>" + personArray[theArrayToUse[startingNum+1]].FirstName + " " + personArray[theArrayToUse[startingNum+1]].LastNameAtBirth + "<BR>&darr;" ;
			}
			nextNum = Math.floor (startingNum / 2);
			// alert ("nextNum : " + nextNum);
			while (nextNum > 0) {
				// alert(nextNum);
				let BOLDcode = '';
				let UNBOLDcode = '';
				startingNum = nextNum;
				if (nextNum == 1) {
					BOLDcode = '<B>';
					UNBOLDcode = '</B>';
				}
				// theList += "<BR/>" +  personArray[theArrayToUse[startingNum]].FirstName + " " + personArray[theArrayToUse[startingNum]].LastNameAtBirth ;

				if (personArray[theArrayToUse[startingNum]].FirstName == undefined) {
					if (personArray[theArrayToUse[startingNum]].RealName == undefined) {
						theList += "<BR/>" +  BOLDcode + "Private " + personArray[theArrayToUse[startingNum]].LastNameCurrent + UNBOLDcode;
					} else 	{
						theList += "<BR/>" +  BOLDcode + theNextBestFirstName( personArray[theArrayToUse[startingNum]]) + " " + personArray[theArrayToUse[startingNum]].LastNameCurrent + UNBOLDcode;
					}

				} else {

					theList += "<BR/>" +  BOLDcode + theNextBestFirstName( personArray[theArrayToUse[startingNum]]) + " " + personArray[theArrayToUse[startingNum]].LastNameAtBirth + UNBOLDcode;
				}

				nextNum = Math.floor (startingNum / 2);
			}
			// alert(theList);
			return theList;
		}

		function followUpNextSetOfPeeps(gen = 1, tbl = 1) {
			// console.log("following up " + gen + " ; " + tbl);
			var listOfPeepsIDs = "";
			for (var i = 0; i < nextSetOfPeeps.length; i++) {
				if (i>0) {
					listOfPeepsIDs += ",";
				}
				listOfPeepsIDs += nextSetOfPeeps[i];
			}

			if (listOfPeepsIDs == '')  {
				// alert ("WARNING WARNING _ DANGER WILL ROBINSON - NO PEEPS !!!");
				// alert("All done @ gen " + gen  + " at table # " + tbl + " in followUpNextSetOfPeeps ");
							if (tbl==2){
								populateAhnenTable(personArray,  theProfilePerson2.Id , 1, 2);
							} else if (tbl == 3) {
								populateAhnenTable(personArray,  theProfilePerson3.Id , 1, 3);
							} else {
								populateAhnenTable(personArray,  theProfilePerson.Id , 1, 1);
							}

							// alert("Starting ALL PEEPS for tbl # " + tbl);
							reportAllPeeps();
							// alert("Done ALL PEEPS ");
							document.getElementById('FormDIV1contacting').style.display = 'none';

							if (theDNAconfType == 3 && tbl == 1) {
								// loadTriInfo2();
							} else if (tbl == 1) {
								loadComplexInfo2();
							} else if (tbl == 2 && theDNAconfType == 'x' && theID3 > '') {
								loadComplexInfo3();
							} 
			} else {
				console.log("following up " + gen + " ; " + tbl, listOfPeepsIDs);
			var request = $.ajax({
				url: API_URL,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				// data: { 'action': 'getRelatives', 'keys': listOfPeepsIDs , 'fields': "Id, Name, FirstName, LastNameAtBirth, Father, Mother,BioFather, BioMother,Gender,DataStatus", 'format': 'json' },
				data: { 'action': 'getPeople', 'appId':'DNAconf',  'keys': listOfPeepsIDs , 'fields': "Id,Name,FirstName,LastNameAtBirth,BirthDate,DeathDate,Father,Mother,BioFather,BioMother,Gender,DataStatus", 'format': 'json' },

				// On successful POST return, check our data. Note from that data whether the login itself was
				// successful (setting session cookies if so). Call the user callback function when done.
				success: function(data) { 
					// alert("Success - in Follow Up function!");						 
					var theJason = JSON.stringify(data);
					console.log("STATUS: " , data[0].status);
					// alert ("did the jason");
					// alert(theJason);
					if (data[0]) {
						// alert ("There is data x " + data[0].items.length);						
						currentSetOfPeeps = [];
						for (var i  in  data[0].people) {
							// if ( data[0].items[i].person.Name == "Leger-743") { alert ("Charles Alert !!!");}
							if (data[0].people[i].Id  < 0) {
								// alert ("NEGATIVE!!! : " + data[0].items[i].person.Id );
							}

							personArray[ data[0].people[i].Id ] = data[0].people[i];
							currentSetOfPeeps.push(data[0].people[i].Id );
							// alert(data[0].items[i].person.FirstName );
						}
						

						updateNextSetOfPeeps();
						
			 
			 			if (gen <  maxFollowupNumOfGens && nextSetOfPeeps.length > 0) {
							followUpNextSetOfPeeps(gen + 1, tbl);
						} else {
							// alert("All done @ gen " + gen  + " at table # " + tbl + " in followUpNextSetOfPeeps ");
							if (tbl==2){
								populateAhnenTable(personArray,  theProfilePerson2.Id , 1, 2);
							} else if (tbl == 3) {
								populateAhnenTable(personArray,  theProfilePerson3.Id , 1, 3);
							} else {
								populateAhnenTable(personArray,  theProfilePerson.Id , 1, 1);
							}

							// alert("Starting ALL PEEPS for tbl # " + tbl);
							reportAllPeeps();
							// alert("Done ALL PEEPS ");
							document.getElementById('FormDIV1contacting').style.display = 'none';

							if (theDNAconfType == 3 && tbl == 1) {
								// loadTriInfo2();
							} else if (tbl == 1) {
								loadComplexInfo2();
							} else if (tbl == 2 && theDNAconfType == 'x' && theID3 > '') {
								// alert ("You REALLY should check out loadComplexInfo3");
								loadComplexInfo3();
							} 

							
						}
			



					}

				},

				// On failed POST/server error, act like a failed login.
				error: function(xhr, status) { 
					alert('OOPS - something wrong ! - ' + status);
				}
			});  // end var request";
		 }
		}

		
		function chooseAlternateWikiTreeID4Tester(whichFunction, whichIDnum, whichID, statusMsg) {
			console.log("function chooseAlternateWikiTreeID4Tester:",whichFunction, whichIDnum, whichID, statusMsg);
			whereToGoAfterChoosingAlternateWikiTreeID = whichFunction;
			whichIDnumForAlternateWikiTreeID = whichIDnum
			document.getElementById('FormDIV1err').style.display = 'block';
			if (whichFunction == 3 || whichFunction == "Tri") {
				document.getElementById('Scenario2TABLE').style.display = 'none';
				document.getElementById('Scenario2SPAN').style.display = 'none';
			} else {
				document.getElementById('Scenario2TABLE').style.display = 'block';
				document.getElementById('Scenario2SPAN').style.display = 'block';
			}

			

			var theMsg = "<h2>Problem trying to retrieve info for <br/><B>" + whichID + "</B></h2><h3>" + statusMsg + "</h3>";
			if (statusMsg == "Permission denied" || statusMsg.indexOf("Permission denied") > -1) {
				theMsg += "WikiTree Profile is Private, unable to access.";
			} else if (statusMsg == "Illegal WikiTree ID") {
				theMsg += "Not a valid WikiTree ID (format: Smith-12345)";
			} else if (statusMsg == "Invalid user") {
				theMsg += "Not a valid WikiTree ID (probably typo)";
			}

			dropDownOptions = "<select id=Div1errTTaRel2Anc1 name=Div1errTTaRel2Anc1><option value=0>Choose relationship:</option><option value=0>Self</<option><option value=1>Parent</option><option value=2>Grandparent</option><option value=3>Great Grandparent</option>"; //<option>2x Great Grandparent</option><option>3x Great Grandparent</option><option>4x Great Grandparent</option><option>5x Great Grandparent</option><option>6x Great Grandparent</option><option>7x Great Grandparent</option><option>8x Great Grandparent</option><option>9x Great Grandparent</option><option>10x Great Grandparent</option>";
			if (theDNAconfType == 'y') {
				dropDownOptions = "<select id=Div1errTTaRel2Anc1 name=Div1errTTaRel2Anc1><option value=0>Choose relationship:</option><option value=0>Self</<option><option value=1>Father</option><option value=2>Grandfather</option><option value=3>Great Grandfather</option>";
			} else if (theDNAconfType == 'mt') {
				dropDownOptions = "<select id=Div1errTTaRel2Anc1 name=Div1errTTaRel2Anc1><option value=0>Choose relationship:</option><option value=0>Self</<option><option value=1>Mother</option><option value=2>Grandmother</option><option value=3>Great Grandmother</option>";
			}
			for (var i = 2; i < 9; i++) {
				if (theDNAconfType == 'y') {
					dropDownOptions += "<option value=" + (i + 2) + ">" + i + "x Great Grandfather</option>"; //
				} else if (theDNAconfType == 'mt') {
					dropDownOptions += "<option value=" + (i + 2) + ">" + i + "x Great Grandmother</option>"; //
					
				}	 else {
					dropDownOptions += "<option value=" + (i + 2) + ">" + i + "x Great Grandparent</option>"; //

				}
			}
			dropDownOptions += "</select><BR>";
			// alert(dropDownOptions);
			document.getElementById('Div1errH1').innerHTML = theMsg;
			document.getElementById('Div1errDropDown1').innerHTML = dropDownOptions;
			dropDownOptions = dropDownOptions.replace('2Anc1','2Anc2').replace('2Anc1','2Anc2');
			document.getElementById('Div1errTTa').textContent = whichID;
			document.getElementById('Div1errTTb').textContent = whichID;
			document.getElementById('Div1errTTc').textContent = whichID;
			document.getElementById('Div1errTTd').textContent = whichID;
			document.getElementById('Div1errDropDown2').innerHTML = dropDownOptions;
		}

		function fixedIDandTryAgain(fixType) {
			// alert("fixedIDandTryAgain");
			// FormDIV1errWikiTreeID1
			// Div1errDropDown1
			var thisID = "FormDIV1errWikiTreeID" + fixType;
			var dropID = "Div1errTTaRel2Anc" + fixType;
			var genderIDmale = "GenderFormDiv" +  fixType + "errRadioMale" ;
			var genderIDfemale = "GenderFormDiv" +  fixType + "errRadioFemale" ;
			// alert(thisID);
			// alert(genderIDmale);
			// alert ("value =  "+ document.getElementById(genderIDmale).value);
			// alert ("checked =  "+ document.getElementById(genderIDmale).checked);
			// alert ("selected =  "+ document.getElementById(genderIDmale).selected);
			// // alert(dropID);
			// alert (document.getElementById(dropID).value);
			console.log("fixedIDandTryAgain");
			console.log("whichIDnumForAlternateWikiTreeID",whichIDnumForAlternateWikiTreeID);
			console.log("whereToGoAfterChoosingAlternateWikiTreeID",whereToGoAfterChoosingAlternateWikiTreeID);
			if (whichIDnumForAlternateWikiTreeID == 3) {
				theID3 = document.getElementById(thisID).value;
				if (fixType == 2) {
					theID3b = document.getElementById(thisID + "b").value;
					aliasClosest3isMRCA = true;
				} else {
					aliasClosest3isMRCA = false;
				}
				numGensFromWikiTreeID3toCousin = document.getElementById(dropID).value;
				if (document.getElementById(genderIDfemale).checked ) {
					testTaker3Gender = 'Female' ;
				} else if (document.getElementById(genderIDmale).checked ) {
					testTaker3Gender = 'Male' ;
				} else {
					testTaker3Gender = '' ;
				}

			} else {
				theID2 = document.getElementById(thisID).value;
				if (fixType == 2) {
					theID2b = document.getElementById(thisID + "b").value;
					aliasClosest2isMRCA = true;
				} else {
					aliasClosest2isMRCA = false;
				}

				numGensFromWikiTreeID2toCousin = document.getElementById(dropID).value;
				if (document.getElementById(genderIDfemale).checked ) {
					testTaker2Gender = 'Female' ;
				} else if (document.getElementById(genderIDmale).checked ) {
					testTaker2Gender = 'Male' ;
				} else {
					testTaker2Gender = '' ;
				}
			}
			// alert("If 1");
			if (whereToGoAfterChoosingAlternateWikiTreeID == 2) {
				document.getElementById('FormDIV1err').style.display = 'none';
				loadComplexInfo2();
			} else if (whereToGoAfterChoosingAlternateWikiTreeID == 3) {
				document.getElementById('FormDIV1err').style.display = 'none';
				if (theDNAconfType == 'Tri'){
					loadTriInfo(3);
				} else {
					loadTriInfo(4);
				}
			} else if (whereToGoAfterChoosingAlternateWikiTreeID == 3.1) {
				document.getElementById('FormDIV1err').style.display = 'none';
				loadComplexInfo3();
			} else  {
				resetApp();
			}
			// alert("If 2");
			// alert ("whichIDnumForAlternateWikiTreeID = " + whichIDnumForAlternateWikiTreeID);
			// alert ("whereToGoAfterChoosingAlternateWikiTreeID = " + whereToGoAfterChoosingAlternateWikiTreeID);
		}

		function setupWarningRePersonNWikiTreeID(N, whichForm, whichID, statusMsg) {
			// alert("setupWarningRePerson1WikiTreeID");
			// FormDIV1triPNerr
			console.log("setupWarningRePersonNWikiTreeID",{N},{whichForm},{whichID}, {statusMsg});
			
			if (document.getElementById('FormDIV1' + whichForm)) {
				document.getElementById('FormDIV1' + whichForm).style.display = 'block';
			}
			document.getElementById('FormDIV1' + whichForm +'P' + N + 'err').style.display = 'block';
			document.getElementById('FormDIV1contacting').style.display = 'none';
			
				
					
			var theMsg = "<h2>Problem trying to retrieve info for<br/><B>" + whichID + "</B></h2><h3>" + statusMsg + "</h3>";		
			var problemQualifier = "a valid";
			var extraQualifier = "";
			if (statusMsg == "Permission denied" || statusMsg.indexOf("Permission denied") > -1) {
				theMsg += "WikiTree Profile is Private, unable to access.";
				problemQualifier = "an accessible";
				extraQualifier = "<I>(or check Privacy settings for profile)</I>";
			} else if (statusMsg == "Illegal WikiTree ID" ) {
				theMsg += "Not a valid WikiTree ID (format: Smith-12345)";
			} else if (statusMsg == "Invalid user" || statusMsg == "Invalid WikiTree ID") {
				theMsg += "Not a valid WikiTree ID (probably typo)";
			}
			
			theMsg += "<BR><BR><B>Person " + N + " must have " + problemQualifier + " WikiTree ID</B><BR>Fix/change the WikiTree ID, then click NEXT.<BR/>" + extraQualifier +"<BR/>";



			document.getElementById('FormDIV1' + whichForm +'P' + N + 'err').innerHTML = theMsg;
			console.log("done updated msg",theMsg);

			if (whichForm == 'abcX') {
				document.getElementById('FormDIV1' + 'abX').style.display = 'block';
			}
		}

		function setupAliasRePersonNWikiTreeID(N,  whichID) {
			// alert("setupWarningRePerson1WikiTreeID");
			// FormDIV1triPNerr
			console.log("setupWarningRePersonNWikiTreeID",{N},{whichID});
			
			
			document.getElementById('FormDIV1triPNerr' ).style.display = 'block';
			document.getElementById('FormDIV1contacting').style.display = 'none';
			
				
					
			var theMsg = "<h2>Problem trying to retrieve info for<br/><B>" + whichID + "</B></h2><h3>" + "Not valid WikiTree ID" + "</h3><I>e.g. Smith-12345</I><BR><BR>";				
			

			theMsg += "<BR><BR><B>Person " + N + " must have a valid WikiTree ID</B><BR>Fix/change the WikiTree ID,<BR>then click the ADD NEW DNA TEST TAKER NOW button below.<BR/>" +"<BR/>";

			let theORmaybeALIASmsg = "<table style='border:solid lime 10px;' ><TR><TD align=center style='background-color:white;'><H2> - OR -</H2>IF this DNA Test Taker does NOT HAVE a public WikiTree Profile<BR><BR>THEN ...<BR><BR>Enter the WikiTree ID of <BR/>the closest Direct Ancestor to the DNA Test Taker<BR/>who <U>IS</U> on WikiTree:<BR><input type=text id=closestToAliasID1>";

			// theORmaybeALIASmsg += "<BR/><BR/><I>Optional:</I><BR>If the closest Ancestors to the DNA Test Taker are a couple ...<BR>Enter the WikiTree ID of the other member of that closest Ancestral Couple<BR/>who <U>IS</U> on WikiTree:<BR><input type=text id=closestToAliasID2>";

			let relationshipKidTypes = ["self","child","grandchild","great-grandchild","great-great-grandchild","great-great-great-grandchild","great-great-great-great-grandchild"];

			theORmaybeALIASmsg += "<BR/><BR/><I>REQUIRED:</I><BR><BR>Choose relationship of <B>" + whichID + "</B> to closest Ancestor(s) on WikiTree:<BR/><SELECT id=closestToAliasRelGen><option value=-1>Pick one:</option>";
			for (var i = 1; i < relationshipKidTypes.length; i++) {
				theORmaybeALIASmsg += "<option value=" + i +">" + relationshipKidTypes[i] + "</option>";
			}
			theORmaybeALIASmsg += "</SELECT>";

			theORmaybeALIASmsg += "<BR/><BR/><button onclick=prepToAddAliasAncestors();>Add Closest Ancestor(s) to "+whichID+"</button>";

			theORmaybeALIASmsg += "</TD></TR></TABLE><BR/>&nbsp;";

			document.getElementById('FormDIV1triPNerr').innerHTML = theMsg + theORmaybeALIASmsg;
			
			
		}

		function prepToAddAliasAncestors() {
			let anc1WikiTreeID = document.getElementById("closestToAliasID1").value;
			// let anc2WikiTreeID = document.getElementById("closestToAliasID2").value;
			let closestToAliasRelGen = document.getElementById("closestToAliasRelGen").value;
			console.log("OK - here we go adding ancestors !", {anc1WikiTreeID},/*{anc2WikiTreeID},*/{closestToAliasRelGen});
			if (anc1WikiTreeID == "") {
				alert("Enter valid WikiTree ID for Closest Ancestor.");
				return;
			}
			if (closestToAliasRelGen < 1) {
				alert("Choose relationship from drop down menu.");
				return;
			}

			// addAncestorsToNewTestTakerToTree(anc1WikiTreeID, anc2WikiTreeID, closestToAliasRelGen);
			addAncestorsToNewTestTakerToTree(anc1WikiTreeID, "", closestToAliasRelGen);
		}

		 

		function howGreatIsThisOrNot( theRel ) {
			if (theRel == 2) {
				return "";
			} else if (theRel == 3) {
				return "grand";
			} else if (theRel == 4) {
				return "great grand";
			} else if (theRel > 4) {
				return (theRel-3) + "x great grand";
			} else if (theRel == 1) {
				return  "father of the ";
			}
		}

		function SonOrDaughter( person ) {
			if (person && person.Gender && person.Gender == "Male") {
				return "son";
			} else if (person && person.Gender && person.Gender == "Female") {				
				return "daughter";
			} else {
				return "child";
			}
		}
		function SonOrDaughter2( theGender ) {
			if (theGender == "Male") {
				return "son";
			} else if (theGender == "Female") {				
				return "daughter";
			} else {
				return "child";
			}
		}
		function HisOrHer( person ) {
			if (person && person.Gender && person.Gender == "Male") {
				return "his";
			} else if (person && person.Gender && person.Gender == "Female") {
				return "her";
			} else {
				return "their";
			}
		}
		function HisOrHer2( theGender ) {
			if (theGender == "Male") {
				return "his";
			} else if (theGender == "Female") {
				return "her";
			} else {
				return "their";
			}
		}
		function FatherOrMother( person ) {
			if (person && person.Gender && person.Gender == "Male") {
				return "father";
			} else if (person && person.Gender && person.Gender == "Female") {
				return "mother";
				
			} else {
				return "parent";
			}
		}
		function FatherOrMother2( theGender ) {
			if (theGender == "Male") {
				return "father";
			} else if (theGender == "Female") {
				return "mother";
				
			} else {
				return "parent";
			}
		}

		function setWarningDiedTooSoonForDNAtesting(arrayOfTesters) {
			showDeathWarning = false;
			let deadTestersText = "";

			for (var i = 0; i < arrayOfTesters.length; i++) {
				theProfilePerson = arrayOfTesters[i];
				let shortDeathDate = "0000";
				if (theProfilePerson && theProfilePerson.DeathDate) {
					shortDeathDate = theProfilePerson.DeathDate.substr(0,4);
				}
				if (shortDeathDate != "0000" && shortDeathDate < 2000) {
					showDeathWarning = true;
					deadTestersText += theNextBestFirstName(theProfilePerson) + " " + theBestLastName(theProfilePerson) + " died " + theProfilePerson.DeathDate + "\n";
				}
			}

			document.getElementById("FormDIV1WarningDeadTesters").innerText = deadTestersText;
			document.getElementById("FormDIV1WarningDeathDates").style.display = (showDeathWarning == true ? "block":"none");
		}

		function showDirectAncestorMiniTree() {
			// alert("Showing Direct Ancestor from 1 to " + theAncsAhnNum) ;

			var DIVtext = "	<P>STEP 4:  Is this mini tree right ? <HR><TABLE id=UNIminiTree align=center><TR><TD align=center><span style=background-color:yellow>COMMON ANCESTOR<BR>&<BR>DNA test taker</span></TD></TR>" ;
			var currNum = theAncsAhnNum;
			statusCodesInMiniTreeTracker = [];

			if (currNum == -1 ) {
				currNum = ahnenIndexArrays[1].indexOf(ahnenIndexArrays[2][1]);
			}
			var childNum = Math.floor(currNum/2);
			console.log("showDirectAncestorMiniTree : curNum, childNum --> ", currNum, childNum, {theAncsAhnNum});
			console.log(theProfilePerson, theTestPerson);
			console.log({ahnenIndexArrays});

			
			setWarningDiedTooSoonForDNAtesting( [theProfilePerson, theTestPerson] );


			var theRel = Math.floor(Math.log2(currNum));
			relOfP2toP1 = HisOrHer(theProfilePerson) + " " +  howGreatIsThisOrNot(theRel + 1) + FatherOrMother( personArray[ ahnenIndexArrays[1][currNum] ] );
			console.log("RELATIONSHIP IS:  --->");
			console.log(relOfP2toP1);
			relationshipMatrix["1|2"] = relOfP2toP1.replace("his ","").replace("her ","").replace("their ","");
			relationshipMatrix["2|1"] = relOfP2toP1.replace("his ","").replace("her ","").replace("their ","");

			let painterRelationships = ["parent", "grandparent"];
			if (theRel <= 1) {
				DNApainterRelationship = "Parent";
			} else if (theRel == 2) {
				DNApainterRelationship = "Grandparent";
			} else if (theRel > 2) {
				DNApainterRelationship = "Great-".repeat(theRel - 2) + "Grandparent";
			}

			console.log({relationshipMatrix}, {DNApainterRelationship},{theRel});

			while (currNum > 0) {
				let nextCurrNum = Math.floor(currNum/2);
				if (currNum == 1){
					DIVtext += "<TR><TD align=center style='background-color:lime;'>&nbsp;<BR>" + theNextBestFirstName( personArray[ ahnenIndexArrays[1][currNum] ]) + " "  + personArray[ ahnenIndexArrays[1][currNum] ].LastNameCurrent + "<BR>&nbsp;</TD></TR>"; 
					DIVtext += "<TR><TD align=center><span style=background-color:yellow>DNA test taker</span></TD></TR>";
				} else {
					let southArrow = "";
					let currAncestor1Child = personArray[ahnenIndexArrays[1][nextCurrNum]];
					if (currAncestor1Child && currAncestor1Child.DataStatus) {	
						if (currNum % 2 == 0) {
							if (currAncestor1Child.DataStatus.BioFather){
								southArrow = colouredArrowInMiniTree("S",currAncestor1Child.DataStatus.BioFather,currAncestor1Child);
							} else {
								southArrow = colouredArrowInMiniTree("S",currAncestor1Child.DataStatus.Father,currAncestor1Child);
							}
						} else {
							if (currAncestor1Child.DataStatus.BioMother){
								southArrow = colouredArrowInMiniTree("S",currAncestor1Child.DataStatus.BioMother,currAncestor1Child);
							} else {
								southArrow = colouredArrowInMiniTree("S",currAncestor1Child.DataStatus.Mother,currAncestor1Child);
							}
						}				
							
					} else {
						southArrow = "&darr;";
					}

					DIVtext += "<TR><TD align=center style='background-color:lime;'>&nbsp;<BR>" + theNextBestFirstName( personArray[ ahnenIndexArrays[1][currNum] ]) + " "  + personArray[ ahnenIndexArrays[1][currNum] ].LastNameAtBirth + "<BR>&nbsp;</TD></TR>"; 
					DIVtext += "<TR><TD align=center>" + howGreatIsThisOrNot(theRel + 1) + FatherOrMother( personArray[ ahnenIndexArrays[1][currNum] ] ) + "<br> <br> <B>"+southArrow+"</B> </TD></TR>";
				}
				DIVtext += "<TR><TD>&nbsp;</TD></TR>";
					
				currNum = nextCurrNum; //Math.floor(currNum/2);
			 	theRel = Math.floor(Math.log2(currNum));
			}
			DIVtext += "</TABLE>";
			DIVtext += "<SPAN id=Legend4ArrowsDirect></SPAN><br/><br/>";
			DIVtext += '<input id=NextButton4direct  class="button" type="submit" value="NEXT" onclick="doneForm4();" ><BR>&nbsp;';
			document.getElementById('FormDIV4direct').innerHTML = DIVtext;
			document.getElementById('FormDIV4direct').style.display = 'block';

			childWikiName = personArray[ ahnenIndexArrays[1][childNum] ].Name 
			childFullName = theNextBestFirstName( personArray[ ahnenIndexArrays[1][childNum] ]) + " " + personArray[ ahnenIndexArrays[1][childNum] ].LastNameAtBirth;

			if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus) {
				console.log("SETUP for VERIFY / MARK (2) : ", personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Father, personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Mother, isCouple, theAncsAhnNum);
			}



			let makeVerifySlashTextVisibleBeforeMARKtextDisplay = "none";
			if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus) {
			
					// Is SINGLE ... so ... test for Gender of Ancestor (whether Ahnentafel# is even or odd) 
					if (theAncsAhnNum % 2 == 0) {
						// EVEN = MALE ancestor ... thus ... check for DataStatus.Father == 30, and if so ... then VERIFY
						if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Father == 30) {
							makeVerifySlashTextVisibleBeforeMARKtextDisplay = "revert"; // revert to normal, inline-block
							document.getElementById('Div7CAVerifyRelationship').innerText = "Paternal relationship";
						}
						// ELSE ... MARK / default
					} else {
						// ODD = FEMALE ancestor ... thus ... check for DataStatus.Mother == 30, and if so ... then VERIFY
						if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Mother == 30) {
							makeVerifySlashTextVisibleBeforeMARKtextDisplay = "revert"; // revert to normal, inline-block
							document.getElementById('Div7CAVerifyRelationship').innerText = "Maternal relationship";
						}
						// ELSE ... MARK
					}
				
			} else {
				// Choose MARK only
				makeVerifySlashTextVisibleBeforeMARKtextDisplay = "none";
			}

			document.getElementById('Div7CAVerify1').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerify2').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerify3').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerify4').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerifyKid').innerText = childFullName;

			if (makeVerifySlashTextVisibleBeforeMARKtextDisplay == "none") {
				theMarkedVerifiedCheckbox = theMVCheckboxMarked;
			} else {
				theMarkedVerifiedCheckbox = theMVCheckboxVerified;
			}

			TopVerifyMarkCheckboxDIVhtml = theMarkedVerifiedCheckbox.replace("<div align=right>","").replace("</div>","").replace("123", "Top");
			document.getElementById('TopVerifyMarkCheckboxDIV').innerHTML  = TopVerifyMarkCheckboxDIVhtml;





			document.getElementById('Div7CAkid').innerHTML =  "<A target=_blank href=https://www.wikitree.com/wiki/" +  childWikiName.replace(" ","_").replace(" ","_") +">" + childFullName + "</A>";
			document.getElementById('Div7CAkid2').innerHTML = "<B>" + childFullName  + "</B> (" + childWikiName+ ")";
			document.getElementById('Div7CAkid3').textContent =  childFullName;

			updateLegend4Arrows();

		}

		// HELPER FUNCTIONS to PROVIDE THE PROPER PRONOUNS PROMPTLY !
		function possessiveOf ( personID ) {
			if (personArray[personID] && personArray[personID].Gender  && personArray[personID].Gender > "" )  {
				if (personArray[personID].Gender == "Male") {
					return "his ";
				} else if (personArray[personID].Gender == "Female") {
					return "her ";
				}
			}

			return "";

		}

		function pronounOf ( personID , theCase = "l" ) {
			// theCase = "l" lowercase or "T" TitleCase
			if (personArray[personID] && personArray[personID].Gender  && personArray[personID].Gender > "" )  {
				if (theCase == "l"){
					if (personArray[personID].Gender == "Male") {
						return "he ";
					} else if (personArray[personID].Gender == "Female") {
						return "she ";
					}
				} else {
					if (personArray[personID].Gender == "Male") {
						return "He ";
					} else if (personArray[personID].Gender == "Female") {
						return "She ";
					}
				}
			}

			if (theCase == "l"){
				return "they ";

			} else {
				return "They ";
			}
			
			
		}

		// HELPER FUNCTION --> used to add to list of Ahnentafel Numbers to IGNORE (ancestors of already matches MRCAs) 
		function addToIgnoreArray(theI, ignoreTheseArray, maxAhnentafelNumber) {
				ignoreTheseArray.push(theI*2);
				ignoreTheseArray.push(theI*2 + 1);

				if (theI * 2 + 1 < maxAhnentafelNumber) {
					addToIgnoreArray(theI*2, ignoreTheseArray, maxAhnentafelNumber)
					addToIgnoreArray(theI*2 + 1, ignoreTheseArray, maxAhnentafelNumber)
				}			
		}

		// HELPER FUNCTION --> Note when the Drop Down Selector for MULTIPLE MRCAs for a SIMPLE DNA Match has been selected / changed
		// and .... update the MiniTree (and other variables) accordingly

		function changeSimpleMRCAs() {
			console.log("\nchangeSimpleMRCAs MultipleMRCAselector", document.getElementById("MultipleMRCAselector").value);		

			let MIDarray = document.getElementById("MultipleMRCAselector").value.split("|");

			MRCA = ahnenIndexArrays[1][ MIDarray[0] ];

			theAncsAhnNum = MIDarray[0];
			theAncsAhnNum4TT = MIDarray[1];

			theAncestor = personArray[MRCA];
			if (MIDarray[2] == 2) {
				// we have an Ancestral Couple
				isCouple = true;
				MRCA2 = ahnenIndexArrays[1][ 1.0 * MIDarray[0] + 1 ];
				theAncestor2 = personArray[MRCA2];
			} else {
				isCouple = false;
				MRCA2 = -1;
			}

			let MRCAnames = theNextBestFirstName(personArray[ahnenIndexArrays[1][MIDarray[0]]]) + " " + theBestLastName(personArray[ahnenIndexArrays[1][MIDarray[0]]]) ;

			if (MIDarray[2] == 2) {
				MRCAnames += " + " + theNextBestFirstName(personArray[ahnenIndexArrays[1][1.0 * MIDarray[0] + 1]]) + " " + theBestLastName(personArray[ahnenIndexArrays[1][1.0 * MIDarray[0] + 1]]) ;
			}

			console.log("Bring on the MINI TREE for MRCA"  + (MRCA2 > -1?"s":"") + " : " + MRCAnames, MRCA, MRCA2);

			if ( MRCA2 > -1) {
				// alert ("COMMON Ancestors : " + personArray[ MRCA ].FirstName + " " + personArray[ MRCA ].LastNameAtBirth + " & " + personArray[ MRCA2 ].FirstName + " " + personArray[ MRCA2 ].LastNameAtBirth);  
				document.getElementById('AncS4').textContent = "S";
				hideAllDropDownsInMiniTree();
			} else if (MRCA > -1) {
				document.getElementById('AncS4').textContent = "";
				hideAllDropDownsInMiniTree();
				// alert ("COMMON Ancestor : " + personArray[ MRCA ].FirstName + " " + personArray[ MRCA ].LastNameAtBirth);
			}


			let theTableHTML = buildHTMLcodeForTable(MIDarray, generationsFromMRCA);
			// document.getElementById("FormDIV4Experimental").style.display = "block";
			console.log("UPDATING the SimpleMiniTreeTable");
			document.getElementById("SimpleMiniTreeTable").innerHTML = theTableHTML;
			console.log( document.getElementById("SimpleMiniTreeTable") );
			whichMiniTree2Use = "SimpleMiniTreeTable";

			// genderCheckFromSimple(1);

		}

		function setRelationshipsFromMatch( matchInfoArray , generationsFromMRCA) {
			let degreeNTHarray = ["","1st","2nd","3rd","th"];

			// MRCA = ahnenIndexArrays[1][ matchInfoArray[1] ];
			// theAncestor = personArray[MRCA];

			// theAncsAhnNum = matchInfoArray[1];
			// theAncsAhnNum4TT = matchInfoArray[2];

			// theRel1 = Math.floor(Math.log2(theAncsAhnNum));			
			// theRel2 = Math.floor(Math.log2(theAncsAhnNum4TT));			
			
			
			if (matchInfoArray[0] == 2) {
				// we have an Ancestral Couple
				isCouple = true;
			} else {
				isCouple = false;
			}

			numNonWikiTreers2 = 0;

			// CALCULATE RELATIONSHIP BETWEEN MRCA and the DNA TEST TAKERS
			MRCAbase = 'parent';
			if (isCouple) {
				addS4Couple = "S";
				MRCAbase = 'parents';
				ancestorType = 'parent';
			} else {
				let startingNum1 = matchInfoArray[1];
				addS4Couple = "";
				if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Male') {
					MRCAbase = 'father';
				} else if (personArray[ahnenIndexArrays[1][startingNum1]].Gender == 'Female') {
					MRCAbase = 'mother';
				}
				ancestorType = MRCAbase;
			}	

			MRCArelationship = theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + "'s " + howGrandIsIt(theRel1) + MRCAbase + "<BR><I>and</I><BR>";
			relCA2P1 = howGrandIsIt( theRel1) + MRCAbase ;
			console.log("relCA2P1 = ", relCA2P1);
			relationshipMatrix["1"] = relCA2P1;

			console.log("MRCArelationship", MRCArelationship);

			console.log("relCA2P1", relCA2P1);

			if (numNonWikiTreers2 > 0) {
				MRCArelationship +=   theOriginalID2 + "'s " + howGrandIsIt( 2 + 1*theRel2 + 1*numNonWikiTreers2 - 2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( 2 + 1*theRel2 + 1*numNonWikiTreers2 - 2) + MRCAbase ;
				console.log("relCA2P2 - 6306", relCA2P2);
			} else if (generationsFromMRCA > 0) {
				MRCArelationship +=   theOriginalID2 + "'s " + howGrandIsIt( 2 + 1*theRel2 + 1*generationsFromMRCA - 2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( 2 + 1*theRel2 + 1*generationsFromMRCA - 2) + MRCAbase ;
				console.log("relCA2P2 - 6310", relCA2P2);
			} else {
				MRCArelationship +=  theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]) + "'s " + howGrandIsIt(theRel2) + MRCAbase ;
				relCA2P2 = howGrandIsIt( theRel2) + MRCAbase ;
				relationshipMatrix["2"] = relCA2P2;
				console.log("relCA2P2 - 6315", relCA2P2);
			}


			// CALCULATE THE RELATIONSHIP BETWEEN DNA TEST TAKERS (with each other)

			// theRel1 = Math.floor(Math.log2(theAncsAhnNum));	--> the log2 will give the generation of the connection to the MRCA for each DNA test taker		
			// theRel2 = Math.floor(Math.log2(theAncsAhnNum4TT));			
			if (theTT1isActually == "child") {
				theRel1ToUse = theRel1 + 1;
			} else if (theTT1isActually == "grandchild") {
				theRel1ToUse = theRel1 + 2;
			} else {
				theRel1ToUse = theRel1;
			}
				

			let howRelatedPhrase = "";
			let minRel = Math.min(theRel1ToUse, theRel2 + generationsFromMRCA);
			let diffRel = Math.abs(theRel1ToUse -  (theRel2 + generationsFromMRCA));
			if (minRel == 0) {
				howRelatedPhrase = "direct ancestor";
			} else if (minRel == 1) {
				howRelatedPhrase = "sibling";
				if ( personArray[ahnenIndexArrays[2][1]] &&  personArray[ahnenIndexArrays[2][1]] &&  personArray[ahnenIndexArrays[2][1]].Gender) {
					if ( personArray[ahnenIndexArrays[2][1]].Gender == "Female") {
						howRelatedPhrase = "sister";
					} else if ( personArray[ahnenIndexArrays[2][1]].Gender == "Male") {
						howRelatedPhrase = "brother";
					}
				}
				if (diffRel > 0) {
					if (theRel1ToUse >  (theRel2 + generationsFromMRCA)){
						howRelatedPhrase = howRelatedPhrase.replace("sister","aunt").replace("brother","uncle").replace("sibling","pibling");
					} else {
						howRelatedPhrase = howRelatedPhrase.replace("sister","niece").replace("brother","nephew").replace("sibling","nibling");
					}
				}
				if (diffRel == 2) {
					howRelatedPhrase = "grand " + howRelatedPhrase;
				} else if (diffRel == 3) {
					howRelatedPhrase = "great grand " + howRelatedPhrase;
				} else if (diffRel > 3) {
					howRelatedPhrase = (diffRel - 2) + "x great grand " + howRelatedPhrase;
				}
										

			} else  {
				howRelatedPhrase = "cousin";
				//if (minRel > 2) {
					//degreeNTHarray = ["","1st","2nd","3rd","th"]
					let howTH = "th";
					if (minRel - 1 < 4) {
						howTH = degreeNTHarray[minRel - 1];
					} else {
						howTH = (minRel - 1) + "th";
					}

					howRelatedPhrase =  howTH + " cousin";
					if (diffRel > 0) {
						howRelatedPhrase += " " + (diffRel) + "x removed";
					}

				//}
			}

			if (matchInfoArray[0] == 1) {
				howRelatedPhrase = "half " + howRelatedPhrase;
			}

			relationshipMatrix["1|2"] = howRelatedPhrase;

			if (howRelatedPhrase.indexOf("cousin") > -1) {
				// same relationship in reverse
				reverseRelatedPhrase = howRelatedPhrase;
			} else {
				// Step 1 - replace the specific relationships with the gender-neutral labels for the inverse relationships
				reverseRelatedPhrase = howRelatedPhrase.replace("aunt","nibling").replace("uncle","nibling").replace("niece","pibling").replace("nephew","pibling").replace("father","child").replace("mother","child").replace("son","parent").replace("daughter","parent").replace("sister","sibling").replace("brother","sibling");
				// Step 2 - replace the gender-neutral terms with specific terms, if the Gender is known
				if ( personArray[ahnenIndexArrays[1][1]].Gender == "Female") {
					reverseRelatedPhrase = reverseRelatedPhrase.replace("nibling","niece").replace("pibling", "aunt").replace("sibling", "sister").replace("parent","mother").replace("child","daughter");
				} else if ( personArray[ahnenIndexArrays[1][1]].Gender == "Male") {
					reverseRelatedPhrase = reverseRelatedPhrase.replace("nibling","nephew").replace("pibling", "uncle").replace("sibling", "brother").replace("parent","father").replace("child","son");
				} else {
					console.log( "Gender reverse issue ?? : " , personArray[ahnenIndexArrays[1][1]].Gender, personArray[ahnenIndexArrays[1][1]]);
				}
			}

			relationshipMatrix["2|1"] = reverseRelatedPhrase;

			console.log({relationshipMatrix});
			console.log({MRCArelationship});
			return howRelatedPhrase;

		}

		function colouredArrowInMiniTree(direction = "S", statusCode, personWithCode) {
			console.log("colouredArrowInMiniTree",direction , statusCode, personWithCode)
			let directionCharacter = {"SW":"↙","SE":"↘","S":"&nbsp;↓&nbsp;"};
			let bkgdClrByCode = {0:"lightgrey",5:"red",10:"orange",20:"cyan",30:"lime", 888:"pink", 999:"aliceblue"};
			if (!statusCode || statusCode == "") {				
				return "<span style='background-color:" + bkgdClrByCode[999] + "';>" + directionCharacter[direction] + "</span>";
				// return directionCharacter[direction];
			} else if (statusCode > 0) {
				if (personWithCode){
					statusCodesInMiniTreeTracker.push(statusCode);
				}
				return "<span style='background-color:" + bkgdClrByCode[statusCode] + "';>" + directionCharacter[direction] + "</span>";
			} else {				
				return "<span style='background-color:" + bkgdClrByCode[999] + "';>" + directionCharacter[direction] + "</span>";
				return directionCharacter[direction];
			}
		}

		function updateLegend4Arrows() {
			console.log("updateLegend4Arrows");
			theLegend4ArrowsHTML = "<B>Legend for Parent/Child status:</B><BR/><I>(set by radio buttons, in EDIT mode)</I><BR/>";
			let descByCode = {0:"No radio button status set",5:"Non-biological parent",10:"Uncertain parentage",20:"Parent/Child relationship is confident",30:"Parent/Child relationship is DNA Confirmed"};
			for (var i in  descByCode) {
				if (statusCodesInMiniTreeTracker.indexOf(i) > -1){
					let desc = descByCode[i];
					theLegend4ArrowsHTML += colouredArrowInMiniTree( "S", i) + " : " + desc + "<BR/>";
				}
			}

			theLegend4ArrowsHTML += colouredArrowInMiniTree( "S", 0) + " : " + descByCode[0] + "<BR/>";

			

			if (statusCodesInMiniTreeTracker.indexOf('5') > -1){
				theLegend4ArrowsHTML += "<P><B>NON-BIOLOGICAL PARENT/CHILD RELATIONSHIP FOUND</B><BR/>No DNA Confirmation statement can be issued for this relationship because there is a break in the chain of DNA from DNA Tester #1 to DNA Tester #2."
				document.getElementById("NextButton4").style.display = "none";
			}
			if (statusCodesInMiniTreeTracker.indexOf('10') > -1){
				theLegend4ArrowsHTML += "<P><B>UNCERTAIN PARENT/CHILD RELATIONSHIP FOUND</B><BR/>No DNA Confirmation statement can be issued for this relationship between DNA Tester #1 and DNA Tester #2 as long as there is one or more uncertain parentages in the chain.  <P>DNA can only confirm a relationship that has already been proven genealogically with at least one source validating every parent/child relationship in the sequence of connections."
				document.getElementById("NextButton4").style.display = "none";
			}

			document.getElementById("Legend4Arrows").innerHTML = theLegend4ArrowsHTML;
			if (document.getElementById("Legend4ArrowsDirect")) {document.getElementById("Legend4ArrowsDirect").innerHTML = theLegend4ArrowsHTML;}
		}


		function buildHTMLcodeForTable( matchInfoArray , generationsFromMRCA = 0 ) {
			let theTableHTML = "";
			console.log("BUILDING the HTML for ",matchInfoArray, {generationsFromMRCA});
			statusCodesInMiniTreeTracker = [];

			MRCA = ahnenIndexArrays[1][ matchInfoArray[1] ];
			theAncestor = personArray[MRCA];

			childNum = Math.floor(matchInfoArray[1] /2);
			childWikiName = personArray[ ahnenIndexArrays[1][childNum] ].Name 
			childFullName = theNextBestFirstName(personArray[ ahnenIndexArrays[1][childNum] ]) + " " + personArray[ ahnenIndexArrays[1][childNum] ].LastNameAtBirth;
				

			theAncsAhnNum = matchInfoArray[1];
			theAncsAhnNum4TT = matchInfoArray[2];

			theRel1 = Math.floor(Math.log2(theAncsAhnNum));			
			theRel2 = Math.floor(Math.log2(theAncsAhnNum4TT));			
			
			console.log("RELs:", theRel1, theRel2);


			let degreeNTHarray = ["","1st","2nd","3rd","th"];
			let offspringArray = ["child","grandchild","great grandchild"];
			let currAhnNum1 = theAncsAhnNum;
			let currAhnNum2 = theAncsAhnNum4TT;
			let FirstName1 = theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]);
			let FirstName2 = theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]);
			let TestTaker2Gender = "Unknown";

			if (ahnenIndexArrays[2].length > 0 && ahnenIndexArrays[2][1] > 0 && personArray[ahnenIndexArrays[2][1]] && personArray[ahnenIndexArrays[2][1]].Gender) {
				TestTaker2Gender = personArray[ahnenIndexArrays[2][1]].Gender;
			}


			// ==========================================
			// START OFF WITH THE TABLE HEADER AT THE TOP -- COMMON ANCESTOR(s) + RELATIONSHIP TO TEST TAKERS
			// ==========================================
			//

			let nameOf2ndTestTakerToUse = (generationsFromMRCA > 0 ? theTestTaker : theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]) );

			if (matchInfoArray[0] == 2) {
				// we have an Ancestral Couple
				isCouple = true;
				MRCA2 = ahnenIndexArrays[1][ 1.0 * matchInfoArray[1] + 1 ];
				theAncestor2 = personArray[MRCA2];

				let currAncestor1ChildAhnNum = Math.floor(theAncsAhnNum / 2);
				let currAncestor1Child = personArray[ ahnenIndexArrays[1][currAncestor1ChildAhnNum]];
				let southWestArrows = "";
				if (currAncestor1Child && currAncestor1Child.DataStatus) {		
						if (currAncestor1Child.BioFather) {
							southWestArrows = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.BioFather,currAncestor1Child) 
						} else {
							southWestArrows = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.Father,currAncestor1Child) 
						}
						if (currAncestor1Child.BioMother) {
							southWestArrows += colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.BioMother,currAncestor1Child) 
						} else {
							southWestArrows += colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.Mother,currAncestor1Child) 
						}

						// southWestArrows = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.Father,currAncestor1Child)  + colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.Mother,currAncestor1Child);
				} else {
					southWestArrows = "↙↙";
				}

				let currAncestor2ChildAhnNum = Math.floor(matchInfoArray[2] / 2);
				let currAncestor2Child = personArray[ ahnenIndexArrays[2][currAncestor2ChildAhnNum]];
				let southEastArrows = "";
				if (currAncestor2Child && currAncestor2Child.DataStatus) {	
						if (currAncestor1Child.BioFather) {
							southEastArrows = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.BioFather,currAncestor2Child) 
						} else {
							southEastArrows = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.Father,currAncestor2Child) 
						}
						if (currAncestor1Child.BioMother) {
							southEastArrows += colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.BioMother,currAncestor2Child) 
						} else {
							southEastArrows += colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.Mother,currAncestor2Child) 
						}

						// southEastArrows = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.Father,currAncestor2Child)  + colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.Mother,currAncestor2Child);
				} else {
					southEastArrows = "↘↘";
				}

			


				theTableHTML = "<tr><td colspan=2>&nbsp;</td><td align=center><span style='background-color:yellow;'>COMMON ANCESTORS</span></td><td colspan=2></td></tr>";
				theTableHTML += "<tr><td colspan=2>&nbsp;</td><td align=center style='background-color:lime;'><b>" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "<br>&<br>" + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2) + "</b></td><td colspan=2></td></tr>";
				theTableHTML += "<tr><td colspan=2 align=right valign=bottom><b>"+southWestArrows+"</b></td><td align=center style='background-color:white;'>" + theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + "'s " + howGrandIsIt(theRel1) + "parents" + "<BR><I>and</I><BR>" + nameOf2ndTestTakerToUse + "'s " + howGrandIsIt(theRel2 + generationsFromMRCA) + "parents"  + "</td><td colspan=2 align=left valign=bottom><b>"+ southEastArrows +"</b></td></tr>";

				if (generationsFromMRCA > 0 && ahnenIndexArrays[2][2] && ahnenIndexArrays[2][2] > 0) {
					FirstName2 +=" & " + theNextBestFirstName( personArray[ahnenIndexArrays[2][2]]);
				}

			} else {
				isCouple = false;
				MRCA2 = -1;
				let MRCAbase = "father";
				if (theAncsAhnNum % 2 == 1) {
					MRCAbase = "mother";
				}

				let parentGender = theAncsAhnNum % 2;
				let currAncestor1ChildAhnNum = Math.floor(theAncsAhnNum / 2);
				let currAncestor1Child = personArray[ ahnenIndexArrays[1][currAncestor1ChildAhnNum]];
				let southWestArrow = "";
				if (currAncestor1Child && currAncestor1Child.DataStatus) {						
					if (parentGender == 0) {
						if (currAncestor1Child.DataStatus.BioFather) {
							southWestArrow = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.BioFather,currAncestor1Child) ;
						} else {
							southWestArrow = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.Father,currAncestor1Child) ;
						}
						// southWestArrow = "<td align=center>↙ <B>" + currAncestor1Child.DataStatus.Father + "</B>/" + currAncestor1Child.DataStatus.Mother + "</td>";
					} else {
						if (currAncestor1Child.DataStatus.BioMother) {
							southWestArrow = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.BioMother,currAncestor1Child) 
						} else {
							southWestArrow = colouredArrowInMiniTree("SW",currAncestor1Child.DataStatus.Mother,currAncestor1Child) 
						}
						// southWestArrow = "<td align=center>↙ " + currAncestor1Child.DataStatus.Father + "/<B>" + currAncestor1Child.DataStatus.Mother + "</B></td>";
					}

				} else {
					southWestArrow = "↙";
				}

				let currAncestor2ChildAhnNum = Math.floor(matchInfoArray[2] / 2);
				let currAncestor2Child = personArray[ ahnenIndexArrays[2][currAncestor2ChildAhnNum]];
				let southEastArrow = "";
				if (currAncestor2Child && currAncestor2Child.DataStatus) {		
					if (parentGender == 0) {				
						if (currAncestor2Child.DataStatus.BioFather) {
							southEastArrow = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.BioFather,currAncestor2Child) ;
						} else {
							southEastArrow = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.Father,currAncestor2Child) ;
						}
					} else {				
						if (currAncestor2Child.DataStatus.BioMother) {
							southEastArrow = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.BioMother,currAncestor2Child);
						} else {
							southEastArrow = colouredArrowInMiniTree("SE",currAncestor2Child.DataStatus.Mother,currAncestor2Child);
						}
					}
				} else {
					southEastArrow = "↘";
				}


				theTableHTML = "<tr><td colspan=2>&nbsp;</td><td align=center><span style='background-color:yellow;'>COMMON ANCESTOR</span></td><td colspan=2></td></tr>";
				theTableHTML += "<tr><td colspan=2>&nbsp;</td><td align=center style='background-color:lime;'><b>" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "</b></td><td colspan=2></td></tr>";
				theTableHTML += "<tr><td colspan=2 align=right valign=bottom><b>" + southWestArrow + "</b></td><td align=center style='background-color:white;'>" + theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + "'s " + howGrandIsIt(theRel1) + MRCAbase + "<BR><I>and</I><BR>" + nameOf2ndTestTakerToUse + "'s " + howGrandIsIt(theRel2 + generationsFromMRCA) + MRCAbase  + "</td><td colspan=2 align=left valign=bottom><b>" + southEastArrow + "</b></td></tr>";
			}

			


			// ===========================================================================================
			// THEN, CONTINUE BUILDING ROW ON ROW OF DESCENDANTS FROM COMMON ANCESTORS DOWN TO TEST TAKERS
			// ===========================================================================================
			//

			let arrayOfTesters = [];

			// adjust for radio buttons for Person 1 (if DNA test taker # 1 is actually a child or grandchild of WikITree ID # 1) 
			let theRel1ToUse = theRel1;
			if (theTT1isActually == "child") {
				theRel1ToUse = theRel1 + 1;
			} else if (theTT1isActually == "grandchild") {
				theRel1ToUse = theRel1 + 2;
			} else {
				arrayOfTesters.push(personArray[ahnenIndexArrays[1][1]]);
			}
				
			// adjust for generationsFromMRCA - distance between MRCA and test taker 2, when that test taker is an alias and not a pure WikiTree ID
			let theRel2ToUse = theRel2 + generationsFromMRCA;
			if (generationsFromMRCA == 0) {
				arrayOfTesters.push(personArray[ahnenIndexArrays[2][1]]);
			}

			let definedRelationship = defineRelationshipsBetween(currAhnNum1, currAhnNum2, theRel1ToUse - theRel1, generationsFromMRCA , 1, 2) ;
			console.log({definedRelationship});

			if (arrayOfTesters.length > 0) {setWarningDiedTooSoonForDNAtesting(arrayOfTesters);}

			for (var g = 0; g < Math.max(theRel1ToUse, theRel2ToUse); g++) {
				// theTableHTML += "<tr><td colspan=2>&nbsp;</td><td align=center style='background-color:lime;'><b>" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "</b></td><td colspan=2></td></tr>";
				let RelDesc2 = "<td align=center>" + " " + "</td>";
				let RelDesc1 = "<td align=center>" + " " + "</td>";

				let currAncestor1 = "";
				let currAncestor2 = "";
				let Anc1HTML = "";
				let Anc2HTML = "";
				let Anc1Desc = "";
				let Anc2Desc = "";
				let blankCell = "<td>&nbsp;</td>";
				let downCell = "<td align=center>↓</td>";
				let DNAtestTakerCell = "<td align=center style='background-color:yellow;'>DNA test taker</td>";	

				if (currAhnNum1 > 1 ) {
					currAhnNum1 = Math.floor(currAhnNum1 / 2);
					currAncestor1 = personArray[ ahnenIndexArrays[1][currAhnNum1]];

					let parentGender = currAhnNum1 % 2;
					let currAncestor1ChildAhnNum = Math.floor(currAhnNum1 / 2);
					let currAncestor1Child = personArray[ ahnenIndexArrays[1][currAncestor1ChildAhnNum]];

					if (currAncestor1Child && currAncestor1Child.DataStatus) {						
						if (parentGender == 0) {
							if (currAncestor1Child.DataStatus.BioFather) {
								downCell = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor1Child.DataStatus.BioFather) + "</B></td>";
							} else {
								downCell = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor1Child.DataStatus.Father) + "</B></td>";
							}
						} else {
							if (currAncestor1Child.DataStatus.BioMother) {
								downCell = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor1Child.DataStatus.BioMother) + "</B></td>";
							} else {
								downCell = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor1Child.DataStatus.Mother) + "</B></td>";
							}
						}

					} else {
						downCell = "<td align=center>↓</td>";
					}


					Anc1HTML = "<td align=center style='background-color:lime;'><b>&nbsp;<br/>" + theNextBestFirstName(currAncestor1) + " " + theBestLastName(currAncestor1) + "<br/>&nbsp;</b></td>";
					if (currAhnNum1 == 1 ) {
						if (theTT1isActually == "self"){
							Anc1Desc = DNAtestTakerCell;
						} else {
							Anc1Desc = blankCell;
						}
					} else {
						let tmpRel = Math.floor(Math.log2(currAhnNum1));			
						let parentType = "father";
						if (currAhnNum1 % 2 == 1) {
							parentType = "mother";
						}
						Anc1Desc = "<td align=center style='background-color:white;'>" + FirstName1 + "'s " +  howGrandIsIt(tmpRel) + parentType + "</td>";
					}


				} else {
					currAhnNum1 -= 1;
					let absCurrAhnNum1 = Math.abs(currAhnNum1);
					let offspringType = "x great grandchild";
					if (absCurrAhnNum1 < 3) {
						offspringType = offspringArray[absCurrAhnNum1];
					} else {
						offspringType = (absCurrAhnNum1 - 1) + offspringType;
					}
					Anc1HTML = "<td align=center style='background-color:#F0F0F0;'>&nbsp;<br/><i>" + FirstName1 + "'s " + offspringType+ "</i><br/>&nbsp;</td>";
					Anc1Desc = blankCell;
					if (g == theRel1ToUse - 1) {
						Anc1Desc = DNAtestTakerCell;
					}
				}

				Anc2Desc = blankCell;
				let downCell2 = "<td align=center>↓</td>";
				if (currAhnNum2 > 1 ) {
					currAhnNum2 = Math.floor(currAhnNum2 / 2);
					currAncestor2 = personArray[ ahnenIndexArrays[2][currAhnNum2]];

					let parentGender2 = currAhnNum2 % 2;
					let currAncestor2ChildAhnNum = Math.floor(currAhnNum2 / 2);
					let currAncestor2Child = personArray[ ahnenIndexArrays[2][currAncestor2ChildAhnNum]];

					if (currAncestor2Child && currAncestor2Child.DataStatus) {
						if (parentGender2 == 0) {
							if (currAncestor2Child.DataStatus.BioFather) {
								downCell2 = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor2Child.DataStatus.BioFather) + "</B></td>";
							} else {
								downCell2 = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor2Child.DataStatus.Father) + "</B></td>";
							}
						} else {
							if (currAncestor2Child.DataStatus.BioMother) {
								downCell2 = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor2Child.DataStatus.BioMother) + "</B></td>";
							} else {
								downCell2 = "<td align=center><B>" + colouredArrowInMiniTree("S", currAncestor2Child.DataStatus.Mother) + "</B></td>";
							}
						}

					} else {
						downCell2 = "<td align=center>↓</td>";
					}

					Anc2HTML = "<td align=center style='background-color:lime;'><b>" + theNextBestFirstName(currAncestor2) + " " + theBestLastName(currAncestor2) + "</b></td>";
					if (currAhnNum2 == 1) {
						Anc2Desc = DNAtestTakerCell;	
					} else {
						let tmpRel = Math.floor(Math.log2(currAhnNum2));			
						let parentType = "father";
						if (currAhnNum2 % 2 == 1) {
							parentType = "mother";
						}
						Anc2Desc = "<td align=center style='background-color:white;'>" + FirstName2 + "'s " +  howGrandIsIt(tmpRel) + parentType + "</td>";
					}
				} else {
					currAhnNum2 -= 1;
					let absCurrAhnNum2 = Math.abs(currAhnNum2);
					let offspringType = "x great grandchild";
					if (absCurrAhnNum2 < 3) {
						offspringType = offspringArray[absCurrAhnNum2];
					} else {
						offspringType = (absCurrAhnNum2 - 1) + offspringType;
					}
					Anc2HTML = "<td align=center style='background-color:#F0F0F0;'><i>"  + FirstName2 + "'s " + offspringType+ "</i></td>";

					if (generationsFromMRCA > 0) {
						if (g == theRel2ToUse - 1) {
							Anc2HTML = "<td align=center style='background-color:#F0F0F0;'><i>"  + theTestTaker + "</i><br/><span align=center style='background-color:yellow;'>&nbsp;&nbsp;DNA test taker&nbsp;&nbsp;</span></td>";
							// downCell2 = DNAtestTakerCell;
						} else {
							let gg = g + 1;
							downCell2 = `<td valign="top" align="center"><div id="P2peepDDown${gg}" style="display: revert;"><select id="Peep2AncDDown${gg}" onchange="updatePeepDDs(${gg},3);"><option>Choose one:</option><option value="-3">grandmother not on WikiTree</option><option value="-2">grandfather not on WikiTree</option></select></div><div id="P2peepRadios${gg}"><input name="P2peep${gg}" id="P2peep${gg}ValM" type="radio" value="M" onclick="changeP2CAsubGender(${gg},'M');">male<br><input name="P2peep${gg}" id="P2peep${gg}ValF" type="radio" value="F" onclick="changeP2CAsubGender(${gg},'F');">female</div><div id="P2peepRealNameRel${gg}"></div><div id="isTT${gg}" style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><div id="downArrowP2R${gg}"><br><b>↓</b></div></td>`;
						}
					}
				}

				let cousinDegree = "";
				if (g < 4) {
					cousinDegree = degreeNTHarray[g] + " ";
				} else {
					cousinDegree = g + "th ";
				}
				let RelBetween = "<td align=center><font color=blue><i>← " + (matchInfoArray[0] == 2 ? "" : "half ") + cousinDegree + (g==0?"siblings":"cousins") + " →</i></font></td>";
				
				theTableHTML += "<tr>" + RelDesc2 + Anc1HTML + RelBetween + Anc2HTML + RelDesc1 + "</tr>";
				theTableHTML += "<tr>" + blankCell + Anc1Desc + blankCell + Anc2Desc + blankCell + "</tr>";
				if ( g <  Math.max(theRel1ToUse, theRel2ToUse) - 1) {
					theTableHTML += "<tr>" + blankCell + downCell + blankCell + downCell2 + blankCell + "</tr>";
				}
			}


			document.getElementById('HowRelatedTheseTwo').innerHTML = "";
			
			let howRelatedPhrase = setRelationshipsFromMatch(matchInfoArray, generationsFromMRCA);
			

			if (howRelatedPhrase.indexOf('th cousin') > -1 || howRelatedPhrase.indexOf('3rd cousin 1x removed') > -1 || howRelatedPhrase.indexOf('3rd cousin 2x removed') > -1 || howRelatedPhrase.indexOf('3rd cousin 3x removed') > -1 || howRelatedPhrase.indexOf('half 3rd cousin') > -1 || howRelatedPhrase.indexOf('half 2nd cousin 2x') > -1 ||  howRelatedPhrase.indexOf('2nd cousin 3x') > -1 || howRelatedPhrase.indexOf('half 1st cousin 3x') > -1 ) {
				// alert ("TOO FAR relationship : " + relOfP1toP2);
				rawRelationship = howRelatedPhrase ;//temprelOfP2toP1.substr(temprelOfP2toP1.indexOf(" ") + 1);
				let firstPersonOffSpringModifier = "";
				if (theRel1ToUse - theRel1 == 1) {
					firstPersonOffSpringModifier = "the child of ";
				} else if (theRel1ToUse - theRel1 == 2) {
					firstPersonOffSpringModifier = "the grandchild of ";
				}	
				console.log("Comparing Warning Peeps:",{theProfilePerson},{theTestPerson});

				document.getElementById('Warning4P1').textContent = firstPersonOffSpringModifier  + theNextBestFirstName( personArray[ahnenIndexArrays[1][1]]) + " " + personArray[ahnenIndexArrays[1][1]].LastNameCurrent;
				if (theTestTakerID > '' /*&& theAncsAhnNum4TT > 0*/) {
					document.getElementById('Warning4P2').textContent =  theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameCurrent ;
				
				} else {
					document.getElementById('Warning4P2').textContent =  theTestTaker ;
				}
				document.getElementById('Warning4Rel').textContent =  rawRelationship;
				document.getElementById('FormDIV4TooFar4SimpleWarning').style.display = 'block';
				document.getElementById('NextButton4').style.display = 'none';

				if (theCurrentMatchesList.length > 1) {
					document.getElementById('Warning4Rel').textContent =  rawRelationship ;
					document.getElementById('MultipleRelationshipsChooseCloserWarning').style.display =  "block" ;
					// NOTE: There are multiple relationships between this pair of test takers.\n  Choose the closer relationship for the DNA Confirmation.
				} else {
					document.getElementById('MultipleRelationshipsChooseCloserWarning').style.display =  "none" ;
				}

			} else {
				document.getElementById('NextButton4').style.display = 'block';
				document.getElementById('FormDIV4TooFar4SimpleWarning').style.display = 'none';

				// CHECK for MULTIPLE COMMON ANCESTORS warning button - if exists - then hide the NEXT BUTTON until the warning is acknowledged
				if (document.getElementById("UnderstandMultipleButton") && document.getElementById("UnderstandMultipleButton").style.display != "none") {
					document.getElementById('NextButton4').style.display = 'none';
				}

			}
				 
			// let nameOf2ndTestTakerToUse = (generationsFromMRCA > 0 ? theTestTaker : theNextBestFirstName( personArray[ahnenIndexArrays[2][1]]) );
			howRelatedPhrase = nameOf2ndTestTakerToUse + " is "  + theNextBestFirstName( personArray[ahnenIndexArrays[1][1]])  + "'s " + howRelatedPhrase;
			document.getElementById('HowRelatedTheseTwo').innerHTML  = howRelatedPhrase + ".";

			console.log("B4 ", {DNApainterRelationship});
			
			if (theRel1ToUse - theRel1 > 0) {
				let realDNApainterRelationship = DNApainterRelationship;
				let definedRelationshipV0 = defineRelationshipsBetween(theAncsAhnNum, theAncsAhnNum4TT, 0, generationsFromMRCA , 1, 2) ;
				console.log({definedRelationshipV0});
				let workingRelationship0 = definedRelationshipV0[0].replace("his ","").replace("her ","").replace("their ","");

				console.log({TestTaker2Gender});
				console.log({workingRelationship0});

				let pronounLessRelationship = definedRelationship[0].replace("his ","").replace("her ","").replace("their ","");

				if (TestTaker2Gender == "Male") {
					workingRelationship0 = workingRelationship0.replace("dpibling","d uncle").replace("pibling","uncle").replace("sibling","brother").replace("parent","father");
					pronounLessRelationship = pronounLessRelationship.replace("dpibling","d uncle").replace("pibling","uncle").replace("sibling","brother").replace("parent","father");
				} else if (TestTaker2Gender == "Female") {
					workingRelationship0 = workingRelationship0.replace("dpibling","d aunt").replace("pibling","aunt").replace("sibling","sister").replace("parent","mother");
					pronounLessRelationship = pronounLessRelationship.replace("dpibling","d aunt").replace("pibling","aunt").replace("sibling","sister").replace("parent","mother");
				}

				howRelatedPhrase = nameOf2ndTestTakerToUse + " is "  + theNextBestFirstName( personArray[ahnenIndexArrays[1][1]])  + "'s " + workingRelationship0;
				

				document.getElementById('HowRelatedTheseTwo').innerHTML  = howRelatedPhrase + ".<br/>" + nameOf2ndTestTakerToUse + " is the "  + pronounLessRelationship + " of " + theNextBestFirstName( personArray[ahnenIndexArrays[1][1]])  + "'s " + (theTT1isActually ) + ".";
				DNApainterRelationship = realDNApainterRelationship;
			}

			if (DNApainterRelationship.indexOf("0C") > -1) {
				// we have an aunt/uncle relationship here .. need to swap out 0C1R etc.. for the words aunt/uncle
				DNApainterRelationship = DNApainterRelationship.replace("0C1R","Aunt %2F Uncle").replace("0C2R","Great-Aunt %2F Uncle").replace("half 0C3R","Half Great-Great-Aunt %2F Uncle").replace("0C3R","Great-Great-Aunt %2F Uncle");
			}

			console.log("AF ", {DNApainterRelationship});
			updateLegend4Arrows();
		
			let makeVerifySlashTextVisibleBeforeMARKtextDisplay = "none";
			if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus) {
				console.log("SETUP for VERIFY / MARK 3: ", personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Father, personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Mother, isCouple, theAncsAhnNum);
				if (isCouple == true) {
					// Choose VERIFY if both DataStatuses are == 30
					if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Father == 30 && personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Mother == 30) {
						makeVerifySlashTextVisibleBeforeMARKtextDisplay = "revert"; // revert to normal, inline-block
						document.getElementById('Div7CAVerifyRelationship').innerText = "Paternal relationship and the Maternal relationship (one joint one statement, or two separate ones)";
					}

					// ELSE ... choose MARK only (default)

				} else {
					// Is SINGLE ... so ... test for Gender of Ancestor (whether Ahnentafel# is even or odd) 
					if (theAncsAhnNum % 2 == 0) {
						// EVEN = MALE ancestor ... thus ... check for DataStatus.Father == 30, and if so ... then VERIFY
						if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Father == 30) {
							makeVerifySlashTextVisibleBeforeMARKtextDisplay = "revert"; // revert to normal, inline-block
							document.getElementById('Div7CAVerifyRelationship').innerText = "Paternal relationship";
						}
						// ELSE ... MARK / default
					} else {
						// ODD = FEMALE ancestor ... thus ... check for DataStatus.Mother == 30, and if so ... then VERIFY
						if (personArray[ ahnenIndexArrays[1][childNum] ].DataStatus.Mother == 30) {
							makeVerifySlashTextVisibleBeforeMARKtextDisplay = "revert"; // revert to normal, inline-block
							document.getElementById('Div7CAVerifyRelationship').innerText = "Maternal relationship";
						}
						// ELSE ... MARK
					}
				}
			} else {
				// Choose MARK only
				makeVerifySlashTextVisibleBeforeMARKtextDisplay = "none";
			}

			document.getElementById('Div7CAVerify1').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerify2').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerify3').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerify4').style.display = makeVerifySlashTextVisibleBeforeMARKtextDisplay;
			document.getElementById('Div7CAVerifyKid').innerText = childFullName;

			if (makeVerifySlashTextVisibleBeforeMARKtextDisplay == "none") {
				theMarkedVerifiedCheckbox = theMVCheckboxMarked;
			} else {
				theMarkedVerifiedCheckbox = theMVCheckboxVerified;
			}

			TopVerifyMarkCheckboxDIVhtml = theMarkedVerifiedCheckbox.replace("<div align=right>","").replace("</div>","").replace("123", "Top");
			document.getElementById('TopVerifyMarkCheckboxDIV').innerHTML  = TopVerifyMarkCheckboxDIVhtml;

			document.getElementById('Div7CAkid').innerHTML =  "<A target=_blank href=https://www.wikitree.com/wiki/" +  childWikiName.replace(" ","_").replace(" ","_") +">" + childFullName + "</A>";
			document.getElementById('Div7CAkid2').innerHTML = "<B>" + childFullName  + "</B> (" + childWikiName+ ")";
			document.getElementById('Div7CAkid3').textContent =  childFullName;

			document.getElementById("CAsourceCitation" ).style.backgroundColor = "white";
			document.getElementById("theInfoParaTop" ).style.backgroundColor = "white";

			return theTableHTML;
		}

		function setupGenderDIV(P1, P2, P3, isP2object, isP3object) {
			// alert ("SETUP Time for GENDER DIV");
			document.getElementById('GenderFormP1').textContent = theNextBestFirstName(P1) + " " + P1.LastNameCurrent;
			if (isP2object) {
				document.getElementById('GenderFormP2').textContent = theNextBestFirstName(P2) + " " + P2.LastNameCurrent;
			} else {
				document.getElementById('GenderFormP2').textContent = (P2);
			}


			if (P1.Gender == undefined || P1.Gender == "") {
				document.getElementById('GenderFormP1isMale').style.display = 'none';
				document.getElementById('GenderFormP1isFemale').style.display = 'none';
				document.getElementById('GenderFormP1isUndefined').style.display = 'revert';
			} else if (P1.Gender == "Male") {
				document.getElementById('GenderFormP1isMale').style.display = 'revert';
				document.getElementById('GenderFormP1isFemale').style.display = 'none';
				document.getElementById('GenderFormP1isUndefined').style.display = 'none';
			} else if (P1.Gender == "Female") {
				document.getElementById('GenderFormP1isMale').style.display = 'none';
				document.getElementById('GenderFormP1isFemale').style.display = 'revert';
				document.getElementById('GenderFormP1isUndefined').style.display = 'none';
			} 



		  	if (isP2object){	
	  			if (P2.Gender == undefined || P3.Gender == "" || P2.Gender == "") {
	  				document.getElementById('GenderFormP2isMale').style.display = 'none';
	  				document.getElementById('GenderFormP2isFemale').style.display = 'none';
	  				document.getElementById('GenderFormP2isUndefined').style.display = 'revert';
	  			} else if (P2.Gender == "Male") {
	  				document.getElementById('GenderFormP2isMale').style.display = 'revert';
	  				document.getElementById('GenderFormP2isFemale').style.display = 'none';
	  				document.getElementById('GenderFormP2isUndefined').style.display = 'none';
	  			} else if (P2.Gender == "Female") {
	  				document.getElementById('GenderFormP2isMale').style.display = 'none';
	  				document.getElementById('GenderFormP2isFemale').style.display = 'revert';
	  				document.getElementById('GenderFormP2isUndefined').style.display = 'none';
	  			} 
	  		} else {
	  			document.getElementById('GenderFormP2isMale').style.display = 'none';
  				document.getElementById('GenderFormP2isFemale').style.display = 'none';
  				document.getElementById('GenderFormP2isUndefined').style.display = 'revert';
	  		}

			if (P3 == '') {
				document.getElementById('GenderFormShowP3').style.display = 'none';

			} else {
				document.getElementById('GenderFormShowP3').style.display = 'revert';

				if (isP3object){
					document.getElementById('GenderFormP3').textContent = theNextBestFirstName(P3) + " " + P3.LastNameCurrent;
				} else {
					document.getElementById('GenderFormP3').textContent = (P3);
				}

				if (isP3object){
					if (P3.Gender == undefined || P3.Gender == "") {
						document.getElementById('GenderFormP3isMale').style.display = 'none';
						document.getElementById('GenderFormP3isFemale').style.display = 'none';
						document.getElementById('GenderFormP3isUndefined').style.display = 'revert';
					} else if (P3.Gender == "Male") {
						document.getElementById('GenderFormP3isMale').style.display = 'revert';
						document.getElementById('GenderFormP3isFemale').style.display = 'none';
						document.getElementById('GenderFormP3isUndefined').style.display = 'none';
					} else if (P3.Gender == "Female") {
						document.getElementById('GenderFormP3isMale').style.display = 'none';
						document.getElementById('GenderFormP3isFemale').style.display = 'revert';
						document.getElementById('GenderFormP3isUndefined').style.display = 'none';
					} 
				} else {
					document.getElementById('GenderFormP3isMale').style.display = 'none';
					document.getElementById('GenderFormP3isFemale').style.display = 'none';
					document.getElementById('GenderFormP3isUndefined').style.display = 'revert';
				}

			}

		}
		
		function genderCheckFromSimple( whereToNext = 0) {
			// alert ("test for Gender need  " + theProfilePerson.Gender + " vs " + theTestPerson.Gender );
			var needCheck = (theProfilePerson.Gender == undefined || theProfilePerson.Gender == "");
			var P2 = '';
			var isP2object = false;
			var isP3object = false;

			whichPathAfterGenderCheck = whereToNext;
			// alert ("genderCheckFromSimple : " + whichPathAfterGenderCheck);

			if (theTestPerson) {
				P2 = theTestPerson;
				isP2object = true;
				theProfilePerson2 = theTestPerson;
				if(!theTestPerson.Gender || theTestPerson.Gender == undefined || theTestPerson.Gender == "")  {
					needCheck = true;
					
				}
			} else {
				P2 = theTestTaker;
				needCheck = true;
			}


			if ( needCheck ) {
				setupGenderDIV(theProfilePerson, P2 , '', isP2object, isP3object);	
				document.getElementById('FormDIV1gender').style.display = 'block';

			}  else {

				followUpAfterLoadSimple();
			}
		}


		function genderCheckFromComplex( whereToNext = 0) {
			// alert ("test for Gender need  " + theProfilePerson.Gender + " vs " + theProfilePerson2.Gender  + " @ " + whereToNext);
			// alert ("Check 1");	
			var needCheck = (theProfilePerson.Gender == undefined || theProfilePerson.Gender == "");
			var P2 = '';
			var P3 = '';
			var isP2object = false;
			var isP3object = false;


			whichPathAfterGenderCheck = whereToNext;
			// alert ("genderCheckFromComplex : " + whichPathAfterGenderCheck);
			// alert ("Check 2 " + needCheck);
			if (theProfilePerson2) {
				P2 = theProfilePerson2;
				isP2object = true;
				
				if(!theProfilePerson2.Gender || theProfilePerson2.Gender == undefined || theProfilePerson2.Gender == "")  {
					needCheck = true;
					
				}
			} else {
				P2 = theID2;
				needCheck = true;
			}

			if (theDNAconfType == 'Tri' || (theDNAconfType == 'x' && theID3 > '')){
				// alert ("Check 3 " + needCheck);
				if (theProfilePerson3) {
					P3 = theProfilePerson3;
					isP3object = true;
					
					if(!theProfilePerson3.Gender || theProfilePerson3.Gender == undefined || theProfilePerson3.Gender == "")  {
						needCheck = true;
						
					}
				} else {
					P3 = theID3;
					needCheck = true;
				}
			}

			// alert ("Crossroads " + needCheck);
			if ( needCheck == true ) {
				// alert("Let's setupGenderDIV !");
				setupGenderDIV(theProfilePerson, P2 , P3, isP2object, isP3object);	
				document.getElementById('FormDIV1gender').style.display = 'block';
				document.getElementById('FormDIV1contacting').style.display = 'none';

			}  else {

				followUpAfterLoadComplex();
			}
		}

		function switchToSimple(p1, p2) {
			document.getElementById('FormDIV4direct').style.display = 'none';	

			document.getElementById('TypeOfConfTitle').innerHTML = '<H2>Simple Autosomal DNA Confirmation</H2>';
			document.getElementById('DNAhelp').innerHTML = "WikiTree Help page for <a target=_blank href='https://www.wikitree.com/wiki/Help:DNA_Confirmation'>DNA Confirmation</a>.";

			theDNAconfType = '1';

			closeEnough4Simple = [false, false, false];
			ahnenIndexArrays[3] = [];



			if (p2 == 1) {
				document.getElementById('TTaker2').value = theProfilePerson.Name;
				// alert("this should never happen p2 == 1");
				// theTestPerson = theProfilePerson;
			} else if (p2 == 2) {
				if (numGensFromWikiTreeID2toCousin > 0) {
					document.getElementById('TTaker2').value = theOriginalID2;
				} else {
					document.getElementById('TTaker2').value = theProfilePerson2.Name;
				}
				// theTestPerson = theProfilePerson2;
				// theTestPersonGender = theProfilePerson2GENDER;
			} else if (p2 == 3) {
				// document.getElementById('TTaker2').value = theProfilePerson3.Name;
				if (numGensFromWikiTreeID3toCousin > 0) {
					document.getElementById('TTaker2').value = theOriginalID3;
				} else {
					document.getElementById('TTaker2').value = theProfilePerson3.Name;
				}
				// theTestPerson = theProfilePerson3;
				// ahnenIndexArrays[2] = ahnenIndexArrays[3];
				// theTestPersonGender = theProfilePerson3GENDER;
			} 


			if (p1 == 1) {
				document.getElementById('TTaker1').value = theProfilePerson.Name;
				fillInID4TT1spawn();
				// normal state of affairs
				// theProfilePerson = theProfilePerson;				
			} else if (p1 == 2) {
				if (numGensFromWikiTreeID2toCousin > 0) {
					document.getElementById('TTaker1').value = theOriginalID2;
					fillInID4TT1spawn();
					if (p2 == 1) {
						document.getElementById('TTaker1').value = theProfilePerson.Name;	
						fillInID4TT1spawn();				
						document.getElementById('TTaker2').value = theOriginalID2;
					} 

				} else {
					document.getElementById('TTaker1').value = theProfilePerson2.Name;
					fillInID4TT1spawn();
				}
				// document.getElementById('TTaker1').value = theProfilePerson2.Name;
				// alert("this should never happen p1 == 2");
				// theProfilePerson = theProfilePerson2;
			} else if (p1 == 3) {
				// document.getElementById('TTaker1').value = theProfilePerson3.Name;
				if (numGensFromWikiTreeID3toCousin > 0) {
					document.getElementById('TTaker1').value = theOriginalID3;
					fillInID4TT1spawn();
					if (p2 == 1) {
						document.getElementById('TTaker1').value = theProfilePerson.Name;
						fillInID4TT1spawn();					
						document.getElementById('TTaker2').value = theOriginalID3;
					} 
				} else {
					document.getElementById('TTaker1').value = theProfilePerson3.Name;
					fillInID4TT1spawn();
				}
				// theProfilePerson = theProfilePerson3;
				// ahnenIndexArray = ahnenIndexArrays[3];
			} 

			doneForm1();


		// // COUSIN of some flavour is the 2nd Test Taker 
		// 	listOfAhns = "";
		// 	var MRCA = -1;
		// 	var MRCA2 = -1;
		// 	var MatchingIndices = [];

		// 	// alert ("Length is " + ahnenIndexArrays[2].length);

		// 	for (var i = 1; i <= 64 && MRCA == -1; i++) {
		// 		if (ahnenIndexArrays[2][i] > -1 ) {
		// 			if (personArray[ ahnenIndexArrays[2][i] ].FirstName ) {
		// 				listOfAhns += " "  + theNextBestFirstName( personArray[ ahnenIndexArrays[2][i] ]);
		// 				for (var j = 1; j < 64 && MRCA == -1; j++) {
		// 					if (ahnenIndexArrays[1][j] ) {
		// 						if (ahnenIndexArrays[1][j] == ahnenIndexArrays[2][i]) {
		// 							// we've found the MRCA
		// 							MRCA = ahnenIndexArrays[2][i];
		// 							theAncsAhnNum = j;
		// 							theAncsAhnNum4TT = i;
		// 							MatchingIndices = [j,i];
		// 							theAncestor = personArray[MRCA];
		// 							isCouple = false;
		// 							if (ahnenIndexArrays[1][j+1] && ahnenIndexArrays[2][i+1] && ahnenIndexArrays[1][j+1] == ahnenIndexArrays[2][i+1]) {
		// 								MRCA2 =ahnenIndexArrays[2][i + 1];
		// 								theAncsAhnNum2 = j + 1;
										
		// 								theAncestor2 = personArray[MRCA2];
		// 								isCouple = true;
		// 								// alert ("is Couple True for : " + ahnenIndexArrays[1][j+1] );
		// 							}
		// 						}
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// 	updateGoBackLinks(4);
		// 	followUpAfterLoadSimple();			
		}

		function followUpAfterLoadSimple() {
			console.log("followUpAfterLoadSimple : path = " + whichPathAfterGenderCheck);
			document.getElementById('FormDIV1gender').style.display = 'none';
			if (isDirectAncestor ==  true) {
				showDirectAncestorMiniTree(); //alert(1345);
			} else {
				if (whichPathAfterGenderCheck == 1) {
					updateGoBackLinks(4);
					// alert ("should now do MiniTree");
					updateMiniTree(); //alert(1347);
					document.getElementById('FormDIV4').style.display = "block";
					document.getElementById('IDgender').style.display = "none";
					howRelated2WikiTreeIDsWithMRCAfound( );

				} else if (whichPathAfterGenderCheck == 2) {
					document.getElementById('FormDIV2').style.display = "revert";
				} else 	if (whichPathAfterGenderCheck == 3) {
					loadTriInfo2();
				} else if (whichPathAfterGenderCheck == 4) {
					// alert ("start loadComplexInfo");
					loadComplexInfo();	
				}
			}
		}
		function followUpAfterLoadComplex() {
			document.getElementById('FormDIV1gender').style.display = 'none';
			
				if (whichPathAfterGenderCheck == 3) {
					loadTriInfo2();
				} else if (whichPathAfterGenderCheck == 4) {
					loadComplexInfo();
				}
			
		}

		

		function howRelated2WikiTreeIDsWithMRCAfound( whichStep = 3) {
			if (isCouple == true && theAncsAhnNum > 1) {
				theAncsAhnNum2 = theAncsAhnNum + 1;
			}
			console.log("function howRelated2WikiTreeIDsWithMRCAfound( " + whichStep + ") - relCA2P1 = ",relCA2P1, "isCouple:",isCouple, "theAncsAhnNum:", theAncsAhnNum, "theAncsAhnNum:", theAncsAhnNum);
			// alert("How Related");
			theRel = Math.floor(Math.log2(theAncsAhnNum));
			let newRel = "";
			// alert ("howRelated = " + theValue);
			// theOtherStep = 7 - whichStep;
			// document.getElementById('relationship' + theOtherStep).value =/* optionsArray[theValue - 1];// */ document.getElementById('relationship' + whichStep).value;

			CArelateds = ['none',  'child', 'grandchild', 'great grandchild', 'great great grandchild', '3x great grandchild'];
			CArelationships = ['self',  'parent', 'Grandparent', 'Great Grandparent', 'Great Great Grandparent', '3x Great Grandparent'];
			var ancFullName = "the Common Ancestor";
			var isThe = " is the ";
			theTypeOfRel = CArelationships[theRel];

			if (theAncestor) {
				ancFullName = theNextBestFirstName(theAncestor) + " " + theAncestor.LastNameAtBirth;
			}
			if (isCouple) {
				// isThe = ' are the '; ... that's an oops ! A single person will always be an "IS THE" ... DOOHH!!!! overzealous is/are police !
				ancFullName += " & " + theNextBestFirstName(theAncestor2) + " " + theAncestor2.LastNameAtBirth;
			}
			
			if (theAncsAhnNum > 1) {
				if ( ahnenIndexArrays[1][ theAncsAhnNum])	{
					if ( personArray[ ahnenIndexArrays[1][ theAncsAhnNum]] ) {
						theAncestor = personArray[ ahnenIndexArrays[1][ theAncsAhnNum]];
						var ancFullName = theNextBestFirstName(theAncestor) + " " + theAncestor.LastNameAtBirth;
 						// alert ("theTypeOfRel = "+ theTypeOfRel);
						if (theRel && theRel >= 2) {

							if (theAncestor.Gender == 'Female') {
								newRel = theTypeOfRel.replace("parent","mother").replace("Grand", 'grand').replace("Great", 'great').replace("Great", 'great');
							} else if (theAncestor.Gender == 'Male') {
								newRel = theTypeOfRel.replace("parent","father").replace("Grand", 'grand').replace("Great", 'great').replace("Great", 'great');
							} else {
								// alert ("GENDER = " + theAncestor.Gender);
							}

						} else {
							// alert ("theRel = "+ theRel);
							if (theAncsAhnNum == 2) {
								newRel = 'father';
							} else if (theAncsAhnNum == 3) {
								newRel = 'mother';
							}
						}
					}
				}
			} 
			if (theAncsAhnNum2 > 1) {
				if ( ahnenIndexArrays[1][ theAncsAhnNum2])	{
					if ( personArray[ ahnenIndexArrays[1][ theAncsAhnNum2]] ) {
						theAncestor2 = personArray[ ahnenIndexArrays[1][ theAncsAhnNum2]];
						ancFullName += " & " + theNextBestFirstName(theAncestor2) + " " + theAncestor2.LastNameAtBirth;
						// document.getElementById('Div4P3').textContent = ancFullName;
						document.getElementById('Div3CAa').textContent =  ancFullName;		
						document.getElementById('Div3CAb').textContent =  ancFullName;		
						document.getElementById('Div3CAc').textContent =  ancFullName;		
						// alert ("theRel = "+ theRel);
						// alert ("GENDER = " + theAncestor.Gender);
						// alert ("theTypeOfRel = "+ theTypeOfRel);
						if (theRel && theRel >= 2) {

							
								newRel = theTypeOfRel.replace("mother","parents").replace("father","parents").replace("parent","parents").replace("parentss","parents").replace("Grand", 'grand').replace("Great", 'great').replace("Great", 'great').replace("Great", 'great');
								
							

						} else {
							// alert ("theRel = "+ theRel);
							
								newRel = 'parents';
							
						}
					}
				}
			} 

			if (newRel) {
				relCA2P1 = newRel;
				console.log("relCA2P1 = ", relCA2P1);
			}

			// alert("theRel vs theType vs New of rel vs AhnNum\n" + theRel + "\n" + theTypeOfRel + "\n" + newRel + "\n" + theAncsAhnNum);
			
			document.getElementById('TreeP1rel2CA').textContent =  theNextBestFirstName( theProfilePerson) + "'s " + newRel ;

			document.getElementById('FormDIV4').style.display = "block";
		}
			
		function changeRelationship() {
			theRel = document.getElementById('relationship').value;
			theRel1 = theRel;	
			console.log("Change relationship to ", theRel);
			theTypeOfRel = "";
			// alert(theRel);
			if (theRel >= 7) {
				showDIV('TooDistant', 'FormDIV2');
			} else {
				// alert ("changeRelationship: " + theRel);
			}
			var divA = "2. <select id=whichMaPa name=mapa onchange='changeAncestor();'> <option>Choose Specific Ancestor</option> ";
			var divX ="";
			var divZ = "</select>";

			var div3A = "<select id=relationship3 name=relationship3 onchange='howRelated(3);'><option>Choose relationship</option>";
			var div4A = "<select id=relationship4 name=relationship4 onchange='howRelated(4);'><option>Choose relationship</option>";
			var div3Options = "";
			var div3Z = "<option>---</option><option value=7>Beyond ...</option></select>";

			var typeOfAncestor = ['Child','Self', 'Parent', "Grandparent", 'Great Grandparent', 'Great Great Grandparent', '3rd Great Grandparent'];
			var howCArelatedToP2 = "";
			if (theRel > 1 && theRel < 7){
				theTypeOfRel = typeOfAncestor[theRel];
				console.log({theTypeOfRel});
				var a1 = 2 ** (1*theRel - 1*1) ;
				var a2 = 2 ** theRel;
				var prevName = "";
				console.log({a1},{a2});

				// alert ("From " + a1 + " to " + a2);
				for (var i = a1; i < a2; i++) {
					if (ahnenIndexArrays[1][i] > 0) {
						if (personArray [ahnenIndexArrays[1][i]]) {
							var FullName = theNextBestFirstName(personArray[ahnenIndexArrays[1][i]]) + " " + personArray [ahnenIndexArrays[1][i]].LastNameAtBirth;
							divX += " <option value=" + i + ">"  + FullName + "</option>";
							if (i % 2 == 0) {
								prevName = theNextBestFirstName(personArray[ahnenIndexArrays[1][i]]);
							} else {
								if (prevName > '') {
									divX += " <option value='" + (i - 1) + "," + i + "'>"  + prevName + " + " + theNextBestFirstName(personArray[ahnenIndexArrays[1][i]]) + "</option>";
									divX += " <option value='-' />";
								}
							}

						}
					} else {
						prevName = "";
					}

				}

				if (theRel == 2) {
					optionsArray = ['Sibling', "Niece/Nephew", 'Grand Niece/Nephew', 'Great Grand Niece/Nephew', '2x Great Grand Niece/Nephew'];
				} else if (theRel == 3) {
					optionsArray = ['Aunt/Uncle', "1st Cousin", '1st Cousin once removed', '1st Cousin twice removed', '1st Cousin thrice removed'];
				} else if (theRel == 4) {
					optionsArray = ['Great Aunt/Uncle', "1st Cousin once removed", '2nd Cousin', '2nd Cousin once removed', '2nd Cousin twice removed'];
				} else if (theRel == 5) {
					optionsArray = ['Great Grand Aunt/Uncle', "1st Cousin twice removed", '2nd Cousin once removed', '3rd Cousin', '3rd Cousin once removed'];
				} else if (theRel == 6) {
					optionsArray = ['2x Great Grand Aunt/Uncle', "1st Cousin thrice removed", '2nd Cousin twice removed', '3rd Cousin once removed', '4th Cousin'];
				}
				
				for (var opts = 0; opts < 5; opts++) {
					div3Options += "<option value=" + (1*opts + 1*1) + ">" + optionsArray[opts] + "</option>";
				}
				
						
			}
			// alert(theRel);
			document.getElementById('Div2Q2select').innerHTML = divA + divX + divZ;
			// document.getElementById('Div4P1a').textContent = theNextBestFirstName( theProfilePerson) + "'s " + theTypeOfRel;
			// alert (div3Options);
			// document.getElementById('Div3SelectOptions').innerHTML = div3A +  div3Options + div3Z;

			
			// document.getElementById('Div4SelectOptions').innerHTML = div4A +  div3Options + div3Z;
		}

		function changeAncestor() {
			theAncsAhnNum =  document.getElementById('whichMaPa').value;
			theRel = document.getElementById('relationship').value;
			isCouple = (theAncsAhnNum.search(",") > 0);
			showNextButton(2);
			let newRel = theRel;
			console.log ("ChangeAncestor: " + theAncsAhnNum + " ; " + theRel + "; couple:" + isCouple);
				
			let theMatchesList = [0,theAncsAhnNum,1];
			ahnenIndexArrays[2] = [0, 0 , 0];
			ahnenIndexArrays[2][1] = ahnenIndexArrays[1][ theAncsAhnNum];

			if (isCouple) {
				ahnNums = theAncsAhnNum.split(',');
				theAncsAhnNum = ahnNums[0];
				theAncsAhnNum2 = ahnNums[1];
				document.getElementById('AncS1').textContent =  's';		
				document.getElementById('AncS2').textContent =  's';		
				document.getElementById('AncS3').textContent =  's';		
				document.getElementById('AncS4').textContent =  'S';		
				document.getElementById('IsAre1').textContent =  'are';		
				document.getElementById('IsAre2').textContent =  'are';		
				document.getElementById('IsAre3').textContent =  'are';		
				document.getElementById('VerbS1').textContent =  '';	
				theMatchesList = [2,theAncsAhnNum,1];
				ahnenIndexArrays[2][2] = ahnenIndexArrays[1][ theAncsAhnNum2];
			}  else {
				theAncsAhnNum2 = 0;
				document.getElementById('AncS1').textContent =  '';		
				document.getElementById('AncS2').textContent =  '';		
				document.getElementById('AncS3').textContent =  '';		
				document.getElementById('AncS4').textContent =  '';		
				document.getElementById('IsAre1').textContent =  'is';		
				document.getElementById('IsAre2').textContent =  'is';		
				document.getElementById('IsAre3').textContent =  'is';		
				document.getElementById('VerbS1').textContent =  's';	
				theMatchesList = [1,theAncsAhnNum,1];
			}

			theCurrentMatchesList = [theMatchesList];

			if (theAncsAhnNum > 1) {
				if ( ahnenIndexArrays[1][ theAncsAhnNum])	{
					if ( personArray[ ahnenIndexArrays[1][ theAncsAhnNum]] ) {
						theAncestor = personArray[ ahnenIndexArrays[1][ theAncsAhnNum]];
						var ancFullName = theNextBestFirstName(theAncestor) + " " + theAncestor.LastNameAtBirth;
						// document.getElementById('Div4P3').textContent = ancFullName;
						document.getElementById('Div3CAa').textContent =  ancFullName;		
						document.getElementById('Div3CAb').textContent =  ancFullName;		
						document.getElementById('Div3CAc').textContent =  ancFullName;		
						// alert ("theRel = "+ theRel);
						// alert ("GENDER = " + theAncestor.Gender);
						// alert ("theTypeOfRel = "+ theTypeOfRel);
						if (theRel && theRel > 2) {

							if (theAncestor.Gender == 'Female') {
								newRel = theTypeOfRel.replace("parent","mother").replace("Grand", 'grand').replace("Great", 'great').replace("Great", 'great');
							} else if (theAncestor.Gender == 'Male') {
								newRel = theTypeOfRel.replace("parent","father").replace("Grand", 'grand').replace("Great", 'great').replace("Great", 'great');
							} else {
								// alert ("GENDER = " + theAncestor.Gender);
							}

						} else {
							// alert ("theRel = "+ theRel);
							if (theAncsAhnNum == 2) {
								newRel = 'father';
							} else if (theAncsAhnNum == 3) {
								newRel = 'mother';
							}
						}
					}
				}
			} 
			if (theAncsAhnNum2 > 1) {
				if ( ahnenIndexArrays[1][ theAncsAhnNum2])	{
					if ( personArray[ ahnenIndexArrays[1][ theAncsAhnNum2]] ) {
						
						theAncestor2 = personArray[ ahnenIndexArrays[1][ theAncsAhnNum2]];
						ancFullName += " & " + theNextBestFirstName(theAncestor2) + " " + theAncestor2.LastNameAtBirth;
						// document.getElementById('Div4P3').textContent = ancFullName;
						document.getElementById('Div3CAa').textContent =  ancFullName;		
						document.getElementById('Div3CAb').textContent =  ancFullName;		
						document.getElementById('Div3CAc').textContent =  ancFullName;		
						// alert ("theRel = "+ theRel);
						// alert ("GENDER = " + theAncestor.Gender);
						// alert ("theTypeOfRel = "+ theTypeOfRel);
						if (theRel && theRel > 2) {

							
								newRel = theTypeOfRel.replace("mother","parents").replace("father","parents").replace("parent","parents").replace("parentss","parents").replace("Grand", 'grand').replace("Great", 'great').replace("Great", 'great');
								
							

						} else {
							// alert ("theRel = "+ theRel);
							
								newRel = 'parents';
							
						}
					}
				}
			} 

			relCA2P1 = newRel;
			console.log("relCA2P1 = ", relCA2P1);

			// alert("theRel vs theType vs New of rel vs AhnNum\n" + theRel + "\n" + theTypeOfRel + "\n" + newRel + "\n" + theAncsAhnNum);
			document.getElementById('Div3CAofP1').textContent = "the " + newRel + " of " + theNextBestFirstName( theProfilePerson) ;						
			document.getElementById('TreeP1rel2CA').textContent =  theNextBestFirstName( theProfilePerson) + "'s " + newRel ;						
			document.getElementById('TreeP2rel2CA').textContent =  theTestTaker + "'s " + newRel ;		
			document.getElementById('Div7CAa').textContent =  ancFullName ;		
			document.getElementById('Div7CAb').textContent =  ancFullName ;		






			if (isCouple == true) {
				theSelectOptions =	"<select id=relationship3 name=relationship3 onchange=howRelated(3);><option>Choose relationship</option><option value=1>Parents</option><option value=2>Grandparents</option><option value=3>Great Grandparents</option><option value=4>Great Great Grandparents</option><option value=5>3x Great Grandparents</option><option>---</option><option>Beyond 3x Great Grandparents</option></select>"
				document.getElementById('Div3SelectOptions').innerHTML = theSelectOptions;
				document.getElementById('DNAimageCodeMother').style.display = 'none';
				document.getElementById('DNAimageCodeFather').style.display = 'none';
				document.getElementById('DNAimageCodeParents').style.display = 'block';
				document.getElementById('DNAimageCodeParentsConfident').style.display = 'none';
			} else if (theAncsAhnNum % 2 == 0) {
				theSelectOptions =	"<select id=relationship3 name=relationship3 onchange=howRelated(3);><option>Choose relationship</option><option value=1>Father</option><option value=2>Grandfather</option><option value=3>Great Grandfather</option><option value=4>Great Great Grandfather</option><option value=5>3x Great Grandfather</option><option>---</option><option>Beyond 3x Great Grandfather</option></select>"
				document.getElementById('Div3SelectOptions').innerHTML = theSelectOptions;
				document.getElementById('DNAimageCodeMother').style.display = 'none';
				document.getElementById('DNAimageCodeFather').style.display = 'block';
				document.getElementById('DNAimageCodeParents').style.display = 'none';
				document.getElementById('DNAimageCodeParentsConfident').style.display = 'none';
			} else {
				theSelectOptions =	"<select id=relationship3 name=relationship3 onchange=howRelated(3);><option>Choose relationship</option><option value=1>Mother</option><option value=2>Grandmother</option><option value=3>Great Grandmother</option><option value=4>Great Great Grandmother</option><option value=5>3x Great Grandmother</option><option>---</option><option>Beyond 3x Great Grandmother</option></select>"
				document.getElementById('Div3SelectOptions').innerHTML = theSelectOptions;
				document.getElementById('DNAimageCodeMother').style.display = 'block';
				document.getElementById('DNAimageCodeFather').style.display = 'none';
				document.getElementById('DNAimageCodeParents').style.display = 'none';
				document.getElementById('DNAimageCodeParentsConfident').style.display = 'none';
			}

			updateMiniTree();				
			showNextButton(2);
			
		}	

		
		function updateMiniTree() {
			console.log({theCurrentMatchesList});
			console.log("BEFORE LOOP",{theAncestor});

			if (theCurrentMatchesList.length > 0) {
				let theTableHTML = buildHTMLcodeForTable(theCurrentMatchesList[0], generationsFromMRCA);
				// document.getElementById("FormDIV4Experimental").style.display = "block";
				document.getElementById("SimpleMiniTreeTable").innerHTML = theTableHTML;
				document.getElementById("OldSimpleMiniTreeTable").style.display = "none";
				whichMiniTree2Use = "SimpleMiniTreeTable";

			}
			console.log("AFTER LOOP",{theAncestor});

			var ordinals = ['0th', '1st', '2nd', '3rd', '4th', '5th', '6th', '7th'];
			var mod2MF = ['M', 'F'];
			var ancFullName = theNextBestFirstName(theAncestor) + " " + theAncestor.LastNameAtBirth;
			
			
			
			setupCustomCitationFields();
		}

		function getMiniTreeHTMLForStep7(miniTreeId) {
			const miniTree = document.getElementById(miniTreeId);
			const miniTreeCopy = miniTree.cloneNode(true);

			for (const select of miniTree.querySelectorAll('select[id^="Peep2AncDDown"]')) {
				const selectedOption = select.options[select.selectedIndex];
				const copiedSelect = miniTreeCopy.querySelector('[id="' + select.id + '"]');
				const selectedOptionText = document.createElement("strong");
				selectedOptionText.textContent = selectedOption ? selectedOption.textContent : "";
				copiedSelect.replaceWith(selectedOptionText);
			}

			// Decided that gender not needed in MiniTree in Step 7 - so replace with space
			for (const radioGroup of miniTree.querySelectorAll('div[id^="P2peepRadios"]')) {
				const selectedRadio = radioGroup.querySelector('input[type="radio"]:checked');
				const copiedRadioGroup = miniTreeCopy.querySelector('[id="' + radioGroup.id + '"]');
				copiedRadioGroup.textContent = ""; // selectedRadio
					// ? (selectedRadio.value == "M" ? "male" : "female")
					// : "gender not specified";
			}

			return miniTreeCopy.innerHTML;
		}

		

		function hideAllDropDownsInMiniTree() {
			for (var i = 1; i < 7 ; i++) {
				thisDD = document.getElementById('P2peepDDown' + i);
				if (thisDD)  {
					thisDD.innerHTML = "";
				}
			}
		}

		function getAncPeepAtDropDown( num ) {
			if (document.getElementById('Peep2AncDDown' + num) ) {

				let theAncID  = document.getElementById('Peep2AncDDown' + num).value;
				if (!theAncID || theAncID < 0) {
					return null;
				}
				for (let i = 0; i < mrcaDescendantsArray.length; i++) {
					let thisD = mrcaDescendantsArray[i];
					if (thisD.Id == theAncID) {
						return thisD;
					}
				}
			}
			return null;
		}


		function updatePeepDDs( selectNum, howManyGens) {
			// howManyGens was the original theValue from the "How is the other DNA test taker related?" --> and gets decremented at each level down the miniTree

			let theAncID  = document.getElementById('Peep2AncDDown' + selectNum).value;
			console.log("updatePeepDDs -> ", selectNum, theValue, theAncID);	

			if (theAncID < 0) {
				// ancestor not on WikiTree ... so ... we need to hide all subsequent drop downs, if they are not already hidden
				for (let i = selectNum + 1; i <= 5; i++) {
					document.getElementById('P2peepDDown' + i).innerHTML = "";	
				}
				// value = -2 if father // -3 if mother - set gender radio button appropriately
				let updatedGender = "M"; // choose one .... change if not with the if stmt below
				if (theAncID == -3) { updatedGender = "F";}

				let theMFradioBtn = document.getElementById("P2peep" + selectNum + "Val" + updatedGender);
				changeP2CAsubGender(selectNum, updatedGender);
				if (theMFradioBtn) {
					theMFradioBtn.checked = true;
					// theMFradioBtn.selected = true;
				}

			} else {
				// UPDATE the GENDER RADIO BUTTONS

				// personArray / theAncestor / theAncestor2
				console.log("isCouple : ",  isCouple);

				console.log("theAncestor", theAncestor);

				 

				if (selectNum == 1) {
					let penultimateAnc1 = personArray[ ahnenIndexArrays[1][Math.floor(theAncsAhnNum/2)]];
					let peepFromSide2 = getAncPeepAtDropDown( 1 )  ;
					console.log("penultimate ancestor on Side 1: ", penultimateAnc1 );
					console.log("person from Side 2 - DropDown 1: ", peepFromSide2);
					// CHECK to see if the Chosen CHILD of the MRCA is a child of BOTH or just ONE of the parents of the DNA Tester 1's penultimate ancestor
					if (isCouple == false) {
						// SINGLE MRCA person --> check to see if DROP DOWN picked a FULL SIBLING
						if (penultimateAnc1.Father == peepFromSide2.Father && penultimateAnc1.Mother == peepFromSide2.Mother && peepFromSide2.DataStatus.Father != 5 && peepFromSide2.DataStatus.Mother != 5) {
							console.log("WARNING WARNING --> FULLL SIBLING ALERT !!!!");
							isCouple = true;
							if (theAncsAhnNum % 2 == 0) {
								// the sole MRCA selected ancestor is MALE, so no change needed
								theAncestor2 = personArray[ ahnenIndexArrays[1][ theAncsAhnNum - 0 + 1]];

							} else {
								// the sole MRCA seleted was FEMALE - so - we need to subtract one to get the MALE of the MRCA couple, for the rest of the logic to work.
								console.log("the sole MRCA seleted was FEMALE - so - we need to subtract one to get the MALE of the MRCA couple, for the rest of the logic to work.")
								theAncsAhnNum -= 1;
								theAncestor = personArray[ ahnenIndexArrays[1][ theAncsAhnNum ]];
								theAncestor2 = personArray[ ahnenIndexArrays[1][ theAncsAhnNum - 0 + 1]];

							}
						} else {
							console.log("OK - must be a HALF sibling - I can live with that");
						}

					} else {
						// IS a full MRCA couple .... better MAKE SURE that the child picked is STILL a FULL SIBLING
						if (penultimateAnc1.Father == peepFromSide2.Father && penultimateAnc1.Mother == peepFromSide2.Mother ) {
							console.log("HURRAY --> Still a FULL SIBLING  !!!!");

						} else {
							console.log("WARNING WARNING --> HALF SIBLING ALERT !!!!!");
						}
					}

				}

				let thisNewPeep = getAncPeepAtDropDown( selectNum )  ;
				if (thisNewPeep.Gender){
					console.log("GENDER ALERT:  We should change all of the references at level ", selectNum, " to " , thisNewPeep.Gender);
					changeP2CAsubGender(selectNum, thisNewPeep.Gender.substring(0,1));
					let theMFradioBtn = document.getElementById("P2peep" + selectNum + "Val" + thisNewPeep.Gender.substring(0,1));
					if (theMFradioBtn) {
						theMFradioBtn.checked = true;
						// theMFradioBtn.selected = true;
					}
				}


				if (howManyGens > 2) {
					// setup a new SELECT drop down for the next generation, as long as there is at least one more generation to go
					let newSelectNum = selectNum - 0 + 1;
					let newGenNum = howManyGens - 1;

					let pickAncDDownHTML = "<select id=Peep2AncDDown" + newSelectNum + " onchange='updatePeepDDs(" + newSelectNum + "," + newGenNum + ");'><option>Choose one:</option>";
					for (let i = 0; i < mrcaDescendantsArray.length; i++) {
						let thisD = mrcaDescendantsArray[i];
						if ((thisD.Father == theAncID || thisD.Mother == theAncID) ) {
							console.log("loadDescendantsOfMRCA : NEXT DROP DOWN : ", thisD.RealName, thisD.LastNameAtBirth);
							pickAncDDownHTML += "<option value=" + thisD.Id + ">" + thisD.RealName + " " + thisD.LastNameAtBirth + "</option>";
						}
					}


					pickAncDDownHTML += "<option value=-3>" + howGreatIsThisOrNot(newGenNum) + "mother not on WikiTree</option>";
					pickAncDDownHTML += "<option value=-2>" + howGreatIsThisOrNot(newGenNum) + "father not on WikiTree</option>";
					pickAncDDownHTML += "</select>";
					document.getElementById('P2peepDDown' + newSelectNum ).innerHTML = pickAncDDownHTML;
					document.getElementById('P2peepDDown' + newSelectNum ).style.display = "revert";
					for (let i = newSelectNum + 1; i <= 5; i++) {
						document.getElementById('P2peepDDown' + i).innerHTML = "";	
					}
				}

			}
		}


		function howRelated( whichStep = 3) {
			theValue =  document.getElementById('relationship' + whichStep).value;
			generationsFromMRCA = theValue;

			if (theValue >= 6) {
				showDIV('TooDistant', 'FormDIV3');
			} else if (theValue > 1) {
				// console.log ("howRelated : " + theValue);
				console.log("THIS would be a GREAT TIME to load the DESCENDANTS of the MRCA - value = ", theValue);
				loadDescendantsOfMRCA(theValue);
			} else if (theValue <= 1) {
				document.getElementById('P2peepDDown1').style.display = "none";
			}
			// alert ("howRelated = " + theValue);
			// theOtherStep = 7 - whichStep;
			// document.getElementById('relationship' + theOtherStep).value =/* optionsArray[theValue - 1];// */ document.getElementById('relationship' + whichStep).value;

			CArelateds = ['none',  'child', 'grandchild', 'great grandchild', 'great great grandchild', '3x great grandchild'];
			var ancFullName = "the Common Ancestor";
			var isThe = " is the ";
			ahnenIndexArrays[2] = [];
			if (theAncestor) {
				ancFullName = theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor);//.LastNameAtBirth;
				ahnenIndexArrays[2][1] = theAncestor.Id;
			}
			if (isCouple) {
				// isThe = ' are the '; ... that's an oops ! A single person will always be an "IS THE" ... DOOHH!!!! overzealous is/are police !
				ancFullName += " & " + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2);//.LastNameAtBirth;
				ahnenIndexArrays[2][2] = theAncestor2.Id;
			}

			howCArelatedToP2 = "and " + theTestTaker +  isThe + CArelateds[theValue] + " of " + ancFullName;
			document.getElementById('Div3CA2P2').textContent = howCArelatedToP2;

			document.getElementById('FormDIV4').style.display = "block";
			document.getElementById('SimpleMiniTreeTable').style.display = "block";
			document.getElementById("OldSimpleMiniTreeTable").style.display = "none";
			// updateMiniTree();


			// buildHTMLcodeForTable( matchInfoArray , generationsFromMRCA = 0 ) {
			let theTableHTML = buildHTMLcodeForTable( [ isCouple ? 2 : 1 , theAncsAhnNum , 1], theValue);
			console.log("UPDATING the SimpleMiniTreeTable from the HOW RELATED function");
			document.getElementById("SimpleMiniTreeTable").innerHTML = theTableHTML;
			whichMiniTree2Use = "SimpleMiniTreeTable";

			showNextButton(3);
			// if (whichStep == 3) {
			// 	numGensFromWikiTreeID2toCousin = 1;
			// }
		}

		function howRemoved( whichStep = 3) {
			theValue =  document.getElementById('removed' + whichStep).value;
			theOtherStep = 7 - whichStep;
			document.getElementById('removed' + theOtherStep).value = document.getElementById('removed' + whichStep).value;
			updateMiniTree();
		}


		function changeP2CAsubGender(level, gender) {
			// alert ("Changing the " + level + "th level child / grandchild of Common Ancestor to a " + gender);
			P2peepsGender[level] = gender;
			// updateMiniTree();
		}

		function changedDNAcompany() {
			// alert("You just changed your DNA company!");
			
			Val = document.getElementById('DNAcompany').value  ;
			// alert (Val);
			if (Val == '23andMe') {
				document.getElementById('DNAcMnote23andMe').style.display = 'revert';
				document.getElementById('DNAsegmentsFTDNA').style.display = 'none';
				document.getElementById('DNAnote4GEDmatch').style.display = 'none';
			} else if (Val == 'Family Tree DNA') {
				document.getElementById('DNAsegmentsFTDNA').style.display = 'revert';
				document.getElementById('DNAcMnote23andMe').style.display = 'none';
				document.getElementById('DNAnote4GEDmatch').style.display = 'none';
			} else if (Val == 'GEDmatch') {
				document.getElementById('DNAnote4GEDmatch').style.display = 'revert';
				document.getElementById('DNAcMnote23andMe').style.display = 'none';
				document.getElementById('DNAsegmentsFTDNA').style.display = 'none';
				// setupGEDmatchDIV();

			} else {
				document.getElementById('DNAsegmentsFTDNA').style.display = 'none';
				document.getElementById('DNAcMnote23andMe').style.display = 'none';
				document.getElementById('DNAnote4GEDmatch').style.display = 'none';
			}

			updateDNApainterLink();

		}

		function setupGEDmatchDIV(divName = 'DNAnote4GEDmatch') {
			let theHTML = "";//<HR/><BR><B>Kit Numbers</B>";
			for (var i = 1; i < ahnenIndexArrays.length; i++) {
				theHTML += "<BR><I>GEDmatch kit # for "+ theNextBestFirstNameLastName(ahnenIndexArrays[i][1]) + "</I> :<BR><input id=DNAkitGM"+i+" type=text value='"+kitNums[i + "-gedmatch" ]+"'>";
			}

			document.getElementById(divName).innerHTML = theHTML;
										
		}

		function setupFTDNAspan(divName = 'TRInoteFTDNACheckboxes') {
			let theHTML = "";//<HR/><BR><B>Kit Numbers</B>";
			let theChromoNum = document.getElementById("ChromoNum").value;
			// <I><BR><BR><B><span id=TriPAfn1>Person A</span> & <span id=TriPBfn1>Person B</span> </B>: DNA match on chromosome <span id=ChromoN1>N</span> :</I><BR> <input type=checkbox id=VerifABbyA name=VerifABbyA value='Y'> verified by <span id=TriPAfnln1>Person A</span>  <input type=checkbox id=VerifABbyB name=VerifABbyB value='Y'> verified by <span id=TriPBfnln1>Person B</span>  <BR>

			for (var i = 1; i < ahnenIndexArrays.length - 1; i++) {
				for (var j = i + 1; j < ahnenIndexArrays.length; j++) {
				theHTML += "<I><BR><B>" + theNextBestFirstName(personArray[ahnenIndexArrays[i][1]]) + " & " + theNextBestFirstName(personArray[ahnenIndexArrays[j][1]]) + " </B>: DNA match on chromosome "+theChromoNum+" :</I><BR> <input type=checkbox id=Verif" + i + j + "by" + i + " value='Y'> verified by " + theNextBestFirstNameLastName(ahnenIndexArrays[i][1]) + "  <input type=checkbox id=Verif" + i + j + "by" + j + " value='Y'> verified by " + theNextBestFirstNameLastName(ahnenIndexArrays[j][1]) + "  <BR> ";
				}
			}

			document.getElementById(divName).innerHTML = theHTML;
										
		}

		function setup23AndMeDIV(divName = 'TRInote23andMeCompares') {
			let theHTML = "";//<HR/><BR><B>Kit Numbers</B>";
			//<span id=TRInote23andMeCompares>
							
			for (var i = 1; i < ahnenIndexArrays.length; i++) {
				let theOthers = "";
				let lastJ = ahnenIndexArrays.length - 1;
				if (i == lastJ) {
					lastJ -= 1;
				}
				for (var j = 1; j < ahnenIndexArrays.length; j++) {
					if (i!=j) {
						if (theOthers > "") {
							theOthers += ", ";
						}
						if (j == lastJ) {
							theOthers += "and ";
						}
						if(ahnenIndexArrays.length > 4) {
							theOthers += theNextBestFirstName(personArray[ahnenIndexArrays[j][1]]);

						} else {
							theOthers += theNextBestFirstNameLastName(ahnenIndexArrays[j][1]);
						}
					}
				}
				theOthers += ".";

				theHTML += "<BR><B>Compare</B>  "+ theNextBestFirstNameLastName(ahnenIndexArrays[i][1]) + "  <B>with</B> "+ theOthers;
			}

			document.getElementById("TRInote23andMeCompares").innerHTML = theHTML;
										
		}

		function changedTriDNAcompany() {
			// alert("You just changed your DNA company!");
			

			Val = document.getElementById('DNAcompany3').value  ;
			// alert (Val);
			if (Val == '23andMe') {
				document.getElementById('TRInote23andMe').style.display = 'revert';
				document.getElementById('TRInoteMyHeritage').style.display = 'none';
				document.getElementById('TRIverification').style.display = 'none';
				document.getElementById('TRInote4GEDmatch').style.display = 'none';
				setup23AndMeDIV();
			
			} else if (Val == 'GEDmatch') {
				document.getElementById('TRInote4GEDmatch').style.display = 'revert';
				document.getElementById('TRInote23andMe').style.display = 'none';
				document.getElementById('TRInoteMyHeritage').style.display = 'none';
				document.getElementById('TRIverification').style.display = 'none';
				setupGEDmatchDIV("TriGEDmatchKitsList");
			} else if (Val == 'MyHeritageDNA') {
				document.getElementById('TRInoteMyHeritage').style.display = 'revert';
				document.getElementById('TRInote4GEDmatch').style.display = 'none';
				document.getElementById('TRInote23andMe').style.display = 'none';
				document.getElementById('TRIverification').style.display = 'none';
				setupFTDNAspan();
			} else {
				document.getElementById('TRIverification').style.display = 'revert';
				document.getElementById('TRInote23andMe').style.display = 'none';
				document.getElementById('TRInoteMyHeritage').style.display = 'none';
				document.getElementById('TRInote4GEDmatch').style.display = 'none';
			
			}

		}

		function changedXDNAcompany() {
			// alert("You just changed your DNA company!");
		
			Val = document.getElementById('XDNAcompany').value  ;
			// alert (Val);
			if (Val == '23andMe') {
				document.getElementById('Xnote23andMe').style.display = 'revert';
				document.getElementById('Xverification').style.display = 'none';
				document.getElementById('Xnote4GEDmatch').style.display = 'none';
				if (theID3 > '') {
					document.getElementById('Xnote23andMe').style.display = 'revert';
				} else {
					document.getElementById('Xnote23andMe').style.display = 'none';
				}
					
			
			} else if (Val == 'GEDmatch') {
				document.getElementById('Xnote4GEDmatch').style.display = 'revert';
				document.getElementById('Xnote23andMe').style.display = 'none';
				document.getElementById('Xverification').style.display = 'none';
				if (theID3 > '') {
					document.getElementById('Xkits4Tri').style.display = 'revert';
				} else {
					document.getElementById('Xkits4Tri').style.display = 'none';
				}
			} else {
				document.getElementById('Xverification').style.display = 'revert';
				document.getElementById('Xnote23andMe').style.display = 'none';
				document.getElementById('Xnote4GEDmatch').style.display = 'none';
				if (theID3 > '') {
					document.getElementById('Xverification').style.display = 'revert';
				} else {
					document.getElementById('Xverification').style.display = 'none';
				}
			
			}

		}

		function changedYDNAcompany() {
			// alert("You just changed your DNA company!");
			Person2NameOnly = ''
			// alert("Assemble C 2");
			if( numGensFromWikiTreeID2toCousin > 0) {
				Person2NameOnly = theOriginalID2;
			} else if (theTestTakerID > '') {
				Person2NameOnly = theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameCurrent;
			} else if (theProfilePerson2 != null) {
				Person2NameOnly = theNextBestFirstName(theProfilePerson2) + " " + theProfilePerson2.LastNameAtBirth
			}
				

			Val = document.getElementById('YDNAcompany').value  ;
			let numMarkers = document.getElementById('Markers').value  ;

			console.log (Val);
			document.getElementById('NameForGeneticDistance').innerText = "Genetic Distance" ;
			// document.getElementById('HelpTextForGeneticDistance').innerText = "To find Genetic Distance,\ngo to Results & Tools > Y-DNA > Matches,\nand note the number of steps for your match." ;
			if (Val == 'mitoYDNA') {
				if (numMarkers == 838) {
					document.getElementById('NameForGeneticDistance').innerText = "YSTR+ Difference" ;
					document.getElementById('HelpTextForGeneticDistance').innerHTML = "To find YSTR+ Difference,<br/>compare Kits using the YSTR+ tool,<br/>and note the number in the GD column (Genetic Distance).<br/>Record as: X of YYY (using the value in the cell and total # of STRs compared)<br/><I>example:   5 of 838</I>";
				} else {
					document.getElementById('HelpTextForGeneticDistance').innerText = "To find Genetic Distance,\ncompare Kits using either the Y-Compare or YSTR+ tool,\nand note the number in the appropriate GD column (Genetic Distance)." ;
				}
				
				document.getElementById('BigYoption').innerText = "YSTR+" ;

				document.getElementById('YkitP1name').textContent = 'mitoYDNA.org Kit ID # for ' +  theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent;
				document.getElementById('YkitP2name').textContent = 'mitoYDNA.org Kit ID # for ' +  Person2NameOnly;
				document.getElementById('YDNAkitP1').value = kitNums["1-mitoydna4Y"];
				document.getElementById('YDNAkitP2').value = kitNums["2-mitoydna4Y"];
				document.getElementById('YDNAhaploP1').value = kitNums["1-haplo"];
				document.getElementById('YDNAhaploP2').value = kitNums["2-haplo"];

			} else if (Val == 'Family Tree DNA') {
				
				if (numMarkers == 838) {
					document.getElementById('NameForGeneticDistance').innerText = "Big Y STR Differences" ;
					document.getElementById('HelpTextForGeneticDistance').innerText = "To find Big Y STR Differences,\ngo to Results & Tools > Y-DNA > Matches,\nand note the differences and total STR count.\nRecord the full text:  X of YYY (using the numbers from your match)." ;
				} else {
					document.getElementById('HelpTextForGeneticDistance').innerText = "To find Genetic Distance,\ngo to Results & Tools > Y-DNA > Matches,\nand note the number of steps for your match." ;
				}
				document.getElementById('BigYoption').innerText = "BigY" ;
				document.getElementById('YkitP1name').textContent = 'FTDNA Kit No. for ' +  theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent + " (if known)";
				document.getElementById('YkitP2name').textContent = 'FTDNA Kit No. for ' +  Person2NameOnly + " (if known)";
				document.getElementById('YDNAkitP1').value = kitNums["1-ftdna"];
				document.getElementById('YDNAkitP2').value = kitNums["2-ftdna"];
				document.getElementById('YDNAhaploP1').value = kitNums["1-haplo"];
				document.getElementById('YDNAhaploP2').value = kitNums["2-haplo"];
			} else {
				document.getElementById('YkitP1name').textContent = 'YDNA Kit # for ' +  theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent;
				document.getElementById('YkitP2name').textContent = 'YDNA Kit # for ' +  Person2NameOnly;
			}
			console.log("Kits: " , kitNums["1-ftdna"], kitNums["2-ftdna"], kitNums["1-mitoydna4Y"], kitNums["2-mitoydna4Y"], kitNums["1-haplo"], kitNums["2-haplo"]);
			updateSNPtreeExplorerLink();

		}
		function changedMTDNAcompany() {
			// alert("You just changed your DNA company!");
			Person2NameOnly = ''
			// alert("Assemble C 2");
			if( numGensFromWikiTreeID2toCousin > 0) {
				Person2NameOnly = theOriginalID2;
			} else if (theTestTakerID > '') {
				Person2NameOnly = theNextBestFirstName(theTestPerson) + " " + theTestPerson.LastNameCurrent;
			} else if (theProfilePerson2 != null) {
				Person2NameOnly = theNextBestFirstName(theProfilePerson2) + " " + theProfilePerson2.LastNameAtBirth
			}

			Val = document.getElementById('MTDNAcompany').value  ;
			// alert (Val);
			if (Val == 'mitoYDNA') {
				document.getElementById('MTkitP1name').textContent = 'mitoYDNA.org Kit ID # for ' + theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent;
				document.getElementById('MTkitP2name').textContent = 'mitoYDNA.org Kit ID # for ' + Person2NameOnly;
				document.getElementById('MTDNAinfoMitoY').style.display = 'revert';
				document.getElementById('MTDNAinfoFTDNA').style.display = 'none';
				document.getElementById('MTcallitMitoY').style.display = 'revert';
				document.getElementById('MTcallitFTDNA').style.display = 'none';
				document.getElementById('MTcallitMitoY2').style.display = 'revert';
				document.getElementById('MTcallitFTDNA2').style.display = 'none';
				document.getElementById('MTcallitMitoY3').style.display = 'revert';
				document.getElementById('MTcallitFTDNA3').style.display = 'none';
				document.getElementById('MTDNAkitP1').value = kitNums["1-mitoydna4MT"];
				document.getElementById('MTDNAkitP2').value = kitNums["2-mitoydna4MT"];
				document.getElementById('MTDNAhaploMP1').value = kitNums["1-haploM"];
				document.getElementById('MTDNAhaploMP2').value = kitNums["2-haploM"];

			} else if (Val == 'Family Tree DNA') {
				document.getElementById('MTkitP1name').textContent = 'FTDNA Kit No. for ' +  theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent + " (if known)";
				document.getElementById('MTkitP2name').textContent = 'FTDNA Kit No. for ' +  Person2NameOnly + " (if known)";
				document.getElementById('MTDNAinfoMitoY').style.display = 'none';
				document.getElementById('MTDNAinfoFTDNA').style.display = 'revert';
				document.getElementById('MTcallitMitoY').style.display = 'none';
				document.getElementById('MTcallitFTDNA').style.display = 'revert';
				document.getElementById('MTcallitMitoY2').style.display = 'none';
				document.getElementById('MTcallitFTDNA2').style.display = 'revert';
				document.getElementById('MTcallitMitoY3').style.display = 'none';
				document.getElementById('MTcallitFTDNA3').style.display = 'revert';
				document.getElementById('MTDNAkitP1').value = kitNums["1-ftdna"];
				document.getElementById('MTDNAkitP2').value = kitNums["2-ftdna"];
				document.getElementById('MTDNAhaploMP1').value = kitNums["1-haploM"];
				document.getElementById('MTDNAhaploMP2').value = kitNums["2-haploM"];
			} else {
				document.getElementById('MTkitP1name').textContent = 'mtDNA Kit # for ' +  theNextBestFirstName( theProfilePerson) + " " + theProfilePerson.LastNameCurrent;
				document.getElementById('MTkitP2name').textContent = 'mtDNA Kit # for ' +  Person2NameOnly;
			}

		}


		function DNAcompany4Citation (DNAcompany) {
			if (DNAcompany == 'GEDmatch') {
				return "[https://GEDmatch.com GEDmatch] comparison";
			} else if (DNAcompany == 'Living DNA') {
				return "[https://LivingDNA.com Living DNA] test match";
			} else if (DNAcompany == 'AncestryDNA') {
				return "{{" + DNAcompany + "}} test match";
			} else if (DNAcompany == 'Geneanet') {
				return "[https://geneanet.org Geneanet] DNA match";
			} else {
				return "{{" + DNAcompany + "}} test match";
			}
		}
		function DNAcompanyName (DNAcompany) {
			if (DNAcompany == 'GEDmatch') {
				return "[https://GEDmatch.com GEDmatch]";
			} else if (DNAcompany == 'Living DNA') {
				return "[https://LivingDNA.com Living DNA]";
			} else if (DNAcompany == 'mitoYDNA') {
				return "[https://mitoYDNA.org mitoYDNA.org]";
			} else if (DNAcompany == 'AncestryDNA') {
				return "{{" + DNAcompany + "}}";
			} else if (DNAcompany == 'Geneanet') {
				return "[https://geneanet.org Geneanet]";
			} else {
				return "{{" + DNAcompany + "}}";
			}
		}

		function shortDNAcompanyName (DNAcompany) {
			if (DNAcompany == 'Family Tree DNA') {
				return "FTDNA";
			} else if (DNAcompany == 'AncestryDNA') {
				return "Ancestry" ;
			} else {
				return  DNAcompany ;
			}
		}

		function compareKitsNote(DNAcompany, Kit1, Kit2, DNAtype) {
			if (DNAcompany == 'GEDmatch') {
				return ", ''([https://app.gedmatch.com/v_compare1.php?kit1=" + Kit1 + "&kit2=" + Kit2 + " compare kits])''";
			} else if (DNAcompany == 'mitoYDNA') {
				if (DNAtype == 'mt'){
					return ", ''([https://mitoydna.org/Public/mtCompare?MtyID=" + Kit1 + "," + Kit2 + " compare kits])''";
				} else if (DNAtype == 'y'){
					return ", ''([https://mitoydna.org/Public/yCompare?MtyID=" + Kit1 + "," + Kit2 + " compare kits])''";
				} else {
					return "";
				}
			} else {
				return "";
			}
		}	

		function addRelFinderLink(relationshipText, p1, p2) {
			let theWikiNameForPerson2 = "";
			if (personArray[ahnenIndexArrays[p2][1]] && personArray[ahnenIndexArrays[p2][1]].Name) {
				theWikiNameForPerson2 = personArray[ahnenIndexArrays[p2][1]].Name;
			}
			if (altWikiNameForPerson2 > "") {
				theWikiNameForPerson2 = altWikiNameForPerson2;
			}

			if (personArray[ahnenIndexArrays[p1][1]] && personArray[ahnenIndexArrays[p1][1]].Name > "" && theWikiNameForPerson2 > "") {	
				return "[https://www.wikitree.com/index.php?title=Special:Relationship&action=calculate&person1Name=" + personArray[ahnenIndexArrays[p1][1]].Name + "&person2Name=" + theWikiNameForPerson2 + " " + relationshipText + "]";
			} 

			return relationshipText;
			
		}

		function cousinCorrect(cousinPhrase) {
			// cousinFormat
			if (document.getElementById('cousinFormatS').checked) {
				cousinFormat = 'S';
			}
			if (document.getElementById('cousinFormatM').checked) {
				cousinFormat = 'M';
			}
			if (document.getElementById('cousinFormatL').checked) {
				cousinFormat = 'L';
			}
			if (!cousinPhrase || cousinPhrase == "") {
				return "";
			}

			console.log("COUSIN CORRECT: ", cousinPhrase , "->", {cousinFormat});
			let ordinalWords = ['zeroeth', 'first', 'second', 'third','fourth','fifth', 'sixth','seventh','eighth','ninth', 'tenth','eleventh','twelfth','thirteenth','fourteenth','fifteenth','sixteenth','seventeenth','eighteenth','nineteenth','twentieth'];
			let ordinalEndings = ['', 'st', 'nd', 'rd','th','th', 'th','th','th','th', 'th','th','th','th','th','th','th','th','th','th','th'];
			let countingWords = ['zero', 'once', 'twice', 'three','four','five', 'six','seven','eight','nine', 'ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen','twenty'];

			if (cousinFormat == "L") {
				//  second cousin three times removed
				return cousinPhrase;

			} else if (cousinFormat == "M") {

				// 2nd cousin 3x removed

				for (var i = 0; i < ordinalWords.length; i++) {
					if (cousinPhrase.indexOf(ordinalWords[i]) > -1) {
						cousinPhrase = cousinPhrase.replace(ordinalWords[i], i + ordinalEndings[i]);
					}
					if (cousinPhrase.indexOf(countingWords[i]) > -1) {
						cousinPhrase = cousinPhrase.replace(countingWords[i], i + "x");
					}
				}
				cousinPhrase = cousinPhrase.replace("times ","");
				return cousinPhrase;
			} else if (cousinFormat == "S") {

				// 2C3R

				for (var i = 0; i < ordinalWords.length; i++) {
					if (cousinPhrase.indexOf(ordinalWords[i]) > -1) {
						cousinPhrase = cousinPhrase.replace(ordinalWords[i], i);
					}
					if (cousinPhrase.indexOf(countingWords[i]) > -1) {
						cousinPhrase = cousinPhrase.replace(countingWords[i], i);
					}
				}
				cousinPhrase = cousinPhrase.replace(" cousin ", "C").replace(" removed","R").replace(" times","").replace(" cousin", "C");
				return cousinPhrase;
			}
				
		}		

		var triCitationStarterArray = [];

		function assembleTriangulationCitationStarters() {
			triCitationStarterArray = [];

			

			
			let theCommonAncestorFirstNames = theNextBestFirstName(theAncestor) ;
			if (isCouple) {
				// alert(2945);
				theCommonAncestorName = "s are [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]] and [[" + theAncestor2.Name + "|" + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2) + "]]"; 
				theCommonAncestorFirstNames += " & " + theNextBestFirstName(theAncestor2);
			} else {
				// alert(2948);
				theCommonAncestorName = " is  [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]]"; 
			}
			

			DNAcompany = document.getElementById('DNAcompany3').value;

				// alert("DNAcompany = " + DNAcompany);
			TriMatchAmount = document.getElementById('TriMatchAmount').value;
			ChromoNumber = document.getElementById('ChromoNum').value;

 			let ConfirmedConfidentStmt = " a triangulated group on " + DNAcompanyName(DNAcompany) + " who share a " ;
			
			if (document.getElementById('TriSegmentMatchStmtStd').checked){
				ConfirmedConfidentStmt +=  (TriMatchAmount) + " cM segment on chromosome " + (ChromoNumber);
			} else {
				ConfirmedConfidentStmt +=  document.getElementById('TriCustomStmt').value; 				
			}
			ConfirmedConfidentStmt += ". ";
 				

				var KitNumArray = ['','','','','','','','','','','','','','','','','','',''];

				if (DNAcompany == "GEDmatch") {

					for (var i = 1; i < ahnenIndexArrays.length; i++) {
						let kitInputID = "DNAkitGM"+i;
						let kitInput = document.getElementById(kitInputID);
						if (kitInput && kitInput.value > '' && kitInput.value != 'undefined') {
							KitNumArray[i] = ", GEDmatch kit # " + kitInput.value ;
						}
					}

					
				} else if (DNAcompany == "Family Tree DNA" || DNAcompany == "MyHeritageDNA") {
						let verifiersList = [0,0,0,0,0,0,0,0,0,0,0];
						for (var i = 1; i < ahnenIndexArrays.length - 1; i++) {
							for (var j = i + 1; j < ahnenIndexArrays.length; j++) {
								var VerifABbyA = document.getElementById("Verif" + i + j + "by" + i).checked;
								var VerifABbyB = document.getElementById("Verif" + i + j + "by" + j).checked;
								let foundCheck = false;
								if (VerifABbyA == true) {
									verifiersList[i] ++;
									foundCheck = true;
								}
								if (VerifABbyB == true) {
									verifiersList[j] ++;
									foundCheck = true;
								}
							}
						}

						VerifiedByABC = " These matches have been independently verified by ";

						// let VerifiedByABC = "";
						for (var i = 1; i < ahnenIndexArrays.length - 1; i++) {
							if (verifiersList[i] > 0) {
								if (VerifiedByABC > "") {
									VerifiedByABC += " & ";
								}
								VerifiedByABC += theNextBestFirstNameLastName[ ahnenIndexArrays[i][1] ];
							}
						}

						if (DNAcompany == "Family Tree DNA" ) {
							VerifiedByABC += " via the Family Finder Chromosome Browser.";
						} else if ( DNAcompany == "MyHeritageDNA") {
							VerifiedByABC += " via the MyHeritage Chromosome Browser.";
						}

				}

				let descentStatement = "";
				if (theNumValidTesters == uniqueChildren.length) {
					descentStatement = " The " + theNumValidTesters + " DNA testers descend from "  + uniqueChildren.length + " different children of the common ancestor" + (isCouple == true ? "s ":" " ) + theCommonAncestorFirstNames + ".";
				} else {
					descentStatement = " The " + theNumValidTesters + " DNA testers descend from "  + uniqueChildren.length + " different children, " + uniqueGrandChildren.length + " unique grandchildren of the common ancestor" + (isCouple == true ? "s ":" " ) + theCommonAncestorFirstNames + ".";
				}

				let thePenultimateYayTesterNum = -1;
				for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
					if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == true) {
						if (testerNum != theLastYayTesterNum ){
							thePenultimateYayTesterNum = testerNum;
						}
					}
				}
				

				// create loop to list all of the YAY testers and their relationships to the first in the list
				 // + Person1FullName4Citation +  "" +  KitNum1 + " and " + Person2FullName4Citation + ", " +  relOfP2toP1 + "," +  KitNum2 +  thirdPersonInfo 


				// SET UP the   MRCA2TestersRelationshipFullNames with proper aliases, as needed
				testerNamesForCitations = [];
				console.log("YAY: ", MRCA2TestersRelationshipFullNames);
				for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
					let nameUsedForMRCArelationship = theNextBestFirstNameLastName( ahnenIndexArrays[testerNum][1] );
					let testerNumPerson = personArray[ ahnenIndexArrays[testerNum][1] ];
					testerNamesForCitations[testerNum] = theNextBestFirstNameLastName( ahnenIndexArrays[testerNum][1] );
					if (document.getElementById("RadioCiteName"+ testerNum + "New").checked){
						testerNamesForCitations[testerNum] = theLastBestFirstNameLastName( ahnenIndexArrays[testerNum][1] );
					}
					if (testerNumPerson.Name.indexOf("Priv8") > -1) {
						testerNamesForCitations[testerNum] = document.getElementById("CustomCiteName" + testerNum + "Cust").value;
					
					} else if (document.getElementById("RadioCiteName"+ testerNum + "Cust").checked){
						testerNamesForCitations[testerNum] = document.getElementById("CustomCiteName" + testerNum + "Cust").value;
					}
					

					console.log("YAY: ", testerNum, nameUsedForMRCArelationship , "vs", testerNamesForCitations[testerNum] , (nameUsedForMRCArelationship == testerNamesForCitations[testerNum] ) );
					if (nameUsedForMRCArelationship == testerNamesForCitations[testerNum] ) {
					} else {
						MRCA2TestersRelationshipFullNames = MRCA2TestersRelationshipFullNames.replace(nameUsedForMRCArelationship, testerNamesForCitations[testerNum] );
						console.log("YAY - change to ", MRCA2TestersRelationshipFullNames);
					}
				}
					

				for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
					let testerNumPerson = personArray[ ahnenIndexArrays[testerNum][1] ];
					let testerNumNameWithLink = theNextBestFirstNameLastName( ahnenIndexArrays[testerNum][1] , document.getElementById("UseWikiLink" + testerNum).checked ) ;
					let nameUsedForMRCArelationship = theNextBestFirstNameLastName( ahnenIndexArrays[testerNum][1] );
					
					if (document.getElementById("RadioCiteName"+ testerNum + "New").checked){
						testerNumNameWithLink = theLastBestFirstNameLastName( ahnenIndexArrays[testerNum][1] , document.getElementById("UseWikiLink" + testerNum).checked ) ;
						
					}
					if (testerNumPerson.Name.indexOf("Priv8") > -1) {
						// testerNumNameWithLink = testerNumPerson.FirstName + ", ";
						testerNumNameWithLink = document.getElementById("CustomCiteName" + testerNum + "Cust").value  + ", ";
						
						if (testerNumPerson.numGensAway == 1) {
							testerNumNameWithLink += "child of ";	
						} else if (testerNumPerson.numGensAway == 2) {
							testerNumNameWithLink += "grandchild of ";	
						} else if (testerNumPerson.numGensAway > 2) {
							testerNumNameWithLink +=  "great-".repeat(testerNumPerson.numGensAway - 2) +"grandchild of ";	
						}

						testerNumNameWithLink += theNextBestFirstNameLastName( testerNumPerson.closestAnc1ID , document.getElementById("UseWikiLink" + testerNum).checked ) ;
					} else if (document.getElementById("RadioCiteName"+ testerNum + "Cust").checked){
						
						if (document.getElementById("UseWikiLink" + testerNum).checked ) {
							testerNumNameWithLink = "[[" + personArray[ ahnenIndexArrays[testerNum][1] ].Name + "|" + document.getElementById("CustomCiteName" + testerNum + "Cust").value + "]]" ;
						} else {
							testerNumNameWithLink = document.getElementById("CustomCiteName" + testerNum + "Cust").value ;
						}

					}
										
					let citation =  ConfirmedConfidentStmt +  "The group of DNA testers include " + testerNumNameWithLink + KitNumArray[testerNum];

					for (var testerNumB = 1; testerNumB < ahnenIndexArrays.length; testerNumB++) {

						if (testerNum != testerNumB && ahnenIndexArrays[testerNumB].length > 0 && yayTestersList[testerNumB] == true) {
							citation += ", "; 
							if (testerNumB == theLastYayTesterNum) {
								citation += "and "; 
							} else if (testerNum == theLastYayTesterNum && testerNumB == thePenultimateYayTesterNum) {
								citation += "and "; 
							}
						
							let testerNumBPerson = personArray[ ahnenIndexArrays[testerNumB][1] ];
							let testerNumBNameWithLink = theLastBestFirstNameLastName( ahnenIndexArrays[testerNumB][1] , document.getElementById("UseWikiLink" + testerNumB).checked) ;
							if (document.getElementById("RadioCiteName"+ testerNumB + "New").checked){
								testerNumBNameWithLink = theNextBestFirstNameLastName( ahnenIndexArrays[testerNumB][1] , document.getElementById("UseWikiLink" + testerNumB).checked ) ;
							}
							if (testerNumBPerson.Name.indexOf("Priv8") > -1) {
								// testerNumBNameWithLink = testerNumBPerson.FirstName + ", ";
								testerNumBNameWithLink = document.getElementById("CustomCiteName" + testerNumB + "Cust").value  + ", ";

								if (testerNumBPerson.numGensAway == 1) {
									testerNumBNameWithLink += "child of ";	
								} else if (testerNumBPerson.numGensAway == 2) {
									testerNumBNameWithLink += "grandchild of ";	
								} else if (testerNumBPerson.numGensAway > 2) {
									testerNumBNameWithLink +=  "great-".repeat(testerNumBPerson.numGensAway - 2) +"grandchild of ";	
								}

								testerNumBNameWithLink += theNextBestFirstNameLastName( testerNumBPerson.closestAnc1ID , document.getElementById("UseWikiLink" + testerNumB).checked) ;
							} else if (document.getElementById("RadioCiteName"+ testerNumB + "Cust").checked){
								if (document.getElementById("UseWikiLink" + testerNumB).checked ) {
									testerNumBNameWithLink = "[[" + personArray[ ahnenIndexArrays[testerNumB][1] ].Name + "|" + document.getElementById("CustomCiteName" + testerNumB + "Cust").value + "]]" ;
								} else {
									testerNumBNameWithLink = document.getElementById("CustomCiteName" + testerNumB + "Cust").value ;
								}

							}


							citation +=  testerNumBNameWithLink + KitNumArray[testerNumB];

							console.log("RELATIONSHIP from MATRIX ",testerNum + "|" + testerNumB, relationshipMatrix[testerNum + "|" + testerNumB]);
							if (relationshipMatrix[testerNum + "|" + testerNumB] == undefined || relationshipMatrix[testerNum + "|" + testerNumB] == "") {
								// use defineRelationshipsBetween(ancA, ancB, #genA, #genB, arraynumA, arraynumB) if UNDEFINED
								let newRelationship = defineRelationshipsBetween(theAncsAhnNums[testerNum], theAncsAhnNums[testerNumB], 0, 0, testerNum, testerNumB)[0];
								relationshipMatrix[testerNum + "|" + testerNumB] = newRelationship.replace("his ", "").replace("her ","").replace("their ","");

								console.log("RELATIONSHIP from MATRIX' ",testerNum + "|" + testerNumB, relationshipMatrix[testerNum + "|" + testerNumB]);
							}

							if (showRelConnLink == true ){
								citation += ", " + possessiveOf(ahnenIndexArrays[testerNum][1]) + addRelFinderLink(cousinCorrect(relationshipMatrix[testerNum + "|" + testerNumB]),testerNum,testerNumB);
							} else {
								citation += ", " + possessiveOf(ahnenIndexArrays[testerNum][1]) + cousinCorrect(relationshipMatrix[testerNum + "|" + testerNumB]); //,1,2;
							}
						
						}
					}	

					triCitationStarterArray[testerNum]  = citation  + "." +  VerifiedByABC + " Their most-recent common ancestor" + theCommonAncestorName  + ", " + MRCA2TestersRelationshipFullNames + "." + descentStatement;//
					triCitationStarterArray[testerNum] = triCitationStarterArray[testerNum].replace(/ \./g,".");

					// if (DiedTooSoonInvalidCitationWarning > "") {
					// 	triCitationStarterArray[testerNum] += "\n" + DiedTooSoonInvalidCitationWarning;
					// }
				}


		}

		function assembleCitation(theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false) {
			// alert ("IN the assembleCitation function - " + doBoth);
			console.log(DNAcompany + " at " + matchAmount + " at " + matchUnits + " at " + NumSegments + " at " +  relationshipPrediction + "." );
			console.log("Assemble CITATION (1) - numGensFromWikiTreeID2toCousin : ",numGensFromWikiTreeID2toCousin);
			console.log("ASSEMBLE NAMES: ", {Person1FullName4Citation}, {Person2FullName4Citation});
			console.log({theAnc});
			var MatOrPat = "Maternal";
			VerifiedByABC = '';

			let DiedTooSoonInvalidCitationWarning = "";
			if (showDeathWarning) {
				DiedTooSoonInvalidCitationWarning = "INVALID DNA CONFIRMATION STATEMENT - FOR TESTING PURPOSES ONLY - \"DNA Tester\" died before DNA testing was available!\n";
			}


			if (theAnc.Gender == 'Male') { MatOrPat = "Paternal";}

			var BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + MatOrPat + " relationship is ";
			if (doBoth == true) {
				let thisBothQualifier = "confident";
				if (theDNAconfType == '1'){
					thisBothQualifier = "confirmed";
				}
				BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + " Paternal and Maternal relationships are both " + thisBothQualifier + " based on ";
			} else  {
				BeginningOfCitation += "confirmed by ";
			}



			relationshipBetweenP1andP2 = 'cousins';
			matchUnits = 'cM';
			if (matchPerCent > "") {
				altMatchMeas = "(" + (matchPerCent) + "% DNA shared)";
			} else {
				altMatchMeas = ""; 
			}
			// alert("Assemble C 1");
			//(NumSegments > 1 ? "s" : "")
			if (isCouple && theAncestor2 && theAncestor2.Name) {
				// alert(2945);
				theCommonAncestorName = "s are [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]] and [[" + theAncestor2.Name + "|" + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2) + "]]"; 
			} else {
				// alert(2948);
				theCommonAncestorName = " is  [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]]"; 
			}
			 

			theTestTakerCitationName = Person2FullName4Citation;
			Person2FirstName = Person2FirstName4Citation;
			Person2NameOnly = Person2FullName4Citation;

			// alert("Assemble C 2b");
			 

			theTestTakerCitationName3 = Person3FullName4Citation;
			Person3FirstName = Person3FirstName4Citation;
			Person3NameOnly = Person3FullName4Citation;

			// alert("Assemble C 3");

			 
			if (theDNAconfType == '1'){
				DNAcompany = document.getElementById('DNAcompany').value;
				console.log("LINE 8685:", {relationshipMatrix});
				if (showRelConnLink == true ){
					relOfP2toP1 = possessiveOf(ahnenIndexArrays[1][1]) + addRelFinderLink(relationshipMatrix["1|2"],1,2);
					relOfP1toP2 = possessiveOf(ahnenIndexArrays[2][1]) + addRelFinderLink(relationshipMatrix["2|1"],2,1);
				} else {
					relOfP2toP1 = possessiveOf(ahnenIndexArrays[1][1]) + relationshipMatrix["1|2"]; //,1,2;
					relOfP1toP2 = possessiveOf(ahnenIndexArrays[2][1]) + relationshipMatrix["2|1"]; //,2,1;
				}

				if (DNAcompany == 'GEDmatch') {
					var P1KitInfo = ", GEDmatch kit # " + document.getElementById('DNAkitGMA').value + ", ";
					var P2KitInfo = ", GEDmatch kit # " + document.getElementById('DNAkitGMB').value + "";

					if (document.getElementById('DNAkitGMA').value == '' || document.getElementById('DNAkitGMA').value == 0){
						P1KitInfo = "";
					}
					if (document.getElementById('DNAkitGMB').value == '' || document.getElementById('DNAkitGMB').value == 0){
						P2KitInfo = "";
					}
					if (P1KitInfo > "" && P2KitInfo > "" && showGEDcomCompareLink == true){
						P2KitInfo += compareKitsNote(DNAcompany, document.getElementById('DNAkitGMA').value, document.getElementById('DNAkitGMB').value , 'au') ;
					}
				} else {
					var P1KitInfo = "";
					var P2KitInfo = "";
				}
						
					
				if (isDirectAncestor) {
					// alert("isDirectAncestor");
					console.log("isDirectAncestor : var citation ", {relOfP2toP1}, ahnenIndexArrays[1][1], ahnenIndexArrays[2][1], ahnenIndexArrays[1].indexOf(ahnenIndexArrays[2][1]) );
					var citation = BeginningOfCitation + "an autosomal " + DNAcompany4Citation(DNAcompany) + " between " + Person1FullName4Citation +  " " + P1KitInfo + "and " + theTestTakerCitationName + P2KitInfo + ", " +  relOfP2toP1 + " (direct ancestor).  Predicted relationship from " + DNAcompany + ": " + relationshipPrediction + ", based on sharing " + matchAmount + " " + matchUnits + " " + altMatchMeas + " across " + NumSegments + " segment" + "s" + ".";
					document.getElementById("CAsourceCitation").style.backgroundColor = "white";
					document.getElementById("theInfoParaTop").style.backgroundColor = "white";
	
				} else {
					// alert("NOT isDirectAncestor");
					let theXofTT1 = "";
					if (theTT1isActually == "child") {
						theXofTT1 = "a child of ";
					} else if (theTT1isActually == "grandchild") {
						theXofTT1 = "a grandchild of ";
					}
					var citation = BeginningOfCitation + "an autosomal " + DNAcompany4Citation(DNAcompany) + " between " + theXofTT1 + Person1FullName4Citation +  " " + P1KitInfo + "and " + Person2FullName4CitationFirstMention + P2KitInfo + ", " +  relOfP2toP1 + ". Their most-recent common ancestor" + theCommonAncestorName + ", the " + relCA2P1 + " of "; 

					if (relCA2P1 == relCA2P2) {
						citation += "both " + "" + Person1FullName4Citation +  " and " + theTestTakerCitationName + ". ";
					} else {
						citation += "" + Person1FullName4Citation +  " and " + relCA2P2 + " of " + theTestTakerCitationName + ". ";
					}
				
					citation += " Predicted relationship from " + DNAcompany + ": " + relationshipPrediction + ", based on sharing " + matchAmount + " " + matchUnits + " " + altMatchMeas + " across " + NumSegments + " segment" + "s" + ".";

				}

			} else if (theDNAconfType == 'y'){
				// alert ("Y @ 4396");
				DNAcompany = document.getElementById('YDNAcompany').value;
				if (document.getElementById('YDNAkitP1').value > '' && document.getElementById('YDNAkitP1').value != 0){
					var P1KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('YDNAkitP1').value + ",";
				} else {
					var P1KitInfo = "";
				}
				if (document.getElementById('YDNAkitP2').value > '' && document.getElementById('YDNAkitP2').value != 0){
					var P2KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('YDNAkitP2').value ; // don't add comma to end, already part of citation
					if (document.getElementById('YDNAkitP1').value > '' && document.getElementById('YDNAkitP1').value != 0){
						P2KitInfo += compareKitsNote(DNAcompany, document.getElementById('YDNAkitP1').value, document.getElementById('YDNAkitP2').value , 'y') ;
					}
				} else {
					var P2KitInfo = "";
				}

				

				let matchDetails = getYDNAmatchDetails();

				var citation = BeginningOfCitation + "Y-chromosome DNA test results on " + DNAcompanyName(DNAcompany) + ".  " + Person1FullName4Citation +  "" + P1KitInfo + " and " + relOfP2toP1 +", " + Person2FullName4CitationFirstMention + P2KitInfo + ", " + matchDetails + ", thereby confirming their direct paternal lines back to their most-recent common ancestor who" + theCommonAncestorName  + ", the " + relCA2P1 + " of ";

				if (relCA2P1 == relCA2P2) {
					citation += "both " + "" + Person1FullName4Citation +  " and " + theTestTakerCitationName + ". ";
				} else {
					citation += "" + Person1FullName4Citation +  " and " + relCA2P2 + " of " + theTestTakerCitationName + ". ";
				}

				citation += getHaploGroupStatement();

				// alert ("Y @ 4419");
					
			} else if (theDNAconfType == 'mt'){
				DNAcompany = document.getElementById('MTDNAcompany').value;
				//genDistanceMT
				
				if (document.getElementById('MTDNAkitP1').value > '' && document.getElementById('MTDNAkitP1').value != 0){
					var P1KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('MTDNAkitP1').value + ",";
				} else {
					var P1KitInfo = "";
				}
				if (document.getElementById('MTDNAkitP2').value > '' && document.getElementById('MTDNAkitP2').value != 0){
					var P2KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('MTDNAkitP2').value ; // don't add comma to end, already part of citation
					if (document.getElementById('MTDNAkitP1').value > '' && document.getElementById('MTDNAkitP1').value != 0){
						P2KitInfo += compareKitsNote(DNAcompany, document.getElementById('MTDNAkitP1').value, document.getElementById('MTDNAkitP2').value, 'mt') ;
					}
				} else {
					var P2KitInfo = "";
				}
				
				typeOfMatch = "an exact HVR1, HVR2 and Coding Region match";
				genDistanceMT = 0;
				if (DNAcompany == 'mitoYDNA') {
					// alert("mito");
					diffHVR1 = document.getElementById('diffHVR1').value;
					diffHVR2 = document.getElementById('diffHVR2').value;
					diffCR = document.getElementById('diffCR').value;

					genDistanceMT = 1.0*diffHVR1 + 1.0*diffHVR2 + 1.0*diffCR;

					if (genDistanceMT == 1) {
						typeOfMatch = "an HVR1, HVR2, CR match with a minor mismatch in ";
						if (diffHVR1 > 0) {
							typeOfMatch += "HVR1";
						} else if (diffHVR2 > 0) {
							typeOfMatch +=  "HVR2";
						} else if (diffCR > 0) {
							typeOfMatch +=  "the Coding Region";
						} 
					} else if (genDistanceMT > 1) {
						typeOfMatch = "an HVR1, HVR2, CR match with minor mismatches in ";
						let foundOne = false;
						if (diffHVR1 > 0) {
							typeOfMatch += "HVR1";
							foundOne = true;
						} 
						if (diffHVR2 > 0) {
							if (foundOne) {
								typeOfMatch += " and ";
							}
							typeOfMatch +=  "HVR2";
							foundOne = true;
						} 
						if (diffCR > 0) {
							if (foundOne) {
								typeOfMatch += " and ";
							}
							typeOfMatch +=  "the Coding Region";
						} 
					}


				} else if (DNAcompany == "Family Tree DNA" ) {	
					// alert("FTDNA");
					genDistanceMT = document.getElementById('genDistanceMT').value;
						
					if (genDistanceMT == 1) {
						typeOfMatch = "an HVR1, HVR2, CR match with a minor mismatch";
					} else if (genDistanceMT > 1) {
						typeOfMatch = "an HVR1, HVR2, CR match with minor mismatches";
					}
					
				} else {
					//alert ("DNAcompany = " + DNAcompany);
				}

				
						


				var citation = BeginningOfCitation + "Mitochondrial DNA test results on " + DNAcompanyName(DNAcompany) + ".  " + Person1FullName4Citation +  "" + P1KitInfo + " and " + relOfP2toP1 +", " + Person2FullName4CitationFirstMention + P2KitInfo + ",  have " + typeOfMatch + ", thereby confirming their direct maternal lines back to their most-recent common ancestor who" + theCommonAncestorName  + ", the " + relCA2P1 + " of ";

				if (relCA2P1 == relCA2P2) {
					citation += "both " + "" + Person1FullName4Citation +  " and " + theTestTakerCitationName + ". ";
				} else {
					citation += "" + Person1FullName4Citation +  " and " + relCA2P2 + " of " + theTestTakerCitationName + ". ";
				}
					
				
 			} else if (theDNAconfType == 'x'){
 				// alert("in X citation creation piece @ 3642 ");
 				DNAcompany = document.getElementById('XDNAcompany').value;
 				XmatchAmount = document.getElementById('XmatchAmount').value;
 				if (isCouple && (theAnc == theAncestor || theAnc == theAncestor2) ) {
 					var ConfirmedConfidentStmt = "confident based on a ";
 				} else {
 					var ConfirmedConfidentStmt = "confirmed by a ";
 				}
 				
 				var KitNum1 = '';
				var KitNum2 = '';
				var KitNum3 = '';

				var KitNumArray = ['','','','','','','','','','','','','','','','','','',''];
				

				if (DNAcompany == "GEDmatch") {
					for (var i = 1; i < ahnenIndexArrays.length; i++) {
						let kitInputID = "DNAkitGM"+i;
						let kitInput = document.getElementById(kitInputID);
						if (kitInput && kitInput.value > '') {
							KitNumArray[i] = ", GEDmatch kit # " + kitInput.value  +",";
						}
					}
					var XPAkitGMA = document.getElementById('XPAkitGMA').value;
					var XPAkitGMB = document.getElementById('XPAkitGMB').value;
					var XPAkitGMC = document.getElementById('XPAkitGMC').value;
					if (XPAkitGMA > '') { KitNum1 = ", GEDmatch kit # " + XPAkitGMA +",";}
					if (XPAkitGMB > '') { KitNum2 = ", GEDmatch kit # " + XPAkitGMB +"";}
					if (XPAkitGMC > '' && theID3 > '') { KitNum3 = ", GEDmatch kit # " + XPAkitGMC ;}
				} else if (DNAcompany == "Family Tree DNA" || DNAcompany == "MyHeritageDNA") {
						var VerifABbyA = document.getElementById('XVerifABbyA').checked;
						var VerifABbyB = document.getElementById('XVerifABbyB').checked;
						var VerifBCbyB = document.getElementById('XVerifBCbyB').checked;
						var VerifBCbyC = document.getElementById('XVerifBCbyC').checked;
						var VerifCAbyC = document.getElementById('XVerifCAbyC').checked;
						var VerifCAbyA = document.getElementById('XVerifCAbyA').checked;

						VerifiedByABC = " These matches have been independently verified by ";
						needAnd = false;
						if (VerifABbyA+VerifCAbyA > 0) {
							VerifiedByABC += theProfilePerson.RealtName + " " + theProfilePerson.LastNameCurrent;
							needAnd = true;
						}
						if (VerifABbyB+VerifBCbyB > 0) {
							if (needAnd) {
								VerifiedByABC += " and ";
							}
							VerifiedByABC += Person2NameOnly;
							needAnd = true;
						}
						if (VerifBCbyC+VerifCAbyC > 0){
							if (needAnd) {
								VerifiedByABC += " and ";
							}
							VerifiedByABC += Person3NameOnly;
						} 
						if (DNAcompany == "Family Tree DNA" ) {
							VerifiedByABC += " via the Family Finder Chromosome Browser. ";
						} else if ( DNAcompany == "MyHeritageDNA") {
							VerifiedByABC += " via the MyHeritage Chromosome Browser. ";
						}

				}

 				if (ahnenIndexArrays[3].length > 0) {

 					if( numGensFromWikiTreeID3toCousin > 0) {
						theTestTakerCitationName3 = theOriginalID3;
						Person3FirstName = theOriginalID3;
					} else if (theID3 > '') {
						// alert(2958);
						// Person3FullName4Citation
						theTestTakerCitationName3 = Person3FullName4Citation ; //"[[" + theProfilePerson3WikiLink + "]]";
						Person3FirstName =   theNextBestFirstName(theProfilePerson3) ;
					} else {
						// alert(2961);
						// alert (theTestTaker);
						if (theOriginalID3 > '') {
							// alert(2963);
							theTestTakerCitationName3 = theOriginalID3;
							Person3FirstName = theOriginalID3;
						} else {
							// alert(2966);
							theTestTakerCitationName3 = 'the Other Test Taker';
							Person3FirstName = 'The Third Test Taker';
						}
					}

					var relOfP2toP3WithoutPronoun = relOfP2toP3.substr( relOfP2toP3.indexOf(" ") + 1 ).replace("cousin", "cousins");
 					thirdPersonInfo = " and " + theTestTakerCitationName3  + ", " + relOfP3toP1 + KitNum3 + ". (" + Person2FirstName + " and " + Person3FirstName + " are " + relOfP2toP3WithoutPronoun + ".)";
 					relCA2ThirdPerson = " and " + relCA2P3 + " of " + theTestTakerCitationName3;
 				}	 else {
 					thirdPersonInfo = ".";
 					relCA2ThirdPerson = "";
 					VerifiedByABC = "";
 				}

 				 if (document.getElementById('xSegmentMatchStmtStd').checked){
 					theChromoSegmentStmt =  (XmatchAmount) + " cM segment on the X chromosome";
 				} else {
 					theChromoSegmentStmt =  document.getElementById('xCustomStmt').value; 				
				}

 				if (ahnenIndexArrays[3].length > 0) {
					var citation = "* " + MatOrPat + " relationship is " + ConfirmedConfidentStmt + " triangulated group on " + DNAcompanyName(DNAcompany) + " who share a  " + theChromoSegmentStmt + ", consisting of ";
					thirdPersonInfo = "," + thirdPersonInfo;
 				} else {
					var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " autosomal DNA match of " + theChromoSegmentStmt + " between ";
 				}
							
				citation +=	"" + Person1FullName4Citation +  ""+ KitNum1 +" and " + theTestTakerCitationName + ", " +  relOfP2toP1  + KitNum2 + thirdPersonInfo  + " "+ VerifiedByABC + "Their most-recent common ancestor" + theCommonAncestorName  + ", the " + relCA2P1 + " of ";
				if (relCA2P1 == relCA2P2) {
					citation += "both " + "" + Person1FullName4Citation +  " and " + theTestTakerCitationName + relCA2ThirdPerson + ". ";
				} else {
					if (relCA2P2 == relCA2P3) {
						citation += "" + Person1FullName4Citation +  " and " + relCA2P2 + " of both " + theTestTakerCitationName + " and " + theTestTakerCitationName3 + ". ";
					} else {
						citation += "" + Person1FullName4Citation +  " and " + relCA2P2 + " of " + theTestTakerCitationName + relCA2ThirdPerson + ". ";

					}
				}
			 
			} else if (theDNAconfType == 'Tri'){
				 
				var citation = BeginningOfCitation + triCitationStarterArray[1];
 			

			} else {
				var citation = BeginningOfCitation + "confirmed by a DNA test of Some Sort by a Company between Person A and Person B with Various Evidences ... all to be properly outlined here shortly ... stay tuned ! ";
			}


			// alert("Assemble C 4");
			citation += theAdditionalNote;		


			if (theDNAconfType == 'Tri'){
				citation += "\n** " + theEndProviso;
			}

			// add the code to "sign the citation"  - to add the name of the citation author and date the citation was added to the profile
			citation += credit4DNAconfAppPreamble + versionNum + " - ~~~~";		

			console.log("ASSEMBLE CITATION (1) - DONE :  ", {citation});
			return citation;
		}

		function setupCitationAppCreditPreamble() {
			credit4DNAconfAppPreamble = "\n** Citation generated by "+ (showDNAconfAppLink == true ?"[https://apps.wikitree.com/apps/clarke11007/DNAconf.php ":"") +"DNA Confirmation app" + (showDNAconfAppLink == true ?"]":"") + ", ";
		}

		function assembleCitation2(theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false) {
			// DNAcompany + " at " + matchAmount + " at " + matchUnits + " at " + NumSegments + " at " +  relationshipPrediction + "." ;
			console.log("Assemble CITATION (2)  : ",{theAnc});

			let DiedTooSoonInvalidCitationWarning = "";
			if (showDeathWarning) {
				DiedTooSoonInvalidCitationWarning = "INVALID DNA CONFIRMATION STATEMENT - FOR TESTING PURPOSES ONLY - \"DNA Tester\" died before DNA testing was available!\n";
			}



			var MatOrPat = "Maternal";
			if (theAnc.Gender == 'Male') { MatOrPat = "Paternal";}
			relationshipBetweenP1andP2 = 'cousins';
			// alert("assembleCitation2 for ", theAnc.FirstName, theAnc.Gender);
			var BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + MatOrPat + " relationship is ";
			if (doBoth == true) {
				BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + "Paternal and Maternal relationships are both ";
			}

			matchUnits = 'cM';
			if (matchPerCent > "") {
				altMatchMeas = "(" + (matchPerCent) + "% DNA shared)";
			} else {
				altMatchMeas = ""; 
			}
			 

			// alert(theCommonAncestorName);

			theTestTakerCitationName = Person2FullName4Citation;
			Person2FirstName = Person2FirstName4Citation;
			Person2NameOnly = Person2FullName4Citation;

			// alert("Assemble C 2b");
			 

			theTestTakerCitationName3 = Person3FullName4Citation;
			Person3FirstName = Person3FirstName4Citation;
			Person3NameOnly = Person3FullName4Citation;

			 
			
			if (ahnenIndexArrays[3].length > 0) {
				 
				var relOfP1toP3WithoutPronoun = relOfP1toP3.substr( relOfP1toP3.indexOf(" ") + 1 ).replace("cousin", "cousins");
 					thirdPersonInfoA = " and " + Person3FullName4Citation  + ", " + relOfP3toP2;
 					thirdPersonInfoB = ". (" + theNextBestFirstName( theProfilePerson) + " and " + Person3FirstName + " are " + relOfP1toP3WithoutPronoun + ".)";
 					relCA2ThirdPerson = " and " + relCA2P3 + " of " + Person3FullName4Citation;
			} else { //alert(4889);
				thirdPersonInfoA = "";
				thirdPersonInfoB = ".";
				relCA2ThirdPerson = "";
			}


			// if (!relOfP2toP1) {
				// alert ("Compare : " +  relOfP2toP1 + " to " + relOfP1toP2);
			// }
			if (theDNAconfType == '1'){
				DNAcompany = document.getElementById('DNAcompany').value;
				if (DNAcompany == 'GEDmatch') {
					var P1KitInfo = "GEDmatch kit # " + document.getElementById('DNAkitGMA').value + ", ";
					var P2KitInfo = ", GEDmatch kit # " + document.getElementById('DNAkitGMB').value + ", ";
				} else {
					var P1KitInfo = "";
					var P2KitInfo = "";
				}
					
				let theXofTT1 = "";
				if (theTT1isActually == "child") {
					theXofTT1 = "a child of ";
				} else if (theTT1isActually == "grandchild") {
					theXofTT1 = "a grandchild of ";
				}

				var citation = BeginningOfCitation + "confirmed by an autosomal " + DNAcompany4Citation(DNAcompany) + " between " + Person2FullName4Citation + " "+ P2KitInfo +"and " + theXofTT1 + Person1FullName4Citation +  ", " + P1KitInfo + relOfP1toP2 + ". Their most-recent common ancestor" + theCommonAncestorName + ", the " + relCA2P2 + " of "; 

				if (relCA2P1 == relCA2P2) {
					citation += "both " + "" + Person2FullName4Citation + " and  " + Person1FullName4Citation +  ". ";
				} else {
					citation +=  "" + Person2FullName4Citation + " and " + relCA2P1 + " of " + Person1FullName4Citation +  ". ";
				}

				citation += "Predicted relationship from " + DNAcompany + ": " + relationshipPrediction + ", based on sharing " + matchAmount + " " + matchUnits + " " + altMatchMeas + " across " + NumSegments + " segment" + "s" + ".";

			} else if (theDNAconfType == 'y'){
				DNAcompany = document.getElementById('YDNAcompany').value;
				if (document.getElementById('YDNAkitP1').value > '' && document.getElementById('YDNAkitP1').value != 0){
					var P1KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('YDNAkitP1').value;
				} else {
					var P1KitInfo = "";
				}
				if (document.getElementById('YDNAkitP2').value > '' && document.getElementById('YDNAkitP2').value != 0){
					var P2KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('YDNAkitP2').value  + ","; // don't add comma to end, already part of citation
				} else {
					var P2KitInfo = "";
				}

			
				let matchDetails = getYDNAmatchDetails();

				var citation = BeginningOfCitation + "confirmed through Y-chromosome DNA testing on " + DNAcompanyName(DNAcompany) + ".  " + Person2FullName4Citation + P2KitInfo + " and " + relOfP1toP2 +", " + Person1FullName4Citation +  "" + P1KitInfo + ", " + matchDetails + ", thereby confirming their direct paternal lines back to their most-recent common ancestor who" + theCommonAncestorName  + ", the " + relCA2P2 + " of ";

				if (relCA2P1 == relCA2P2) {
					citation += "both " + Person2FullName4Citation + " and " + Person1FullName4Citation +  ". ";
				} else {
					citation +=  Person2FullName4Citation + " and " + relCA2P1 + " of  " + Person1FullName4Citation +  ". ";
				}
				citation += getHaploGroupStatement();
					
			} else if (theDNAconfType == 'mt'){
				DNAcompany = document.getElementById('MTDNAcompany').value;
				//genDistanceMT
				
				if (document.getElementById('MTDNAkitP1').value > '' && document.getElementById('MTDNAkitP1').value != 0){
					var P1KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('MTDNAkitP1').value ;
				} else {
					var P1KitInfo = "";
				}
				if (document.getElementById('MTDNAkitP2').value > '' && document.getElementById('MTDNAkitP2').value != 0){
					var P2KitInfo = ", " + shortDNAcompanyName(DNAcompany) + " kit # " + document.getElementById('MTDNAkitP2').value + ","; // don't add comma to end, already part of citation
				} else {
					var P2KitInfo = "";
				}
				

				var citation = BeginningOfCitation + "confirmed through Mitochondrial DNA test results on " + DNAcompanyName(DNAcompany) + ".  " + Person2FullName4Citation + P2KitInfo + " and " + relOfP1toP2 + ", " + Person1FullName4Citation +  "" + P1KitInfo +  ",  have an exact HVR1 and HVR2 match, thereby confirming their direct maternal lines back to their most-recent common ancestor who" + theCommonAncestorName  + ", the " + relCA2P2 + " of ";

				if (relCA2P1 == relCA2P2) {
					citation += "both " + Person2FullName4Citation + " and " + Person1FullName4Citation +  ". ";
				} else {
					citation +=  Person2FullName4Citation + " and " + relCA2P1 + " of  " + Person1FullName4Citation +  ". ";
				}
					
			 } else if (theDNAconfType == 'x'){
 				DNAcompany = document.getElementById('XDNAcompany').value;
 				XmatchAmount = document.getElementById('XmatchAmount').value;
 				if (isCouple && (theAnc == theAncestor || theAnc == theAncestor2)) {
 					var ConfirmedConfidentStmt = "confident based on a ";
 				} else {
 					var ConfirmedConfidentStmt = "confirmed by a ";
 				}

 				var KitNum1 = '';
				var KitNum2 = '';
				var KitNum3 = '';
				if (DNAcompany == "GEDmatch") {
					var XPAkitGMA = document.getElementById('XPAkitGMA').value;
					var XPAkitGMB = document.getElementById('XPAkitGMB').value;
					var XPAkitGMC = document.getElementById('XPAkitGMC').value;
					if (XPAkitGMA > '') { KitNum1 = ", GEDmatch kit # " + XPAkitGMA +"";}
					if (XPAkitGMB > '') { KitNum2 = ", GEDmatch kit # " + XPAkitGMB +",";}
					if (XPAkitGMC > '') { KitNum3 = ", GEDmatch kit # " + XPAkitGMC ;}
				}

 				if (thirdPersonInfoA >"") {
					var citation = BeginningOfCitation + ConfirmedConfidentStmt + " triangulated group on " + DNAcompanyName(DNAcompany) + " who share a  " + XmatchAmount + " cM segment on the X chromosome, consisting of ";
					thirdPersonInfoA = "," + thirdPersonInfoA;					
 				} else {
					var citation = "* " + MatOrPat + " relationship is " + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " autosomal DNA match of " + XmatchAmount + " cM on the X chromosome between ";
					thirdPersonInfoA = ""; 
 				}

				citation += Person2FullName4Citation + KitNum2 + " and " + Person1FullName4Citation +  ", " +  relOfP1toP2  + KitNum1 + thirdPersonInfoA + KitNum3 + thirdPersonInfoB + " "+ VerifiedByABC + "Their most-recent common ancestor" + theCommonAncestorName  + ", the " + relCA2P2 + " of ";
				if (relCA2P1 == relCA2P2) {
					citation += "both " + Person2FullName4Citation + " and " + Person1FullName4Citation +  "" +  relCA2ThirdPerson + ". ";
				} else {
					if (relCA2P1 == relCA2P3) {
						citation += Person2FullName4Citation + " and " + relCA2P1 + " of both " + Person1FullName4Citation +  " and "  + Person2FullName4Citation3 + ". ";
					} else {
						citation += Person2FullName4Citation + " and " + relCA2P1 + " of " + Person1FullName4Citation +  "" + relCA2ThirdPerson + ". ";

					}
				}	
			} else if (theDNAconfType == 'Tri'){
				// var citation = BeginningOfCitation + "confirmed by a autosomal Triangulated set of tests from Some autosomal Tirangulated set of Companys between Person A and Person B with Various Evidences ... all to be properly outlined here shortly ... stay tuned ! ";
 				// DNAcompany = document.getElementById('DNAcompany3').value;
 				// // alert("DNAcompany = " + DNAcompany);
 				// TriMatchAmount = document.getElementById('TriMatchAmount').value;
 				// ChromoNumber = document.getElementById('ChromoNum').value;
 				if (isCouple && (theAnc == theAncestor || theAnc == theAncestor2) ) {
 					var ConfirmedConfidentStmt = "confident based on";
 				} else {
 					var ConfirmedConfidentStmt = "confirmed by";
 				}
 				
 				// if (document.getElementById('TriSegmentMatchStmtStd').checked){
 				// 	theChromoSegmentStmt =  (TriMatchAmount) + " cM segment on chromosome " + (ChromoNumber);
 				// } else {
 				// 	theChromoSegmentStmt =  document.getElementById('TriCustomStmt').value; 				
				// }

				// var KitNum1 = '';
				// var KitNum2 = '';
				// var KitNum3 = '';
				// var KitNumArray = ['','','','','','','','','','','','','','','','','','',''];

				// if (DNAcompany == "GEDmatch") {
				// 	for (var i = 1; i < ahnenIndexArrays.length; i++) {
				// 		let kitInputID = "DNAkitGM"+i;
				// 		let kitInput = document.getElementById(kitInputID);
				// 		if (kitInput && kitInput.value > '') {
				// 			KitNumArray[i] = ", GEDmatch kit # " + kitInput.value  +",";
				// 		}
				// 	}

				// 	// var TriPAkitGMA = document.getElementById('TriPAkitGMA').value;
				// 	// var TriPAkitGMB = document.getElementById('TriPAkitGMB').value;
				// 	// var TriPAkitGMC = document.getElementById('TriPAkitGMC').value;
				// 	// if (TriPAkitGMA > '') { KitNum1 = " GEDmatch kit # " + TriPAkitGMA +",";}
				// 	// if (TriPAkitGMB > '') { KitNum2 = ", GEDmatch kit # " + TriPAkitGMB +",";}
				// 	// if (TriPAkitGMC > '') { KitNum3 = ", GEDmatch kit # " + TriPAkitGMC ;}
				// }

				// // alert ("Avengers assemble ..");
				// var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " who share a " + theChromoSegmentStmt + ", consisting of " + Person2FullName4Citation + KitNum2 + " and " + Person1FullName4Citation +  ", " +  relOfP1toP2  + "," + KitNum1 + thirdPersonInfoA + KitNum3 + thirdPersonInfoB + VerifiedByABC +" Their most-recent common ancestor" + theCommonAncestorName  + ", the " + relCA2P2 + " of ";
				// // alert (citation);
				// // alert (relCA2P1  + "  / " +  relCA2P2 + "  / " +  relCA2P3);
				// if (relCA2P1 == relCA2P2) {
				// 	// alert ("IF1 " )
				// 	citation += "both " + Person2FullName4Citation + " and " + Person1FullName4Citation +  "" +  relCA2ThirdPerson + ". ";
				// } else {
				// 	// alert ("IF2 " )
				// 	if (relCA2P1 == relCA2P3) {
				// 	// alert ("IF2 A " )
				// 		citation += Person2FullName4Citation + " and " + relCA2P1 + " of both " + Person1FullName4Citation +  " and "  + Person3FullName4Citation + ". ";
				// 	} else {
				// 	// alert ("IF2 B " )
				// 		citation += Person2FullName4Citation + " and " + relCA2P1 + " of " + Person1FullName4Citation +  "" + relCA2ThirdPerson + ". ";

				// 	}
				// }	

				var citation = BeginningOfCitation + ConfirmedConfidentStmt + triCitationStarterArray[2];
				
			} else {
				var citation = BeginningOfCitation + "confirmed by a DNA test of Some Sort by a Company between Person A and Person B with Various Evidences ... all to be properly outlined here shortly ... stay tuned ! ";
			}
			// alert(citation);
			citation += theAdditionalNote;		
			// alert("*" + theAdditionalNote + "*");
			if (theDNAconfType == 'Tri'){
				citation += "\n** " + theEndProviso;
			}
			// add the code to "sign the citation"  - to add the name of the citation author and date the citation was added to the profile
			citation += credit4DNAconfAppPreamble + versionNum + " - ~~~~";				
			return citation;
		}
		
		function assembleCitation3(theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false) {
			// DNAcompany + " at " + matchAmount + " at " + matchUnits + " at " + NumSegments + " at " +  relationshipPrediction + "." ;
			console.log("Assemble CITATION (3) : ",{theAnc});

			let DiedTooSoonInvalidCitationWarning = "";
			if (showDeathWarning) {
				DiedTooSoonInvalidCitationWarning = "INVALID DNA CONFIRMATION STATEMENT - FOR TESTING PURPOSES ONLY - \"DNA Tester\" died before DNA testing was available!\n";
			}


			var MatOrPat = "Maternal";
			if (theAnc.Gender == 'Male') { MatOrPat = "Paternal";}
			relationshipBetweenP1andP2 = 'cousins';
			
			var BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + MatOrPat + " relationship is ";
			if (doBoth == true) {
				BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + "Paternal and Maternal relationships are both ";
			}

			matchUnits = 'cM';
			if (matchPerCent > "") {
				altMatchMeas = "(" + (matchPerCent) + "% DNA shared)";
			} else {
				altMatchMeas = ""; 
			}
			//(NumSegments > 1 ? "s" : "")
			if (isCouple) {
				theCommonAncestorName = "s are [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]] and [[" + theAncestor2.Name + "|" + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2) + "]]"; 
			} else {
				theCommonAncestorName = " is  [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]]"; 
			}

			ProfilePersonName = "" + Person1FullName4Citation +  "";
			Person1FirstName = Person1FirstName4Citation ;
			

			theTestTakerCitationName = Person2FullName4Citation;
			Person2FirstName = Person2FirstName4Citation;
			Person2NameOnly = Person2FullName4Citation;

			// alert("Assemble C 2b");
			 

			theTestTakerCitationName3 = Person3FullName4Citation;
			Person3FirstName = Person3FirstName4Citation;
			Person3NameOnly = Person3FullName4Citation;

			// // alert("Assemble C 2");
			// if( numGensFromWikiTreeID2toCousin > 0) {
			// 	theTestTakerCitationName = theOriginalID2;
			// 	Person2FirstName = theOriginalID2;
				
			// } else if (theProfilePerson2) {
			// 	// alert(2958);
			// 	theTestTakerCitationName = "[[" + theProfilePerson2WikiLink + "]]";
			// 	Person2FirstName =  theNextBestFirstName( theProfilePerson2);
			// } else {
			// 	// alert(2961);
			// 	// alert (theTestTaker);
			// 	if (theTestTaker > '') {
			// 		// alert(2963);
			// 		theTestTakerCitationName = theTestTaker;
			// 		Person2FirstName = theTestTaker;
			// 	} else {
			// 		// alert(2966);
			// 		theTestTakerCitationName = 'the Other Test Taker';
			// 		Person2FirstName = 'The Other Test Taker';
			// 	}
			// }

			// if (ahnenIndexArrays[3].length > 0) {
			// 	if( numGensFromWikiTreeID3toCousin > 0) {
			// 		theTestTakerCitationName3 = theOriginalID3;
			// 		Person3FirstName = theOriginalID3;
			// 	} else if (theID3 > '') {
			// 		// alert(2958);
			// 		theTestTakerCitationName3 = "[[" + theProfilePerson3WikiLink + "]]";
			// 		Person3FirstName =   theNextBestFirstName( theProfilePerson3) ;
			// 	} else {
			// 		// alert(2961);
			// 		// alert (theTestTaker);
			// 		if (theOriginalID3 > '') {
			// 			// alert(2963);
			// 			theTestTakerCitationName3 = theOriginalID3;
			// 			Person3FirstName = theOriginalID3;
			// 		} else {
			// 			// alert(2966);
			// 			theTestTakerCitationName3 = 'the Other Test Taker';
			// 			Person3FirstName = 'The Third Test Taker';
			// 		}
			// 	}
				
			// } 
			
				var relOfP1toP2WithoutPronoun    = relOfP1toP2.substr( relOfP1toP2.indexOf(" ") + 1 ).replace("cousin", "cousins");
 				var	relInfo = " (" + theNextBestFirstName( theProfilePerson) + " and " + Person2FirstName + " are " + relOfP1toP2WithoutPronoun + ".)";
					



 				if (isCouple && (theAnc == theAncestor || theAnc == theAncestor2)) {
 					var ConfirmedConfidentStmt = "confident based on a triangulated group on ";
 				} else {
 					var ConfirmedConfidentStmt = "confirmed by a triangulated group on ";
 				}			
			// if (!relOfP2toP1) {
				// alert ("Compare : " +  relOfP2toP1 + " to " + relOfP1toP2);
			// }
			var KitNum1 = '';
			var KitNum2 = '';
			var KitNum3 = '';
			

			if (theDNAconfType == 'x'){
 				DNAcompany = document.getElementById('XDNAcompany').value;
 				XmatchAmount = document.getElementById('XmatchAmount').value;
				
				if (DNAcompany == "GEDmatch") {
					var XPAkitGMA = document.getElementById('XPAkitGMA').value;
					var XPAkitGMB = document.getElementById('XPAkitGMB').value;
					var XPAkitGMC = document.getElementById('XPAkitGMC').value;
					if (XPAkitGMA > '') { KitNum1 = ", GEDmatch kit # " + XPAkitGMA +"";}
					if (XPAkitGMB > '') { KitNum2 = ", GEDmatch kit # " + XPAkitGMB ;}
					if (XPAkitGMC > '') { KitNum3 = ", GEDmatch kit # " + XPAkitGMC +"," ;}
				}
 				 
 					
				var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " who share a " + XmatchAmount + " cM segment on the X chromosome, consisting of ";
			
				
			} else if (theDNAconfType == 'Tri'){
				// var citation = BeginningOfCitation + "confirmed by a autosomal Triangulated set of tests from Some autosomal Tirangulated set of Companys between Person A and Person B with Various Evidences ... all to be properly outlined here shortly ... stay tuned ! ";
 				// DNAcompany = document.getElementById('DNAcompany3').value;
 				// // alert("DNAcompany = " + DNAcompany);
 				// TriMatchAmount = document.getElementById('TriMatchAmount').value;
 				// ChromoNumber = document.getElementById('ChromoNum').value;
				
				// if (document.getElementById('TriSegmentMatchStmtStd').checked){
 				// 	theChromoSegmentStmt =  (TriMatchAmount) + " cM segment on chromosome " + (ChromoNumber);
 				// } else {
 				// 	theChromoSegmentStmt =  document.getElementById('TriCustomStmt').value; 				
				// }

				// var KitNumArray = ['','','','','','','','','','','','','','','','','','',''];
				// if (DNAcompany == "GEDmatch") {
				// 	for (var i = 1; i < ahnenIndexArrays.length; i++) {
				// 		let kitInputID = "DNAkitGM"+i;
				// 		let kitInput = document.getElementById(kitInputID);
				// 		if (kitInput && kitInput.value > '') {
				// 			KitNumArray[i] = ", GEDmatch kit # " + kitInput.value  +",";
				// 		}
				// 	}

				// 	// var TriPAkitGMA = document.getElementById('TriPAkitGMA').value;
				// 	// var TriPAkitGMB = document.getElementById('TriPAkitGMB').value;
				// 	// var TriPAkitGMC = document.getElementById('TriPAkitGMC').value;
				// 	// if (TriPAkitGMA > '') { KitNum1 = ", GEDmatch kit # " + TriPAkitGMA +"";}
				// 	// if (TriPAkitGMB > '') { KitNum2 = ", GEDmatch kit # " + TriPAkitGMB +"";}
				// 	// if (TriPAkitGMC > '') { KitNum3 = ", GEDmatch kit # " + TriPAkitGMC + "," ;}
				// }


				// var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " who share a " + theChromoSegmentStmt + ", consisting of ";
				var citation = BeginningOfCitation + ConfirmedConfidentStmt + triCitationStarterArray[3];
				
			}
			
			citation += theTestTakerCitationName3 + KitNum3 + " and " + Person1FullName4Citation +  ", " +  relOfP1toP3  + KitNum1 + ", and " + theTestTakerCitationName +  ", "  +  relOfP2toP3 + KitNum2 + "." + relInfo + VerifiedByABC + " Their most-recent common ancestor" + theCommonAncestorName  + ", the " + relCA2P3 + " of ";
			
				if (relCA2P1 == relCA2P3) {
					citation += "both " + theTestTakerCitationName3 + " and " + Person1FullName4Citation + " and the " + relCA2P2 + " of " + theTestTakerCitationName  + ". ";
				} else {
					if (relCA2P1 == relCA2P2) {
						citation += theTestTakerCitationName3 + " and " + relCA2P1 + " of both " + Person1FullName4Citation + " and "  + theTestTakerCitationName + ". ";
					} else {
						citation += theTestTakerCitationName3 + ", " + relCA2P1 + " of " + Person1FullName4Citation + " and " + relCA2P2 + " of " + theTestTakerCitationName  + ". "; 

					}
				}	
			
				
				

			citation += theAdditionalNote;		
			if (theDNAconfType == 'Tri'){
				citation += "\n** " + theEndProviso;
			}
			// add the code to "sign the citation"  - to add the name of the citation author and date the citation was added to the profile
			citation += credit4DNAconfAppPreamble + versionNum + " - ~~~~";				
			return citation;
		}

		function assembleCitationN(testerNum , theAnc, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, doBoth = false) {
			// DNAcompany + " at " + matchAmount + " at " + matchUnits + " at " + NumSegments + " at " +  relationshipPrediction + "." ;
			console.log("Assemble CITATION (N) : ",{theAnc}, {theAncestor}, {theAncestor2});

			let DiedTooSoonInvalidCitationWarning = "";
			if (showDeathWarning) {
				DiedTooSoonInvalidCitationWarning = "INVALID DNA CONFIRMATION STATEMENT - FOR TESTING PURPOSES ONLY - \"DNA Tester\" died before DNA testing was available!\n";
			}


			var MatOrPat = "Maternal";
			if (theAnc.Gender == 'Male') { MatOrPat = "Paternal";}
			relationshipBetweenP1andP2 = 'cousins';
			
			let BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + MatOrPat + " relationship is ";
			if (doBoth == true) {
				BeginningOfCitation = "* " + DiedTooSoonInvalidCitationWarning + "Paternal and Maternal relationships are both ";
			}

			matchUnits = 'cM';
			if (matchPerCent > "") {
				altMatchMeas = "(" + (matchPerCent) + "% DNA shared)";
			} else {
				altMatchMeas = ""; 
			}
			//(NumSegments > 1 ? "s" : "")
			if (isCouple) {
				theCommonAncestorName = "s are [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]] and [[" + theAncestor2.Name + "|" + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2) + "]]"; 
			} else {
				theCommonAncestorName = " is  [[" + theAncestor.Name + "|" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "]]"; 
			}

			ProfilePersonName = "" + Person1FullName4Citation +  "";
			Person1FirstName = Person1FirstName4Citation ;
			

			theTestTakerCitationName = Person2FullName4Citation;
			Person2FirstName = Person2FirstName4Citation;
			Person2NameOnly = Person2FullName4Citation;

			// alert("Assemble C 2b");
			
			Person3FirstName = Person3FirstName4Citation;
			theTestTakerCitationName3 = Person3FullName4Citation;
			Person3NameOnly = Person3FullName4Citation;

			Person3FirstName = theNextBestFirstName( personArray[ ahnenIndexArrays[testerNum][1]]);
			theTestTakerCitationName3 = Person3FirstName + " " + theBestLastName( personArray[ ahnenIndexArrays[testerNum][1]]);
			Person3NameOnly = theTestTakerCitationName3;

			var relOfP1toP2WithoutPronoun    = relOfP1toP2.substr( relOfP1toP2.indexOf(" ") + 1 ).replace("cousin", "cousins");
			var	relInfo = " (" + theNextBestFirstName( theProfilePerson) + " and " + Person2FirstName + " are " + relOfP1toP2WithoutPronoun + ".)";
	
			var ConfirmedConfidentStmt = "confirmed by";
			if (isCouple && (theAnc == theAncestor || theAnc == theAncestor2)) {
				var ConfirmedConfidentStmt = "confident based on";
			} else {
			}	

			
			var KitNum1 = '';
			var KitNum2 = '';
			var KitNum3 = '';
			

			if (theDNAconfType == 'x'){
 				DNAcompany = document.getElementById('XDNAcompany').value;
 				XmatchAmount = document.getElementById('XmatchAmount').value;
				
				if (DNAcompany == "GEDmatch") {
					var XPAkitGMA = document.getElementById('XPAkitGMA').value;
					var XPAkitGMB = document.getElementById('XPAkitGMB').value;
					var XPAkitGMC = document.getElementById('XPAkitGMC').value;
					if (XPAkitGMA > '') { KitNum1 = ", GEDmatch kit # " + XPAkitGMA +"";}
					if (XPAkitGMB > '') { KitNum2 = ", GEDmatch kit # " + XPAkitGMB ;}
					if (XPAkitGMC > '') { KitNum3 = ", GEDmatch kit # " + XPAkitGMC +"," ;}
				}
 				 
 					
				var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " who share a " + XmatchAmount + " cM segment on the X chromosome, consisting of ";
			
				
			} else if (theDNAconfType == 'Tri'){
				 


				var citation = BeginningOfCitation + ConfirmedConfidentStmt + triCitationStarterArray[testerNum];
				// var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " who share a " + theChromoSegmentStmt + ", consisting of ";
				// var citation = BeginningOfCitation + ConfirmedConfidentStmt + DNAcompanyName(DNAcompany) + " who share a " + theChromoSegmentStmt + ", consisting of ";
				
			}
			
			 
			

			citation += theAdditionalNote;		


			if (theDNAconfType == 'Tri'){
				citation += "\n** " + theEndProviso;
			}

			// add the code to "sign the citation"  - to add the name of the citation author and date the citation was added to the profile
			citation += credit4DNAconfAppPreamble + versionNum + " - ~~~~";				

			return citation;
		}

		// function assembleExtraCitations - called from function  doneForm6()
		// THIS is the UBER CITATION BABY-MAKING MACHINE HERE:

		// The ORIGINAL - PRIME - Citation has already been created by doneForm6() calling the function assembleCitation() (citation for MRCA from TestTaker#1's branch)
		// THIS function then follows that up by doing this:
		// --> calls assembleCitation inside a WHILE loop to finish off the rest of the TestTaker #1s branch

		// --> creates the top level citation for TestTaker # 2, using assembleCitation2()
		// --> calls assembleCitation2 inside a WHILE loop to finish off the rest of the TestTaker #2s branch

		// --> creates the top level citation for TestTaker # 3, using assembleCitation2()
		// --> calls assembleCitation3 inside a WHILE loop to finish off the rest of the TestTaker #3s branch

		// SHOULD CHANGE THIS TO ASSEMBLE CITATION N AND MAKE IT FLEXIBLE TO ALL ALL N TEST TAKERS!

		function assembleExtraCitations(DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction) {
			// alert("assembleExtraCitations");
			console.log("Assemble CITATION (EXTRA) -  : ",{DNAcompany});
			var theInfo = "<I>Optional:<BR><input type=checkbox name=addExtraPersonal id=addExtraPersonal0 onchange='swapBonusInfo(1);'> Add relationship of DNA tester to Profile Ancestors in Bonus citations</I><HR>";
			var theInfoX = "<I>Optional:<BR><input type=checkbox name=addExtraPersonal id=addExtraPersonal1 checked onchange='swapBonusInfo(0);'> Add relationship of DNA tester to Profile Ancestors in Bonus citations</I><HR>";
			var numOfExtras = 0;
			var additionalNames = "";
			
			var childsAhnNum = Math.floor(theAncsAhnNum / 2);	

			var assemble4Tester1OK = true;
			var assemble4Tester2OK = true;

			if(theDNAconfType == "Tri" ){
				assemble4Tester1OK = yayTestersList[1];
				assemble4Tester2OK = yayTestersList[2];
			}

			document.getElementById('ExtraInfo').innerHTML = "";
			document.getElementById('additionalNames').innerHTML = "";

			// alert(3037);
			if (isCouple) {
				var TheCommonAncestorNameSimple =  theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + " & " + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2)  ; 
			// alert(3039);
				var TheCommonAncestorNameSimpleWithIDs =  "<B>" + theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor) + "</B>" + " (" + theAncestor.Name + ")" + " & " + "<B>" + theNextBestFirstName(theAncestor2) + " " + theBestLastName(theAncestor2) + "</B>" + " (" + theAncestor2.Name + ")"  ; 
			} else {
			// alert(3042);
				var TheCommonAncestorNameSimple =  theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor)  ; 
				var TheCommonAncestorNameSimpleWithIDs =  theNextBestFirstName(theAncestor) + " " + theBestLastName(theAncestor)  + " (" + theAncestor.Name + ")" ; 
			}

			var TheCommonAncestorNameSimpleUC = TheCommonAncestorNameSimple.toUpperCase();
			document.getElementById('Div7CAa').textContent = TheCommonAncestorNameSimpleUC;
			document.getElementById('Div7CAb').innerHTML = TheCommonAncestorNameSimpleWithIDs;

			let theSectionHeader = "<H5 style='background-color:lime;'>Finish Confirmations from DNA Tester " + theNextBestFirstNameLastName(ahnenIndexArrays[1][1])+ " to MRCA" + (isCouple?"s ":" ") +  TheCommonAncestorNameSimple + "</H5>";
			let addedSectionHeader = false;

			
			// alert(3050);
			// MAKE SURE that the correct IMAGE is showing on the final page about what to do
			if (isCouple) {
				if (theDNAconfType == '1') {
					document.getElementById('DNAimageCodeMother').style.display = 'none';
					document.getElementById('DNAimageCodeFather').style.display = 'none';
					document.getElementById('DNAimageCodeParents').style.display = 'block';
					document.getElementById('DNAimageCodeParentsConfident').style.display = 'none';
				} else {
					document.getElementById('DNAimageCodeMother').style.display = 'none';
					document.getElementById('DNAimageCodeFather').style.display = 'none';
					document.getElementById('DNAimageCodeParents').style.display = 'none';
					document.getElementById('DNAimageCodeParentsConfident').style.display = 'block';
				}
			} else if (theAncsAhnNum % 2 == 0) {
				document.getElementById('DNAimageCodeMother').style.display = 'none';
				document.getElementById('DNAimageCodeFather').style.display = 'block';
				document.getElementById('DNAimageCodeParents').style.display = 'none';
				document.getElementById('DNAimageCodeParentsConfident').style.display = 'none';
			} else {
				document.getElementById('DNAimageCodeMother').style.display = 'block';
				document.getElementById('DNAimageCodeFather').style.display = 'none';
				document.getElementById('DNAimageCodeParents').style.display = 'none';
				document.getElementById('DNAimageCodeParentsConfident').style.display = 'none';
			}

			// alert(6006);
			var bkgdClrs = ['#FFEEEE', '#EEFFEE','#EEEEFF', '#EEEEDD','#DDEEEE', '#EEDDEE'];
			while (childsAhnNum > 1 && assemble4Tester1OK) {
				// alert("while ... " + childsAhnNum);
				numOfExtras++;
				var nextChildNum = Math.floor(childsAhnNum / 2);	
				if (ahnenIndexArrays[1][childsAhnNum] && ahnenIndexArrays[1][nextChildNum]) {
					if (personArray[ahnenIndexArrays[1][childsAhnNum]] && personArray[ahnenIndexArrays[1][nextChildNum]] && ahnenIndexArrays[1][childsAhnNum] > 0) {
						thePerson = personArray[ahnenIndexArrays[1][childsAhnNum]];
						theChild = personArray[ahnenIndexArrays[1][nextChildNum]];

						 additionalNames += " <BR> ";

							thePersonFirstName =  theNextBestFirstName(thePerson);
						
							theChildFirstName =  theNextBestFirstName(theChild);
						let doExtraCitation = true;	
						if (theChild) {
							if (theChild.Name == undefined) {
								console.log("UNABLE to Assemble the Citation for ", theChildFirstName) ;
								doExtraCitation = false;
							} else {
								console.log("Should be able to Assemble the Citation for ", theChildFirstName, "*", theChild.Name, "*") ;

							}
						} else {
							console.log("Will likely have trouble trying to Assemble the Citation for ", theChildFirstName) ;
						}

						if (doExtraCitation) {
							theMarkedVerifiedCheckbox = theMVCheckboxMarked ; //"<div align=right>Marked. Citation added. DONE !</div>";
							let theMarkedStarter = "";
							console.log("SETUP for VERIFY / MARK 4: ", theChild.FirstName, theChild.DataStatus.Father, theChild.DataStatus.Mother, isCouple, thePerson.FirstName, childsAhnNum);

							if (childsAhnNum % 2 == 0 && theChild.DataStatus.Father == 30) {
								theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
								theMarkedStarter = "Verify / ";
							} else if (childsAhnNum % 2 == 1 && theChild.DataStatus.Mother == 30) {
								theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
								theMarkedStarter = "Verify / ";
							}


							
							if(addedSectionHeader == false) {
								theInfo += theSectionHeader;
								theInfoX += theSectionHeader;
								addedSectionHeader = true;	
							}

							additionalNames += thePersonFirstName + " " + thePerson.LastNameAtBirth;
							theInfo += "<P id=theInfoPara" + nextChildNum + "  style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + " ;'  bgClr=" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + " >" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePerson.LastNameAtBirth.toUpperCase() + "</span></B><BR>";

							theInfo += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>"


							theInfoX += "<P id=theInfoXPara" + nextChildNum + "  style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + " ;'  bgClr=" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + " >" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePerson.LastNameAtBirth.toUpperCase() + "</span></B><BR>";
							theInfoX += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>"

							if (childsAhnNum % 2 == 0) {
								theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
								theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
							} else {
								theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
								theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
							}
							console.log(3091 + " Assemble Citation for ID # " + thePerson.Id);
							var thisCitation = assembleCitation(thePerson,DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction);
							// alert(3091 + " Done ID # " + thePerson.Id + " and child # " + theChild.Id);
							// alert(3093);
							var thisCitationX = thisCitation + relationshipBetween4inLineage(Person1FullName4Citation, Person1FirstName4Citation,  theProfilePerson.Gender, theChild, thePerson, theAncestor, childsAhnNum, theAncsAhnNum);
							// alert(3095 + " w Relationship");
							if (theDNAconfType == 'Tri'){								
								thisCitationX = thisCitationX.replace(theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + "\n** " + theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~";
							} else {
								// move signature to the end of the line
								thisCitationX = thisCitationX.replace(credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + credit4DNAconfAppPreamble + versionNum + " - ~~~~";
							}
							

							theMarkedVerifiedCheckbox = theMarkedVerifiedCheckbox.replace("123", nextChildNum);
							console.log({theMVCheckboxMarked}, {theMarkedVerifiedCheckbox})
							theInfo += "<br><textarea id=ExtraCitation" + nextChildNum + " rows=9 cols=80>" + thisCitation + "</textarea><br>";
							theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitation" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

							theInfoX += "<br><textarea id=ExtraCitationX" + nextChildNum + " rows=9 cols=80>" + thisCitationX + "</textarea><br>";
							theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationX" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";
						}

					}
				}

				childsAhnNum = nextChildNum ;	

			}

			
			// =========== === ====== ==== ====='= =========' ========== ====== =========
			// ROUTINE for the SECOND TEST TAKER'S ANCESTORS' ADDITIONAL SOURCE CITATIONS
			// =========== === ====== ==== ====='= =========' ========== ====== =========
			

			console.log ("Compare theAncsAhnNum2 to theAncsAhnNum4TT : "); 
			console.log (7056 + " : "  + theAncsAhnNum2  + " v " + theAncsAhnNum4TT );
			console.log("theTestTakerID: " + theTestTakerID);

			if (theAncsAhnNum2 > 0 &&  theDNAconfType != 1) {
				theAncsAhnNum4TT = theAncsAhnNum2;
				theTestTakerID = 'TestTaker2';
				theTestPerson = personArray[ ahnenIndexArrays[2][1]];
			}

			console.log("theAncsAhnNum2: " + theAncsAhnNum2 ,  "ahnenIndexArrays[2].length: " + ahnenIndexArrays[2].length) ;
			console.log("theAncsAhnNum3: " + theAncsAhnNum3 ,  "ahnenIndexArrays[3].length: " + ahnenIndexArrays[3].length) ;


			if (theTestTakerID > '' && theAncsAhnNum4TT > 1 && !isDirectAncestor && assemble4Tester2OK) {
				// alert(6063);
				childsAhnNum = Math.floor(theAncsAhnNum4TT / 2);	
				// alert(6120 + " childsAhnNum = " + childsAhnNum);
				thePerson = personArray[ahnenIndexArrays[2][childsAhnNum]];
				// alert("Hello ?");
				if ((theDNAconfType == 'x' || theDNAconfType == 'Tri') && isCouple) {
					var ConfirmedConfidentStmt = 'confident';
				} else {
					var ConfirmedConfidentStmt = 'confirmed with DNA';
				}
				thePersonLastName = theBestLastName(thePerson);
				nextChildNum = "TT";

				let theMarkedStarter = "";
				theMarkedVerifiedCheckbox = theMVCheckboxMarked ; //"<div align=right>Marked. Citation added. DONE !</div>";

				console.log("SETUP for VERIFY / MARK 5: ", thePerson.FirstName, thePerson.DataStatus.Father, thePerson.DataStatus.Mother, isCouple);

				if (isCouple == true && thePerson.DataStatus.Mother == 30 && thePerson.DataStatus.Father == 30) {
					theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
					theMarkedStarter = "Verify / ";

				} else if (isCouple == false && childsAhnNum % 2 == 0 && thePerson.DataStatus.Father == 30) {
					theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
					theMarkedStarter = "Verify / ";
				} else if (isCouple == false && childsAhnNum % 2 == 1 && thePerson.DataStatus.Mother == 30) {
					theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
					theMarkedStarter = "Verify / ";
				}

				theSectionHeader = "<H5 style='background-color:lime;'>Do Confirmations from DNA Tester " + theNextBestFirstNameLastName(ahnenIndexArrays[2][1])+ " to MRCA" + (isCouple?"s ":" ") +  TheCommonAncestorNameSimple + "</H5>";
				theInfo += theSectionHeader;
				theInfoX += theSectionHeader;

				// alert ("Last Name = " + thePersonLastName);
				theInfo += "<P id=theInfoPara" + nextChildNum + " bgClr=" + bkgdClrs[ ((numOfExtras+1) % bkgdClrs.length ) ] + "  style='background-color:" + bkgdClrs[ ((numOfExtras+1) % bkgdClrs.length ) ] + ";'>" + (numOfExtras+1) + "<BR/><B>DNA Confirmation <u>from</u> "  + theNextBestFirstName(thePerson).toUpperCase() + " " + thePersonLastName.toUpperCase() + " <u>for</u><BR><span style='font-size: 20;'> " + TheCommonAncestorNameSimpleUC + "</span></B><BR>";

				theInfo += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  thePerson.Name.replace(" ","_").replace(" ","_") +">" + theNextBestFirstName(thePerson) + " " + thePersonLastName + "</A></span> (" + thePerson.Name + ")<BR>to "  + TheCommonAncestorNameSimpleWithIDs + "<BR>as <b>" + ConfirmedConfidentStmt + "</b> <br>";
				 

				theInfoX += "<P id=theInfoXPara" + nextChildNum + " bgClr=" + bkgdClrs[ ((numOfExtras+1) % bkgdClrs.length ) ] + "  style='background-color:" + bkgdClrs[ ((numOfExtras+1) % bkgdClrs.length ) ] + ";'>" + (numOfExtras+1) + "<BR/><B>DNA Confirmation <u>from</u> "  + theNextBestFirstName(thePerson).toUpperCase() + " " + thePersonLastName.toUpperCase() + " <u>for</u><BR><span style='font-size: 20;'> " + TheCommonAncestorNameSimpleUC + "</span></B><BR>";
				theInfoX += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  thePerson.Name.replace(" ","_").replace(" ","_") +">" + theNextBestFirstName(thePerson) + " " + thePersonLastName + "</A></span> (" + thePerson.Name + ")<BR>to "  + TheCommonAncestorNameSimpleWithIDs + "<BR>as <b>" + ConfirmedConfidentStmt + "</b> <br>";

				// alert ("Round 2 : childsAhnNum " + childsAhnNum  + " is " +  thePerson.Gender + " from " + theAncestor.Gender);
				if (isCouple) {
					if (theDNAconfType == 'x' || theDNAconfType == 'Tri') {
						theInfo += "<img src='images/FatherConfidentDNAradiobtn.png'>";
						theInfo += "<img src='images/MotherConfidentDNAradiobtn.png'>";
						theInfoX += "<img src='images/FatherConfidentDNAradiobtn.png'>";
						theInfoX += "<img src='images/MotherConfidentDNAradiobtn.png'>";
					} else {
						theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
						theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
						theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
						theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
					}
						
				} else if (/*childsAhnNum % 2 == 0 || (childsAhnNum == 1 &&*/ theAncestor.Gender == 'Male') {
					theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
					theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
				} else {
					theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
					theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
				}

				// alert(5345);
				var thisCitation = assembleCitation2(theAncestor, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, isCouple);				
				// alert(5351);

				


				theMarkedVerifiedCheckbox = theMarkedVerifiedCheckbox.replace("123", nextChildNum);
				
				theInfo += "<br><textarea id=ExtraCitationTT"  + " rows=9 cols=80>" + thisCitation + "</textarea>";
				theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationTT"  + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

				theInfoX += "<br><textarea id=ExtraCitationXTT"  + " rows=9 cols=80>" + thisCitation + "</textarea>";
				theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationXTT"  + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

				numOfExtras++;
				additionalNames += "<BR><BR>" + TheCommonAncestorNameSimple + " from " + theNextBestFirstName(thePerson) + " " + theBestLastName( thePerson) + "<BR>";
 				// alert(5360);
				while (childsAhnNum > 1 ) {
					
					numOfExtras++;
					var nextChildNum = Math.floor(childsAhnNum / 2);	
					// alert("nextChildNum: "+ nextChildNum);
					if (ahnenIndexArrays[2][childsAhnNum] && ahnenIndexArrays[2][nextChildNum]) {
						
						if (personArray[ahnenIndexArrays[2][childsAhnNum]] && personArray[ahnenIndexArrays[2][nextChildNum]] ) {
						// alert(thePerson.LastNameAtBirth + " & " + theChild.LastNameAtBirth);							
							thePerson = personArray[ahnenIndexArrays[2][childsAhnNum]];
							theChild = personArray[ahnenIndexArrays[2][nextChildNum]];

							let doExtraCitation = true;	
							if (theChild) {
								if (theChild.Name == undefined) {
									console.log("UNABLE to Assemble the Citation for ", theChildFirstName) ;
									doExtraCitation = false;
								}
							} else {
								doExtraCitation = false;
							}

							if (doExtraCitation) {

								thePersonFirstName = theNextBestFirstName(thePerson);
								theChildFirstName = theNextBestFirstName(theChild);
								thePersonLastName = theBestLastName(thePerson);
								theChildLastName = theBestLastName(theChild);

								additionalNames += thePersonFirstName + " " + thePersonLastName + "<BR>";
								
								let theMarkedStarter = "";
								theMarkedVerifiedCheckbox = theMVCheckboxMarked ; //"<div align=right>Marked. Citation added. DONE !</div>";

								console.log("SETUP for VERIFY / MARK 6: ", theChild.FirstName, theChild.DataStatus.Father, theChild.DataStatus.Mother, isCouple, thePerson.FirstName, childsAhnNum);

								if (childsAhnNum % 2 == 0 && theChild.DataStatus.Father == 30) {
									theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
									theMarkedStarter = "Verify / ";
								} else if (childsAhnNum % 2 == 1 && theChild.DataStatus.Mother == 30) {
									theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
									theMarkedStarter = "Verify / ";
								}

								theInfo += "<P id=theInfoParaTT" + nextChildNum + " bgClr=" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + " style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + ";'>" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePersonLastName.toUpperCase() + "</span></B><BR>";
								theInfo += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>";


								theInfoX += "<P id=theInfoXParaTT" + nextChildNum + " bgClr=" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + " style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + ";'>" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePersonLastName.toUpperCase() + "</span></B><BR>";
								theInfoX += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>";
								
								if (childsAhnNum % 2 == 0) {
									theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
									theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
								} else {
									theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
									theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
								}
								
								var thisCitation = assembleCitation2(thePerson,DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction);
								
								var thisCitationX = thisCitation + relationshipBetween4inLineage( Person2FullName4Citation, Person2FirstName4Citation, testTaker2Gender, theChild, thePerson, theAncestor, childsAhnNum, theAncsAhnNum4TT, theOriginalID2, numGensFromWikiTreeID2toCousin );

								if (theDNAconfType == 'Tri'){								
									thisCitationX = thisCitationX.replace(theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + "\n** " + theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~";
								} else {
									thisCitationX = thisCitationX.replace(credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + credit4DNAconfAppPreamble + versionNum + " - ~~~~"; // move the signature line to the END of the citation
								}

								

								theMarkedVerifiedCheckbox = theMarkedVerifiedCheckbox.replace("123", "TT" + nextChildNum);

								
								theInfo += "<br><textarea id=ExtraCitationTT" + nextChildNum + " rows=9 cols=80>" + thisCitation + "</textarea>";
								theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationTT" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

								theInfoX += "<br><textarea id=ExtraCitationXTT" + nextChildNum + " rows=9 cols=80>" + thisCitationX + "</textarea>";
								theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationXTT" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

							}

						}
					}
					
					childsAhnNum = nextChildNum ;	

				}
				// alert(6225);
				
			} else if (theAncsAhnNum2 == 1) {
				// nothing to see here ... the MRCA is the already at the top

			} else {
				console.log("NOT YET adding to ADDITIONAL NAMES : ", theTestTakerID , theAncsAhnNum4TT ,isDirectAncestor, theDNAconfType , theAncsAhnNum2, "TTlevel =  TBD");
				TTlevel = generationsFromMRCA;

				if (TTlevel === undefined) {
					console.log("TTlevel is undefined ");
				} else {
					console.log("TTlevel = ",TTlevel);
				}


				if (theDNAconfType == 1 && theTestTakerID == '' ) {
					let thisPeep = null;
					let lastPeep = null;
					for (var sNum = 1; sNum <= 1; sNum++) {
						thisPeep = getAncPeepAtDropDown(sNum);
						if (thisPeep) {
							// additionalNames += "<BR>" + thisPeep.RealName + " " + thisPeep.LastNameAtBirth;
							console.log("GOT ANC PEEP (1):", thisPeep);
							// childsAhnNum = Math.floor(theAncsAhnNum4TT / 2);	
							// alert(6120 + " childsAhnNum = " + childsAhnNum);
							thePerson = thisPeep;//personArray[ahnenIndexArrays[2][childsAhnNum]];
							// alert("Hello ?");
							// if ((theDNAconfType == 'x' || theDNAconfType == 'Tri') && isCouple) {
							// 	var ConfirmedConfidentStmt = 'confident';
							// } else {
							ConfirmedConfidentStmt = 'confirmed with DNA';
							// }
							thePersonLastName = theBestLastName(thePerson);
							let theMarkedStarter = "";
							// alert ("Last Name = " + thePersonLastName);
							theInfo += "<P style='background-color:" + bkgdClrs[ ((numOfExtras+1) % bkgdClrs.length ) ] + ";'>" + (numOfExtras+1) + "<BR/><B>DNA Confirmation <u>from</u> "  + theNextBestFirstName(thePerson).toUpperCase() + " " + thePersonLastName.toUpperCase() + " <u>for</u><BR><span style='font-size: 20;'> " + TheCommonAncestorNameSimpleUC + "</span></B><BR>";

							theInfo += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  thePerson.Name.replace(" ","_").replace(" ","_") +">" + theNextBestFirstName(thePerson) + " " + thePersonLastName + "</A></span> (" + thePerson.Name + ")<BR>to "  + TheCommonAncestorNameSimpleWithIDs + "<BR>as <b>" + ConfirmedConfidentStmt + "</b> <br>";
							 

							theInfoX += "<P style='background-color:" + bkgdClrs[ ((numOfExtras+1) % bkgdClrs.length ) ] + ";'>" + (numOfExtras+1) + "<BR/><B>DNA Confirmation <u>from</u> "  + theNextBestFirstName(thePerson).toUpperCase() + " " + thePersonLastName.toUpperCase() + " <u>for</u><BR><span style='font-size: 20;'> " + TheCommonAncestorNameSimpleUC + "</span></B><BR>";
							theInfoX += theMarkedStarter + "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  thePerson.Name.replace(" ","_").replace(" ","_") +">" + theNextBestFirstName(thePerson) + " " + thePersonLastName + "</A></span> (" + thePerson.Name + ")<BR>to "  + TheCommonAncestorNameSimpleWithIDs + "<BR>as <b>" + ConfirmedConfidentStmt + "</b> <br>";

							// alert ("Round 2 : childsAhnNum " + childsAhnNum  + " is " +  thePerson.Gender + " from " + theAncestor.Gender);
							if (isCouple) {
								
									theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
									theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
									theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
									theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
								
									
							} else if (  theAncestor.Gender == 'Male') {
								theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
								theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
							} else {
								theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
								theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
							}

							// alert(5345);
							var thisCitation = assembleCitation2(theAncestor, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, isCouple);				
							// alert(5351);
							
							
							theInfo += "<br><textarea id=ExtraCitationTT"  + " rows=9 cols=80>" + thisCitation + "</textarea>";
							theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationTT"  + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

							theInfoX += "<br><textarea id=ExtraCitationXTT"  + " rows=9 cols=80>" + thisCitation + "</textarea>";
							theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationXTT"  + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

							numOfExtras++;
							additionalNames += "<BR><BR>" + TheCommonAncestorNameSimple + " from " + theNextBestFirstName(thePerson) + " " + theBestLastName( thePerson) + "<BR>";
			 				

						}
					}
					for (var sNum = 2; sNum <= 5; sNum++) {
						lastPeep = thisPeep;
						thisPeep = getAncPeepAtDropDown(sNum);
						if (thisPeep) {
							// additionalNames += "<BR>" + thisPeep.RealName + " " + thisPeep.LastNameAtBirth;
							console.log("GOT ANC PEEP (" + sNum + ") :",  thisPeep);
							numOfExtras++;
							var nextChildNum = sNum; //Math.floor(childsAhnNum / 2);	
							// alert("nextChildNum: "+ nextChildNum);
							// if (ahnenIndexArrays[2][childsAhnNum] && ahnenIndexArrays[2][nextChildNum]) {
								
								// if (personArray[ahnenIndexArrays[2][childsAhnNum]] && personArray[ahnenIndexArrays[2][nextChildNum]] ) {
								// alert(thePerson.LastNameAtBirth + " & " + theChild.LastNameAtBirth);							
									thePerson = lastPeep;//personArray[ahnenIndexArrays[2][childsAhnNum]];
									theChild = thisPeep;//personArray[ahnenIndexArrays[2][nextChildNum]];

									thePersonFirstName = theNextBestFirstName(thePerson);
									theChildFirstName = theNextBestFirstName(theChild);
									thePersonLastName = theBestLastName(thePerson);
									theChildLastName = theBestLastName(theChild);

									additionalNames += thePersonFirstName + " " + thePersonLastName + "<BR>";
									
									theInfo += "<P style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + ";'>" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePersonLastName.toUpperCase() + "</span></B><BR>";
									theInfo += "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>";


									theInfoX += "<P style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + ";'>" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePersonLastName.toUpperCase() + "</span></B><BR>";
									theInfoX += "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>";
									
									if (thePerson.Gender == 'Male' ) {
										theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
										theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
									} else {
										theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
										theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
									}
									
									var thisCitation = assembleCitation2(thePerson,DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction);
									
									var thisCitationX = thisCitation + relationshipBetween4inLineage4SimpleAliases( Person2FullName4Citation, Person2FirstName4Citation, testTaker2Gender, theChild, thePerson, theAncestor,  TTlevel - sNum +1,  TTlevel ,  theOriginalID2, numGensFromWikiTreeID2toCousin );

									if (theDNAconfType == 'Tri'){								
										thisCitationX = thisCitationX.replace(theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + "\n** " + theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~";
									} else {
										thisCitationX = thisCitationX.replace(credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + credit4DNAconfAppPreamble + versionNum + " - ~~~~"; // move signature to end of line
									}

									theInfo += "<br><textarea id=ExtraCitationTT" + nextChildNum + " rows=9 cols=80>" + thisCitation + "</textarea>";
									theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationTT" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

									theInfoX += "<br><textarea id=ExtraCitationXTT" + nextChildNum + " rows=9 cols=80>" + thisCitationX + "</textarea>";
									theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationXTT" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

								// }
							// }
							
							// childsAhnNum = nextChildNum ;	

						}
					}
				}
			}
			// alert(6228);


		

			let firstYayTesterFoundPast2 = false;
			for (var testerNum = 3; testerNum < ahnenIndexArrays.length; testerNum++) {
			if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == true) {
				// alert(5411);
				theAncsAhnNum4TT = theAncsAhnNums[testerNum]; //theAncsAhnNum3;
				theTestTakerID3 = 'TestTaker' + testerNum;
				theTestPerson3 = personArray[ ahnenIndexArrays[testerNum][1]];	
				
				childsAhnNum = Math.floor(theAncsAhnNum4TT / 2);	
				thePerson = personArray[ahnenIndexArrays[testerNum][childsAhnNum]];
				let theChild = personArray[ahnenIndexArrays[testerNum][Math.floor(childsAhnNum/2)]];

				if ((theDNAconfType == 'x' || theDNAconfType == 'Tri') && isCouple) {
					var ConfirmedConfidentStmt = 'confident';
				} else {
					var ConfirmedConfidentStmt = 'confirmed with DNA';
				}

				thePersonFirstName = theNextBestFirstName(thePerson);
				thePersonLastName = theBestLastName( thePerson);

				let theMarkedStarter = "";
				theMarkedVerifiedCheckbox = theMVCheckboxMarked ; //"<div align=right>Marked. Citation added. DONE !</div>";

				console.log("SETUP for VERIFY / MARK N: ", theChild.FirstName, theChild.DataStatus.Father, theChild.DataStatus.Mother, isCouple, thePerson.FirstName, childsAhnNum);

				if (childsAhnNum % 2 == 0 && theChild.DataStatus.Father == 30) {
					theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
					theMarkedStarter = "Verify / ";
				} else if (childsAhnNum % 2 == 1 && theChild.DataStatus.Mother == 30) {
					theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
					theMarkedStarter = "Verify / ";
				}


				if (assemble4Tester1OK == false && assemble4Tester2OK == false && firstYayTesterFoundPast2 == false) {
					// DO NOTHING about the TOP LEVEL match with the MRCA ... because that will already be covered by the initial assembleCitationN call at the top
					// does NOT need to be repeated in the optional info box underneath
					firstYayTesterFoundPast2 = true;

				} else {

					theSectionHeader = "<H5 style='background-color:lime;'>Do Confirmations from DNA Tester " + theNextBestFirstNameLastName(ahnenIndexArrays[testerNum][1])+ " to MRCA" + (isCouple?"s ":" ") +  TheCommonAncestorNameSimple + "</H5>";
					theInfo += theSectionHeader;
					theInfoX += theSectionHeader;

					theInfo += "<P id=theInfoParaTT" + testerNum + "Ahn" + childsAhnNum + " bgClr=" + bkgdClrs[ ((numOfExtras + 1) % bkgdClrs.length ) ] + " style='background-color:" + bkgdClrs[ ((numOfExtras + 1) % bkgdClrs.length ) ] + ";'>" + (numOfExtras + 1) + "<BR/><B>DNA Confirmation <u>from</u> "  + thePersonFirstName.toUpperCase() + " " + thePerson.LastNameAtBirth.toUpperCase() + " <u>for</u><BR><span style='font-size: 20;'> " + TheCommonAncestorNameSimpleUC + "</span></B><BR>";
					theInfo += "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  thePerson.Name.replace(" ","_").replace(" ","_") +">" + theNextBestFirstName(thePerson) + " " + thePersonLastName + "</A></span> (" + thePerson.Name + ")<BR>to "  + TheCommonAncestorNameSimpleWithIDs + "<BR>as <b>" + ConfirmedConfidentStmt + "</b> <br>";

					theInfoX += "<P id=theInfoXParaTT" + testerNum + "Ahn" + childsAhnNum + " bgClr=" + bkgdClrs[ ((numOfExtras + 1) % bkgdClrs.length ) ] + " style='background-color:" + bkgdClrs[ ((numOfExtras + 1) % bkgdClrs.length ) ] + ";'>" + (numOfExtras + 1) + "<BR/><B>DNA Confirmation <u>from</u> "  + thePersonFirstName.toUpperCase() + " " + thePerson.LastNameAtBirth.toUpperCase() + " <u>for</u><BR><span style='font-size: 20;'> " + TheCommonAncestorNameSimpleUC + "</span></B><BR>";
					theInfoX += "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  thePerson.Name.replace(" ","_").replace(" ","_") +">" + theNextBestFirstName(thePerson) + " " + thePersonLastName + "</A></span> (" + thePerson.Name + ")<BR>to "  + TheCommonAncestorNameSimpleWithIDs + "<BR>as <b>" + ConfirmedConfidentStmt + "</b> <br>";

					if (isCouple) {
						if (theDNAconfType == 'x' || theDNAconfType == 'Tri') {
							theInfo += "<img src='images/FatherConfidentDNAradiobtn.png'>";
							theInfo += "<img src='images/MotherConfidentDNAradiobtn.png'>";
							theInfoX += "<img src='images/FatherConfidentDNAradiobtn.png'>";
							theInfoX += "<img src='images/MotherConfidentDNAradiobtn.png'>";

						} else {
							theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
							theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
							theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
							theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";

						}
					} else if (/*childsAhnNum % 2 == 0 || (childsAhnNum == 1 && */theAncestor.Gender == 'Male') {
						theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
						theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
					} else {
						theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
						theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
					}

					
					var thisCitation = assembleCitationN(testerNum , theAncestor, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction, isCouple);				
					
					if (isCouple) {
						// thisCitation += "\n\n" + assembleCitation3(theAncestor2, DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction);					
					}

					theMarkedVerifiedCheckbox = theMarkedVerifiedCheckbox.replace("123", "TT" + testerNum + "Ahn" + childsAhnNum);
					
					theInfo += "<br><textarea id=ExtraCitationTT" + testerNum + "Ahn" + childsAhnNum + " rows=9 cols=80>" + thisCitation + "</textarea>";
					theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationTT" + testerNum + "Ahn" + childsAhnNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

					theInfoX += "<br><textarea id=ExtraCitationXTT" + testerNum + "Ahn" + childsAhnNum + " rows=9 cols=80>" + thisCitation + "</textarea>";
					theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationXTT" + testerNum + "Ahn" + childsAhnNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

					numOfExtras++;
					additionalNames += "<BR>" + TheCommonAncestorNameSimple + " from " + thePersonFirstName + " " + theBestLastName( thePerson)  ;
				}

				while (childsAhnNum > 1 ) {
					
					numOfExtras++;
					var nextChildNum = Math.floor(childsAhnNum / 2);	
					// alert ("Third nextChildNum: " + nextChildNum);
					if (ahnenIndexArrays[testerNum][childsAhnNum] && ahnenIndexArrays[testerNum][nextChildNum]) {
						
						if (personArray[ahnenIndexArrays[testerNum][childsAhnNum]] && personArray[ahnenIndexArrays[testerNum][nextChildNum]] && personArray[ahnenIndexArrays[testerNum][nextChildNum]].Name.indexOf("Priv8") == -1) {
							
							thePerson = personArray[ahnenIndexArrays[testerNum][childsAhnNum]];
							theChild = personArray[ahnenIndexArrays[testerNum][nextChildNum]];
							thePersonFirstName = theNextBestFirstName(thePerson);
							thePersonLastName = theBestLastName( thePerson);
							theChildFirstName = theNextBestFirstName(theChild);

							console.log("PRIV 8 CHECK: ", thePerson.Name, theChild.Name, thePerson.Name.indexOf("Priv8"), theChild.Name.indexOf("Priv8"));

							if (additionalNames > '') { additionalNames += " <BR> ";}
							additionalNames += thePersonFirstName + " " + thePerson.LastNameAtBirth;

							let theMarkedStarter = "";
							theMarkedVerifiedCheckbox = theMVCheckboxMarked ; //"<div align=right>Marked. Citation added. DONE !</div>";

							console.log("SETUP for VERIFY / MARK N: ", theChild.FirstName, theChild.DataStatus.Father, theChild.DataStatus.Mother, isCouple, thePerson.FirstName, childsAhnNum);

							if (childsAhnNum % 2 == 0 && theChild.DataStatus.Father == 30) {
								theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
								theMarkedStarter = "Verify / ";
							} else if (childsAhnNum % 2 == 1 && theChild.DataStatus.Mother == 30) {
								theMarkedVerifiedCheckbox = theMVCheckboxVerified ;
								theMarkedStarter = "Verify / ";
							}
							
							theInfo += "<P id=theInfoParaTT" + testerNum + "Ahn" + nextChildNum + " bgClr=" + bkgdClrs[ ((numOfExtras ) % bkgdClrs.length ) ] + " style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + ";'>" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePersonLastName.toUpperCase() + "</span></B><BR>";
							theInfo += "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>"

							theInfoX += "<P id=theInfoXParaTT" + testerNum + "Ahn" + nextChildNum + " bgClr=" + bkgdClrs[ ((numOfExtras ) % bkgdClrs.length ) ] + " style='background-color:" + bkgdClrs[ (numOfExtras % bkgdClrs.length ) ] + ";'>" + numOfExtras + "<BR/><B>DNA Confirmation for <BR><span style='font-size: 20;'>"  + thePersonFirstName.toUpperCase() + " " + thePersonLastName.toUpperCase() + "</span></B><BR>";
							theInfoX += "Mark the relationship<BR>from <span style='font-size: 16;'><A target=_blank href=https://www.wikitree.com/wiki/" +  theChild.Name.replace(" ","_").replace(" ","_") +">" + theChildFirstName + " " + theChild.LastNameAtBirth + "</A></span> (" + theChild.Name + ")<BR>to "  + thePersonFirstName + " " + thePerson.LastNameAtBirth + " (" + thePerson.Name + ")<BR>as <b>confirmed with DNA</b> <br>"
							
							if (childsAhnNum % 2 == 0) {
								theInfo += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
								theInfoX += "<img src='images/FatherConfirmedDNAradiobtn.png'>";
							} else {
								theInfo += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
								theInfoX += "<img src='images/MotherConfirmedDNAradiobtn.png'>";
							}
							
							var thisCitation = assembleCitationN(testerNum , thePerson,DNAcompany,matchAmount,matchPerCent,NumSegments, relationshipPrediction);
							
							var thisCitationX = thisCitation + relationshipBetween4inLineage( Person3FullName4Citation, Person3FirstName4Citation, testTaker3Gender , theChild, thePerson, theAncestor, childsAhnNum, theAncsAhnNum4TT, theOriginalID3, numGensFromWikiTreeID3toCousin);
							if (theDNAconfType == 'Tri'){								
								thisCitationX = thisCitationX.replace(theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + "\n** " + theEndProviso + credit4DNAconfAppPreamble + versionNum + " - ~~~~";
							} else {
								thisCitationX = thisCitationX.replace(credit4DNAconfAppPreamble + versionNum + " - ~~~~","") + credit4DNAconfAppPreamble + versionNum + " - ~~~~"; // move signature to end of the line
							}
							
							theMarkedVerifiedCheckbox = theMarkedVerifiedCheckbox.replace("123", "TT" + testerNum + "Ahn" + nextChildNum);

							theInfo += "<br><textarea id=ExtraCitationTT" + testerNum + "Ahn" + nextChildNum + " rows=9 cols=80>" + thisCitation + "</textarea>";
							theInfo += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationTT" + testerNum + "Ahn" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

							theInfoX += "<br><textarea id=ExtraCitationXTT" + testerNum + "Ahn" + nextChildNum + " rows=9 cols=80>" + thisCitationX + "</textarea>";
							theInfoX += "<input class='button' type='submit' value='COPY' onclick=\"copyToClipboard('ExtraCitationXTT" + testerNum + "Ahn" + nextChildNum + "');\"></P>"+theMarkedVerifiedCheckbox+"<HR>";

						}
					}

					childsAhnNum = nextChildNum ;	

				}
				
			} // end if ahnenIndexArrays[testerNum].length > 0
			} // end for numTester = 4 ... N
			
			
			// alert("6578 - done the hard work");
			document.getElementById('ExtraInfo').innerHTML = theInfo;
			document.getElementById('additionalNames').innerHTML = additionalNames;

			if (numOfExtras == 0) {
				document.getElementById('BonusSection').style.display = 'none';
			} else {
				document.getElementById('BonusSection').style.display = 'block';
			}


			if (numOfExtras > 1) {
				document.getElementById('DIV7addS').textContent = 's';
			} else {
				document.getElementById('DIV7addS').textContent = '';
			}

			BonusInfo = theInfo;
			BonusInfoX = theInfoX;

			// alert("5717 - DONE DONE extras !");
		}

		function theBestFirstName( thePerson ) {
			if (thePerson) {
				if (thePerson.FirstName == undefined) {
					if (thePerson.RealName == undefined) {
						return "Private";
					} else  {
						return thePerson.RealName ;
					}
				}  else {
					return thePerson.FirstName;
				}
			} else {
				return "Unknown";
			}
		}

		function theNextBestFirstName( thePerson ) {
			if (thePerson) {
				if (thePerson.RealName == undefined) {
					if (thePerson.FirstName == undefined) {
						return "Private";
					} else  {
						return thePerson.FirstName ;
					}
				}  else {
					return thePerson.RealName;
				}
			} else {
				return "Unknown";
			}
		}

		function theBestLastName( thePerson ) {
			if (thePerson) {
				if (thePerson.LastNameAtBirth == undefined) {
					if (thePerson.LastNameCurrent == undefined) {
						if (theBestFirstName(thePerson) == 'Private') {
							return "";
						} else {
							return "Private";
						}
					} else  {
						return thePerson.LastNameCurrent ;
					}
				}  else {
					return thePerson.LastNameAtBirth;
				}
			} else {
				if (theBestFirstName(thePerson) == 'Unknown') {
					return "Person";
				} else {
					return "Unknown";
				}

			}
		}

		// return Last
		function theLastLastName( thePerson ) {
			if (thePerson) {
				if (thePerson.LastNameCurrent == undefined) {
					if (thePerson.LastNameAtBirth == undefined) {
						if (theBestFirstName(thePerson) == 'Private') {
							return "";
						} else {
							return "Private";
						}
					} else  {
						return thePerson.LastNameAtBirth ;
					}
				}  else {
					return thePerson.LastNameCurrent;
				}
			} else {
				 
					return "Unknown";
			
			}
		}

		function theNextBestFirstNameLastName( theID , makeWikiLink = false ) {
			let theFullName =  theNextBestFirstName(personArray[theID]) + " " + theBestLastName( personArray[theID] );
			if (makeWikiLink == true ) {
				if (personArray[theID].Name) {
					return "[[" + personArray[theID].Name + "|" + theFullName + "]]";
				} else {
					return theFullName;
				}

			} else {
				return theFullName;
			}
		}

		function theLastBestFirstNameLastName( theID , makeWikiLink = false ) {
			let theFullName =  theNextBestFirstName(personArray[theID]) + " " + theLastLastName( personArray[theID] );
			if (makeWikiLink == true ) {
				if (personArray[theID].Name) {
					return "[[" + personArray[theID].Name + "|" + theFullName + "]]";
				} else {
					return theFullName;
				}

			} else {
				return theFullName;
			}
		}


		function theNextBestLastName( thePerson ) {
			if (thePerson) {
				if (thePerson.LastNameCurrent == undefined) {
					if (thePerson.LastNameAtBirth == undefined) {
						if (theBestFirstName(thePerson) == 'Private') {
							return "";
						} else {
							return "Private";
						}
					} else  {
						return thePerson.LastNameAtBirth ;
					}
				}  else {
					return thePerson.LastNameCurrent;
				}
			} else {
				return "Unknown";
			}
		}


		function relationshipBetween4inLineage( testTakerFullName, testTakerFirstName, testTakerGender, theChild, thePerson, theAncestor, childsAhnNum, AncsAhnNum, testTakerAlias = '', numGensFromWikiTree = 0) {
			// alert( theProfilePerson + " " + theChild + " " + thePerson + " " + theAncestor + " " + childsAhnNum + " " + AncsAhnNum);
				var theRel = Math.floor(Math.log2(childsAhnNum));
				var theRel2 = Math.floor(Math.log2(AncsAhnNum));
				var theStmt = " ";

				var sonOrDaughter = SonOrDaughter2(testTakerGender);
				 

				if (1.0*theRel + 1.0*numGensFromWikiTree == 1) {
					theStmt +=  /*theNextBestFirstName( theProfilePerson)*/  testTakerFirstName +  " is the " + SonOrDaughter(theChild) + " of " + theNextBestFirstName(thePerson) +  " " + theBestLastName(thePerson) + " who is the " + howGreatIsThisOrNot(theRel2 - theRel + 1) + SonOrDaughter(thePerson) ;
				} else {
					theStmt += "DNA test taker " + testTakerFullName + " is the " + howGreatIsThisOrNot(1.0*theRel + 1.0*numGensFromWikiTree) + sonOrDaughter + " of " + theNextBestFirstName(theChild) +  " " + theBestLastName(theChild) + ", the " + SonOrDaughter(theChild) + " of " + theNextBestFirstName(thePerson) +  " " + theBestLastName(thePerson) + ", the " + howGreatIsThisOrNot(theRel2 - theRel + 1) + SonOrDaughter(thePerson) ;
				}
				if (isCouple) {
					theStmt += " of the common ancestors " + theNextBestFirstName(theAncestor) +  " " + theBestLastName(theAncestor) + " & "  + theNextBestFirstName(theAncestor2) +  " " + theBestLastName(theAncestor2);
				} else 	if (isDirectAncestor) {
					theStmt += " of " + theNextBestFirstName(theAncestor) +  " " + theBestLastName(theAncestor)  ;
				} else {
					theStmt +=  " of the common ancestor " + theNextBestFirstName(theAncestor) +  " " + theBestLastName(theAncestor);
				}
				theStmt += ".";
				return theStmt;
		}

		function relationshipBetween4inLineage4SimpleAliases( testTakerFullName, testTakerFirstName, testTakerGender, theChild, thePerson, theAncestor, RelNum1 , RelNum2 , testTakerAlias = '', numGensFromWikiTree = 0) {
			// alert( theProfilePerson + " " + theChild + " " + thePerson + " " + theAncestor + " " + childsAhnNum + " " + AncsAhnNum);
				var theRel = RelNum1; // Math.floor(Math.log2(childsAhnNum));
				var theRel2 = RelNum2;// Math.floor(Math.log2(AncsAhnNum));
				var theStmt = " ";

				var sonOrDaughter = SonOrDaughter2(testTakerGender);
				 

				if (1.0*theRel + 1.0*numGensFromWikiTree == 1) {
					theStmt +=  /*theNextBestFirstName( theProfilePerson)*/  testTakerFirstName +  " is the " + SonOrDaughter(theChild) + " of " + theNextBestFirstName(thePerson) +  " " + theBestLastName(thePerson) + " who is the " + howGreatIsThisOrNot(theRel2 - theRel + 1) + SonOrDaughter(thePerson) ;
				} else {
					theStmt += "DNA test taker " + testTakerFullName + " is the " + howGreatIsThisOrNot(1.0*theRel + 1.0*numGensFromWikiTree) + sonOrDaughter + " of " + theNextBestFirstName(theChild) +  " " + theBestLastName(theChild) + ", the " + SonOrDaughter(theChild) + " of " + theNextBestFirstName(thePerson) +  " " + theBestLastName(thePerson) + ", the " + howGreatIsThisOrNot(theRel2 - theRel + 1) + SonOrDaughter(thePerson) ;
				}
				if (isCouple) {
					theStmt += " of the common ancestors " + theNextBestFirstName(theAncestor) +  " " + theBestLastName(theAncestor) + " & "  + theNextBestFirstName(theAncestor2) +  " " + theBestLastName(theAncestor2);
				} else 	if (isDirectAncestor) {
					theStmt += " of " + theNextBestFirstName(theAncestor) +  " " + theBestLastName(theAncestor)  ;
				} else {
					theStmt +=  " of the common ancestor " + theNextBestFirstName(theAncestor) +  " " + theBestLastName(theAncestor);
				}
				theStmt += ".";
				return theStmt;
		}


		function swapBonusInfo( which = 2 ) {
			// alert("Trying to Swap the BONUS info for " + which);
			if (which == 0) {
				document.getElementById('ExtraInfo').innerHTML = BonusInfo;
			} else {
				document.getElementById('ExtraInfo').innerHTML = BonusInfoX;
			}
		}


		function showHideExtraInfo() {
			isChecked = document.getElementById("showExtras").checked;
			if (isChecked) {
				newStatus = "block";
			} else {
				newStatus = "none";
			}
			
			document.getElementById('ExtraInfo').style.display = newStatus;
		}

		function showHideResearchNotes() {
			let theActualNotes = document.getElementById("DNAresearchNotesText").innerText;
			

			isChecked = document.getElementById("showResearchNotes").checked;
			if (isChecked) {
				newStatus = "block";
				if (theActualNotes == "") {
					document.getElementById("DNAresearchNotesText").innerHTML = assembleResearchNotes();
				}
			} else {
				newStatus = "none";
			}
			
			document.getElementById('ResearchNotes').style.display = newStatus;
		}
		function HideResearchNotes() {
			document.getElementById("showResearchNotes").checked = false;
			document.getElementById('ResearchNotes').style.display = "none";			
		}



		var theFirstYayTesterNum = -1;
		var theLastYayTesterNum = -1;

		let theNumValidTesters = 0;
		let theValidTesterNames = "";
		let theGenNumCollection = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]];
		let nonGenNumCollection = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]];
		let avgMRCAgenCompleteness = 0;
		let uniqueChildren = [];
		let uniqueGrandChildren = [];

		let MRCA2TestersRelationshipFNamesOnly = "";
		let MRCA2TestersRelationshipFullNames = "";

		function setupAllYayTesterVariables(){
							
			theFirstYayTesterNum = -1;
			theLastYayTesterNum = -1;
			for (var i = 1; i < ahnenIndexArrays.length; i++) {
				if (yayTestersList[i] == true) {
					if (theFirstYayTesterNum == -1){
						theFirstYayTesterNum = i;
					} else {
						theLastYayTesterNum = i;
					}
				}
			}

			theNumValidTesters = 0;
			theValidTesterNames = "";
			theGenNumCollection = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]];
			nonGenNumCollection = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]];
			avgMRCAgenCompleteness = 0;
			uniqueChildren = [];
			uniqueGrandChildren = [];

			for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
				if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == true) {
					theNumValidTesters++;
					theValidTesterNames += theNextBestFirstNameLastName(ahnenIndexArrays[testerNum][1], true) + ", ";

					let theGenNum = Math.floor(Math.log2(theAncsAhnNums[testerNum]));
					theGenNumCollection[theGenNum].push(testerNum);
					console.log("theTesterFullnessInfo",testerNum,theTesterFullnessInfo[testerNum]);
					avgMRCAgenCompleteness += theTesterFullnessInfo[testerNum].MRCAgenCompleteness;

					let childOfMRCA = Math.floor(theAncsAhnNums[testerNum] / 2);
					let grandchildOfMRCA = ahnenIndexArrays[testerNum][Math.floor(childOfMRCA / 2)];
					childOfMRCA = ahnenIndexArrays[testerNum][childOfMRCA];

					if (uniqueChildren.length == 0 || uniqueChildren.indexOf(childOfMRCA) == -1) {
						uniqueChildren.push(childOfMRCA);
					}
					if (uniqueGrandChildren.length == 0 || uniqueGrandChildren.indexOf(grandchildOfMRCA) == -1) {
						uniqueGrandChildren.push(grandchildOfMRCA);
					}

				} else if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == false) {
					
					let theGenNum = Math.floor(Math.log2(theAncsAhnNums[testerNum]));
					nonGenNumCollection[theGenNum].push(testerNum);
					
				}
			}
			if (theNumValidTesters > 0) { 
				avgMRCAgenCompleteness /= theNumValidTesters;
			}

			let foundOne = false;
			MRCA2TestersRelationshipFNamesOnly = "";
			MRCA2TestersRelationshipFullNames = "";

			for (var i = 1; i < 10; i++) {
				if (theGenNumCollection[i].length > 0) {
					if (foundOne == true) {
						MRCA2TestersRelationshipFNamesOnly += ", and the ";
						MRCA2TestersRelationshipFullNames += ", and the ";
					}
					if (i > 3) {
						MRCA2TestersRelationshipFNamesOnly += (i-2) + "x great grandparent";
						MRCA2TestersRelationshipFullNames += (i-2) + "x great grandparent";
					} else if (i == 3) {
						MRCA2TestersRelationshipFNamesOnly +=  "great grandparent";
						MRCA2TestersRelationshipFullNames +=  "great grandparent";
					} else if (i == 2) {
						MRCA2TestersRelationshipFNamesOnly +=  "grandparent";
						MRCA2TestersRelationshipFullNames +=  "grandparent";
					} else if (i == 1) {
						MRCA2TestersRelationshipFNamesOnly +=  "parent";
						MRCA2TestersRelationshipFullNames +=  "parent";
					}
					if (isCouple) {
						MRCA2TestersRelationshipFNamesOnly+= "s";
						MRCA2TestersRelationshipFullNames+= "s";
					}
					MRCA2TestersRelationshipFNamesOnly+= " of ";
					MRCA2TestersRelationshipFullNames+= " of ";

					for (var j = 0; j < theGenNumCollection[i].length; j++) {
						if (j>0) {
							MRCA2TestersRelationshipFNamesOnly += ", ";
							MRCA2TestersRelationshipFullNames += ", ";
							if (j == theGenNumCollection[i].length - 1) {
								MRCA2TestersRelationshipFNamesOnly += "and ";
								MRCA2TestersRelationshipFullNames += "and ";
							}

						}
						MRCA2TestersRelationshipFNamesOnly+=  theNextBestFirstName(personArray[ ahnenIndexArrays[ theGenNumCollection[i][j] ][1] ]) ;
						MRCA2TestersRelationshipFullNames+=  theNextBestFirstNameLastName (  ahnenIndexArrays[ theGenNumCollection[i][j] ][1] ) ;
					}
					foundOne = true;
				}
			}

		}

		function assembleTheEndProviso() {
			let amountCM = Math.round (document.getElementById("TriMatchAmount").value );

			let theAncestorID =  ahnenIndexArrays[1][ theAncsAhnNum];
			let theAncestorID2 =  0;
			if (isCouple) {
				theAncestorID2 =  ahnenIndexArrays[1][ theAncsAhnNum + 1];				
			}

			theEndProviso = "As of the date of this confirmation statement, the DNA testers collectively have documented ";
			theEndProviso+=  Math.round(10* avgMRCAgenCompleteness)/10 + " % of their ancestors on WikiTree, up to the generation of the MRCA" + (isCouple == true ? "s":"") + ", ";

			theEndProviso+= theNextBestFirstName(personArray[  theAncestorID ]) ;
			if (isCouple == true ) {
				theEndProviso+= " and " + theNextBestFirstName(personArray[  theAncestorID2 ]) ;
			}
			theEndProviso += "; individually, they have documented ";
			for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
				if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == true) {
					// theEndProviso+= theTesterFullnessInfo[testerNum].MRCAgenCompleteness + "% ("+   theNextBestFirstName(personArray[  ahnenIndexArrays[testerNum][1] ]) + "), ";
					let thisTesterName = theNextBestFirstName(personArray[  ahnenIndexArrays[testerNum][1] ]);
					let testerNumPerson = personArray[ ahnenIndexArrays[testerNum][1] ];

					if (testerNumPerson.Name.indexOf("Priv8") > -1) {
						thisTesterName = document.getElementById("CustomCiteName" + testerNum + "Cust").value;
					
					} else if (document.getElementById("RadioCiteName"+ testerNum + "Cust").checked){
						thisTesterName = document.getElementById("CustomCiteName" + testerNum + "Cust").value;
					}
					
					theEndProviso+= theTesterFullnessInfo[testerNum].MRCAgenCompleteness + "% ("+   thisTesterName + "), ";
				}
			}
			theEndProviso += "respectively.";
			theEndProviso += "\n** There are no other common ancestors within " + (doExtendedAncestorSearch4Triangulation ? "fourteen":"ten") + " generations of this group of DNA test takers on WikiTree, at this point in time.";

			if (amountCM < 12) {
				theEndProviso += "\n** NOTE: Segments of " + amountCM + " cM have a false positive rate of " + falsePositiveRateList[amountCM] + "%, according to a Family Tree DNA " + (showWhitePaperLink == true?"[https://blog.familytreedna.com/wp-content/uploads/2021/08/Family_Finder_Matching_WhitePaper.pdf ":"") + "white paper" + (showWhitePaperLink == true?"]":"") + " from 2021."
			}

		}

		function assembleResearchNotes() {
			let theNote = "A DNA Confirmation via Triangulation was made between ";
			let theAncestorID =  ahnenIndexArrays[1][ theAncsAhnNum];
			let thisEndProviso = theEndProviso.substr(0, theEndProviso.indexOf("respectively")); // "As of the date of this confirmation statement, the DNA testers collectively have documented ";

			theValidTesterNames = "";

			for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
				if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == true) {
					let testerNumPerson = personArray[ ahnenIndexArrays[testerNum][1] ];
					let testerNumNameWithLink = theNextBestFirstNameLastName( ahnenIndexArrays[testerNum][1] , true) ;

					if (testerNumPerson.Name.indexOf("Priv8") > -1) {
						testerNumNameWithLink = testerNumPerson.FirstName + ", ";
						if (testerNumPerson.numGensAway == 1) {
							testerNumNameWithLink += "child of ";	
						} else if (testerNumPerson.numGensAway == 2) {
							testerNumNameWithLink += "grandchild of ";	
						} else if (testerNumPerson.numGensAway > 2) {
							testerNumNameWithLink +=  "great-".repeat(testerNumPerson.numGensAway - 2) +"grandchild of ";	
						}

						testerNumNameWithLink += theNextBestFirstNameLastName( testerNumPerson.closestAnc1ID , true) ;
						theValidTesterNames += testerNumNameWithLink + ", ";

					} else {
						theValidTesterNames += theNextBestFirstNameLastName(ahnenIndexArrays[testerNum][1], true) + ", ";
					}

				} 
			}

			let numNonValids = ahnenIndexArrays.length - 1 - theNumValidTesters;
			

			theNote += theNumValidTesters + " DNA test takers who share a common " + (isCouple == true ? "ancestral couple":"ancestor");
			theNote += ", " + theNextBestFirstNameLastName(theAncestorID, true);
			let theAncestorID2 =  0;
			if (isCouple) {
				theAncestorID2 =  ahnenIndexArrays[1][ theAncsAhnNum + 1];
				theNote += " & " + theNextBestFirstNameLastName(theAncestorID2, true) + ".";
			}
			theNote +=  " The DNA test takers are ";
			theNote += theValidTesterNames; //" INSERT LIST HERE ";

			// ChromoNum / TriMatchAmount

			theNote += "and they all share a common segment of DNA that is " + document.getElementById("TriMatchAmount").value  + " centiMorgans (cM) on chromosome "+ document.getElementById("ChromoNum").value +".";

			theNote += " The common " + (isCouple == true ? "ancestral couple are the ":"ancestor is the ");

			theNote += MRCA2TestersRelationshipFNamesOnly;

			theNote+= ".";


			if (theNumValidTesters == uniqueChildren.length) {
				theNote+= " The " + theNumValidTesters + " DNA testers descend from "  + uniqueChildren.length + " different children of the common ancestor" + (isCouple == true ? "s.":".");
			} else {
				theNote+= " The " + theNumValidTesters + " DNA testers descend from "  + uniqueChildren.length + " different children, " + uniqueGrandChildren.length + " unique grandchildren of the common ancestor" + (isCouple == true ? "s.":".");
			}

			// theEndProviso+=  Math.round(10* avgMRCAgenCompleteness)/10 + " % of their ancestors on WikiTree, up to the generation of the MRCA" + (isCouple == true ? "s":"") + ", ";

			// theEndProviso+= theNextBestFirstName(personArray[  theAncestorID ]) ;
			// if (isCouple == true ) {
			// 	theEndProviso+= " and " + theNextBestFirstName(personArray[  theAncestorID2 ]) ;
			// }
			// theEndProviso += "; individually, they have ";
			// for (var testerNum = 1; testerNum < ahnenIndexArrays.length; testerNum++) {
			// 	if (ahnenIndexArrays[testerNum].length > 0 && yayTestersList[testerNum] == true) {
			// 		theEndProviso+= theTesterFullnessInfo[testerNum].MRCAgenCompleteness + "%, ";
			// 	}
			// }
			// theEndProviso += "respectively.";
			
			theNote+= thisEndProviso;

			if (numNonValids > 0) {
				theNote += "\n\nThere " + (numNonValids == 1 ? "is ":"are ") + numNonValids + " additional DNA tester" + (numNonValids > 1?"s":"") + " who " + (numNonValids == 1 ? "is a descendant":"are descendants") + " of the common ancestor" + (isCouple == true ? "s":"") + " and who share" + (numNonValids == 1 ? "s":"") + " the common segment of DNA. "
			
				// theNote += " BARNEY and BETTY are 3x great grandchildren";

				foundOne = false;
				let foundTooFar = false;
				for (var i = 1; i < 10; i++) {
					if (nonGenNumCollection[i].length > 0) {
						if (foundOne == true) {
							theNote += ", and ";
						}
						if (i  - 2 > 4 ) {
							foundTooFar = true;
						}

						for (var j = 0; j < nonGenNumCollection[i].length; j++) {
							if (j>0) {
								theNote += ", ";
								if (j == nonGenNumCollection[i].length - 1) {
									theNote += "and ";
								}

							}
							theNote+=  theNextBestFirstNameLastName(  ahnenIndexArrays[ nonGenNumCollection[i][j] ][1] , true) ;
						}


						if (nonGenNumCollection[i].length == 1) {
							theNote += " is a ";
						} else {
							theNote += " are ";
						}

						
						if (i > 3) {
							theNote += (i-2) + "x great grandchild";
						} else if (i == 3) {
							theNote +=  "great grandchild";
						} else if (i == 2) {
							theNote +=  "grandchild";
						} else if (i == 1) {
							theNote +=  "child";
						}
						if (nonGenNumCollection[i].length > 1) {
							theNote+= "ren";
						}
						

						
						foundOne = true;
					}

				}


				theNote += " of the common ancestor" + (isCouple == true ? "s":"") + ", but " + (numNonValids == 1 ? "descends from ":"they descend from ") ; 
				theNote+= theNextBestFirstName(personArray[  theAncestorID ]) ;
				if (isCouple == true ) {
					theNote+= " and " + theNextBestFirstName(personArray[  theAncestorID2 ]) ;
				}
				theNote += " in multiple ways";
				if (foundTooFar == true) {
					theNote += " and/or are too distantly related for a reliable autosomal match"
				}
				theNote += ", so it can't be determined which path to follow to confirm the origin of the common DNA segment that is shared with the others.";
			}

			let theNoOtherGensNote = 
			 theNote += "\n\nThere are no other common ancestors within " + (doExtendedAncestorSearch4Triangulation ? "fourteen":"ten") + " generations of this group of DNA test takers, based on their ancestors on WikiTree, as of the date of this note.  Should additional branches be added to their family trees, a re-examination of common ancestors would be prudent.";
			 // theEndProviso += "\n** There are no other common ancestors within " + (doExtendedAncestorSearch4Triangulation ? "fourteen":"ten") + " generations of this group of DNA test takers on WikiTree, at this point in time.";

			theNote += " According to the current WikiTree pedigree, all the DNA test takers listed above descend from the common ancestor/ancestral couple of ";
			theNote+= theNextBestFirstName(personArray[  theAncestorID ]) ;
			if (isCouple == true ) {
				theNote+= " and " + theNextBestFirstName(personArray[  theAncestorID2 ]) ;
			}
			theNote+= ". If the link between each child and parent in the relationship chain is genealogically sound, with sources, then this DNA evidence supports those relationship claims.";

			theNote += " ~~~~";

			// theTesterFullnessInfo lastFullGen MRCAgenCompleteness

			return theNote;
		}

		function setupOptionsForDNATestTaker (num, thePerson) {
			var PersonFullName4Citation = "";
			var PersonFirstName4Citation = "";
			// alert ("num, from2, from 3 = " + num +  "  , " + numGensFromWikiTreeID2toCousin + "  , " + numGensFromWikiTreeID3toCousin);
			if (num == 2 && numGensFromWikiTreeID2toCousin > 0) {
				// document.getElementById('CustomCiteName2Cust').value = theOriginalID2;
				document.getElementById('RadioCiteName2Cust').checked = true;
				document.getElementById('SpanCiteName' + num + 'New').style.display =  'none';
				document.getElementById('SpanCiteName' + num + 'Old').style.display =  'none';
				theAlias = document.getElementById('CustomCiteName2Cust').value;
				return [theAlias , theAlias];

				
			} else if (num == 3 && numGensFromWikiTreeID3toCousin > 0) {
				// document.getElementById('CustomCiteName3Cust').value = theOriginalID3;
				document.getElementById('RadioCiteName3Cust').checked = true;
				document.getElementById('SpanCiteName' + num + 'New').style.display =  'none';
				document.getElementById('SpanCiteName' + num + 'Old').style.display =  'none';
				theAlias = document.getElementById('CustomCiteName3Cust').value;
				return [theAlias , theAlias];
			}

			var theOldName = theNextBestFirstName(thePerson) + " " + theBestLastName(thePerson);
			var theNewName = theNextBestFirstName(thePerson) + " " + theBestLastName(thePerson);
			if ( theBestLastName(thePerson) == theBestLastName(thePerson) )  {
				var theSpaceBetween = " ";
			} else {
				var theSpaceBetween = " ("+ theBestLastName(thePerson) + ") " ;
			}
			var theNewNameShow = theNextBestFirstName( thePerson) + theSpaceBetween + theBestLastName(thePerson);

			document.getElementById('CustomCiteName' + num + 'New').textContent = theNewNameShow ;


			if (theOldName == theNewName) {
				document.getElementById('CustomCiteName' + num + 'Old').textContent =  '';
				document.getElementById('SpanCiteName' + num + 'Old').style.display =  'none';
			} else {
				document.getElementById('CustomCiteName' + num + 'Old').textContent = theOldName ;
				document.getElementById('SpanCiteName' + num + 'Old').style.display =  'revert';
			}
			// if (theBestLastName(thePerson) == theBestLastName(thePerson)) {
			// 	document.getElementById('SpanCiteName' + num + 'Combo').style.display =  'none';
			// } else {
			// 	document.getElementById('CustomCiteName' + num + 'Combo').textContent = theNextBestFirstName( thePerson) + "(" + theBestLastName(thePerson) + ")" + theBestLastName(thePerson); ;
			// 	document.getElementById('SpanCiteName' + num + 'Combo').style.display =  'revert';
			// }


			if (document.getElementById('RadioCiteName' + num + 'Old').checked) {
				// if UseWikiLink1 . checked .... DO STUFF HERE !!!!
				if (document.getElementById('UseWikiLink' + num).checked) {
					PersonFullName4Citation = "[[" + thePerson.Name.replace(" ","_").replace(" ","_") + "|" + theOldName + "]]" ;
				} else {
					PersonFullName4Citation = theOldName ;
				}

				PersonFirstName4Citation =  theNextBestFirstName( thePerson) ;
			} else if (document.getElementById('RadioCiteName' + num + 'New').checked) {
				if (document.getElementById('UseWikiLink' + num).checked) {
					PersonFullName4Citation = "[[" + thePerson.Name.replace(" ","_").replace(" ","_") + "|" +  theNewNameShow + "]]" ;
				} else {
					PersonFullName4Citation = theNewNameShow ;
				}

				PersonFirstName4Citation =  theNextBestFirstName( thePerson) ;
			} else if (document.getElementById('RadioCiteName' + num + 'Cust').checked) {
				if (document.getElementById('UseWikiLink' + num).checked) {
					PersonFullName4Citation = "[[" + thePerson.Name.replace(" ","_").replace(" ","_") + "|" +  document.getElementById('CustomCiteName' + num + 'Cust').value + "]]" ;
				} else {
					PersonFullName4Citation = document.getElementById('CustomCiteName' + num + 'Cust').value ;
				}
				 // PersonFullName4Citation = document.getElementById('CustomCiteName' + num + 'Cust').value;

				 PersonFirstName4Citation = document.getElementById('CustomCiteName' + num + 'Cust').value;
			} else  {
				 PersonFullName4Citation = "";
				 PersonFirstName4Citation = "";
			}
			return [PersonFullName4Citation, PersonFirstName4Citation];
		}



		function hideAllDIVs() {
			// alert(typeMode);
			allDIVs = ['TooDistant', 'FormDIV1ab', 'FormDIV1abc', 'FormDIV1abP1err','FormDIV1abP2err','FormDIV1abcP1err','FormDIV1abXP1err', 'FormDIV1abcXP1err', 'FormDIV1err' ,'FormDIV1abX', 'FormDIV1b','FormDIV1contacting','FormDIV1gender','FormDIV1YGenderWarning', 'FormDIV2', 'FormDIV3', 'FormDIV4TooFar4SimpleWarning','FormDIV4MultipleMRCAs' , 'FormDIV4','FormDIV4direct','FormDIV4abc','FormDIV1WarningDeathDates', 'FormDIV5', 'FormDIV6','FormDIV6MT','FormDIV6X','FormDIV6Y','FormDIV6TRI', 'FormDIV7' ];
			
			for (var i = 0; i < allDIVs.length; i++) {
				document.getElementById(allDIVs[i]).style.display = 'none';
			}		
			
		}

		function showNextButton(num) {
			if (document.getElementById('NextButton' + num) ) {document.getElementById('NextButton' + num).style.display = 'block';}
		}
			

		// function return2Step2() {
		// 	// alert ("Return to Step 2");
		// 	document.getElementById('FormDIV3').style.display = 'none';
		// 	document.getElementById('FormDIV2').style.display = 'block';
		// }
		 
		function return2Step(whichStep) {
			hideAllDIVs();
			hideMiniTree(true);

			if (whichStep == 1) {
				if (theDNAconfType == 'Tri') {
					whichForm = "FormDIV1abc";
				} else if (theDNAconfType == 'x') {
					whichForm = "FormDIV1abX";
				} else {
					whichForm = "FormDIV1ab";

				}
			} else if (whichStep == 2) {
				document.getElementById("relationship3").value = 'Choose relationship';
				document.getElementById("Div3CA2P2").textContent = '';
				whichForm = "FormDIV" + whichStep;	

			} else if (whichStep == 4) {
				if (theCurrentMatchesList.length > 1) {
					document.getElementById('FormDIV4MultipleMRCAs').style.display = "block";
				}
				if (theDNAconfType == '1') {
					if (isDirectAncestor) {
						whichForm = "FormDIV4direct";
					} else {
						whichForm = "FormDIV4";
					}

				} else {
					whichForm = "FormDIV4direct";
				}
			} else if (whichStep == 6) {
				if (theDNAconfType == '1') {
					whichForm = "FormDIV6";
				} else if (theDNAconfType == 'y') {
					whichForm = "FormDIV6Y";
				} else if (theDNAconfType == 'mt') {
					whichForm = "FormDIV6MT";
				} else if (theDNAconfType == 'x') {
					whichForm = "FormDIV6X";
				} else if (theDNAconfType == 'Tri') {
					whichForm = "FormDIV6TRI";
					
				} else {
					
					whichForm = "FormDIV6";
				}

			} else {

				whichForm = "FormDIV" + whichStep;				
			}
			

			document.getElementById( whichForm).style.display = 'block';
			document.getElementById('FormDIV1Warning').style.display = 'none';
			updateGoBackLinks(whichStep);
		}

		function updateGoBackLinks(whichStep) {
			var theHTML4GoTo = "<table align=center><tr>";
			// var GoToStepsHTML = Array();
			GoToStepsHTML = [ "<A onclick='resetApp(\"live\");'><u>Step 0</u><br>Menu</A>",  "<A onclick='return2Step(1);'><u>Step 1</u><br>Testers</A>",  " <A onclick='return2Step(2);'><u>Step 2</u><BR>MRCA</A>", " <A onclick='return2Step(3);'><u>Step 3</u><BR>How?</A>", " <A onclick='return2Step(4);'><u>Step 4</u><BR>Mini Tree</A>", " <A onclick='return2Step(5);'><u>Step 5</u><BR>Questions</A>", " <A onclick='return2Step(6);'><u>Step 6</u><BR>DNA info</A>"];

			if (whichStep > 0 && ((theTestPerson && theTestPerson != null) || (theDNAconfType != 1) )){
				 GoToStepsHTML[2] = "<font color=grey> <u>Step 2</u><BR>MRCA</font>" ;
				 GoToStepsHTML[3] = "<font color=grey> <u>Step 3</u><BR>How?</font>";
			}

			theHTML4GoTo += "<td align=center valign=middle style='padding:10px;'>" + "Go back to: "  + "</td>";
			for (var i = 0; i <= whichStep; i++) {
				theHTML4GoTo += "<td align=center valign=middle style='padding:10px;'>" + GoToStepsHTML[i] + "</td>";
			}
			theHTML4GoTo += "</tr></table>";
			document.getElementById('GoBackLinks').innerHTML = theHTML4GoTo;
			document.getElementById('ListOfNamesDIV').innerHTML = "";
		}
			
		 
		function updateDNApainterLink() {
			let roundMatchAmount = Math.round(1.0 * document.getElementById('matchAmount').value);
			// let DNApainterRelationship = "1C2R";

			let HTMLstatement = "View the stats for this match on <A target=_blank href='https://dnapainter.com/tools/sharedcmv4/" + roundMatchAmount + "/" + DNApainterRelationship + "'>DNApainter.com</A>.";

			if (roundMatchAmount > 0) {
				document.getElementById('DNApainterLink').innerHTML = HTMLstatement;
			} else  {
				document.getElementById('DNApainterLink').innerHTML = "";
			}

		}
		
		function fillInID4TT1spawn() {
			console.log("FUNCTION: fillInID4TT1spawn");
			document.getElementById("TT1isSelfPrimaryID").textContent = document.getElementById("TTaker1").value;
			document.getElementById("TT1isChildPrimaryID").textContent = document.getElementById("TTaker1").value;
			document.getElementById("TT1isGrandChildPrimaryID").textContent = document.getElementById("TTaker1").value;
		}

		function updateSNPtreeExplorerLink() {
			let hg1 = document.getElementById("YDNAhaploP1").value;
			let hg2 = document.getElementById("YDNAhaploP2").value;
			let SNPtreeExplorerLinkDIV = document.getElementById("SNPtreeExplorerLink");

			if (hg1 > "" || hg2 > "") {
				let theSNPhtml = "";
				// show LINK !
				
				if (hg1 > "") {
					theSNPhtml += "<a target=_blank href='" + SNP_Tracker_URLstarter + hg1 + "'>View "+ hg1 +" on SNP Tracker</A>";
				} 

				if (hg2 > "") {
					if (hg1 > "") { theSNPhtml += "<br/>";}
					theSNPhtml += "<a target=_blank href='" + SNP_Tracker_URLstarter + hg2 + "'>View "+ hg2 +" on SNP Tracker</A>";					
				}

				if (hg1 > "" && hg2 > ""){
					let values = hg1 + "," + hg2;
					theSNPhtml += "<br/><br/><a target=_blank href='" + SNP_Tree_Explorer_URLstarter + values + "'>View Haplogroups on SNP Tree Explorer</A>";
				} 

				theSNPhtml += "<BR/><I>courtesy of Rob Spencer, Tracking Back (<A target=_blank href=ScaledInnovation.com>scaledinnovation.com</a>)";
					

				SNPtreeExplorerLinkDIV.innerHTML = theSNPhtml; 
			} else {
				SNPtreeExplorerLinkDIV.innerHTML = "";
			}

		}

		function getYDNAmatchDetails () {
			let matchDetails = "";

			var YDNAcompany = document.getElementById('YDNAcompany').value;
			var genDistance = document.getElementById('genDistance').value;
			var numMarkers = document.getElementById('Markers').value;
			matchDetails =  "match at a Genetic Distance of " + genDistance + " on " + numMarkers + " markers";

			if (numMarkers == 838) {
				if (YDNAcompany == "mitoYDNA") {
					matchDetails =  "match with a YSTR+ Difference at " + genDistance ;
				} else {
					// assume FTDNA here
					matchDetails =  "match with " + genDistance + " Big Y STR Differences";
				}
			}

			return matchDetails;
		}


		function getHaploGroupStatement() {
			let hg1 = document.getElementById("YDNAhaploP1").value;
			let hg2 = document.getElementById("YDNAhaploP2").value;
			let showLink = showHaplogroupLink;
			// let SNPtreeExplorerLinkDIV = document.getElementById("SNPtreeExplorerLink");
			let theStatement = "";
			let hg1Link = hg1;
			let hg2Link = hg2;
			let Person1FirstName = Person1FirstName4Citation ;

			if (showLink) {
				hg1Link = "[" + SNP_Tracker_URLstarter + hg1 + " "+ hg1 +"]";
				hg2Link = "[" + SNP_Tracker_URLstarter + hg2 + " "+ hg2 +"]";
			}

			if (hg1 > "" || hg2 > "") {
				// show LINK !
				if (hg1 > "" && hg2 > ""){
					if (hg1 == hg2) {
						theStatement = "Both " + Person1FirstName + " and " + Person2FirstName + " belong to haplogroup " + hg1Link + ".";
					} else {
						theStatement = Person1FirstName + " and " + Person2FirstName + " belong to haplogroups " + hg1Link + " and " + hg2Link + ", respectively." ;
						if (showLink){
							let values = hg1 + "," + hg2;
							theStatement += " [" + SNP_Tree_Explorer_URLstarter + values + " (SNP Tree Explorer)]";
						}
					}
				} else if (hg1 > "") {
					theStatement = Person1FirstName +  " belongs to haplogroup " + hg1Link + "." ;
					// theStatement += "<a target=_blank href='" + SNP_Tracker_URLstarter + hg1 + "'>View "+ hg1 +" on SNP Tracker</A>";
				} else if (hg2 > "") {
					theStatement = Person2FirstName +  " belongs to haplogroup " + hg2Link + "." ;
					// theStatement += "<a target=_blank href='" + SNP_Tracker_URLstarter + hg2 + "'>View "+ hg2 +" on SNP Tracker</A>";					
				}
			
				// theStatement += "<BR/><I>courtesy of Rob Spencer, Tracking Back (<A target=_blank href=ScaledInnovation.com>scaledinnovation.com</a>)";
					

				// SNPtreeExplorerLinkDIV.innerHTML = theStatement; 
			} else {
				// SNPtreeExplorerLinkDIV.innerHTML = "";
			}

			return theStatement;

		}

		function showMiniTree() {
			document.getElementById("floatingMiniTreeToggle").style.display = "none";
			document.getElementById("floatingMiniTreeInTable").style.display = "block";
			document.getElementById("AllTheSteps").setAttribute("align" , "right") ;
		}

		function hideMiniTree(hideAll = false) {
			document.getElementById("floatingMiniTreeInTable").style.display = "none";
			document.getElementById("AllTheSteps").setAttribute("align" , "center") ;

			if (hideAll == true) {
				document.getElementById("floatingMiniTreeToggle").style.display = "none";
			} else {
				document.getElementById("floatingMiniTreeToggle").style.display = "block";
			}
		}


		</script>

	</HEAD>



	<BODY>
		<DIV align=center>
		<h1>DNA Confirmation Citation Maker app</h1>


	<DIV id=floatingMiniTreeToggle align=left style='position: fixed; display: none; padding-left: 5px;' ><IMG onclick='showMiniTree();' width=50px src=./pix/miniTreeIcon.png /></DIV>
	<DIV id=floatingMiniTreeInTable align=left style='position: fixed; display: none; padding-left: 5px;' ><span align=right><button style='padding:5px; background-color:red;' onclick='hideMiniTree();'>&nbsp;x&nbsp;</button></span><BR/></DIV>

		<DIV id=versionDesc>
		<span align=center>
				<span style='background-color:yellow;'>Version 3.12 </span> - updated 14 Oct 2026
				
		</span>
		</DIV>
		<span id=TypeOfConfTitle></span>
		<DIV id=DNAhelp><P>This app is designed to help you create proper source citations to add to your profiles to record your <a target=_blank href="https://www.wikitree.com/wiki/Help:DNA_Confirmation">DNA Confirmations</a>.
		</P>
		<P><B>How to Add Source Citations</B><BR>
		Every time you mark a child's relationship to their parent as <A target=_blank href=https://www.wikitree.com/wiki/Help:Confirmed_with_DNA>Confirmed with DNA</A><BR>you must add a source citation to the <B>== Sources ==</B> section<BR> of the <B><I>child's</I></B> profile that explains how you made the DNA-confirmed conclusion.</P>
		<P><A target=_blank href=https://www.wikitree.com/wiki/Help:Sources>Sources</A> are essential for our mission to create an accurate <A target=_blank href=https://www.wikitree.com/wiki/Help:Collaborative_Family_Tree>single family tree</A>.<BR>If there is <U>not</U> a proper source citation for a DNA-confirmed conclusion,<BR>then, the <A target=_blank href=https://www.wikitree.com/wiki/Help:Relationship_Status>relationship status</A> should be changed by you or another member to <A target=_blank href=https://www.wikitree.com/wiki/Help:Confident>Confident</A>.</P>
		<P>Some members also like to add a <B>=== DNA ===</B> subsection<BR/>under the <B>Biography</B> section or <B><A target=_blank href=https://www.wikitree.com/wiki/Help:Research_Notes>Research Notes</A></B> section but this is optional and not a substitute for a source citation.</P>
	
		</DIV>

		<DIV align=center id=ChooseDNAconfType  style=" background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
			<H2><B>CHOOSE THE TYPE OF DNA CONFIRMATION YOU WISH TO PERFORM:</B></H2>

			<table align="center"><tr>
				<td align=center><input type=submit class=submit value="Simple DNA match" onclick="beginConf('');"> <br>- choose this if your DNA confirmation is based on an<BR>Autosomal DNA match with a 3rd cousin or closer<br>&nbsp;<br>&nbsp;</td></tr><tr>
				<td align=center><input type=submit class=submit value="Triangulation" onclick="beginConf('Tri');"> <br>- choose this if you are doing a triangulated DNA confirmation involving 3 or more DNA matches that are greater than 3rd cousins<br>&nbsp;
					
						<br>&nbsp;<br>&nbsp;</td></tr><tr>
				<td align=center><input type=submit class=submit value="X DNA match" onclick="beginConf('x');"> <br>- choose this if you are confirming a relationship based on an X-chromosome match<br>&nbsp;
					
						<br>&nbsp;<br>&nbsp;</td></tr><tr>
				<td align=center><input type=submit class=submit value="Y DNA match" onclick="beginConf('y');"> <br>- choose this if you are confirming a paternal relationship based on shared Y-DNA from a DNA tester's father's father's line.<br>&nbsp;<br>&nbsp;</td></tr><tr>
				<td align=center><input type=submit class=submit value="mtDNA match" onclick="beginConf('mt');"> <br>- choose this if you are confirming a maternal relationship based on shared mitochondrial DNA (mtDNA) from<BR/>a DNA tester's mother's mother's line.<br></td></tr>
			</table>
		</DIV>
			

		 
		<DIV id=YourDNA></DIV>
		</p>
		<DIV id=GoBackLinks></DIV>
		<DIV id=DebugDIV></DIV>

		<DIV id=AllTheSteps align=center>
		<DIV id=TooDistant align=center style="background-color:yellow; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px; display: none;">
				<H1>WARNING</H1>
				
				<P align=center><B>You have chosen a relationship that is BEYOND a GREAT GREAT GRANDPARENT !</B>
				</P>

				<P>
					This is a relationship that can only be confirmed by DNA using a Triangulation method, using at least 3 DNA test takers, and comparing results on individual chromosomes.<br><br>
					At this current time, this initial version of this app cannot help with that, except to point you to the <a target=_blank href=https://www.wikitree.com/wiki/Help:Triangulation>Help page on Triangulation</a> on WikiTree.</P>
					<P>Eventually, this app will also be able to guide you through the Triangulation process ... but ... alas ... not at this immediate point in time.</P>

				<input class="button" type="submit" value="Close" onclick="closeDIV('TooDistant');" ><BR>&nbsp;
		
				
		</DIV>	

		<DIV id=FormDIV1ab align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 1:  Who has taken the DNA tests ?<HR>
				<span id=FormDIV1abP1err name=FormDIV1abP1err style="display:none; background-color:yellow;"></span>
				<P><B>YOUR WIKITREE ID:</B><br/> <input type=text name=id id=TTaker1 value="" onchange="fillInID4TT1spawn();"><BR><I>(e.g. Marcoux-336)<BR/></I>
				<font size=-1>
					<span id=TT1chooseSelfChildGrandKidRADIOS>
						<input type=radio name=TT1isWho id=TT1isSelfCHK checked> DNA test taker # 1 is <span id=TT1isSelfPrimaryID>WikiTree ID</span><BR/>
						<input type=radio name=TT1isWho id=TT1isChildCHK> DNA test taker # 1 is a child of <span id=TT1isChildPrimaryID>WikiTree ID</span><BR/>
						<input type=radio name=TT1isWho id=TT1isGrandChildCHK> DNA test taker # 1 is a grandchild of <span id=TT1isGrandChildPrimaryID>WikiTree ID</span><BR/>
					</span>
				</font>	
				</P>
				
				<HR><B>OTHER DNA TEST TAKER:</B><br/> 

				<span id=FormDIV1abP2err name=FormDIV1abP2err style="display:none; background-color:yellow;"></span>
				<input type=text id=TTaker2 value="" onchange="showNextButton(1);"> 	<BR><I>(WikiTree ID if possible; otherwise use Name, Alias or Initials for privacy)<BR/></I>
				
				
				<HR>
				<input id=NextButton1  class="button" type="submit" value="NEXT" onclick="doneForm1();" ><BR>&nbsp;
		

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		<DIV id=FormDIV1gender align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 1g:  Specify gender for DNA test takers<HR>
				 
				<P>
					<B><span id=GenderFormP1></span></B>:<br/> 
					<span id=GenderFormP1isMale>Male</span>
					<span id=GenderFormP1isFemale>Female</span>
					<span id=GenderFormP1isUndefined>
						<input type=radio name=GenderFormP1radio id=GenderFormP1radioMale value="Male"> Male 
						<input type=radio name=GenderFormP1radio id=GenderFormP1radioFemale value="Female"> Female 
					</span>

					<BR><BR>
					<B><span id=GenderFormP2></span></B>:<br/> 
					<span id=GenderFormP2isMale>Male</span>
					<span id=GenderFormP2isFemale>Female</span>
					<span id=GenderFormP2isUndefined>
						<input type=radio name=GenderFormP2radio id=GenderFormP2radioMale value="Male"> Male 
						<input type=radio name=GenderFormP2radio id=GenderFormP2radioFemale value="Female"> Female 
					</span>

					<span id=GenderFormShowP3>
					<BR><BR>
					<B><span id=GenderFormP3></span></B>:<br/> 
					<span id=GenderFormP3isMale>Male</span>
					<span id=GenderFormP3isFemale>Female</span>
					<span id=GenderFormP3isUndefined  style="display:none;" >
						<input type=radio name=GenderFormP3radio id=GenderFormP3radioMale value="Male"> Male 
						<input type=radio name=GenderFormP3radio id=GenderFormP3radioFemale value="Female"> Female 
					</span>
					</span>

					<BR><BR>

					 	
				<HR>
				<input id=NextButton1  class="button" type="submit" value="NEXT" onclick="doneForm1Gender();" ><BR>&nbsp;<BR>&nbsp;
		
				Why do we ask?<BR><BR>Some DNA confirmations require specific paths, <BR>based on the X or Y chromosome.  <BR><BR>All source confirmations use the gender to create standard citations.<BR><BR><I>Note: All of the text provided by this app can be modified, so if the DNA test taker prefers a non-binary or non-traditional pronoun in the source citation, that can be done individually after the standard citation is produced.</I>
				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		<DIV id=FormDIV1abc align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 1:  Who has taken the DNA tests ?<HR>
				<span id=FormDIV1abcP1err name=FormDIV1abP1err style="display:none; background-color:yellow;"></span>
				<P>
					<span style="background-color:#FFD580;"><B>&nbsp;Enter WikiTree ID for:&nbsp;</B></span>
					
					<br><br><B>TEST TAKER A:</B><br/> <input type=text name=id id=TTakerA value=""  onchange="updateRequiredToContinue4TriStep1();"><BR><I>(e.g. Marcoux-336)<BR/></I></P>
				
				<HR><B>TEST TAKER B:</B><br/> <input type=text id=TTakerB value="" onchange="updateRequiredToContinue4TriStep1();"> 	<BR><I>(WikiTree ID if possible; otherwise use Name, Alias or Initials for privacy)<BR/></I>
				
				<HR><B>TEST TAKER C:</B><br/> <input type=text id=TTakerC value="" onchange="updateRequiredToContinue4TriStep1();"> 	<BR><I>(WikiTree ID if possible; otherwise use Name, Alias or Initials for privacy)<BR/></I>
				
				
				<HR>
				<span style="background-color:#FFD580;"><B>&nbsp;Enter DNA Details of what is in common:&nbsp;</B></span>
				<br/><br/>
				What Chromosome is the Shared Segment on ? 
				<select id=Step1ChromoNum name=ChromoNum onchange="changedStep1ChromoNum();"> 
					<option value='?'>1 - 22 ?</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>7</option>
					<option>8</option> ~
					<option>9</option>
					<option>10</option>
					<option>11</option>
					<option>12</option>
					<option>13</option>
					<option>14</option>
					<option>15</option>
					<option>16</option>
					<option>17</option>
					<option>18</option>
					<option>19</option>
					<option>20</option>
					<option>21</option>
					<option>22</option>					
				</select>

				<br/> Length of Shared Segment (amount in common among all DNA testers) : <input type=text id=Step1TriMatchAmount name=matchAmount size=8 value=""  onchange="changedStep1ChromoNum();">
				cM (centiMorgans) 
				<BR><I>If using 23andMe.com, enter:<BR/><input type=text id=Step1TriMatchPercent name=matchAmount size=8 value=""  onchange="changedStep1ChromoNum(23);">
				% of shared DNA </I>
				<HR>

				<span style="background-color:#FFD580;"><B>&nbsp;# of Generations to go back in time to find Common Ancestors:</B></span>
				<br/><br/>
				<span id=showUseGens4Tri10><input type=radio name=HowManyTRIgenerations id=useGens4Tri10 value=10 checked>Use standard 10 generations &nbsp;&nbsp;&nbsp;</span>
				<input type=radio name=HowManyTRIgenerations id=useGens4Tri14 value=14 >Use extended 14 generations
				<HR>
				<span id=required4NextButton1abc ><B><U>REQUIRED to CONTINUE:</U></B><BR/>
					WikiTree ID for Test Taker A <BR/>
					WikiTree ID or Alias for Test Taker B <BR/>
					WikiTree ID or Alias for Test Taker C <BR/>
					Chromosome # of Shared Segment <BR/>
					Amount of Shared Segment <BR/>
				</span>
				<span id=showNextButton1abc style="display:none;">
					<input id=NextButton1abc  class="button" type="submit" value="NEXT" onclick="doneForm1Tri();" ><BR>&nbsp;
				</span>
		

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		<DIV id=FormDIV1abX align=center style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 1:  Who has taken the DNA tests ?<HR>
				<table border=0><tr>
				<td valign=top>
					If the matches are <B>both men</B> there is a <BR/>special opportunity for one-to-one confirmation<BR/>if they match on their X chromosomes.<BR/><BR/>Since men have only one X chromosome,<BR/>no triangulation is necessary.	
					<span id=FormDIV1abXP1err name=FormDIV1abP1err style="display:none; background-color:yellow;"></span>
					<P>Enter WikiTree ID for:<br><B>TEST TAKER A:</B><br/> <input type=text name=id id=TTakerAX2 value=""><BR><I>(e.g. Marcoux-336)<BR/></I></P>
					
					<HR><B>TEST TAKER B:</B><br/> <input type=text id=TTakerBX2 value=""> 	<BR><I>(WikiTree ID if possible; otherwise use Name, Alias or Initials for privacy)<BR/></I>
					<HR>
					<input id=NextButton1abX2  class="button" type="submit" value="NEXT" onclick="doneForm1Xpair();" ><BR>&nbsp;
				</td>
				<td valign=top bgcolor="#FFF">&nbsp;&nbsp;</td>
				<td valign=top bgcolor="#F0F0F0">&nbsp;</td>
				<td valign=top bgcolor="#FFF">&nbsp;&nbsp;</td>
				<td valign=top>
					Otherwise, triangulation is necessary.<BR/><BR/><BR/><BR/>
					<B>All 3 testers</B> will have to share<BR/>the same segment on the X-chromosome.
					<span id=FormDIV1abcXP1err name=FormDIV1abcP1err style="display:none; background-color:yellow;"></span>
					<P>Enter WikiTree ID for:<br><B>TEST TAKER A:</B><br/> <input type=text name=id id=TTakerAX3 value=""><BR><I>(e.g. Marcoux-336)<BR/></I></P>
					
					<HR><B>TEST TAKER B:</B><br/> <input type=text id=TTakerBX3 value=""> 	<BR><I>(WikiTree ID if possible; otherwise use Name, Alias or Initials for privacy)<BR/></I>
					<HR><B>TEST TAKER C:</B><br/> <input type=text id=TTakerCX3 value=""> 	<BR><I>(WikiTree ID if possible; otherwise use Name, Alias or Initials for privacy)<BR/></I>
					<HR>
					<input id=NextButton1abX3  class="button" type="submit" value="NEXT" onclick="doneForm1Xtrio();" ><BR>&nbsp;
					<div id=FormDIV1abcX></div>
				</td></tR>
			</table>
		

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		<DIV id=FormDIV1b align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 1b:  Who <span id=Div1bIsAre>is</span> the closest ancestor<span id=Div1bS></span> to <span id=Div1bP2P3>the other Test Taker(s)</span> on WikiTree?<HR>
				
				<P><B>ENTER WIKITREE ID<br>for <span id=Div1bP2>Test Taker B</span>'s closest ancestor:</B><br/> <input type=text name=id id=WikiID_B value=""><BR><I>(e.g. Labonté-125)<BR/></I></P>
				<P><B>ENTER WIKITREE ID<br>for <span id=Div1bP3>Test Taker C</span>'s closest ancestor:</B><br/> <input type=text name=id id=WikiID_C value=""><BR><I>(e.g. Labonté-127)<BR/></I></P>
				
				
				
				<HR>
				<input id=NextButton1  class="button" type="submit" value="NEXT" onclick="doneForm1b();" ><BR>&nbsp;
		

				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	
		

		<DIV id=FormDIV1err align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
			<span id=Div1errH1></span>
			<P>How to proceed?<br/><br/>
				
					<B>Did you make a typo in the WikiTree ID you entered?</B><BR>If so - <A  onclick='return2Step(1);'><U>return to STEP 1</U></A> and re-enter it.<BR/><BR/>
					<B>Does this person not have a WikiTree ID?</B><BR><I>(either not on WikiTree at all, or not a public profile)</I><BR>
						If so - enter the WikiTree ID of the closest ancestor who IS on WikiTree. <BR><I>(See the two scenarios below, and choose one.)</I><BR/>
				
				</P>
				<table align="center"  id=Scenario2TABLE><TR>
					<TD><A href="#Scenario1"><img width=250px src="images/DNAconfWikiTreeClosestAncestor1.png"></A></TD>
					<TD><A href="#Scenario2"><img width=250px src="images/DNAconfWikiTreeClosestAncestor2.png"></A></TD>
				</TR></table>
				<A Name="Scenario1" ></A>
				<BR>&nbsp;<BR>
				<img width=500px src="images/DNAconfWikiTreeClosestAncestor1.png">
				<BR><B>Enter the WikiTree ID for closest Ancestor who IS on WikiTree:</B><BR>
				<input type=text id=FormDIV1errWikiTreeID1 onchange='newWikiTreeIDentered(1);'><BR>
				<B>Relationship of <span id=FormDIV1errWikiTreeClose1>the closest Ancestor</span> to <span id=Div1errTTa name=Div1errTTa></span>:</B><BR>
				<span id=Div1errDropDown1></span> <BR>
				<B><span id=Div1errTTc name=Div1errTTc></span></B> is </B>
					<input type=radio name=GenderFormDiv1errRadio id=GenderFormDiv1errRadioMale value="Male"> Male 
					<input type=radio name=GenderFormDiv1errRadio id=GenderFormDiv1errRadioFemale value="Female"> Female 
				<BR>
				<BR><input type=submit class=submit value="Next" onclick="fixedIDandTryAgain('1');">
				
				<SPAN ID="Scenario2SPAN">
					<A Name="Scenario2"></A>
					<BR>&nbsp;<BR>
					<img width=500px src="images/DNAconfWikiTreeClosestAncestor2.png">
					<BR><B>Enter WikiTree ID for MRCA (Most Recent Common Ancestor)<br/>who must be on WikiTree:</B><BR>
					<input type=text id=FormDIV1errWikiTreeID2 onchange='newWikiTreeIDentered(2);'><BR>
					<BR><B>WikiTree ID for 2nd MRCA (If MRCA = Ancestral Couple)<br/>who must also be on WikiTree:</B><BR>
					<I>(Or leave blank if MRCA = Single Ancestor, <BR>i.e., the DNA test takers descend from different spouses)</I><BR/>
					<input type=text id=FormDIV1errWikiTreeID2b onchange='newWikiTreeIDentered("2b");'><BR>
					<B>Relationship of <span id=FormDIV1errWikiTreeClose2>MRCA</span> to <span id=Div1errTTb name=Div1errTTb></span>:</B><BR>
					<span id=Div1errDropDown2></span>	<BR>
					<B><span id=Div1errTTd name=Div1errTTd></span></B> is </B>
						<input type=radio name=GenderFormDiv2errRadio id=GenderFormDiv2errRadioMale value="Male"> Male 
						<input type=radio name=GenderFormDiv2errRadio id=GenderFormDiv2errRadioFemale value="Female"> Female 	<BR>			
					<BR><input type=submit class=submit value="Next" onclick="fixedIDandTryAgain('2');">
				</SPAN>

		</DIV>
				

		<DIV id=FormDIV1contacting align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
			<P>Contacting WikiTree ..... loading data .... for Person # <B><span id=SearchPN></span></B> of <B><span id=SearchPM></span></B>  </P>
		</DIV>

		<DIV id=ListOfNamesDIV></DIV>
		<DIV id=FormDIV1Warning align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
			<P><B>WARNING! No Common Ancestor Found !!</B><br><br>
				No common ancestor was found between <span id=Warning1P1></span> and <span id=Warning1P2></span> within five generations that would indicate a relationship of 3rd cousin or closer.
				<br><br>(1) If the two profiles are unrelated, perhaps there was a typo in the WikiTree IDs.  Please verify that you have entered the right IDs. <br>In this case: <b><A onclick=return2Step(1)><U>RETURN to STEP 1</U></A></b>.
				<br><br>(2) If the two profiles ARE related as 3rd cousins or closer, but the full family trees of both sides are not completed on WikiTree, then you can continue manually, and select the common ancestor from <span id=Warning1P1b></span>'s tree, and specify the relationship to <span id=Warning1P2b></span>.  <br>In this case: <b><A onclick=return2Step(2)><U>PROCEED to STEP 2</U></A></b>.
				<br><br>(3) If the two profiles ARE related, but the relationship is <B>further</B> than 3rd cousins<BR><I>(3rd cousins once removed or more removed,<BR> 4th or greater cousins<span id=HalfRelsTooFarExamples></span>)</I><BR>in order to CONFIRM the relationship <BR>to the Most Recent Common Ancestor<BR>you will need to TRIANGULATE your findings with a third DNA Match.<BR>You will need to prove that persons A, B and C all share at least 7cM of DNA on the same segment.<br><BR>
				In this case: <b><A onclick=beginConf('Tri');><U>TRY TRIANGULATION</U></A></b>.
				<BR>You may also wish to check out this <a target=_blank href=https://www.wikitree.com/wiki/Help:Triangulation>documentation on Triangulation</a>.<BR><I>("further" is defined by amount of shared DNA, not # of steps away genealogically)</I>

				<span id=WarningNonBioFound style="display:none;">
					<br><br>(4) It is possible that the test takers ARE related non-biologically.  There was at least one parent-child relationship discovered in traversing the family trees that was marked as NON-BIOLOGICAL (e.g. an adoptive parent), and any ancestors beyond that point cannot be used for DNA confirmation.<BR><span id=NonBioListOfPeeps></span>
				</span>
			</P>
		</DIV>

		<DIV id=FormDIV1WarningDeathDates align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
			<P><span style='background-color:pink;'><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WARNING! DNA Test Taker died before DNA Testing available !!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</B></span><br><br>
				One (or more) of the DNA Test Takers has a Death Date BEFORE the year 2000, before DNA test taking was commonly available.<BR/><BR/>
				<span id=FormDIV1WarningDeadTesters></span><BR/><BR/>
				<B>Any source citation made with this combination of DNA Test Takers is INVALID and must NOT be used on an actual WikiTree profile!</B><BR/><BR/>
				However, you may proceed for demonstration and/or testing purposes to view the end results and analyze the text generated.
			</P>
		</DIV>

		<DIV id=FormDIV1YGenderWarning align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: red; border-style: solid; border-width: 2px; border-radius: 20px;">
			<P><B>WARNING: Y-DNA confirmation requires both DNA testers to have Gender listed as Male on WikiTree.</B><BR/><BR/>
				Y-DNA cannot confirm a direct paternal-line relationship unless both testers are male.<BR/><BR/>
				<span id=YGenderWarningDetails></span><BR/><BR/>
				<A onclick="return2Step(1);"><U>RETURN to STEP 1</U></A>
			</P>
		</DIV>

		<DIV id=FormDIV2 align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 2:  Who is your common ancestor ?
				
				<HR>


				<P>The common ancestor(s) that <B><span id=Div2P1a>Person 1</span></B> and <B><span id=Div2P2a>Person 2</span></B> share is/are <br><B><span id=Div2P1b>Person 1</span></B>'s :<br/> 
					1. <select id=relationship name=relationship onchange="changeRelationship();"> 
					<option>Choose relationship</option>
					<option value=2>Parent(s)</option>
					<option value=3>Grandparent(s)</option>
					<option value=4>Great Grandparent(s)</option>
					<option value=5>Great Great Grandparent(s)</option>
					<option value=6>3x Great Grandparent(s)</option>
					<option>---</option>
					<option value=7>Beyond 2nd Greats...</option>

					
					</select>
					<br>
					<div id=Div2Q2select>2. <select id=whichMaPa name=mapa> 
					<option>Choose Specific Ancestor(s)</option>
					
					</select></div>
					


				  <BR><I><BR/></I></P>
				
				
				
				<HR>
				<input id=NextButton2 style="display:none;" class="button" type="submit" value="NEXT" onclick="doneForm2();" ><BR>&nbsp;
		
		<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>	

		
		<DIV id=FormDIV3 align=center  style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 3:  How is the other DNA Test Taker related ? <HR>
								

				<P>The common ancestor<span id=AncS1></span>, <B><span id=Div3CAa>The Common Ancestor</span></B>, <span id=IsAre1></span> <br><B><span id=Div3CAofP1>Person 1's ancestor</span></B>.<br/> 
					<P>If the common ancestor<span id=AncS2></span> need<span id=VerbS1></span> to be changed, please <a onclick=return2Step(2);><U>return to Step 2</U></a>.</P>

					<I>How <span id=IsAre2></span> <span id=Div3CAb>the Common Ancestor</span> related to <span id=Div3P2a>Person 2</span> ?</I>

				<P><B><span id=Div3CAc>The Common Ancestor</span></B> <span id=IsAre3></span> <br><B><span id=Div3P2b>Person 2</span></B>'s :<br/> 
					<div id=Div3SelectOptions>
						<select id=relationship3 name=relationship3  onchange="howRelated(3);"> 
							<option>Choose relationship</option>
							<option value=1>Parent</option>
							<option value=2>Grandparent</option>
							<option value=3>Great Grandparent</option>
							<option value=4>Great Great Grandparent</option>
							<option value=5>3x Great Grandparent</option>
							<option>---</option>
							<option value=6>Beyond 2x Great Grandparent</option>
						</select>
					</div>
					<BR><I><span id=Div3CA2P2></span><BR/></I></P>							

		</DIV>
		<DIV id=FormDIV4MultipleMRCAs align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
		</DIV>

		<DIV id=FormDIV4TooFar4SimpleWarning align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
			<P><B>WARNING! Relationship too distant !!</B><br><br>
				The relationship between <B><span id=Warning4P1></span></B> and <B><span id=Warning4P2></span></B> is not 3rd cousin or closer.  It has been calculated as <B><span id=Warning4Rel></span></B>. <BR><I>(You can view the mini-tree below)</I>

				<br><br>
				<span id=MultipleRelationshipsChooseCloserWarning><B>NOTE: There are multiple relationships between this pair of test takers.<BR/>Choose the closer relationship for the DNA Confirmation.<BR/><BR/></B></span>

				For profiles that are <B>further</B> than 3rd cousins<BR><I>(3rd cousins once or more times removed,<BR> 4th or greater cousins<span id=HalfRelsTooFarExamples2></span>)</I><BR>in order to CONFIRM the relationship <BR>to the Most Recent Common Ancestor<BR>you will need to TRIANGULATE your findings with a third DNA Match.<BR>You will need to prove that persons A, B and C all share at least 7cM of DNA on the same segment.<br><BR>In this case, return to <A onclick="resetApp();">Step 0</A> or <A onclick="resetApp();">RESET</A> the app, and choose TRIANGULATION.  <BR><BR>In the meantime, you may also want to<BR>check out this <a target=_blank href=https://www.wikitree.com/wiki/Help:Triangulation>documentation on Triangulation</a>.
				<BR><I>("further" is defined by amount of shared DNA, not # of steps away genealogically.)</I>

			</P>
		</DIV>

		<DIV id=FormDIV4direct align=center  style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">				
		</DIV>					

		<DIV id=FormDIV4Experimental align=center  style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">				
									
		</DIV>
		
		<DIV id=FormDIV4 align=center  style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 4:  Is this mini tree right ? <HR>
		
				  <p id=IDgender>Identify the gender <i>(if known)</i> of each ancestor in the tree, then click NEXT below.</p>
				  	<table id=SimpleMiniTreeTable align=center> 
					</table>
				  <table id=OldSimpleMiniTreeTable align=center> 
				  	<TR><TD colspan=2>&nbsp;</TD><TD align=center><span style="background-color: yellow;">COMMON ANCESTOR<span id=AncS4></span></span></TD><TD colspan=2>&nbsp;</TD></TR>
				  	<TR><TD colspan=2>&nbsp;</TD><TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCA>Luther Pendragon</span></b><br>&nbsp;</TD><TD colspan=2>&nbsp;</TD></TR>
				  	<TR><TD colspan=2 align=right valign=bottom><B>&LowerLeftArrow;</B></TD><TD align=center><span id=TreeP1rel2CA></span><br><I>and</I><br><span id=TreeP2rel2CA></span></TD><TD colspan=2 align=left valign=bottom><B>&LowerRightArrow;</B></TD></TR>

				  	<TR><TD align=center><span id=P2auncle>Person 2's auncle</span></TD>
				  		<TD id=TD4TreeCAsub1 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub1Half> </span>siblings</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub1b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1auncle>Person 1's auncle<br>&nbsp;</span></TD></TR>
					<TR><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub1>Person 1's ancestor</span><div id=isP1TT1 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP1R1><br><B>&darr;</B></DIV></TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown1></div><div id=P2peepRadios1><input name=P2peep1 id=P2peep1ValM type=radio value='M' onclick="changeP2CAsubGender(1,'M');">male<br><input name=P2peep1 id=P2peep1ValF type=radio value='F' onclick="changeP2CAsubGender(1,'F');">female</div><div id=P2peepRealNameRel1></div><div id=isTT1 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP2R1><br><B>&darr;</B></DIV></TD><TD>&nbsp;</TD></TR>			  		

				  	<TR id=MiniTreeRow2a><TD align=center><span id=P2cousin1>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub2 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub2>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub2Half></span> 1st cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub2b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub2b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin1>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow2b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub2>Person 1's ancestor</span><div id=isP1TT2 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP1R2><br><B>&darr;</B></DIV></TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown2></div><div id=P2peepRadios2><input name=P2peep2 id=P2peep2ValM type=radio value='M' onclick="changeP2CAsubGender(2,'M');">male<br><input name=P2peep2 id=P2peep2ValF type=radio value='F' onclick="changeP2CAsubGender(2,'F');">female</div><div id=P2peepRealNameRel2></div><div id=isTT2 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP2R2><br><B>&darr;</B></DIV></TD><TD>&nbsp;</TD></TR>			  		

				  	<TR id=MiniTreeRow3a><TD align=center><span id=P2cousin2>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub3 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub3>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub3Half></span> 2nd cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub3b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub3b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin2>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow3b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub3>Person 1's ancestor</span><div id=isP1TT3 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP1R3><br><B>&darr;</B></DIV></TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown3></div><div id=P2peepRadios3><input name=P2peep3 id=P2peep3ValM type=radio value='M' onclick="changeP2CAsubGender(3,'M');">male<br><input name=P2peep3 id=P2peep3ValF type=radio value='F' onclick="changeP2CAsubGender(3,'F');">female</div><div id=P2peepRealNameRel3></div><div id=isTT3 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP2R3><br><B>&darr;</B></DIV></TD><TD>&nbsp;</TD></TR>			  		

				  	<TR id=MiniTreeRow4a><TD align=center><span id=P2cousin3>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub4 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub4>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub4Half></span> 3rd cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub4b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub4b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin3>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow4b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub4>Person 1's ancestor</span><div id=isP1TT4 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP1R4><br><B>&darr;</B></DIV></TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown4></div><div id=P2peepRadios4><input name=P2peep4 id=P2peep4ValM type=radio value='M' onclick="changeP2CAsubGender(4,'M');">male<br><input name=P2peep4 id=P2peep4ValF type=radio value='F' onclick="changeP2CAsubGender(4,'F');">female</div><div id=P2peepRealNameRel4></div><div id=isTT4 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><DIV id=downArrowP2R4><br><B>&darr;</B></DIV> </TD><TD>&nbsp;</TD></TR>			  		

				  	<TR id=MiniTreeRow5a><TD align=center><span id=P2cousin4>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub5 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub5>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub5Half></span> 4th cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub5b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub5b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin4>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow5b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub5>Person 1's ancestor</span><div id=isP1TT5 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><br>&nbsp;</TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown5></div><div id=P2peepRadios5><input name=P2peep5 id=P2peep5ValM type=radio value='M' onclick="changeP2CAsubGender(5,'M');">male<br><input name=P2peep5 id=P2peep5ValF type=radio value='F' onclick="changeP2CAsubGender(5,'F');">female</div><div id=P2peepRealNameRel5></div><div id=isTT5 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div> </TD><TD>&nbsp;</TD></TR>			  		






				  	<TR id=MiniTreeRow6a><TD align=center><span id=P2cousin4>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub6 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub6>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub6Half></span> 4th cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub6b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub6b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin4>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow6b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub6>Person 1's ancestor</span><div id=isP1TT6 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><br>&nbsp;</TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown6></div><div id=P2peepRadios6><input name=P2peep6 id=P2peep6ValM type=radio value='M' onclick="changeP2CAsubGender(6,'M');">male<br><input name=P2peep6 id=P2peep6ValF type=radio value='F' onclick="changeP2CAsubGender(6,'F');">female</div><div id=P2peepRealNameRel6></div><div id=isTT6 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div> </TD><TD>&nbsp;</TD></TR>			  		

				  	<TR id=MiniTreeRow7a><TD align=center><span id=P2cousin4>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub7 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub7>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub7Half></span> 4th cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub7b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub7b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin4>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow7b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub7>Person 1's ancestor</span><div id=isP1TT7 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><br>&nbsp;</TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown7></div><div id=P2peepRadios7><input name=P2peep7 id=P2peep7ValM type=radio value='M' onclick="changeP2CAsubGender(7,'M');">male<br><input name=P2peep7 id=P2peep7ValF type=radio value='F' onclick="changeP2CAsubGender(7,'F');">female</div><div id=P2peepRealNameRel7></div><div id=isTT7 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div> </TD><TD>&nbsp;</TD></TR>			  		

				  	<TR id=MiniTreeRow8a><TD align=center><span id=P2cousin4>Person 2's cousin</span></TD>
				  		<TD id=TD4TreeCAsub8 align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub8>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center><font color=blue>&larr; <I><span id=TreeCAsub8Half></span> 4th cousins</I> &rarr;</font></TD>
				  		<TD id=TD4TreeCAsub8b align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub8b>Person 2's Great Grand Parent</span></b><br>&nbsp;</TD>				  		
				  		<TD align=center><span id=P1cousin4>Person 1's cousin<br>&nbsp;</span></TD></TR>
					<TR id=MiniTreeRow8b><TD align=center>&nbsp;</TD>
				  		<TD align=center><span id=P1CAsub8>Person 1's ancestor</span><div id=isP1TT8 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div><br>&nbsp;</TD>
				  		<TD>&nbsp;</TD><TD valign=top align=center><div id=P2peepDDown8></div><div id=P2peepRadios8><input name=P2peep8 id=P2peep8ValM type=radio value='M' onclick="changeP2CAsubGender(8,'M');">male<br><input name=P2peep8 id=P2peep8ValF type=radio value='F' onclick="changeP2CAsubGender(8,'F');">female</div><div id=P2peepRealNameRel8></div><div id=isTT8 style="display:none; background-color: yellow;">DNA&nbsp;test&nbsp;taker</div> </TD><TD>&nbsp;</TD></TR>			  		

				  </table>	
				
				
				
				<HR>
				<input id=NextButton4  class="button" type="submit" value="NEXT" onclick="doneForm4();" ><BR>&nbsp;
		
		<BR>&nbsp;<SPAN id=HowRelatedTheseTwo></SPAN>
		<BR>&nbsp;<BR>&nbsp;
		<SPAN id=Legend4Arrows></SPAN>
		<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV4abc align=center  style="display:none; background-color:white; max-width: 700px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 4:  Is this mini tree right ? <HR>
		
				  <p>Identify the gender <i>(if known)</i> of each ancestor in the tree, then click NEXT below</p>
				  <table align=center> 
				  	<TR><TD colspan=2>&nbsp;</TD><TD align=center><span style="background-color: yellow;">COMMON ANCESTOR<span id=AncS4></span></span></TD><TD colspan=2>&nbsp;</TD></TR>
				  	<TR><TD colspan=2>&nbsp;</TD><TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCA>Luther Pendragon</span></b><br>&nbsp;</TD><TD colspan=2>&nbsp;</TD></TR>
				  	<TR><TD colspan=2>&nbsp;</TD><TD align=center><span id=TreeP1rel2CA></span><br><I>and</I><br><span id=TreeP2rel2CA></span></TD><TD colspan=2>&nbsp;</TD></TR>

				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&larr; merge &rarr;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&larr; merge &rarr;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>

				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>
				  	
				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>
				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>
				  	
				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>
				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>
				  	
				  	<TR>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Arthur Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1>Lance Pendragon</span></b><br>&nbsp;</TD>
				  		<TD align=center>&nbsp;</TD>
				  		<TD align=center style="background-color:lime;">&nbsp;<br><b><span id=TreeCAsub1b>Percival Pendragon</span></b><br>&nbsp;</TD>
				  	</TR><TR><TD colspan=5 align=center>&nbsp;</TD></TR>
				  	
				  	 	  		

				  </table>	
				
				
				
				<HR>
				<input id=NextButton4abc  class="button" type="submit" value="NEXT" onclick="doneForm4();" ><BR>&nbsp;
		
		<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		


		<DIV id=FormDIV5 align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 5:  Are you sure about this ?
				<DIV id=DIV5warning style="background-color: yellow; display: none;">
					<HR><span style="background-color: yellow;"><H2>WARNING !</H2>
						To CONFIRM a DNA relationship based on a set of DNA Matches, you need to be able to answer YES to all of the questions below.<br><br>
						If you cannot, you may need to do more traditional genealogy research before continuing.
					</span>
				</DIV>
				<HR><B>Have you confirmed your relationship to your match through traditional genealogy or family history?</B><br/> 
				<input type=radio id=traditionalYes name=traditional value="Y"> YES
				<input type=radio id=traditionalNo name=traditional value="N"> NO

			<HR><B>Has this DNA Match been provided by a DNA testing company or a third-party test comparison site such as <span id=FormDIV5SuchAs>GEDmatch</span> ?</B><br/> 
				<input type=radio id=DNAtestYes name=DNAtest value="Y"> YES
				<input type=radio id=DNAtestNo name=DNAtest value="N"> NO

			<HR><B>Does the DNA-predicted relationship correspond with the known relationship from traditional genealogy ?</B><br/> 
				<input type=radio id=DNAcorrespondYes name=DNAcorrespond value="Y"> YES
				<input type=radio id=DNAcorrespondNo name=DNAcorrespond value="N"> NO



				  <BR><I><BR/></I></P>
				
				
				
				<HR>
				<input id=NextButton5 class="button" type="submit" value="NEXT" onclick="doneForm5();" ><BR>&nbsp;
		
		<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV6 align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 6:  What are the details of your autosomal DNA match ?
				
				<HR><B>NAME of DNA testing company / database:</B><br/> 
					<select id=DNAcompany name=DNAcompany onchange="changedDNAcompany();"> 
					<option value='?'>Choose company</option>
					<option value='23andMe'>23andMe</option>
					<option value="AncestryDNA">Ancestry</option>
					<option value="Family Tree DNA">Family Tree DNA (FTDNA)</option>
					<option value='GEDmatch'>GEDmatch</option>
					<option value='Geneanet'>Geneanet</option>
					<option value='Living DNA'>Living DNA</option>
					<option value="MyHeritageDNA">MyHeritage</option>
					<!-- <option>Other DNA company</option> -->
					</select>

				<HR><B>AMOUNT of Match :</B><br/> <input type=text onchange='updateDNApainterLink();' id=matchAmount name=matchAmount size=8 value="">
				cM (centiMorgans) <br>
				<span id=DNAcMnote23andMe style="display:none;"><I>NOTE: To find the amount of cM you share with a match on 23andMe,<br>click on the match name, and in the genetic relationship panel,<br>click on View DNA details.</I><br/> <input type=text id=matchPerCent name=matchPerCent size=8 value="">
				% DNA shared <br><br></span>
				# of Segments: <input type=text id=NumSegments name=NumSegments size=4>
				<span id=DNAsegmentsFTDNA style="display:none;"><br><I>NOTE: To find the # of segments you have with a match on FamilyTreeDNA,<br>click the checkbox next to the match name in FamilyFinder, then<br>click on the Chromosome Browser button.<br>Change the drop-down selector from 5 to 7+ centimorgans (recommended), then<br>count the number of segments in the browser display.</I><br></span>
				<HR><B>Relationship predicted by DNA testing company :</B><br/> <input type=text id=relationshipPrediction name=relationshipPrediction  value="">


				<span id=DNAnote4GEDmatch style="display:none;">
					<HR/>
					<BR><B>Kit Numbers</B>
					<BR><I>GEDmatch kit # for <span id=DNAGMpA>Person A</span></I> :<BR>
					 <input id=DNAkitGMA type=text>
					<BR><I>GEDmatch kit # for <span id=DNAGMpB>Person B</span> </I>:<BR>
					 <input id=DNAkitGMB type=text>
 				</span>

				  <BR><I><BR/></I></P>
				
				
				<div align=right id=FormDIV6AddNote1Link><A onclick="addNote();"><U>Add Note</U></A></div>

				<DIV id=FormDIV6AddNote1 style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="addNoteClose();"><U>CLOSE</U></A></P>

					<BR><B>Optional: Additional Note</B>
					<BR><BR><I>Add extra information here if desired.
					<BR><BR>This note will be added to the end 
					<BR>of every citation created for this match.
					<BR>
					<input type=text id=DIV6addedNote1 width=400px>
					<BR>
				(NOTE: This is usually left blank.)</I>
				</DIV><br>
				<DIV id=DNApainterLink></DIV>
				<HR>
				<input id=NextButton6  class="button" type="submit" value="NEXT" onclick="doneForm6();" ><BR>&nbsp;
		
			<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV6TRI align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 6:  What are the details of your autosomal DNA matches ?
				
					<HR><B>NAME of DNA testing company / database:</B><br/> 
					<select id=DNAcompany3 name=DNAcompany3 onchange="changedTriDNAcompany();"> 
					<option value='?'>Choose company</option>
					<option>23andMe</option>
					<option value="Family Tree DNA">Family Tree DNA (FTDNA)</option>
					<option>GEDmatch</option>
					<option value="MyHeritageDNA">MyHeritage</option>
					<!-- <option>Other DNA company</option> -->
					</select>

				<HR><B>DNA Details of what is in common</B><BR><BR>
				What Chromosome is the Shared Segment on ? 
				<select id=ChromoNum name=ChromoNum onchange="changedChromoNum();"> 
					<option value='?'>1 - 22 ?</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>7</option>
					<option>8</option> ~
					<option>9</option>
					<option>10</option>
					<option>11</option>
					<option>12</option>
					<option>13</option>
					<option>14</option>
					<option>15</option>
					<option>16</option>
					<option>17</option>
					<option>18</option>
					<option>19</option>
					<option>20</option>
					<option>21</option>
					<option>22</option>					
				</select>

				<br/> Length of Shared Segment (amount in common among all DNA testers) : <input type=text id=TriMatchAmount name=matchAmount size=8 value=""  onchange="changedChromoNum();">
				cM (centiMorgans) 

				<SPAN id=FormDIV6FalsePercentageNote></SPAN>

				<div align=right id=FormDIV6CustomTRIChromosomesLink><A onclick="customizeChromosomes();"><U>Customize Chromosome Segments</U></A></div>

				<DIV id=FormDIV6CustomTRIChromosomes style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="customizeChromosomesClose();"><U>CLOSE</U></A></P>
					<B>Customize wording for citation regarding shared Chromosome Segment(s)</B><BR><BR>
					<input type=radio name=TriSegmentMatchStmt id=TriSegmentMatchStmtStd value=Std checked> Use standard wording:<BR>
					<I>They share a <SPAN id=TriStdStmtAmount></SPAN> cM segment on chromosome <SPAN id=TriStdStmtSegment></SPAN></I>
					<BR><BR>
					<input type=radio name=TriSegmentMatchStmt  id=TriSegmentMatchStmtCustom value=Custom> Use customized wording:<BR>
					<I>They share a <BR><textarea rows=2 cols=50  id=TriCustomStmt></textarea> </I>
					<BR><I>Note: Use customized wording if additional details are needed.</I>
				</DIV>
				

				<HR/>


				
				<span id=TRInote4GEDmatch style="display:none;">
					<BR><B>Kit Numbers</B>
					<span id=TriGEDmatchKitsList>
						<BR><I>GEDmatch kit # for <span id=TriGMpA>Person A</span></I> :<BR>
						 <input id=TriPAkitGMA type=text>
						<BR><I>GEDmatch kit # for <span id=TriGMpB>Person B</span> </I>:<BR>
						<input id=TriPAkitGMB type=text>
						<BR><I>GEDmatch kit # for <span id=TriGMpC>Person C</span> </I>:<BR>
						<input id=TriPAkitGMC type=text>
					</span>

					<HR><B>Note regarding confirmation</B><BR><BR><I>Remember that you must check that every combination of the DNA testers does indeed match on the same chromosome. <BR><BR>On GEDmatch you can do this by using the Tier 1 <B>Triangulation</B> tool.<br><br>If you don't have Tier 1 access to GEDmatch this month, you can also<BR>use the <B>People who match both, or 1 of 2 kits</b> tool, <BR>then use the <B>Visualization</B> option afterwards to view the <B>Triangulation</B>.<BR></I>
				</span>
				<span id=TRInote23andMe style="display:none;">
					<BR><B>Note regarding confirmation</B><BR><BR><I>Remember that you must check to make sure that every combination of the DNA testers does indeed match on the same chromosome. <BR><BR>On 23andMe you can do this by using the Chromosome Browser<br>to compare each of the Test Takers to each other.<BR><BR>
						Do this multiple times, each time putting a different person in the "Compare" slot.<BR>
						<span id=TRInote23andMeCompares>
							<B>Compare</B>  Person A  <B>With</B> Person B and Person C.<BR>
							<B>Compare</B>  Person B  <B>With</B> Person C and Person A.<BR>
							<B>Compare</B>  Person C  <B>With</B> Person A and Person B.
						</span>
						<BR><BR>In ALL the comparisons, the same DNA segment should show overlap<BR>for all the DNA test takers on Chromosome <span id=TRInote23andMeChromoN>N</span>.<BR></I>
						
				</span>
				<span id=TRInoteMyHeritage style="display:none;">
					<BR><B>Note regarding confirmation</B><BR><BR><I>Remember that you must check to make sure that every combination of the DNA testers does indeed match on the same chromosome. <BR><BR>On MyHeritage, when viewing your DNA Matches list, <BR>use the Triangulation indicator to open up <BR>the <B>One-to-many Chromosome Browser</B> to verify<BR>the Triangulated Segment you have in common on Chromosome <span id=TriNoteMyHeritageChromoN>N</span>.<BR><BR></I>
				</span>
						
				<span id=TRIverification style="display:none;"><BR><B>Verification</B><BR><BR><I>Because of the way this testing company uses its Chromosome Browser, each combination in the triangulation has to have its DNA match on the shared segment independently verified by at least one of those testers.</I> <BR><BR>Please check to verify each of the following combinations.
					<SPAN id=TRInoteFTDNACheckboxes>
						<I><BR><BR><B><span id=TriPAfn1>Person A</span> & <span id=TriPBfn1>Person B</span> </B>: DNA match on chromosome <span id=ChromoN1>N</span> :</I><BR> <input type=checkbox id=VerifABbyA name=VerifABbyA value='Y'> verified by <span id=TriPAfnln1>Person A</span>  <input type=checkbox id=VerifABbyB name=VerifABbyB value='Y'> verified by <span id=TriPBfnln1>Person B</span>  <BR>
						<I><BR><B><span id=TriPBfn2>Person B</span> & <span id=TriPCfn1>Person C</span> </B>: DNA match on chromosome <span id=ChromoN2>N</span> :</I><BR> <input type=checkbox id=VerifBCbyB name=VerifBCbyB value='Y'> verified by <span id=TriPBfnln2>Person B</span>  <input type=checkbox id=VerifBCbyC name=VerifBCbyC value='Y'> verified by <span id=TriPCfnln1>Person C</span>  <BR>
						<I><BR><B><span id=TriPCfn2>Person C</span> & <span id=TriPAfn2>Person A</span> </B>: DNA match on chromosome <span id=ChromoN3>N</span> :</I><BR> <input type=checkbox id=VerifCAbyC name=VerifCAbyC value='Y'> verified by <span id=TriPCfnln2>Person C</span>  <input type=checkbox id=VerifCAbyA name=VerifCAbyA value='Y'> verified by <span id=TriPAfnln2>Person A</span>  <BR>
					</SPAN>	
				</span>
			

				
				  <BR><I><BR/></I></P>
				
				<div align=right id=FormDIV6AddNoteTriLink><A onclick="addNote();"><U>Add Note</U></A></div>

				<DIV id=FormDIV6AddNoteTri style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="addNoteClose();"><U>CLOSE</U></A></P>

					<BR><B>Optional: Additional Note</B>
					<BR><BR><I>Add extra information here if desired.
					<BR><BR>This note will be added to the end 
					<BR>of every citation created for this match.
					<BR>
					<input type=text id=DIV6addedNoteTri width=400px>
					<BR>
				(NOTE: This is usually left blank.)</I>
				</DIV>	
				
				
				<HR>
				<input id=NextButton6tri  class="button" type="submit" value="NEXT" onclick="doneForm6();" ><BR>&nbsp;
		
			<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV6X align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 6:  What are the details of your X-Chromosome DNA match ?
				
				<HR><B>NAME of DNA testing company / database:</B><br/> 
					<select id=XDNAcompany name=XDNAcompany onchange="changedXDNAcompany();"> 
					<option value='?'>Choose company</option>
					<option>23andMe</option>
					<option value="Family Tree DNA">Family Tree DNA (FTDNA)</option>
					<option>GEDmatch</option>
					<!-- <option>Other DNA company</option> -->
					</select>

				<HR><B>Length of Shared Segment on X-chromosome :</B><br/> <input type=text id=XmatchAmount name=XmatchAmount size=8 value="" onchange="changedXmatchAmount();">
				cM (centiMorgans) 

				<div align=right id=FormDIV6CustomXChromosomesLink><A onclick="customizeChromosomes();"><U>Customize Chromosome Segments</U></A></div>

				<DIV id=FormDIV6CustomXChromosomes style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="customizeChromosomesClose();"><U>CLOSE</U></A></P>
					<B>Customize wording for citation regarding shared Chromosome Segment(s)</B><BR><BR>
					<input type=radio name=xSegmentMatchStmt id=xSegmentMatchStmtStd value=Std checked> Use standard wording:<BR>
					<I>They share a <SPAN id=xStdStmtAmount></SPAN> cM segment on the X chromosome</I>
					<BR><BR>
					<input type=radio name=xSegmentMatchStmt  id=xSegmentMatchStmtCustom value=Custom> Use customized wording:<BR>
					<I>They share a <BR><textarea rows=2 cols=50  id=xCustomStmt></textarea> </I>
					<BR><I>Note: Use customized wording if additional details are needed.</I>
				</DIV>
				
							
				
				<span id=Xnote4GEDmatch style="display:none;">
					<HR/>
					<BR><B>Kit Numbers</B>
					<BR><I>GEDmatch kit # for <span id=XGMpA>Person A</span></I> :<BR>
					 <input id=XPAkitGMA type=text>
					<BR><I>GEDmatch kit # for <span id=XGMpB>Person B</span> </I>:<BR>
					<input id=XPAkitGMB type=text>
					<span id=Xkits4Tri>
						<BR><I>GEDmatch kit # for <span id=XGMpC>Person C</span> </I>:<BR>
						<input id=XPAkitGMC type=text>
					
						<BR>
						<BR><B>Note regarding confirmation</B><BR><BR><I>Remember that you must check that every combination of the DNA testers does indeed match on the X Chromosome. On GEDmatch you can do this by using the Tier 1 <B>Triangulation</B> tool, and restrict it to the X chromosome.<br><br>If you don't have Tier 1 access to GEDmatch this month, you can also<BR>use the <B>People who match both, or 1 of 2 kits</b> tool, <BR>then use the <B>Visualization</B> option afterwards to view the <B>Triangulation</B>.<BR></I>
							
					</span>
				</span>
				<span id=Xnote23andMe style="display:none;">
					<BR><B>Note regarding confirmation</B><BR><BR><I>Remember that you must check to make sure that every combination of the DNA testers does indeed match on the X Chromosome. <BR><BR>On 23andMe you can do this by using the Chromosome Browser<br>to compare the three Test Takers to each other.<BR><BR>
						Do this three times, each time putting a different person in the "Compare" slot.<BR>
						<span id=Xnote23andMeCompares>
							<B>Compare</B>  Person A  <B>With</B> Person B and Person C.<BR>
							<B>Compare</B>  Person B  <B>With</B> Person C and Person A.<BR>
							<B>Compare</B>  Person C  <B>With</B> Person A and Person B.
						</span>
						<BR><BR>In ALL three comparisons, the same DNA segment should show overlap<BR>for all three test takers on the X Chromosome.<BR></I>
				</span>
				<span id=Xverification style="display:none;"><BR><B>Verification</B><BR><BR><I>Because of the way Family Tree DNA uses its Chromosome Browser, each combination in the triad has to have its DNA match on the shared segment independently verified by at least one of those testers.</I> <BR><BR>Please check to verify each of the following combinations.
				<I><BR><BR><B><span id=XPAfn1>Person A</span> & <span id=XPBfn1>Person B</span> </B>: DNA match on X chromosome :</I><BR> <input type=checkbox id=XVerifABbyA name=VerifABbyA value='Y'> verified by <span id=XPAfnln1>Person A</span>  <input type=checkbox id=XVerifABbyB name=VerifABbyB value='Y'> verified by <span id=XPBfnln1>Person B</span>  <BR>
				<I><BR><B><span id=XPBfn2>Person B</span> & <span id=XPCfn1>Person C</span> </B>: DNA match on X chromosome :</I><BR> <input type=checkbox id=XVerifBCbyB name=VerifBCbyB value='Y'> verified by <span id=XPBfnln2>Person B</span>  <input type=checkbox id=XVerifBCbyC name=VerifBCbyC value='Y'> verified by <span id=XPCfnln1>Person C</span>  <BR>
				<I><BR><B><span id=XPCfn2>Person C</span> & <span id=XPAfn2>Person A</span> </B>: DNA match on X chromosome :</I><BR> <input type=checkbox id=XVerifCAbyC name=VerifCAbyC value='Y'> verified by <span id=XPCfnln2>Person C</span>  <input type=checkbox id=XVerifCAbyA name=VerifCAbyA value='Y'> verified by <span id=XPAfnln2>Person A</span>  <BR>
				</span>
			
				<div align=right id=FormDIV6AddNotexLink>&nbsp;<BR><A onclick="addNote();"><U>Add Note</U></A></div>

				<DIV id=FormDIV6AddNotex style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="addNoteClose();"><U>CLOSE</U></A></P>

					<BR><B>Optional: Additional Note</B>
					<BR><BR><I>Add extra information here if desired.
					<BR><BR>This note will be added to the end 
					<BR>of every citation created for this match.
					<BR>
					<input type=text id=DIV6addedNotex width=400px>
					<BR>
				(NOTE: This is usually left blank.)</I>
				</DIV>
				
				<HR>
				<input id=NextButton6x  class="button" type="submit" value="NEXT" onclick="doneForm6();" ><BR>&nbsp;
		
			<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV6Y align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 6:  What are the details of your Y DNA match ?
				
				<HR><B>NAME of Y DNA testing company / database :</B><br/> 
					<select id=YDNAcompany name=YDNAcompany onchange="changedYDNAcompany();"> 
					<option value='?'>Choose company</option>
					<option value="Family Tree DNA">Family Tree DNA (FTDNA)</option>
					<option value='mitoYDNA'>mitoYDNA.org</option>
					<!-- <option>Other DNA company</option> -->
					</select>


				<HR><B>DETAILS of Y DNA MATCH<BR><BR># of Markers for Match:</B><br/> 
					<select id=Markers name=Markers align=center  onchange="changedYDNAcompany();"> 
					<option value='0'>Choose #</option>
					<option value='37'>37</option>
					<option value='67'>67</option>
					<option value='111'>111</option>
					<option id=BigYoption value='838'>BigY</option>
					</select>

				<BR>
				<BR><span id=HelpTextForGeneticDistance></span>
				<br/>
				<B><span id=NameForGeneticDistance>Genetic Distance</span> :</B><br/> <input type=text id=genDistance name=genDistance size=8 value="0"> 
				<BR>
				<BR>
				<B><span id=YkitP1name>Y-DNA Kit # for Person 1</span>:</B><br/> <input type=text id=YDNAkitP1 name=YDNAkitP1 size=8 value="0"> 
				<BR>
				<B>Haplogroup:</B><br/> <input onchange='updateSNPtreeExplorerLink();' type=text id=YDNAhaploP1 name=YDNAhaploP1 size=12 value="0"> 
				<BR>
				<B><span id=YkitP2name>Y-DNA Kit # for Person 2</span>:</B><br/> <input type=text id=YDNAkitP2 name=YDNAkitP2 size=8 value="0"> 
				<BR>
				<B>Haplogroup:</B><br/> <input onchange='updateSNPtreeExplorerLink();' type=text id=YDNAhaploP2 name=YDNAhaploP2 size=12 value="0"> 
				<BR>
				 
				<BR><BR/><div id=SNPtreeExplorerLink></div></P>

				<span id=YdistanceTooMuch style="display:block; text-align: left; background-color: yellow;">
					<P align=center><B>GENETIC DISTANCE - TOO MUCH</B></P>
					The Genetic Distance is too large to verify the paternal line connection.<BR><BR>
					
				</span>

				
				
				<div align=right id=FormDIV6AddNoteyLink><A onclick="addNote();"><U>Add Note</U></A></div>

				<DIV id=FormDIV6AddNotey style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="addNoteClose();"><U>CLOSE</U></A></P>

					<BR><B>Optional: Additional Note</B>
					<BR><BR><I>Add extra information here if desired.
					<BR><BR>This note will be added to the end 
					<BR>of every citation created for this match.
					<BR>
					<input type=text id=DIV6addedNotey width=400px>
					<BR>
				(NOTE: This is usually left blank.)</I>
				</DIV>
				
				<HR>
				<input id=NextButton6y  class="button" type="submit" value="NEXT" onclick="doneForm6();" ><BR>&nbsp;
		
			<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV6MT align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 6:  What are the details of your mt DNA match ?
				
				<HR><B>NAME of mt DNA testing company / database :</B><br/> 
					<select id=MTDNAcompany name=MTDNAcompany onchange="changedMTDNAcompany();"> 
					<option value='?'>Choose company</option>
					<option value="Family Tree DNA">Family Tree DNA (FTDNA)</option>
					<option value='mitoYDNA'>mitoYDNA.org</option>
					<!-- <option>Other DNA company</option> -->
					</select>


				<HR><B>DETAILS of mt DNA MATCH</B><BR><BR>
				<span id=MTDNAinfoFTDNA style="display:none; text-align: left;"><I>
					<UL>
						<LI>On the <B>mtDNA - Matches</B> page, </LI>
						<LI> From the <B>Regions</B> drop down,</LI>
						<LI> Choose <B>HVR1, HVR2, Coding Regions</B>.<BR/> &nbsp;</LI>
						
						<LI>Fill in the information requested below.</LI>
					</UL>
					</I><br/>
									<BR>
					<B>Genetic Distance :</B><br/> <input type=text id=genDistanceMT name=genDistance size=8 value="0"> <BR>
	
				</span>		

				<span id=MTDNAinfoMitoY style="display:none; text-align: left;"><I>
					<UL>
						<LI>On the <B>mtCompare / Match</B> page, </LI>
						<LI>Fill in the information requested below.</LI>
					</UL>
					</I><br/>
								<table align="center"><TR>
									<TH align=center valign=middle> Diff HVR1 </TH>
									<TH align=center valign=middle> Diff HVR2 </TH>
									<TH align=center valign=middle> Diff CR </TH>									
								</TR><TR>
									<TD align=center valign=middle> <input type=text id=diffHVR1 name=diffHVR1 size=8 value="0"> </TD>
									<TD align=center valign=middle> <input type=text id=diffHVR2 name=diffHVR2 size=8 value="0"> </TD>
									<TD align=center valign=middle> <input type=text id=diffCR name=diffCR size=8 value="0"> </TD>
									
								</TR></table>

				</span>		

				<B><span id=MTkitP1name>mtDNA Kit # for Person 1</span>:</B><br/> <input type=text id=MTDNAkitP1 name=MTDNAkitP1 size=8 value="0"> 
				<BR>
				<B><span id=MTkitP2name>mtDNA Kit # for Person 2</span>:</B><br/> <input type=text id=MTDNAkitP2 name=MTDNAkitP2 size=8 value="0"> 
				<BR>

				<span id=MTmismatchVerif style="display:block; text-align: left; background-color: yellow;">
					<P align=center><B>MISMATCH WARNING</B></P>
					The <span id=MTcallitMitoY>combined difference</span><span id=MTcallitFTDNA>Genetic Distance</span> is greater than 1.<BR><BR>
					Unless this has been confirmed to be due to MINOR mismatches, it throws the validity of the citation into question.<BR><BR>
					<input type=checkbox id=MTminorMismatchConfirmed value=Y>I confirm that this <span id=MTcallitMitoY2>combined difference</span><span id=MTcallitFTDNA2>Genetic Distance</span> is due to a Minor Mismatch, and that the documented genealogy supports the connection this proves through the maternal lines.
				</span>

				<span id=MTmismatchTooMuch style="display:block; text-align: left; background-color: yellow;">
					<P align=center><B>MISMATCH - TOO MUCH</B></P>
					The <span id=MTcallitMitoY3>combined difference</span><span id=MTcallitFTDNA3>Genetic Distance</span> is too large to verify the maternal line connection.<BR><BR>
					
				</span>
				<!-- <BR>

				<B>mt DNA Haplogroup :</B><br/> <input type=text id=mtHaplo name=mtHaplo size=8 value="">  -->

		
				  <BR><I><BR/></I></P>
				
				<div align=right id=FormDIV6AddNotemtLink><A onclick="addNote();"><U>Add Note</U></A></div>

				<DIV id=FormDIV6AddNotemt style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >	
					<P align=right><A onclick="addNoteClose();"><U>CLOSE</U></A></P>

					<BR><B>Optional: Additional Note</B>
					<BR><BR><I>Add extra information here if desired.
					<BR><BR>This note will be added to the end 
					<BR>of every citation created for this match.
					<BR>
					<input type=text id=DIV6addedNotemt width=400px>
					<BR>
				(NOTE: This is usually left blank.)</I>
				</DIV>
				
				<HR>
				<input id=NextButton6mt  class="button" type="submit" value="NEXT" onclick="doneForm6();" ><BR>&nbsp;
		
			<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
		</DIV>		

		<DIV id=FormDIV7 align=center style="display:none; background-color:white; max-width: 500px; padding: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;">
				<P>STEP 7:  Here are the instructions to add the DNA confirmation<br>and your properly sourced citation for this DNA confirmation:
					<div align=right id=FormDIV7CustomCitationsLink><A onclick="customizeCitations();"><U>Customize Citations</U> 
						<svg height="16" width="16" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
            				viewBox="0 0 512 512"  xml:space="preserve"> <style type="text/css"> .st0{fill:#000000;} </style>
					        <g>
					            <path class="st0" d="M499.453,210.004l-55.851-2.58c-5.102-0.23-9.608-3.395-11.546-8.103l-11.508-27.695
					                c-1.937-4.728-0.997-10.145,2.455-13.914l37.668-41.332c4.718-5.188,4.546-13.205-0.421-18.182l-46.434-46.443
					                c-4.986-4.967-13.003-5.159-18.2-0.412l-41.312,37.668c-3.778,3.443-9.206,4.402-13.924,2.436l-27.694-11.488
					                c-4.718-1.946-7.864-6.454-8.094-11.565l-2.589-55.831C301.675,5.534,295.883,0,288.864,0h-65.708
					                c-7.02,0-12.831,5.534-13.156,12.562l-2.571,55.831c-0.23,5.111-3.376,9.618-8.094,11.565L171.64,91.447
					                c-4.737,1.966-10.165,1.007-13.924-2.436l-41.331-37.668c-5.198-4.746-13.215-4.564-18.201,0.412L51.769,98.198
					                c-4.986,4.977-5.158,12.994-0.422,18.182l37.668,41.332c3.452,3.769,4.373,9.186,2.416,13.914l-11.469,27.695
					                c-1.956,4.708-6.444,7.873-11.564,8.103l-55.832,2.58c-7.019,0.316-12.562,6.118-12.562,13.147v65.699
					                c0,7.019,5.543,12.83,12.562,13.148l55.832,2.579c5.12,0.229,9.608,3.394,11.564,8.103l11.469,27.694
					                c1.957,4.728,1.036,10.146-2.416,13.914l-37.668,41.313c-4.756,5.217-4.564,13.224,0.403,18.201l46.471,46.443
					                c4.967,4.977,12.965,5.15,18.182,0.422l41.312-37.677c3.759-3.443,9.207-4.392,13.924-2.435l27.694,11.478
					                c4.719,1.956,7.864,6.464,8.094,11.575l2.571,55.831c0.325,7.02,6.136,12.562,13.156,12.562h65.708
					                c7.02,0,12.812-5.542,13.138-12.562l2.589-55.831c0.23-5.111,3.376-9.619,8.094-11.575l27.694-11.478
					                c4.718-1.957,10.146-1.008,13.924,2.435l41.312,37.677c5.198,4.728,13.215,4.555,18.2-0.422l46.434-46.443
					                c4.967-4.977,5.139-12.984,0.421-18.201l-37.668-41.313c-3.452-3.768-4.412-9.186-2.455-13.914l11.508-27.694
					                c1.937-4.709,6.444-7.874,11.546-8.103l55.851-2.579c7.019-0.318,12.542-6.129,12.542-13.148v-65.699
					                C511.995,216.122,506.472,210.32,499.453,210.004z M256.01,339.618c-46.164,0-83.622-37.438-83.622-83.612
					                c0-46.184,37.458-83.622,83.622-83.622s83.602,37.438,83.602,83.622C339.612,302.179,302.174,339.618,256.01,339.618z"/>
					        </g>
				        </svg>


					</A></div>
						<DIV id=FormDIV7CustomCitations style="display:none; text-align:center; background-color:lightgoldenrodyellow; max-width: 400px; padding-bottom: 20px; margin: 20px; border-color: green; border-style: solid; border-width: 2px; border-radius: 20px;" >							
						<P align=right><A onclick="customizeCitationsClose();"><U>CLOSE</U> <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2ZM15.36 14.3C15.65 14.59 15.65 15.07 15.36 15.36C15.21 15.51 15.02 15.58 14.83 15.58C14.64 15.58 14.45 15.51 14.3 15.36L12 13.06L9.7 15.36C9.55 15.51 9.36 15.58 9.17 15.58C8.98 15.58 8.79 15.51 8.64 15.36C8.35 15.07 8.35 14.59 8.64 14.3L10.94 12L8.64 9.7C8.35 9.41 8.35 8.93 8.64 8.64C8.93 8.35 9.41 8.35 9.7 8.64L12 10.94L14.3 8.64C14.59 8.35 15.07 8.35 15.36 8.64C15.65 8.93 15.65 9.41 15.36 9.7L13.06 12L15.36 14.3Z" fill="red"/>
        </svg></A></P>

					<B>DNA Test Taker # 1:</B><BR>
					<span id=SpanCiteName1Old><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName1  id=RadioCiteName1Old value='Old'>use name at birth: <span id=CustomCiteName1Old></span><BR></span>
					<span id=SpanCiteName1New><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName1 id=RadioCiteName1New value='New' checked>use current name: <span id=CustomCiteName1New></span><BR></span>
					<input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName1 id=RadioCiteName1Cust value='Cust'>use alias: <input type=text  onchange="changeCousinFormat(0);" id=CustomCiteName1Cust><BR>
					<span id=SpanWikiLink1><input type=checkbox checked onclick="changeCousinFormat(0);" name=UseWikiLink1 id=UseWikiLink1 value='Y'>link to profile <span id=WikiTreeProfile1></span><BR></span>

					<BR>

					<B>DNA Test Taker # 2:</B><BR>
					<span id=SpanCiteName2Old><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName2  id=RadioCiteName2Old value='Old'>use name at birth: <span id=CustomCiteName2Old></span><BR></span>
					<span id=SpanCiteName2New><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName2 id=RadioCiteName2New value='New' checked>use current name: <span id=CustomCiteName2New></span><BR></span>
 					<input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName2 id=RadioCiteName2Cust value='Cust'>use alias: <input type=text  onchange="changeCousinFormat(0);" id=CustomCiteName2Cust><BR>
					<span id=SpanWikiLink2><input type=checkbox checked onclick="changeCousinFormat(0);" name=UseWikiLink2 id=UseWikiLink2 value='Y'>link to profile <span id=WikiTreeProfile2></span><BR></span>

					<BR>

					<span id=NeedCustomCiteName3 style="display:none;">
						<B>DNA Test Taker # 3:</B><BR>
						<span id=SpanCiteName3Old><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName3  id=RadioCiteName3Old value='Old'>use name at birth: <span id=CustomCiteName3Old></span><BR></span>
						<span id=SpanCiteName3New><input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName3 id=RadioCiteName3New value='New' checked>use current name: <span id=CustomCiteName3New></span><BR></span>
	 					<input type=radio  onclick="changeCousinFormat(0);" name=CustomCiteName3 id=RadioCiteName3Cust value='Cust'>use alias: <input type=text  onchange="changeCousinFormat(0);" id=CustomCiteName3Cust><BR>
						<span id=SpanWikiLink3><input type=checkbox checked onclick="changeCousinFormat(0);" name=UseWikiLink3 id=UseWikiLink3 value='Y'>link to profile <span id=WikiTreeProfile3></span><BR></span>						
					</span>

					<span id=NeedCustomCiteNameN style="display:none;">
					</span>


					<BR>
					</span>
					<HR/>
					<B>Cousin Format:</B><BR>
					<input type=radio name=cousinFormatRadio id=cousinFormatS value='S' onclick="changeCousinFormat(1);"> 2C3R 
					<input type=radio name=cousinFormatRadio id=cousinFormatM value='M' checked onclick="changeCousinFormat(2);"> 2nd cousin 3x removed  <BR>
					<input type=radio name=cousinFormatRadio id=cousinFormatL value='L' onclick="changeCousinFormat(3);">  second cousin three times removed 
					<BR><BR>
					<B>Extra Links:</B><BR>
					<input type=checkbox name=showRelConnLink id=showRelConnLink value='Y' checked onclick="changeCousinFormat(0);"> Add Relationship link to citation<BR>
					<input type=checkbox name=showGEDcomCompareLink id=showGEDcomCompareLink value='Y' checked onclick="changeCousinFormat(0);"> Add GEDcom Compare link to citation<BR>
					<input type=checkbox name=showDNAconfAppLink id=showDNAconfAppLink value='Y' onclick="changeCousinFormat(0);"> Add link to this DNA Confirmation app<BR>
					<input type=checkbox name=showWhitePaperLink id=showWhitePaperLink value='Y' onclick="changeCousinFormat(0);"> Add link to FTDNA white paper (if needed)<BR>
					<input type=checkbox name=showHaplogroupLink id=showHaplogroupLink value='Y' checked onclick="changeCousinFormat(0);"> Add Haplogroup links to citation (Y/mtDNA)
					
					<BR><BR>
					<B>Example Citation:</B><BR>
					<textarea id=CAsourceCitationCustom rows="12" cols="50"></textarea>

						</DIV>
					
					<div align=left>
				<HR><P align=center><B>ADD the DNA Confirmation for<BR><span style="font-size: 20;" id=Div7CAa>COMMON ANCESTOR</span>:</B></P> 
				<P>On WikiTree, the DNA Confirmation is done via the relationship status level, which is set from the <b>child's</b> edit page.<br>
					<UL>
						<LI>Go to <span id=Div7CAkid style='font-size: 16;'>Common Ancestor' child</span>'s <span style="background-color: orange;">&nbsp<b>Edit</b>&nbsp;</span> page.</LI>
						<LI><span id=Div7CAVerify1>VERIFY / </span>MARK the relationship<span id=AncS3></span> from <span id=Div7CAkid2 style='font-size: 16;'>Common Ancestor's child</span><BR>to <span id=Div7CAb>her mother</span><BR>as <B><U><span id=Div7ConfirmedConfident>confirmed with DNA</span></U></B> in the <b>Edit Family</b> column.
							<br>
							<span id=DNAimageCodeMother><img width=100% src="images/WhereMotherSection.png">
								<br><br> from the list of options ...<br>
								<img src="images/MotherConfirmedRadio.png">
								<br><br> choose <b>confirmed with DNA</b><br>
								<img src="images/MotherConfirmedDNAradiobtn.png">
							</span>
							<span id=DNAimageCodeFather><img width=100% src="images/WhereFatherSection.png">
								<br><br> from the list of options ...<br>
								<img src="images/FatherConfirmedRadio.png">
								<br><br> choose <b>confirmed with DNA</b><br>
								<img src="images/FatherConfirmedDNAradiobtn.png">
							</span>
							<span id=DNAimageCodeParents><img width=100% src="images/WhereParentsSection.png">
								<br><br> from the list of options ...<br>
								<img src="images/FatherConfirmedRadio.png"><img src="images/MotherConfirmedRadio.png">
								<br><br> choose <b>confirmed with DNA</b><br>
								<img src="images/FatherConfirmedDNAradiobtn.png"><img src="images/MotherConfirmedDNAradiobtn.png">
							</span>
							<span id=DNAimageCodeParentsConfident><img width=100% src="images/WhereParentsSectionConfident.png">
								<br><br> from the list of options ...<br>
								<img src="images/FatherConfidentRadio.png"><img src="images/MotherConfidentRadio.png">
								<br><br> choose <b>Confident</b><br>
								<img src="images/FatherConfidentDNAradiobtn.png"><img src="images/MotherConfidentDNAradiobtn.png">
							</span>



						</LI>
					</UL>



				<HR><B><span id=Div7CAVerify2>VERIFY / </span>ADD the SOURCE CITATION for COMMON ANCESTOR:</B><br/> 
				<P id=Div7CAVerify3>If there is already a source citation confirming the <span id=Div7CAVerifyRelationship>Paternal relationship Maternal relationship Paternal and Maternal relationships</span>, you don't need to add another one to <span id=Div7CAVerifyKid>the Common Ancestor's child</span>'s profile.  You can add more than one source citation if you like, but excessive numbers of citations add clutter and not value to the profile.</P>
				<UL id=theInfoParaTop ><LI><span id=Div7CAVerify4>Verify / </span>Add this source citation in the <b>== Sources ==</b> section of <span id=Div7CAkid3>the Common Ancestor's child</span>'s profile (<I>to explain how you made the DNA-confirmed conclusion</I>).<BR>
				<textarea id=CAsourceCitation rows=6 cols=80>Citation goes here</textarea>
				<input class="button" type="submit" value="COPY" onclick="copyToClipboard('CAsourceCitation');"><BR>&nbsp;</LI>
				<LI>Add an explanation in the <b>Explain your changes</b> area<br><I>(e.g.  DNA confirmation added with source citation)</I><br/>
					<img src="images/ExplainChanges.png"></LI>
					<LI> Click the <b>SAVE CHANGES</b> button
					</LI></UL>
				<div align=right id=TopVerifyMarkCheckboxDIV><input onclick='doVerifyMarkDone(this);'  textareaID=Top type=checkbox>Verified. DONE !</div>
				</div>

				
				<DIV id=BonusSection><HR><H2>BONUS !</H2>
				<P>With this DNA match, you can also add DNA Confirmation<span id=DIV7addS></span> for:<BR><BR> <I><span id=additionalNames> (List of additional people goes here)</span></I><br><br>
				<h2><input type=checkbox id=showExtras value='Y' onclick="showHideExtraInfo();">Show extra DNA confirmations</h2>
				<h2 id=ResearchNotesH2><input type=checkbox id=showResearchNotes value='Y' onclick="showHideResearchNotes();">Show DNA Research Notes</h2>
					
				<HR>
				<DIV id=ExtraInfo style="display:none;"></DIV>
				<DIV id=ResearchNotes style="display:none;">
					<HR>
					<H3>=== DNA Research Notes ===</H3>
					<textarea id="DNAresearchNotesText" rows="35" cols="80"></textarea>
					<input class="button" type="submit" value="COPY" onclick="copyToClipboard('DNAresearchNotesText');">

				</DIV>
				</DIV>
				<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;

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
	        <input type="hidden" name="appId" value="DNAconf">
	        <input type="hidden" name="returnURL" value="https://apps.wikitree.com/apps/clarke11007/DNAconf.php">
	        <input type="submit" class="button" value="Client Login">
	        </form>
	      </p>
	      
	    </div>


<P align=center>
	<BR/><BR/>
		Free Space Pages with background info re: latest round of updates:<BR/>
		<A target=_blank href="https://www.wikitree.com/wiki/Space:G2G_Post_Prep_Page">April 2024 - DNA Confirmation App update</A>  / 
		<A target=_blank href="https://www.wikitree.com/wiki/Space:DNA_Confirmation_Discussions">August 2024 - DNA Triangulation discussions</A>
	<BR/><BR/>

	<SPAN id=WhatsNewScrollNote  style="display:none;">Scroll down to read about What's New<br><br></SPAN>
	<A target=_blank href=index.php>Other apps by Greg Clarke</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>Contact the author</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1782243/dna-confirmation-app-version-3-00-update>Latest G2G post (19 August 2024)</A>  •  &nbsp;&nbsp; 
	<!-- <A target=_blank href=https://www.wikitree.com/g2g/1210942/new-app-dna-confirmation-citation-maker>G2G post1</A>  •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1247480/update-to-dna-confirmation-citation-maker-app>G2G post2</A>  &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://www.wikitree.com/g2g/1395703/another-update-to-dna-confirmation-app>G2G post3</A>  &nbsp;&nbsp; •  &nbsp;&nbsp;  -->
	<A onclick=toggleWhatsNew() ><u>What's New?</u></A> <br/>

	How to Videos: 
	<A target=_blank href=https://youtu.be/QH65lW0D870>V1 - Peter Roberts</A>&nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://youtu.be/dpJ-5Smdb44?t=174>V1 - Greg</A> &nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://youtu.be/EQVEX5VW6x0>Triangulation - Peter</A>&nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://youtu.be/LABITpLhC0Y>X-DNA</A>&nbsp;&nbsp; •  &nbsp;&nbsp; 
	<A target=_blank href=https://youtu.be/kDGukur0yCc>Y-DNA</A>
	

	<!-- <BR><A onclick="resetApp('debug');">debug</A> -->
	<br><br>
	<!-- <input type="radio" id=resetRadioBtn name=appMode onclick="resetApp('live');" >reset live version  <input type="radio" name=appMode onclick="resetApp('debug');" >debug version<br> -->
	<SPAN id=FAQsScrollNote  style="display:none;">Scroll down to read the FAQs<br></SPAN>
	<input type=submit class=submit id=showFAQs value='FAQs' onclick='toggleFAQs();'><br/><br/>
</P>

<DIV id=FAQs  style="display:none;">
<table border=0><tr><td width=25%>&nbsp;</td>
<td width=50% align=left><P>
<b>FREQUENTLY ASKED QUESTIONS</b><br/><br/>
<b>Q. What's the primary purpose of this app?</b><br/>
A. The primary purpose of this app is to generate a source citation to add to a WikiTree Profile when you are adding a DNA Confirmation, that is, when you are confirming that a parent-child relationship has been confirmed by DNA. <br><I>Big thanks to Mags Gaulden and Peter Roberts of the DNA Innovators team for helping with the development of this app!</I>

<br><br><b>Q. What is a DNA Confirmation?</b><br/>
A. DNA cannot <u>prove</u> relationships.  However, DNA can <u>confirm</u> relationships when they match up with properly researched genealogy.  

<br><br><b>Q. How do you do a DNA Confirmation on WikiTree?</b><br/>
A. On WikiTree, when a parent-child relationship is confirmed by a DNA match that lines up with properly researched genealogy, and the Most Recent Common Ancestor(s) [MRCA] based on the genealogy agrees with the relationship suggested by the DNA match - then - you indicate this by changing an option in the child's WikiTree Profile.<BR/>
In EDIT mode, in the top section (to the right), change the option for the Mother (or Father, or both) to indicate they are <B>confirmed with DNA</B><br>
<img src=images/MotherConfirmedRadio.png>   <br>
NOTE: After you do this - you need a SOURCE CITATION statement to explain why you clicked that radio button - that's what this app will create for you!


<br><br><b>Q. What is a DNA Confirmation <b>Citation</b>?</b><br/>
Basically, it’s a source citation that outlines why you indicated that a paternal or maternal relationship is confirmed by DNA.<UL>
<LI>The Citation includes the name of the DNA test takers, the company of the test, the <acronym title="Most Recent Common Ancestor(s)">MRCA</acronym>, and the redicted and actual relationship between matches.</LI>
<LI>The Citation allows others to verify results, and also allows future potential cousins to make new discoveries </LI></UL>
When you edit a profile, you select a radio button to update the relationship status between parent and child , then add a DNA Confirmation Citation statement in the <b>==&nbsp;Sources&nbsp;==</b> section 

<br><br><b>Q. What does the app do for you?</b><br/>
The app will create a DNA Confirmation Citation for you to use in your relative’s profile. 
<br><br><b>Q. How does it work?</b><br/>
The app will guide you through the process of creating a DNA Confirmation Citation asking for the information a piece at a time, then finally assembles the citation for you, and gives instructions on how  to do it.
<br><br><b>Q. Can you show me?</b><br/>
<UL>
<LI>View <A target=_blank href=https://youtu.be/QH65lW0D870>this video by Peter J Roberts</A> showing how he used the app to make a series of DNA Confirmation Citations based on a cousin match on GEDmatch.com</LI>
<LI>View <A target=_blank href=https://youtu.be/dpJ-5Smdb44?t=174>this video of Greg on WikiTree Live</A> demonstrating two examples - Example 1 with a DNA company match, and Example 2 with a fellow Wikitreer</LI>
</UL>



 
<br><br>If you have other questions you'd like answered, please <A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>contact the author!</A>

</td> <td width=25%></td></tr></table>
&nbsp;<br><br>
</DIV>
      
<DIV id=WhatsNew  style="display:none;">
<table border=0 align=center><tr><td width=25%>&nbsp;</td>
<td width=50% align=left><P align=center>
<span style="background-color: orange;"><b>&nbsp;What's New / App Updates&nbsp;</b></span><br/><br/>
<I>Many thanks to the many hours of consultation and feedback<BR>provided by Peter J Roberts, Mags Gaulden and John Kingman!<BR>Thanks also to beta-testers and bug-finders<BR>Emma, John, Ole, Loretta, Chris W, Dagmar, Jonny, Roger, Topher, Kylie, Jane, Charles, Bill V ... <BR>Murray, Betsy, Jim, Kirby, Mike, Todd, Riel, Ian, Kay, Audrey, William C, Geo Ford ... <BR>(your name could go here too!) ...</I><BR>
<br/><b>Version 3.12 - updated 14 Oct 2026</b><br/>
* fixed logic error with half cousins and Aliases (thanks Geo)
<br/><b>Version 3.11 - updated 13 Oct 2026</b><br/>
* fixed half relationship confirmation statements (half was sometimes omitted)<BR>
* Step 7 mini-tree shows the descendant choices made in Step 4<BR>
* Y DNA Match now checks that both DNA testers are male<BR>
* Step 7 Bonus citations will now include those in path from Common Ancestor to Alias, for WikiTree ancestors of Alias that are identified

<br/><b>Version 3.10 - updated 26 Feb 2026</b><br/>
* fixed DNA confirmation to use BioParents for all other matches
<br/><b>Version 3.09 - updated 25 Feb 2026</b><br/>
* fixed DNA confirmation to use BioParents for autosomal matches (Simple DNA Match)
<br/><b>Version 3.08 - updated 25 Jan 2026</b><br/>
* tweak to sibling relationship descriptor (partial fix)
<br/><b>Version 3.07 - updated 16 Dec 2025</b><br/>
* added appId to final two missing calls
<br/><b>Version 3.06 - updated 28 Nov 2025</b><br/>
* added appId to API call for database queries
<br/><b>Version 3.05 - updated 8 May 2025</b><br/>
* Tweak made to DNA Confirmation policy wording, re: position of source citations.
<br/><b>Version 3.04 - updated 7 April 2025</b><br/>
* Fixed relationship calculation for mother/father.
<br/><b>Version 3.03 - updated 5 April 2025</b><br/>
* Fixed bug with gender selection.
* Fixed bug with Direct Ancestor confirmations not going through to very final stage.
<br/><b>Version 3.02 - updated 25 September 2024</b><br/>
* Fixed bug - Simple DNA match - text for top ancestral couple's citation said "confident" when it should have been "confirmed".
<br/><b>Version 3.01 - updated 19 August 2024</b><br/>
* Minor tweak.
<br/><b>Version 3.00 - updated 19 August 2024</b><br/>
* Updated version released, with new version number and G2G post.
<br/><b>Version 2.52 - updated 17 August 2024</b><br/>
* Checked GEDmatch kit #s - do not pass GO unless one has been filled in for each DNA tester (when GEDmatch is selected as testing company).<br/>
* Added INVALID CITATION warning if there is a DNA Tester who died before DNA testing had become available
<br/><b>Version 2.51 - updated 16 August 2024</b><br/>
* Triangulation: Added False Positive Note when < 12 cM.<br/>
* Added logic to convert % shared to cM, and auto switch to extended 14 generations when < 25 cM.<br/>
<br/><b>Version 2.50 - updated 15 August 2024</b><br/>
* Triangulation: Added End Proviso to citation statements indicating tree completeness for all DNA testers.<br/>
<br/><b>Version 2.49 - updated 18 July 2024</b><br/>
* Triangulation: Removed false Common Ancestors found based on "hourglass" mini Tree shape (going through a common mid-tree ancestor).<br/>
<br/><b>Version 2.48 - updated 16 July 2024</b><br/>
* Triangulation: Added option to use 14 generations of ancestors for an extended search (vs. standard 10 generation search).<br/>
<br/><b>Version 2.47 - updated 15 July 2024</b><br/>
* Triangulation: Aliases set in Custom Settings (Step 7, gears icon), now used properly in all citations, in list of testers and relationship to MRCA statement.<br/>
<br/><b>Version 2.46 - updated 14 July 2024</b><br/>
* Fixed bugs re: private birthdates (sorting ancestors), extra GEDmatch comma, excessive citations warning with multiple run-on relationships.<br/>
<br/><b>Version 2.45 - updated 13 July 2024</b><br/>
* Fixed bugs that had creeped into Simple / Y / mtDNA matches citation wording.<br/>
* Hid option for child / grandchild of Primary WikiTree ID for non-Simple DNA Matches.<br/>
* Fixed some bugs with cousin format changes not changing.<br/>
<br/><b>Version 2.44 - updated 12 July 2024</b><br/>
* Fixed error checking for private person, no Ancestor/Descendant access in Simple DNA Match.<br/>
<br/><b>Version 2.43 - updated 5 July 2024</b><br/>
* Added floating MiniTree option at end of the process (Step 7), for visual reference.<br/>
<br/><b>Version 2.42 - updated 4 July 2024</b><br/>
* Updated source citation statement wording for Triangulation.<br/>
<br/><b>Version 2.41 - updated 2 July 2024</b><br/>
* Added DNA Research Notes for Triangulation.<br/>
<br/><b>Version 2.40 - updated 17 June 2024</b><br/>
* Added ability to add Aliases for additional Triangulation matches (4th tester and beyond),<br/>
for use by DNA testers without WikiTree profiles.<br/>
* Generate placeholder citation statements for valid Triangulation chains<br>
* Add option to show / copy DNA Research Notes (final step)<BR/>
<br/><b>Version 2.39 - updated 10 June 2024</b><br/>
* Added legend for Arrows at bottom of  Triangulation mini-tree.<br/>
* Updated GEDmatch and 23andMe and FTDNA sections of Step 6 - DNA Info - to accommodate > 3 DNA testers.<br/>
<br/><b>Version 2.38 - updated 8 June 2024</b><br/>
* Added &check; or &cross; summary below each DNA test taker, and a Proceed / Do not proceed summary statement at top of Triangulation mini-tree.<br/>
* Fixed logic for drawing arrows in Triangulation mini-tree.<br/>
<br/><b>Version 2.37 - updated 7 June 2024</b><br/>
* Adjusted Triangulation algorithm for narrowing down Common Ancestors (removing duplicates, but not too early in the process).<br/>
* Added logic and warnings in Triangulation for "died before DNA testing", and > 4th great grand parents<br/>
<br/><b>Version 2.36 - updated 29 May 2024</b><br/>
* Added app name and version number to citation statement.<br/>
* Added images to denote generation completeness indicators for Triangulation testers' family trees.<br/>
<br/><b>Version 2.35 - updated 22 May 2024</b><br/>
* Working on creating flexible mini-table that can take multiple (3 or more) testers.<br/>
* Added warning: DO NOT USE "IN DEVELOPMENT" version for live DNA Confirmations on profiles (except for testing purposes, on your own profile).<br/>
<br/><b>Version 2.34 - updated 20 May 2024</b><br/>
* First attempt at revising Triangulation: Finds multiple MRCAs for 3 testers.<br/>
* Drop Down menu for different MRCAs or Couples / also DropDown for Multiple Paths to MRCA per tester.<br/>
<br/><b>Version 2.33 - updated 18 May 2024</b><br/>
* Checked for invalid WikiTree ID in Simple DNA Match (fixed bug).<br/>
* Made First Names consistently set to "Preferred Name".<br/>
<br/><b>Version 2.32 - updated 17 May 2024</b><br/>
* Added support for BigY tests from FamilyTreeDNA for Y DNA Matches.<br/>
* Added support for YSTR+ matches from FamilyTreeDNA for Y DNA.<br/>
* Added links to Rob Spencer's Tracking Back website (scaledinnovations.com)<br/> on Y DNA info page and optionally (in settings) as link in citations.<br/>
* Fixed bugs / added similar features to Y DNA/mtDNA Confirmations (from recent Simple DNA match upgrades).<br/>
* e.g. Added (relationship to WikiTreer) option to Y DNA and mtDNA matches with aliases.<br/>
<br/><b>Version 2.31 - updated 16 May 2024</b><br/>
* Added ~~~~ signature to end of citation (converts to Name & Timestamp).<br/>
* Revised relationship link for Aliases (uses closest ancestor to alias on WikiTree),<BR/>and displays that relationship at beginning of citation.<br/>
<br/><b>Version 2.30 - updated 15 May 2024</b><br/>
* Added Legend for arrows to Direct Ancestors mini-tree.<br/>
* Added Warning message if any DNA tester passed away before 2000, when testing became available.<br/>
* Bug fix - thanks to Betsy Ko's sharp eyes!<br/>
<br/><b>Version 2.29 - updated 14 May 2024</b><br/>
* Added tracking option in final step (checkbox that turns "DONE" citations green).<br/>
* Added additional directions and wording to distinguish between <BR/>a) New Citations that need to be MARKED and ADDED, <BR/>from b) those that may pre-exist, and just need to be VERIFIED.<br/>
* Added note about excessive citations on a profile (adds clutter, not extra value). <BR/><I>Author's personal opinion: 3 source citations would be the max I'd want on a single profile!</I><br/>
<br/><b>Version 2.28 - updated 13 May 2024</b><br/>
* Multiple bug fixes found with intensive testing.<br/>
* Fixed DNA painter relationship links (for non-cousins).<br/>
<br/><b>Version 2.27 - updated 7 May 2024</b><br/>
* Fixed mini-tree and citation text for direct ancestor Simple Confirmations.<br/>
<br/><b>Version 2.26 - updated 6 May 2024</b><br/>
* Checked for parent/child status, no DNA confirmations for non-bio or uncertain parentages.<br/>
* Customized the connective arrows in MiniTree to indicate parent/child status visually <BR>(and Legend below to describe them).<br/>
<br/><b>Version 2.25 - updated 2 May 2024</b><br/>
* Checked for non-biological parent, and ignoring ancestors beyond on that branch.<br/>
<br/><b>Version 2.24 - updated 29 April 2024</b><br/>
* Link to latest G2G post added up top.<br/>
<br/><b>Version 2.23 - updated 16 April 2024</b><br/>
* Bug fixes.<br/>
* Add DNApainter links at bottom of Step 6 for Simple Confirmations.<br/>
* Add option to link to Relationship Finder (between DNA test takers).<br/>
* Add option to embed Compare link to GEDmatch, when both IDs defined.<br/>
* Autosaves last used options for Cousin format, and new link options<br/>(via a cookie in browser).<br/>
<br/><b>Version 2.22 - updated 18 February 2024</b><br/>
* Revise drawing of MiniTrees from Simple Confirmation.<br/>
* Search for multiple paths in Simple Confirmation.<BR>
<br/><b>Version 2.21 - updated 16 January 2024</b><br/>
* Fix crash in Triangulation using aliases.<br/>
* Complete switch of functions to use getPeople <BR>
<br/><b>Version 2.20 - updated 8 January 2024</b><br/>
* Updated to PHP8 (back end programming under the hood)<BR>
<br/><b>Version 2.19 - updated 14 Oct 2023</b><br/>
* FIXED THINGS. Switched to getPeople API.<BR>
<br/><b>Version 2.18 - updated 12 Jul 2023</b><br/>
* Tweaked bug in Simple Match with private parents of primary test taker.<BR>
<br/><b>Version 2.17 - updated 10 May 2023</b><br/>
* Tweaked relationship calculation to properly detect direct descendants / ancestors / siblings.<BR>
<br/><b>Version 2.16 - updated 1 March 2023</b><br/>
* Tweaked relationship calculation for child / grandchild of active WikiTreer.<BR>
<br/><b>Version 2.15 - updated 2 January 2023</b><br/>
* Tweaked relationship calculation for child / grandchild of active WikiTreer.<BR>
<br/><b>Version 2.14 - 22 December 2022</b><br/>
* Added check for Private / malformed WikiTree ID for Tester # 2.<BR>
* Added ability for DNA Tester # 1 in source citations to be <BR>the child or grandchild of an active WikiTreer (who manages their DNA kits)<BR>
<br/><b>Version 2.13 - 21 December 2022</b><br/>
* Corrected misleading error message when Private WikiTree ID has been entered.<BR>
<br/><b>Version 2.12 - 12 December 2022</b><br/>
* Fixed error found in Triangulation, side effect of X-chromosome logic - now both work properly. (Thanks Bill V!)<BR>
<br/><b>Version 2.11 - 11 December 2022</b><br/>
* Fixed logic finding ancestors with X-chromsomes (was letting some males into the AhnenTafel who didn't belong)<BR>
<br/><b>Version 2.10 - 8 November 2022</b><br/>
* Fixed logic with half-cousins in Triangulation<BR>
* Fixed wrong message (mark Mother as confirmed, when it should be Father)<BR>
* Corrected some cousin relationships incorrectly labelled<BR>
<br/><b>Version 2.09 - 20 September 2022</b><br/>
* Fixing things in Triangulation land ...<BR>
<br/><b>Version 2.08 - 19 August 2022</b><br/>
* In SIMPLE DNA MATCH: Able now to add existing WikiTree profiles in Mini-Tree<BR>
between MRCA and Alias/non-WikiTree DNA tester, <BR/> 
thus allowing for more Bonus Citations at the final stage.<BR/>
<br/><b>Version 2.07 - 14 August 2022</b><br/>
* Fixed two typos in calculating / naming great great granduncle / great great grandparents in specific mini tree with alias DNA tester<BR/>
<br/><b>Version 2.06 - 27 March 2022</b><br/>
* Removed reference to multiple segments in customize wording<BR/>
<br/><b>Version 2.05 - 15 March 2022</b><br/>
* Removed "Other DNA company" from drop down lists<BR/>
* Added "Geneanet" as a DNA Company option for Simple DNA Matches<BR/>
* Automatically load mitoYDNA, GEDmatch and FTDNA kit #s for DNA test takers, if available<BR/>
* Added links to mitoYDNA comparison page if mitoYDNA kit #s included for both test takers<BR/>
* Added links to GEDmatch comparison page if GEDmatch kit #s included for both test takers<BR/>
* Reconfigured X-DNA Match Intro screen to be more explicit about 2 options<BR/>
* Revised wording for MTDNA Matches (included Coding Region in citation)<BR/>
<br/><b>Version 2.04 - 12 March 2022</b><br/>
* How to Add Source Citations - brief outline added to opening page<BR/>
<br/><b>Version 2.03 - 1 March 2022</b><br/>
* Triangulation mini tree : closer branches drawn beside each other<BR/>
* Slight redesign of output while waiting for Triangulation to load
<br/><b>Version 2.02 - 28 Feb 2022</b><br/>
* Triangulation no longer allows you to continue <BR>if any pair of 3 test takers are 3rd cousins or closer.<BR>Will suggest a Simple DNA match, and give link to create that citation.<BR>
* Simple DNA mini-tree tweak - child/grandchild of DNA test takers greyed out
<br/><b>Version 2.01 - 25 June 2021</b><br/>
Added check for 7cM minimum DNA match length
<br/><b>Version 2.0 - 15 June 2021</b><br/>
Transferred Beta version features to Live version of app
<br/><b>Version 1.99.04 - 14 June 2021</b><br/>
Fixed bugs related to Customize Citations options<BR>
Fixed hanging while doing MiniTree or Bonus Citations
<br/><b>Version 1.99.03 - 13 June 2021</b><br/>
More Customize Citation features (top of STEP 7)<BR>
* Added option to show/hide link to Profile for WikiTreer DNA testers<BR>
* Added option to use birthname or current name for WikiTreer DNA testers
<br/><b>Version 1.99.02 - 11 June 2021</b><br/>
Added option to <U>Add Note</U> to end of each citation (STEP 6).<BR>
Added option to <U>Customize Chromosome Segment</U>(s) citation wording.<BR>(STEP 6 - Triangulation / X-chromosome).<BR>
Added option to <U>Customize Citations</U> to allow<BR>for Alias instead of DNA Test Taker actual name (STEP 7).<BR>
Moved option to change Cousin Format into Customize Citations area.
<br/><b>Version 1.99 - 10 June 2021</b><br/>
Fixed bug in EDIT page links to profiles with two word last names
<br/><b>Version 1.98 - 9 June 2021</b><br/>
Edited Final Step instructions to include WikiTree IDs for profiles involved,<BR>and broke up long-winded instruction statement<BR>into digestable chunks,<BR>for clarity.
<br/><b>Version 1.97 - 8 June 2021</b><br/>
Fixed bug that prevented Bonus citations when using an alias and <BR>the WikiTree ID for the closest ancestor is the MRCA.
<br/><b>Version 1.96 - 4 June 2021</b><br/>
Fixed bug that sometimes prevented Triangulation matches from being found<BR>and sometimes prevented BONUS citations.<BR>
Added Cousin Format option for citations
<br/><b>Version 1.95 - 3 June 2021</b><br/>
Added relationship summary at the bottom of Mini-Tree for Triangulation and new functions.
<br/><b>Version 1.94 - 2 June 2021</b><br/>
Added Gender check dialog, needed when accessing semi-private profiles, <BR>for standard citations and specific DNA confirmations based on X and Y chromosomes.<BR>Fixed extra citations where Alias was used for 2nd (or 3rd) test taker so that optional relationship is described properly.
<br/><b>Version 1.93 - 29 May 2021</b><br/>
Changed message for MyHeritage Triangulation instructions <BR>- independent verification not needed if following marked Triangulated Segments from One-to-many Chromosome Browser<BR>
Added counter on "Contacting WikiTree ... " waiting message.<BR>
Switched to Usual Names / Current Last Names for DNA testers names in mini-trees<BR>
Fixed bug causing NO Bonus Citations to appear (mostly - if you can still reproduce this - email Greg !) 
<br/><b>Version 1.92 - 28 May 2021</b><br/>
Changed DNA testers names in citations to use Usual Name and Current Last Name.<BR>Fixed issue with Simple DNA Match returning to MRCA navigation bug<BR>as well as detritus from previous mini-tree in some cases.
<br/><b>Version 1.91 - 14 May 2021</b><br/>
Added warning messages, tweaked citation statements,<BR>and logic to correctly navigate app<BR>
Ready for beta testing !
<br/><b>Versions 1.80 - 1.90 - early May 2021</b><br/>
Added layouts for Y-DNA, mtDNA, X-chromosome (pair only) confirmations
Added layout for Triangulation and X-chromosome (trio) confirmations
<br/><b>Version 1.07 - 5 May 2021</b><br/>
Combined Paternal & Maternal citations into one DNA confirmation citation, for top level MRCA ancestral couple, where appropriate.
<br/>Added ability to go one generation back, and thus create confirmations for 2C2R and 1C3R
<br/>Added logic to check for relationships that are "chartable" by mini-tree, but not close enough for simple confirmation (3C1R, 4C, half 2C2R, half 1C3R)
<br/><b>Version 1.06 - 16 April 2021</b><br/>
Fixed logic to deal with scenario where second test taker is direct ancestor.
<br/><b>Version 1.05 - 16 April 2021</b><br/>
Added logic to recognize half-relationships if only a single common ancestor.
<br/><b>Version 1.04 - 16 April 2021</b><br/>
Tweaked formatting for citations so they stand out better.  <br>Added optional checkbox to display relationship between DNA testers, Profile person, and MRCA.
<br/><b>Version 1.03 - 7 April 2021</b><br/>
Tweaked wording for citations.  <br>Added extra notes on how to find cM for 23andMe customers, <Br>and how to find # of segments for FTDNA customers.
<br/><b>Version 1.02 - 28 March 2021</b><br/>
Fixed bug where first cousins on WikiTree had relationships misrepresented.<br>Added What's New section
<br/><b>Version 1.01 - 28 March 2021</b><br/>
Fixed spacing, missing NEXT button. Version # added.
<br/><b>Version 1.00 - 27 March 2021</b><br/>
Original app launch.
<br><br><br><A onclick=toggleWhatsNew() ><u>Close up What's New?</u></A> <br/>

</td> <td width=25%></td></tr></table>
&nbsp;<br><br>
</DIV>
     

</BODY>

</HTML>
