<?php

namespace Belca\FGen;

use Belca\FGen\Contracts\FileGenerator;

class FGen implements FileGenerator
{
    /**
     * Класс-определитель типа файла.
     *
     * @var string
     */
    protected static $determinerClass = '';

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

    protected $

    public function isFileTypeEqualDriverName()
    {
        return $this->fileTypeEqualDriverName;
    }

    public function

    public function file()
    {
        /**
         * Определяем тип файла для получения драйвера обработки файла.
         * Если драйвер для обработки по типу файла не определен и
         * включено равенство типа и названия драйвера, то в качестве имени
         * драйвера используется тип файла.
         */
        $determiner = new self::$determinerClass($filename);

        $filetype = $determiner->getFileType();

        $driverName = $this->getDriverNameByFileType($filetype);

        if (empty($driverName) && $this->isFileTypeEqualDriverName()) {
            $driverName = $filetype;
        }

        $inspectorClass = $this->getInspector($driverName);

        /**
         * Если класс Инспектора указанного драйвера найден, то вернуть его
         * и проверить на соответствие типа файла.
         * Если Инспектор не найден, то считать, что файл успешно прошел
         * проверку на соответствие типа.
         */
        if ($inspectorClass) {
            $inspector = new $inspectorClass;

            /**
             * Если файл не соответствует указанному типу, то не обрабатывать
             * файл и вернуть false, что означает ошибку.
             */
            if (! $inspector->check($filename, $type)) {
                return false;
            }
        }

        // TODO проверить наличие обработчиков указанного типа.
        $this->getHandlersByDriverName($driverName);


        // драйвер должен повторно выполнять проверку файла на соответствие типа

        /**
         * Если драйвер подтверждает тип файла, то выполняется дальнейшая
         * обработка файла.
         */

    }
}
