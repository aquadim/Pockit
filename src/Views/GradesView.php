<?php
namespace Pockit\Views;

// Оценки

class GradesView extends LayoutView {

	public function customHead() { ?>
<link rel='stylesheet' href='/css/grades.css'>
	<?php }

	public function content():void { ?>

<h1 class='text-center'>Оценки</h1>

<svg id="loading" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:transparent;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
<g transform="translate(80,50)">
<g transform="rotate(0)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="1">
<animateTransform attributeName="transform" type="scale" begin="-0.875s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.875s"></animate>
</circle>
</g>
</g><g transform="translate(71.21320343559643,71.21320343559643)">
<g transform="rotate(45)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.875">
<animateTransform attributeName="transform" type="scale" begin="-0.75s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.75s"></animate>
</circle>
</g>
</g><g transform="translate(50,80)">
<g transform="rotate(90)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.75">
<animateTransform attributeName="transform" type="scale" begin="-0.625s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.625s"></animate>
</circle>
</g>
</g><g transform="translate(28.786796564403577,71.21320343559643)">
<g transform="rotate(135)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.625">
<animateTransform attributeName="transform" type="scale" begin="-0.5s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.5s"></animate>
</circle>
</g>
</g><g transform="translate(20,50.00000000000001)">
<g transform="rotate(180)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.5">
<animateTransform attributeName="transform" type="scale" begin="-0.375s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.375s"></animate>
</circle>
</g>
</g><g transform="translate(28.78679656440357,28.786796564403577)">
<g transform="rotate(225)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.375">
<animateTransform attributeName="transform" type="scale" begin="-0.25s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.25s"></animate>
</circle>
</g>
</g><g transform="translate(49.99999999999999,20)">
<g transform="rotate(270)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.25">
<animateTransform attributeName="transform" type="scale" begin="-0.125s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="-0.125s"></animate>
</circle>
</g>
</g><g transform="translate(71.21320343559643,28.78679656440357)">
<g transform="rotate(315)">
<circle cx="0" cy="0" r="6" fill="#7286ff" fill-opacity="0.125">
<animateTransform attributeName="transform" type="scale" begin="0s" values="1.5 1.5;1 1" keyTimes="0;1" dur="1s" repeatCount="indefinite"></animateTransform>
<animate attributeName="fill-opacity" keyTimes="0;1" dur="1s" repeatCount="indefinite" values="1;0" begin="0s"></animate>
</circle>
</g>
</g>
</svg>

<script>
// Создаём запрос на сервер для получения оценок
const xhr = new XMLHttpRequest();
xhr.onload = function () {
	document.getElementById("loading").remove();
	let data = JSON.parse(this.responseText);
	let table = document.createElement("table");

	// Заголовки
	let header = document.createElement("tr");
	let cell = document.createElement("th"); cell.textContent = "Дисциплина"; header.appendChild(cell);
	cell = document.createElement("th"); cell.textContent = "Оценки"; header.appendChild(cell);
	cell = document.createElement("th"); cell.textContent = "Средний балл"; header.appendChild(cell);
	table.appendChild(header);

	for (let y = 0; y < data.length; y++) {
		let row = document.createElement("tr");

		cell = document.createElement("td"); cell.textContent = data[y][0]; row.appendChild(cell);
		cell = document.createElement("td"); cell.textContent = data[y][1]; row.appendChild(cell);
		cell = document.createElement("td"); cell.textContent = data[y][2]; row.appendChild(cell);

		if (data[y][1].indexOf('2') > -1) {
			row.classList.add("problem");
		} else {
			if (data[y][1].indexOf('3') == -1 && data[y][1].indexOf('4') == -1) {
				row.classList.add("perfect");
			}
		}

		table.appendChild(row);
	}
	document.body.appendChild(table);
	
}
xhr.open("GET", "/grades/get");
xhr.send();
</script>
		
<?php }}
