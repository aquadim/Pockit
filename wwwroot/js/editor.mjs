// TODO:
// 2. Ctrl+V - вставка картинки (сделано наполовину)
// 3. Стилизация
// 5. Подсветка синтаксиса

import {EditorView, basicSetup} from "codemirror"
import {EditorState} from "@codemirror/state"
import {autocompletion} from "@codemirror/autocomplete"
import {keymap} from "@codemirror/view"

const completions = [
	{label: "@titlepage", type: "keyword", info: "Титульная страница"},
	{label: "@section:название", type: "keyword", info: "Секция основной части"},
	{label: "@-", type: "keyword", info: "Разрыв страницы"},
	{label: "@img:источник:подпись", type: "keyword", info: "Изображение"},
	{label: "@\\", type: "keyword", info: "Пустая строка"},
	{label: "@raw", type: "keyword", info: "Начало чистого HTML"},
	{label: "@endraw", type: "keyword", info: "Конец чистого HTML"},
];

function myCompletions(context) {
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

let handlers = {
    paste: function(e, ed) {
        console.log("PASTED");
        return true;
    }
}

let editorState = EditorState.create({
	doc: globalReportMarkup,
	extensions: [
		basicSetup,
		EditorView.lineWrapping,
		autocompletion({override: [myCompletions]}),
		keymap.of([{
            key: "Ctrl-s",
            run() { saveMarkup(false); return true }
		}]),
        EditorView.domEventHandlers(handlers)
	],
});

let editor = new EditorView({
	state: editorState,
	lineWrapping: true,
	parent: document.getElementById("newEditor")
});

window.editor = editor;