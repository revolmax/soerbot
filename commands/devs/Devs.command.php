<?php
return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
    {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, array(
                'name' => 'devs', // Give command name
                'aliases' => array(),
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'Команда devs', // Fill the description
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true,
                'args' => array( // If you need some variables you should either fill this section or remove it
                    array(
                        'key' => 'topic',
                        'label' => 'topic',
                        'prompt' => 'Привет! Devs-твой помощник. Укажите топик: how-to-start, about, beginner, list, topicN',
                        'type' => 'string'
                    )
                )
            ));
        }
        
       
        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            $basePath = dirname(__FILE__);
        switch ($args['topic']) {
            
            case 'about':
                $devsTopic = \file_get_contents($basePath . '/store/about.topic.md');
                return $message->direct($devsTopic);
                
                break;
                
            case 'beginner':
                $devsTopic = \file_get_contents($basePath . '/store/beginner.topic.md');
                return $message->direct($devsTopic);
                
                break;
                case 'list':
                
                $devsTopic = \file_get_contents($basePath . '/store/list.topic.md');
                return $message->direct($devsTopic);
                
                
                break;    
        }
        return  $message->reply('Пожалуйста укажите один из топиков: [ how-to-start | about | beginner | list | topic2 | topicN ]');
        }
    });
};