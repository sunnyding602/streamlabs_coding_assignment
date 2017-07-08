<?php
class DB{
	private static $dbh;
	public static function  get_dbh(){
		if(!empty(self::$dbh)) return self::$dbh;
		require_once(getenv('SITE_ROOT')."/config/config.php");
		self::$dbh = new PDO($db_config['dsn'], $db_config['user'], $db_config['pass'], array(
			PDO::ATTR_PERSISTENT => true
		));
		return self::$dbh;
	}

}

//$dbh = DB::get_dbh();
