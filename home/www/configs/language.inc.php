<div id="currentLanguage">
	<img src="<?= $adminArea ? '../' : '' ?>images/lang_<?=$_SESSION["lang"]?>.svg" border="0" />
</div>

<div id="languageChoice" style="display:none; position:absolute;">
<?
$langFiles = glob(dirname(__FILE__)."/lang_*.php");
foreach($langFiles as $langFile) {
	$lang=substr($langFile, strpos($langFile, "lang_")+5, 2);
?>
	<div><img onclick="window.location.href='./?lang=<?= $lang ?>'" src="<?= $adminArea ? "../" : "" ?>images/lang_<?= $lang ?>.svg" border="0" /></div>
<?
}
?>
</div>
<script>
	$("#currentLanguage") 
		.click(function() {
			var languageChoice = $("#languageChoice");
			if(languageChoice.is(':hidden')) {
				languageChoice.css("top", $(this).offset().top+$(this).height());
				languageChoice.css("left", $(this).offset().left + ($(this).width()-languageChoice.width())/2);
				languageChoice.slideDown();
			}
		});

	$(document).mouseup(function (e) {
		var languageChoice = $("#languageChoice");

		if (languageChoice.is(':visible') && 
			!languageChoice.is(e.target) &&
    		languageChoice.has(e.target).length === 0) {
    		
    		languageChoice.slideUp();
		}
	});
</script>
