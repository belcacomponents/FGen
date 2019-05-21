<?php

namespace Belca\FGen;

use Belca\FGen\Contracts\FileHandler as FileHandlerInterface;
use Belca\GeName\GeName;

abstract class FHandler implements FileHandlerInterface
{
    /**
     * Директория для сохранения файлов.
     *
     * @var string
     */
    protected $directory;

    protected $exceptions = ['setDirectory', 'getDirectory', 'handle'];

    public function setDirectory($directory = '')
    {
        $this->directory = isset($directory) && is_string($directory) ? $directory : '';
    }

    public function getDirectory()
    {
        return $this->directory ?? '';
    }

    /**
     * Выполняет обработку указанного файла по указанному методу и параметрам
     * обработки файла. Возвращает массив данных при успешной обработке,
     * false в случае возникновения ошибки или несоответствующих данных.
     *
     * @param  string $filename Путь к обрабатываемому файлу
     * @param  string $method   Запускаемый метод обработки
     * @param  mixed  $options  Параметры обработки файла
     * @return mixed
     */
    abstract public function handle($filename, $method, $options);
}
