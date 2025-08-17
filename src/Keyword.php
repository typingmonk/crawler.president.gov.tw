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
        return array_merge(
            ['增訂、刪除並修正', '延展並修正'],
            $actions,
            $single_actions
        );
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

    public static function getLawNameType1($title)
    {
        $law_end_strs = ['條文', '條例', '條例條文'];
        $actions = self::getActions();

        foreach ($law_end_strs as $end_str) {
            if (str_ends_with($title, $end_str)) {
                $length_str = mb_strlen($title);
                foreach ($actions as $action) {
                    $length_action = mb_strlen($action);
                    if (mb_substr($title, 0, $length_action) === $action) {
                        $length_title = mb_strlen($title);
                        $matched_pattern = "^{$action}.*" . "{$end_str}$";
                        $length_law = $length_title - $length_action;
                        if (str_contains($end_str, '條文')) {
                            $length_law = $length_law - 2;
                        }
                        $law = mb_substr(
                            $title,
                            $length_action,
                            $length_law
                        );
                        return [$law, $matched_pattern];
                    }
                }
            }
        }
        return false;
    }

    public static function getLawID($law_name)
    {
        $query_url = 'https://ly.govapi.tw/v2/laws?q="' . $law_name . '"&類別=母法&limit=10';
        $ret = file_get_contents($query_url);
        $json = json_decode($ret);
        $laws = $json->laws ?? [];
        if (empty($laws)) {
            //no matched law in db
            return false;
        }
        foreach ($laws as $law) {
            $law_name_retrieved = $law->名稱;
            if ($law_name != $law_name_retrieved) {
                //law_name not exact matched
                continue;
            }

            //update law_id
            $law_id = $law->法律編號;
            return $law_id;
        }

        return false;
    }
}
