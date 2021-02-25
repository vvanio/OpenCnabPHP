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
 * Classe abstrata responsável por centralizar os método comuns ao tratamento
 * dos registros nos arquivos de remessa e retorno.
 */
abstract class RegistroAbstract
{

    /**
     *
     * @var array - armazena os dados definidos para o registro
     */
    protected $data;

    /**
     *
     * @var array - armazena as definições de metadados do registro
     */
    protected $meta;

    /**
     *
     * @var array - armazena os registros filhos
     */
    public $children;

    /**
     *
     * Método getUnformated()
     * Busca o valor de dum atributo do objeto de forma simples
     * e sem formatação de valor.
     *
     * @param string $prop
     *            - atributo que se deseja obter o valor
     *            
     * @return mixed $this->data[$prop]
     *         - valor do atributo
     */
    public function getUnformated($prop)
    {
        // retorna o valor da propriedade
        if (isset($this->data[$prop])) {
            return $this->data[$prop];
        }
    }

    /**
     *
     * Método prepareText()
     * Porção de código retirado do projeto andersondanilo/CnabPHP
     * e usado como esta sem alteração.
     * Recebe um texto e prepara para inserir no arquivo de texto.
     *
     * @param string $text
     *            - texto a ser praparado
     * @param string $remove
     *            - itens adicionais para remover
     *            
     * @return string $result
     *         - texto processado
     */
    protected function prepareText($text, $remove = null)
    {
        // limpa o texto de ascentos e outros caracteres não desejados
        $result = strtoupper($this->removeAccents(trim(html_entity_decode($text))));
        // se houver itens adicionais a remover
        if ($remove) {
            // limpar do texto os itens adicionais
            $result = str_replace(str_split($remove), '', $result);
        }
        return $result;
    }

    /**
     *
     * Método removeAccents()
     * Porção de código retirado do projeto andersondanilo/CnabPHP
     * e usado como esta sem alteração.
     * Recebe um texto e prepara para inserir no arquivo de texto.
     *
     * @param string $string
     *            - texto a ser processado
     *            
     * @return string $string
     *         - texto processado sem os ascentos
     */
    protected function removeAccents($string)
    {
        return preg_replace(
            array(
                '/\xc3[\x80-\x85]/',
                '/\xc3\x87/',
                '/\xc3[\x88-\x8b]/',
                '/\xc3[\x8c-\x8f]/',
                '/\xc3([\x92-\x96]|\x98)/',
                '/\xc3[\x99-\x9c]/',
                '/\xc3[\xa0-\xa5]/',
                '/\xc3\xa7/',
                '/\xc3[\xa8-\xab]/',
                '/\xc3[\xac-\xaf]/',
                '/\xc3([\xb2-\xb6]|\xb8)/',
                '/\xc3[\xb9-\xbc]/',
            ), str_split('ACEIOUaceiou', 1), $this->isUtf8($string) ? $string : utf8_encode($string)
        );
    }

    /**
     *
     * Método removeAccents()
     * Porção de código retirado do projeto andersondanilo/CnabPHP
     * e usado como esta sem alteração.
     * Recebe um texto e prepara para inserir no arquivo de texto.
     *
     * @param string $string
     *            - texto a ser processado
     *            
     * @return string $string
     *         - texto processado
     */
    protected function isUtf8($string)
    {
        return preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]
            | [\xC2-\xDF][\x80-\xBF]
            | \xE0[\xA0-\xBF][\x80-\xBF]
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
            | \xED[\x80-\x9F][\x80-\xBF]
            | \xF0[\x90-\xBF][\x80-\xBF]{2}
            | [\xF1-\xF3][\x80-\xBF]{3}
            | \xF4[\x80-\x8F][\x80-\xBF]{2}
            )*$%xs', $string
        );
    }

    /**
     * Método getText()
     * Metodo que percorre todos os filhos acionando o metodo getText() deles
     * para preparar as linhas para serem exportadas no fromat txt.
     */
    public function getText()
    {
        $retorno = '';
        // itera sobre os campos do registro para gerar a linha correspondente
        // foreach ($this->meta as $key => $value) { tratar warning $value is never used
        foreach (array_keys($this->meta) as $key) {
            $retorno .= $this->$key;
        }
        // armazena a limnha correspondete
        RemessaAbstract::$retorno[] = $retorno;
        // se há descendentes do objeto
        if ($this->children) {
            // itera sobre os objetos filhos
            foreach ($this->children as $child) {
                // acionando o metodo para gerar a linha correspondente
                $child->getText();
            }
        }
    }

    /**
     * Método mb_str_pad()
     * Processa a string $input preenchendo com $pad_string até o tamanho $pad_length
     *
     * @param string $input
     *            - texto a ser processado
     * @param int $pad_length
     *            - comprimento a ser alcançado
     * @param string $pad_string
     *            - texto utilizado para o padding
     * @param int $pad_type
     *            - tipo de padding
     *            
     * @return string $input
     * @author Kari "Haprog" Sderholm
     */
    public function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $diff = strlen($input) - mb_strlen($input);
        return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
    }

    /**
     * Método addChild()
     * Recebe os parametros abaixo e insere num array para uso fururo
     *
     * @param RegistroRemAbstract $child
     *            - recebe um filho de RegistroRemAbstract
     */
    public function addChild(RegistroRemAbstract $child)
    {
        $this->children[] = $child;
    }
}
