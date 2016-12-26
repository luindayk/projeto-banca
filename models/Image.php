<?php
    
require_once("classes/BaseModel.php");

class Image extends BaseModel {
    protected function initAttributes() {
        $this->addAttribute('image_id', DataType::INTEGER, 'Cód. Ficha');
        $this->addAttribute('path', DataType::STRING, 'Local da Imagem', 100);
        $this->addAttribute('imagenome', DataType::STRING, 'Nome da Imagem', 50);
        $this->addAttribute('modelo_id', DataType::INTEGER, 'Nro. Modelo');
    }

    protected function getTableName() {
        return 'tb_image';
    }

    protected function getPkName() {
        return 'image_id';
    }

    public function validateData() {
        $this->clearErrors();

        if(isset($this->getAttrValue('path')) == '') {
            $this->setError('path', "Informe o caminho da imagem!");
        }

        if(isset($this->getAttrValue('imagenome')) == '') {
            $this->setError('imagenome', "Digite o nome da imagem!");
        }

        if(isset($this->getAttrValue('modelo_id')) == '') {
            $this->setError('modelo_id', "Informe o modelo!");
        }

        return !$this->hasErrors();
    }
}

?>