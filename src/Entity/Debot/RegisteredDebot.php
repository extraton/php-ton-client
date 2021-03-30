<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Debot;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type RegisteredDebot
 */
class RegisteredDebot extends AbstractResult
{
    /**
     * Get debot handle which references an instance of debot engine.
     *
     * @return int
     */
    public function getDebotHandle(): int
    {
        return $this->requireInt('debot_handle');
    }

    /**
     * Get debot abi as json string
     *
     * @return string
     */
    public function getDebotAbi(): string
    {
        return $this->requireString('debot_abi');
    }
}
