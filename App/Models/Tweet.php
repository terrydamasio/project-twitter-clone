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
        public function salvarTweet() {
            $query = "insert into tweets(id_usuario, tweet) values(:id_usuario, :tweet)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':tweet', $this->__get('tweet'));
            $stmt->execute();

            return $this;
        }

        //recuperar tweets
        public function recuperarTweet() {
            $query = "
                select 
                    t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data 
                from 
                    tweets as t
                    left join usuarios as u on (t.id_usuario = u.id)
                where 
                    id_usuario = :id_usuario 
                order by 
                    t.data desc
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

    }