<?php

declare(strict_types=1);

namespace Extraton\TonClient\Composer;

use Composer\Script\Event;
use RuntimeException;

use function fclose;
use function file_exists;
use function fopen;
use function fwrite;
use function gzclose;
use function gzeof;
use function gzopen;
use function gzread;
use function is_dir;
use function mkdir;
use function rtrim;
use function sprintf;
use function str_replace;
use function strtolower;
use function sys_get_temp_dir;
use function system;
use function tempnam;
use function unlink;

use const DIRECTORY_SEPARATOR;
use const PHP_OS;

/**
 * Composer script for post-install and post-update execute
 */
class PostScript
{
    private const DEFAULT_SDK_VERSION = '1.0.0';

    private const SOURCE_FILE_NAME = [
        'win32'  => 'tonclient_%s_%s_dll.gz',
        'darwin' => 'tonclient_%s_%s.gz',
        'linux'  => 'tonclient_%s_%s.gz',
    ];

    private const DESTINATION_FILE_NAME = [
        'win32'  => 'tonclient.dll',
        'darwin' => 'tonclient.dylib',
        'linux'  => 'tonclient.so',
    ];

    private const DOWNLOAD_URL = 'http://sdkbinaries.tonlabs.io/%s';

    /**
     * Download TON SDK library
     *
     * @param Event $event
     */
    public static function downloadLibrary(Event $event): void
    {
        $extra = $event->getComposer()->getPackage()->getExtra();
        $binSdkVersion = $extra['sdk-version'] ?? self::DEFAULT_SDK_VERSION;

        $os = strtolower(PHP_OS);
        if (!isset(self::SOURCE_FILE_NAME[$os], self::DESTINATION_FILE_NAME[$os])) {
            throw new RuntimeException(sprintf('Unknown OS "%s"', $os));
        }

        $srcFileName = sprintf(self::SOURCE_FILE_NAME[$os], str_replace('.', '_', $binSdkVersion), $os);
        $downloadUrl = sprintf(self::DOWNLOAD_URL, $srcFileName);
        $tmpPath = tempnam(sys_get_temp_dir(), 'ton_client_');

        $downloadScript = <<<DOWNLOAD
            php -r "copy('{$downloadUrl}', '{$tmpPath}');"
        DOWNLOAD;

        system($downloadScript);

        // Get or create bin dir
        $binDir = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../../bin/');
        if (!file_exists($binDir) && !mkdir($binDir) && !is_dir($binDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $binDir));
        }

        // Destination path to library
        $dstPath = rtrim($binDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::DESTINATION_FILE_NAME[$os];

        // Unpack gz file
        $tmpFileHandler = gzopen($tmpPath, 'rb');
        $dstFileHandler = fopen($dstPath, 'wb');
        while (!gzeof($tmpFileHandler)) {
            fwrite($dstFileHandler, gzread($tmpFileHandler, 4096));
        }
        fclose($dstFileHandler);
        gzclose($tmpFileHandler);

        // Remove temporary file
        unlink($tmpPath);
    }
}
