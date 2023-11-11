<?php 
    namespace App\Controllers;

	//recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

	class AppController extends Action {

        public function timeline() {
            session_start();
        
            //verificar se os dados estão preenchidos para mostrar a página restrita
            if($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
                $this->render('timeline');
            } else {
                header('location: /?acesso=erro');
            }
        }
    }
