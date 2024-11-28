<?php

namespace KnosTx\PlayerRename;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase implements Listener {

    /** @var array Stores renamed players as [originalName => newName]. */
    private array $renamedPlayers = [];

    /** @var string Path to the file where renamed player data is stored. */
    private string $dataFile;

    public function onEnable(): void {
        $this->dataFile = $this->getDataFolder() . "renamedPlayers.dat";

        if (!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }

        $this->loadData();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDisable(): void {
        $this->saveData();
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (isset($this->renamedPlayers[$name])) {
            $newName = $this->renamedPlayers[$name];
            $player->setDisplayName($newName);
            $player->setNameTag($newName);
            $player->sendMessage(TF::YELLOW . "Your name has been updated to " . TF::AQUA . $newName);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch (strtolower($command->getName())) {
            case "rename":
                if (!$sender instanceof Player) {
                    $sender->sendMessage(TF::RED . "This command can only be used by a player.");
                    return true;
                }

                if (!$sender->hasPermission("playerrename.rename")) {
                    $sender->sendMessage(TF::RED . "You do not have permission to use this command.");
                    return true;
                }

                if (count($args) < 1) {
                    $sender->sendMessage(TF::RED . "Usage: /rename <newName>");
                    return true;
                }

                $newName = array_shift($args);
                $oldName = $sender->getName();

                $this->renamedPlayers[$oldName] = $newName;
                $sender->setDisplayName($newName);
                $sender->setNameTag($newName);
                $sender->sendMessage(TF::GREEN . "Your name has been updated to " . TF::AQUA . $newName);

                $this->saveData();

                return true;

            case "listrenamed":
                if (!$sender->hasPermission("playerrename.list")) {
                    $sender->sendMessage(TF::RED . "You do not have permission to use this command.");
                    return true;
                }

                if (empty($this->renamedPlayers)) {
                    $sender->sendMessage(TF::YELLOW . "No players have been renamed yet.");
                    return true;
                }

                $sender->sendMessage(TF::GREEN . "Renamed Players:");
                foreach ($this->renamedPlayers as $original => $renamed) {
                    $sender->sendMessage(TF::AQUA . $original . " -> " . TF::YELLOW . $renamed);
                }

                return true;

            default:
                return false;
        }
    }

    private function saveData(): void {
        file_put_contents($this->dataFile, serialize($this->renamedPlayers));
    }

    private function loadData(): void {
        if (file_exists($this->dataFile)) {
            $data = file_get_contents($this->dataFile);
            $this->renamedPlayers = unserialize($data) ?: [];
        }
    }
}