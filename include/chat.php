<?php
class Chat{
    private static $instance = null;

    private function __construct(){
    }

    public static function getInstance(){
        if (self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_msg_by_username($username){
        $dbh = DB::get_dbh();
        $sql = 'SELECT * FROM chat where username = ?';
        $sth = $dbh->prepare($sql);
        $sth->execute(array($username));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}
