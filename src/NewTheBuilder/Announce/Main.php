<?php

namespace NewTheBuilder\Announce;

use JsonException;
use NewTheBuilder\Announce\Command\AnnounceCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private static Main $main;

    /**
     * @return void
     * @throws JsonException
     */
    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        self::$main = $this;
        $config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, [
            "Prefix" => "[§cANNOUNCE§f] ",
            "Color" => "§e"
        ]);
        $config->save();
        $this->getServer()->getCommandMap()->register("Announce", new AnnounceCommand());
    }

    public static function getInstance() : Main {
        return self::$main;
    }

}