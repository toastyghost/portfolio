<?
	$The_Submission = $The_Submissions[0];
	foreach($The_Submission->Local_Values_Array as $The_Local_Value) $Video_Information[$The_Local_Value->Field_Information['name']] = $The_Local_Value->Data;
?>
<a id="flowplayer" href="<?=$THE_BASE_CDN_URL.'/'.$Video_Information[$The_View->Local_Data['video_field']]?>" style="display:block;width:<?=$The_View->Local_Data['width']?>px;height:<?=$The_View->Local_Data['height']?>px;"></a>
<script src="/mimik/mimik_plugins/video/flowplayer/flowplayer-3.2.6.min.js"></script>
<script>
flowplayer('flowplayer','<?=$THE_BASE_URL?>/mimik/mimik_plugins/video/flowplayer/flowplayer-3.2.7.swf',{
	clip:{
		autoPlay:false,
		autoBuffering:true,
		scaling:'fit'
	}
});
</script>
<img width="0" height="0" class="portfolio_item_image" src="/mimik/mimik_images/spacer.gif" alt="<?=$Video_Information['description']?>"/>