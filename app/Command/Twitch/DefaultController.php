<?php
#label app/Command/Twitch/DefaultController.php

namespace App\Command\Twitch;

use App\Service\EnvService as Env;
use App\Service\TwitchClientService as TwitchClient;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $this->info("Starting farming...");

        $app = $this->getApp();

        // $user_name = $app->config->user_name;
        // $user_oauth = $app->config->user_oauth;

        // if (!isset($app->config->user_name) || !isset($app->config->user_oauth)) {
        //     $this->error("Missing 'user_name' and/or 'user_oauth' config settings.");
        //     return;
        // }


        $user_name  = ENV::value('USER_NAME');
        $user_oauth = Env::value('USER_OAUTH');

        $client = new TwitchClient($user_name, $user_oauth);
        $client->connect();

        if (!$client->isConnected()) {
            $this->error("cant connect to the server.");
            return;
        }

        $this->info("Connected.\n");

        while (true) {
            $content = $client->read(512);
            $this->out($content . "\n", "dim");
            sleep(5);
        }
    }
}
