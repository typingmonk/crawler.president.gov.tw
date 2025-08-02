<?php

class Keyword {
    private static $actions = ['制定', '修正', '增訂', '廢止', '刪除'];

    private static $common_not_law_strs = [
        '任免官員', '授予勳章', '明令褒揚', '總統活動紀要', '副總統活動紀要',
    ];

    public static function getActions()
    {
        $actions = [];
        $single_actions = self::$actions;
        foreach ($single_actions as $single_action_a) {
            foreach ($single_actions as $single_action_b) {
                if ($single_action_a != $single_action_b) {
                    $actions[] = "{$single_action_a}並{$single_action_b}";
                }
            }
        }
        return array_merge(['增訂、刪除並修正'], $actions, $single_actions);
    }

    public static function isObviousNotLaw($title)
    {
        foreach (self::$common_not_law_strs as $str) {
            if ($title == $str) {
                return "^{$str}$";
            }
        }
        if (str_ends_with($title, '典禮')) {
            return '典禮$';
        }
        return false;
    }
}
