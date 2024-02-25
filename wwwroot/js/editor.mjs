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
import { tags as t } from "@lezer/highlight";

// Все ключевые слова
const completions = [
	{label: "@titlepage", type: "keyword", info: "Титульная страница"},
	{label: "@section:название", type: "keyword", info: "Секция основной части"},
	{label: "@-", type: "keyword", info: "Разрыв страницы"},
	{label: "@img:источник:подпись", type: "keyword", info: "Изображение"},
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
	paste: function(e, ed) {
		console.log("PASTED");
		return true;
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
        backgroundColor: "rgba(255,255,255,0.1)"
    },
    ".cm-selectionBackground, &.cm-focused > .cm-scroller > .cm-selectionLayer .cm-selectionBackground": {
        background: "var(--accent)"
    }
}, {dark: true});

let agstHighlightStyle = HighlightStyle.define([
	{tag: t.keyword, color: "#9171E8"},
	{tag: t.separator, color: "#FF813B"},
	{tag: t.attributeValue, color: "#E8922A"}
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

        if (stream.match(/^@(\w)*/)) {
            state.lineKeyword = true;
            return "keyword";
        }

        if (state.lineKeyword && stream.match(/^:$/)) {
			state.lineKeyword = false;
			return "separator";
		}

        if (state.lineKeyword && stream.match(":")) {
            return "separator";
        }

        if (state.lineKeyword && stream.match(/^[а-я \w]+$/i, true, true)) {
            state.lineKeyword = false;
            return "attributeValue";
        }

        if (state.lineKeyword && stream.match(/^[а-я \w]+/i, true, true)) {
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