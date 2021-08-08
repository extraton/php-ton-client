<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGetAddressType
 */
class ResultOfGetAddressType extends AbstractResult
{
    /** @var string Address type: AccountId */
    public const ACCOUNT_ID = 'AccountId';

    /** @var string Address type: hex */
    public const HEX = 'Hex';

    /** @var string Address type: base64 */
    public const BASE64 = 'Base64';

    /**
     * Get address type
     *
     * @return string
     */
    public function getAddressType(): string
    {
        return $this->requireString('address_type');
    }

    /**
     * Check: address type is AccountId
     *
     * @return bool
     */
    public function isAccountId(): bool
    {
        return $this->getAddressType() === self::ACCOUNT_ID;
    }

    /**
     * Check: address type is Hex
     *
     * @return bool
     */
    public function isHex(): bool
    {
        return $this->getAddressType() === self::HEX;
    }

    /**
     * Check: address type is Base64
     *
     * @return bool
     */
    public function isBase64(): bool
    {
        return $this->getAddressType() === self::BASE64;
    }
}
