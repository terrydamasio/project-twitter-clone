<?php

namespace App\Controllers;

//os recursos do miniframework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->render('index');
	}

	public function inscreverse() {

		$this->view->usuario = [
			'nome' => '',
			'email' => '',
			'senha' => ''
		];

		$this->view->erroCadastro = false;

		$this->render('inscreverse');
	}

	public function registrar() {
		//receber dados do form
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);		
		$usuario->__set('email', $_POST['email']);		
		$usuario->__set('senha', $_POST['senha']);	

		//sucesso
		if($usuario->validaCadastro() && count($usuario->getUsuario()) == 0) { 
		//se o retorno da query for igual a 0 o cadastro é efetuado 

			$usuario->salvar();
			$this->render('cadastro');

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