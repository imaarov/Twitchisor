<?php
#app/Service/TwitchChatClient.php


namespace App\Service;

use App\Service\BaseService;

class TwitchClientService extends BaseService {
    public function __construct(
        protected $user,
        protected $oauth
    )
    {}

    protected $socket;
    public static $HOST = "irc.chat.twitch.tv";
    public static $PORT = "6667";


    public function connect()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_connect($this->socket, self::$HOST, self::$PORT) === FALSE) {
            return null;
        }

        $this->authenticate();
        $this->setUser();
        $this->joinChannel($this->user);
    }

    public function authenticate()
    {
        $this->send(sprintf("PASS %s", $this->oauth));
    }

    public function setUser()
    {
        $this->send(sprintf("User %s", $this->user));
    }

    public function joinChannel($channel)
    {
        $this->send(sprintf("JOIN #%s", $channel));
    }

    public function getLastError()
    {
        return socket_last_error($this->socket);
    }

    public function isConnected()
    {
        return !is_null($this->socket);
    }

    public function read($size = 256)
    {
        if (!$this->isConnected()) {
            return null;
        }

        return socket_read($this->socket, $size);
    }

    public function send($message)
    {
        if (!$this->isConnected()) {
            return null;
        }

        return socket_write($this->socket, $message . "\n");
    }

    public function close()
    {
        socket_close($this->socket);
    }
}
