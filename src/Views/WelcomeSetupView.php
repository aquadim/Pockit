<?php
// Страница первоначальной настройки

namespace Pockit\Views;

class WelcomeSetupView extends LayoutView {
	protected $page_title = "Первоначальная настройка";

    protected function customHead() : void { ?>

<link rel='preload' href='/img/stars.gif' as='image'>
<link rel='stylesheet' href='/css/welcomeSetup.css'>

    <?php } protected function customScripts() : void { ?>

<script src='/js/welcomeSetup.js'></script>
	
	<?php } public function content() : void { ?>

<div id='cardMain' class='card m-3'>
    <h1 class='card-title text-center'>Добро пожаловать в карманный сервер!</h1>

    <div class='toHide'>
        <p class='textwall'>
            Я очень рад что вы загрузили мою разработку! Перед началом работы,
            пожалуйста, укажите некоторые ваши данные для корректной работы
            приложения.
        </p>

        <form method='post' action='/api/welcomeRegister' id='formMain'>

            <div class="form-control-container">
                <label for="inpSurname">Фамилия</label>
                <input
                    class="form-control"
                    id="inpSurname"
                    type="text"
                    placeholder='Иванов'
                    name="surname"/>
            </div>
            
            <div class="form-control-container">
                <label for="inpName">Имя</label>
                <input
                    class="form-control"
                    id="inpName"
                    type="text"
                    placeholder='Иван'
                    name="name"/>
            </div>
            
            <div class="form-control-container">
                <label for="inpPatronymic">Отчество</label>
                <input
                    class="form-control"
                    id="inpPatronymic"
                    type="text"
                    placeholder='Иванович'
                    name="patronymic"/>
            </div>
            
            <div class="form-control-container">
                <label for="inpGroup">Твоя группа</label>
                <input
                    class="form-control"
                    id="inpGroup"
                    type="text"
                    placeholder="Например: 3ИС"
                    name="group"/>
            </div>
            
            <div class="form-control-container">
                <label for="inpCode">Твой шифр (начинается с точки)</label>
                <input
                    class="form-control"
                    id="inpCode"
                    type="text"
                    placeholder="Например: .012.09.02.07.000"
                    name="code"/>
            </div>
            
            <div class="form-control-container">
                <label for="inpLogin">
                    Логин от электронного дневника (необязательно)
                </label>
                <input
                    class="form-control"
                    id="inpLogin"
                    type="text"
                    name="login"/>
            </div>
            
            
            <div class="form-control-container">
                <label for="inpPassword">
                    Пароль от электронного дневника (необязательно)
                </label>
                <input
                    class="form-control"
                    id="inpPassword"
                    type="text"
                    name="password"/>
            </div>

            <button id='btnSubmit' type='submit' class='btn success w-100'>
                Пуск!
            </button>
        </form>
    </div>
    
</div>
		
<?php }
}
