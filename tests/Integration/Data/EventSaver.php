<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient\Data;

use Generator;
use RuntimeException;
use SplFileObject;

use function json_encode;
use function sprintf;
use function str_replace;

use const DIRECTORY_SEPARATOR;
use const LOCK_EX;
use const LOCK_NB;
use const PHP_EOL;

/**
 * EventSaver
 */
class EventSaver
{
    /**
     * @param string $fileName
     * @return Generator
     */
    public function getSaver(string $fileName)
    {
        $path = str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            sprintf(
                '%s/../artifacts/%s.txt',
                __DIR__,
                str_replace(['::', '\\'], '_', $fileName)
            )
        );

        $file = new SplFileObject($path, "w");
        $locked = $file->flock(LOCK_EX | LOCK_NB);

        if (!$locked) {
            throw new RuntimeException(sprintf('Can\'t acquire lock for file %s', $path));
        }

        $file->ftruncate(0);
        $file->fseek(0);

        while (true) {
            $line = yield;
            $file->fwrite(json_encode($line, JSON_PRETTY_PRINT));
            $file->fwrite(PHP_EOL);
            $file->fwrite(PHP_EOL);
        }
    }
}
