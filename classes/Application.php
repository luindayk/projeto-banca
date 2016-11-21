<?php
class Application {
    
    private $_configSet; 
    private $_params; 
    private $_dbConnection = null; 

    public function __construct($configSet) {
        $this->_configSet = $configSet;
        $this->_params = $this->getParams();
    }

    private function getParams() {
        return include("config/{$this->_configSet}.php");
    }
    
    public function getParam($paramName) {
        return $this->_params[$paramName];
    }
    
    private function connectDb() {
        
        $this->_dbConnection = new PDO(
            "mysql:host={$this->getParam('db_host')};
            port={$this->getParam('db_port')};
            dbname={$this->getParam('db_schema')};charset=utf8",
            $this->getParam('db_user'), $this->getParam('db_password'));
    }
    
    public function getDb() {
        // Conecta ao BD se ainda não estiver conectado
        if(! isset($this->_dbConnection)) {
            $this->connectDb();
        }
        return $this->_dbConnection;
    }
    
    public function run() {
        $queryString = $_SERVER['QUERY_STRING'];
        $parts = [];
        if($queryString == '') {
            $controller = ucfirst($this->getParam('default_controller'));
            $action = 'action' . ucfirst($this->getParam('default_action'));
        }
        else { 
          $parts = explode('/', $queryString);
            
            switch(count($parts)) :
                case 1: // Apenas o controller
                    $controller = ucfirst($parts[0]);
                    $action = 'action' . ucfirst($this->getParam('default_action'));
                    break;
                
                case 2: // Controller/action
                    $controller = ucfirst($parts[0]);
                    $action = 'action' . ucfirst($parts[1]);
                    break;
                
                case 3: // Controller/action/id
                    $controller = ucfirst($parts[0]);
                    $action = 'action' . ucfirst($parts[1]);
                    $id = $parts[2];                    
                    if(! is_numeric($id)) {
                        header('HTTP/1.0 400 Bad request');
                        exit(1);    
                    }                
            endswitch;
        }

        $classFilename = "controllers/{$controller}Controller.php";
        
        if(is_file($classFilename)) {
            require_once("controllers/{$controller}Controller.php");
        }
        else {
            // Arquivo do controller não encontrado
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
        
        // Cria o controller
        $controllerClassName = $controller . 'Controller';
        
        // Classe do controller não encontrada no arquivo
        if(!class_exists($controllerClassName)) {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
        
        $c = new $controllerClassName($this);
        
        // Método da action não encontrado no controller
        if(!method_exists($c, $action)) {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
        
        // Caso a rota tenha três partes, é necessário chamar
        // a action passando id como parâmetro
        if(count($parts) == 3) {
            $c->$action($id);
        }
        else {
            // Invoca a action dentro do controller
            $c->$action();
        }

    }
}

?>