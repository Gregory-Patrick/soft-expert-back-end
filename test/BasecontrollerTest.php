<?php

    use PHPUnit\Framework\TestCase;
    use App\Core\BaseController;
    use App\Utils\Response;
    use App\Utils\Validate;
    use App\Models\ProductModel;

    /**
     * Classe BaseControllerTest
     *
     * Esta classe contém testes para os métodos do controlador base (BaseController).
     */
    class BaseControllerTest extends TestCase
    {
        private $baseController;
        private $modelMock;
        private $responseMock;
        private $validateMock;

        /**
         * Configuração inicial para cada teste.
         *
         * Este método é chamado antes de cada método de teste.
         */
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

        /**
         * Testa o método getAll para garantir que ele retorne todos os itens corretamente.
         */
        public function testGetAll()
        {
            $data = ['item1', 'item2'];

            $this->modelMock->method('findAll')->willReturn($data);

            $this->responseMock->expects($this->once())
                ->method('objectResponse')
                ->with(200, $data);

            $this->baseController->getAll();
        }

        /**
         * Testa o método getAll quando não há itens encontrados.
         */
        public function testGetAllNotFound()
        {
            $this->modelMock->method('findAll')->willReturn(false);

            $this->responseMock->expects($this->once())
                ->method('setSimpleResponse')
                ->with(404, 'Not found');

            $this->baseController->getAll();
        }

        /**
         * Testa o método getById para garantir que ele retorne um item corretamente.
         */
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

        /**
         * Testa o método getById quando o item não é encontrado.
         */
        public function testGetByIdNotFound()
        {
            $this->responseMock->method('getItemRequestId')->willReturn(1);
            $this->modelMock->method('findById')->willReturn(false);

            $this->responseMock->expects($this->once())
                ->method('setSimpleResponse')
                ->with(404, 'Not found');

            $this->baseController->getById();
        }

        /**
         * Testa o método create para garantir que ele crie um item corretamente.
         */
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

        /**
         * Testa o método create quando ocorre uma falha ao salvar o item.
         */
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

        /**
         * Testa o método update para garantir que ele atualize um item corretamente.
         */
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

        /**
         * Testa o método update quando o item não é encontrado.
         */
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

        /**
         * Testa o método delete para garantir que ele delete um item corretamente.
         */
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

        /**
         * Testa o método delete quando o item não é encontrado.
         */
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

?>