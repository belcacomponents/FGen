<?php

namespace Belca\FGen\Contracts;

/**
 * Интерфейс определителя типа файла.
 */
interface FileTypeDeterminer
{
    /**
     * Если задан путь к обрабатываемому файлу, то сохраняет его для обработки.
     *
     * @param string $filename
     */
    public function __construct($filename = null);

    /**
     * Задает путь к обрабатываемому файлу.
     *
     * @param string $filename 
     */
    public function setFilename($filename);

    /**
     * Возвращает имя обрабатываемого файла.
     *
     * @return string
     */
    public function getFilename();

    /**
     * Определяет и возвращает тип файла на основе его содержимого (тип MIME)
     * или названия файла.
     *
     * @return string
     */
    public function getFileType();

    /**
     * Возвращает определенный MIME-тип файла.
     *
     * @return string
     */
    public function getMime();

    /**
     * Возвращает альтернативные MIME-типы и определенный.
     *
     * @return array
     */
    public function getMimes();

    /**
     * Возвращает указанное расширение файла.
     *
     * @return string
     */
    public function getExtention();

    /**
     * Возвращает обнаруженное расширение файла.
     *
     * @return string
     */
    public function getDetectedExtention();

    /**
     * Возвращает все возможные расширения файла.
     *
     * @return array
     */
    public function getExtentions();

    /**
     * Возвращает всю информацию о файле. Если $group - true, то группирует
     * ответ на типы файла и расширения файла, иначе все в одном массиве.
     *
     * @return mixed
     */
    public function getAll($group = true);
}
