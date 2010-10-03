<?
error_reporting(0);

$xmlString;
$xmlFilename;
$target;
if (isset($_FILES['xmlfile'])){
	$xmlFilename = basename($_FILES['xmlfile']['name']);
	$target = "_data/" . $xmlFilename;
	if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $target)){
		$xml = simplexml_load_file($target, null, LIBXML_NOCDATA); // returns valse if xml is invalid
		if ($xml) $xmlString = $xml->asXML();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SubChild.com - Live XML Editor</title>
<script type="text/javascript" src="js/ext/jquery-1.4.js"></script>
<script type="text/javascript" src="js/ext/jquery-color.js"></script>
<script type="text/javascript" src="js/ext/GLR/GLR.js"></script>
<script type="text/javascript" src="js/ext/GLR/GLR.messenger.js"></script>
<script type="text/javascript" src="js/loc/xmlEditor.js"></script>
<link href="css/main.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
$(document).ready(function(){
<? if ($target && $xml){ ?>
	GLR.messenger.show({msg:"Loading XML..."});
	console.time("loadingXML");
	
	xmlEditor.loadXmlFromFile("<?=$target?>", "#xml", function(){
//	xmlEditor.loadXmlFromString($("#xmlString").val(), "#xml", function(){																														 																													 
		console.timeEnd("loadingXML");
		$("#xml").show();
		$("#actionButtons").show();																																				
		xmlEditor.renderTree();
		$("button#saveFile").show().click(function(){
			GLR.messenger.show({msg:"Generating file...", mode:"loading"});
			$.post("do/saveXml.php", {xmlString:xmlEditor.getXmlAsString(), xmlFilename:"<?=$xmlFilename?>"}, function(data){
				if (data.error){
					GLR.messenger.show({msg:data.error,mode:"error"});
				}
				else {
					GLR.messenger.inform({msg:"Done.", mode:"success"});
					if (!$("button#viewFile").length){
						$("<button id='viewFile'>View Updated File</button>")
							.appendTo("#actionButtons div")
							.click(function(){ window.open(data.filename); });
					}
				}
			}, "json");
		});
	});
<? } else { ?>
	$("#xml").html("<span style='font:italic 11px georgia,serif; color:#f30;'>Please upload a valid XML file.</span>").show();
	<? if ($target && !$xml){ ?>
	GLR.messenger.showAndHide({msg:"Uploaded file is not valid XML and cannot be edited.", mode:"error", speed:3000});
	<? } ?>
<? } ?>
//	$("#todos, #links").height($("#about").height()+"px");
});
</script>
</head>

<body>
	<div id="header">
		<a href="index.php" id="home"></a>
	</div>
  
<? /*   <div style="margin:50px auto; width:500px; text-align:center; font:helvetica neue, arial;">I'm working on this at the moment so it might be funky for a bit. And yes, I'll try not to make changes in "production" in the future.</div> */ ?>
  
	<form id="uploadForm" action="index.php" method="post" enctype="multipart/form-data">
		<label for="xmlfile">Specify XML file to edit:</label>
		<input type="file" name="xmlfile" id="xmlfile"/>
		<input type="submit" value="Upload"/>
	</form>
	<div id="xml" style="display:none;"></div>
	<div id="actionButtons" style="display:none;">
		<div></div>
		<button id="saveFile">Save XML</button>
	</div>
	<div id="nodePath"></div>
	<div id="footer">
		<div id="about">
			<h3>About LiveXmlEdit</h3>
			<p>
				LiveXMLEdit is a tool for inline editing of XML files. It renders the uploaded XML 
				file and lets you create and delete nodes and attributes, as well as update their
				values by clicking on them directly. If you don't see a dedicated save (Submit)
				button, use the ENTER key to save. Once you are done editing the file, you can 
				generate the new version and save it.
			</p>
			<p>
				LiveXmlEdit is optimized for Firefox, Google Chrome and Safari.  It will work just as well in 
				Internet Explorer 6/7/8 but won't look as pretty.
			</p>
		</div>
		<div id="todos">
			<h3>Planned Enhancements</h3>
			<ul>
				<li>Optimize rendering of large XMLs.</li>
				<li>Prettify upload form.</li>
				<li>Support creating a new XML document from scratch.</li>
      	<li>XML comment editing and creation.</li>        
				<li>Auto Save and versioning.</li>
				<li>Reverting to original or last saved.</li>
				<li>Support for Undo(s).</li>
				<li>XSD generation and exporting.</li>			
			</ul>
		</div>
		<div id="links">
			<h3>Contact</h3>
			<p>
				Bugs? Ideas? Random thoughts?<br/>Let me know: <strong>ak@subchild.com</strong>
			</p>
			<p>
				Related topics are discussed on <a href="http://www.subchild.com/">subchild.com</a>.
			</p>
		</div>
	</div>
	<? /* if ($xmlString){ ?><textarea style="display:none;" id="xmlString"><?=$xmlString?></textarea><? } */ ?>
	
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-443787-5");
pageTracker._trackPageview();
} catch(err) {}</script>	
</body>
</html>

