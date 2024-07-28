<?php

use PHPUnit\Framework\TestCase;
use App\Core\BaseController;
use App\Utils\Response;
use App\Utils\Validate;
use App\Models\ProductModel;

class BaseControllerTest extends TestCase
{
    private $baseController;
    private $modelMock;
    private $responseMock;
    private $validateMock;

    protected function setUp(): void
    {
        $this->modelMock = $this->createMock(ProductModel::class);
        $this->responseMock = $this->createMock(Response::class);
        $this->validateMock = $this->createMock(Validate::class);

        $this->baseController = $this->getMockBuilder(BaseController::class)
            ->setConstructorArgs([$this->modelMock])
            ->onlyMethods(['validate', 'errorValidate'])
            ->getMock();

        $this->baseController->setResponse($this->responseMock);
        $this->baseController->setValidate($this->validateMock);
    }

    public function testGetAll()
    {
        $data = ['item1', 'item2'];

        $this->modelMock->method('findAll')->willReturn($data);

        $this->responseMock->expects($this->once())
            ->method('objectResponse')
            ->with(200, $data);

        $this->baseController->getAll();
    }

    public function testGetAllNotFound()
    {
        $this->modelMock->method('findAll')->willReturn(false);

        $this->responseMock->expects($this->once())
            ->method('setSimpleResponse')
            ->with(404, 'Not found');

        $this->baseController->getAll();
    }

    public function testGetById()
    {
        $data = ['id' => 1, 'name' => 'test'];

        $this->responseMock->method('getItemRequestId')->willReturn(1);
        $this->modelMock->method('findById')->willReturn($data);

        $this->responseMock->expects($this->once())
            ->method('objectResponse')
            ->with(200, $data);

        $this->baseController->getById();
    }

    public function testGetByIdNotFound()
    {
        $this->responseMock->method('getItemRequestId')->willReturn(1);
        $this->modelMock->method('findById')->willReturn(false);

        $this->responseMock->expects($this->once())
            ->method('setSimpleResponse')
            ->with(404, 'Not found');

        $this->baseController->getById();
    }

    public function testCreate()
    {
        $data = ['name' => 'test'];

        $this->responseMock->method('getDataRequest')->willReturn($data);
        $this->modelMock->method('save')->willReturn(true);

        $this->responseMock->expects($this->once())
            ->method('setSimpleResponse')
            ->with(200, 'Registered successfully');

        $this->baseController->create();
    }

    public function testCreateFailure()
    {
        $data = ['name' => 'test'];

        $this->responseMock->method('getDataRequest')->willReturn($data);
        $this->modelMock->method('save')->willReturn(false);

        $this->responseMock->expects($this->once())
            ->method('setSimpleResponse')
            ->with(500, 'Something unexpected happened. Try again later');

        $this->baseController->create();
    }

    public function testUpdate()
    {
        $data = ['name' => 'test'];
        $id = 1;

        $this->responseMock->method('getDataRequest')->willReturn($data);
        $this->responseMock->method('getItemRequestId')->willReturn($id);
        $this->modelMock->method('findById')->willReturn(true);
        $this->modelMock->method('update')->willReturn(true);

        $this->responseMock->expects($this->once())
            ->method('setSimpleResponse')
            ->with(200, 'Updated successfully');

        $this->baseController->update();
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'test'];
        $id = 1;

        $this->responseMock->method('getDataRequest')->willReturn($data);
        $this->responseMock->method('getItemRequestId')->willReturn($id);
        $this->modelMock->method('findById')->willReturn(false);

        $this->responseMock->expects($this->exactly(1))
            ->method('setSimpleResponse')
            ->with(404, 'Not found');

        $this->baseController->update();
    }

    public function testDelete()
    {
        $id = 1;

        $this->responseMock->method('getItemRequestId')->willReturn($id);
        $this->modelMock->method('findById')->willReturn(true);

        $this->responseMock->expects($this->once())
            ->method('setSimpleResponse')
            ->with(200, 'Deleted successfully');

        $this->baseController->delete();
    }

    public function testDeleteNotFound()
    {
        $id = 1;

        $this->responseMock->method('getItemRequestId')->willReturn($id);
        $this->modelMock->method('findById')->willReturn(false);

        $this->responseMock->expects($this->exactly(1))
            ->method('setSimpleResponse')
            ->with(404, 'Not found');

        $this->baseController->delete();
    }
}
