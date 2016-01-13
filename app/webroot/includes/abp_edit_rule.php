<?php
use classes\core\helpers\FormHelper;
use classes\core\repository\Database;

$id = $_GET['id'] + 0;

$sql = <<<EOQ
SELECT
	*
FROM
	territory_rules
WHERE
	id = ?
EOQ;

$params = array(
	$id
);
$ruleDetail = Database::getInstance()->query($sql)->single($params);

if($ruleDetail)
{

	$power_types = [
		"Merit" => 'Merit',
		"ICDisc" => "In-Clan Discipline",
		"OOCDisc" => "Out-of-Clan Disc.",
		"Devotion" => "Devotion/Ritual/Misc.",
		"Derangement" => "Derangement",
	];

	$power_typeSelect = FormHelper::Select($power_types, 'power_type', $ruleDetail['power_type']);

	$sharedChecked = ($ruleDetail['is_shared']) ? "checked" : "";

	$page_content = <<<EOQ
<h2>Update ABP Rule</h2>

<form id="createRuleForm">
<div class="formInput">
	<label>Rule Name:</label>
	<input type="text" name="ruleName" id="ruleName" value="$ruleDetail[rule_name]" />
</div>
<div class="formInput">
	<label>Power Type:</label>
	$power_typeSelect
</div>
<div class="formInput">
	<label>Power Name:</label>
	<input type="text" name="powerName" id="powerName" value="$ruleDetail[power_name]"><br />
</div>
<div class="formInput">
	<label>Power Note:</label>
	<input type="text" name="powerNote" id="powerNote" value="$ruleDetail[power_note]"><br />
</div>
<div class="formInput">
	<label>Is Shared:</label>
	<input type="checkbox" name="isShared" id="isShared" value="y" $sharedChecked><br />
</div>
<div class="formInput">
	<label>Multiplier:</label>
	<input type="text" name="multiplier" id="multiplier" value="$ruleDetail[multiplier]"><br />
</div>
<div class="formInput">
	<label>Modifier:</label>
	<input type="text" name="modifier" id="modifier" value="$ruleDetail[modifier]"><br />
</div>
<div class="formInput">
	<input type="hidden" name="id" id="id" value="$id" />
	<input type="button" name="formSubmit" id="formSubmit" value="Update rule" />
</div>
</form>
<script language="javascript">
	$(document).ready(function(){
		$('input:text').keypress(function(e){
			if(e.keyCode == 13)
			{
				return false;
			}
		});
		$('#formSubmit').click(function(){
			var errors = '';
			if($.trim($('#ruleName').val()) == '')
			{
				errors += " - Enter a name for the Rule.\\r\\n";
			}
			if($.trim($('#power_type').val()) == '')
			{
				errors += ' - Enter a power Type (Merit, ICDisc, OOCDisc, Devotion, Derangement).\\r\\n';
			}
			if($.trim($('#powerName').val()) == '')
			{
				errors += ' - Enter a Power Name.\\r\\n';
			}
			
			if(errors == '')
			{
				$.ajax({
					url: "/abp.php?action=edit_rule_post",
					data: $('#createRuleForm').serialize(),
					type: "post",
					dataType: "html",
					success: function(response, status, request) {
						refreshAbpRuleList(response);
						$("#rulePane").css("display", "none") ;
					},
					error: function(request, message, exception) {
						alert('There was an error submitting the request. Please try again.');
					}
				});
			}
			else
			{
				alert('Please correct the following errors: \\r\\n' + errors);
			}
		});
	});
</script>
EOQ;
}
else
{
	echo "Illegal Rule.";
}
