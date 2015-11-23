<?php
namespace todo;

/**
* Class databaseHelper
*/
class databaseHelper {
	/**
	* @var string
	*/
	protected $dbHost = '';

	/**
	* @var string
	*/
	protected $dbUser = '';

	/**
	* @var string
	*/
	protected $dbPass = '';

	/**
	* @var string
	*/
	protected $dbName = '';

	/**
	 * @param string $host
	 * @param string $dbName
	 * @param string $user
	 * @param string $pass
	 */
	public function __construct($host = '', $dbName = '', $user = '', $pass = '') {
		$this->setDbHost($host);
		$this->setDbName($dbName);
		$this->setDbPass($pass);
		$this->setDbUser($user);
	}

	/**
	* @param string $dbHost
	*/
	public function setDbHost($dbHost) {
		$this->dbHost = $dbHost;
	}

	/**
	* @param string $dbUser
	*/
	public function setDbUser($dbUser) {
		$this->dbUser = $dbUser;
	}

	/**
	* @param string $dbPass
	*/
	public function setDbPass($dbPass) {
		$this->dbPass = $dbPass;
	}

	/**
	* @param string $dbName
	*/
	public function setDbName($dbName) {
		$this->dbName = $dbName;
	}


	/**
	* Minimalist db helper for mysql via pdo.
	*
	* @return PDO
	*/
	public function getDB() {
		$mysql_conn_string = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName;
		$dbConnection = new \PDO($mysql_conn_string, $this->dbUser, $this->dbPass);
		$dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $dbConnection;
	}
}
