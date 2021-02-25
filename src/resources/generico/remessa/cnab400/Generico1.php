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
use CnabPHP\RemessaAbstract;
use CnabPHP\Especie;
use Exception;

/**
 */
class Generico1 extends RegistroRemAbstract
{

    /**
     * Método set_numero_registro()
     *
     *
     * @param mixed $value
     */
    protected function set_numero_registro($value)
    {
        $lote = RemessaAbstract::getLote(0);
        $this->data['numero_registro'] = $lote->get_counter();
    }

    /**
     * Método set_tipo_inscricao_empresa()
     *
     *
     * @param mixed $value
     * @throws Exception
     */
    protected function set_tipo_inscricao_empresa($value)
    {
        $value = RemessaAbstract::getLote(0)->entryData['tipo_inscricao'];
        if ($value == 1 || $value == 2) {
            $this->data['tipo_inscricao_empresa'] = RemessaAbstract::getLote(0)->entryData['tipo_inscricao'];
        } else {
            throw new Exception("O tipo de incrição deve ser 1  para CPF e 2 para CNPJ, o valor informado foi:" . $value);
        }
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
     * Método set_numero_inscricao_empresa()
     *
     *
     * @param mixed $value
     */
    protected function set_numero_inscricao_empresa($value)
    {
        $this->data['numero_inscricao_empresa'] = str_ireplace(array(
            '.',
            '/',
            '-'
        ), array(
            ''
        ), RemessaAbstract::getLote(0)->entryData['numero_inscricao']);
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
     * Método set_agencia()
     *
     *
     * @param mixed $value
     */
    protected function set_agencia($value)
    {
        $this->data['agencia'] = RemessaAbstract::getLote(0)->entryData['agencia'];
    }

    /**
     * Método set_agencia_dv()
     *
     *
     * @param mixed $value
     */
    protected function set_agencia_dv($value)
    {
        $this->data['agencia_dv'] = RemessaAbstract::getLote(0)->entryData['agencia_dv'];
    }

    /**
     * Método set_conta()
     *
     *
     * @param mixed $value
     */
    protected function set_conta($value)
    {
        $this->data['conta'] = RemessaAbstract::getLote(0)->entryData['conta'];
    }

    /**
     * Método set_conta_dv()
     *
     *
     * @param mixed $value
     */
    protected function set_conta_dv($value)
    {
        $this->data['conta_dv'] = RemessaAbstract::getLote(0)->entryData['conta_dv'];
    }

    /**
     * Método set_cep_pagador()
     *
     *
     * @param mixed $value
     */
    protected function set_cep_pagador($value)
    {
        $cep = $value;
        $cep_array = str_ireplace('-', '', $cep);
        $this->data['cep_pagador'] = $cep_array;
    }

    /**
     * Método set_especie_titulo()
     *
     *
     * @param mixed $value
     */
    protected function set_especie_titulo($value)
    {
        if (is_int($value)) {
            $this->data['especie_titulo'] = $value;
        } else {
            $especie = new Especie(RemessaAbstract::$banco);
            $this->data['especie_titulo'] = $especie->getCodigo($value);
        }
    }
}
