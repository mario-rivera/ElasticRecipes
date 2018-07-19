<?php
require_once __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use Elasticsearch\Client;
use App\Recipes\RecipeDeleter;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException as ElasticInvalidArgumentException;
use App\Recipes\RecipeNotFoundException;
use App\Recipes\InvalidArgumentsException;

class RecipeDeleterTests extends TestCase
{
    private $instance;

    protected function setUp()
    {
        $this->instance = new RecipeDeleter();
    }

    protected function tearDown()
    {
        $this->instance = NULL;
    }

    public function testDelete_invalidArguments_InvalidArgumentsException()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('delete')
            ->will($this->throwException(new ElasticInvalidArgumentException));


        $this->expectException(InvalidArgumentsException::class);
        $this->instance->delete($stub, []);
    }

    public function testDelete_notExists_RecipeNotFoundException()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('delete')
            ->will($this->throwException(new Missing404Exception));


        $this->expectException(RecipeNotFoundException::class);
        $this->instance->delete($stub, []);
    }

    public function testDelete_Ok_BooleanTrue()
    {
        $stub = $this->createMock(Client::class);

        $deleteResult = true;
        $stub
            ->method('delete')
            ->willReturn($indexResult);


        $result = $this->instance->delete($stub, ['id' => 'new recipe']);
        $this->assertEquals($indexResult, $result);
    }
}
