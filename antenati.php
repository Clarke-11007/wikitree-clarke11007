<?php


$prefill = $_GET['prefill'];
$site = $_GET['site'];
$quick = $_GET['quick'];
date_default_timezone_set('UTC');
// get contents of a file into a string
function readDataIntoArray( $TheFileName )  {
	$TheArray = array();

	$filename = $TheFileName; //"data/anciteList.dat";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	$TheArray = explode(chr(10), $contents);
	return $TheArray;
};
 
// $ListArray = readDataIntoArray("data/anciteList.dat");
// $TitlesArray = readDataIntoArray("data/anciteTitles.dat");
// $DetailsArray = readDataIntoArray("data/anciteDetails.dat");

// $theURL = "https://www.antenati.cultura.gov.it/ark:/12657/an_ua35983372/5Y4qADg" ;
// 	$page = file_get_contents($theURL);
// 	$JSONobject = json_decode($page);
// echo "<textarea rows=100 cols=100> " . $page . "</textarea>";

/*
<article>
      <header>
        <h2>
          Registro: Registro 87 
        </h2>
      </header>
      <hr>
      
      <div class="detail-registry">
        <div>
          <p>
                          <a href="/search-registry/?tipologia=Nati&serie=35937822">
                <strong>Nati</strong>
              </a>
                          </strong>
          </p>  
          <p>
            <a href="/search-registry/?fondo=35937699&descrizione=Stato civile italiano">
                Stato civile italiano</a>
              >  
            <a href="/search-registry/?serie=35937822&descrizione=Malito">
                Malito</a>
          </p>
          <p>
            <strong>Conservato da:</strong>
            <a href="/archivio/archivio-di-stato-di-cosenza" title="">
              Archivio di Stato di Cosenza            </a>
          </p>        
          <p>
            <strong>Comune/Località:</strong> 
            <a href="/search-registry/?localita=Malito">
              Malito            </a>
          </p>
                        <p>
                <strong>Segnatura attuale:</strong> 
                87              </p>
                  </div>      
        <div>
                  </div>
        <aside>
          <!--
          <div class="item-save">
            <a href="#" title="">
              <i class="far fa-bookmark fa-fw fa-lg"></i> Salva            </a>
          </div>
          -->          
        </aside>
      </div>
      
    </article>
    */

?>
<HTML>
<HEAD>
	<TITLE>Antenati Citer</TITLE>
	<LINK REL="stylesheet" TYPE="text/css" HREF="https://www.wikitree.com/A.g2g,,_qa-theme,,_WikiTree,,_qa-styles.css,,q1.8.3+css,,_main-new.css,Mcc.yioqhluBJs.css.pagespeed.cf.QfgWhteQbd.css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

	<script type="text/javascript" language="javascript">

		
		var categoryArray = [];
		var titlesArray = [];
		var sourceInfoArray = [];

		
		// var titlesArray = <?php echo json_encode($TitlesArray) ?>;
		// var detailsArray = <?php echo json_encode($DetailsArray) ?>;
		var numCitations = 0;

		var testString = "Looking for clues? Yes we are!";
			// alert (testString.search("\\?"));

		$(document).ready(function(){ 
			console.log("READY FREDDY!");
			if (gupSafe('r') > '') {
				console.log("GUP R = " , gupSafe('r'));
				if (gupSafe('r') == 'na') {
					// nothing to see here
				} else {
					document.getElementById('RecordURL').value = gupSafe('r');
					console.log("Should try and load ", gupSafe('r'));
					findAntenatiRecord();
				}

			} else {
				console.log("No GUPPIES (r)");
			}
		});

		function gupSafe(name) {
			// Reads the requested parameter from the URL
			// from http:www.netlobo.com/url_query_string_javascript.html
			/*
			Most of the server-side programming languages, like PHP, ASP, or JSP, give you easy access to parameters in the query string of a URL. Javascript does not give you easy access. With javascript you must write your own function to parse the window.location.href value to get the query string parameters you want.
			*/
			name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
			var regexS = "[\\?&]"+name+"=([^&#]*)";
			var regex = new RegExp( regexS );
			var results = regex.exec(window.location.href);
			if( results == null ){
				return "na";
			}
			else {
				//trace(name +" is "+results[1]);
				let safeResults = results[1];
				console.log("GUP: original: " , safeResults)
				safeResults = safeResults.replace(/<.*?>/g, "");
				safeResults = safeResults.replace(/</g, "&lt;");
				safeResults = safeResults.replace(/>/g, "&gt;");
				safeResults = safeResults.replace(/\(/g, "");
				safeResults = safeResults.replace(/\)/g, "");
				console.log("GUP: final: " , safeResults)
				return safeResults;

			}
		}

		function toggleIncludeDate() {
			var IncludeDateID = document.getElementById('IncludeDate');
			var AccessedID = document.getElementById('Accessed');
			if (IncludeDateID.checked) {
				// alert("INCLUDE the date now!");
				AccessedID.disabled = false;
			} else {
				// alert("Lose the date and whole 'Accessed on' tagline.");
				AccessedID.disabled = true;
			}
		}

		function trimString(str) {
			let newString = str;
			for (var i = 0; i < newString.length; i++) {
				console.log(i,newString[i], newString.charCodeAt( i) );
			}
			while ((newString.substring( newString.length - 1 ) == " " || newString.substring( newString.length - 1 ) == "\r"|| newString.substring( newString.length - 1 ) == "\t" || newString.substring( newString.length - 1 ) == "\n" ) && newString.length > 1) {
				newString = newString.substring(0, newString.length - 1);
			}
			while ((newString.substring( 0,1 ) == " " || newString.substring( 0,1 ) == "\r"|| newString.substring( 0,1 ) == "\t" || newString.substring( 0,1 ) == "\n" ) && newString.length > 1) {
				newString = newString.substring(1);
			}
			return newString;
		}

		function processRawInfoFromAntenati () {
			var RawInfoFromAntenatiTextArea =  document.getElementById('RawInfoFromAntenati');
			var data = RawInfoFromAntenatiTextArea.value;

			var ArchiveNameDIV =  document.getElementById('ArchiveName');
			var EraNameDIV =  document.getElementById('EraName');
			var TownNameDIV =  document.getElementById('TownName');
			var RecordTypeDIV =  document.getElementById('RecordType');
			var RegisterNameDIV =  document.getElementById('RegisterName');

			var ImageNumDIV =  document.getElementById('ImageNum');
			var YearNumDIV =  document.getElementById('YearNum');

			let whereMirador = data.indexOf("Mirador");
			let whereGThan = data.indexOf(">", whereMirador);
			// let theArticleText = data.substring(articleBegins, articleEnds);

			let goodStuff = data.substring(data.indexOf("\n", whereMirador ) + 1, data.indexOf("\n", whereMirador + 15));

			console.log("goodStuff:\n", goodStuff);

			let goodBits = goodStuff.split(">");
			let bitsAndDIVs = [ArchiveNameDIV, EraNameDIV, TownNameDIV, RegisterNameDIV];
			for (var i = 0; i < goodBits.length; i++) {
				bitsAndDIVs[i].value = goodBits[i].trim();
			}

			let typesOfRecords = ["Nati","Matrimoni","Morti","Processetti"];
			let dataBeforeMirador = data.substring(0, whereMirador);

			for (var i = 0; i < typesOfRecords.length; i++) {
				let theTypeIndex = dataBeforeMirador.indexOf(typesOfRecords[i]);
				console.log(typesOfRecords[i] , theTypeIndex);
				if (theTypeIndex > -1) {
					RecordTypeDIV.value = typesOfRecords[i];
				}
			}

			let pageNumbers = data.match(/\d+ di \d+/);
			if (pageNumbers.length > 0) {
				ImageNumDIV.value = pageNumbers[0];
			}

			let whereDatazione = data.indexOf("Datazione");
			if (whereDatazione > -1) {
				let theYearString = data.substring(whereDatazione).match(/\d\d\d\d/);
				if (theYearString.length > 0) {
					YearNumDIV.value = theYearString[0];
				}

			}
			


		}

		function processTheAntenatiData(data) {
			console.log( "processTheAntenatiData:",data); 
			var ArchiveNameDIV =  document.getElementById('ArchiveName');
			var EraNameDIV =  document.getElementById('EraName');
			var TownNameDIV =  document.getElementById('TownName');
			var RecordTypeDIV =  document.getElementById('RecordType');
			var RegisterNameDIV =  document.getElementById('RegisterName');

			let articleBegins = data.indexOf("<article>");
			let articleEnds = data.indexOf("</article>");
			let theArticleText = data.substring(articleBegins, articleEnds);

			console.log("ARTICLE:\n", theArticleText);

			let registroBegins = theArticleText.indexOf("Registro:");
			let registroEnds = theArticleText.indexOf("</h2>", registroBegins);
			console.log("REG ENDS: " , registroEnds);
			let registro = theArticleText.substring(registroBegins + 10 , registroEnds).replace(/  /g, "");
			if (registroEnds < 0) {registro = "";}

			let tipologiaBegins = theArticleText.indexOf("tipologia=");
			let tipologiaEnds = theArticleText.indexOf("&", tipologiaBegins);
			let tipologia = theArticleText.substring(tipologiaBegins + 10 , tipologiaEnds); //.replace(/  /g, "");
			if (tipologiaEnds < 0) {tipologia = "";}

			let fondoBegins = theArticleText.indexOf("fondo=");
			let fondoReallyBegins = theArticleText.indexOf('descrizione=', fondoBegins);
			let fondoEnds = theArticleText.indexOf('"', fondoReallyBegins);
			let fondo = theArticleText.substring(fondoReallyBegins + 12 , fondoEnds); //.replace(/  /g, "");
			if (fondoEnds < 0) {fondo = "";}

			let archivioBegins = theArticleText.indexOf("/archivio/");
			let archivioReallyBegins = theArticleText.indexOf('>', archivioBegins);
			let archivioEnds = theArticleText.indexOf('<', archivioReallyBegins);
			let archivio = theArticleText.substring(archivioReallyBegins + 1 , archivioEnds); //.replace(/  /g, "");
			if (archivioEnds < 0) {archivio = "";}

			// let localitaBegins = theArticleText.indexOf("localita=");
			let localitaBegins = theArticleText.indexOf("descrizione=" , fondoEnds);
			let localitaEnds = theArticleText.indexOf('"', localitaBegins);
			let localita = theArticleText.substring(localitaBegins + 12 , localitaEnds); //.replace(/  /g, "");
			if (localitaEnds < 0) {localita = "";}

			console.log("Registro: ", registro, "*" + registro.substring(registro.length - 1) + "*" , "#" + trimString(registro) + "#" );
			RegisterNameDIV.value = trimString(registro);
			RecordTypeDIV.value = tipologia;
			EraNameDIV.value = fondo;
			ArchiveNameDIV.value = trimString(archivio);
			TownNameDIV.value = localita;

			document.getElementById("OfProcessettiEndNote").style.display = "none";
			document.getElementById("EventTypeTR").style.display = "none";
			document.getElementById("EventYearTR").style.display = "none";

			if (tipologia.indexOf("rocessetti") > -1) {
				document.getElementById("OfProcessettiEndNote").style.display = "revert";
				document.getElementById("EventTypeTR").style.display = "revert";
				document.getElementById("EventYearTR").style.display = "revert";
			}




		}

		async function findAntenatiRecord() {
			var RecordURLdiv = document.getElementById('RecordURL');
			if (RecordURLdiv.value > ""){
				console.log("LangCheck 1: " , RecordURLdiv.value);
				let url = RecordURLdiv.value.replace(/\?lang=../g,"");
				console.log("LangCheck 2: " , url);
				console.log("LangCheck 3: " , RecordURLdiv.value.replace(/\?lang=../g,"") );
				var WhereSELECTORdiv = document.getElementById('WhereSELECT');
				WhereSELECTORdiv.value = "---";
				console.log("GET CONTENTS OF THE ANTENATI RECORD NOW FOR :", RecordURLdiv.value);

				$.post('php/getContents.php', { url: url }, function(data) {
					console.log("SUCCESS:");
				    // document.getElementById('somediv').innerHTML = data;    
				    processTheAntenatiData(data);    
				});
				 

			} else {
				console.log("CAN'T FIND THE ANTENATI RECORD FOR AN EMPTY STRING", RecordURLdiv.value);
			}

		}
		async function findAntenatiRecordV2() {
			var RecordURLdiv = document.getElementById('RecordURL');
			if (RecordURLdiv.value > ""){
				let url = RecordURLdiv.value.replace(/\?lang=../g,"");
				var xhr = new XMLHttpRequest();
				console.log("FIND THE ANTENATI RECORD NOW FOR :", RecordURLdiv.value);
				xhr.open("GET", url, true);
				// xhr.responseType = 'application/json';
				xhr.onload = function () {
				  // Successful request
				  if (xhr.status == 200) {
				    console.log('ANTENATI success');
				    console.log(xhr);
				  }
				};
				xhr.onerror = function () {
				  // Crossdomain request denied
				  if (xhr.status === 0) {
				    console.log(xhr.response)
				  }
				};

				// xhr.crossDomain = true;
				// xhr.withCredentials = true;
				xhr.send();

			} else {
				console.log("CAN'T FIND THE ANTENATI RECORD FOR AN EMPTY STRING", RecordURLdiv.value);
			}

		}
		async function findAntenatiRecordV1() {
			var RecordURLdiv = document.getElementById('RecordURL');

			if (RecordURLdiv.value > ""){
				const myInit = {
				  method: 'POST',
				  // headers: myHeaders,
				  mode: 'no-cors',
				  cache: 'default'
				};
				console.log("FETCH THE ANTENATI RECORD NOW FOR :", RecordURLdiv.value);
				const response = await fetch(RecordURLdiv.value, myInit).then( (data) => console.log("Data:", data));
				console.log("FOUND the ANTENATI RECORD - all loaded now !")
				console.log("Response:",response);					
				// console.log("Blob:", response.blob());					
				// console.log("Data:", response.data );					
			} else {
				console.log("CAN'T FIND THE ANTENATI RECORD FOR AN EMPTY STRING", RecordURLdiv.value);
			}
		}

		function findAntenatiRecordV0() {
			var RecordURLdiv = document.getElementById('RecordURL');
			
			console.log("FIND THE ANTENATI RECORD NOW FOR :", RecordURLdiv.value);
			var request = $.ajax({
				url: RecordURLdiv.value,
				crossDomain: true,
				xhrFields: { withCredentials: true },
				type: 'POST',
				dataType: 'json',
				
				// LOAD ALL THE INFO about the CENTRAL PERSON and ALL THEIR DIRECT RELATIVES / IMMEDIATE FAMILY MEMBERS (1 step from them)
				// data: { 'action': 'getRelatives', 'keys':  allKeysString , 'fields': "Id, Name, Gender, IsLiving, FirstName, MiddleName,  LastNameAtBirth, LastNameCurrent, RealName, Prefix, Suffix, BirthLocation, DeathLocation, BirthDate, DeathDate,Privacy, DataStatus", 'getParents':'false','getSiblings':'false','getChildren':'false','getSpouses':'false', 'format': 'json' },
				data: {   'format': 'json' },

				success: function(data) { 
					var theJason = JSON.stringify(data);
					console.log("FOUND the ANTENATI RECORD - all loaded now !")
					console.log(data);					
					console.log("theJason:",theJason);
				}

			});

		}

		function parseAntenatiSource() {

		}

		function copyCitation() {
		  /* Get the text field */
		  var copyText = document.getElementById("Citation");

		  /* Select the text field */
		  copyText.select();
		  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		  /* Copy the text inside the text field */
		  document.execCommand("copy");

		  /* Alert the copied text */
		  alert("Copied the text: " + copyText.value);
		}

		function clearAll() {

			var RecordURLdiv = document.getElementById('RecordURL');
			var PersonDIV = document.getElementById('PersonName');
			var AttoNumDIV = document.getElementById('AttoNum');
			var ImageNumDIV = document.getElementById('ImageNum');
			var YearNumDIV = document.getElementById('YearNum');
			var WhereSELECTORdiv = document.getElementById('WhereSELECT');


			var ArchiveNameDIV =  document.getElementById('ArchiveName');
			var EraNameDIV =  document.getElementById('EraName');
			var TownNameDIV =  document.getElementById('TownName');
			var RecordTypeDIV =  document.getElementById('RecordType');
			var RegisterNameDIV =  document.getElementById('RegisterName');
			
			var FinalNoteID = document.getElementById('FinalNote');
			var CitationID = document.getElementById('Citation');
			// var SubNLs4BRID = document.getElementById('SubNLs4BR');
			// var IncludeDateID = document.getElementById('IncludeDate');
			// var RefTypeSimpleID = document.getElementById('RefTypeSimple');
			// var RefTypeNamedID = document.getElementById('RefTypeNamed');
			// var RefTypeNoneID = document.getElementById('RefTypeNone');
			// var IncludeReqSubNoteID = document.getElementById('IncludeReqSubNote');

			var DIVsArray = [RecordURLdiv,PersonDIV,AttoNumDIV,ImageNumDIV,YearNumDIV,WhereSELECTORdiv,ArchiveNameDIV,EraNameDIV,TownNameDIV,RecordTypeDIV,RegisterNameDIV,FinalNoteID,CitationID]; //,SubNLs4BRID,IncludeDateID,RefTypeSimpleID,RefTypeNamedID,RefTypeNoneID,IncludeReqSubNoteID];

			for (var i = 0; i < DIVsArray.length; i++) {
				DIVsArray[i].value = "";
				// DIVsArray[i].innerHTML = "";
			}

		}

		function quickFill() {
			var QuickID = document.getElementById('Quick');
			var SourceID = document.getElementById('Source');
			var DetailsID = document.getElementById('Details');
			//global TitlesArray;
			var whichOptionValue = 1 + Number(QuickID.value);
			var titleForQuickFill = QuickID.options[whichOptionValue].text; 
			// alert(titleForQuickFill);
			for (var i = 0; i < titlesArray.length;  i++) {
				if (titlesArray[i] == titleForQuickFill){
						SourceID.innerHTML =  sourceInfoArray[i];//"and here too";
						// DetailsID.innerHTML = detailsArray[QuickID.value];//"and here too";
				}
			}

		}

		function quickCatChange() {
			var CatID = document.getElementById('Cat');
			var QuickID = document.getElementById('Quick');
			var SourceID = document.getElementById('Source');
			var DetailsID = document.getElementById('Details');
			//global TitlesArray;
			// alert (CatID.value);
			// alert (catsArray[ (CatID.value) ]);
			var theCat =  (catsArray[ (CatID.value) ]);
			var theOptions = QuickID.options;

			var j = 0; // index to use for refined option list
			for (var i = 0; i < titlesArray.length;  i++) {
				if (categoryArray[ i ] == theCat || theCat == "ALL") {
					// alert( titlesArray[i]);
					j++;
					theOptions[j].text = titlesArray[i];
				}
			}
			if (j==1) {
				theOptions[1].selected = true;
				quickFill();
			} else {
				theOptions[0].selected = true;
			}

			for (var i = j+1;i <= titlesArray.length;  i++) {
				theOptions[i].text = "";
			}


				// SourceID.innerHTML =  sourceInfoArray[QuickID.value];//"and here too";
				// DetailsID.innerHTML = detailsArray[QuickID.value];//"and here too";


		}

		function compileCitation() {
			var RecordURLdiv = document.getElementById('RecordURL');
			var PersonDIV = document.getElementById('PersonName');
			var AttoNumDIV = document.getElementById('AttoNum');
			var ImageNumDIV = document.getElementById('ImageNum');
			var YearNumDIV = document.getElementById('YearNum');
			var WhereSELECTORdiv = document.getElementById('WhereSELECT');


			var ArchiveNameDIV =  document.getElementById('ArchiveName');
			var EraNameDIV =  document.getElementById('EraName');
			var TownNameDIV =  document.getElementById('TownName');
			var RecordTypeDIV =  document.getElementById('RecordType');
			var RegisterNameDIV =  document.getElementById('RegisterName');
			// var TypeID = document.getElementById('Type');
			// var RecordURLID = document.getElementById('RecordURL');
			// var ShareableURLID = document.getElementById('ShareableURL');
			var FinalNoteID = document.getElementById('FinalNote');
			var CitationID = document.getElementById('Citation');
			var SubNLs4BRID = document.getElementById('SubNLs4BR');
			var IncludeDateID = document.getElementById('IncludeDate');
			var RefTypeSimpleID = document.getElementById('RefTypeSimple');
			var RefTypeNamedID = document.getElementById('RefTypeNamed');
			var RefTypeNoneID = document.getElementById('RefTypeNone');
			var IncludeReqSubNoteID = document.getElementById('IncludeReqSubNote');

			var AttiType = 'Atti di Nascita';
			var Prefix = "";
			var theArchive = "";
			var theEra = "";
			var theTown = "";
			var theYear = "";
			var theType = "";
			var theRegNum = "";


			let monthsArray = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

			let todaysDate = new Date();
			let theDateAccessed =  todaysDate.getDate() + " " + monthsArray[ todaysDate.getMonth() ] + " "
			 + todaysDate.getFullYear();

			numCitations += 1;

			refName = "Antenati" ;
			if (RecordTypeDIV.value > '') {
				refName = (RecordTypeDIV.value).replace(/ /g, "").replace(/,/g, "");
			}
			  
			if (refName == "Antenati")  {
				refName +=  numCitations;
			}
			// alert(DetailsID.innerHTML);
			// alert(DetailsID.value);
			ErrorNote = "";

			ExtraAsterisk = "";
			if (RefTypeSimpleID.checked) {
				tempSRC = "<ref>";
			} else if (RefTypeNamedID.checked) {
				tempSRC =  "<ref name='" + refName + "'>" ; 
			} else {
				tempSRC = "* ";
				ExtraAsterisk = "*";
			}

			
			if (RecordTypeDIV.value.indexOf("Nati") > -1) {
				Prefix = "Birth of ";
				AttiType = 'Atti di Nascita';
			} else if (RecordTypeDIV.value.indexOf("Mort") > -1) {
				Prefix = "Death of ";
				AttiType = 'Atti di Morte';
			} else if (RecordTypeDIV.value.indexOf("rocessetti") > -1) {

				Prefix = document.getElementById("EventYearNum").value + " "; 

				if (document.getElementById("EventTypeBaptism").checked) {
					Prefix += "Baptism of ";
				} else if (document.getElementById("EventTypeBirth").checked) {
					Prefix += "Birth of ";
				} else if (document.getElementById("EventTypeMarriage").checked) {
					Prefix += "Marriage of ";
				} else if (document.getElementById("EventTypeDeath").checked) {
					Prefix += "Death of ";
				} else if (document.getElementById("EventTypeEvent").checked) {
					Prefix += "Event of ";
				} else  {
					Prefix += "Event : ";
				}

				AttiType = 'Atti di Matrimonio / Processetti - ' + YearNumDIV.value;
				

			} else if (RecordTypeDIV.value.indexOf("Matrimon") > -1) {
				Prefix = "Marriage of ";
				AttiType = 'Atti di Matrimonio';
			}

			if (ArchiveNameDIV.value > "") {theArchive = ArchiveNameDIV.value;}
			if (EraNameDIV.value > "") {theEra = EraNameDIV.value;}
			if (TownNameDIV.value > "") {theTown = TownNameDIV.value;}
			if (YearNumDIV.value > "") {theYear = YearNumDIV.value;}
			if (RecordTypeDIV.value > "") {theType = RecordTypeDIV.value;}
			if (RegisterNameDIV.value > "") {theRegNum = RegisterNameDIV.value;}
			// <ref name=Nati>Portale Antenati, ''Atti di Nascita'', '''Birth of Raffaele Macchione'''.  Accessed on 2022-08-02  from https://www.antenati.cultura.gov.it/  - Archivio di Stato di Cosenza - Marzi -  1810 - Nati - registro 2 - atto 52 -  [https://www.antenati.cultura.gov.it/ark:/12657/an_ua35972529/LeN37Jy right side of  image 28 of 38]</ref>

			tempSRC +=  "Portale Antenati, ''" + AttiType + "'', '''" + Prefix + PersonDIV.value + "'''. ";
			tempSRC += "Accessed on " + theDateAccessed + " from antenati.cultura.gov.it  Source: ";
			if (theArchive > '') { tempSRC += theArchive + " - " ;}
			if (theEra > '') { tempSRC += theEra + " - " ;}
			if (theTown > '') { tempSRC += theTown + " - " ;}
			if (theYear > '') { tempSRC += theYear + " - " ;}
			if (theType > '') { tempSRC += theType + " - " ;}
			if (theRegNum > '' && theRegNum != theYear ) { tempSRC += theRegNum + " - " ;}
			if (AttoNumDIV.value > '') { tempSRC += "Atto " + AttoNumDIV.value + " - " ;}

			tempSRC += "[" + RecordURLdiv.value.replace(/\?lang=../g,"") + " " + WhereSELECTORdiv.value + "image " + ImageNumDIV.value + "]";

			     

			if (FinalNoteID.value > '') {
				FinalNoteText = FinalNoteID.value;
				whereNL = FinalNoteText.search('\n');

				if (SubNLs4BRID && SubNLs4BRID.checked){
					while (whereNL > -1 ) {
						NewFinalNoteText = FinalNoteText.substr(0, whereNL) + "<br/>" + FinalNoteText.substr(whereNL+1);
						FinalNoteText = NewFinalNoteText;
						whereNL = FinalNoteText.search('\n');
					}
				}
				tempSRC += "\n" + ExtraAsterisk + ExtraAsterisk + "<blockquote>" + FinalNoteText + "</blockquote>";
			}

			if (RefTypeSimpleID.checked || RefTypeNamedID.checked) {
				tempSRC += " </ref>";				
			}

			// if (ErrorNote == "BadAntenatiRecord") {
			// 	CitationID.value = "Could not assemble the Antenati Record template for this citation.\n\nPlease verify the record type (indexed record / source image / tree / profile / media) as well as the Record URL.\n\n" + tempSRC;
			// } else if (ErrorNote == "BadAntenatiImage") {
			// 	CitationID.value = "Could not assemble the Antenati Image template for this citation.\n\nPlease verify the record type (indexed record / source image / tree / profile / media) as well as the Record URL.\n\n" + tempSRC;
			// } else if (ErrorNote == "BadAntenatiTree") {
			// 	CitationID.value = "Could not assemble the Antenati Tree template for this citation.\n\nPlease verify the record type (indexed record / source image / tree / profile / media) as well as the Record URL.\n\n" + tempSRC;
			// } else if (ErrorNote == "BadAntenatiProfile") {
			// 	CitationID.value = "Could not assemble the Antenati Tree template for this Profile Person citation.\n\nPlease verify the record type (indexed record / source image / tree / profile / media) as well as the Record URL.\n\n" + tempSRC;
			// } else if (ErrorNote == "BadAntenatiMedia") {
			// 	CitationID.value = "Could not assemble the Antenati Tree Media template for this citation.\n\nPlease verify the record type (indexed record / source image / tree / profile / media) as well as the Record URL.\n\n" + tempSRC;
			// } else {
				CitationID.value = tempSRC;
			// }

			//alert(tempSRC);
		}
		// document.getElementById("calculate").onclick = function() {
			// document.getElementById('Citation').innerHTML = "HI there - Cite this!";
		  // compileCitation();
		function highlightThisTD(num) {
			// alert(num);
			document.getElementById('TDoption1').style = 'background-color:white;';
			document.getElementById('TDoption2').style = 'background-color:white;';
			document.getElementById('TDoption3').style = 'background-color:white;';
			document.getElementById('TDoption4').style = 'background-color:white;';
			document.getElementById('TDoption5').style = 'background-color:white;';
			document.getElementById('TDoption' + num).style = 'background-color:yellow;';
			document.getElementById('RecordType' + num).checked = true;
		}

		

	</script>
</HEAD>
<BODY>
<H1>Antenati Citation Builder</H1>
Use the form below to create a WikiTree source citation for a record found on the <a target=_blank href=https://antenati.cultura.gov.it>Antenati website</a> for Italian records. (antenati.cultura.gov.it) <BR>
(Scroll to bottom of page for help links.) <BR>
<I>Last update: 20 January 2024 (added support for processettimove to new server, URL change)</I>
<P>&nbsp;</P>
	<TABLE align=center bgcolor=white><TR><TD>
		<OL>
			<LI>Fill out the coloured sections of the form below.</LI>
			<LI>Click the Compile Citation button to assemble the pieces together into one REF citation /REF chunk of text.</LI>
			<LI>Click the Copy Citation button to copy the reference to your clipboard. <I>(May need to scroll down)</I></LI>
			<LI>Paste the citation into the WikiTree profile you are currently editing.</LI></OL>
</TD></TR></TABLE>

	<TABLE align=center bgcolor=white>
		<TR><TH>&nbsp;</TH><TD align=right> <input type="submit" value="Clear All" style="background-color: red; color: white;"  onclick="clearAll();"> </TD></TR>


		<TR valign=top bgcolor=#EEEECC> <TH colspan=2>The Antenati Record</TH></TR>
		<TR valign=top bgcolor=#EEEECC><TH>Record URL:</TH>
			<TD> 
				<I><FONT color=grey>(Copy and paste the entire URL into the text box below.)<BR> This info will be used to assemble the appropriate citation. </FONT></I><BR>
				<input type="text" name="RecordURL" id="RecordURL" size=80 onchange=findAntenatiRecord(); 
					<?php if ($prefill=="Y") { ?>
						value="https://www.antenati.cultura.gov.it/ark:/12657/an_ua35983372/5Y4qADg"
					<?php	}  ?>
					> 
		</TD></TR>

					  

		<TR valign=top bgcolor=#EEEECC><TH>Person(s) name:</TH>
			<TD> 
		  		<I><FONT color=grey>(Enter Bride & Groom for a Matrimonio record, otherwise, just a single person's full name.  </FONT></I>
				<BR>
				<input type="text" name="PersonName" id="PersonName" size=80
				<?php if ($prefill=="Y") { ?>
					value="Raffaele Macchione"
				<?php	}  ?>
				>
		</TD></TR>


		
		 <TR valign=top bgcolor=#EEEECC><TH>Year :</TH>
		 	<TD> 
		 		<input type="text" name="YearNum" id="YearNum" size=10
					<?php if ($prefill=="Y") { ?>
						value="1882"
					<?php	}  ?> 
					> <span style='display:none;' id=OfProcessettiEndNote><I>&nbsp;&nbsp;(of Matrimonio/Processetti)</I></span>
		</TD></TR>


		 <TR valign=top bgcolor=#EEEECC><TH>Atto # :</TH>
		 	<TD> 
		 		<input type="text" name="AttoNum" id="AttoNum" size=10
					<?php if ($prefill=="Y") { ?>
						value="5"
					<?php	}  ?>
					>
		</TD></TR>


		
		 <TR valign=top bgcolor=#EEEECC><TH>Image # :</TH>
		 	<TD> 
		  		<I><FONT color=grey>(Enter image # only - OR - N of M (e.g. 7 of 19) if desired. </FONT></I>
				<BR>
		 		<input type="text" name="ImageNum" id="ImageNum" size=10
					<?php if ($prefill=="Y") { ?>
						value="7 of 19"
					<?php	}  ?>
					>
		</TD></TR>


		 <TR valign=top bgcolor=#EEEECC><TH>Location Descriptor :<BR><I>(optional)</I></TH>
		 	<TD> 
		  		<I><FONT color=grey>(Choose WHERE on the image the record begins, to help others locate it easily. OR ... leave it blank by choosing --- </FONT></I>
				<BR>
		 		<SELECT  name="WhereSELECT" id="WhereSELECT">
					<OPTION value="">---</OPTION>
					<OPTION value="left side of ">left side of image</OPTION>
					<OPTION value="top left of ">top left of image</OPTION>
					<OPTION value="middle left of ">middle left of image</OPTION>
					<OPTION value="bottom left of ">bottom left of image</OPTION>
					<OPTION value="right side of ">right side of image</OPTION>
					<OPTION value="top right of ">top right of image</OPTION>
					<OPTION value="middle right of ">middle right of image</OPTION>
					<OPTION value="bottom right of ">bottom right of image</OPTION>
				</SELECT>	
		</TD></TR>

		 <TR  style='display:none;' id=EventTypeTR valign=top bgcolor=#44EECC><TH>Event Type :</TH>
		 	<TD> 
		  		
		 		<input type="radio" name="EventTypeRadio" id="EventTypeBirth" value='Birth'> Birth
		 		<input type="radio" name="EventTypeRadio" id="EventTypeBaptism" value='Baptism' checked> Baptism
		 		<input type="radio" name="EventTypeRadio" id="EventTypeMarriage" value='Marriage'> Marriage
		 		<input type="radio" name="EventTypeRadio" id="EventTypeDeath" value='Death'> Death
		 		<input type="radio" name="EventTypeRadio" id="EventTypeEvent" value='Event'> Other Event
		</TD></TR>
		<TR  style='display:none;' id=EventYearTR valign=top bgcolor=#44EECC><TH>Event Year :</TH>
		 	<TD> 
		 		<input type="text" name="EventYearNum" id="EventYearNum" size=10 >
		</TD></TR>

		
		 
		<TR valign=top bgcolor=#FFF> <TH colspan=2>Info from Antenati website</TH></TR>
		<TR valign=top bgcolor=#FFF> <TD colspan=2><I><FONT color=grey>This information should load automatically, but if it does not ... </FONT></I></TD></TR>

		<TR valign=top bgcolor=#FFF><TH>&nbsp;</TH>
			<TD>		<I><FONT color=grey>  you can fill it in by hand <BR>- OR -<BR> you can Select All / Copy the Antenati page, then Paste all that into this text area. Click PROCESS.</FONT></I><BR/>
				<textarea rows=3 cols="80" name="RawInfoFromAntenati" id="RawInfoFromAntenati" size=80></textarea><BR/>
				<input type="submit" value="Process" style="background-color: lime;"  onclick="processRawInfoFromAntenati();">
		</TD></TR>
<TR valign=top bgcolor=#FFF><TH>Archive:</TH>
			<TD>		
				<input type="text" name="ArchiveName" id="ArchiveName" size=80
					<?php if ($prefill=="Y") { ?>
						value="Archivio di Stato di Cosenza"
					<?php	}  ?>
					> 
		</TD></TR>

		<TR valign=top bgcolor=#FFF><TH>Era:</TH>
					<TD>		
						<input type="text" name="EraName" id="EraName" size=80
							<?php if ($prefill=="Y") { ?>
								value="Stato civile italiano"
							<?php	}  ?>
							> 
				</TD></TR>

		<TR valign=top bgcolor=#FFF><TH>Town:</TH>
					<TD>		
						<input type="text" name="TownName" id="TownName" size=80
							<?php if ($prefill=="Y") { ?>
								value="Malito"
							<?php	}  ?>
							> 
				</TD></TR>

		<TR valign=top bgcolor=#FFF><TH>Record Type:</TH>
					<TD>		
						<input type="text" name="RecordType" id="RecordType" size=80
							<?php if ($prefill=="Y") { ?>
								value="Nati"
							<?php	}  ?>
							> 
				</TD></TR>

		<TR valign=top bgcolor=#FFF><TH>Register:</TH>
					<TD>		
						<input type="text" name="RegisterName" id="RegisterName" size=80
							<?php if ($prefill=="Y") { ?>
								value="Registro 87"
							<?php	}  ?>
							> 
				</TD></TR>


		 
		

		<TR valign=top bgcolor=#FFFFCC> <TH colspan=2>BONUS INFO (optional)</TH></TR>

		<TR valign=top bgcolor=#FFFFCC><TH>Final Note/<BR/>Transcription:<BR><I>(Optional)</I></TH><TD> <textarea rows=3 cols="80" name="FinalNote" id="FinalNote" size=80><?php if ($prefill=="Y") { ?>Marriage noted in margin to Carolina Spina, 5 Sep 1912<?php	}  ?></textarea> <BR>Note:  In some browsers, you can resize this textbox by dragging on bottom right corner downwards.<BR><BR><input type="checkbox" name="SubNLs4BR"  id="SubNLs4BR" value="Y">Check this box to replace new line characters (when you hit ENTER) in Final Note with &lt;br/&gt; code<BR><I>This will force WikiTree to respect the new line formatting of your transcription.<BR>(Useful for lists, e.g. census data)</I></TD></TR>
		<TR><TH>&nbsp;</TH><TD> <input type="submit" value="Compile Citation" style="background-color: lime;"  onclick="compileCitation();"> <BR>
		<input type=radio name=RefType id=RefTypeSimple value=RefSimple>Simple Ref
		<input type=radio name=RefType id=RefTypeNamed value=RefNamed checked>Named Ref
		<input type=radio name=RefType id=RefTypeNone value=NoRef >No Ref tags
		</TD></TR>
		<TR> <TH>Citation</TH> <TD> <textarea rows=8 cols="80" name="Citation" id="Citation"></textarea> </TD></TR>
		<TR><TH>&nbsp;</TH><TD> <input type="submit" value="Copy Citation" style="background-color: lime;"  onclick="copyCitation();"> </TD></TR>
	</TABLE>
	<P>&nbsp;</P>
	<P>See example of a <A target=_blank HREF="https://www.wikitree.com/wiki/Macchione-32">WikiTree profile</A> using citations built with the Antenati Citation Builder.</P>
	<P>See a demo of  <A target=_blank HREF="https://youtu.be/nNMNVHp4390">how to use this app</A> on YouTube.</P>

	

<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;
	<DIV align=center><FONT SIZE=-1><A target=_blank HREF=https://www.wikitree.com/g2g/1452757/new-app-for-creating-antenati-source-citations>G2G post</A></FONT> &nbsp;&nbsp; •  &nbsp;&nbsp;  <A target=_blank HREF=index.php>Other apps by Greg Clarke</A></FONT> &nbsp;&nbsp; •  &nbsp;&nbsp;  <A target=_blank href=https://www.wikitree.com/index.php?title=Special:PrivateMessage&who=19066309>Contact the author</A></DIV><BR/>&nbsp;<BR/>&nbsp;<BR>
	<I>Bookmarklet code:</I><BR/>
	<textarea cols=50 rows=2>javascript:window.open("https://apps.wikitree.com/apps/clarke11007/antenati.php?r="+window.location,"Ant app");void(0);</textarea>
	<BR/>&nbsp;<BR>
	<BR/>YouTube demo: <A target=_blank href="https://youtu.be/50M-w_yXHzs">How to setup and use this Bookmarklet for maximum fun !</A><BR>
	<BR/>&nbsp;<BR>


</BODY>
</HTML>
