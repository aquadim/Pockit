// Текущее состояние страницы
// 0 - режим редактирования
// 1 - режим просмотра превью
var current_state = 0;

$(document).ready(function() {
	textAreaAdjust(document.getElementById("markuparea"));
});

// При печати текста заставляет textbox расширяться
function textAreaAdjust(element) {
	element.style.height = "1px";
	element.style.height = (25+element.scrollHeight)+"px";
}

function updatePreview() {
	let new_text = $("#markuparea").val();
	if (new_text == old_text) {
		return;
	}
	old_text = new_text;

	const str = document.location.toString();
	const slash_idx = str.lastIndexOf('/');
	const report_id = str.substring(slash_idx + 1);
	$.post(
		"/regen/gethtml",
		{
			markup: new_text,
			report_id: report_id
		},
		function (data, textStatus, xhr) {
			$("#preview").html(data);
		}
	);
}