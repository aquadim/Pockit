// Текущее состояние страницы
// 0 - режим редактирования
// 1 - режим просмотра превью
var current_state = 0;
var markup_area = $('#markuparea');
var preview_area = $('#preview');
var saveButton = $('#saveMarkupButton');

$(document).ready(function() {
	textAreaAdjust(document.getElementById("markuparea"));

	$("#saveForm").submit(function(e) {
		e.preventDefault();
		saveMarkup();
	});

	// Сохранение на Ctrl+S
	document.addEventListener("keydown", function(e) {
	  if (e.keyCode === 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
		e.preventDefault();
		saveMarkup();
	  }
	}, false);

	// Вставка картинок на Ctrl+V
	// https://stackoverflow.com/a/6338207
	$("#markuparea").on('paste', function (e) {
		let items = (e.clipboardData || e.originalEvent.clipboardData).items;
		for (let index in items) {
			let item = items[index];
			if (item.kind === 'file') {

				// Загрузка файла через jQuery AJAX
				// https://stackoverflow.com/a/13333478
				var fd = new FormData();
				fd.append('file', item.getAsFile());

				$.ajax({
					url: "/autogost/upload-image",
					type: "post",
					data: fd,
					processData: false,
					contentType: false,
					success: function (response) {
						response = JSON.parse(response);

						if (response.ok) {
							const line = "?"+response.filename+":"+"Подпись изображения";
							markup_area.val(markup_area.val() + line);
						}
					}
				});
			}
		}
	});
	
	$("#switchPreview").click(function() {
		markup_area.hide();
		preview_area.show();
		updatePreview();
		saveMarkup();
	});

	$("#switchMarkup").click(function() {
		markup_area.show();
		preview_area.hide();
		saveMarkup();
	});

	$("#printReport").click(function() {
		saveMarkup();
		updatePreview(true);
	})
});

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
		"/autogost/gethtml",
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

// Сохраняет разметку для данного отчёта
function saveMarkup() {
	$.ajax({
		url: "/reports/update",
		type: "post",
		data: {
			id: $("#idInput").val(),
			markup: $("#markuparea").val()
		},
		success: function() {
			saveButton.blur();
		}
	});
}