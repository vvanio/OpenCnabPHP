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
namespace CnabPHP;

use Exception;

/**
 */
abstract class RegistroRemAbstract extends RegistroAbstract
{

    /**
     * @var array $entryData
     */
    protected $entryData;

    /**
     * Método __construct()
     * Responsável pela criação de uma instancia de registro qualquer.
     *
     * @param array $data
     *            - dados para criação do registro
     */
    public function __construct($data = null)
    {
        // se o ID for informado
        if ($data) {
            // carrega o objeto correspondente
            $this->entryData = $data;
            // atribui valor defaulto para os atributos não especificados
            // foreach ($this->meta as $key => $value) { tratar warning $value is never used
            foreach (array_keys($this->meta) as $key) {
                $this->$key = (isset($data[$key])) ? $data[$key] : $this->meta[$key]['default'];
            }
        }
    }

    /**
     * Método __set()
     * Código executado sempre que for definido um valor para um atributo do registro.
     *
     * @param string $prop
     *            - atributo a ser definido
     * @param mixed $value
     *            - valor a ser atribuído para o atributo
     */
    public function __set($prop, $value)
    {
        // verifica se existe Método set_<atributo>
        if (method_exists($this, 'set_' . $prop)) {
            // executa o Método set_<atributo>
            call_user_func(array(
                $this,
                'set_' . $prop
            ), $value);
        } else {
            // localiza o atributo no metadado
            $metaData = (isset($this->meta[$prop])) ? $this->meta[$prop] : null;
            // se não foi definido nenhum valor
            if (($value == "" || $value === null) && $metaData['default'] != "") {
                // atribui o valor default para o atributo
                $this->data[$prop] = $metaData['default'];
            } else {
                // atribui o valor enviado para o atributo
                $this->data[$prop] = $value;
            }/**
            * @return string
            */
            
        }
    }

    /**
     * Método __get()
     * Código executado sempre que um atributo do registro for obrigatório.
     *
     * @param string $prop
     *            - atributo a ser definido
     */
    public function __get($prop)
    {
        // verifica se existe Método get_<atributo>
        if (method_exists($this, 'get_' . $prop)) {
            // executa o Método get_<atributo>
            return call_user_func(array(
                $this,
                'get_' . $prop
            ));
        } else {
            // retorna uma chamada get personalizada
            return $this->___get($prop);
        }
    }

    /**
     * Método ___get()
     * Código auxiliar para ser chamado para dentro de metodo get personalizado
     *
     * @param string $prop
     *            - atributo a ser definido
     *            
     * @throws Exception
     * @throws \ErrorException
     * 
     * @return string|NULL
     */
    public function ___get($prop)
    {
        // retorna o valor da propriedade
        if (isset($this->meta[$prop])) {
            // localiza o atributo no metadado
            $metaData = (isset($this->meta[$prop])) ? $this->meta[$prop] : null;
            // atribui valor default caso não tenha nenum dado
            $this->data[$prop] = ! isset($this->data[$prop]) || $this->data[$prop] == '' ? $metaData['default'] : $this->data[$prop];
            // caso o campo seja obrigatório e tenha valor nulo, lança a execessão apropriada
            if ($metaData['required'] == true && ($this->data[$prop] == '' || ! isset($this->data[$prop]))) {
                if (isset($this->data['nosso_numero'])) {
                    throw new Exception('Campo faltante ou com valor nulo:' . $prop . " Boleto Numero:" . $this->data['nosso_numero']);
                } else {
                    throw new Exception('Campo faltante ou com valor nulo:' . $prop);
                }
            }
            // determina a função de tratamento de erro a ser utilizada
            set_error_handler(function ($severity, $message, $file, $line, $errcontext) {
                throw new \ErrorException(json_encode($errcontext) . "->" . $message, $severity, $severity, $file, $line);
            });
            // realiza o tratamento do dado conforme o tipo e retorna o dado formatado
            switch ($metaData['tipo']) {
                case 'decimal':
                    $retorno = (($this->data[$prop] && trim($this->data[$prop]) !== "" ? number_format($this->data[$prop], $metaData['precision'], '', '') : (isset($metaData['default']) ? $metaData['default'] : '')));
                    return str_pad($retorno, $metaData['tamanho'] + $metaData['precision'], '0', STR_PAD_LEFT);
                case 'int':
                    $retorno = (isset($this->data[$prop]) && trim($this->data[$prop]) !== "" ? number_format($this->data[$prop], 0, '', '') : (isset($metaData['default']) ? $metaData['default'] : ''));
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                case 'alfa':
                    $retorno = (isset($this->data[$prop])) ? $this->prepareText($this->data[$prop]) : '';
                    return $this->mb_str_pad(mb_substr($retorno, 0, $metaData['tamanho'], "UTF-8"), $metaData['tamanho'], ' ', STR_PAD_RIGHT);
                case 'alfa2':
                    $retorno = (isset($this->data[$prop])) ? $this->data[$prop] : '';
                    return $this->mb_str_pad(mb_substr($retorno, 0, $metaData['tamanho'], "UTF-8"), $metaData['tamanho'], ' ', STR_PAD_RIGHT);
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 6:
                    $retorno = ($this->data[$prop]) ? date("dmy", strtotime($this->data[$prop])) : '';
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 8:
                    $retorno = ($this->data[$prop]) ? date("dmY", strtotime($this->data[$prop])) : '';
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                case $metaData['tipo'] == 'dateReverse':
                    $retorno = ($this->data[$prop]) ? date("Ymd", strtotime($this->data[$prop])) : '';
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                default:
                    return null;
            }
            // restaura a função de tratamento previamente utilizada
            restore_error_handler();
        }
    }

    /**
     * Método getFileName()
     * Código responsável pela formatação do nome do arquivo .
     * 
     * @return string
     */
    public function getFileName()
    {
        return 'R' . RemessaAbstract::$banco . str_pad($this->entryData['numero_sequencial_arquivo'], 4, '0', STR_PAD_LEFT) . '.rem';
    }
}
