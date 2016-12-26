<?php
require_once('classes/BaseController.php');
require_once('models/Ficha.php');

class NewsController extends BaseController {
    
    public function actionNew() {
        
        $ficha = new Ficha($this);

        if($_POST != []) {
            
            $ficha->loadRawData();

            if($ficha->save()) {
                $this->addMessage('Ficha inserida com sucesso.');
                $this->actionIndex();die;
            }            
        }        
        $this->render('new', $ficha);
    }
    
    public function requireLogin($action){
        $hasSession = isset($_SESSION['user_id']);

        return false;//!$hasSession && in_array($action, ['new', 'edit', 'delete', 'index']);
    }

    public function actionEdit($id) {
        
        $ficha = new Ficha($this); 
        
        if($ficha->findByPk($id)) {

            if($_POST != []) {
                $ficha->loadRawData();
            }
            if($ficha->save()) {
                $this->addMessage('Ficha atualizada com sucesso.');
                $this->actionIndex();die;
            }
            $this->render('edit', $ficha);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
    }
    
    public function actionDelete($id) {
        $ficha = new Ficha($this); 
        
        if($ficha->findByPk($id)) {
            
            if($_POST != [] && isset($_POST['_DELETE_'])) {
                if($ficha->deleteByPk($id)) {
                    $this->addMessage('Ficha excluída com sucesso.');
                    $this->actionIndex();die;
                }
            }
            else if($_POST != []) {
                header('Location: ?ficha');
            }
            $this->render('delete', $ficha);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        } 
    }

    public function actionIndex() {
        $ficha = new Ficha($this); 

        $listFicha = $ficha->find(); 

        $this->render('index', $listFicha, 'Lista de Fichas');
    }
}

?>