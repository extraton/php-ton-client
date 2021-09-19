<?php

declare(strict_types=1);

namespace Extraton\TonClient\Composer;

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
class Scripts
{
    private const DEFAULT_SDK_VERSION = '1.21.5';

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
     * Download TON SDK library and save to bin directory
     */
    public static function downloadLibrary(): void
    {
        $os = strtolower(PHP_OS);
        if (!isset(self::SOURCE_FILE_NAME[$os], self::DESTINATION_FILE_NAME[$os])) {
            throw new RuntimeException(sprintf('Unknown OS %s.', $os));
        }

        $srcFileName = sprintf(self::SOURCE_FILE_NAME[$os], str_replace('.', '_', self::DEFAULT_SDK_VERSION), $os);
        $downloadUrl = sprintf(self::DOWNLOAD_URL, $srcFileName);
        $tmpPath = tempnam(sys_get_temp_dir(), 'ton_client_');
        if ($tmpPath === false) {
            throw new RuntimeException(sprintf('Failed to create temporary file %s.', $tmpPath));
        }

        $downloadScript = <<<DOWNLOAD
            php -r "copy('{$downloadUrl}', '{$tmpPath}');"
        DOWNLOAD;

        system($downloadScript);

        // Get or create bin dir
        $binDir = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../../bin/');
        if (!file_exists($binDir) && !mkdir($binDir) && !is_dir($binDir)) {
            throw new RuntimeException(sprintf('The path %s does not exist.', $binDir));
        }

        // Destination path to library
        $dstPath = rtrim($binDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::DESTINATION_FILE_NAME[$os];

        // Unpack gz file
        $tmpFileHandler = gzopen($tmpPath, 'rb');
        if ($tmpFileHandler === false) {
            throw new RuntimeException(sprintf('Could not open file %s.', $tmpPath));
        }

        $dstFileHandler = fopen($dstPath, 'wb');
        if ($dstFileHandler === false) {
            throw new RuntimeException(sprintf('Could not open file %s.', $dstPath));
        }

        while (!gzeof($tmpFileHandler)) {
            $data = gzread($tmpFileHandler, 4096);
            if ($data === false) {
                throw new RuntimeException('Call function gzread failed.');
            }

            fwrite($dstFileHandler, $data);
        }
        fclose($dstFileHandler);
        gzclose($tmpFileHandler);

        // Remove temporary file
        unlink($tmpPath);
    }
}
