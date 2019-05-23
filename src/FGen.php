<?php

namespace Belca\FGen;

use Belca\FGen\Contracts\FileGenerator;
use Belca\FGen\Contracts\FileTypeDeterminer;

class FGen implements FileGenerator
{
    /**
     * Директория с файлом.
     *
     * Директорию необходимо задавать при работе с относительным путем к файлу.
     *
     * @var string
     */
    protected $directory;

    /**
     * Класс-определитель типа файла.
     *
     * @var string
     */
    protected static $determinerClass = \Belca\FGen\Determiner::class;

    /**
     * Связи между типами файлов и драйверами.
     *
     * Пример хранимых значений:
     * $relations = [
     *     'image/jpeg' => 'jpg',
     *     'image/png' => 'png',
     * ];
     *
     * @var mixed
     */
    protected $relations = [];

    // Получить файл
    // Получить способ обработки
    // Загрузить конфигурацию или сведения об обработке, т.е. должна быть загружена конфигурация обработки или класс или массив правил
    // Отправить на обработку
    // Проверить возможность обработки, проверить файл, отправить в конкретный класс для обработки
    // Вернуть сведения о файле

    /**
     * Тип файла соответствует имени драйвера.
     *
     * Используется, когда не все типы данных указаны и типы файла или их
     * расширений могут соответствовать названиям драйверов.
     *
     * @var bool
     */
    protected $fileTypeEqualDriverName = true;

    /**
     * Обработка нулевой конфигурации.
     *
     * Если при обработке файла не задана или не найдена текущая конфигурация
     * обработки файла, то выполняется обработка в соответствии с правилами
     * заданными в определенном драйвере.
     *
     * @var boolean
     */
    protected $zeroConfigHandling = true;

    /**
     * Правила обработки файлов.
     *
     * @var mixed
     */
    protected $rules;

    /**
     * Обработчики файлов.
     *
     * Пример хранимых значений:
     * $handlers = [
     *    'image/jpeg' => [
     *        '_class' => '\Belca\File\FGenHandlers\SimpleImageHandler',
     *        'resize' => '\Belca\File\FGenHandlers\ResizeJpegHandler',
     *        'signature' => '\Belca\File\FGenHandlers\SignatureJpegHandler',
     *    ],
     *    'image/png' => [
     *        '_class' => '\Belca\File\FGenHandlers\SimpleImageHandler',
     *    ],
     * ];
     *
     * @var mixed
     */
    protected $handlers;

    /**
     * Инициализированные экземпляры классов-обработчиков.
     *
     * @var array
     */
    protected $instances;

    /**
     * Данные обработки файла.
     *
     * @var mixed
     */
    protected $data;

    public function __construct($config = null, FileTypeDeterminer $fileTypeDeterminerClass = null)
    {
        // Проверить наличие всех типов переменных и передать их

        if (isset($fileTypeDeterminerClass)) {
            $this->setDeterminer($fileTypeDeterminerClass);
        }
    }

    /**
     * Устанавливает путь директории в которой находится обрабатываемый файл.
     * При использовании директории, необходимо указывать относительный
     * путь к файлу.
     *
     * @param string $directory
     */
    public function setDirectory($directory = '')
    {
        if (is_dir($directory)) {
            $this->directory = $directory;
        }
    }

    /**
     * Возвращает путь к директории с файлом.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory ?? '';
    }

    /**
     * Добавляет правила обработки файлов к существующим правилам. Если
     * $replace - true, то заменяет существующие правила обработки.
     *
     * @param mixed   $rules
     * @param boolean $replace
     */
    public function addRules($rules, $replace = true)
    {
        if (! empty($rules) && is_array($rules)) {
            foreach ($rules as $driver => $driverRules) {
                $this->addDriverRules($driver, $driverRules, $replace);
            }
        }
    }

    /**
     * Добавляет правила обработки к указанному драйверу. Если
     * $replace - true, то заменяет существующие правила обработки.
     *
     * @param string  $driver
     * @param mixed   $rules
     * @param boolean $replace
     */
    public function addDriverRules($driver, $rules, $replace = true)
    {
        if (! empty($rules) && is_array($rules)) {
            foreach ($rules as $handler => $handlerRules) {
                $this->addHandlerRules($driver, $handler, $handlerRules, $replace);
            }
        }
    }

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
    public function addHandlerRules($driver, $handler, $rules, $replace = true)
    {
        if (! empty($rules) && is_array($rules)) {
            foreach ($rules as $variant => $options) {
                $this->addOptionsHanling($driver, $handler, $variant, $options, $replace);
            }
        }

    }

    /**
     * Добавляет параметры обработки к указанному варианту обработчика.
     *
     * @param string  $driver
     * @param string  $handler
     * @param string  $variant
     * @param array   $options
     * @param boolean $replace
     */
    public function addOptionsHanling($driver, $handler, $variant, $options, $replace = true)
    {
        if (isset($this->rules[$driver][$handler][$variant]) && $replace) {
            $this->rules[$driver][$handler][$variant] = array_merge($this->rules[$driver][$handler][$variant], $options);
        }

        if (empty($this->rules[$driver][$handler][$variant]) && is_array($options)) {
            $this->rules[$driver][$handler][$variant] = $options;
        }
    }

    /**
     * Возвращает все правила обработки файлов.
     *
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules ?? [];
    }

    /**
     * Возвращает правила обработки конкретного драйвера.
     *
     * @param  string $driver  Драйвер обработки файла
     * @return mixed
     */
    public function getRulesByDriverName($driverName)
    {
        return $this->rules[$driverName] ?? [];
    }

    /**
     * Возвращает правила обработки для указанного драйвера и обработчика.
     *
     * @param  string $driverName
     * @param  string $handlerName
     * @return mixed
     */
    public function getRulesByHandlerName($driverName, $handlerName)
    {
        return $this->rules[$driver][$handler] ?? [];
    }

    /**
     * Добавляет новые связи между списком драйверов и обрабатываемыми
     * типами файлов.
     *
     * @param mixed $relations Массиов связей драйверов и типов файлов.
     */
    public function addRelations($relations, $replace = true)
    {
        if (! empty($relations) && is_array($relations)) {
            foreach ($relations as $driver => $types) {
                $this->addFileTypes($driver, $types, $replace);
            }
        }
    }

    /**
     * Добавляет указанные типы файлов к указанному драйверу.
     *
     * @param string  $driverName
     * @param array   $types
     * @param boolean $replace
     */
    public function addFileTypes($driverName, $types, $replace = true)
    {
        if (! empty($types) && is_array($types)) {
            foreach ($types as $type) {
                $this->addFileType($driverName, $type, $replace);
            }
        }
    }

    /**
     * Добавляет новый тип файла к указанному драйверу.
     *
     * @param string  $driverName
     * @param string  $type
     * @param boolean $replace
     */
    public function addFileType($driverName, $type, $replace = true)
    {
        if (((isset($this->relations[$type]) && $replace) || empty($this->relations[$type])) && is_string($type)) {
            $this->relations[$type] = $driverName;
        }
    }

    /**
     * Возвращает связи драйверов с типами файлов.
     *
     * @return mixed
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Возвращает связи указанного драйвера с типами файлов.
     *
     * @param  string $driverName Название драйвера
     * @return array|null
     */
    public function getRelation($driverName)
    {
        return array_keys($this->relations, $driverName) ?? ($this->fileTypeEqualDriverName ? $driverName : null);
    }

    /**
     * Задает состояние равнозначности типа файла и имя драйвера.
     *
     * @param  boolean $state
     */
    public function fileTypeEqualDriverName($state = true)
    {
        $this->fileTypeEqualDriverName = is_bool($state) ? $state : true;
    }

    /**
     * Проверяет, включено ли соответствие между типом файла и драйвером.
     *
     * @return boolean
     */
    public function isFileTypeEqualDriverName()
    {
        return $this->fileTypeEqualDriverName;
    }

    /**
     * Возвращает имя драйвера по указанному типу файла.
     *
     * @param  string $type Тип файла
     * @return string
     */
    public function getDriverNameByFileType($type)
    {
        return $this->relations[$type] ?? ($this->fileTypeEqualDriverName ? $type : null);
    }

    /**
     * Добавляет новых Инспекторов для проверки типов файлов.
     *
     * @param mixed $inspectors Массив Инспекторов
     */
    public function addInspectors($inspectors, $replace = true)
    {

    }

    /**
     * Задает указанному драйверу нового Инспектора.
     *
     * @param string $driverName
     * @param string $className
     */
    public function addInspector($driverName, $className, $replace = true)
    {

    }

    /**
     * Возвращает Инспекторов файлов.
     *
     * @return mixed
     */
    public function getInspectors()
    {
        return $this->inspectors;
    }

    /**
     * Возвращает класс Инспектора в соответствии с указанным драйвером.
     *
     * @param  string $driverName Имя драйвера
     * @return string
     */
    public function getInspector($driverName)
    {
        return $this->inspectors[$driverName] ?? null;
    }

    /**
     * Проверяет, является ли класс обработчиком.
     *
     * @param  string  $className
     * @return boolean
     */
    public static function isHandler($className)
    {
        return isset($className) && class_exists($className) && is_subclass_of($className, \Belca\FGen\Contracts\FileHandler::class);
    }

    /**
     * Добавляет обработчиков файла. Если $replace - true, то будут заменены
     * существующие обработчики файла.
     *
     * @param mixed   $handlers Список обработчиков с драйвером и именем обработчика
     * @param boolean $replace  Заменять существующие обработчики при совпадении.
     */
    public function addHandlers($handlers, $replace = true)
    {
        if (! empty($handlers) && is_array($handlers)) {
            foreach ($handlers as $driver => $values) {
                if (is_array($values)) {
                    foreach ($values as $handlerName => $handlerClass) {
                        // Глобальный класс
                        if ($handlerName == '_class') {
                            $this->addHandler($driver, null, $handlerClass, $replace);
                        }

                        $this->addHandler($driver, $handlerName, $handlerClass, $replace);
                    }
                } elseif (is_string($values)) {
                    // Глобальный класс
                    $this->addHandler($driver, null, $values, $replace);
                }
            }
        }
    }

    /**
     * Добавляет нового обработчика для указанного драйвера.
     *
     * @param string  $driverName  Имя драйвера
     * @param string  $handlerName Имя драйвера
     * @param string  $className   Имя класса
     * @param boolean $replace     Замена существующего обработчика
     */
    public function addHandler($driverName, $handlerName, $className, $replace = true)
    {
        if (static::isHandler($className)) {
            if ((isset($this->handlers[$driverName][$handlerName]) && $replace) || empty($this->handlers[$driverName][$handlerName])) {
                $this->handlers[$driverName][$handlerName ?? '_class'] = $className;
            }
        }
    }

    /**
     * Возвращает список обработчиков файла.
     *
     * @return mixed
     */
    public function getHandlers()
    {
        return $this->handlers ?? [];
    }

    /**
     * Возвращает список обработчиков указанного драйвера.
     *
     * @param  string $driverName
     * @return mixed
     */
    public function getHandlersByDriverName($driverName)
    {
        return $this->handlers[$driverName] ?? null;
    }

    /**
     * Возвращает класс обработчика по имени драйвера и имени обработчика.
     * Класс обработчика определяется с учетом глобального обработчика драйвера.
     *
     * @param  string $driverName
     * @param  string $handler
     * @return string|null
     */
    public function getHandler($driverName, $handler, \Belca\FGen\Contracts\FileHandler $defaultHandlerClass = null)
    {
        return $this->handlers[$driverName][$handler] ?? $this->handlers[$driverName]['_class'] ?? $defaultHandlerClass;
    }

    /**
     * Задает класс-определитель драйвера (типа файла).
     *
     * @param string $className Имя класса
     */
    protected function setDeterminer(FileTypeDeterminer $className)
    {
        static::$determiner = $className;
    }

    /**
     * Возвращает класс-определитель драйвера.
     *
     * @return string
     */
    public function getDeterminer()
    {
        return static::$determiner;
    }

    /**
     * Возвращает правила обработки по конфигурации определенного драйвера.
     *
     * @param  mixed $script
     * @return mixed
     */
    public static function scriptToRules($script)
    {
      // TODO на основе заданных данных получить правила обработки
      // 'jpg' => [
      //    'original',
      //    'thumbnail',
      //    'resize' => ['tiny', 'small', 'normal'],
      //    'signature',
      //],
    }

    /**
     * Вызывает обработку файла.
     *
     * @param  string $filename  Путь к файлу
     * @param  array  $script    Параметры обработки (список сценариев)
     * @param  string $directory Директория для сохранения обработанных файлов
     * @return mixed
     */
    public function file($filename, $script = null, $directory = null)
    {
        $this->data = null;

        $fileDirectory = $this->getDirectory();

        $fullname = $fileDirectory.'/'.$filename;

        if (! is_file($fullname)) {
            return false;
        }

        // Определяем тип файла для получения драйвера обработки файла.
        // Если драйвер для обработки по типу файла не определен и
        // включено равенство типа и названия драйвера, то в качестве имени
        // драйвера используется тип файла.
        $determiner = new static::$determinerClass($fullname);

        $filetype = $determiner->getFileType();

        $driverName = $this->getDriverNameByFileType($filetype);

        if (empty($driverName) && $this->isFileTypeEqualDriverName()) {
            $driverName = $filetype;
        }

        $inspectorClass = $this->getInspector($driverName);

        // Если класс Инспектора указанного драйвера найден, то вернуть его
        // и проверить на соответствие типа файла.
        // Если Инспектор не найден, то считать, что файл успешно прошел
        // проверку на соответствие типа.
        if ($inspectorClass) {
            $inspector = new $inspectorClass;

            // Если файл не прошел проверку
            if (! $inspector->check($fullname, $type)) {
                return false;
            }
        }

        // Получаем правила обработки файла или возвращаем false.
        if (! empty($script)) {
            if (is_array($script)) {
                $rules = static::scriptToRules($script);
            } else {
                return false;
            }
        } elseif ($this->zeroConfigHandling) {
            $rules = $this->getRules($driverName);
        } else {
            return false;
        }

        // Если проверка прошла успешно, но нет правил обработки, то файл
        // не обработан и возвращается пустой массив.
        if (empty($rules)) {
            return [];
        }

        if (empty($directory)) {
            $directory = pathinfo($fullname, PATHINFO_DIRNAME);
        }

        $data = $this->handle($filename, $driverName, $rules, $directory);

        $this->data = $data;

        return $data;
    }

    /**
     * Запускает обработку указанного файла по указанному драйверу и
     * правилам обработки.
     *
     * @param  string $filename
     * @param  string $driverName
     * @param  array  $rules
     * @return mixed
     */
    public function handle($filename, $driverName, $rules, $directory)
    {
        $data = [];

        $fileDirectory = $this->getDirectory();

        $fullname = $fileDirectory.'/'.$filename;

        if (empty($rules[$driverName]) || ! is_array($rules[$driverName])) {
            return [];
        }

        foreach ($rules[$driverName] as $handler => $handlerRules) {

            // Обработчик для одного метода обработки
            $handlerClass = $this->getHandler($driverName, $handler, $rules['_class'] ?? ($handlerRules['_class'] ?? null));

            // Инициализируем обработчика и передаем данные для обрабтки
            foreach ($handlerRules as $handlerMode => $options) {

                // Переопределяем обработчика конкретного метода, если это возможно
                if (static::isHandler($options['_class'] ?? null)) {
                    $localHandler = $options['_class'];
                }

                $className = $handlerClass ?? $localHandler ?? null;

                if (empty($className)) {
                    break;
                }

                if (empty($this->instances[$className])) {
                    $this->instances[$className] = new $className();
                }

                // Переопределение вызываемого метода обработчика.
                // Переопределять метод обработчика необходимо, если
                // имя операции обработчика отличается от исполняемого метода.
                if (! empty($options['_method']) && is_string($options['_method'])) {
                    $method = $options['_method'];
                } else {
                    $method = $handler;
                }

                // Необходимо задать рабочий каталог и каталог для сохранения
                $this->instances[$className]->setSourceDirectory($fileDirectory);
                $this->instances[$className]->setFinalDirectory($directory);
                $handlingResult = $this->instances[$className]->handle($filename, $method, $options);
                
                if ($handlingResult) {
                    $data[$driverName][$handler][$handlerMode] = $handlingResult;
                }
            }
        }

        return $data;
    }

    /**
     * Возвращает данные после обработки (вызова метода file()). Возвращает ту
     * же информацию, что и метод file().
     *
     * @return mixed
     */
    public function getDataAfterHandling()
    {
        return $this->data;
    }
}
