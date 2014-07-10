<head>
	<link rel="stylesheet" type="text/css" href="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_plugins/calendar/fullcalendar.css"/>
	<style>#calendar{width:<?=$The_View->Local_Data['width']?>px;}</style>
	<script type="text/javascript" src="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_js/jquery.js"></script>
	<script type="text/javascript" src="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_js/ui.core.js"></script>
	<script type="text/javascript" src="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_js/ui.draggable.js"></script>
	<script type="text/javascript" src="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_js/ui.resizable.js"></script>
	<script type="text/javascript" src="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_plugins/calendar/custom.js"></script>
	<script type="text/javascript" src="http://<?=$_SERVER['HTTP_HOST']?>/mimik/mimik_plugins/calendar/fullcalendar.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#calendar').fullCalendar({
				editable: false,
				events: [
						<?
							$Submissions_Length = count($The_Submissions);
							foreach ($The_Submissions as $The_Key => $The_Submission) :
							$The_Submission_Title = $The_Submission->Gets_The_Local_Value($The_View->Local_Data['title_field']);
							$The_Submission_Date = $The_Submission->Gets_The_Local_Value($The_View->Local_Data['sort_field']);
								echo '
								{
									id:"'.$The_Submission->ID.'",
									title:"'.$The_Submission_Title->Live_Site_HTML_For_The_Data().'",
									start:"'.$The_Submission_Date->Live_Site_HTML_For_The_Data().'",
									end:"'.$The_Submission_Date->Live_Site_HTML_For_The_Data().'",
									url:"javascript:"
								}';
							if ($The_Key != $Submissions_Length - 1) echo ',';
							endforeach;
						?>
						],
				prev: 'circle-triangle-w',
				next: 'circle-triangle-e',
				weekMode: 'liquid'
			});
		});
	</script>
</head>
<body>
	<div id="calendar"></div>
	<input type="hidden" id="view_id" name="view_id" value="<?=$The_View_Parameters['id']?>"/>
</body>