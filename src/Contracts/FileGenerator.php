<?php

namespace Belca\FGen\Contracts;

use Belca\FGen\Contracts\FileTypeDeterminer;

/**
 * Интерфейс генерации и конвертации файла
 */
interface FileGenerator
{
    /**
     * Задает конфигурацию обработки файла.
     *
     * Конфигурация содержит правила обработки, скрипты обработки, список
     * обработчиков и драйверов.
     *
     * @param mixed $config
     */
    public function __construct($config = null, FileTypeDeterminer $fileTypeDeterminerClass);

    /**
     * Устанавливает путь директории в которой находится обрабатываемый файл.
     * При использовании директории, необходимо указывать относительный
     * путь к файлу.
     *
     * @param string $directory
     */
    public function setDirectory($directory = '');

    /**
     * Возвращает путь к директории с файлом.
     *
     * @return string
     */
    public function getDirectory();

    /**
     * Добавляет правила обработки файлов к существующим правилам. Если
     * $replace - true, то заменяет существующие правила обработки.
     *
     * @param mixed   $rules
     * @param boolean $replace
     */
    public function addRules($rules, $replace = true);

    /**
     * Добавляет правила обработки к указанному драйверу. Если
     * $replace - true, то заменяет существующие правила обработки.
     *
     * @param string  $driver
     * @param mixed   $rules
     * @param boolean $replace
     */
    public function addDriverRules($driver, $rules, $replace = true);

    /**
     * Добавляет новые правила для указанного драйвера и обработчика. Если
     * $replace - true, то заменяет существующее правило, иначе объединяет
     * значения с помощью функции array_merge().
     *
     * @param string  $driver  Драйвер обработки файла
     * @param string  $handler Обработчик файла
     * @param mixed   $rules   Правила обработки файла
     * @param boolean $replace Замена существующих правил
     */
    public function addHandlerRules($driver, $handler, $rules, $replace = true);

    /**
     * Добавляет параметры обработки к указанному варианту обработчика.
     *
     * @param string  $driver
     * @param string  $handler
     * @param string  $variant
     * @param array   $options
     * @param boolean $replace
     */
    public function addOptionsHanling($driver, $handler, $variant, $options, $replace = true);

    /**
     * Возвращает все правила обработки файлов.
     *
     * @return mixed
     */
    public function getRules();

    /**
     * Возвращает правила обработки конкретного драйвера.
     *
     * @param  string $driver  Драйвер обработки файла
     * @return mixed
     */
    public function getRulesByDriverName($driverName);

    /**
     * Возвращает правила обработки для указанного драйвера и обработчика.
     *
     * @param  string $driverName
     * @param  string $handlerName
     * @return mixed
     */
    public function getRulesByHandlerName($driverName, $handlerName);

    /**
     * Устанавливает новый сценарии обработки файлов заменяя существующие.
     *
     * @param mixed $scripts Сценарии обработки файлов
     */
    //public function setScripts($scripts);

    /**
     * Возвращает сценарии обработки файлов. Если указано значение $script, то
     * возвращает указанный сценарий.
     *
     * @param  string $script Сценарий обработки файла
     * @return mixed
     */
    //public function getScripts($script = null);

    /**
     * Возвращает настройки указанного сценария.
     *
     * @param  $name $script Название сценария
     * @return mixed
     */
    //public function getScript($script);

    /**
     * Добавляет новые сценарии в список сценариев. Если $replace - true, то
     * заменяет существующие данные указанного сценария.
     *
     * @param mixed   $scripts Список сценариев
     * @param boolean $replace Замена существующих сценариев
     */
    /*public function addScripts($scripts, $replace = true);

    public function addScript($name, $script, $replace = true);*/


    /**
     * Добавляет новые связи между списком драйверов и обрабатываемыми
     * типами файлов.
     *
     * @param mixed $relations Массиов связей драйверов и типов файлов.
     */
    public function addRelations($relations, $replace = true);

    /**
     * Добавляет указанные типы файлов к указанному драйверу.
     *
     * @param string  $driverName
     * @param array   $types
     * @param boolean $replace
     */
    public function addFileTypes($driverName, $types, $replace = true);

    /**
     * Добавляет новый тип файла к указанному драйверу.
     *
     * @param string  $driverName
     * @param string  $type
     * @param boolean $replace
     */
    public function addFileType($driverName, $type, $replace = true);

    /**
     * Возвращает связи драйверов с типами файлов.
     *
     * @return mixed
     */
    public function getRelations();

    /**
     * Возвращает связи указанного драйвера с типами файлов.
     *
     * @param  string $driverName
     * @return array|null
     */
    public function getRelation($driverName);

    /**
     * Возвращает имя драйвера по указанному типу файла.
     *
     * @param  string $type Тип файла
     * @return string|null
     */
    public function getDriverNameByFileType($type);

    /**
     * Добавляет новых Инспекторов для проверки типов файлов.
     *
     * @param mixed $inspectors Массив Инспекторов
     */
    public function addInspectors($inspectors, $replace = true);

    /**
     * Задает указанному драйверу нового Инспектора.
     *
     * @param string $driverName
     * @param string $className
     */
    public function addInspector($driverName, $className, $replace = true);

    /**
     * Возвращает Инспекторов файлов.
     *
     * @return mixed
     */
    public function getInspectors();

    /**
     * Возвращает класс Инспектора в соответствии с указанным драйвером.
     *
     * @param  string $driverName Имя драйвера
     * @return string
     */
    public function getInspector($driverName);

    /**
     * Проверяет, является ли класс обработчиком.
     *
     * @param  string  $className
     * @return boolean
     */
    public static function isHandler($className);

    /**
     * Добавляет новые обработчики файла. Если $replace - true, то заменяет
     * существующие обработчики файла.
     *
     * @param mixed   $handlers Список обработчиков с драйвером и именем обработчика
     * @param boolean $replace  Заменять существующие обработчики при совпадении.
     */
    public function addHandlers($handlers, $replace = true);

    /**
     * Добавляет нового обработчика для указанного драйвера.
     *
     * @param string  $driverName  Имя драйвера
     * @param string  $handlerName Имя драйвера
     * @param string  $className   Имя класса
     * @param boolean $replace     Замена существующего обработчика
     */
    public function addHandler($driverName, $handlerName, $className, $replace = true);

    /**
     * Возвращает список обработчиков файла.
     *
     * @return mixed
     */
    public function getHandlers();

    /**
     * Возвращает список обработчиков указанного драйвера.
     *
     * @param  string $driverName
     * @return mixed
     */
    public function getHandlersByDriverName($driverName);

    /**
     * Возвращает класс обработчика по имени драйвера и имени обработчика.
     *
     * @param  string $driverName
     * @param  string $handler
     * @return string
     */
    public function getHandler($driverName, $handler, \Belca\FGen\Contracts\FileHandler $defaultHandlerClass = null);

    /**
     * Возвращает класс-определитель драйвера.
     *
     * @return string
     */
    public function getDeterminer();

    /**
     * Конвертирует сценарий обработки файла в правила обработки файла.
     *
     * @param  mixed $script
     * @return mixed
     */
    public static function scriptToRules($script);

    /**
     * Вызывает обработку файла, сохраняет полученные значения и возвращает их.
     *
     * @param  string $filename  Путь к файлу
     * @param  array  $script    Параметры обработки (список сценариев)
     * @param  string $directory Директория для сохранения обработанных файлов
     * @return mixed
     */
    public function file($filename, $script = [], $directory = null);

    /**
     * Запускает обработку указанного файла по указанному драйверу и
     * правилам обработки. Сохранение новых файлов происходит в указанную
     * директорию.
     *
     * @param  string $filename
     * @param  string $driverName
     * @param  array  $rules
     * @param  string $directory
     * @return mixed
     */
    public function handle($filename, $driverName, $rules, $directory);

    /**
     * Возвращает данные после обработки (вызова метода file()). Возвращает ту
     * же информацию, что и метод file().
     *
     * @return mixed
     */
    public function getDataAfterHandling();

}
