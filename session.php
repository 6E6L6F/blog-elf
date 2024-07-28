<?php

class Session {
    private $started = false;

    public function __construct() {
        if (!isset($_SESSION)) {
            $this->start();
        }
    }

    public function start() {
        if (!$this->started) {
            session_start();
            $this->started = true;
        }
    }

    public function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    public function unSet(string $key) {
        unset($_SESSION[$key]);
    }

    public function destroy() {
        session_destroy();
        $this->started = false;
    }

    public function isLoggedIn(): bool {
        if ($this->hasExpired() === true) {
            $this->destroy();
            $this->started = false;
            return false;
        } elseif ($this->get('logged_in') === true){
            return true;

        } else {
            $this->started = false;
            return false;
        }

    }

    public function login(string $userName , int $userid) {
        $this->set('logged_in', true);
        $this->set('username', $userName);
        $this->set('userid', $userid);
        $this->set('login_time', date('Y-m-d H:i:s'));
    }

    public function logout() {
        $this->unset('logged_in');
        $this->unset('username');
    }

    public function setExpireTime(int $expireTime) {
        $this->set('expire_time', time() + $expireTime);
    }

    public function hasExpired(): bool {
        $expireTime = $this->get('expire_time');
        return $expireTime !== null && time() > $expireTime;
    }
}

?>