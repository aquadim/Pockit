// TODO:
// 2. Ctrl+V - вставка картинки (сделано наполовину)
// 5. Подсветка синтаксиса

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

// Все ключевые слова
const completions = [
	{label: "@titlepage", type: "keyword", info: "Титульная страница"},
	{label: "@section:Название", type: "keyword", info: "Секция основной части"},
	{label: "@-", type: "keyword", info: "Разрыв страницы"},
	{label: "@img:Источник:Подпись", type: "keyword", info: "Изображение"},
	{label: "@\\", type: "keyword", info: "Пустая строка"},
	{label: "@raw", type: "keyword", info: "Начало чистого HTML"},
	{label: "@endraw", type: "keyword", info: "Конец чистого HTML"},
];

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

// События DOM
let handlers = {
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

// Тема
let agstTheme = EditorView.theme({
   "&": {
        color: "var(--gray-9)",
        backgroundColor: "var(--gray-4)"
   },
   ".cm-cursor": {
       borderLeftColor: "white"
   },
   "& .cm-gutters": {
        color: "var(--gray-7)",
        backgroundColor: "var(--gray-2)"
    },
    "& .cm-activeLine, & .cm-activeLineGutter": {
        backgroundColor: "rgba(0,0,0,0.15)"
    },
    ".cm-selectionBackground, &.cm-focused > .cm-scroller > .cm-selectionLayer .cm-selectionBackground": {
        background: "rgba(255,255,255,0.10)"
    }
}, {dark: true});

// Тема синтаксиса
let agstHighlightStyle = HighlightStyle.define([
	{tag: tags.keyword, color: "#E9E564"},
	{tag: tags.separator, color: "#F45F3B"},
	{tag: tags.attributeValue, color: "#D25A68"}
]);

// Подсветка синтаксиса
const AgstLanguage = StreamLanguage.define({
   	name: "Autogost",
   	startState: () => {
   		return {lineKeyword: false, arg: false}
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
			return "separator";
		}

		// Двоеточие
        if (state.lineKeyword && stream.match(/^:/)) {
            return "separator";
        }

		// Последний аргумент
        if (state.lineKeyword && stream.match(/^[^:]*$/, true, true)) {
            state.lineKeyword = false;
            return "attributeValue";
        }

		// Не последний аргумент
        if (state.lineKeyword && stream.match(/^[^:]*/, true, true)) {
            return "attributeValue";
        }

        stream.skipToEnd();
   		return null;
   	}
});

// Инициализация редактора
let editorState = EditorState.create({
	doc: globalReportMarkup,
	extensions: [
		basicSetup,
		EditorView.lineWrapping,
		autocompletion({override: [autogostCompletions]}),
		keymap.of([{
			key: "Ctrl-s",
			run() { saveMarkup(false); return true }
		}]),
		EditorView.domEventHandlers(handlers),
		agstTheme,
		syntaxHighlighting(agstHighlightStyle),
		new LanguageSupport(AgstLanguage)
	],
});

let editor = new EditorView({
	state: editorState,
	lineWrapping: true,
	parent: document.getElementById("agstEditor")
});

window.editor = editor;