<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient\Entity;

use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
use Extraton\TonClient\Handler\Response;
use PHPUnit\Framework\TestCase;

use function microtime;
use function uniqid;

/**
 * @coversDefaultClass \Extraton\TonClient\Entity\Utils\ResultOfConvertAddress
 */
class ResultOfConvertAddressTest extends TestCase
{
    /**
     * @covers ::getAddress
     */
    public function testGetAddress(): void
    {
        $data = [
            'address' => $address = uniqid(microtime(), true),
        ];

        $result = new ResultOfConvertAddress(new Response($data));

        self::assertEquals(
            $address,
            $result->getAddress()
        );
    }
}
