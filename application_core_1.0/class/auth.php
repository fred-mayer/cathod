<?php

class TAuth
{
    protected $db;

    public $user = null;
    public $isAdmin = false;
    public $isAuthorized = false;

    public function __construct( TMySQL $db )
    {
        $this->db = $db;
        $cookies = new TCookies();
        
    	// Авторизован пользователь?
        if ( isset($cookies->login) && isset($cookies->hash) )
        {
            $login = $cookies->login;
            $hash = $cookies->hash;


            $user = $this->db->select( 'SELECT id, login FROM users WHERE login=\''.$login.'\' AND `hash`=\''.$hash.'\' LIMIT 1' )->current();

            if ( $user === false )
	    {
                $this->db->update( 'users', array( 'hash'=>md5( $this->generateHash() ) ), 'login=\''.$login.'\'' );

	        unset( $cookies->login );
	        unset( $cookies->hash );
	    }
	    else
	    {
                $this->user = $user;
                $this->isAuthorized = true; // Пользователь авторизован
                
                $this->isAdmin = true; // Пользователь админ
	    }
	}
        
        if ( isset($_GET['logout']) )
        {
            $this->logout();
        }
    }
    
    public function login( $login, $password )
    {
        $cookies = new TCookies();

        $user = $this->db->select( 'SELECT id, login FROM users WHERE login=\''.$login.'\' AND password=\''.md5( $password ).'\' LIMIT 1' )->current();

        if ( $user === false )
	{
            $this->db->update( 'users', array( 'hash'=>md5( $this->generateHash() ) ), 'login=\''.$login.'\'' );

	    unset( $cookies->login );
	    unset( $cookies->hash );
	}
	else // Успешный вход
	{
            $cookies->login = $user->login;
            $cookies->hash = md5( $this->generateHash() );
            
            $this->db->update( 'users', array( 'hash'=>$cookies->hash ), 'login=\''.$cookies->login.'\'' );

            $this->user = $user;
            $this->isAuthorized = true; // Пользователь авторизован
            
            $this->isAdmin = true; // Пользователь админ
	}
        
        return $this->isAuthorized;
    }
    
    public function logout()
    {
        $cookies = new TCookies();

        if ( $this->isAuthorized )
	{
            $this->db->update( 'users', array( 'hash'=>md5( $this->generateHash() ) ), 'login=\''.$this->user->login.'\'' );

	    unset( $cookies->login );
	    unset( $cookies->hash );
            
            $this->user = null;
            $this->isAdmin = false;
            $this->isAuthorized = false;
	}
    }
    
    public function generateHash( $length=6 ) // Функция для генерации случайной строки
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789';
        $code = '';
        $clen = strlen( $chars ) - 1;
        while ( strlen( $code ) < $length )
        {
            $code .= $chars[ mt_rand( 0, $clen ) ];
        }
        return $code;
    }
}

?>