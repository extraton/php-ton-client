<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

use function sprintf;

/**
 * Type StateInitSource
 */
class StateInitSource implements Params
{
    public const TYPE_MESSAGE = 'Message';

    public const TYPE_STATE_INIT = 'StateInit';

    public const TYPE_TVC = 'Tvc';

    private string $type;

    private MessageSource $messageSource;

    private string $code;

    private string $data;

    private ?string $library;

    private string $tvc;

    private ?string $publicKey;

    private ?StateInitParams $stateInitParams;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param MessageSource $messageSource
     * @return self
     */
    public static function fromMessage(MessageSource $messageSource): self
    {
        $instance = new self(self::TYPE_MESSAGE);
        $instance->setMessageSource($messageSource);

        return $instance;
    }

    /**
     * @param MessageSource $messageSource
     * @return self
     */
    private function setMessageSource(MessageSource $messageSource): self
    {
        $this->messageSource = $messageSource;

        return $this;
    }

    /**
     * @param string $code
     * @param string $data
     * @param string|null $library
     * @return self
     */
    public static function fromStateInit(string $code, string $data, ?string $library = null): self
    {
        $instance = new self(self::TYPE_STATE_INIT);
        $instance->setCode($code);
        $instance->setData($data);
        $instance->setLibrary($library);

        return $instance;
    }

    /**
     * @param string $code
     * @return self
     */
    private function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $data
     * @return self
     */
    private function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string|null $library
     * @return self
     */
    private function setLibrary(?string $library): self
    {
        $this->library = $library;

        return $this;
    }

    /**
     * @param string $tvc
     * @param string|null $publicKey
     * @param StateInitParams|null $stateInitParams
     * @return self
     */
    public static function fromTvc(
        string $tvc,
        ?string $publicKey = null,
        ?StateInitParams $stateInitParams = null
    ): self {
        $instance = new self(self::TYPE_TVC);
        $instance->setTvc($tvc);
        $instance->setPublicKey($publicKey);
        $instance->setStateInitParams($stateInitParams);

        return $instance;
    }

    /**
     * @param string $tvc
     * @return self
     */
    public function setTvc(string $tvc): self
    {
        $this->tvc = $tvc;

        return $this;
    }

    /**
     * @param string|null $publicKey
     * @return self
     */
    public function setPublicKey(?string $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * @param StateInitParams|null $stateInitParams
     * @return self
     */
    public function setStateInitParams(?StateInitParams $stateInitParams): self
    {
        $this->stateInitParams = $stateInitParams;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $result['type'] = $this->type;

        if ($this->type === self::TYPE_MESSAGE) {
            $result['source'] = $this->messageSource;
        } elseif ($this->type === self::TYPE_STATE_INIT) {
            $result['code'] = $this->code;
            $result['data'] = $this->data;
            $result['library'] = $this->library;
        } elseif ($this->type === self::TYPE_TVC) {
            $result['type'] = $this->type;
            $result['tvc'] = $this->tvc;
            $result['public_key'] = $this->publicKey;
            $result['init_params'] = $this->stateInitParams;
        } else {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        return $result;
    }
}
