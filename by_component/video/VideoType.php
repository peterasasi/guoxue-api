<?php


namespace by\component\video;


class VideoType
{
    const Mp4 = "video/mp4";

    const Webm = "video/webm";

    const HlsM3u8 = "application/x-mpegURL";

    const Mpd = "application/dash+xml";

    // iframe 插入播放 ，不能用播放器播放的
    const IFrameInsert = "iframe_insert";

    // 网盘视频来源，如果有密码用逗号分割，密码放后面
    // url@password
    const CloudDisk = 'cloud_disk';

    public static $supportArr = [
        self::Mp4, self::Webm, self::HlsM3u8, self::Mpd,
        // 特殊的处理
        self::IFrameInsert, self::CloudDisk
    ];

    public static function isSupport($type) {
        return in_array($type, self::$supportArr);
    }
}
