<?php

namespace Belca\FGen;

use Belca\FGen\Contracts\FileTypeDeterminer;

/**
 * Определяет тип файла.
 */
class Determiner implements FileTypeDeterminer
{
    protected $filename;

    public function __construct($filename = null)
    {
        if (isset($filename)) {
            $this->setFilename($filename);
        }
    }

    /**
     * Запускает функцию получения данных на основе текущего файла.
     *
     * @return void
     */
    protected function getInfo()
    {
        $this->mime = mime_content_type($this->filename);

        $pathinfo = pathinfo($this->filename);

        $this->extension = $pathinfo['extension'];
    }

    /**
     * Задает путь к обрабатываемому файлу.
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        if (is_file($filename)) {
            $this->filename = $filename;

            $this->getInfo();
        }
    }

    /**
     * Возвращает имя обрабатываемого файла.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Определяет и возвращает тип файла на основе его содержимого (тип MIME)
     * или названия файла.
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->mime;
    }

    /**
     * Возвращает определенный MIME-тип файла.
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Возвращает альтернативные MIME-типы и определенный.
     *
     * @return array
     */
    public function getMimes()
    {

    }

    /**
     * Возвращает указанное расширение файла.
     *
     * @return string
     */
    public function getExtention()
    {
        return $this->extension;
    }

    /**
     * Возвращает обнаруженное расширение файла.
     *
     * @return string
     */
    public function getDetectedExtention()
    {
        // TODO реализовать данный класс на основе Mimey
    }

    /**
     * Возвращает все возможные расширения файла.
     *
     * @return array
     */
    public function getExtentions()
    {
        return $this->extension;
    }

    /**
     * Возвращает всю информацию о файле. Если $group - true, то группирует
     * ответ на типы файла и расширения файла, иначе все в одном массиве.
     *
     * @return mixed
     */
    public function getAll($group = true)
    {

    }
}
