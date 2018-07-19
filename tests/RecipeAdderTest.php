<?php
require_once __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use Elasticsearch\Client;
use App\Recipes\RecipeAdder;

use Elasticsearch\Common\Exceptions\InvalidArgumentException as ElasticInvalidArgumentException;
use App\Recipes\RecipeExistsException;
use App\Recipes\InvalidArgumentsException;

class RecipeAdderTests extends TestCase
{
    private $instance;

    protected function setUp()
    {
        $this->instance = new RecipeAdder();
    }

    protected function tearDown()
    {
        $this->instance = NULL;
    }

    public function testAdd_recipeExists_RecipeExistsException()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('exists')
            ->willReturn(true);


        $this->expectException(RecipeExistsException::class);
        $this->instance->add($stub, []);
    }

    public function testAdd_invalidArguments_InvalidArgumentsException()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('exists')
            ->willReturn(false);

        $stub
            ->method('index')
            ->will($this->throwException(new ElasticInvalidArgumentException));


        $this->expectException(InvalidArgumentsException::class);
        $this->instance->add($stub, []);
    }

    public function testAdd_Ok_BooleanTrue()
    {
        $stub = $this->createMock(Client::class);
        $stub
            ->method('exists')
            ->willReturn(false);

        $indexResult = true;
        $stub
            ->method('index')
            ->willReturn($indexResult);


        $result = $this->instance->add($stub, ['title' => 'new recipe']);
        $this->assertEquals($indexResult, $result);
    }
}
