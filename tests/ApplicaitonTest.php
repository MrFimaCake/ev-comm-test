<?php

use PHPUnit\Framework\TestCase;

use CommentApp\Application;
use CommentApp\Config;
use CommentApp\Request;
use CommentApp\Response;
use CommentApp\EventManager;
use CommentApp\Event;

use CommentApp\Actions\LoginFormAction;
use CommentApp\Actions\CommentListAction;

use CommentApp\Models;
use CommentApp\Repositories;


final class ApplicaitonTest extends TestCase{
    
    private function createApp()
    {
        $rootPath = dirname(__DIR__);
        $config = new Config($rootPath);
        return new Application($config);
    }
    
    private function getFakeVars()
    {
        $requestVars = [
            'param'    => 'pararam',
        ];
        $serverVars = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/not-existing-url'
        ];
        
        return [[], $requestVars, $serverVars];
    }
        
    
    public function testCanBeCreatedWithValidRootPath()
    {
        $app = $this->createApp();
        
        $this->assertInstanceOf(
            Application::class,
            $app
        );
    }
    
    public function testAppHasResponse()
    {
        $app = $this->createApp();
        
        $fakeVars = $this->getFakeVars();
        $app->createResponse(Request::create(...$fakeVars));
        
        $this->assertInstanceOf(
            Response::class,
            $app->getResponse()
        );
    }
    
    public function testAppHasRequest()
    {
        $app = $this->createApp();
        
        $fakeVars = $this->getFakeVars();
        $app->createResponse(Request::create(...$fakeVars));
        
        $this->assertInstanceOf(
            Request::class,
            $app->getRequest()
        );
    }
    
    public function testEventManagerInstanceWasCreated()
    {
        $this->createApp();
        
        $this->assertInstanceOf(
            EventManager::class,
            EventManager::getInstance()
        );
    }
    
    public function testLoginPageOnUnauth()
    {
        $app = $this->createApp();
        
        $fakeVars = $this->getFakeVars();
        $fakeVars[2]['REQUEST_URI'] = '/';
        
        $app->createResponse(Request::create(...$fakeVars));
        
        $this->assertEquals(
            LoginFormAction::class,
            $app->getAction()
        );
    }
    
    public function testCommentListPageOnUnauth()
    {
        $app = $this->createApp();
        EventManager::attachObserver(Request::class, new class() {
                   
            public function onRequestInit(Event $event)
            {
                $event->getApp()->getRequest()->getUser()->setIsExists(true);
            }
        });
        
        $fakeVars = $this->getFakeVars();
        $fakeVars[2]['REQUEST_URI'] = '/';
        
        $app->createResponse(Request::create(...$fakeVars));

        $this->assertEquals(
            CommentListAction::class,
            $app->getAction()
        );
    }
    
    public function testAppCreatesRightRepos()
    {
        $app = $this->createApp();
        
        $this->assertInstanceOf(
            Repositories\UserRepository::class,
            $app->getRepo(Models\User::class)
        );
        
        $this->assertInstanceOf(
            Repositories\ObserverRepository::class,
            $app->getRepo('observer')
        );
        
        $this->assertInstanceOf(
            Repositories\CommentSourceRepository::class,
            $app->getRepo(Models\CommentSource::class)
        );
        
        $this->assertInstanceOf(
            Repositories\CommentRepository::class,
            $app->getRepo(Models\Comment::class)
        );
    }
    
}
