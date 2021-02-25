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

/**
 */
abstract class RegistroRetAbstract extends RegistroAbstract
{

    /**
     *
     * @var string - armazena a linha do registro
     */
    public $linha;

    /**
     * Método __construct()
     * instancia registro qualquer
     *
     * @param string $linhaTxt
     *            - dados para o registro
     */
    public function __construct($linhaTxt)
    {
        $posicao = 0;
        // atribui a linha do registro
        $this->linha = $linhaTxt;
        // itera sobre o metadados
        foreach ($this->meta as $key => $value) {
            // altera o formato do conteúdo conforme o tamanho e a precisão definida
            $valor = (isset($value['precision'])) ? substr($linhaTxt, $posicao, $value['tamanho'] + $value['precision']) : substr($linhaTxt, $posicao, $value['tamanho']);
            // atribui o valor formatado na chave
            $this->$key = $valor;
            // altera a posição considerada conforme o tamanho e a precisão definida
            $posicao += (isset($value['precision'])) ? $value['tamanho'] + $value['precision'] : $value['tamanho'];
        }
    }

    /**
     * método __set()
     * Código executado sempre que for definido um valor para um atributo do registro.
     *
     * @param string $prop
     *            - atributo a ser definido
     * @param mixed $value
     *            - valor a ser atribuído para o atributo
     */
    public function __set($prop, $value)
    {
        // verifica se existe método set_<atributo>
        if (method_exists($this, 'set_' . $prop)) {
            // executa o Método set_<atributo>
            call_user_func(array(
                $this,
                'set_' . $prop
            ), $value);
        } else {
            // localiza o atributo no metadado
            $metaData = (isset($this->meta[$prop])) ? $this->meta[$prop] : null;
            // atribui o valor para o atributo formatado conforme a definição do tipo
            switch ($metaData['tipo']) {
                case 'decimal':
                    $inteiro = abs(substr($value, 0, $metaData['tamanho']));
                    $decimal = abs(substr($value, $metaData['tamanho'], $metaData['precision'])) / 100;
                    $retorno = ($inteiro + $decimal);
                    $this->data[$prop] = $retorno;
                    break;
                case 'int':
                    $retorno = abs($value);
                    $this->data[$prop] = $retorno;
                    break;
                case 'alfa':
                    $retorno = trim($value);
                    $this->data[$prop] = $retorno;
                    break;
                case 'date':
                    if ($metaData['required']) {
                        if ($metaData['tamanho'] == 6) {
                            $data = \DateTime::createFromFormat('dmy', sprintf('%06d', $value));
                        } elseif ($metaData['tamanho'] == 8) {
                            $data = \DateTime::createFromFormat('dmY', sprintf('%08d', $value));
                        } else {
                            throw new \InvalidArgumentException("Tamanho do campo {$prop} inválido");
                        }

                        $this->data[$prop] = $data->format('Y-m-d');
                    } else {
                        $this->data[$prop] = '';
                    }
                    break;
                default:
                    $this->data[$prop] = $value;
                    break;
            }
        }
    }

    /*
     * Método __get()
     * Código executado sempre que um atributo do registro for obrigatório.
     *
     * @param string $prop
     * - atributo a ser definido
     *
     * @return mixed
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
            // retorna o valor deifnido para o atributo
            return $this->data[$prop];
        }
    }

    /*
     * Método ___get()
     * Código auxiliar para ser chamado para dentro de metodo get personalizado
     *
     * @param string $prop
     * - atributo a ser definido
     *
     * @return string|NULL
     */
    public function ___get($prop)
    {
        // retorna o valor da propriedade
        if (isset($this->meta[$prop])) {
            // localiza o atributo no metadado
            $metaData = (isset($this->meta[$prop])) ? $this->meta[$prop] : null;
            // realiza o tratamento do dado conforme o tipo e retorna o dado formatado
            switch ($metaData['tipo']) {
                case 'decimal':
                    return ($this->data[$prop]) ? number_format($this->data[$prop], $metaData['precision'], ',', '.') : '';
                case 'int':
                    return (isset($this->data[$prop])) ? abs($this->data[$prop]) : '';
                case 'alfa':
                    return ($this->data[$prop]) ? $this->prepareText($this->data[$prop]) : '';
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 6:
                    return ($this->data[$prop]) ? date("d/m/y", strtotime($this->data[$prop])) : '';
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 8:
                    return ($this->data[$prop]) ? date("d/m/Y", strtotime($this->data[$prop])) : '';
                default:
                    return null;
            }
        }
    }

    /**
     * Método get_meta()
     * Metodo que retorna o metadado do registro
     *
     * @return array
     */
    public function get_meta()
    {
        return $this->meta;
    }

    /**
     * Método getChilds()
     * Metodo que retorna todos os filhos do registro
     *
     * @return array
     */
    public function getChilds()
    {
        return $this->children;
    }

    /**
     * Método getChild()
     * Metodo que retorna um filho
     *
     * @param number $index
     *            - posição do filho que se deseja obter
     * @return mixed
     */
    public function getChild($index = 0)
    {
        return $this->children[$index];
    }
}
