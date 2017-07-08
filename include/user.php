<?php
class User{
    private static $instance = null;

    private function __construct(){
    }

    public static function getInstance(){
        if (self::$instance == null)
        {
            self::$instance = new self();
        }
    
        return self::$instance;
    }

    public function register($username, $third_party_id, $channel='youtube'){
        //youtube provide channel_id instead of user_id.
        if(empty($username) || empty($third_party_id) || empty($channel)){
            throw new Exception('username or channel or channel_id is null');
        }
        $users = $this->get_user_by_username($username);
        if(count($users) != 0){
            throw new Exception('please choose another username');
        }
        $dbh = DB::get_dbh();
        $sql = 'INSERT INTO user (username, third_party_id, channel) VALUES(?, ?, ?)';
        $sth = $dbh->prepare($sql); 
        $is_succ = $sth->execute(array($username, $third_party_id, $channel));
        if($is_succ == false) throw Exception('database error');
    }

    public function get_user_by_username($username){
        $dbh = DB::get_dbh();
        $sql = 'SELECT * FROM user where username = ?';
        $sth = $dbh->prepare($sql);
        $sth->execute(array($username));
        return $sth->fetchAll();
    }

    public function get_user_by_third_party_id_and_channel($third_party_id, $channel){
        $dbh = DB::get_dbh();
        $sql = 'SELECT * FROM user where third_party_id = ? and channel = ? ';
        $sth = $dbh->prepare($sql);
        $sth->execute(array($third_party_id, $channel));
        return $sth->fetchAll();
    }

    public function is_registered($third_party_id, $channel){
        $users = $this->get_user_by_third_party_id_and_channel($third_party_id, $channel);
        if(count($users) != 0) return true;
        return false;
    }

    public function set_session_id($username){
        $session_id = $this->create_sessionid();
        $dbh = DB::get_dbh();
        $sql = 'UPDATE user SET session_id = ? where username=?';
        $sth = $dbh->prepare($sql);
        $is_succ = $sth->execute(array($session_id, $username));
        if($is_succ == false) 
            throw new Exception('login user fail, failed to write sessionid into db');
        return $session_id;
    }

    public function create_sessionid(){
        return gen_uuid();
    }

    public function get_user_by_session_id($session_id){
        $dbh = DB::get_dbh();
        $sql = 'SELECT * FROM user where session_id = ?';

        $sth = $dbh->prepare($sql);
        $sth->execute(array($session_id));
        $rows = $sth->fetchAll();

        if(count($rows) != 1){
            return null;
        }

        return $rows[0];
    }

}
