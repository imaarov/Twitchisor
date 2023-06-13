<?php
#app/Service/TwitchChatClient.php


namespace App\Service;

use App\Enum\InternetStatusTypes;
use App\Service\BaseService;
use Minicli\Command\CommandController;

class TwitchClientService
{
    protected $socket;
    protected $nick;
    protected $oauth;

    static $host = "irc.twitch.tv";
    static $port = "6667";

    public function __construct($nick, $oauth)
    {
        $this->nick = $nick;
        $this->oauth = $oauth;
    }


    public function connect(): ?InternetStatusTypes
    {
        if(! $this->check_internet_connection()) {
            return InternetStatusTypes::INTERNET_CONNECTION_FAILED;
        }
        if (!extension_loaded('sockets')) {
            //? Socket Extention of php.ini is NOT enable
        }else {
            //? Socket Extention of php.ini is enable
        }

        // socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
        // socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));


        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_connect($this->socket, self::$host, self::$port) === FALSE) {
            return InternetStatusTypes::INTERNET_CONNECTION_TIMEOUT;
        }

        $this->authenticate();
        $this->setNick();
        $this->joinChannel($this->nick);
    }

    public function authenticate()
    {
        $this->send(sprintf("PASS %s", $this->oauth));
    }

    public function setNick()
    {
        $this->send(sprintf("NICK %s", $this->nick));
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

    public function check_internet_connection(): bool
    {
        $connected = @fsockopen("www.google.com", 80);
        //website, port  (try 80 or 443)
        if ($connected) {
            $is_conn = true; //action when connected
            fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }
}
