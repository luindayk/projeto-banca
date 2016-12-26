<?php

class Application {
    
    private $_configSet; // Conjunto de configurações
    private $_params; // Parâmetros do conjunto de configurações
    private $_dbConnection = null; // Conexão ao BD
    
    /*
        Este método é o construtor da classe, ou seja, é executado toda vez que se inicia a
        aplicação. Ela recebe como parâmetro o nome do arquivo com configurações padrão da aplicação
        e a atribue à váriavel $_configSet do escopo. Usa também um método para capturar os parâmetros
        da $_confiSet         
    */
    public function __construct($configSet) {
        $this->_configSet = $configSet;
        $this->_params = $this->getParams();
    }
    
    /*
        Este método é responsável por capturar todos os pârametros do arquivo de configurações
    */
    private function getParams() {
        return include("config/{$this->_configSet}.php");
    }
    
    /*
        Este método é responsável por capturar um pârametro específico do arquivo da $_configSet
    */
    public function getParam($paramName) {
        return $this->_params[$paramName];
    }
    
    /*
        Este método é responsável por configurar a conexão com o BD.
        Usa a classe PDO com um construtor que recebe como pârametros do BD (host, porta, nome do banco,
        nome de usuário e senha) e atribue à variável do escopo $_dbConnetion;
    */
    private function connectDb() {
        
        $this->_dbConnection = new PDO(
            "mysql:host={$this->getParam('db_host')};
            port={$this->getParam('db_port')};
            dbname={$this->getParam('db_schema')};charset=utf8",
            $this->getParam('db_user'), $this->getParam('db_password'));
    }
    
    /*
        Este método é responsável por fazer a conexão com o BD.
        Primeiro ele verifica se a variável $_dbConnection não está vazia, e se der 
        certo ele retorna uma nova conexão
    */
    public function getDb() {
        // Conecta ao BD se ainda não estiver conectado
        if(! isset($this->_dbConnection)) {
            $this->connectDb();
        }
        return $this->_dbConnection;
    }

    private function processLogin($controller, $action, $id) {

        session_start();

        // Guarda as informações da rota qeu o usuário originalmente
        // queria acessar
        $_SESSION['route_controller'] = $controller;
        $_SESSION['route_action'] = $action;
        $_SESSION['route_route_id'] = $id;

        header('Location: ?site/login');
    }
    
    /*
     * Função responsável por executar a aplicação, determinando
     * qual controller e qual action serão acionados a partir
     * dos parâmetros passados na URL
     */
    public function run() {
        /* 
            Esta variável é declarada como um array e recebe como valor os caracteres
            digitados na URL
        */             
        $controllerName = '';
        $actionName = '';

        $controllerObj = null;
        $actionMethod = null;
        
        $queryString = $_SERVER['QUERY_STRING'];
        $id = null;

        /*
            Esta variável é declarada como um array e será usada para receber as partes
            em que serão divididas a URL
        */
        $parts = [];

        // Controller e action padrões
        /*
            Verificação da URL, caso esteja vazia é acionada a action padrão que 
            está no arquivo de configuração e foi atribuída na variável $_configSet.            
        */
        if($queryString == '') {
            // define o controller padrão da aplicação e usa a função 'ucfirst' para
            // alterar a primeira letra em maiúsculo (site -> Site)
            $controllerName = strtolower($this->getParam('default_controller'));
            // define a action padrão da aplicação (index)
            $actionName = strtolower($this->getParam('default_action'));
        }
        else 
        { // Caso a URL esteja preenchida...
          // Cria um vetor com as diferentes partes da query string
          $parts = explode('/', $queryString);
            
            switch(count($parts)) :
                case 1: // Apenas o controller
                    // primeira parte do conteúdo digitado na URL para definir o controller
                    $controllerName = strtolower($parts[0]);
                    // como não foi digitado a segunda parte pra uma action, é atribuída
                    // a action padrão (index)
                    $actionName = strtolower($this->getParam('default_action'));
                    break;
                
                case 2: // Controller/action
                    // primeira parte do conteúdo digitado na URL para definir o controller
                    $controllerName = strtolower($parts[0]);
                    // segunda parte do conteúdo e define a action
                    $actionName = strtolower($parts[1]);
                    break;
                
                case 3: // Controller/action/id
                    // primeira parte do conteúdo digitado na URL para definir o controller
                    $controllerName = strtolower($parts[0]);
                    // segunda parte do conteúdo e define a action
                    $actionName = strtolower($parts[1]);
                    // terceira parte do conteúdo e define o id
                    $id = $parts[2];
                    
                    // O id precisa ser um número
                    if(! is_numeric($id)) {
                        header('HTTP/1.0 400 Bad request');
                        exit(1);    
                    }                
            endswitch;
        }
        
        // Carrega o arquivo da classe do controller que deve estar no diretório 
        // controllers.
        $controllerClassName = ucfirst($controllerName) . 'Controller';

        $controllerFileName = "controllers/{$controllerClassName}.php";
        // Verifica se o diretório e arquivo são válidos, caso não, retorna Não Encontrado
        if(is_file($controllerFileName)) {
            require_once($controllerFileName);
        }
        else {
            // Arquivo do controller não encontrado
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }

        // Classe do controller não encontrada no arquivo
        if(!class_exists($controllerClassName)) {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }

        $controllerObj = new $controllerClassName($this);

        $actionMethod = 'action' . ucfirst($actionName);
        
        // Método da action não encontrado no controller
        if(!method_exists($controllerObj, $actionMethod)) {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
        
        // Se o login for necessário, faz o desvio
        if($controllerObj->requireLogin($actionName)) {
            $this->processLogin($controllerName, $actionName, $id);
        }
        
        // Caso a rota tenha três partes, é necessário chamar
        // a action passando id como parâmetro
        if(count($parts) == 3) {
            $controllerObj->$actionMethod($id);
        }
        else {
            // Invoca a action dentro do controller
            $controllerObj->$actionMethod();
        }
    
    }
}

?>