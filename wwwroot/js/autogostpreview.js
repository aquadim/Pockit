// Возвращает количество строк разметки
function getMarkupLineCount() {
	return tareaMarkup.value.split('\n').length;
}

// Обновление поля разметки
function markupUpdate(forceLineNumbersUpdate=false) {
	if (tareaMarkup.scrollHeight > tareaMarkup.clientHeight) {
		// Содержимое полностью не вмещается
		tareaMarkup.style.height = "calc(2lh + " + tareaMarkup.scrollHeight +"px)";
	}

	let numberOfLines = getMarkupLineCount();
	if (forceLineNumbersUpdate || numberOfLines != lastMarkupLineCount) {
		lineNums.innerHTML = (Array(numberOfLines).fill('<span></span>').join(''));
		lastMarkupLineCount = numberOfLines;
	}
	unsavedChanges = true;
}

// Сохранение разметки
function saveMarkup(updateButtonText=false) {
	$.ajax({
		url: "/reports/update",
		type: "post",
		data: {
			id: globalReportId,
			markup: window.editor.state.doc.toString()
		},
		success: function() {
			unsavedChanges = false;
			if (updateButtonText) {
				btnSave.textContent = "Сохранено";
			}
			btnSave.blur();
			console.log("Markup saved");
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
    markupUpdate();
}

// Показывает превью и отключает редактор
function toPreview() {
	editorSection.classList.add("hidden");
	previewSection.classList.remove("hidden");
}

// Показывает редактор и отключает превью
function toMarkup() {
	editorSection.classList.remove("hidden");
	previewSection.classList.add("hidden");
}

// Обновляет #preview на странице, отсылая запрос на получение HTML
function updatePreview(asyncronous=true) {
	//~ errorsArea.hide();

	$.ajax({
		async: asyncronous,
		url: "/autogost/gethtml",
		type: "post",
		data: {
			report_id: globalReportId
		},
		success: function (data, textStatus, xhr) {
			// HTML сгенерирован успешно
			previewOut.innerHTML = data;
		},
		error: function(xhr, status, error) {
			
			//~ let errors = JSON.parse(xhr.responseText);
			//~ let list = $("<ol></ol>");

			//~ for (let i = 0; i < errors.length; i++) {
				//~ addErrorMessage(errors[i][0], errors[i][1], list);
			//~ }

			//~ $("#loader").remove();
			//~ errorsArea.html("<h3 class='text-center'>Ошибки разметки</h3>");
			//~ errorsArea.append(list);
			//~ errorsArea.show();
		}
	});
}

// Последнее число строк разметки
var lastMarkupLineCount;

// Есть ли несохранённые изменения
var unsavedChanges = false;

// Состояние страницы. 0 - разметка, 1 - превью
var state = 0;

// DOM
var tareaMarkup = document.getElementById("agstMarkup");
var lineNums = document.getElementById("agstLineNumbers");
var btnSave = document.getElementById("saveMarkupButton");
var btnFilename = document.getElementById("getFilename");
var btnToMarkup = document.getElementById("switchMarkup");
var btnToPreview = document.getElementById("switchPreview");
var btnPrint = document.getElementById("printReport");
var previewSection = document.getElementById("agstPreview");
var editorSection = document.getElementById("agstEditor");
var previewOut = document.getElementById("agstOutput");
markupUpdate(true);

tareaMarkup.onkeyup = function() {
	markupUpdate();
}

// Сохранение разметки
btnSave.onclick = function() {
	saveMarkup(true);
}

btnSave.onmouseleave = function() {
	btnSave.textContent = "Сохранить";
}

// Получение названия файла для сохранения
btnFilename.onclick = async function() {
	await navigator.clipboard.writeText(globalFilename);
	this.textContent = "Название скопировано";
	this.blur();
}

btnFilename.onmouseleave = function() {
	this.textContent = "Получить название файла";
}

// Вставка картинок на Ctrl+V
// https://stackoverflow.com/a/6338207
tareaMarkup.onpaste = function (e) {
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
						insertAtCursor(
							tareaMarkup,
							"\n@img:"+response.filename+":Изображение"
						);
					}
				}
			});
		}
	}
};

// Предотвращение случайной потери данных
// https://developer.mozilla.org/en-US/docs/Web/API/Window/beforeunload_event
const beforeUnloadHandler = (event) => {
	if (unsavedChanges == false) {
		return true;
	}

	// Recommended
	event.preventDefault();

	// Included for legacy support, e.g. Chrome/Edge < 119
	event.returnValue = true;
};

window.addEventListener("beforeunload", beforeUnloadHandler);

// Переключение между разметкой и превью
btnToPreview.onclick = function() {
	state = 1;
	btnToMarkup.classList.remove('border-accent');
	this.classList.add('border-accent');
	this.blur();

	previewOut.innerHTML = "<div class='loader'></div>";
	toPreview();
	saveMarkup();

	updatePreview(false);
}

btnToMarkup.onclick = function() {
	state = 0;
	btnToPreview.classList.remove('border-accent');
	this.classList.add('border-accent');
	this.blur();
	toMarkup();
}

btnPrint.onclick = function() {
	window.print();
}

window.addEventListener('beforeprint', function() {
	updatePreview(false);
}, false);

