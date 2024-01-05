// Текущее состояние страницы
// 0 - режим редактирования
// 1 - режим просмотра превью
var current_state = 0;
var markup_area = $('#markuparea');
var preview_area = $('#preview');

$(document).ready(function() {
	textAreaAdjust(document.getElementById("markuparea"));
});

$("#switchPreview").click(function() {
	markup_area.hide();
	preview_area.show();
	updatePreview();
});

$("#switchMarkup").click(function() {
	markup_area.show();
	preview_area.hide();
});

$("#printReport").click(function() {

	updatePreview(true);
})

// При печати текста заставляет textbox расширяться
function textAreaAdjust(element) {
	element.style.height = "1px";
	element.style.height = (25+element.scrollHeight)+"px";
}

function updatePreview(then_print=false) {
	const str = document.location.toString();
	const slash_idx = str.lastIndexOf('/');
	const report_id = str.substring(slash_idx + 1);
	$.post(
		"/regen/gethtml",
		{
			markup: markup_area.val(),
			report_id: report_id
		},
		function (data, textStatus, xhr) {
			$("#preview").html(data);
			if (then_print) {
				window.print();
			}
		}
	);
}