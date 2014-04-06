<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/30/13
 * Time: 4:23 PM
 */

namespace classes\core\helpers;


class MenuHelper
{
    public static function GenerateMenu($menuItems) {
        $menuId = mt_rand(1000000, 9999999);

        $menu = <<<EOQ
<ul class="menu" id="menu-$menuId">
EOQ;
        if(is_array($menuItems))
        {
            $menu .= self::AppendMenuLevel($menuItems, true);
        }

        $menu .= <<<EOQ
</ul>
<script type="text/javascript">
        $(function () {
            $(".menu")
                .menubar();
        });
</script>
EOQ;
        return $menu;
    }

    private static function AppendMenuLevel($menuItems, $firstLayer)
    {
        $menuLevel = "";
        if(!$firstLayer) {
            $menuLevel .= <<<EOQ
<ul>
EOQ;
        }

        foreach($menuItems as $label => $item) {
            if($item !== 'break') {
                if(is_array($item)) {
                    $link = (isset($item['link'])) ? $item['link'] : '#';
                    $icon = (isset($item['icon'])) ? '<img src="' . $item['icon'] . '" />' : '';
                    $id = (isset($item['id'])) ? $item['id'] : null;
                    $class = (isset($item['class'])) ? $item['class'] : null;
                    $target = isset($item['target']) ? 'target="' . $item['target'] . '"': '';

                    $liTag = "<li ";
                    if($id != null) {
                        $liTag .= "id=\"$id\" ";
                    }

                    if($id != null) {
                        $liTag .="class=\"$class\"";
                    }
                    $liTag .= ">";

                    $menuLevel .= $liTag . '<a href="' . $link. '" ' . $target . '>' . $icon . $label . '</a>';

                    if(isset($item['submenu'])) {
                        $menuLevel .= self::AppendMenuLevel($item['submenu'], false);
                    }

                    $menuLevel .= '</li>';
                }
                else {
                    $menuLevel .= '<li><a href="' . $item . '">' . $label . '</a></li>';
                }
            }
            else {
                $menuLevel .= "<li style=\"height: 4px;\"><hr style=\"height:4px;background-color:#003388;border:none;\"/></li>";
            }
        }

        if(!$firstLayer) {
            $menuLevel .= "</ul>";
        }

        return $menuLevel;
    }

} 