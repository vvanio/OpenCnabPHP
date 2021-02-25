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
namespace CnabPHP\resources\generico\remessa\cnab240;

use CnabPHP\RegistroRemAbstract;
use CnabPHP\RemessaAbstract;
use Exception;

/**
 */
class Generico0 extends RegistroRemAbstract
{

    /**
     *
     * @var integer
     */
    protected $counter;

    /**
     * Método inserirDetalhe()
     *
     *
     * @param mixed $data
     */
    public function inserirDetalhe($data)
    {
        $class = 'CnabPHP\resources\\B' . RemessaAbstract::$banco . '\remessa\\' . RemessaAbstract::$layout . '\Registro1';
        $this->children[] = new $class($data);
    }

    /**
     * Método set_situacao_arquivo()
     *
     *
     * @param mixed $value
     */
    protected function set_situacao_arquivo($value)
    {
        $this->data['situacao_arquivo'] = ($value == 'T') ? "REMESSA-TESTE" : "REMESSA-PRODUCAO";
    }

    /**
     * Método set_data_geracao()
     *
     *
     * @param mixed $value
     */
    protected function set_data_geracao($value)
    {
        $this->data['data_geracao'] = date('Y-m-d');
    }

    /**
     * Método set_hora_geracao()
     *
     *
     * @param mixed $value
     */
    protected function set_hora_geracao($value)
    {
        $this->data['hora_geracao'] = date('His');
    }

    /**
     * Método set_tipo_inscricao()
     *
     *
     * @param mixed $value
     * @throws Exception
     */
    protected function set_tipo_inscricao($value)
    {
        if ($value == 1 || $value == 2) {
            $this->data['tipo_inscricao'] = $value;
        } else {
            throw new Exception("O tipo de incrição deve ser 1  para CPF e 2 para CNPJ, o valor informado foi:" . $value);
        }
    }

    /**
     * Método set_numero_inscricao()
     *
     *
     * @param mixed $value
     */
    protected function set_numero_inscricao($value)
    {
        $this->data['numero_inscricao'] = str_ireplace(array(
            '.',
            '/',
            '-'
        ), array(
            ''
        ), $value);
    }

    /**
     * Método set_convenio()
     *
     *
     * @param mixed $value
     */
    protected function set_convenio($value)
    {
        $this->data['convenio'] = RemessaAbstract::$entryData['codigo_beneficiario'] . RemessaAbstract::$entryData['codigo_beneficiario_dv'];
    }

    /**
     * Método get_numero_registro()
     *
     *
     * @return NULL
     */
    public function get_numero_registro()
    {
        return null;
    }

    /**
     * Método get_counter()
     *
     *
     * @return number
     */
    public function get_counter()
    {
        $this->counter ++;
        return $this->counter;
    }
}
