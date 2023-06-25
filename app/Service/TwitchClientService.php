<?php

namespace App\Service;

use App\Enum\ConnectionStatusTypes;
use Minicli\Output\OutputHandler;

class TwitchClientService{

    private $socket;
    private $op;

    public function __construct(
        private string $host,
        private int $port,
        private string $username,
        private string $oauth,
    ){
        $this->op = new OutputHandler();
    }

    public function connect(): ?ConnectionStatusTypes
    {
        //? Checking for connecting
        if(!$this->check_internet_connection()) {
            return ConnectionStatusTypes::INTERNET_CONNECTION_FAILED;
        }
        if(!extension_loaded('sockets')) {
            return ConnectionStatusTypes::SOCKET_EXTENSION_NOT_LOADED;
        }

        $this->op->info("Start to Connecting ...");
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(! socket_connect($this->socket, $this->host, $this->port)) {
            return ConnectionStatusTypes::SOCKET_CONNECTION_FAILED;
        }
        $this->op->info("Connected");
        $this->connectViaOauth();
    }

    public function connectViaOauth(): void
    {
        $this->send(sprintf("PASS %s", $this->oauth));
        $this->send(sprintf("NICK %s", $this->username));
    }

    public function joinChannel(string $channel)
    {
        $this->send(sprintf("JOIN #%s", $channel));
    }



    public function isConnected(): bool
    {
        return $this->socket ? true : false;
    }

    public function send(string $msg)
    {
        if ($this->isConnected()) {
            return socket_write($this->socket, $msg . "\n");
        }
    }

    public function read(int $size = 256)
    {
        if ($this->isConnected()) {
            return socket_read($this->socket, $size);
        }
    }


    public function check_internet_connection(): bool
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            $is_conn = true; //action when connected
            fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    public function getLastError()
    {
        return socket_last_error($this->socket);
    }
}
