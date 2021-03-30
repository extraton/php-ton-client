<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\App;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

/**
 * Type AppRequestResult
 */
class AppRequestResult implements Params
{
    public const TYPE_ERROR = 'Error';

    public const TYPE_OK = 'Ok';

    private string $type;

    private string $text;

    /** @var mixed */
    private $result;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param string $text
     * @return self
     */
    public static function fromError(string $text): self
    {
        $instance = new self(self::TYPE_ERROR);
        $instance->setText($text);

        return $instance;
    }

    /**
     * @param mixed $result
     * @return self
     */
    public static function fromOK($result): self
    {
        $instance = new self(self::TYPE_OK);
        $instance->setResult($result);

        return $instance;
    }

    /**
     * Set error text
     *
     * @param string $text
     */
    private function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Set result
     *
     * @param mixed $result
     */
    private function setResult($result): void
    {
        $this->result = $result;
    }

    public function jsonSerialize(): array
    {
        if (!in_array($this->type, [self::TYPE_OK, self::TYPE_ERROR], true)) {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        $result = [
            'type' => $this->type,
        ];

        if ($this->type === self::TYPE_OK) {
            $result['result'] = $this->result;
        }

        if ($this->type === self::TYPE_ERROR) {
            $result['text'] = $this->text;
        }

        return $result;
    }
}
