<?php
/** php-pdo-helper provides a convenient way to handle the rows returned by SQL SELECT command in PDO.
* @link https://github.com/yannbelief/php-pdo-helper
* @author CHEN Yen Ming https://github.com/yannbelief/
* @copyright 2013 CHEN Yen Ming
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
  
class DB
{
	public static $dsn = "";
	public static $account = "";
	public static $password = "";

	private static $db;
	
	public static function instance(){
		if(isset(DB::$db) == false)
			DB::$db = new DB;	
		return DB::$db;
	}
	
	public function pdo(){
	
		return $this->pdo;
	} 
	
	public function notORM(){
		include "notORM/NotORM.php";
		$tables = new NotORM($this->pdo());
		return $tables;
	}
	

	protected $pdo;
	public function __construct() {

    	$dsn = DB::$dsn;
		$user = DB::$account;
		$pwd = DB::$password;
    	$dbh = new PDO($dsn, $user, $pwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$this->pdo = $dbh;

	}
	
	/* Fetch records which contains only the value of first column and return them as an array */
	function fetchColOneArr($sql,$params=array()) {
		$rows = $this->fetch($sql,$params,PDO::FETCH_NUM);
		$result = array();
		foreach($rows as $row) {
			$result[] = $row[0];
		}
		return $result;
	}
	
	/* Fetch only the first record as object. A default object can be passed in as a argument to replace the return value NULL when it fetchs no record. */
	function fetchOneObj($sql,$params=array(),$defaultObj=NULL) {
		$rows = $this->fetch($sql,$params);
		if(count($rows) > 0)
			$result = $rows[0];
		else
			$result = $defaultObj;
		return $result;
	}
	
	/* Fetch the value of the first column from 1st record. A default object can be passed in as a argument to replace the return value NULL when it fetchs no record. */
	function fetchOneVal($sql,$params=array(),$defaultProp=NULL) {
		$rows = $this->fetch($sql,$params,PDO::FETCH_NUM);
		if(count($rows) > 0)
			$result = $rows[0][0];
		else
			$result = $defaultProp;
		return $result;
	}
	
	/* Use this method when your SQL starts with "INSERT...", it will return the id of inserted record. */
	function insert($sql,$params=array()) {
		try {
		   $sth = $this->pdo->prepare($sql);
 		   $sth->execute($params);
 		   return $this->pdo->lastInsertId();
 		} catch (PDOException $e) {
			echo $e->getMessage();
		}   
	}

	/* Use this method when you are using SQL command like "UPDATE", "ALTER TABLE", ...etc. */
	function execute($sql,$params=array()) {
		   $sth = $this->pdo->prepare($sql);
 		   return $sth->execute($params);
	}
	
	/* Fecth records according your SQL command and return records as objects. */
	function fetch($sql,$params=array(),$method=PDO::FETCH_OBJ) {
		   $sth = $this->pdo->prepare($sql);
 		   $sth->execute($params);
 		   $result = $sth->fetchAll($method);
 		   return $result;
	}
	
	/* Fecth records according your SQL command and return records as associated arrays. */
	function fetchArr($sql,$params=array()) {
		return $this->fetch($sql,$params=array(),PDO::FETCH_BOTH);

	}

	
	function __destruct() {
		$this->pdo = NULL;
	}
}
?>
