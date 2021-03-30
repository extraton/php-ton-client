<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Debot;

use Extraton\TonClient\Entity\Params;

/**
 * Type DebotAction
 */
class DebotAction implements Params
{
    private string $description;

    private string $name;

    private int $actionType;

    private int $to;

    private string $attributes;

    private string $misc;

    /**
     * Describes a debot action in a Debot Context.
     *
     * @param string $description A short action description. Should be used by Debot Browser as name of menu item.
     * @param string $name Depends on action type. Can be a debot function name or a print string (for Print Action).
     * @param int $actionType Action type.
     * @param int $to ID of debot context to switch after action execution.
     * @param string $attributes Action attributes. In the form of "param=value,flag". attribute example: instant, args, fargs, sign.
     * @param string $misc Some internal action data. Used by debot only.
     */
    public function __construct(
        string $description,
        string $name,
        int $actionType,
        int $to,
        string $attributes,
        string $misc
    ) {
        $this->description = $description;
        $this->name = $name;
        $this->actionType = $actionType;
        $this->to = $to;
        $this->attributes = $attributes;
        $this->misc = $misc;
    }

    public function jsonSerialize(): array
    {
        return [
            'description' => $this->description,
            'name'        => $this->name,
            'action_type' => $this->actionType,
            'to'          => $this->to,
            'attributes'  => $this->attributes,
            'misc'        => $this->misc,
        ];
    }
}
