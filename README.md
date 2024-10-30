### Обновление до более новой версии
1. Отыщите файл db.sqlite3, скопируйте его в папку нового релиза, замените ![изображение](https://github.com/user-attachments/assets/bbbaf8ff-e9ec-450a-be1b-805c8ceed7f8)
2. Скопируйте все файлы из папки wwwroot/img/autogost в ту же самую папку, но нового релиза, замените все ![изображение](https://github.com/user-attachments/assets/5acaffd1-32c2-4c25-be1f-3c0694254fa6)

### Установка / Подготовка к релизу
Чтобы подготовить текущую версию к релизу, выполнить следующие команды в корневой директории
1. `composer install`
2. `npm install`
3. `php bin/doctrine.php orm:schema-tool:create`
4. `php bin/setup/seed.php`
5. `npx rollup -c bundleConfigs/editor.js`
6. Создать скрипт запуска `start.bat` для Windows для переносной версии

### Запуск
#### Windows:
Если php присутствует в PATH, запустить файл `bin/winStart.cmd` в корневой директории проекта.
Иначе, запустить `start.bat` релиза.
#### Unix подобные (Linux & MacOS):
Запустить `bin/unixStart.sh`, в корневой директории проекта. Затем зайдите в любом браузере по адресу: http://localhost:9000/
