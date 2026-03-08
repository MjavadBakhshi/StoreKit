<?php

namespace Domain\Shared\Actions;

use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;

class SessionAction
{
    protected $session;
    protected $request;

    public function __construct(SessionManager $session, Request $request)
    {
        $this->session = $session;
        $this->request = $request;
    }

     public function __call($method, $args) {
        // First, check if the method is defined on this class
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }

        // If not, call it on the session object
        return call_user_func_array([$this->session, $method], $args);
    }
    /**
     * Set a session value with domain-prefixed key.
     */
    public function set(string $key, mixed $value): void
    {
        $this->session->put($this->getFullKey($key), $value);
    }

    /**
     * Get a session value with domain-prefixed key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->session->get($this->getFullKey($key), $default);
    }

    /**
     * Forget a session key with domain-prefixed key.
     */
    public function forget(string $key): void
    {
        $this->session->forget($this->getFullKey($key));
    }

    /**
     * Get all session data with domain-prefixed keys.
     */
    public function all(): array
    {
        return $this->session->all();
    }

    public function has(string $key) :bool
    {
        return $this->session->has($this->getFullKey($key));
    }


    private function getFullKey($key) :string
    {
        $domain = $this->request->getHost();
        return "store:{$domain}:{$key}";
    }
}
