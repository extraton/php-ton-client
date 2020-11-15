<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;
use RuntimeException;

use function in_array;
use function sprintf;

class AccountForExecutor implements Params
{
    public const TYPE_NONE = 'None';

    public const TYPE_UNINIT = 'Uninit';

    public const TYPE_ACCOUNT = 'Account';

    private string $type;

    private string $boc;

    private ?bool $unlimitedBalance;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return self
     */
    public static function fromNone(): self
    {
        return new self(self::TYPE_NONE);
    }

    /**
     * @return self
     */
    public static function fromUninit(): self
    {
        return new self(self::TYPE_UNINIT);
    }

    /**
     * @param string $boc
     * @param bool|null $unlimitedBalance
     * @return static
     */
    public static function fromAccount(string $boc, ?bool $unlimitedBalance): self
    {
        $instance = new self(self::TYPE_ACCOUNT);
        $instance->setBoc($boc);
        $instance->setUnlimitedBalance($unlimitedBalance);

        return $instance;
    }

    /**
     * @param string $boc
     * @return $this
     */
    private function setBoc(string $boc): self
    {
        $this->boc = $boc;

        return $this;
    }

    /**
     * @param bool|null $unlimitedBalance
     * @return $this
     */
    private function setUnlimitedBalance(?bool $unlimitedBalance): self
    {
        $this->unlimitedBalance = $unlimitedBalance;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $result['type'] = $this->type;

        if (in_array($this->type, [self::TYPE_NONE, self::TYPE_UNINIT], true)) {
            // do nothing
        } elseif ($this->type === self::TYPE_ACCOUNT) {
            $result['boc'] = $this->boc;
            $result['unlimited_balance'] = $this->unlimitedBalance;
        } else {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        return $result;
    }
}
