<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Binding\Binding;

class TonClient
{
    private array $configuration;

    private Binding $binding;

    private ?int $context = null;

    /**
     * @param array $configuration
     * @param Binding $binding
     */
    public function __construct(array $configuration, Binding $binding)
    {
        $this->configuration = $configuration;
        $this->binding = $binding;
    }

    /**
     * @return int
     */
    public function getContext(): int
    {
        if ($this->context === null) {
            $this->context = $this->binding->createContext($this->configuration);
        }

        return $this->context;
    }
}
