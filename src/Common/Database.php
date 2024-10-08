<?php
// Класс работы с БД

namespace Pockit\Common;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class Database {
    private static $db;
    private $connection;
    private $entity_manager;
    
    private function __construct() {
        // Подключение к БД
        $dsnParser = new DsnParser();
        $connection_params = $dsnParser->parse(dsn);
        $this->connection = DriverManager::getConnection($connection_params);

        // Получение менеджера сущностей
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(index_dir . '/src/Models'),
            isDevMode: true,
        );
        $this->entity_manager = new EntityManager($this->connection, $config);
    }

    // Инициализирует БД
    public static function init(): void {
        self::$db = new Database();
    }

    // Возвращает ссылку на $connection
    public static function getConnection() {
        if (self::$db == null) {
            self::init();
        }
        return self::$db->connection;
    }

    // Возвращает ссылку на $entity_manager
    public static function getEM() {
        if (self::$db == null) {
            self::init();
        }
        return self::$db->entity_manager;
    }
}
