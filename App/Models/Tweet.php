<?php
    namespace App\Models;
    
    use MF\Model\Model;

    class Tweet extends Model {
        private $id;
        private $id_usuario;
        private $tweet;
        private $senha;

        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        //salvar tweets


        //recuperar tweets
    }