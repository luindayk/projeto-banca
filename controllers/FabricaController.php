<?php
require_once('classes/BaseController.php');
require_once('models/Fabrica.php');

class NewsController extends BaseController {
    
    public function actionNew() {
        
        $fabrica = new Fabrica($this);

        if($_POST != []) {
            
            $fabrica->loadRawData();

            if($fabrica->save()) {
                $this->addMessage('Fábrica inserida com sucesso.');
                $this->actionIndex();die;
            }            
        }        
        $this->render('new', $fabrica);
    }
    
    public function requireLogin($action){
        $hasSession = isset($_SESSION['user_id']);

        return false; //!$hasSession && in_array($action, ['new', 'edit', 'delete', 'index']);
    }

    public function actionEdit($id) {
        
        $fabrica = new Fabrica($this); 
        
        if($fabrica->findByPk($id)) {

            if($_POST != []) {
                $fabrica->loadRawData();
            }
            if($fabrica->save()) {
                $this->addMessage('Fábrica atualizada com sucesso.');
                $this->actionIndex();die;
            }
            $this->render('edit', $fabrica);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
    }
    
    public function actionDelete($id) {
        $fabrica = new Fabrica($this); 
        
        if($fabrica->findByPk($id)) {
            
            if($_POST != [] && isset($_POST['_DELETE_'])) {
                if($fabrica->deleteByPk($id)) {
                    $this->addMessage('Fábrica excluída com sucesso.');
                    $this->actionIndex();die;
                }
            }
            else if($_POST != []) {
                header('Location: ?fabrica');
            }
            $this->render('delete', $fabrica);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        } 
    }

    public function actionIndex() {
        $fabrica = new Fabrica($this); 

        $listFabrica = $fabrica->find(); 

        $this->render('index', $listFabrica, 'Fábricas');
    }
}

?>