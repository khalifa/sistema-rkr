<?php
namespace Application\Model;

 class User
 {
     public $id;
     public $username;
     public $password;
     public $email;
     public $name;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->username = (!empty($data['username'])) ? $data['username'] : null;
         $this->password  = (!empty($data['password'])) ? $data['password'] : null;
         $this->email  = (!empty($data['email'])) ? $data['email'] : null;
         $this->name  = (!empty($data['name'])) ? $data['name'] : null;
     }
 }