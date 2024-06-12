const btnAddTheme = document.getElementById('btnAddTheme');
btnAddTheme.onclick = () => {
    crudCreateShowWindow(
        'themes',
        {
            'Файл темы': {type: "file", name: "themeFile", accept: '.zip'}
        },
        'Добавление темы',
        function() {},
        true
    )
}