<?php
require_once __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use Elasticsearch\Client;
use App\Recipes\RecipeUpdater;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException as ElasticInvalidArgumentException;
use App\Recipes\RecipeNotFoundException;
use App\Recipes\InvalidArgumentsException;

class RecipeUpdaterTests extends TestCase
{
    private $instance;

    protected function setUp()
    {
        $this->instance = new RecipeUpdater();
    }

    protected function tearDown()
    {
        $this->instance = NULL;
    }

    public function testUpdate_invalidArguments_InvalidArgumentsException()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('update')
            ->will($this->throwException(new ElasticInvalidArgumentException));


        $this->expectException(InvalidArgumentsException::class);
        $this->instance->update($stub, []);
    }

    public function testUpdate_notExists_RecipeNotFoundException()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('update')
            ->will($this->throwException(new Missing404Exception));


        $this->expectException(RecipeNotFoundException::class);
        $this->instance->update($stub, []);
    }

    public function testUpdate_Ok_BooleanTrue()
    {
        $stub = $this->createMock(Client::class);

        $deleteResult = true;
        $stub
            ->method('update')
            ->willReturn($indexResult);


        $result = $this->instance->update($stub, ['id' => 'new recipe', 'title' => 'new title']);
        $this->assertEquals($indexResult, $result);
    }

    public function testMapParamsToDoc_Array()
    {

        $params = [
            'id' => 'id',
            'title' => 'sometitle'
        ];

        $result = $this->invokeMethod($this->instance, 'mapParamsToDoc', array($params));

        unset($params['id']);
        $this->assertEquals($result, $params);
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
