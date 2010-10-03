<?php
/**
 * Saves POST input as an XML file and returns a JSON response
 */
$xmlString;

if (isset($_POST['xmlString'])){
	$filename  = $_POST['xmlFilename'];
	$xmlString = stripslashes($_POST['xmlString']);
	
	$newFile = "_data/".$filename.".edit.xml";
	
	//write new data to the file, along with the old data 
	$handle = fopen("../".$newFile, "w"); 
	if (fwrite($handle, $xmlString) === false) { 
		echo "{error:\"Couldn't write to file.\"}";  
	} 
	else {
		echo "{filename:\"".$newFile."\"}";
	}
	fclose($handle); 	
}
?>