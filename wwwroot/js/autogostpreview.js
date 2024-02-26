// Возвращает количество строк разметки
function getMarkupLineCount() {
	return tareaMarkup.value.split('\n').length;
}

// Сохранение разметки
function saveMarkup(successCallback) {
	$.ajax({
		url: "/reports/update",
		type: "post",
		data: {
			id: globalReportId,
			markup: window.editor.state.doc.toString()
		},
		success: function() {
			unsavedChanges = false;
			btnSave.blur();
			console.log("Markup saved");
			if (successCallback) {
				successCallback();
			}
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
}

// Обновляет #preview на странице, отсылая запрос на получение HTML
function updatePreview(onSuccess) {
	$.ajax({
		url: "/autogost/gethtml",
		type: "post",
		data: {
			report_id: globalReportId
		},
		success: function (data, textStatus, xhr) {
			// HTML сгенерирован успешно
			previewOut.innerHTML = data;
			onSuccess();
			console.log("Updating preview finished successfully");
		},
		error: function(xhr, status, error) {
			console.error("Error in updating preview");
		}
	});
}

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

// Сохранение разметки
btnSave.onclick = function() {
	saveMarkup(function() {
		btnSave.textContent = "Сохранено";
	});
}

btnSave.onmouseleave = function() {
	btnSave.textContent = "Сохранить";
}

// Получение названия файла для сохранения
btnFilename.onclick = async function() {
	await navigator.clipboard.writeText(globalFilename);
}

// Предотвращение случайной потери данных
// https://developer.mozilla.org/en-US/docs/Web/API/Window/beforeunload_event
//~ const beforeUnloadHandler = (event) => {
	//~ if (unsavedChanges == false) {
		//~ return true;
	//~ }

	//~ // Recommended
	//~ event.preventDefault();

	//~ // Included for legacy support, e.g. Chrome/Edge < 119
	//~ event.returnValue = true;
//~ };

//~ window.addEventListener("beforeunload", beforeUnloadHandler);

// Переключение на превью
btnToPreview.onclick = function() {

	previewOut.innerHTML = "<div class='loader'></div>";
	btnToPreview.blur();

	editorSection.classList.add("hidden");
	previewSection.classList.remove("hidden");
	btnToMarkup.classList.remove('border-accent');
	btnToPreview.classList.add('border-accent');

	// 1. Разметка сохраняется
	// 2. Превью обновляется
	// 3. state = 1
	saveMarkup(function() {
		updatePreview(function() {
			state = 1;
		});
	});
	
}

// Переключение на редактор
btnToMarkup.onclick = function() {
	btnToMarkup.blur();
	btnToPreview.classList.remove('border-accent');
	btnToMarkup.classList.add('border-accent');
	editorSection.classList.remove("hidden");
	previewSection.classList.add("hidden");
	state = 0;
}

// Обработчик кнопки печати
btnPrint.onclick = function() {
	updatePreview(function() {
		window.print();
	});
}