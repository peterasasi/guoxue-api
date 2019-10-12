<?php

namespace by\component\config\constants;


class SysConfigTypeEnum
{
    const DIGIT = 0;
    const CHAR = 1;
    const TEXT = 2;
    const ARRAY_TYPE = 3;
    const ENUM = 4;
    const PICTURE = 5;


    public static function isValid($type) {
        $validTypes = [SysConfigTypeEnum::DIGIT, SysConfigTypeEnum::CHAR, SysConfigTypeEnum::TEXT
            ,SysConfigTypeEnum::ARRAY_TYPE, SysConfigTypeEnum::ENUM, SysConfigTypeEnum::PICTURE
        ];
        return in_array($type, $validTypes);
    }
}
