<?php 
    namespace App\Controllers;

	//recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

	class AppController extends Action {

        public function timeline() {

            //verificar se os dados estão preenchidos para mostrar a página restrita
            $this->validaAutenticacao();

            //recuperação dos tweets
            $tweet = Container::getModel('Tweet');

            //passar o parâmetro do id_usuario para recuperar tweets do usuário
            $tweet->__set('id_usuario', $_SESSION['id'] );
            $tweets = $tweet->recuperarTweet();

            //criando atributo dinâmico
            $this->view->tweets = $tweets;

            $this->render('timeline');
            
        }

        public function tweet() {
        
            //verificar se os dados estão preenchidos para mostrar a página restrita
            $this->validaAutenticacao();
                
            $tweet = Container::getModel('Tweet');
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweet->salvarTweet();

            header('location: /timeline');

        
        }

        public function quemSeguir() {

            //verificar se os dados estão preenchidos para mostrar a página restrita
            $this->validaAutenticacao();

            $pesquisarUsuario = isset($_GET['pesquisarUsuario']) ? $_GET['pesquisarUsuario'] : '';
            $usuarios = array();

            if($pesquisarUsuario != '') {
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarUsuario);
                $usuarios = $usuario->getAllUsers();
            }

            $this->view->usuarios = $usuarios; 

            $this->render('quemSeguir');
        }

        public function validaAutenticacao() {
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
                header('location: /?login=erro');
            } 
        }

    }
