<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Boc\ResultOfParse;

class Boc
{
    private TonClient $tonClient;

    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    public function parseMessage(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_message',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    public function parseTransaction(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_transaction',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    public function parseAccount(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_account',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    public function parseBlock(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_block',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }
}
