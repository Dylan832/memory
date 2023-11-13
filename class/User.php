<?php
session_start();
require_once('./function.php');
require_once('./bdd.php');

class User
{
    public $id;
    public $login;
    public $password;
    public $email;
    public $firstname;
    public $lastname;

    public function __construct($id, $login, $password, $email, $firstname, $lastname)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function getAllinfo()
    {
        return $this->login;
        return $this->password;
        return $this->email;
        return $this->firstname;
        return $this->lastname;
    }
    public function getId($bdd)
    {
        $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $recupUser->execute([$_POST['login']]);
    }

    public function getLogin($login)
    {
        return $this->login;
    }
    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword($password)
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function getEmail($email)
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function getFirstname($firstname)
    {
        return $this->firstname;
    }
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }


    public function getLastname($lastname)
    {
        return $this->lastname;
    }
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function register($bdd)
    {
        $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $recupUser->execute([$this->login]);
        $insertUser = $bdd->prepare("INSERT INTO utilisateurs (login,password, email,firstname,lastname) VALUES(?,?,?,?,?)");

        if (login($this->login) == false) {
        } elseif (email($this->email) == false) {
        } elseif (password($this->password) == false) {
        } elseif (confirm_password($_POST['confirm_password']) == false) {
        } elseif (firstname($this->firstname) == false) {
        } elseif (lastname($this->lastname) == false) {
        } elseif (same_password($this->password, $_POST['confirm_password']) == false) {
        } elseif (special_login($this->login) == false) {
        } elseif ($recupUser->rowCount() > 0) {
            echo 'Login déjà utilisé';
        } else {
            $insertUser->execute([$this->login, password_hash($this->password, PASSWORD_DEFAULT), $this->email, $this->firstname, $this->lastname]);
            header('Location:connexion.php');
        }
    }


    public function connect($bdd)
    {
        $request = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $request->execute([$this->login]);
        $res = $request->fetchAll(PDO::FETCH_OBJ);
        // var_dump($res);

        $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ? AND password = ?");
        $recupUser->execute([$this->login, $res[0]->password]);
        $result = $recupUser->fetch(PDO::FETCH_OBJ);
        // $_SESSION['user'] = serialize($this);

        if (login($this->login) == false) {
        } elseif (password($this->password) == false) {
        } elseif (special_login($this->login) == false) {
        } elseif ($recupUser->rowCount() > 0) {
            if ($result != false) {
                if (password_verify($this->password, $result->password)) {
                    $this->id = $result->id;
                    $this->login = $result->login;
                    $this->password = $result->password;
                    $this->email = $result->email;
                    $this->firstname = $result->firstname;
                    $this->lastname = $result->lastname;

                    $_SESSION['user'] = $this;
                    header('Location: index.php');
                }
            }
        } else {
            echo 'uilisateurs inconnu';
        }
    }

    public function update($bdd)
    {

        $request = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $request->execute([$this->id]);
        $res = $request->fetchAll(PDO::FETCH_OBJ);
        // var_dump($res[0]->password);

        $recupUser = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ? AND id != ?");
        $recupUser->execute([$this->login, $this->id]);
        // var_dump($res);
        $insertUser = $bdd->prepare("UPDATE utilisateurs SET login = ?,password = ?,email = ?, firstname = ?,lastname = ? WHERE id = ? ");

        if (login($this->login) == false) {
        } elseif (email($this->email) == false) {
        } elseif (password($this->password) == false) {
        } elseif (firstname($this->firstname) == false) {
        } elseif (lastname($this->lastname) == false) {
        } elseif (special_login($this->login) == false) {
        } elseif ($recupUser->rowCount() > 0) {
            echo 'Login déjà utilisé';
        } else {
            if ($this->password != password_verify($this->password, $res[0]->password)) {
                echo  "Ce n'est pas le bon mot de passe";
            } else {
                $insertUser->execute([$this->login, $res[0]->password, $this->email, $this->firstname, $this->lastname, $this->id]);
                $_SESSION['user']->login = $this->login;
                $_SESSION['user']->password = $this->password;
                $_SESSION['user']->email = $this->email;
                $_SESSION['user']->firstname = $this->firstname;
                $_SESSION['user']->lastname = $this->lastname;
                header('Location:profil.php');
            }
        }
    }
    public function updatePassword($bdd)
    {
        $request = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $request->execute([$this->id]);
        $res = $request->fetchAll(PDO::FETCH_OBJ);
        // var_dump($res);
        $insertUser = $bdd->prepare("UPDATE utilisateurs SET password = ? WHERE id = ? ");


        if (password($this->password) == false) {
        } elseif (empty($_POST['new_password'])) {
            echo 'Champ New Password vide';
        } elseif ($_POST['password'] != password_verify($_POST['password'], $res[0]->password)) {
            echo  "Ce n'est pas le bon mot de passe";
        } else {
            $insertUser->execute([$this->password, $this->id]);
            $_SESSION['user']->password = $this->password;
            header('Location:profil.php');
        }
    }
    public function disconnect()
    {
        unset($_SESSION['user']);
    }

    public function isConnected()
    {
        if (isset($_SESSION['user']->login)) {
            echo 'Connected';
            return true;
        } else {
            echo 'Disconnected';
            return false;
        }
    }
}

// $user = new User($login, $password, $email, $firstname, $lastname);
// $user = new User('a', 'a', 'a', 'a', 'a');
// var_dump($user);

// $user->register($bdd);
// $user->connect($bdd);
// $user->disconnect();
// $user->isConnected();

// $user->getAllinfo();
// $user->getLogin();
// $user->setLogin();
// $user->getPassword();
// $user->setPassword();
// $user->getEmail();
// $user->setEmail();
// $user->getFirstname();
// $user->setFirstname();
// $user->getLastname();
// $user->setLastname();
