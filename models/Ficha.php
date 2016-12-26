<?php
	
require_once("classes/BaseModel.php");

class Fabrica extends BaseModel {
	protected function initAttributes() {
        $this->addAttribute('ficha_id', DataType::INTEGER, 'Cód. Ficha');
        $this->addAttribute('qtdpares', DataType::INTEGER, 'Qtd. Pares');
        $this->addAttribute('numeroplano', DataType::STRING, 'Nro. Plano', 10);
        $this->addAttribute('numeroficha', DataType::STRING, 'Nro. Ficha', 10);
        $this->addAttribute('dataentrada', DataType::DATA_TIME, 'Data de Entrada');
        $this->addAttribute('datasaida', DataType::DATA_TIME, 'Data de Saída');
        $this->addAttribute('modelo_id', DataType::INTEGER, 'Nro. Modelo');
    }

    protected function getTableName() {
    	return 'tb_ficha';
    }

    protected function getPkName() {
    	return 'fabrica_id';
    }

    public function validateData() {
    	$this->clearErrors();

    	if(isset($this->getAttrValue('qtdpares')) == '') {
    		$this->setError('qtdpares', "Digite a quantidade de pares!");
    	}

    	if(isset($this->getAttrValue('numeroplano')) == '') {
    		$this->setError('numeroplano', "Digite o número do plano!");
    	}

    	if(isset($this->getAttrValue('numeroficha')) == '') {
    		$this->setError('numeroficha', "Digite o número da ficha!");
    	}

    	if(isset($this->getAttrValue('modelo_id')) == '') {
    		$this->setError('modelo_id', "Informe o modelo!");
    	}

    	return !$this->hasErrors();
    }
}

?>