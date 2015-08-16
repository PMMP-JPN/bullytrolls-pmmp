<?php
namespace bullytrolls;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\IPlayer;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

class bullytrolls extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this,$this);//プラグインをpmmpに登録します。
			}

    public function onDisable(){//終了時の動作を書きます。
    }
				
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {//コマンドを打たれた時のイベント
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "bullytrolls":
    			if(isset($args[0])){
						if($sender->hasPermission("bullytrolls.command")){//権限のチェック
							foreach($this->getServer()->getOnlinePlayers() as $p){///指定したプレイヤーがいるか
								$k2name = $p->getName();
								if($k2name == $args[0]){
									$ok = 1;
								}
							}
						if(!isset($ok)){
							$sender->sendMessage(TextFormat::RED. " [Bullytrolls] そんな人はいません。");
							break;
						}
						$playerk = $this->getServer()->getPlayer($args[0]);
						$kname =  $playerk->getName();
						$name = $sender->getName();
						if(!isset($this->izimeta[$kname])){
							$sender->sendMessage(TextFormat::RED." [Bullytrolls] ".$kname."をいじめました。");
							$this->getLogger()->info(TextFormat::RED." [Bullytrolls] ".$name."が".$kname."をいじめました。");
							///////
							$effect = Effect::getEffect(10); //エフェクトID
							$effect->setVisible(true); //パーティクル
							$effect->setAmplifier(100);//レベル
							$effect->setDuration(10000*20); //20x秒数で時間
							$playerk->addEffect($effect) ;//エフェクトの追加
							//////
							$effect = Effect::getEffect(19); //エフェクトID
							$effect->setVisible(true); //パーティクル
							$effect->setAmplifier(100);//レベル
							$effect->setDuration(10000*20); //20x秒数で時間
							$playerk->addEffect($effect) ;//エフェクトの追加
							//////
							$effect = Effect::getEffect(9); //エフェクトID
							$effect->setVisible(true); //パーティクル
							$effect->setAmplifier(100);//レベル
							$effect->setDuration(10000*20); //20x秒数で時間
							$playerk->addEffect($effect) ;//エフェクトの追加
							//////
							$this->izimeta[$kname] = $name;
							break;
						}else{
							unset($this->izimeta[$kname]);
							foreach($playerk->getEffects() as $effect){
								$playerk->removeEffect($effect->getId());//エフェクトの除去
							}
							$sender->sendMessage(TextFormat::RED." [Bullytrolls] ".$kname."を許しました。");
							$this->getLogger()->info(TextFormat::RED." [Bullytrolls] ".$name."が".$kname."を許してあげました。");						break;
							}
    					}else{
    						$sender->sendMessage("権限がありません。");
    						break;
	   						}
						}else{
							$sender->sendMessage("Usege: /bt プレイヤー名 ;プレイヤーをいじめます。");
							break;
						}
					}
	
				}


		public function onPlayernigetatoki(PlayerQuitEvent $event){//プレイヤーが鯖から抜けた時
			$player = $event->getPlayer();
			$name = $player->getName();
			if(isset($this->izimeta[$name])){
				$reason = " [Bullytrolls] ".$this->izimeta[$name]."が".$name."をbanipしてあげました。";
				$ip = $player->getAddress();
				$this->getLogger()->info(TextFormat::RED." [Bullytrolls] ".$this->izimeta[$name]."が".$name."をbanipしてあげました。");
				$this->getServer()->broadcastMessage(" [Bullytrolls] ".$this->izimeta[$name]."が".$name."をbanipしてあげました。".$ip);
				$player->getServer()->getIPBans()->addBan($ip,$this->izimeta[$name], null,$reason);//ip-ban
				$player->getServer()->getNameBans()->addBan($name,$this->izimeta[$name], null,$reason);//ban
			}
	
		}
   	
	public function onBlockPlace(BlockPlaceEvent $event){//ブロックが置かれた時のイベント
		$player = $event->getPlayer();
		$name = $player->getName();
   		if(isset($this->izimeta[$name])){
			$event->setCancelled();	//中止させます
		}
	}
	
	
	public function onBlockBreak(BlockBreakEvent $event){//ブロックが壊された時のイベント
		$player = $event->getPlayer();
		$name = $player->getName();
   		if(isset($this->izimeta[$name])){
		$event->setCancelled();//中止させます
		}
	}
		
}