<?php
namespace Pockit\Views;

// Страница помощи Автогоста

class AutoGostHelpView extends LayoutView {
    protected $page_title = 'Справка Автогоста';

    public function content():void { ?>

<div class='card m-3'>
    <h1 class='text-center card-title'>Справка Автогоста</h1>

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
        текста</span>.
    </p>

    <h3 class='textwall'>Вставка изображений</h3>
    <p class='textwall'>
        Вставлять изображения можно несколькими способами:
    </p>
    <ul class='textwall'>
        <li>Скопировать изображение и вставить в редактор</li>
        <li>
            Нажать на кнопку "Добавить изображения" и выбрать необходимые файлы
        </li>
        <li>Вручную прописать ключевое слово <span class='keyword'>@img</span>,
        указав источник и подпись (не рекомендуется)</li>
    </ul>

    <h3 class='textwall'>Вставка таблиц</h3>
    <p class='textwall'>
        Для вставки в отчёт таблицы, необходимо выполнить следующие действия:
    </p>
    <ol class='textwall'>
        <li>Создайте таблицу в Excel;</li>
        <li>Сохраните файл <span class='fg-accent'>как CSV</span>;</li>
        <li>
            При редактировании отчёта нажмите на кнопку "Добавить таблицу";
        </li>
        <li>
            Выберите файл CSV, который вы сохранили и укажите разделитель
            данных. Чаще всего им является символ запятой (,). Так же
            потребуется ввести подпись;
        </li>
        <li>
            Нажмите на кнопку "Готово". После этого на место курсора
            вставится таблица с данными из CSV файла.
        </li>
    </ol>
    <p class='textwall'>
        Ограничения функционала вставки таблиц:
    </p>
    <ul class='textwall'>
        <li>В настоящее время в таблицы нельзя вставлять рисунки.</li>
    </ul>

    <h3 class='textwall'>Как сохранить отчёт в PDF</h3>
    <p class='textwall'>
        Нажмите на кнопку "Печать" на странице редактирования отчёта.
    </p>
    <img
        style='max-width:80%;margin-left:50%;transform:translateX(-50%)'
        src='/img/agsthelp/printButton.png'/>
    <p class='textwall'>
        Если ошибок разметки не обнаружено, должен открыться диалог
        печати браузера. Для того чтобы отчёт сохранился правильно, укажите
        следующие параметры при печати (возможно потребуется открыть
        <span class='fg-accent'>дополнительные настройки печати</span>,
        ниже приведён скриншот диалога печати Firefox с указанием расположения
        дополнительных параметров):
    </p>
    <img
        style='max-width:80%;margin-left:50%;transform:translateX(-50%)'
        src='/img/agsthelp/printAll.png'/>
    <ul class='textwall'>
        <li>
            Масштаб: <span class='fg-accent'>По ширине страницы</span>
        </li>
        <li>
            Поля: <span class='fg-accent'>Нет</span>
        </li>
        <li>
            Печатать колонтитулы: <span class='fg-accent'>Нет</span>
        </li>
        <li>
            Печатать фон: <span class='fg-accent'>Да</span>
        </li>
    </ul>
    <p class='textwall'>
        После этого нажмите на кнопку "Сохранить", после чего откроется
        диалог сохранения файла. Назвать файл будет удобнее всего, если
        нажать на кнопку <span class='fg-accent'>"Скопировать название файла"
        </span> на странице и потом вставить скопированный текст.
    </p>
    
</div>
        
<?php }}
