<?php 
class DataType /* extends SplEnum */ {
    const VIRTUAL = -1;     // Atributo que só existe no modelo lógico
    const INTEGER = 0;
    const STRING = 1;
    const DATE_TIME = 2;
    const DOUBLE = 3;
}

class Attribute {

    private $_name; // nome do campo
    private $_dataType; // nome do tipo
    private $_length; // tamanho limite do campo, caso necessário
    private $_label;  // nome dado ao campo pelo model
    private $_value; // valor do campo

    public function __construct(
        string $name,
        /*DataType*/ $dataType,
        string $label,
        int $length = null,
        $value = null
    ) {
        $this->_name = $name; // atribuindo nome
        $this->_dataType = $dataType; // atribuindo tipo
        $this->_length = $length; // atribuindo tamanho limite
        $this->_label = $label; // atribuindo uma label
        $this->_value = $value; // atribuindo uma valor
    }
    
    public function setValue($value) {
        $this->_value = $value;
    }
    
    public function getValue() {
        return $this->_value;
    }
    
    public function getDataType() {
        return $this->_dataType;
    }
    
    public function getName() {
        return $this->_name;
    }

    public function getLabel() {
        return $this->_label;
    }

}

?>