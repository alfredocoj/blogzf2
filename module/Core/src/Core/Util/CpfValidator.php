<?php

namespace Core\Util;

use Zend\Validator\AbstractValidator;


class CpfValidator extends AbstractValidator
{
    const INVALID = "CPFInvalido.";

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID        => "CPF Inválido.",
    );

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $cpf = $this->trimCPF($value);
        if (!$this->respectsRegularExpression($cpf)) {
            $this->error(self::INVALID);
            return false;
        }

        if (!$this->applyingCpfRules($cpf)) {
            $this->error(self::INVALID);
            return false;
        }

        return true;
    }

    /**
     * @param $cpf
     * @return string
     */
    private function trimCPF($cpf)
    {
        $cpf = preg_replace('/[.,-]/', '', $cpf);

        return $cpf;
    }

    /**
     * @param $cpf
     * @return bool
     */
    private function respectsRegularExpression($cpf)
    {
        $regularExpression = "[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}";

        if (!@ereg("^" . $regularExpression . "\$", $cpf)) {
            return false;
        }

        return true;
    }

    /**
     * @param $cpf
     * @return bool
     */
    private function applyingCpfRules($cpf)
    {
         // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = ereg_replace('[^0-9]', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }
}
