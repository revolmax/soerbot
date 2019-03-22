<?php
namespace Tests\Commands;

use Tests\TestCase;
use React\Promise\Promise;
use ArrayObject;

class CommandTest extends TestCase
{

    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Devs/Devs.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testBasics()
    {
       $this->assertEquals($this->command->name, 'devs');
       $this->assertEquals($this->command->description, 'Команда devs');
       $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testArguments()
    {
       $this->assertEquals(sizeof($this->command->args), 1);
       $this->assertArrayHasKey('key', $this->command->args[0]);
       $this->assertArrayHasKey('label', $this->command->args[0]);
       $this->assertArrayHasKey('prompt', $this->command->args[0]);
       $this->assertArrayHasKey('type', $this->command->args[0]);
       $this->assertEquals($this->command->args[0]['key'], 'topic');
       $this->assertEquals($this->command->args[0]['label'], 'topic');
       $this->assertEquals($this->command->args[0]['prompt'], 'Привет! Devs-твой помощник. Укажите топик: how-to-start, about, beginner, topic1, topicN');
       $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    public function testSimpleResponseToTheDiscord(): void
    {

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () { });

        $commandMessage->expects($this->once())->method('say')->with('devs [how-to-start|about|beginner|list|topic3|topicN]')->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => '']), false);
    }

    public function testDevsAboutArgument(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $rulesContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Devs/store/about.topic.md');
        $commandMessage->expects($this->once())->method('say')->with($rulesContent)->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'about']), false);
    }
    public function testDevsBeginnerArgument(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $channelContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Devs/store/beginner.topic.md');
        $commandMessage->expects($this->once())->method('say')->with($channelContent)->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'beginner']), false);
    }
    public function testDevsListArgument(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $channelContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Devs/store/list.topic.md');
        $commandMessage->expects($this->once())->method('say')->with($channelContent)->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'list']), false);
    }
    public function __sleep()
    {
        $this->command = null;
    }
}