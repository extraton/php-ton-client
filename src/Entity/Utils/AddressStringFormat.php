<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

use function in_array;
use function sprintf;

class AddressStringFormat implements Params
{
    public const TYPE_ACCOUNT_ID = 'AccountId';

    public const TYPE_HEX = 'Hex';

    public const TYPE_BASE64 = 'Base64';

    private string $type;

    private bool $url;

    private bool $test;

    private bool $bounce;

    public function __construct(
        string $type,
        bool $url = false,
        bool $test = false,
        bool $bounce = false
    ) {
        if (!in_array($type, [self::TYPE_ACCOUNT_ID, self::TYPE_HEX, self::TYPE_BASE64], true)) {
            throw new DataException(sprintf('Unknown type %s.', $type));
        }

        $this->type = $type;
        $this->url = $url;
        $this->test = $test;
        $this->bounce = $bounce;
    }

    /**
     * @return self
     */
    public static function accountId(): self
    {
        return new self(self::TYPE_ACCOUNT_ID);
    }

    /**
     * @return self
     */
    public static function hex(): self
    {
        return new self(self::TYPE_HEX);
    }

    /**
     * @param bool $url Is url
     * @param bool $test Is test
     * @param bool $bounce Is bounce
     * @return self
     */
    public static function base64(bool $url = false, bool $test = false, bool $bounce = false): self
    {
        return new self(self::TYPE_BASE64, $url, $test, $bounce);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        if (!in_array($this->type, [self::TYPE_ACCOUNT_ID, self::TYPE_HEX, self::TYPE_BASE64], true)) {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        $result = [
            'type' => $this->type,
        ];

        if ($this->type === self::TYPE_BASE64) {
            $result['url'] = $this->url;
            $result['test'] = $this->test;
            $result['bounce'] = $this->bounce;
        }

        return $result;
    }
}
