<?php

    use PHPUnit\Framework\TestCase;
    use App\Core\BaseModel;
    use PDO;
    use PDOStatement;

    /**
     * Classe BaseModelTest
     *
     * Esta classe contém testes para os métodos do modelo base (BaseModel).
     */
    class BaseModelTest extends TestCase {
        protected $pdo;
        protected $stmt;
        protected $model;

        /**
         * Configuração inicial para cada teste.
         *
         * Este método é chamado antes de cada método de teste.
         */
        protected function setUp(): void {
            $this->pdo = $this->createMock(PDO::class);
            $this->stmt = $this->createMock(PDOStatement::class);

            $this->stmt->method('execute')->willReturn(true);
            $this->pdo->method('prepare')->willReturn($this->stmt);

            $this->model = new BaseModel($this->pdo, 'test_table');
        }

        /**
         * Testa o método findAll para garantir que ele retorne todos os registros corretamente.
         */
        public function testFindAll() {
            $this->stmt->method('fetchAll')->willReturn([
                ['id' => 1, 'name' => 'Test']
            ]);

            $result = $this->model->findAll();
            $this->assertIsArray($result);
            $this->assertCount(1, $result);
            $this->assertEquals(1, $result[0]['id']);
            $this->assertEquals('Test', $result[0]['name']);
        }

        /**
         * Testa o método findById para garantir que ele retorne um registro corretamente.
         */
        public function testFindById() {
            $this->stmt->method('fetch')->willReturn(['id' => 1, 'name' => 'Test']);

            $result = $this->model->findById(1);
            $this->assertIsArray($result);
            $this->assertEquals(1, $result['id']);
            $this->assertEquals('Test', $result['name']);
        }

        /**
         * Testa o método save para garantir que ele salve um registro corretamente.
         */
        public function testSave() {
            $this->stmt->method('bindValue')->willReturn(true);
            
            $this->stmt->expects($this->once())->method('execute')->willReturn(true);

            $result = $this->model->save(['name' => 'Test', 'value' => 100]);
            $this->assertTrue($result);
        }

        /**
         * Testa o método update para garantir que ele atualize um registro corretamente.
         */
        public function testUpdate() {
            $this->stmt->method('bindValue')->willReturn(true);
            
            $this->stmt->expects($this->once())->method('execute')->willReturn(true);

            $result = $this->model->update(1, ['name' => 'Updated Test', 'value' => 200]);
            $this->assertTrue($result);
        }

        /**
         * Testa o método delete para garantir que ele delete um registro corretamente.
         */
        public function testDelete() {
            $this->stmt->method('bindParam')->willReturn(true);
            
            $this->stmt->expects($this->once())->method('execute')->willReturn(true);

            $result = $this->model->delete(1);
            $this->assertTrue($result);
        }
    }

?>