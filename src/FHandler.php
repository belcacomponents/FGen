<?php

namespace Belca\FGen;

use Belca\FGen\Contracts\FileHandler as FileHandlerInterface;

abstract class FHandler implements FileHandlerInterface
{
    /**
     * Исходная директория с исходным файлом.
     *
     * @var string
     */
    protected $sourceDirectory;

    /**
     * Директория для сохранения файла.
     *
     * @var string
     */
    protected $finalDirectory;

    protected $exceptions = ['setDirectory', 'getDirectory', 'handle'];

    /**
     * Задает исходную директорию, где хранится исходный файл.
     *
     * @param string $directory [description]
     */
    public function setSourceDirectory($directory = '')
    {
        $this->sourceDirectory = is_dir($directory) ? $directory : '';
    }

    /**
     * Возвращает исходную директорию.
     *
     * @return string
     */
    public function getSourceDirectory()
    {
        return $this->sourceDirectory ?? '';
    }

    /**
     * Задает директорию для сохранения файла.
     *
     * @param string $directory
     */
    public function setFinalDirectory($directory)
    {
        $this->finalDirectory = is_dir($directory) ? $directory : '';
    }

    /**
     * Возвращает директорию для сохранения файла.
     *
     * @return string
     */
    public function getFinalDirectory()
    {
        return $this->finalDirectory ?? $this->getSourceDirectory();
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
    abstract public function handle($filename, $method, $options = []);
}
