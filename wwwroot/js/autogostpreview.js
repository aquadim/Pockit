var markupArea = $('#markuparea');
var previewArea = $('#preview');

var btnPreview = $("#switchPreview");
var btnMarkup = $("#switchMarkup");
var btnPrint = $('#printReport');
var btnSave = $('#saveMarkupButton');
var btnFilename = $('#getFilename');

var reportId = $("#idInput").val();
var filename = $("#filename").val().replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "");

var isDirty = false;
var state = 0; // 0 - редактирование, 1 - превью

$(document).ready(function() {
	textAreaAdjust(document.getElementById("markuparea"));

	markupArea.keyup(function() {
		isDirty = true;
	});

	// Сохранение по кнопке
	btnSave.click(function(e) {
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
	markupArea.on('paste', function (e) {
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
							const line = "\n@img:"+response.filename+":Изображение";
							insertAtCursor(document.getElementById("markuparea"), line);
						}
					}
				});
			}
		}
	});
	
	btnPreview.click(function() {
		state = 1;
		$("#switchMarkup").removeClass('selected');
		$("#switchPreview").addClass('selected');
		previewArea.html('<div class="loader"></div>');
		markupArea.hide();
		previewArea.show();
		saveMarkup();
		updatePreview();
	});

	btnMarkup.click(function() {
		state = 0;
		$("#switchMarkup").addClass('selected');
		$("#switchPreview").removeClass('selected');
		textAreaAdjust(markupArea);
		markupArea.show();
		previewArea.hide();
		saveMarkup();
	});

	// Скопировать текст в буфер обмена
	btnFilename.click(async function() {
		await navigator.clipboard.writeText(filename);
		btnFilename.text("Название скопировано");
		btnFilename.blur();
	});

	btnFilename.mouseleave(function () {
		btnFilename.text("Получить название файла");
	});

	btnPrint.click(function() {
		saveMarkup();
		updatePreview(true);
	});
});

// Заставляет textarea расширяться так, чтобы был текст в ней был полностью
// виден
function textAreaAdjust(element) {
	if (element.scrollHeight > element.clientHeight) {
		// Содержимое полностью не вмещается
		element.style.height = "calc(1lh + " + element.scrollHeight +"px)";
	}
}

// Обновляет #preview на странице, отсылая запрос на получение HTML
function updatePreview(thenPrint=false) {
	$.ajax({
		url: "/autogost/gethtml",
		type: "post",
		data: {
			report_id: reportId
		},
		success: function (data, textStatus, xhr) {
			previewArea.html(data);
			if (thenPrint) {
				if (state == 0) {
					previewArea.show();
				}
				window.print();
				if (state == 0) {
					previewArea.hide();
				}
			}
		}
	});
}

// Сохраняет разметку для данного отчёта
function saveMarkup() {
	$.ajax({
		url: "/reports/update",
		type: "post",
		data: {
			id: reportId,
			markup: markupArea.val()
		},
		success: function() {
			isDirty = false;
			btnSave.blur();
		}
	});
}

// Вставка текста на текущую позицию курсора
// https://stackoverflow.com/a/11077016
function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
    textAreaAdjust(myField);
    isDirty = true;
}

// https://developer.mozilla.org/en-US/docs/Web/API/Window/beforeunload_event
const beforeUnloadHandler = (event) => {
	if (isDirty == false) {
		return true;
	}

	// Recommended
	event.preventDefault();

	// Included for legacy support, e.g. Chrome/Edge < 119
	event.returnValue = true;
};

window.addEventListener("beforeunload", beforeUnloadHandler);
