<?php

	namespace App\Config;

	use PDO;

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
		 * Inicializa a conexão com o banco de dados MySQL.
		 * Define o modo de erro do PDO para exceções.
		 *
		 * @throws \PDOException Se houver um erro ao tentar se conectar ao banco de dados.
		 */
		public function __construct() {
			$this->conn = new PDO('mysql:host=localhost;dbname=soft_expert', 'root', 'root');
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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