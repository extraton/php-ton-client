<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\ParamsOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Net;

class NetTest extends AbstractModuleTest
{
    private Net $net;

    public function setUp(): void
    {
        parent::setUp();
        $this->net = $this->tonClient->getNet();
    }

    public function testQueryCollectionSuccessResult(): void
    {
        $query = new ParamsOfQueryCollection(
            'accounts',
            [
                'id',
                'last_paid',
                'balance',
                'due_payment'
            ],
            (new Filters())->add(
                'last_paid',
                Filters::IN,
                [
                    1601332024,
                    1601331924,
                    1601332491,
                    1601332679
                ]
            ),
            (new OrderBy())->add(
                'last_paid',
                OrderBy::DESC
            ),
            2
        );

        $expected = new ResultOfQueryCollection(
            new Response(
                [
                    'result' => [
                        [
                            'id'          => '0:d3ce35fb2a03e988890bbe1a8843a0b482e7938eb98a52b939e46f72cac2e8a7',
                            'last_paid'   => 1601331924,
                            'balance'     => '0x64',
                            'due_payment' => null,
                        ],
                        [
                            'id'          => '0:614e76067fa459e87a1f7423fda7065a359503b3a9b64a2da3f18d0292676b0a',
                            'last_paid'   => 1601332024,
                            'balance'     => '0x3b9aca63',
                            'due_payment' => null,
                        ],
                    ],
                ]
            )
        );

        self::assertEquals($expected, $this->net->queryCollection($query));
    }
}
