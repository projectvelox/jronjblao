<?php
//===================================================//
//=====  CLASS: Number to Words                 =====//
//===================================================//

define("MAJOR", 'Pesos &');
define("MINOR", 'Centavos');

class NumWords 
{
    var $pesos;
    var $centavos;
    var $major;
    var $minor;
    var $words = '';
    var $number;
    var $magind;
    var $units = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
    var $teens = array('ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
    var $tens  = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
    var $mag   = array('', 'thousand', 'million', 'billion', 'trillion');

    public function NumWords($amount, $major = MAJOR, $minor = MINOR)
    {
        $this->major  = $major;
        $this->minor  = $minor;
        $this->number = number_format($amount, 2);
        list($this->pesos, $this->centavos) = explode('.', $this->number);
        $this->words = " $this->major $this->centavos $this->minor";
        if ($this->pesos == 0)
            $this->words = "Zero $this->words";
        else {
            $groups = explode(',', $this->pesos);
            $groups = array_reverse($groups);
            for ($this->magind = 0; $this->magind < count($groups); $this->magind++) {
                if (($this->magind == 1) && (strpos($this->words, 'hundred') === false) && ($groups[0] != '000'))
                    $this->words = ' and ' . $this->words;
                $this->words = $this->_build($groups[$this->magind]) . $this->words;
            }
        }
    }
    
    private function _build($n)
    {
        $res = '';
        $na  = str_pad("$n", 3, "0", STR_PAD_LEFT);
        if ($na == '000')
            return '';
        if ($na{0} != 0)
            $res = ' ' . $this->units[$na{0}] . ' hundred';
        if (($na{1} == '0') && ($na{2} == '0'))
            return $res . ' ' . $this->mag[$this->magind];
        $res .= $res == '' ? '' : ' and';
        $t = (int) $na{1};
        $u = (int) $na{2};
        switch ($t) {
            case 0:
                $res .= ' ' . $this->units[$u];
                break;
            case 1:
                $res .= ' ' . $this->teens[$u];
                break;
            default:
                $res .= ' ' . $this->tens[$t] . ' ' . $this->units[$u];
                break;
        }
        $res .= ' ' . $this->mag[$this->magind];
        return $res;
    }    

}

?>