<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\ParamsInterface;
use RuntimeException;

class StateInitSource implements ParamsInterface
{
    public const TYPE_MESSAGE = 'Message';

    public const TYPE_STATE_INIT = 'StateInit';

    public const TYPE_TVC = 'Tvc';

    private string $type;

    private string $tvc;

    private ?string $publicKey = null;

    private ?StateInitParams $initParams = null;

    private string $code;

    private string $data;

    private ?string $library = null;

    private MessageSource $messageSource;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromMessage(MessageSource $messageSource): self
    {
        $instance = new self(self::TYPE_MESSAGE);
        $instance->setMessageSource($messageSource);

        return $instance;
    }

    public static function fromStateInit(string $code, string $data, ?string $library = null): self
    {
        $instance = new self(self::TYPE_STATE_INIT);
        $instance->setStateInitParams($code, $data, $library);

        return $instance;
    }

    public static function fromTvc(string $tvc, ?string $publicKey = null, ?StateInitParams $initParams = null): self
    {
        $instance = new self(self::TYPE_TVC);
        $instance->setTvcParams($tvc, $publicKey, $initParams);

        return $instance;
    }

    /**
     * @param MessageSource $messageSource
     * @return $this
     */
    private function setMessageSource(MessageSource $messageSource): self
    {
        $this->messageSource = $messageSource;

        return $this;
    }

    private function setStateInitParams(string $code, string $data, ?string $library): self
    {
        $this->code = $code;
        $this->data = $data;
        $this->library = $library;

        return $this;
    }

    /**
     * @param string $tvc
     * @param string|null $publicKey
     * @param StateInitParams|null $initParams
     * @return $this
     */
    private function setTvcParams(string $tvc, ?string $publicKey, ?StateInitParams $initParams): self
    {
        $this->tvc = $tvc;
        $this->publicKey = $publicKey;
        $this->initParams = $initParams;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        if ($this->type === self::TYPE_MESSAGE) {
            return [
                'type'   => $this->type,
                'source' => $this->messageSource,
            ];
        }

        if ($this->type === self::TYPE_STATE_INIT) {
            return [
                'type'    => $this->type,
                'code'    => $this->code,
                'data'    => $this->data,
                'library' => $this->library,
            ];
        }

        if ($this->type === self::TYPE_TVC) {
            return [
                'type'        => $this->type,
                'tvc'         => $this->tvc,
                'public_key'  => $this->publicKey,
                'init_params' => $this->initParams,
            ];
        }

        throw new RuntimeException('Invalid data.');
    }
}
