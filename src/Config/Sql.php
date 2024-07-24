<?php

	namespace App\Config;

	Use PDO;

	class Sql {

		private $conn;

		public function __construct()
		{
			$this->conn = new PDO('mysql:host=localhost;dbname=soft_expert', 'root', 'root');
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function getConnection() {
			return $this->conn;
		}
	}

 ?>