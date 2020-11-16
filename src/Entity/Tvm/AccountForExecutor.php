<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

use function in_array;
use function sprintf;

/**
 * Type AccountForExecutor
 */
class AccountForExecutor implements Params
{
    public const TYPE_NONE = 'None';

    public const TYPE_UNINIT = 'Uninit';

    public const TYPE_ACCOUNT = 'Account';

    private string $type;

    private string $boc;

    private ?bool $unlimitedBalance;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Non-existing account to run a creation internal message.
     * Should be used with skip_transaction_check = true if the message has no deploy data
     * since transactions on the uninitialized account are always aborted
     *
     * @return self
     */
    public static function fromNone(): self
    {
        return new self(self::TYPE_NONE);
    }

    /**
     * Emulate uninitialized account to run deploy message
     *
     * @return self
     */
    public static function fromUninit(): self
    {
        return new self(self::TYPE_UNINIT);
    }

    /**
     * Account state to run message
     *
     * @param string $boc Account BOC. Encoded as base64.
     * @param bool|null $unlimitedBalance Flag for running account with the unlimited balance.
     *                                    Can be used to calculate transaction fees without balance check
     * @return self
     */
    public static function fromAccount(string $boc, ?bool $unlimitedBalance): self
    {
        $instance = new self(self::TYPE_ACCOUNT);
        $instance->setBoc($boc);
        $instance->setUnlimitedBalance($unlimitedBalance);

        return $instance;
    }

    /**
     * Set account BOC. Encoded as base64.
     *
     * @param string $boc Account BOC. Encoded as base64.
     * @return self
     */
    private function setBoc(string $boc): self
    {
        $this->boc = $boc;

        return $this;
    }

    /**
     * Set unlimited balance flag
     *
     * @param bool|null $unlimitedBalance Flag for running account with the unlimited balance.
     *                                    Can be used to calculate transaction fees without balance check
     * @return self
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
        if (!in_array($this->type, [self::TYPE_NONE, self::TYPE_UNINIT, self::TYPE_ACCOUNT], true)) {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        $result['type'] = $this->type;

        if ($this->type === self::TYPE_ACCOUNT) {
            $result['boc'] = $this->boc;
            $result['unlimited_balance'] = $this->unlimitedBalance;
        }

        return $result;
    }
}
