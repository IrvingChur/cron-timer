<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit95c983d4daf27a62e643436fbfc6ec05
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '72579e7bd17821bb1321b87411366eae' => __DIR__ . '/..' . '/illuminate/support/helpers.php',
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Container\\' => 14,
            'Predis\\' => 7,
        ),
        'O' => 
        array (
            'OSS\\' => 4,
        ),
        'I' => 
        array (
            'Illuminate\\Support\\' => 19,
            'Illuminate\\Database\\' => 20,
            'Illuminate\\Contracts\\' => 21,
            'Illuminate\\Container\\' => 21,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Inflector\\' => 26,
        ),
        'C' => 
        array (
            'Cron\\' => 5,
            'Carbon\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Predis\\' => 
        array (
            0 => __DIR__ . '/..' . '/predis/predis/src',
        ),
        'OSS\\' => 
        array (
            0 => __DIR__ . '/..' . '/aliyuncs/oss-sdk-php/src/OSS',
        ),
        'Illuminate\\Support\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/support',
        ),
        'Illuminate\\Database\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/database',
        ),
        'Illuminate\\Contracts\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/contracts',
        ),
        'Illuminate\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/container',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Doctrine\\Common\\Inflector\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/inflector/lib/Doctrine/Common/Inflector',
        ),
        'Cron\\' => 
        array (
            0 => __DIR__ . '/..' . '/mtdowling/cron-expression/src/Cron',
        ),
        'Carbon\\' => 
        array (
            0 => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon',
        ),
    );

    public static $classMap = array (
        'AMFReader' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.flv.php',
        'AMFStream' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.flv.php',
        'AVCSequenceParameterSetReader' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.flv.php',
        'Image_XMP' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.tag.xmp.php',
        'getID3' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/getid3.php',
        'getID3_cached_dbm' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/extension.cache.dbm.php',
        'getID3_cached_mysql' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/extension.cache.mysql.php',
        'getID3_cached_mysqli' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/extension.cache.mysqli.php',
        'getID3_cached_sqlite3' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/extension.cache.sqlite3.php',
        'getid3_aa' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.aa.php',
        'getid3_aac' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.aac.php',
        'getid3_ac3' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.ac3.php',
        'getid3_amr' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.amr.php',
        'getid3_apetag' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.tag.apetag.php',
        'getid3_asf' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.asf.php',
        'getid3_au' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.au.php',
        'getid3_avr' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.avr.php',
        'getid3_bink' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.bink.php',
        'getid3_bmp' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.bmp.php',
        'getid3_bonk' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.bonk.php',
        'getid3_cue' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.misc.cue.php',
        'getid3_dsf' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.dsf.php',
        'getid3_dss' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.dss.php',
        'getid3_dts' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.dts.php',
        'getid3_efax' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.efax.php',
        'getid3_exception' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/getid3.php',
        'getid3_exe' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.misc.exe.php',
        'getid3_flac' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.flac.php',
        'getid3_flv' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.flv.php',
        'getid3_gif' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.gif.php',
        'getid3_gzip' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.archive.gzip.php',
        'getid3_handler' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/getid3.php',
        'getid3_id3v1' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.tag.id3v1.php',
        'getid3_id3v2' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.tag.id3v2.php',
        'getid3_iso' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.misc.iso.php',
        'getid3_jpg' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.jpg.php',
        'getid3_la' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.la.php',
        'getid3_lib' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/getid3.lib.php',
        'getid3_lpac' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.lpac.php',
        'getid3_lyrics3' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.tag.lyrics3.php',
        'getid3_matroska' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.matroska.php',
        'getid3_midi' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.midi.php',
        'getid3_mod' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.mod.php',
        'getid3_monkey' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.monkey.php',
        'getid3_mp3' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.mp3.php',
        'getid3_mpc' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.mpc.php',
        'getid3_mpeg' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.mpeg.php',
        'getid3_msoffice' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.misc.msoffice.php',
        'getid3_nsv' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.nsv.php',
        'getid3_ogg' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.ogg.php',
        'getid3_optimfrog' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.optimfrog.php',
        'getid3_par2' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.misc.par2.php',
        'getid3_pcd' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.pcd.php',
        'getid3_pdf' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.misc.pdf.php',
        'getid3_png' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.png.php',
        'getid3_quicktime' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.quicktime.php',
        'getid3_rar' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.archive.rar.php',
        'getid3_real' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.real.php',
        'getid3_riff' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.riff.php',
        'getid3_rkau' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.rkau.php',
        'getid3_shorten' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.shorten.php',
        'getid3_svg' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.svg.php',
        'getid3_swf' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.swf.php',
        'getid3_szip' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.archive.szip.php',
        'getid3_tar' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.archive.tar.php',
        'getid3_tiff' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.graphic.tiff.php',
        'getid3_ts' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio-video.ts.php',
        'getid3_tta' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.tta.php',
        'getid3_voc' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.voc.php',
        'getid3_vqf' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.vqf.php',
        'getid3_wavpack' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.audio.wavpack.php',
        'getid3_write_apetag' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.apetag.php',
        'getid3_write_id3v1' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.id3v1.php',
        'getid3_write_id3v2' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.id3v2.php',
        'getid3_write_lyrics3' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.lyrics3.php',
        'getid3_write_metaflac' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.metaflac.php',
        'getid3_write_real' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.real.php',
        'getid3_write_vorbiscomment' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.vorbiscomment.php',
        'getid3_writetags' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/write.php',
        'getid3_xz' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.archive.xz.php',
        'getid3_zip' => __DIR__ . '/..' . '/james-heinrich/getid3/getid3/module.archive.zip.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95c983d4daf27a62e643436fbfc6ec05::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95c983d4daf27a62e643436fbfc6ec05::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit95c983d4daf27a62e643436fbfc6ec05::$classMap;

        }, null, ClassLoader::class);
    }
}
