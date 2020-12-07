<?php

namespace App\Helper;

class Functions {
    public static function gender ($cod) {
        if($cod == 1)
            return "Hombres";
        return "Mujeres";
    }
    public static function info($aux){
        // \Debugbar::info($aux);
    }
}
