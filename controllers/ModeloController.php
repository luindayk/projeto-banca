<?php
require_once('classes/BaseController.php');
require_once('models/Modelo.php');

class NewsController extends BaseController {
    
    public function actionNew() {
        
        $modelo = new Modelo($this);

        if($_POST != []) {
            
            $modelo->loadRawData();

            if($modelo->save()) {
                $this->addMessage('Modelo inserido com sucesso.');
                $this->actionIndex();die;
            }            
        }        
        $this->render('new', $modelo);
    }
    
    public function requireLogin($action){
        $hasSession = isset($_SESSION['user_id']);

        return false; //!$hasSession && in_array($action, ['new', 'edit', 'delete', 'index']);
    }

    public function actionEdit($id) {
        
        $modelo = new Modelo($this); 
        
        if($modelo->findByPk($id)) {

            if($_POST != []) {
                $modelo->loadRawData();
            }
            if($modelo->save()) {
                $this->addMessage('Modelo atualizado com sucesso.');
                $this->actionIndex();die;
            }
            $this->render('edit', $modelo);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
    }
    
    public function actionDelete($id) {
        $modelo = new Modelo($this); 
        
        if($modelo->findByPk($id)) {
            
            if($_POST != [] && isset($_POST['_DELETE_'])) {
                if($modelo->deleteByPk($id)) {
                    $this->addMessage('Modelo excluído com sucesso.');
                    $this->actionIndex();die;
                }
            }
            else if($_POST != []) {
                header('Location: ?modelo');
            }
            $this->render('delete', $modelo);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        } 
    }

    public function actionIndex() {
        $modelo = new Modelo($this); 

        $listModelo = $modelo->find(); 

        $this->render('index', $listModelo, 'Fábricas');
    }
}

?>