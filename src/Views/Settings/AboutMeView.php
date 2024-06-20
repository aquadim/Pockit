<?php
// Страница настройки информации "обо мне"

namespace Pockit\Views\Settings;

use Pockit\Views\LayoutView;

class AboutMeView extends LayoutView {
    protected $page_title = 'Настройки информации обо мне';
    protected $crumbs = [
        'Главная' => '/',
        'Настройки' => "/settings",
        'Обо мне' => ''
    ];
    protected $surname;
    protected $name;
    protected $group;
    protected $code;
    protected $login;

    public function customScripts() : void { ?>
<script src='/js/aboutSettings.js'></script>
	
	<?php } public function content() : void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Настройки автогоста</h1>

    <form action="/api/saveAboutMeSettings" method="post" id='formMain'>

        <div class="form-control-container">
			<label for="inpSurname">Фамилия</label>
            <input
                type='text'
                class='form-control'
                id='inpSurname'
                name='surname'
                value='<?=$this->surname?>'/>
		</div>

        <div class="form-control-container">
			<label for="inpName">Имя</label>
            <input
                type='text'
                class='form-control'
                id='inpName'
                name='name'
                value='<?=$this->name?>'/>
		</div>

        <div class="form-control-container">
			<label for="inpPatronymic">Отчество</label>
            <input
                type='text'
                class='form-control'
                id='inpPatronymic'
                name='patronymic'/>
		</div>

        <div class="form-control-container">
			<label for="inpGroup">Группа</label>
            <input
                type='text'
                class='form-control'
                id='inpGroup'
                name='group'
                placeholder='3ИС'
                value='<?=$this->group?>'/>
		</div>

        <div class="form-control-container">
			<label for="inpCode">Шифр (точка первая)</label>
            <input
                type='text'
                class='form-control'
                id='inpCode'
                name='code'
                placeholder='.012.09.02.07.000'
                value='<?=$this->code?>'/>
		</div>

        <div class="form-control-container">
			<label for="inpLogin">Логин от АВЕРС</label>
            <input
                type='text'
                class='form-control'
                id='inpLogin'
                name='login'
                placeholder='KOROLEVVS'
                value='<?=$this->login?>'/>
		</div>

        <div class="form-control-container">
			<label for="inpPassword">Пароль от АВЕРС</label>
            <input
                type='password'
                class='form-control'
                id='inpPassword'
                name='password'
                value='****'/>
		</div>
        
        <button class='btn success w-100'>Сохранить</button>
    </form>
</div>

<?php }
}
