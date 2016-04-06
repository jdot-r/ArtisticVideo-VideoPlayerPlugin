<?php
namespace AAText;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use AAText\CallbackTask;

class AAText extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->text = [];
        $this->tmp = [];
        $this->stmp = [];
        $path = $this->getServer()->getDataPath() . "/plugins/Art/ArtisticText/";
        @mkdir($this->getServer()->getDataPath() . "/plugins/Art/");
        @mkdir($this->getServer()->getDataPath() . "/plugins/Art/ArtisticText/");
        @mkdir($this->getServer()->getDataPath() . "/plugins/Art/ArtisticText/GetFiles/");
        $this->cfg = new Config($path . "Config.yml", Config::YAML);
        if (!$this->cfg->exists("视频刷新率(秒每次)")) {
            $this->cfg->set("视频刷新率(秒每次)", 0.05);
            $this->cfg->save();
        }
        if (!$this->cfg->exists("视频播放列表及坐标(如需关闭随便填不存在的txt文件即可)")) {
            $this->cfg->set("视频播放列表及坐标(如需关闭随便填不存在的txt文件即可)", array("x"=>0,"y"=> 0,"z"=> 0,"world"=>"world", "text"=>"233.txt","Height" =>61,"blocks"=>array(" "=>155)));
            $this->cfg->save();
        }
        $this->video = $this->cfg->get("视频播放列表及坐标(如需关闭随便填不存在的txt文件即可)");
        $this->tmpcfg = $this->cfg->get("视频刷新率(秒每次)") * 20;
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallBackTask([$this, "AddFloating"]), $this->tmpcfg);
        $this->gif();
    }


    public function AddFloating()
    {
        if (file_exists($this->getTextDir() . $this->video["text"])) {
            foreach ($this->Anma as $arg) {
                $id = $arg['id'];
                $argg = $arg['text'];
                $i = $argg['nowp'] + 1;
                if ($i <= $argg['sp']) {
                    $hu = explode("\n", $argg['arg'][$i]);
                    $co = strlen($hu[1]);
                    $line = count($hu);
                    for ($u = 0; $u < $line; $u++) {
                        for ($o = 0; $o < $co; $o++) {
                            $s = mb_substr($hu[$line - $u - 1], $o, 1);
                            if ($s == " ") {
                                $this->levelt->setBlock(new Position($this->video["x"] + $o, $this->video["y"] + $u, $this->video["z"], $this->levelt), $this->block[1], true, false);
                            } else {
                                $this->levelt->setBlock(new Position($this->video["x"] + $o, $this->video["y"] + $u, $this->video["z"], $this->levelt), $this->block[2], true, false);
                            }
                        }
                    }
                } else {
                    $argg['nowp'] = 0;
                    $i = 0;
                }
                @$this->Anma[$id]['text']['nowp'] = $i;
            }
        }
    }

    public function gif()
    {
        $iii = 0;
        $text = $this->getTextDir() . $this->video["text"];
        if (file_exists($this->getTextDir() . $this->video["text"])) {
            $this->levelt = $this->getserver()->getlevelbyname($this->video["world"]);
            $text = $this->ZPoss($text, $this->video["Height"]);
            $array = array(
                'id' => $iii,
                'rtext' => $text,
                'text' => $text
            );
            $this->Anma[$iii] = $array;
            $item = Item::get(155);
            $this->block[1] = $item->getblock();
            $item = Item::get(159, 15);
            $this->block[2] = $item->getblock();
        }
    }

    public function getTextDir()
    {
        return $this->getServer()->getDataPath() . "/plugins/Art/ArtisticText/GetFiles/";
    }

    public function ZPoss($text, $height)
    {
        $fopen = fopen($text, "r");
        $fopen1 = fopen($text, "r");
        $i = 0;
        while (!feof($fopen1)) {
            $i++;
            $c = fgets($fopen1);
        }
        $shouldfor = floor($i / $height) - 1;
        $i = 0;
        $arg = array();
        while ($i <= $shouldfor) {
            $i++;
            $ttt = "";
            $ii = 0;
            while ($ii < $height) {
                $ii++;
                $ttt .= fgets($fopen);
            }
            $arg[$i] = $ttt;
        }
        $array = array(
            'arg' => $arg,
            'nowp' => 0,
            'sp' => $shouldfor,
        );
        return $array;
    }
}

?>