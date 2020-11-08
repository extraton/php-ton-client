<?php

declare(strict_types=1);

namespace Extraton\TonClient\FFI;

use FFI;
use FFI\CData;

use function call_user_func_array;

class FFIAdapter
{
    private string $libraryInterface;

    private string $libraryPath;

    private ?FFI $ffi = null;

    public function __construct(string $libraryInterface, string $libraryPath)
    {
        $this->libraryInterface = $libraryInterface;
        $this->libraryPath = $libraryPath;
    }

    public function __call(string $functionName, array $arguments = []): FFI\CData
    {
        return $this->call($functionName, $arguments);
    }

    public function call(string $functionName, array $arguments = []): FFI\CData
    {
        $callable = [
            $this->getFFI(),
            $functionName
        ];

        return call_user_func_array($callable, $arguments);
    }

    public function getFFI(): FFI
    {
        if ($this->ffi === null) {
            $this->ffi = FFI::cdef(
                $this->getLibraryInterface(),
                $this->getLibraryPath()
            );
        }

        return $this->ffi;
    }

    public function getLibraryInterface(): string
    {
        return $this->libraryInterface;
    }

    public function getLibraryPath(): string
    {
        return $this->libraryPath;
    }

    public function callNew(string $type, bool $owned = true): FFI\CData
    {
        return $this->getFFI()->new($type, $owned);
    }

    /**
     * Creates a PHP string from a memory area
     *
     * @param CData $ptr The start of the memory area from which to create a string
     * @param int $size The number of bytes to copy to the string
     * @return string
     */
    public function callString(CData $ptr, int $size): string
    {
        return FFI::string($ptr, $size);
    }

    /**
     * Copies one memory area to another
     *
     * @param CData $destination The start of the memory area to copy to
     * @param string $source The start of the memory area to copy from
     * @param int $size The number of bytes to copy
     */
    public function callMemCpy(CData $destination, string $source, int $size): void
    {
        FFI::memcpy($destination, $source, $size);
    }
}
