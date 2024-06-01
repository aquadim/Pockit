import {EditorView, basicSetup} from "codemirror"
import {EditorState} from "@codemirror/state"
import {autocompletion} from "@codemirror/autocomplete"
import {keymap} from "@codemirror/view"
import {
    LanguageSupport,
    StreamLanguage,
    StringStream,
    HighlightStyle,
    syntaxHighlighting
} from "@codemirror/language";
import { tags } from "@lezer/highlight";

// Возвращает разметку для отчёта
function getMarkup(report_id) {
    return fetch("http://localhost:9000/reports/get", {
        method: "post",
        body: JSON.stringify(
            {id: report_id}
        )
    });
}

// Переключает состояние боковой панели
function toggleSidebar() {
    sidebarOpened = !sidebarOpened;
    if (sidebarOpened) {
        openSidebar();
    } else {
        closeSidebar();
    }
}

// Открывает панель инструментов
function openSidebar() {
    sidebar.classList.add("sidebarOpen");
    content.classList.add("contentShifted");
    btnSidebarToggle.textContent = "❌ Закрыть панель инструментов";
}

// Закрывает панель инструментов
function closeSidebar() {
    sidebar.classList.remove("sidebarOpen");
    content.classList.remove("contentShifted");
    btnSidebarToggle.textContent = "📖 Открыть панель инструментов";
}

// Переключает редактор в режим "Загрузка"
function editorToLoader() {
    editorLoader.classList.remove('hidden');
}

// Переключает редактор в режим "Превью"
async function editorToPreview() {
    editorToLoader();
	btnToPreview.blur();

	editorSection.classList.add("hidden");
	previewSection.classList.remove("hidden");
	btnToMarkup.classList.remove('border-accent');
	btnToPreview.classList.add('border-accent');

	// 1. Разметка сохраняется
	// 2. Превью обновляется
    // 3.1 Скрываем загрузку
	// 3.2 state = 1
	await saveMarkup(function() {
		updatePreview(function() {
            editorLoader.classList.add('hidden');
			editorState = 1;
		});
	});
}

// Сохранение разметки
async function saveMarkup(successCallback) {
    const response = await fetch("http://localhost:9000/reports/update", {
        method: "post",
        body: JSON.stringify(
            {id: PHP_report_id, markup: editor.state.doc.toString()}
        )
    });

    if (response.ok) {
        unsavedChanges = true;
        btnSave.blur();
        console.log("Markup saved");
        if (successCallback) {
            successCallback();
        }
        return;
    }

    console.error("Failed to save markup");
}

// Автодополнение
function autogostCompletions(context) {
    let before = context.matchBefore(/^@\w*/);
    if (!context.explicit && !before) {
        return null;
    }
    return {
        from: before ? before.from : context.pos,
        options: completions,
        validFor: /^\w*$/
    }
}

// Боковая панель
let sidebarOpened       = true;
let sidebar             = document.getElementById('agstControls');
let content             = document.getElementById('agstMain');
let btnSidebarToggle    = document.getElementById('btnToggleSidebar');

// Кнопки боковой панели
let btnToPreview 	= document.getElementById("switchPreview");
let btnToMarkup 	= document.getElementById("switchMarkup");
let btnAddImage 	= document.getElementById("btnAddImage");
let btnPrint 		= document.getElementById("printReport");
let btnFilename 	= document.getElementById("getFilename");
let btnSave 		= document.getElementById("saveMarkupButton");

// Редактор
// Состояние редактора. 0 - разметка, 1 - превью
let editorState = 0;
let previewSection 	= document.getElementById("agstPreview");
let editorSection	= document.getElementById("agstEditor");
let previewOut 		= document.getElementById("agstOutput");
let editorLoader    = document.getElementById('agstLoader');
let unsavedChanges  = false;

// -- CodeMirror --
// Все ключевые слова
const completions = [
    {label: "@titlepage", type: "keyword", info: "Титульная страница"},
    {label: "@section:Название", type: "keyword", info: "Секция основной части"},
    {label: "@-", type: "keyword", info: "Разрыв страницы"},
    {label: "@img:Источник:Подпись", type: "keyword", info: "Изображение"},
    {label: "@\\", type: "keyword", info: "Пустая строка"},
    {label: "@raw", type: "keyword", info: "Начало чистого HTML"},
    {label: "@endraw", type: "keyword", info: "Конец чистого HTML"},
    {label: "@@:Комментарий", type: "comment", info: "Комментарий"},
];

// -- Привязка событий --
btnSidebarToggle.onclick = e => toggleSidebar();
btnToPreview.onclick = async function(e) {
    await editorToPreview();
}
btnSave.onclick = e => saveMarkup();

// -- Инициализация редактора CodeMirror --

let editor;
editorToLoader();

// События DOM редактора разметки
let editorEventHandlers = {
    // Вставка картинки
    // https://stackoverflow.com/a/6338207
    paste: function(e, ed) {
        let items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let index in items) {
            let item = items[index];

            // Этот элемент вставки - не файл
            if (item.kind !== 'file') {
                continue;
            }

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
                        window.editor.dispatch({
                            changes: {
                                from: window.editor.state.selection.main.head,
                                insert: "\n@img:"+response.filename+":Изображение"
                            }
                        });
                    }
                }
            });
        }
        return false;
    }
}

// Тема редактора
const
    ivory = "#b6c3cf",
    background = "#3B3B3B",
    cursor = "#E6E6FA",
    selection = "#2f5692",
    darkBackground = "#303030",
    stone = "#7d8799";
    
    
let agstTheme = EditorView.theme({
    "&": {
    color: ivory,
    backgroundColor: background
    },

    ".cm-content": {
        caretColor: cursor
    },

    ".cm-cursor, .cm-dropCursor": {borderLeftColor: cursor},
    "&.cm-focused > .cm-scroller > .cm-selectionLayer .cm-selectionBackground, .cm-selectionBackground, .cm-content ::selection": {backgroundColor: selection},

    ".cm-searchMatch": {
        backgroundColor: "#C9BD3E",
        outline: "2px solid #FF804C"
    },

    ".cm-activeLine": {backgroundColor: "#4343437f"},
    ".cm-selectionMatch": {backgroundColor: "#aafe661a"},

    ".cm-gutters, .cm-activeLineGutter": {
        backgroundColor: darkBackground,
        color: stone,
        border: "none"
    },
}, {dark: true});

// Тема синтаксиса
let agstHighlightStyle = HighlightStyle.define([
    {tag: tags.keyword, color: "#d58a4a"},
    {tag: tags.separator, color: "#b6c3cf"},
    {tag: tags.attributeValue, color: "#edc881"},
    {tag: tags.labelName, color: "#a88ab6"}
]);

// Язык
const AgstLanguage = StreamLanguage.define({
    name: "Autogost",
    startState: () => {
            return {lineKeyword: false, arg: false, argNum: 0}
    },
    token: function(stream, state) {

        // Ключевые слова:
        // @titlepage, @section, @- ...
        if (stream.match(/^@(\w|-|\\)*$/)) {
            return "keyword";
        }

        // Ключевые слова после которых идут аргументы
        if (stream.match(/^@(\w|-|\\)*/)) {
            state.lineKeyword = true;
            return "keyword";
        }

        // Двоеточие в конце
        if (state.lineKeyword && stream.match(/^:$/)) {
            state.lineKeyword = false;
            state.argNum = 0;
            return "separator";
        }

        // Двоеточие
        if (state.lineKeyword && stream.match(/^:/)) {
            state.argNum++;
            return "separator";
        }

        // Последний аргумент
        if (state.lineKeyword && stream.match(/^[^:]*$/, true, true)) {
            let output;

            if (state.argNum == 1) {
                output = "attributeValue";
            } else {
                output = "labelName";
            }

            state.lineKeyword = false;
            state.argNum = 0;
            
            return output;
        }

        // Не последний аргумент
        if (state.lineKeyword && stream.match(/^[^:]*/, true, true)) {
            if (state.argNum == 1) {
                return "attributeValue";
            }
            return "labelName";
        }

        stream.skipToEnd();
        return null;
    }
});

getMarkup(PHP_report_id).
then(async function(response) {
    let responseData = await response.json();
    let initialMarkup = responseData['markup'];

    let cmEditorState = EditorState.create({
        doc: initialMarkup,
        extensions: [
            basicSetup,
            EditorView.lineWrapping,
            autocompletion({override: [autogostCompletions]}),
            keymap.of([{
                key: "Ctrl-s",
                run() { saveMarkup(null); return true }
            }]),
            EditorView.domEventHandlers(editorEventHandlers),
            agstTheme,
            syntaxHighlighting(agstHighlightStyle),
            new LanguageSupport(AgstLanguage)
        ],
    });

    // CodeMirror init
    editor = new EditorView({
        state: cmEditorState,
        lineWrapping: true,
        parent: editorSection
    });

    // Скрыть загрузчик
    editorLoader.classList.add('hidden');
    
});