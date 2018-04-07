<?php

namespace CommentApp;

use CommentApp\Models\User;
use CommentApp\Repositories\UserRepository;
/**
 * Keeps all about request or gives access for it
 * 
 * @property array $cookies list of cookies
 * @property array $requestVars list of vars from request
 * @property array $serverVars list of server vars
 * @property User  $user web user
 */
class Request {
    
    const USER_COOKIE_HASH_KEY = 'user_comment_hash';
    
    private $cookies;
    private $requestVars;
    private $serverVars;
    
    private $user;
    
    /**
     * @param array $cookies
     */
    public function setCookieVars($cookies)
    {
        $this->cookies = $cookies;
    }
    
    /**
     * @param array $serverVars
     */
    public function setServerVars($serverVars)
    {
        $this->serverVars = $serverVars;
    }

    /**
     * @param array $request
     */
    public function setRequestVars($request)
    {
        $this->requestVars = $request;
    }
    
    /**
     * @param string $hash from cookie
     */
    public function initGuestUser(string $hash = '')
    {
        $user = new User;
        $user->setAttribute('user_hash', $hash);
        $this->setUser($user);
    }
    
    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Get if current user logged in
     *
     * @return bool
     */
    public function getIsUserGuest()
    {
        return !$this->user || !$this->user->getIsExists();
    }
    
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @return bool
     */
    public function hasUserCookieHash()
    {
        return array_key_exists(self::USER_COOKIE_HASH_KEY, $this->cookies);
    }
    
    /**
     * Try to get users hash from cookie
     * @return string|false
     */
    public function getUserCookieHash()
    {
        return $this->cookies[self::USER_COOKIE_HASH_KEY] ?? false;
    }
    
    /**
     * @return bool
     */
    public function cleanUserCookieHash()
    {
        $cookieKey = self::USER_COOKIE_HASH_KEY;
        
        return setcookie($cookieKey, false, -1);
    }
    
    /**
     * Create new cookie
     *
     * @return string|bool
     */
    public function initUserCookieHash()
    {
        $cookieKey = self::USER_COOKIE_HASH_KEY;
        $cookieHash = uniqid(uniqid());
        $cookieExpire = time() + 3600 * 24;
        
        return setcookie($cookieKey, $cookieHash, $cookieExpire) 
            ? $cookieHash 
            : false;
    }
    
    /**
     * @param string $name
     * @return string|false
     */
    public function getParam(string $name)
    {
        return $this->requestVars[$name] ?? false;
    }
    
    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->serverVars['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * @param UserRepository $repo
     * @return boolean
     */
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
    
    /**
     * @param array $cookies
     * @param array $requestVars
     * @param array $serverVars
     * @return self
     */
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
    
    /**
     * 
     * @param bool $authOnly
     * @return bool
     */
    public function matchUser(bool $authOnly)
    {
        return $authOnly === !$this->getIsUserGuest();
    }
}
