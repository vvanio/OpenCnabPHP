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
 * A classe RemessaAbstract cuida das questões gerais dos arquivos
 */
abstract class RemessaAbstract
{

    /**
     *
     * @var string - sera atribuido o nome do banco que tambem é o nome da pasta que contem os layouts
     */
    public static $banco;

    /**
     *
     * @var string - recebera o nome do layout na instanciação
     */
    public static $layout;

    //
    /**
     *
     * @var RegistroRemAbstract - armazena o objeto registro 0 do arquivo
     */
    public static $hearder;

    /**
     *
     * @var array - mantem os dados passados em $data na instanciação
     */
    public static $entryData;

    /**
     *
     * @var integer - contador de lotes
     */
    public static $loteCounter = 1;

    /**
     *
     * @var string - finalizador de linhas
     */
    public static $endLine = "\r\n";

    /**
     *
     * @var array - armazena os registros filhos da classe remessa
     */
    public static $children = array();

    /**
     *
     * @var array - linhas do arquvio durante a geração do txt de remessa
     */
    public static $retorno = array();

    /**
     * método __construct()
     * Recebe os parametros
     *
     * @param string $banco
     *            - nome do banco
     * @param string $layout
     *            - nome do layout
     * @param array $data
     *            - dados nessesarios para criação do arquvio
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
     * Insere detalhes no registro atual
     * Recebe os parametros
     *
     * @param array $data
     *            - dados para criação do registro
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
     * Servirá para enviar lotes com layouts diferentes num mesmo arquivo
     * Recebe os parametros
     *
     * @param string $newLayout
     *            - nome do novo layout do lote
     *            
     * @todo validação pendente - nao testado
     */
    public function changeLayout($newLayout)
    {
        self::$layout = $newLayout;
    }

    /**
     * Método addChild()
     * Adiciona um registro filho ao arquivo
     * Recebe os parametros abaixo e insere num array para uso fururo
     *
     * @param RegistroRemAbstract $child
     *            - tipo de registro filho a ser adicionado
     */
    private static function addChild(RegistroRemAbstract $child)
    {
        self::$children[] = $child;
    }

    /**
     * método addLote()
     * Adiciona um novo lote ao arquivo
     * Recebe os parametros abaixo e insere num array para uso fururo
     *
     * @param array $data
     *            - dados do lote a ser aberto
     * @return RegistroRemAbstract $lote
     *         - o lote criado (para qualquer layout 240 )
     *         - ou $this se o layout for outro
     */
    public function addLote(array $data)
    {
        // para qualquer layout 240
        if (strpos(self::$layout, '240')) {
            // instancia um objeto de registro 1 - Lote
            $class = '\CnabPHP\resources\\B' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
            $loteData = $data ? $data : RemessaAbstract::$entryData;
            $lote = new $class($loteData);
            // adiciona o objeto com um filho do arquivo
            self::$children[0]->addChild($lote);
        } else {
            // o próprio arquivo
            $lote = $this;
        }
        // retorna o lote
        return $lote;
        // incremeta o contador de lotes.
        self::$loteCounter ++;
    }

    /**
     * Método getLote()
     * Metodo statico para pegar o objeto do lote
     *
     * @param int $index
     *            - indice do lote , normalmente 1
     *            
     * @return RegistroRemAbstract lote
     */
    public static function getLote($index)
    {
        // para qualquer layout 240
        if (strpos(self::$layout, '240')) {
            // retorna o objeto do lote no indice do filho
            return self::$children[0]->children[$index - 1];
        } else {
            // retorna o objeto do lote
            return self::$children[0];
        }
    }

    /**
     * método getLotes()
     * Metodo statico para pegar os objetos dos lotes
     *
     * @return array lotes
     */
    public static function getLotes()
    {
        return self::$children[0]->children;
    }

    /**
     * Método getText()
     * Metodo que percorre todos os filhos acionando o metodo getText() deles
     * para preparar as linhas para serem exportadas no fromat txt.
     *
     * @return string
     */
    public function getText()
    {
        // obtem iterativamente o texto de cada objeto
        foreach (self::$children as $child) {
            // echo '<pre>';
            // var_dump($child);
            // echo '</pre>';
            $child->getText();
        }
        // instancia um objeto de registro 9
        $class = '\CnabPHP\resources\\B' . self::$banco . '\remessa\\' . self::$layout . '\Registro9';
        $headerArquivo = new $class(array(
            '1' => 1
        ));
        // obtem o texto do registro
        $headerArquivo->getText();
        // retorna a string montada para gravação no arquivo
        return implode(self::$endLine, self::$retorno) . self::$endLine;
    }

    /**
     * Método getFileName()
     * Código responsável pela formatação do nome do arquivo .
     *
     * @return string
     */
    public function getFileName()
    {
        return self::$children[0]->getFileName();
    }
}
