<?php

namespace Belca\FGen\Contracts;

interface FileHandler
{
    /**
     * Выполняет обработку указанного файла по указанному методу и параметрам
     * обработки файла. Дополнительно может быть указан режим обработки.
     *
     * @param  string $filename [description]
     * @param  string $method   [description]
     * @param  mixed  $options  [description]
     * @param  string $mode     [description]
     * @return [type]           [description]
     */
    public function handle($filename, $method, $options);
}
