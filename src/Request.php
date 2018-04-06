<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CommentApp;

use CommentApp\Models\User;
use CommentApp\Repositories\UserRepository;
/**
 * Description of Request
 *
 * @author mrcake
 */
class Request {
    
    const USER_COOKIE_HASH_KEY = 'user_comment_hash';
    //put your code here
    
    private $cookies;
    private $requestVars;
    private $serverVars;
    
    private $user;
    
    
    public function setCookieVars($cookies)
    {
        $this->cookies = $cookies;
    }
    
    public function setServerVars($serverVars)
    {
        $this->serverVars = $serverVars;
    }

    public function setRequestVars($request)
    {
        $this->requestVars = $request;
    }
    
    public function initGuestUser(string $hash = '')
    {
        $user = new User;
        $user->setAttribute('user_hash', $hash);
        $this->setUser($user);
    }
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function getIsUserGuest()
    {
        return !$this->user || !$this->user->getIsExists();
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function hasUserCookieHash()
    {
        return array_key_exists(self::USER_COOKIE_HASH_KEY, $this->cookies);
    }
    
    public function getUserCookieHash()
    {
        return $this->cookies[self::USER_COOKIE_HASH_KEY] ?? false;
    }
    
    public function cleanUserCookieHash()
    {
        $cookieKey = self::USER_COOKIE_HASH_KEY;
        
        return setcookie($cookieKey, false, -1);
    }
    
    public function initUserCookieHash()
    {
        $cookieKey = self::USER_COOKIE_HASH_KEY;
        $cookieHash = uniqid(uniqid());
        $cookieExpire = time() + 3600 * 24;
        
        return setcookie($cookieKey, $cookieHash, $cookieExpire) 
            ? $cookieHash 
            : false;
    }
    
    public function getParam(string $name)
    {
        return $this->requestVars[$name] ?? false;
    }
    
    public function isPost()
    {
        return $this->serverVars['REQUEST_METHOD'] === 'POST';
    }
    
    public function createUserFromRequest(UserRepository $repo)
    {
        $requestParams = $this->requestVars;
        if (!isset($requestParams['CreateUser'])) {
            return false;
        }
        
        if (!isset($requestParams['CreateUser']['username'])) {
            return false;
        }
        
        if ($user = $repo->getByUsername($requestParams['CreateUser']['username'])) {
            $user->setAttribute('user_hash', $this->initUserCookieHash());
            $repo->save($user);
            return $user;
        } else {
            $user = User::fromArray($requestParams['CreateUser']);
            $user->setAttribute('user_hash', $this->initUserCookieHash());
            return $repo->save($user) ?: false; 
        }
    }
    
    public static function create(
        array $cookies = [], 
        array $requestVars = [],
        array $serverVars = []
    ) {
        $self = new self;
        $self->setCookieVars(empty($cookies) ? $_COOKIE : $cookies);
        $self->setRequestVars(empty($requestVars) ? $_REQUEST : $requestVars);
        $self->setServerVars(empty($serverVars) ? $_SERVER : $serverVars);
        
        return $self;
    }
    
    /**
     * Check if current method is allowed by given method(s)
     * 
     * @param type $method
     * @return type
     */
    public function matchMethod($method)
    {
        $methods = (array)$method;
        return in_array($this->serverVars['REQUEST_METHOD'], $methods);
    }
    
    /**
     * Check if uri matches
     *
     * @param type $url
     * @return type
     */
    public function matchUrl($url)
    {
        return strpos($url, $this->serverVars['REQUEST_URI']) === 0;
    }
    
    /**
     * Match params
     * 
     * @param array $ruleParams
     * @return boolean
     */
    public function matchParams(array $ruleParams)
    {
        $requestParams = $this->requestVars;
        foreach ($ruleParams as $key => $ruleParam) {
            $isRequired = $ruleParam['required'] ?? false;
            if ($isRequired && !isset($requestParams[$key])) {
                return false;
            }
        }
        return true;
    }
    
    public function matchUser(bool $authOnly)
    {
        return $authOnly === !$this->getIsUserGuest();
    }
}
