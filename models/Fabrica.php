<?php
	
require_once("classes/BaseModel.php");

class Fabrica extends BaseModel {
	protected function initAttributes() {
        $this->addAttribute('fabrica_id', DataType::INTEGER, 'Cód. Fábrica');
        $this->addAttribute('razaosocial', DataType::STRING, 'Razão Social', 50);
        $this->addAttribute('nomefantasia', DataType::STRING, 'Nome Fantasia', 50);
        $this->addAttribute('endereco', DataType::STRING, 'Endereço', 80);
        $this->addAttribute('cnpj', DataType::STRING, 'CNPJ', 16);
    }

    protected function getTableName() {
    	return 'tb_fabrica';
    }

    protected function getPkName() {
    	return 'fabrica_id';
    }

    public function validateData() {
    	$this->clearErrors();

    	if(isset($this->getAttrValue('razaosocial')) == '') {
    		$this->setError('razaosocial', "Digite a Razão Social!");
    	}

    	if(isset($this->getAttrValue('endereco')) == '') {
    		$this->setError('endereco', "Digite o Endereço!");
    	}

    	if(isset($this->getAttrValue('cnpj')) == '') {
    		$this->setError('cnpj', "Digite o CNPJ!");
    	}

    	return !$this->hasErrors();
    }
}

?>