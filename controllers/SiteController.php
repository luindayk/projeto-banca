<?php
    require_once('classes/BaseController.php');
    require_once('classes/BaseModel.php');

    class SiteController extends BaseController {
        public function requireLogin($action) {
            return false;
        }

        // Action padrão do controller
        public function actionIndex() {
            //$news = new News($this); // cria um novo objeto do modelo
            //$newsList = $news->find(null, 'creation_time desc', 10); // faz uma busca no BD
            //$this->render('index', $newsList); // Renderiza uma view com os dados da busca
            require_once('views/_header.php');
            echo 'hello world';
            require_once('views/_footer.php');
        }
        
        public function actionTeste() {
            require_once('views/_header.php');
            echo 'Isto é um teste';
            require_once('views/_footer.php');
        }
        
    }

?>