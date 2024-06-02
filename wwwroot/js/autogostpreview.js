// Возвращает количество строк разметки
function getMarkupLineCount() {
	return tareaMarkup.value.split('\n').length;
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

// DOM
var tareaMarkup 	= document.getElementById("agstMarkup");
var lineNums 		= document.getElementById("agstLineNumbers");

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

// Обработчик кнопки вставки изображения
btnAddImage.onclick = function() {
	// Выбор файла
	let input = document.createElement('input');
	input.type = 'file';
	input.onchange = function(e) {
		let file = e.target.files[0];
	}
}