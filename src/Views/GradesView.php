<?php
namespace Pockit\Views;

// Оценки

class GradesView extends LayoutView {

	public function customHead() { ?>
<link rel='stylesheet' href='/css/grades.css'>
<link rel='stylesheet' href='/css/spinner.css'>
	<?php }

	public function content():void { ?>

<div class="card m-1" id="gradesCard">
	<h1 class='text-center'>Оценки</h1>
	<div class="loader" id="loading"></div>
</div>

<script>
// Создаём запрос на сервер для получения оценок
const xhr = new XMLHttpRequest();
xhr.onload = function () {
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

	document.getElementById("loading").remove();
	document.getElementById("gradesCard").appendChild(table);
	
}
xhr.open("GET", "/grades/get");
xhr.send();
</script>
		
<?php }}
