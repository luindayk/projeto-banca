<?php
require_once('classes/BaseController.php');
require_once('models/Image.php');

class NewsController extends BaseController {
    
    public function actionNew() {
        
        $image = new Image($this);

        if($_POST != []) {
            
            $image->loadRawData();

            if($image->save()) {
                $this->addMessage('Imagem inserida com sucesso.');
                $this->actionIndex();die;
            }            
        }        
        $this->render('new', $image);
    }
    
    public function requireLogin($action){
        $hasSession = isset($_SESSION['user_id']);

        return false;//!$hasSession && in_array($action, ['new', 'edit', 'delete', 'index']);
    }

    public function actionEdit($id) {
        
        $image = new Image($this); 
        
        if($image->findByPk($id)) {

            if($_POST != []) {
                $image->loadRawData();
            }
            if($image->save()) {
                $this->addMessage('Imagem atualizada com sucesso.');
                $this->actionIndex();die;
            }
            $this->render('edit', $image);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        }
    }
    
    public function actionDelete($id) {
        $image = new Image($this); 
        
        if($image->findByPk($id)) {
            
            if($_POST != [] && isset($_POST['_DELETE_'])) {
                if($image->deleteByPk($id)) {
                    $this->addMessage('Imagem excluída com sucesso.');
                    $this->actionIndex();die;
                }
            }
            else if($_POST != []) {
                header('Location: ?image');
            }
            $this->render('delete', $image);    
        }
        else {
            header('HTTP/1.0 404 Not Found');
            exit(1);
        } 
    }

    public function actionIndex() {
        $image = new Image($this); 

        $listImage = $image->find(); 

        $this->render('index', $listImage, 'Lista de Imagens');
    }
}

?>