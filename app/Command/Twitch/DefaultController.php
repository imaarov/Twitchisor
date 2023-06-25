<?php

declare(strict_types=1);
#label app/Command/Twitch/DefaultController.php

namespace App\Command\Twitch;

use App\Enum\ConnectionStatusTypes;
use App\Service\EnvService as Env;
use App\Service\TwitchClientService as TwitchClient;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $this->info("Starting farming...");

        $app = $this->getApp();

        $user_name  = Env::get('USER_NAME');
        $user_oauth = Env::get('USER_OAUTH');
        $this->info("Connecting with:\nusername:{$user_name}\noauth:{$user_oauth}");
        $client = new TwitchClient(
            host: "irc.chat.twitch.tv",
            port: 6667,
            username: $user_name,
            oauth: $user_oauth
        );
        $res = $client->connect();

        switch ($res) {
            case ConnectionStatusTypes::INTERNET_CONNECTION_FAILED:
                $this->error("Internet Failed Connected");
                return;

            case ConnectionStatusTypes::INTERNET_CONNECTION_TIMEOUT:
                $this->error("Internet Timeout Connected");
                return;
            case ConnectionStatusTypes::SOCKET_EXTENSION_NOT_LOADED:
                $this->error("Socket Extension is not loaded in php.ini");
                return;
            case ConnectionStatusTypes::SOCKET_CONNECTION_FAILED:
                $this->error( socket_strerror($client->getLastError()));
                return;
        }

        if ( ! $client->isConnected()) {
            $this->error("cant connect to the server.");
            return;
        }

        $this->info("Connected.\n");

        while (true) {
            $content = $client->read(512);
            $this->out($content."\n", "dim");
            sleep(5);
        }
    }
}
