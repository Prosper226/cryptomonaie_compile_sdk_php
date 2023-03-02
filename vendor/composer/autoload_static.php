<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita843ecf4eb74d4c7513b3d4ce3f9d9c4
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'lab\\' => 4,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
        ),
        'N' => 
        array (
            'Nowpayment\\' => 11,
        ),
        'M' => 
        array (
            'MoovAfrica\\' => 11,
        ),
        'L' => 
        array (
            'Ligdicash\\' => 10,
        ),
        'K' => 
        array (
            'Kraken\\' => 7,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'D' => 
        array (
            'Dunya\\' => 6,
        ),
        'C' => 
        array (
            'Crypto\\' => 7,
            'Coinbase\\' => 9,
            'CinetPay\\' => 9,
        ),
        'B' => 
        array (
            'Binance\\' => 8,
            'Bapi\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'lab\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Nowpayment\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/NOWPAYMENT',
        ),
        'MoovAfrica\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/MOOVAFRICA',
        ),
        'Ligdicash\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/LIGDICASH',
        ),
        'Kraken\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/KRAKEN',
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
        'Dunya\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/PAYDUNYA',
        ),
        'Crypto\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/CRYPTO',
        ),
        'Coinbase\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/COINBASE',
        ),
        'CinetPay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/CINETPAY',
        ),
        'Binance\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/BINANCE',
        ),
        'Bapi\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/BAPI',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita843ecf4eb74d4c7513b3d4ce3f9d9c4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita843ecf4eb74d4c7513b3d4ce3f9d9c4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita843ecf4eb74d4c7513b3d4ce3f9d9c4::$classMap;

        }, null, ClassLoader::class);
    }
}
