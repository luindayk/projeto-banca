<?php
	
require_once("classes/BaseModel.php");

class Modelo extends BaseModel {
	protected function initAttributes() {
        $this->addAttribute('modelo_id', DataType::INTEGER, 'Cód. Modelo');
        $this->addAttribute('numeromodelo', DataType::STRING, 'Nro. Modelo', 10);
        $this->addAttribute('preco', DataType::DOUBLE, 'Preço');
        $this->addAttribute('fabrica_id', DataType::INTEGER, 'Cód. Fábrica');
    }

    protected function getTableName() {
    	return 'tb_modelo';
    }

    protected function getPkName() {
    	return 'modelo_id';
    }

    public function validateData() {
    	$this->clearErrors();

    	if(isset($this->getAttrValue('numeromodelo')) == '') {
    		$this->setError('numeromodelo', "Digite o número do modelo!");
    	}

    	if(isset($this->getAttrValue('preco')) == '') {
    		$this->setError('preco', "Digite o preço!");
    	}

    	if(isset($this->getAttrValue('fabrica_id')) == '') {
    		$this->setError('fabrica_id', "Informe a'fábrica!");
    	}

    	return !$this->hasErrors();
    }
}

?>