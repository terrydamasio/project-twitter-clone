<?php
	namespace App\Controllers;

	//recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

	class IndexController extends Action {

		public function index() {

			$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
			$this->render('index');
		}

		public function inscreverse() {
			$this->view->usuario = [
				'nome' => '',
				'email' => '',
				'senha' => ''
			];

			$this->view->erroCadastro = false;
			$this->view->erroCadastro2 = false;
			$this->render('inscreverse');
		}

		public function registrar() {
			//receber dados do form
			$usuario = Container::getModel('Usuario');

			$usuario->__set('nome', $_POST['nome']);		
			$usuario->__set('email', $_POST['email']);		
			$usuario->__set('senha', md5($_POST['senha']));	

			//sucesso -> se o retorno da query for igual a 0 o cadastro é efetuado 
			if($usuario->validarCadastro()) { 
				
				$this->view->usuario = [
					'nome' => $_POST['nome'],
					'email' => $_POST['email'],
					'senha' => $_POST['senha']
				];

				//verifica se usuário já existe
				if(count($usuario->getUsuario()) == 0) {
					$usuario->salvar();
					$this->render('cadastro');
				} else {
					$this->view->erroCadastro2 = true; //parametro para verificar se existe os campos foram preenchidos com sucesso.
					$this->render('inscreverse');	
				}

			} else { //erro
				
				//capturando valor do input e deixar os valores preenchidos para o usuario não preencher novamente
				$this->view->usuario = [
					'nome' => $_POST['nome'],
					'email' => $_POST['email'],
					'senha' => $_POST['senha']
				];

				$this->view->erroCadastro = true; //parametro para verificar se existe os campos foram preenchidos com sucesso.
				$this->render('inscreverse');
			}
		}

	}


?>