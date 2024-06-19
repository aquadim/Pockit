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
Запустить `bin/unixStart.cmd`, в корневой директории проекта.