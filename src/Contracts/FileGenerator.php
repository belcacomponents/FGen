<?php

namespace Belca\FGen\Contracts;

/**
 * Интерфейс генерации и конвертации файла
 */
interface FileGenerator
{
    /**
     * Задает новую конфигурацию для обработки файла заменяя существующую.
     *
     * Конфигурация в себе хранит правила обработки, скрипты обработки.
     *
     * @param mixed $config Массив конфигурации
     */
    public function setConfig($config);

    /**
     * Добавляет новое значение конфигурации в существующую конфигурацию.
     * Если $replace - true, то заменяет конфликтующие значения.
     *
     * @param mixed   $config  Новое значение конфигурации
     * @param boolean $replace Замена существующей настройки
     */
    public function addConfig($config, $replace = true);

    /**
     * Возвращает конфигурацию. Если установлен тип конфигурации, то возвращает
     * указанный тип.
     *
     * @param  string $key Тип конфигурации
     * @return mixed
     */
    public function getConfig($key = null);

    /**
     * Устанавливает правила обработки файлов заменяя существующие.
     *
     * @param mixed $rules Правила обработки
     */
    public function setRules($rules);

    /**
     * Возвращает правила обработки файлов. Если указан драйвер, то возвращает
     * правила обработки указанного драйвера.
     *
     * @param  string $driver Драйвер обработки файла
     * @return mixed
     */
    public function getRules($driver = null);

    /**
     * Возвращает правила обработки конкретного драйвера. Если указан обработчик,
     * то возвращает значение конкретного обработчика.
     *
     * @param  string $driver  Драйвер обработки файла
     * @param  string $handler Обработчик файла
     * @return mixed
     */
    public function getRule($driver, $handler = null);

    /**
     * Добавляет новые правила в текущие правила обработки файлов. Если $replace
     * - true, то заменяет существующие правила обработки файла.
     *
     * @param mixed   $rules   Правила обработки файлов
     * @param boolean $replace Замена существующих правил
     */
    public function addRules($rules, $replace = true);

    /**
     * Добавляет новое правило для указанного драйвера и обработчика. Если
     * $replace - true, то заменяет существующее правило, иначе объединяет
     * значения.
     *
     * @param string  $driver  Драйвер обработки файла
     * @param string  $handler Обработчик файла
     * @param mixed   $rules   Правила обработки файла
     * @param boolean $replace Замена существующих правил
     */
    public function addRule($driver, $handler, $rules, $replace = true);

    /**
     * Добавляет нового обработчика для указанного драйвера.
     *
     * @param string  $driver  Драйвер обработки файла
     * @param string  $handler Обработчик
     * @param boolean $replace Замена существующего обработчика
     */
    public function addHandler($driver, $handler, $replace = true);

    // TODO конкретные типы или параметры или опции или настройки ... название надо
    public function addOption($driver, $handler, $option, $settings, $replace = true);

    /**
     * Устанавливает новый сценарии обработки файлов заменяя существующие.
     *
     * @param mixed $scripts Сценарии обработки файлов
     */
    public function setScripts($scripts);

    /**
     * Возвращает сценарии обработки файлов. Если указано значение $script, то
     * возвращает указанный сценарий.
     *
     * @param  string $script Сценарий обработки файла
     * @return mixed
     */
    public function getScripts($script = null);

    /**
     * Возвращает настройки указанного сценария.
     *
     * @param  $name $script Название сценария
     * @return mixed
     */
    public function getScript($script);

    /**
     * Добавляет новые сценарии в список сценариев. Если $replace - true, то
     * заменяет существующие данные указанного сценария.
     *
     * @param mixed   $scripts Список сценариев
     * @param boolean $replace Замена существующих сценариев
     */
    public function addScripts($scripts, $replace = true);

    public function addScript($name, $script, $replace = true);

    public function setDrivers($drivers);

    public function getDrivers($driver = null);

    public function getDriver($driver);

    public function addDrivers($drivers, $replace = true);

    public function addDriver($name, $driver, $replace = true);

    /**
     * Возвращает имя драйвера по указанному типу файла.
     *
     * @param  string $type Тип файла
     * @return string
     */
    public function getDriverNameByFileType($type);

    /**
     * Задает новые связи между списком драйверов и обрабатываемыми типами файлов.
     *
     * @param mixed $relations Массиов связей драйверов и типов файлов.
     */
    public function setRalations($relations);


    public function addRelation($relation, $replace = true);

    public function addFileTypes($driverName, $types, $replace = true);

    public function addFileType($driverName, $type, $replace = true);

    /**
     * Возвращает связи драйверов с типами файлов. Если указан конкретный драйвер,
     * то возвращает его список связей.
     *
     * @param  string $driverName Название драйвера
     * @return mixed
     */
    public function getRalations($driverName = null);

    /**
     * Возвращает связи указанного драйвера с типами файлов.
     *
     * @param  string $driverName Название драйвера
     * @return mixed
     */
    public function getRalation($driverName);

    /**
     * Задает новых Инспекторов для проверки типов файлов.
     *
     * @param mixed $inspectors Массив Инспекторов
     */
    public function setInspectors($inspectors);

    /**
     * Задает указанному драйверу нового Инспектора.
     *
     * @param string $driverName Имя драйвера
     * @param string $className  Имя класса-инспектора
     */
    public function addInspector($driverName, $className);

    /**
     * Возвращает Инспекторов файлов. Если указано имя драйвера, то возвращает
     * инспектора принадлежащему этому драйверу.
     *
     * @param  string $driverName Имя драйвера
     * @return mixed
     */
    public function getInspectors($driverName = null);

    /**
     * Возвращает класс Инспектора в соответствии с указанным драйвером.
     *
     * @param  string $driverName Имя драйвера
     * @return string
     */
    public function getInspector($driverName);

    /**
     * Задает новые обработчики файла заменяя предыдущие.
     *
     * @param mixed $handlers Массив обработчиков
     */
    public function setHandlers($handlers);

    /**
     * Возвращает список обработчиков файла. Если указано имя драйвера, то
     * возвращает обработчики конкретного драйвера.
     *
     * @param  string $driverName Имя драйвера
     * @return mixed
     */
    public function getHandlers($driverName = null);

    /**
     * Возвращает список обработчиков указанного драйвера.
     *
     * @param  string $driverName Имя драйвера
     * @return mixed
     */
    public function getHandlersByDriverName($driverName);

    /**
     * Добавляет новые обработчики файла. Если $replace - true, то заменяет
     * существующие обработчики файла.
     *
     * @param mixed   $handlers Список обработчиков
     * @param boolean $replace  Замена существующих обработчиков
     */
    public function addHandlers($handlers, $replace = true);

    /**
     * Добавляет нового обработчика к указанному драйверу.
     *
     * @param string  $driverName Имя драйвера
     * @param string  $className  Имя класса
     */
    public function addHandlerByDriverName($driverName, $className);

    // TODO получить список вариантов обработок указанного драйвера и обработчика

    /**
     * Задает класс-определитель драйвера (типа файла).
     *
     * @param string $className Имя класса
     */
    public function setDeterminer($className);

    /**
     * Возвращает класс-определитель драйвера.
     *
     * @return string
     */
    public function getDeterminer();

    public function file();

}
