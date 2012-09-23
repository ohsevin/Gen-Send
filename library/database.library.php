<?php
class MFYU_Database extends PDO {
    protected $_dbHandle;
    protected $_result;
	protected $_query;
	protected $_table;

	protected $_describe = array();

	protected $_orderBy;
	protected $_order;
	protected $_extraConditions;
	protected $_hO;
	protected $_hM;
	protected $_hMABTM;
	protected $_page;
	protected $_limit;
	
	public $inflect;
	public $cache;

	public function __construct() {
		
		$this->inflect = Inflection::get_instance();
		$this->cache = Cache::get_instance();
		
		$dsn = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
		$user = DB_USER;
		$password = DB_PASSWORD;
		
		$options = array(
			PDO::ATTR_PERSISTENT => true, 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		
		try {
			parent::__construct($dsn, $user, $password, $options);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}
}	
?>
