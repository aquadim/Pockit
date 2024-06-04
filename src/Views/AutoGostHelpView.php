<?php
namespace Pockit\Views;

// Страница помощи Автогоста

class AutoGostHelpView extends LayoutView {
    protected $page_title = 'Помощь автогоста';

    public function content():void { ?>

<div class='card m-3'>
    <h1 class='text-center card-title'>Помощь автогоста</h1>

    <h3 class='textwall'>Общая информация</h3>
    <p class='textwall'>
        Автогост - подсистема, которая форматирует отчёты, преобразуя
        специальный язык в HTML, который можно скачать или распечатать.
    </p>
    <p class='textwall'>
        В режиме <span class='fg-accent'>разметки</span> вы пишите язык
        Автогоста. В режиме <span class='fg-accent'>превью</span> вы смотрите
        на то, каким получится отчёт при печатании.
    </p>
    <p class='textwall'>
        Отчёт состоит из <span class='fg-accent'>секций</span>. В каждой секции
        может быть несколько страниц. Страница отчёта автоматически добавляется,
        когда вы указываете <span class='fg-accent'>маркер страницы</span>.
        Маркеров страниц бывает несколько:
    </p>
    <ul class='textwall'>
        <li><span class='keyword'>@titlepage</span> добавляет титульный лист</li>
        <li><span class='keyword'>@practicetitle</span> добавляет титульный лист
        отчёта для практики</li>
        <li><span class='keyword'>@section</span> добавляет секцию отчёта</li>
        <li><span class='keyword'>@-</span> выполняет принудительный разрыв
        страницы</li>
    </ul>
    <p class='textwall'>
        На каждой строке распологается отдельный <span class='fg-accent'>абзац
        текста</span>. Вы не можете писать текст до любого маркера страницы,
        так как Автогост не знает какой странице/секции принадлежит текст.
        В случаее если вы случайно написали текст до маркеров страницы,
        Автогост выдаст сообщение об ошибке и строку, на которой она была
        найдена.
    </p>

    <h3 class='textwall'>Вставка изображений</h3>
    <p class='textwall'>
        Вставлять изображения можно несколькими способами:
    </p>
    <ul class='textwall'>
        <li>Вручную прописать ключевое слово <span class='keyword'>@img</span>,
        указав источник и подпись</li>
        <li>Скопировать изображение и вставить в редактор языка</li>
        <li>Перетащить изображение с рабочего стола в редактор языка</li>
        <li>Нажать на кнопку "Добавить изображения".</li>
    </ul>

    <h3 class='textwall'>Вставка таблиц</h3>
    <p class='textwall'>
        // TODO
    </p>
    
</div>
        
<?php }}
