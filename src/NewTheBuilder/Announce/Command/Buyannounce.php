<?php

namespace NewTheBuilder\Announce\Command;

use Form\CustomForm;
use NewTheBuilder\Announce\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Buyannounce extends Command {

    private static array $buyannounce;

    public function __construct() {
        parent::__construct("buyannounce", "allows you to buy an ad", "/buyannounce", ["buyad"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);

        if (!$sender instanceof Player) {

            $sender->sendMessage($config->get("Console_Command"));
            return true;
        }

        if (!Main::getInstance()->getServer()->getPluginManager()->getPlugin("EconomyAPI") or Main::getInstance()->getServer()->getPluginManager()->getPlugin("BedrockEconomy")) {
            if (Server::getInstance()->getLanguage() == "fra") {
                $sender->sendMessage("§cVous n'avez aucun plugin d'économie");
            } else {
                $sender->sendMessage("§cYou don't have Economy plugin !");
            }
            return true;
        }

        $this->FormMenu($sender);

        return true;
    }

    public function FormMenu(Player $sender): bool {
        $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);
        $form = new CustomForm(function (Player $sender, $data) {

            if ($data === null) {
                return true;
            }

            $config = new Config(Main::getInstance()->getDataFolder() . "Config.yml", Config::YAML);

            if (Main::getInstance()->getServer()->getPluginManager()->getPlugin("EconomyAPI")) {
                $economy = Main::getInstance()->getServer()->getPluginManager()->getPlugin("EconomyAPI");
                if ($economy->myMoney($sender) >= $config->get("announce_price")) {
                    $economy->reduceMoney($sender->getName(), $config->get("announce_price"));
                    Server::getInstance()->broadcastMessage(str_replace(["{PLAYER}", "{ANNOUNCE}"], [$sender->getName(), $data[1]], $config->get("Player_announce_prefix")));
                } else {
                    $sender->sendMessage($config->get("not_money"));
                    return true;
                }
            } elseif (Main::getInstance()->getServer()->getPluginManager()->getPlugin("BedrockEconomy")) {
                $economy = Main::getInstance()->getServer()->getPluginManager()->getPlugin("BedrockEconomy");
            }

        });

        $form->setTitle($config->get("title"));
        $form->addLabel($config->get("content"));
        $form->addInput($config->get("InPut"));
        $sender->sendForm($form);
        return true;
    }

}