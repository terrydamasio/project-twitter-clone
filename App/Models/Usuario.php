<?php
    namespace App\Models;
    
    use MF\Model\Model;
    use PDO;

    class Usuario extends Model {
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        //salvar cadastro
        public function salvar() {
            $query = "insert into usuarios(nome, email, senha) values(:nome, :email, :senha)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
            $stmt->execute();
            
            return $this;
        }

        //validar cadastro
        public function validarCadastro() {
            $valido = true;

            if(strlen($this->__get('nome')) < 3)
                $valido = false;
                
            if(strlen($this->__get('email')) < 3)
                $valido = false;

            if(strlen($this->__get('senha')) < 3)
                $valido = false;

            return $valido;
        }

        //recuperar cadastro por email
        public function getUsuario() {
            $query = "select nome, email from usuarios where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //autenticar usuario no login
        public function autenticar() {
            $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":email", $this->__get('email'));
            $stmt->bindValue(":senha", $this->__get('senha'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC); // retornar apenas um único registro (fetch)

            // verifica se id e nome existem e seta a partir do proprio objeto Usuario
            if($usuario['id'] != '' && $usuario['nome'] != '') {
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $this; // retornar o próprio objeto 
        }   

        //recuperar todos usuarios de acordo com termo de pesquisa
        public function getAllUsers() {
            $query = "
                select 
                    u.id, u.nome, u.email,
                    (
                        select 
                            count(*)
                        from 
                            usuarios_seguidores as us
                        where 
                            us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
                    ) as seguindo_sn
                from 
                    usuarios as u
                where 
                    u.nome like :nome and u.id != :id_usuario 
            ";  //id é diferente do id do usuário autenticado

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id')); 
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function seguirUsuario($id_usuario_seguindo) {
            $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values(:id_usuario, :id_usuario_seguindo)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
            $stmt->execute();

            return true;
        }

        public function deixarSeguirUsuario($id_usuario_seguindo) {
            $query = "delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
            $stmt->execute();

            return true;
        }

        public function usuariosSeguindo() {
            $query = "
                select COUNT(*) AS usuarios_seguindo
                FROM usuarios_seguidores where id_usuario = :id_usuario
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function usuariosSeguidores() {
            $query = "
                select COUNT(*) AS usuarios_seguidores
                FROM usuarios_seguidores where id_usuario = :id_usuario
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }



    }