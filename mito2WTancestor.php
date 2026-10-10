<?php


/*
mito2WTancestor - mitoYDNA to WikiTree Ancestor search program
Clarke-15233
French-13565 

e.g. 
https://apps.wikitree.com/apps/clarke11007/mito2WTancestor.php?dna=mt&id=Clarke-11007&v=Y&t=Y

This mini-app will find the earliest known ancestor along a maternal or paternal line, then display their DNA Descendants page from WikiTree

PARAMETERS:

id : WikiTree ID (e.g. Clarke-11007 - REQUIRED  - no default supplied)
dna : DNA type, values Y or mt (DEFAULT = Y))
v : verbose , values Y or N (DEFAULT = N).  If Y then list of all ancestors found will be given.  Otherwise, no output until WikiTree page or Error message

*/

$v = $_GET['v'];  // Verbose ?
$id = $_GET['id']; // WikiTree ID
$dna = $_GET['dna']; // DNA type
$t = $_GET['t']; // DNA type

if ( $_COOKIE['mito2WTreeDoLogin'] ==  'Y' && !$id) {
	// must have just returned from a Login screen, so let's load up the variables from the Cookies
	$id = $_COOKIE['mito2WTreeID'];
	$dna = $_COOKIE['mito2WTreeDNAtype'];
	$v = 'Y';
	// $t = 'Y';
	setcookie('mito2WTreeDoLogin', 'N'); // now that we've used these values - no longer need them
}
if ($_GET['authcode'] > ''){
	// var_dump($_COOKIE);
}

$mitoBlue = "#3067AA";
$wikiGreen = "#26422E";

$fontBlue = "<FONT color=" . $mitoBlue . ">";
$fontGreen = "<FONT color=" . $wikiGreen . ">";
$fontStop = "</FONT>";

$numGenerations = 0;

if ($t == 'Y') {
	$testing = true;	
} else {
	$testing = false;	
}

if ($v == 'Y') {
	$verbose = true;
	$delay=3;
} else {
	$verbose = false;
	$delay=0;
}

if ($dna != 'mt') {
	$dna = 'Y';
	$gender = 'Male';
	$parent = 'Father';
	$bioparent = 'BioFather';
	$lc_parent = "father";
} else {
	$gender = 'Female';
	$parent = 'Mother';
	$bioparent = 'BioMother';
	$lc_parent = "mother";
}

	echo "<IMG width='200px' src=images/mitoydnalogo.png /> <font size=24>&nbsp;&nbsp;&#8594;&nbsp;&nbsp;</font> <IMG width='200px' src='https://www.wikitree.com/images/wikitree-small.png' /><BR>";
	echo "<font face='sans-serif'>";

if ($testing) {
	$delay = 20;
}

if (strlen($id) == 0) {
	echo "<BR>ERROR: Missing WikiTree ID<BR>Please try again.";
} else {
	// echo "<P>Proceed, amigo!";
	$OKtoProceed = true;
	$SafePerson = str_replace(" ", "%20", $id);


	$theURL = "https://api.wikitree.com/api.php?action=getPerson&appId=mitoYDNA2WTanc&fields=Id,Name,ShortName,FirstName,LastNameCurrent,$parent,$bioparent,&depth=1&key=" . $SafePerson;
		
	if ($testing) {
		echo "<BR>URL:" . $theURL . "<BR>" ;
	}
	
	$page = file_get_contents($theURL);
	$JSONobject = json_decode($page);
	

	$theName = $fontGreen .  $JSONobject[0]->person->Name . $fontStop;
	$theID = $theName;
	if (strlen( $JSONobject[0]->person->FirstName) > 0  && strlen( $JSONobject[0]->person->LastNameCurrent) > 0 ) {
		$theName = $fontBlue . $JSONobject[0]->person->FirstName . " " . $JSONobject[0]->person->LastNameCurrent  . $fontStop . " (" . $theName . ")";
	} 

	if (!$JSONobject[0]->person) {
		$OKtoProceed = false;
	}
		
	$theName = $id;

	if ($testing) {
		var_dump($JSONobject[0]->person); echo "<BR><BR>";
		echo "<BR>Cookies are " . $_COOKIE['mito2WTreeDNAtype'] . ' and ' . $_COOKIE['mito2WTreeID'] . '<BR> ' ;
	}

	echo "Searching for the Earliest Known Ancestor of " . $theName . " on the Direct $gender Line:<BR>";
	// echo $JSONobject[0]->person->$bioparent . "<BR>";
	//$AncestorArray1
	// echo "<BR>" . $JSONobject . "<BR>";
	// $_SESSION['AncestorArray1'] = $JSONobject[0]->ancestors;

 	// var_dump($JSONobject[0]->person->$parent);

 	if ($JSONobject[0]->person->$parent > 0 || $JSONobject[0]->person->$bioparent > 0) {
 		if ($JSONobject[0]->person->$bioparent > 0) {
 			$furthestID = $JSONobject[0]->person->$bioparent;
 		} else {
 			$furthestID = $JSONobject[0]->person->$parent;
 		}
 		// $furthestID = $JSONobject[0]->person->$parent; // keep track of the WikiTree ID furthest up the food chain that we've succcessfully retrieved

 		// echo "<BR>" . $JSONobject[0]->person->Name . " of " . $JSONobject[0]->person->$parent;

 		while ($JSONobject[0]->person->$parent > 0 || $JSONobject[0]->person->$bioparent > 0) {
 				$numGenerations++; // increment the counter for # of Generations

 				$prevName = $theName; // remember the previous Name found
 				$prevID = $theID; // and the previous ID

		 		// $SafePerson = $JSONobject[0]->person->$parent;
 				if ($JSONobject[0]->person->$bioparent > 0) {
		 			$SafePerson = $JSONobject[0]->person->$bioparent;
		 		} else {
		 			$SafePerson = $JSONobject[0]->person->$parent;
		 		}
 				$theURL = "https://api.wikitree.com/api.php?action=getPerson&appId=mitoYDNA2WTanc&fields=Id,Name,FirstName,LastNameCurrent,BirthLocation,BirthDate,$parent,$bioparent&depth=1&key=" . $SafePerson;
 				if ($testing) {
			 		echo "<BR>URL:" . $theURL;
			 	}
				$page = file_get_contents($theURL);
				$JSONobject = json_decode($page);

				if ($JSONobject[0]->person->$bioparent > 0) {
			 		$furthestID = $JSONobject[0]->person->$bioparent;
			 	} else if ($JSONobject[0]->person->$parent > 0) {
		 			$furthestID = $JSONobject[0]->person->$parent;
		 		} else {
			 		// echo "<P>END OF THE LINE with ". $JSONobject[0]->person->Name;
			 	}


		 		$theName = $fontGreen .  $JSONobject[0]->person->Name . $fontStop;
			 	if ($testing) {
			 		echo "<BR>Look:" . $parent . $bioparent. ":" . $SafePerson . " " . $theName ;
			 	}

		 		$theID = $theName;
				if (strlen( $JSONobject[0]->person->FirstName) > 0  && strlen( $JSONobject[0]->person->LastNameCurrent) > 0 ) {
					// $theName .= " (" . $JSONobject[0]->person->FirstName . " " . $JSONobject[0]->person->LastNameCurrent . ")";
					$theName = $fontBlue . $JSONobject[0]->person->FirstName . " " . $JSONobject[0]->person->LastNameCurrent  . $fontStop . " (" . $theName . ")";
				}

				if ( $JSONobject[0]->person->BirthLocation || $JSONobject[0]->person->BirthDate ) {
					$birthInfo = ", born ";
					if ( $JSONobject[0]->person->BirthLocation && $JSONobject[0]->person->BirthDate ) {
						$comma = ', ';
					} else {
						$comma = '';
					}
					if ( $JSONobject[0]->person->BirthDate ) {
						$birthInfo .= $JSONobject[0]->person->BirthDate . $comma;
					}
					if ( $JSONobject[0]->person->BirthLocation ) {
						$birthInfo .= $JSONobject[0]->person->BirthLocation;
					}

				} else {
					$birthInfo = "";
				}

				if ($verbose){
		 			echo "<BR>Found the $lc_parent of $prevID : $theName $birthInfo";// . $JSONobject[0]->person->Name. " of " . $JSONobject[0]->person->$parent;
				}	


 		}
 	} else {
 		// echo "<P>ENDED before it even began ! - must use $SafePerson";
 		$OKtoProceed = false;
 		$furthestID = $JSONobject[0]->person->Id;
 	}
 	
 	if ($OKtoProceed) {

	 	echo "<P><P><B>Loading the DNA Descendants page for $theName ....</B>";
	 	$theURL = "https://api.wikitree.com/api.php?action=getPerson&appId=mitoYDNA2WTanc&fields=Id,Name&key=" . $SafePerson;
		$page = file_get_contents($theURL);
		$JSONobject = json_decode($page);
		// var_dump($JSONobject[0]->person);
		// echo " also known as " . $JSONobject[0]->person->Name;
		$newURL = "https://www.wikitree.com/treewidget/" . str_replace(" " ,"%20", $JSONobject[0]->person->Name ) . "/890";
		// echo " at " . $newURL  . "<BR>";
		if ($testing) {
			// dont' go any further
			
		} else {

			echo '<HTML><HEAD><meta http-equiv="refresh" content="' . $delay . ';url=' . $newURL. '"/></HEAD></HTML>'; 
		}

		$delayDescription = "";
		if ($numGenerations <= 5 ) {
			$delayDescription = "usually just a few seconds.";
		} else if ($numGenerations < 10) {
			$delayDescription = "generally between 10 and 40 seconds.";
		} else {
			$delayDescription = "often between 30 and 60 seconds.";
		}
		echo "<BR><BR><I>Please be patient while we are retrieving the descendant data from WikiTree.<BR>Depending on the size of the family tree, this could take some time. <BR><BR>For a search involving $numGenerations generations, the average wait time is " . $delayDescription . "<BR><BR>Wait time can also vary based on server activity.</I><BR><IMG width=200px src='https://mitoydna.org/images/ButtonLogoTeeterspinmed_gif.gif' />";

 	} else {
 		$returnURL = "https://apps.wikitree.com/apps/clarke11007/mito2WTancestor.php";//?dna=$dna&id=$id&v=Y&t=Y";
 		 // $_COOKIE['mito2WTreeDNAtype']  = $dna;
 		 // $_COOKIE['mito2WTreeID']  = $id;
		setcookie('mito2WTreeDNAtype', $dna);
		setcookie('mito2WTreeID', $id);	
		setcookie('mito2WTreeDoLogin', 'Y');

 		if ($testing) {echo "RETURN URL = <BR>$returnURL<BR>";}
 		echo "<P><B>There is a problem accessing the WikiTree Profile for $id and/or for their $parent.</B><P><BR>
 		Possible Reasons:<OL>
 		<LI> $id is a Living Person with a PRIVATE WikiTree Profile.  If you ARE this person, or a Profile Manager for them, you can <a  href='https://api.wikitree.com/api.php?action=clientLogin&appId=mitoYDNA2WTanc&returnURL=$returnURL'>Login to WikiTree</a> and retry to load their EKA Descendants list. <BR>
 		<I>NOTE: If there are multiple Private Profiles between the Living Person and the EKA, it may not be possible to climb the tree.</I>
 		<BR><BR>
 		<LI> $id does not have any Ancestors, and is their own EKA.  If this is the case, you can view <a href='https://www.wikitree.com/treewidget/$id/890'>their Descendants list</a> directly (if they have any descendants). <BR><BR>
 		<LI> $id is not a valid WikiTree ID.  Please <A target=_blank href='https://www.wikitree.com/wiki/$id'>double-check</a> if you are the Profile Manager for this user, and re-enter the WikITree ID in the mitoYDNA database if needed.
 		</OL>
 		";


 		
 	}
}
?>