<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 09/01/17
 * Time: 15:14
 */

const ACTIVITY_TYPE_CHURCH = 3;
const ACTIVITY_TYPE_GRAVEYARD = 4;

function getAssetLink($assetLink) {
    /**
     * @var \Phalcon\Config $config
     */
    global $config;

    return $config->get('cdn') . $assetLink;
}

function headLink($file) {
    return '<link rel="stylesheet" type="text/css" href="'.getAssetLink('css/'.$file).'">'.PHP_EOL;
}

function headJs($file) {
    return '<script type="text/javascript" src="'.getAssetLink('js/'.$file).'"></script>'.PHP_EOL;
}

function getUrl($link) {
    /**
     * @var \Phalcon\Config $config
     */
    global $config;

    return $config->get('baseUrl') . $link;
}

function getNameChangeCost($count, $exp) {
    return pow(2, $count) * getLevel($exp) * 4800;
}

function getClanHideoutCost($stufe) {
    if($stufe == 1) {return 3;} elseif($stufe == 2) {return 296;}
    elseif($stufe == 3) {return 4130;} elseif($stufe == 4) {return 26796;}
    elseif($stufe == 5) {return 114283;} elseif($stufe == 6) {return 375818;}
    elseif($stufe == 7) {return 1018158;} elseif($stufe == 8) {return 2425286;}
    elseif($stufe == 9) {return 5215001;} elseif($stufe == 10) {return 10343751;}
    elseif($stufe == 11) {return 19218989;} elseif($stufe == 12) {return 33834222;}
    elseif($stufe == 13) {return 56925897;} elseif($stufe == 14) {return 92153181;}
    elseif($stufe == 15) {return 144301645;} elseif($stufe == 16) {return 219511858;}
    elseif($stufe == 17) {return 325533800;} else {return 472008025;}
}

function highscoreShowToName($val) {
    if($val == 'level') {return 'Level';} elseif($val == 'raid') {return 'Booty';}
    elseif($val == 'fightvalue') {return 'Battle value';} elseif($val == 'fights') {return 'Fights';}
    elseif($val == 'fight1') {return 'Victories';} elseif($val == 'fight2') {return 'Defeats';}
    elseif($val == 'fight0') {return 'Draw';} elseif($val == 'lanter') {return 'Lanterns';}
    elseif($val == 'goldwin') {return 'looted Gold';} elseif($val == 'goldlost') {return 'lost Gold';}
    elseif($val == 'hits1') {return 'Damage caused';} elseif($val == 'hits2') {return 'Hit points lost';}
    elseif($val == 'trophypoints') {return 'Trophy points';}
    elseif($val == 'castle') {return 'Level';}
    elseif($val == 'warraid') {return 'War Booty';}
    elseif($val == 'members') {return 'Members';}
    elseif($val == 'ppm') {return 'average Booty';}
    elseif($val == 'seals') {return 'Seals';}
    elseif($val == 'gatesopen') {return 'Gate openings';}
    elseif($val == 'lastgateopen') {return 'last gate opening';}
    else {return 'Henchmen power';}
}

function getHuntNameFromId($huntId) {
    if($huntId == 1) {
        return 'Farm';
    } elseif($huntId == 2) {
        return 'Village';
    } elseif($huntId == 2) {
        return 'Small Town';
    } elseif($huntId == 2) {
        return 'City';
    } else {
        return 'Metropolis';
    }
}

function getSkillCost($skillLevel) {
    return floor(pow($skillLevel - 4, 2.4));
}

function dd() {
    array_map(function($x) { var_dump($x); }, func_get_args()); die;
}

function getHideoutCost($type, $level) {
    if($type=='domi') {return pow(4, $level);}
    elseif($type=='wall') {return pow(10, $level);}
    elseif($type=='path') {return pow(9, $level);}
    else {return pow(8, $level);}
}

function getWallEffect($level) {
    if(!$level) return 0;
    if($level == 1) return -1;
    return ($level - 1) * -3;
}

function getLandEffect($level) {
    if(!$level) return 0;
    if($level == 1 || $level == 2) return $level * 2;
    if($level < 6) return ($level - 1) * 2;
    return 12;
}

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function prettyNumber($number) {
    return number_format($number, 0, ',', '.');
}

function getLevel($exp) {
    return floor( sqrt( $exp / 5 ) ) + 1;
}

function getExpNeeded($level) {
    return ((pow( $level, 2 ) * 5) + (5 * floor($level / 5)));
}

function getPreviousExpNeeded($level) {
    return getExpNeeded($level - 1);
}

function getItemModelFromModelNo($modelNo) {
    $modelArray = array('weapons', 'potions', 'helmets', 'armour', 'jewellery', 'gloves', 'boots', 'shields');

    return $modelArray[$modelNo-1];
}

function parseBBCodes($text) {

    preg_match_all('~!S:"(.*?)"!~s', $text, $users, PREG_SET_ORDER);

    foreach($users as $user) {
        $userDb = ORM::for_table('user')
            ->selectExpr('id')
            ->where('name', $user[1])
            ->find_one();

        if($userDb) {
            $text = str_replace('S:"'.$user[1].'"', 'S:'.$user[1].':'.$userDb->id, $text);
        } else {
            $text = str_replace('!S:"'.$user[1].'"!', $user[1], $text);
        }
    }

    preg_match_all('~!N:"(.*?)"!~s', $text, $clannames, PREG_SET_ORDER);

    foreach($clannames as $clanname) {
        $clanDb = ORM::for_table('clan')
            ->selectExpr('id')
            ->where('name', $clanname[1])
            ->find_one();

        if($clanDb) {
            $text = str_replace('N:"'.$clanname[1].'"', 'N:'.$clanname[1].':'.$clanDb->id, $text);
        } else {
            $text = str_replace('!N:"'.$clanname[1].'"!', $clanname[1], $text);
        }
    }

    preg_match_all('~!A:"(.*?)"!~s', $text, $clantags, PREG_SET_ORDER);

    foreach($clantags as $clantag) {
        $clanDb = ORM::for_table('clan')
            ->selectExpr('id')
            ->where('tag', $clantag[1])
            ->find_one();

        if($clanDb) {
            $text = str_replace('A:"'.$clantag[1].'"', 'A:'.$clantag[1].':'.$clanDb->id, $text);
        } else {
            $text = str_replace('!A:"'.$clantag[1].'"!', $clantag[1], $text);
        }
    }

    $find = array(
        '~\[b\](.*?)\[/b\]~s',
        '~\[i\](.*?)\[/i\]~s',
        '~\[f s=(.*?)\](.*?)\[/f\]~s',
        '~\[f c=(.*?)\](.*?)\[/f\]~s',
        '~\[f f="(.*?)"\](.*?)\[/f\]~s',
        '~!S:(.*?):(.*?)!~s',
        '~!N:(.*?):(.*?)!~s',
        '~!A:(.*?):(.*?)!~s',
    );

    $replace = array(
        '<b>$1</b>',
        '<i>$1</i>',
        '<span style="font-size:$1px;">$2</span>',
        '<span style="color:$1;">$2</span>',
        '<font face="$1">$2</font>',
        '<a href="'.getUrl('profile/player/$2').'">$1</a>',
        '<a href="'.getUrl('clan/view/$2').'">$1</a>',
        '<a href="'.getUrl('clan/view/$2').'">$1</a>'
    );

    return str_replace(PHP_EOL, '<br>', preg_replace($find,$replace,e($text)));
}

function profilePrintUserItem($i) {
    ?>
    <tr>
        <td class='<?php if($i->equipped) echo 'active'; else echo 'inactive'; ?> itemslot' style="text-align:center;">
            <div style="position:relative;width:300px;">
                <img src="<?php echo getAssetLink('img/items/'.$i->model.'/'.$i->id.'.jpg') ?>" <?php if($i->scost > 0) echo 'style="border: 1px solid #6f86a9;"'; ?> alt="<?php echo $i->name ?>">

                <!-- Unterscheidung ob Waffe oder nicht, da unterschiedliches Bildformat -->
                <div style="position: absolute; right: 20px; top: 15px; z-index: 9999;">
                    <?php if ($i->stern >= 1): ?>
                        <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0px none;">
                    <?php endif ?>
                    <?php if ($i->stern == 2): ?>
                        <img src="<?php echo getAssetLink('img/symbols/stern.png'); ?>" style="border: 0px none;">
                    <?php endif ?>
                </div>
            </div>
        </td>

        <?php
        if($i->duration > 0)
        {
            $durationString = '';
            $dur = $i->duration;
            if($dur/3600 < 10) $durationString .= '0' . $dur/3600 . ':'; else $durationString .= $dur/3600 . ':';
            $dur = $dur %3600;
            if($dur/60 < 10) $durationString .= '0' . $dur/60 . ':'; else $durationString .= $dur/60 . ':';
            $dur = $dur%60;
            if($dur < 10) $durationString .= '0' . $dur; else $durationString .= $dur;
        }

        if($i->cooldown > 0) {
            $cooldownString = '';
            $cd = $i->cooldown;
            if($cd/3600 < 10) $cooldownString .= '0' . $cd/3600 . ':'; else $cooldownString .= $cd/3600 . ':';
            $cd = $cd %3600;
            if($cd/60 < 10) $cooldownString .= '0' . $cd/60 . ':'; else $cooldownString .= $cd/60 . ':';
            $cd = $cd%60;
            if($cd < 10) $cooldownString .= '0' . $cd; else $cooldownString .= $cd;
        }
        ?>

        <td class='<?php if($i->equipped) echo 'active'; else echo 'tdn'; ?>'>
            <strong><?php echo $i->name; ?> </strong><br>
            (Your inventory: <?php if($i->volume) echo $i->volume; else echo 0; ?> item(s))<br><br>
            Resale value: <?php echo number_format($i->slcost, 0, ",", "."); ?><img src="<?php echo getAssetLink('img/symbols/res2.gif'); ?>" alt="Gold" align="absmiddle" border="0"><br><br>
            <?php if($i->str > 0): ?>        Strenght: +<?php echo $i->str; ?><br> <?php endif; ?>
            <?php if($i->def > 0): ?>         Defence: +<?php echo $i->def; ?><br> <?php endif; ?>
            <?php if($i->dex > 0): ?>         Dexterity: +<?php echo $i->dex; ?><br> <?php endif; ?>
            <?php if($i->end > 0): ?>         Endurance: +<?php echo $i->end; ?><br> <?php endif; ?>
            <?php if($i->cha > 0): ?>         Charisma: +<?php echo $i->cha; ?><br> <?php endif; ?>
            <?php if($i->str < 0): ?>         Strenght: <?php echo $i->str; ?><br> <?php endif; ?>
            <?php if($i->def < 0): ?>         Defence: <?php echo $i->def; ?><br> <?php endif; ?>
            <?php if($i->dex < 0): ?>         Dexterity: <?php echo $i->dex; ?><br> <?php endif; ?>
            <?php if($i->end < 0): ?>         Endurance: <?php echo $i->end; ?><br> <?php endif; ?>
            <?php if($i->cha < 0): ?>         Charisma: <?php echo $i->cha; ?><br> <?php endif; ?>
            <?php if($i->hpbonus > 0): ?>     Vitality: +<?php echo number_format($i->hpbonus, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->regen > 0): ?>       Regeneration: +<?php echo number_format($i->regen, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->hpbonus < 0): ?>     Vitality: <?php echo number_format($i->hpbonus, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->regen < 0): ?>       Regeneration: <?php echo number_format($i->regen, 0, ",", "."); ?><br> <?php endif; ?>
            <?php if($i->sbschc > 0): ?>      Basic hit chance: +<?php echo $i->sbschc; ?><br> <?php endif; ?>
            <?php if($i->sbscdmg > 0): ?>     Basic damage: +<?php echo $i->sbscdmg; ?><br> <?php endif; ?>
            <?php if($i->sbsctlnt > 0): ?>    Basic talent: +<?php echo $i->sbsctlnt; ?><br> <?php endif; ?>
            <?php if($i->sbnshc > 0): ?>      Bonus hit chance: +<?php echo $i->sbnshc; ?><br> <?php endif; ?>
            <?php if($i->sbnsdmg > 0): ?>     Bonus damage: +<?php echo $i->sbnsdmg; ?><br>   <?php endif; ?>
            <?php if($i->sbnstlnt > 0): ?>    Bonus talent: +<?php echo $i->sbnstlnt; ?><br> <?php endif; ?>
            <?php if($i->sbschc < 0): ?>      Basic hit chance: <?php echo $i->sbschc; ?><br> <?php endif; ?>
            <?php if($i->sbscdmg < 0): ?>     Basic damage: <?php echo $i->sbscdmg; ?><br> <?php endif; ?>
            <?php if($i->sbsctlnt < 0): ?>    Basic talent: <?php echo $i->sbsctlnt; ?><br> <?php endif; ?>
            <?php if($i->sbnshc < 0): ?>      Bonus hit chance: <?php echo $i->sbnshc; ?><br> <?php endif; ?>
            <?php if($i->sbnsdmg < 0): ?>     Bonus damage: <?php echo $i->sbnsdmg; ?><br>   <?php endif; ?>
            <?php if($i->sbnstlnt < 0): ?>    Bonus talent: <?php echo $i->sbnstlnt; ?><br> <?php endif; ?>
            <?php if($i->ebschc < 0): ?>      Basic hit chance (on opponent): <?php echo $i->ebschc; ?><br> <?php endif; ?>
            <?php if($i->ebscdmg < 0): ?>     Basic damage (on opponent): <?php echo $i->ebscdmg; ?><br> <?php endif; ?>
            <?php if($i->ebsctlnt < 0): ?>    Basic talent (on opponent): <?php echo $i->ebsctlnt; ?><br> <?php endif; ?>
            <?php if($i->ebnshc < 0): ?>      Bonus hit chance (on opponent): <?php echo $i->ebnshc; ?><br> <?php endif; ?>
            <?php if($i->ebnsdmg < 0): ?>     Bonus damage (on opponent): <?php echo $i->ebnsdmg; ?><br> <?php endif; ?>
            <?php if($i->ebnstlnt < 0): ?>    Bonus talent (on opponent): <?php echo $i->ebnstlnt; ?><br> <?php endif; ?>
            <?php if($i->apup > 0): ?>    Energy: <?php echo $i->apup; ?><br> <?php endif; ?>
            <?php if($i->id == 156): ?>       Man hunt: gold and booty x2<br> <?php endif; ?>
            <?php if($i->duration > 0): ?>    Duration of effect: <?php echo $durationString; ?><br> <?php endif; ?>
            <?php if(!$i->duration > 0): if($i->cooldown > 0): ?>    Cooldown time: <?php echo $cooldownString; ?><br> <?php endif; endif; ?>
            <br>
            Requirement: level <?php echo number_format($i->level, 0, ",", "."); ?><br>

            <?php if(!$i->equipped && $i->expire <= time()): ?>
            <br><div class="btn-left left"><div class="btn-right">
                        <form method="post" action="<?php echo getUrl('profile/item/equip'); ?>">
                            <input type="hidden" name="__token" value="">
                            <input type="hidden" name="item_id" value="<?php echo $i->id; ?>">
                            <button class="btn">Use this item</button>
                        </form>
                    </div></div><br/>
            <?php elseif($i->cooldown > 0 && $i->expire > time()): ?>
                Cooldown time <span id="item_cooldown2_<?php echo $i->id; ?>" ></span><br/>
                <script type="text/javascript">
                    $(function () {
                        $("#item_cooldown2_<?php echo $i->id; ?>").countdown({
                            until: +<?php echo $i->expire - time(); ?>,
                            compact: true,
                            compactLabels: ['y', 'm', 'w', 'd'],
                            description: '',onExpiry: redirectUser
                        });
                    });
                    function redirectUser() {
                        setTimeout('window.location = "<?php echo url('profile/index') ?>"',3000);
                    }
                </script>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}