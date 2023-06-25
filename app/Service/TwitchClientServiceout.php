<?php

// declare(strict_types=1);
// #app/Service/TwitchChatClient.php

// namespace App\Service;

// use App\Enum\InternetStatusTypes;

// class TwitchClient
// {
//     protected $socket;
//     protected $nick;
//     protected $oauth;

//     public static string $host = "irc.chat.twitch.tv";
//     public static int $port = 6667;

//     public function __construct($nick, $oauth)
//     {
//         $this->nick = $nick;
//         $this->oauth = $oauth;
//     }


//     public function connect(): ?InternetStatusTypes
//     {

//         if( ! $this->check_internet_connection()) {
//             return InternetStatusTypes::INTERNET_CONNECTION_FAILED;
//         }
//         if ( ! extension_loaded('sockets')) {
//             //? Socket Extention of php.ini is NOT enable
//         } else {
//             //? Socket Extention of php.ini is enable
//         }

//         // socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
//         // socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));

//         echo "\n Connecting ... \n";
//         $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//         if (false === socket_connect($this->socket, self::$host, self::$port)) {
//             return InternetStatusTypes::INTERNET_CONNECTION_TIMEOUT;
//         }
//         echo "\nConnected.\n";

//         $this->authenticate();
//         $this->setNick();
//         $this->joinChannel($this->nick);
//     }

//     public function authenticate(): void
//     {
//         $this->send(sprintf("PASS %s", $this->oauth));
//     }

//     public function setNick(): void
//     {
//         $this->send(sprintf("NICK %s", $this->nick));
//     }

//     public function joinChannel($channel): void
//     {
//         $this->send(sprintf("JOIN #%s", $channel));
//     }

//     public function getLastError()
//     {
//         return socket_last_error($this->socket);
//     }

//     public function isConnected()
//     {
//         return null !== $this->socket;
//     }

//     public function read($size = 256)
//     {
//         if ( ! $this->isConnected()) {
//             return null;
//         }

//         return socket_read($this->socket, $size);
//     }

//     public function send($message)
//     {
//         if ( ! $this->isConnected()) {
//             return null;
//         }

//         return socket_write($this->socket, $message."\n");
//     }

//     public function close(): void
//     {
//         socket_close($this->socket);
//     }

//     public function check_internet_connection(): bool
//     {
//         $connected = @fsockopen("www.google.com", 80);
//         //website, port  (try 80 or 443)
//         if ($connected) {
//             $is_conn = true; //action when connected
//             fclose($connected);
//         } else {
//             $is_conn = false; //action in connection failure
//         }
//         return $is_conn;
//     }
// }
