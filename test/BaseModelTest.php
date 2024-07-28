<?php

use PHPUnit\Framework\TestCase;
use App\Core\BaseModel;
use PDO;
use PDOStatement;

class BaseModelTest extends TestCase {
    protected $pdo;
    protected $stmt;
    protected $model;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($this->stmt);

        $this->model = new BaseModel($this->pdo, 'test_table');
    }

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

    public function testFindById() {
        $this->stmt->method('fetch')->willReturn(['id' => 1, 'name' => 'Test']);

        $result = $this->model->findById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Test', $result['name']);
    }

    public function testSave() {
        $this->stmt->method('bindValue')->willReturn(true);
        
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->model->save(['name' => 'Test', 'value' => 100]);
        $this->assertTrue($result);
    }

    public function testUpdate() {
        $this->stmt->method('bindValue')->willReturn(true);
        
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->model->update(1, ['name' => 'Updated Test', 'value' => 200]);
        $this->assertTrue($result);
    }

    public function testDelete() {
        $this->stmt->method('bindParam')->willReturn(true);
        
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);

        $result = $this->model->delete(1);
        $this->assertTrue($result);
    }
}

?>
