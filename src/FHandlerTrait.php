<?php

namespace Belca\FGen;

/**
 * Реализовывает абстрактный метод handle().
 */
trait FHandlerTrait
{
    /**
     * Запускает методы обработки из текущего класса, передавая имя файла,
     * и опции обработки файла, и возвращая обработанные данные.
     * Если указанный метод не существует - вернет false.
     *
     * @param  string $filename Путь к обрабатываемому файлу
     * @param  string $method   Запускаемый метод обработки
     * @param  mixed  $options  Параметры обработки файла
     * @return mixed
     */
    public function handle($filename, $method, $options = [])
    {
        if (! is_file($this->getSourceDirectory().'/'.$filename)) {
            return false;
        }

        if (! in_array($method, $this->exceptions) && is_callable([$this, $method])) {
            return $this->$method($filename, $options);
        }

        return false;
    }
}
