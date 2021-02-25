<?php
namespace CnabPHP\resources\B748\remessa\cnab400;

use CnabPHP\RemessaAbstract;
use CnabPHP\resources\generico\remessa\cnab400\Generico2;

/**
 */
class Registro2 extends Generico2
{

    /**
     * Metadados do Registro
     *
     * @var array
     */
    protected $meta = array(
        'tipo_registro' => array(
            'tamanho' => 1,
            'default' => '2',
            'tipo' => 'int',
            'required' => true
        ),
        'filler0' => array(
            'tamanho' => 11,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => true
        ),
        'nosso_numero' => array(
            'tamanho' => 9,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        ),
        'mensagem_1' => array(
            'tamanho' => 80,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => false
        ),
        'mensagem_2' => array(
            'tamanho' => 80,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => false
        ),
        'mensagem_3' => array(
            'tamanho' => 80,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => false
        ),
        'mensagem_4' => array(
            'tamanho' => 80,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => false
        ),
        'seu_numero' => array(
            'tamanho' => 10,
            'default' => '0000000000',
            'tipo' => 'alfa',
            'required' => false
        ),
        'filler' => array(
            'tamanho' => 43,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => false
        ),
        'numero_registro' => array(
            'tamanho' => 6,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        )
    );

    /**
     * Método __construct()
     *
     * @param array $data
     *            - dados para criação do registro
     */
    public function __construct($data = null)
    {
        if (empty($this->data)) {
            parent::__construct($data);
        }
    }

    /**
     * Método set_mensagem_1()
     *
     * @param mixed
     */
    protected function set_mensagem_1($value)
    {
        $mensagem = (! empty($this->entryData['mensagem'])) ? explode(PHP_EOL, $this->entryData['mensagem']) : array();
        $this->data['mensagem_1'] = count($mensagem) >= 1 ? $mensagem[0] : ' ';
    }

    /**
     * Método set_mensagem_2()
     *
     * @param mixed
     */
    protected function set_mensagem_2($value)
    {
        $mensagem = (! empty($this->entryData['mensagem'])) ? explode(PHP_EOL, $this->entryData['mensagem']) : array();
        $this->data['mensagem_2'] = count($mensagem) >= 2 ? $mensagem[1] : ' ';
    }

    /**
     * Método ()
     *
     * @param set_mensagem_3mixed
     */
    protected function set_mensagem_3($value)
    {
        $mensagem = (! empty($this->entryData['mensagem'])) ? explode(PHP_EOL, $this->entryData['mensagem']) : array();
        $this->data['mensagem_3'] = count($mensagem) >= 3 ? $mensagem[2] : ' ';
    }

    /**
     * Método set_mensagem_4()
     *
     * @param mixed
     */
    protected function set_mensagem_4($value)
    {
        $mensagem = (! empty($this->entryData['mensagem'])) ? explode(PHP_EOL, $this->entryData['mensagem']) : array();
        $this->data['mensagem_4'] = count($mensagem) >= 4 ? $mensagem[3] : ' ';
    }

    /**
     * Método get_nosso_numero()
     *
     * @return string
     */
    protected function get_nosso_numero()
    {
        $modulo11 = self::modulo11(str_pad(RemessaAbstract::$entryData['agencia'], 4, 0, STR_PAD_LEFT) . str_pad(RemessaAbstract::$entryData['posto'], 2, 0, STR_PAD_LEFT) . str_pad(RemessaAbstract::$entryData['codigo_beneficiario'], 5, 0, STR_PAD_LEFT) . str_pad(strftime("%y", strtotime($this->data_emissao)), 2, 0, STR_PAD_LEFT) . 2 . str_pad($this->data['nosso_numero'], 5, 0, STR_PAD_LEFT), 7);
        return strftime("%y", strtotime($this->entryData['data_emissao'])) . $this->entryData['seu_byte'] . str_pad($this->data['nosso_numero'], 5, 0, STR_PAD_LEFT) . $modulo11['digito'];
    }

    /**
     * Método ()
     * Cálculo do modulo11
     * 
     * @param number $num
     * @param number $base
     * 
     * @return number[]
     */
    protected static function modulo11($num, $base = 9)
    {
        $fator = 2;
        $soma = 0;
        $numeros = [];
        $parcial = [];
        
        // Separacao dos numeros.
        for ($i = strlen($num); $i > 0; $i --) {
            // Pega cada numero isoladamente.
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo falor.
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos.
            $soma += $parcial[$i];
            if ($fator == $base) {
                // Restaura fator de multiplicacao para 2.
                $fator = 1;
            }
            $fator ++;
        }
        
        // Cálculo do modulo11 
        $result = array(
            'digito' => ($soma * 10) % 11,
            // Remainder.
            'resto' => $soma % 11
        );
        if ($result['digito'] == 10) {
            $result['digito'] = 0;
        }
        return $result;
    }
}
