<?php

namespace Belca\FGen\Contracts;

/**
 * Инспектор файла.
 *
 * Выполняет полный анализ файла на соответствие или для выяснения типа файла.
 */
interface Inspector
{
    /**
     * Задает полное имя файла для его обработки.
     *
     * @param string $filename Путь к файлу
     */
    public function setFilename($filename);

    /**
     * Возвращает имя обрабатываемого файла.
     *
     * @return string
     */
    public function getFilename();

    /**
     * Возвращает тип файла.
     *
     * @param  string $filename Путь к файлу
     * @return string
     */
    public function getFileType($filename);

    /**
     * Возвращает типы файла.
     *
     * @param  string $filename Путь к файлу
     * @return array
     */
    public function getFileTypes($filename);

    /**
     * Возвращает расширение файла.
     *
     * @param  string $filename Путь к файлу
     * @return
     */
    public function getExtention($filename);

    /**
     * Возвращает расширения файла.
     *
     * @param  string $filename Путь к файлу
     * @return
     */
    public function getExtentions($filename);

    /**
     * Проверяет файл на соотвествие указанных типов.
     *
     * @param  string $filename Путь к файлу
     * @param  array  $types    Тип файла или список типов файла
     * @return bool
     */
    public function check($filename, $types);
}
