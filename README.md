Sistema de Login - RKR
=======================

Samir Palumbo Khalifa samirpalumbo@gmail.com

Instalar Dependencias
---------------------
composer install

Configurar BD
-------------

- No arquivo ./config/autoload/local.php
...
 return array(
     'db' => array(
         'username' => 'colocar username local',
         'password' => 'colocar senha local',
     ),
 );
...

- No arquivo ./config/autoload/global.php
...
'dsn'            => 'mysql:dbname=nomedoDB;host=nomeDoHost',
...

Criar Tabela User
-----------------
CREATE TABLE `user` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
 `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `users_email_unique` (`email`),
 UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

Rotas
-----
- /auth formulário para login de autenticação
- /register formulário para registrar ou alterar usuário