<?php

  class Cookie {

    public $_userId = null;

    public static function new(string $cookie_name, string $identify, int $expire, bool $test = null) {
      $prefix = '%' . $identify . '%s\r\n';
      $unique = uniqid($prefix, true);

      if(isset($test)){
        return $unique;
      }

      setcookie($cookie_name, $unique, time() + $expire);
    }

    public static function delete(string $cookie_name): void {
      setcookie($cookie_name, '', time() - 3600, '/');
      unset($_COOKIE[$cookie_name]);
    }

    private function tearOutId(): void {
      $extractCookie =  explode('%', $_COOKIE['user']);
      $this->_userId = $extractCookie[1];
    }

    public function getId(): string {
      $this->tearOutId();
      return $this->_userId;
    }

  }



?>
