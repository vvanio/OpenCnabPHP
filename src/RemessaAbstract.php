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
abstract class RemessaAbstract
{

    // sera atribuido o nome do banco que tambem ? o nome da pasta que contem os layouts
    public static $banco;

    // recebera o nome do layout na instanciação
    public static $layout;

    // armazena o objeto registro 0 do arquivo
    public static $hearder;

    // mantem os dados passados em $data na instanciação
    public static $entryData;

    // contador de lotes
    public static $loteCounter = 1;

    // finalizador de linhas
    public static $endLine = "\r\n";

    // armazena os registros filhos da classe remessa
    public static $children = array();

    // durante a geração do txt de retorno se tornara um array com as linhas do arquvio
    public static $retorno = array();

    /**
     * método __construct()
     * Recebe os parametros
     *
     * @$banco = nome do banco no momento so Caixa
     * @$layout = nome do layout no momento so Cnab240_SIGCB
     * @$data = um array contendo os dados nessesarios para o arquvio
     */
    public function __construct($banco, $layout, $data)
    {
        self::$banco = $banco;
        self::$layout = strtolower($layout);
        $class = '\CnabPHP\resources\\B' . self::$banco . '\remessa\\' . self::$layout . '\Registro0';
        self::$entryData = $data;
        self::$hearder = new $class($data);
        self::$children = array();
        self::$retorno = array();
        self::$loteCounter = 1;
        self::$children[] = self::$hearder;
    }

    /**
     * método inserirDetalhe()
     * Recebe os parametros
     *
     * @$data = um array contendo os dados nessesarios para o arquvio
     */
    public function inserirDetalhe($data)
    {
        self::$hearder->inserirDetalhe($data);
        // $class = '\CnabPHP\resources\\B' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
        // self::addChild(new $class($data));
        // self::$counter++;
    }

    /**
     * Método changeLayout()
     * Recebe os parametros
     *
     * @$newLayout = altera o layout do lote , servira para enviar lotes de layouts diferentes no mesmo arquvio
     *  //(ALERTA) nao testado
     */
    public function changeLayout($newLayout)
    {
        self::$layout = $newLayout;
    }

    /**
     * Método addChild()
     * Recebe os parametros abaixo e insere num array para uso fururo
     *
     * @RegistroRemAbstract $child = recebe um filho de RegistroRemAbstract
     */
    private static function addChild(RegistroRemAbstract $child)
    {
        self::$children[] = $child;
    }

    /**
     * método addLote()
     * Recebe os parametros abaixo e insere num array para uso fururo
     *
     * @array $data = recebe um array contendo os dados do lote a sera aberto e
     *          retorna para qualqer layout 240 o lote criado ou $this se outro
     */
    public function addLote(array $data)
    {
        if (strpos(self::$layout, '240')) {
            $class = '\CnabPHP\resources\\B' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
            $loteData = $data ? $data : RemessaAbstract::$entryData;
            $lote = new $class($loteData);
            self::$children[0]->addChild($lote);
        } else {
            $lote = $this;
        }
        return $lote;
        self::$loteCounter ++;
    }

    /**
     * Método getLote()
     * Metodo statico para pegar o objeto do lote
     *
     * @$index = o indice do lote , normalmente 1
     */
    public static function getLote($index)
    {
        if (strpos(self::$layout, '240')) {
            return self::$children[0]->children[$index - 1];
        } else {
            return self::$children[0];
        }
    }

    /**
     * método getLotes()
     * Metodo statico para pegar os objetos dos lotes
     */
    public static function getLotes()
    {
        return self::$children[0]->children;
    }

    /**
     * Método getText()
     * Metodo que percorre todos os filhos acionando o metodo getText() deles
     */
    public function getText()
    {
        foreach (self::$children as $child) {
            // echo '<pre>';
            // var_dump($child);
            // echo '</pre>';
            $child->getText();
        }
        $class = '\CnabPHP\resources\\B' . self::$banco . '\remessa\\' . self::$layout . '\Registro9';
        $headerArquivo = new $class(array(
            '1' => 1
        ));
        $headerArquivo->getText();
        return implode(self::$endLine, self::$retorno) . self::$endLine;
    }

    /**
     */
    public function getFileName()
    {
        return self::$children[0]->getFileName();
    }
}
