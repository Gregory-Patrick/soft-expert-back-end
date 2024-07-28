<?php

	namespace App\Config;

	use PDO;
	use PDOException;

	/**
	 * Classe Sql
	 *
	 * Esta classe gerencia a conexão com o banco de dados utilizando PDO.
	 */
	class Sql {

		/**
		 * @var PDO A instância da conexão PDO.
		 */
		private $conn;

		/**
		 * Construtor da classe Sql.
		 *
		 * Inicializa a conexão com o banco de dados PostgreSQL.
		 * Define o modo de erro do PDO para exceções.
		 *
		 * @throws \PDOException Se houver um erro ao tentar se conectar ao banco de dados.
		 */
		public function __construct() {
			$dsn = 'pgsql:host=localhost;port=5432;dbname=soft_expert'; // Altere como necessário
			$username = 'postgres'; // Altere como necessário
			$password = 'admin'; // Altere como necessário

			try {
				$this->conn = new PDO($dsn, $username, $password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
				die();
			}
		}

		/**
		 * Obtém a conexão PDO.
		 *
		 * @return PDO A instância da conexão PDO.
		 */
		public function getConnection() {
			return $this->conn;
		}
	}
?>