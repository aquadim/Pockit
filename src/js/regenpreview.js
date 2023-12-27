$(document).ready(function() {

	$("#tabs").tabs({
		beforeActivate: function(e, ui) {
			if (ui.newPanel.attr('id') == 'preview') {
				// Открывается вкладка просмотра превью
				// Отправляем запрос на сервер для генерации HTML
				writePreview("#previewdiv");
			}
		}
	});

	const elem = document.getElementById("markuparea");
	textAreaAdjust(elem);
	
});

// При печати текста заставляет textbox расширяться
function textAreaAdjust(element) {
  element.style.height = "1px";
  element.style.height = (25+element.scrollHeight)+"px";
}

function writePreview(selector, then_print=false) {
	const str = document.location.toString();
	const slash_idx = str.lastIndexOf('/');
	const report_id = str.substring(slash_idx + 1);
	$.post(
		"/regen/gethtml",
		{
			markup: $("#markuparea").val(),
			report_id: report_id
		},
		function (data, textStatus, xhr) {
			$(selector).html(data);
			if (then_print) {
				window.print();
				$("#printpreview").html("");
			}
		}
	);
}

function printReport() {
	writePreview("#printpreview", true);
}