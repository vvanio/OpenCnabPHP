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
class Generico1 extends RegistroRemAbstract
{

    /**
     *
     * @var integer
     */
    protected $counter = 0;

    /**
     * Método set_codigo_lote()
     *
     *
     * @param mixed $value
     */
    protected function set_codigo_lote($value)
    {
        $this->data['codigo_lote'] = RemessaAbstract::$loteCounter;
    }

    /**
     * Método set_tipo_servico()
     *
     *
     * @param mixed $value
     * @throws Exception
     */
    public function set_tipo_servico($value)
    {
        if ($value == 'S') {
            $this->data['tipo_servico'] = 1;
        } elseif ($value == 'N') {
            $this->data['tipo_servico'] = 2;
        } elseif ((int) $value <= 2) {
            $this->data['tipo_servico'] = $value;
        } else {
            throw new Exception("O tipo de servico deve ser 1 ou S para Registrada ou 2 ou N para Sem Registro, o valor informado foi:" . $value);
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
        $value = $value ? $value : RemessaAbstract::$entryData['tipo_inscricao'];
        if ($value == 1 || $value == 2) {
            $this->data['tipo_inscricao'] = $value;
        } else {
            throw new Exception("O tipo de inscrição deve ser 1  para CPF e 2 para CNPJ, o valor informado foi:" . $value);
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
        $this->data['numero_inscricao'] = $value == '' ? str_ireplace(array(
            '.',
            '/',
            '-'
        ), array(
            ''
        ), RemessaAbstract::$entryData['numero_inscricao']) : str_ireplace(array(
            '.',
            '/',
            '-'
        ), array(
            ''
        ), $value);
    }

    /**
     * Método set_codigo_beneficiario()
     *
     *
     * @param mixed $value
     */
    protected function set_codigo_beneficiario($value)
    {
        $this->data['codigo_beneficiario'] = RemessaAbstract::$entryData['codigo_beneficiario'];
    }

    /**
     * Método set_codigo_beneficiario_dv()
     *
     *
     * @param mixed $value
     */
    protected function set_codigo_beneficiario_dv($value)
    {
        $this->data['codigo_beneficiario_dv'] = RemessaAbstract::$entryData['codigo_beneficiario_dv'];
    }

    /**
     * Método set_agencia()
     *
     *
     * @param mixed $value
     */
    protected function set_agencia($value)
    {
        $this->data['agencia'] = $value == '' ? RemessaAbstract::$entryData['agencia'] : $value;
    }

    /**
     * Método set_agencia_dv()
     *
     *
     *
     * @param mixed $value
     */
    protected function set_agencia_dv($value)
    {
        $this->data['agencia_dv'] = $value == '' ? RemessaAbstract::$entryData['agencia_dv'] : $value;
    }

    /**
     * Método set_conta()
     *
     *
     * @param mixed $value
     */
    protected function set_conta($value)
    {
        $this->data['conta'] = $value == '' ? RemessaAbstract::$entryData['conta'] : $value;
    }

    /**
     * Método set_conta_dv()
     *
     *
     * @param mixed $value
     */
    protected function set_conta_dv($value)
    {
        $this->data['conta_dv'] = $value == '' ? RemessaAbstract::$entryData['conta_dv'] : $value;
    }

    /**
     * Método set_codigo_convenio()
     *
     *
     * @param mixed $value
     */
    protected function set_codigo_convenio($value)
    {
        $this->data['codigo_convenio'] = RemessaAbstract::$entryData['codigo_beneficiario'];
    }

    /**
     * Método set_nome_empresa()
     *
     *
     * @param mixed $value
     */
    protected function set_nome_empresa($value)
    {
        $this->data['nome_empresa'] = $value == '' ? RemessaAbstract::$entryData['nome_empresa'] : $value;
    }

    /**
     * Método set_numero_remessa()
     *
     *
     * @param mixed $value
     */
    protected function set_numero_remessa($value)
    {
        $this->data['numero_remessa'] = $value == '' ? RemessaAbstract::$entryData['numero_sequencial_arquivo'] : $value;
    }

    /**
     * Método set_mensagem_3()
     *
     *
     * @param mixed $value
     */
    protected function set_mensagem_3($value)
    {
        $mensagem = (isset($this->entryData['mensagem'])) ? explode(PHP_EOL, $this->entryData['mensagem']) : array();
        $this->data['mensagem_3'] = count($mensagem) >= 1 ? $mensagem[0] : ' ';
    }

    /**
     * Método set_mensagem_4()
     *
     *
     * @param mixed $value
     */
    protected function set_mensagem_4($value)
    {
        $mensagem = (isset($this->entryData['mensagem'])) ? explode(PHP_EOL, $this->entryData['mensagem']) : array();
        $this->data['mensagem_4'] = count($mensagem) >= 2 ? $mensagem[1] : ' ';
    }

    /**
     * Método set_data_gravacao()
     *
     *
     * @param mixed $value
     */
    protected function set_data_gravacao($value)
    {
        $this->data['data_gravacao'] = date('Y-m-d');
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

    /**
     * Método inserirDetalhe()
     *
     *
     * @param mixed $data
     */
    public function inserirDetalhe($data)
    {
        $class = 'CnabPHP\resources\\B' . RemessaAbstract::$banco . '\remessa\\' . RemessaAbstract::$layout . '\Registro3P';
        $this->children[] = new $class($data);
    }

    /**
     * Método inserirTransferencia()
     *
     *
     * @param mixed $data
     */
    public function inserirTransferencia($data)
    {
        $class = 'CnabPHP\resources\\B' . RemessaAbstract::$banco . '\remessa\\' . RemessaAbstract::$layout . '\Registro3A';
        $this->children[] = new $class($data);
    }

    /**
     * {@inheritdoc}
     * @see \CnabPHP\RegistroAbstract::getText()
     */
    public function getText()
    {
        $retorno = '';
        $dataReg5 = array();
        $dataReg5['qtd_titulos_simples'] = '0';
        $dataReg5['qtd_titulos_caucionada'] = '0';
        $dataReg5['qtd_titulos_descontada'] = '0';
        $dataReg5['vrl_titulos_simples'] = '0.00';
        $dataReg5['vlr_titulos_caucionada'] = '0.00';
        $dataReg5['vlr_titulos_descontada'] = '0.00';

        //foreach ($this->meta as $key => $value) { tratar warning $value is never used
        foreach (array_keys($this->meta) as $key) {
            $retorno .= $this->$key;
        }
        RemessaAbstract::$retorno[] = $retorno;
        if ($this->children) {
            // percorre todos objetos filhos
            foreach ($this->children as $child) {
                if ($child->codigo_carteira == 1) {
                    $dataReg5['qtd_titulos_simples'] ++;
                    $dataReg5['vrl_titulos_simples'] += $child->getUnformated('valor');
                }
                if ($child->codigo_carteira == 3) {
                    $dataReg5['qtd_titulos_caucionada'] ++;
                    $dataReg5['vlr_titulos_caucionada'] += $child->getUnformated('valor');
                }
                if ($child->codigo_carteira == 4) {
                    $dataReg5['qtd_titulos_descontada'] ++;
                    $dataReg5['vlr_titulos_descontada'] += $child->getUnformated('valor');
                }
                $child->getText();
            }
            $class = 'CnabPHP\resources\\B' . RemessaAbstract::$banco . '\remessa\\' . RemessaAbstract::$layout . '\Registro5';
            $registro5 = new $class($dataReg5);
            $registro5->getText();
        }
    }
}
