<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfIteratorNext
 */
class ResultOfIteratorNext extends AbstractResult
{
    /**
     * Next available items.
     *
     * @return list<mixed>
     */
    public function getItems(): array
    {
        return $this->requireArray('items');
    }

    /**
     * Indicates that there are more available items in iterated range.
     *
     * @return bool
     */
    public function hasMore(): bool
    {
        return $this->requireBool('has_more');
    }

    /**
     * Optional iterator state that can be used for resuming iteration.
     *
     * @return list<mixed>|mixed
     */
    public function getResumeState()
    {
        return $this->requireData('resume_state');
    }
}
