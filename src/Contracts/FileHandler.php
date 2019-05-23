<?php

namespace Belca\FGen\Contracts;

interface FileHandler
{
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
    public function handle($filename, $method, $options = []);
}
