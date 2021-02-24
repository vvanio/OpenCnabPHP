<?php
/*
 * CnabPHP - Geração de arquivos de remessa e retorno em PHP
 *
 * LICENSE: The MIT License (MIT)
 *
 * Copyright (C) 2013 Ciatec.net
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify,
 * merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace CnabPHP\resources\generico\remessa\cnab400;

use CnabPHP\RegistroRemAbstract;
use cnabPHP\RemessaAbstract;
use Exception;

/**
 */
class Generico9 extends RegistroRemAbstract
{

    /**
     */
    protected function set_numero_registro($value)
    {
        $lote = RemessaAbstract::getLote(0);
        $this->data['numero_registro'] = $lote->get_counter();
    }

    /**
     */
    protected function set_mensagem_1($value)
    {
        $mensagem = (isset(RemessaAbstract::$entryData['mensagem'])) ? explode(PHP_EOL, RemessaAbstract::$entryData['mensagem']) : array();
        $this->data['mensagem_1'] = count($mensagem) >= 1 ? $mensagem[0] : ' ';
    }

    /**
     */
    protected function set_mensagem_2($value)
    {
        $mensagem = (isset(RemessaAbstract::$entryData['mensagem'])) ? explode(PHP_EOL, RemessaAbstract::$entryData['mensagem']) : array();
        $this->data['mensagem_2'] = count($mensagem) >= 2 ? $mensagem[1] : ' ';
    }

    /**
     */
    protected function set_mensagem_3($value)
    {
        $mensagem = (isset(RemessaAbstract::$entryData['mensagem'])) ? explode(PHP_EOL, RemessaAbstract::$entryData['mensagem']) : array();
        $this->data['mensagem_3'] = count($mensagem) >= 3 ? $mensagem[2] : ' ';
    }

    /**
     */
    protected function set_mensagem_4($value)
    {
        $mensagem = (isset(RemessaAbstract::$entryData['mensagem'])) ? explode(PHP_EOL, RemessaAbstract::$entryData['mensagem']) : array();
        $this->data['mensagem_4'] = count($mensagem) >= 4 ? $mensagem[3] : ' ';
    }

    /**
     */
    protected function set_mensagem_5($value)
    {
        $mensagem = (isset(RemessaAbstract::$entryData['mensagem'])) ? explode(PHP_EOL, RemessaAbstract::$entryData['mensagem']) : array();
        $this->data['mensagem_5'] = count($mensagem) >= 5 ? $mensagem[4] : ' ';
    }
}
