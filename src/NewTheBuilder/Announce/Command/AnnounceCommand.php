<?php

namespace NewTheBuilder\Announce\Command;

use NewTheBuilder\Announce\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;

class AnnounceCommand extends Command {

    public function __construct() {
        parent::__construct("announce", "Allows you to make an announcement on the server", "/announce <message>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender->hasPermission("announce.use")){
            $sender->sendMessage("§cYou don't ave permission to use this");
            return true;
        }
        if (count($args) === 0) {
            $sender->sendMessage("§cPlease post an ad");
            return true;
        }
        $message = implode(" ", $args);
        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);
        Server::getInstance()->broadcastMessage($config->get("Prefix") . $config->get("Color") . $message);
        return true;
    }

}