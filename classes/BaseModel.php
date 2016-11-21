<?php 
require_once('Attribute.php');

abstract class BaseModel {

    private $_attributes = [];  // Vetor vazio
    private $_controller; // Controller
    private $_errors = []; // Mensagens de erros

    protected function addAttribute(
        $name,
        /*DataType*/ $dataType,
        $label,
        $length = null,
        $value = null
    ) {
        $attr = new Attribute($name, $dataType, $label, $length, $value);
        $this->_attributes[$name] = $attr; //Adiciona o atributo ao vetor
    }

    abstract protected function initAttributes(); // Inicialização dos atributos da tabela do BD
    abstract protected function getTableName(); // Nome da tabela
    abstract protected function getPkName();    // Nome do campo chave primária
    
    public function __construct($controller) {
        $this->initAttributes();
        $this->_controller = $controller;
    }
    
    public function getController() {
        return $this->_controller;
    }
    
    public function setAttrValue(string $attrName, $value) {
        //echo '***************', $attrName, '<br />';
        if(key_exists($attrName, $this->_attributes)) {
            $this->_attributes[$attrName]->setValue($value);
        }
    }
    
    public function getAttrValue(string $attrName) {
        if(key_exists($attrName, $this->_attributes)) {
            return $this->_attributes[$attrName]->getValue();
        }
        else {
            return NULL;
        }
    }
    
    public function findByPk($pkValue) {
        $db = $this->getController()->getApp()->getDb();
        $rows = $db->query("select * from {$this->getTableName()} where
        {$this->getPkName()} = '{$pkValue}'");

        $found = false;
        
        if($rows) {
            foreach($rows as $row) {
                $fieldNames = array_keys($row);
                foreach($fieldNames as $fn) {
                    $this->setAttrValue($fn, $row[$fn]);
                }
                
                $found = true;
            }
        }
        return $found;
    }
    
    public function find($where = null, $orderBy = null, $limit = null) {
        $db = $this->getController()->getApp()->getDb();
        $sql = "select * from {$this->getTableName()}";
        
        if(isset($where)) {
            $sql .= ' where ' . $where;
        }

        if(isset($orderBy)) {
            $sql .= ' order by ' . $orderBy;
        }

        if(isset($limit)) {
            $sql .= ' limit ' . $limit;
        }
        
        $rows = $db->query($sql);
        
        if(! $rows) {
            return null;
        }
        
        $instances = [];

        $className = get_class($this);
        
        foreach($rows as $row) {
            $instance = new $className($this->getController());

            $fieldNames = array_keys($row);
            foreach($fieldNames as $fn) {
                $instance->setAttrValue($fn, $row[$fn]);
            }
            
            $instances[] = $instance;
        }
        
        return $instances;
        
    }
    
    public function save($successMessage = null) {
        $isValid = $this->validateData();

        if($isValid) {
           
            // O valor da chave primária é nulo -> INSERT
            if($this->getAttrValue($this->getPkName()) == null) {
                return $this->insert();
            }
            else {
                return $this->update();
            }
            
        }
        return $isValid;
        
    }
    
    protected function update() {
        
        $fieldList = [];
        
        // Para cada um dos atributos do modelo
        foreach($this->_attributes as $attr) {
            
            if($attr->getDataType() != DataType::VIRTUAL) {
                
                $fieldname = $attr->getName();
                $value = $attr->getValue();
                
                if($value == null) {
                    $value = 'null';
                }
                else {
                    $value = "\"$value\""; // O valor entre aspas duplas
                }
                
                // campo = valor
                $fieldList[] = $fieldname . ' = ' . $value;
            }
            
        }        
        
        $setFields = implode(', ', $fieldList);
        
        $sql = "UPDATE {$this->getTableName()}
            SET $setFields
            WHERE {$this->getPkName()} = {$this->getAttrValue($this->getPkName())}";
            
        $db = $this->getController()->getApp()->getDb();
        
        return $db->exec($sql);            
        
    }
    
    public function deleteByPk($pk) {
        
        $sql = "DELETE FROM {$this->getTableName()}
            WHERE {$this->getPkName()} = $pk";
            
        $db = $this->getController()->getApp()->getDb();
        
        return $db->exec($sql);
        
    }
    
    public function loadRawData() {
        foreach($_POST as $attrName => $attrValue) {
            $this->setAttrValue($attrName, $attrValue);
        }
    }
    
    protected function clearErrors() {
        $this->_errors = [];
    }
    
    public function setError($attrName, $message) {
        if(isset($this->_errors[$attrName])) {
            $this->_errors[$attrName] .= "<br />" . $message;
        }
        else {
            $this->_errors[$attrName] = $message;
        }
    }
    
    protected function getError($attrName) {
        if(key_exists($attrName, $this->_errors)) {
            return $this->_errors[$attrName];
        }
        else {
            return NULL;
        }
    }

    public function displayErrors($attrName) {
        $error = $this->getError($attrName);
        
        if($error) {
            echo "<div style='color:red;font-size=85%'>$error</div>";
        }
    }
    
    public function hasErrors() {
        return count($this->_errors) > 0;
    }
    
    private function insert() {
            
        $fieldnames = [];
        $values = [];

        foreach($this->_attributes as $attr) {
                        
            if($attr->getDataType() != DataType::VIRTUAL) {
                
                $fieldnames[] = $attr->getName();
                $value = $attr->getValue();
                
                if($value == null) {
                    $values[] = 'null';
                }
                else {
                    $values[] = "\"$value\""; // O valor entre aspas duplas
                }
            }
            
        }
        
        $sql = 'INSERT INTO ' . $this->getTableName() .
            '(' . implode(', ', $fieldnames) . ') VALUES (' .
                  implode(', ', $values) . ')';
            
        $db = $this->getController()->getApp()->getDb();
        
        return $db->exec($sql);
    }

}

?>