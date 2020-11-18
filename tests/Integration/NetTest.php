<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\ParamsOfQueryCollection;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ParamsOfWaitForCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfWaitForCollection;
use Extraton\TonClient\Handler\Response;

use function dechex;
use function hexdec;

/**
 * Integration tests for Net module
 *
 * @coversDefaultClass \Extraton\TonClient\Net
 */
class NetTest extends AbstractModuleTest
{
    /**
     * @covers ::queryCollection
     */
    public function testQueryCollectionWithSuccessResult(): void
    {
        $query = new ParamsOfQueryCollection(
            'accounts',
            [
                'acc_type',
                'acc_type_name',
                'balance',
                'boc',
                'code',
                'code_hash',
                'data',
                'data_hash',
                'due_payment',
                'id',
                'last_paid',
                'last_trans_lt',
                'library',
                'library_hash',
                'proof',
                'split_depth',
                'state_hash',
                'tick',
                'tock',
                'workchain_id',
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
                            'acc_type'      => 1,
                            'acc_type_name' => 'Active',
                            'balance'       => '0x1ce2514f',
                            'boc'           => 'te6ccgECEwEAAtEAAm/ADId8FgqEJCa1Vsdp+TLZEhoncccTpAcAfhxPo5dfdbyiJoTRAvuTLjgAAAABJnJrCQc4lFPTQAYBAWGAAAC6a27GIgAAAAAADbugc+/+2LU7xqxMmxYy7bFgYjpjDDXnlBWCM7RIv4CUf7MgAgIDzyAFAwEB3gQAA9AgAEHeff/bFqd41YmTYsZdtiwMR0xhhrzygrBGdokX8BKP9mQCJv8A9KQgIsABkvSg4YrtU1gw9KEJBwEK9KQg9KEIAAACASAMCgH+/38h1SDHAZFwjhIggQIA1yHXC/8i+QFTIfkQ8qjiItMf0z81IHBwcO1E0PQEATQggQCA10WY0z8BM9M/ATKWgggbd0Ay4nAjJrmOJCX4I4ED6KgkoLmOF8glAfQAJs8LPyPPCz8izxYgye1UfzIw3t4FXwWZJCLxQAFfCtsw4AsADIA08vBfCgIBIBANAQm8waZuzA4B/nDtRND0BAEyINaAMu1HIm+MI2+MIW+MIO1XXwRwaHWhYH+6lWh4oWAx3u1HbxHXC/+68uBk+AD6QNN/0gAwIcIAIJcwIfgnbxC53vLgZSIiInDIcc8LASLPCgBxz0D4KM8WJM8WI/oCcc9AcPoCcPoCgEDPQPgjzwsfcs9AIMkPABYi+wBfBV8DcGrbMAIBSBIRAOu4iQAnXaiaBBAgEFrovk5gHwAdqPkQICAZ6Bk6DfGAPoCLLfGdquAmDh2o7eJQCB6B3lFa4X/9qOQN4iYAORl/+ToN6j2q/ajkDeJZHoALBBjgMcIGDhnhZ/BBA27oGeFn7jnoMrnizjnoPEAt4jni2T2qjg1QAMrccCHXSSDBII4rIMAAjhwj0HPXIdcLACDAAZbbMF8H2zCW2zBfB9sw4wTZltswXwbbMOME2eAi0x80IHS7II4VMCCCEP////+6IJkwIIIQ/////rrf35bbMF8H2zDgIyHxQAFfBw==',
                            'code'          => 'te6ccgECDQEAAjAAAib/APSkICLAAZL0oOGK7VNYMPShAwEBCvSkIPShAgAAAgEgBgQB/v9/IdUgxwGRcI4SIIECANch1wv/IvkBUyH5EPKo4iLTH9M/NSBwcHDtRND0BAE0IIEAgNdFmNM/ATPTPwEyloIIG3dAMuJwIya5jiQl+COBA+ioJKC5jhfIJQH0ACbPCz8jzws/Is8WIMntVH8yMN7eBV8FmSQi8UABXwrbMOAFAAyANPLwXwoCASAKBwEJvMGmbswIAf5w7UTQ9AQBMiDWgDLtRyJvjCNvjCFvjCDtV18EcGh1oWB/upVoeKFgMd7tR28R1wv/uvLgZPgA+kDTf9IAMCHCACCXMCH4J28Qud7y4GUiIiJwyHHPCwEizwoAcc9A+CjPFiTPFiP6AnHPQHD6AnD6AoBAz0D4I88LH3LPQCDJCQAWIvsAXwVfA3Bq2zACAUgMCwDruIkAJ12omgQQIBBa6L5OYB8AHaj5ECAgGegZOg3xgD6Aiy3xnargJg4dqO3iUAgegd5RWuF//ajkDeImADkZf/k6Deo9qv2o5A3iWR6ACwQY4DHCBg4Z4WfwQQNu6BnhZ+456DK54s456DxALeI54tk9qo4NUADK3HAh10kgwSCOKyDAAI4cI9Bz1yHXCwAgwAGW2zBfB9swltswXwfbMOME2ZbbMF8G2zDjBNngItMfNCB0uyCOFTAgghD/////uiCZMCCCEP////6639+W2zBfB9sw4CMh8UABXwc=',
                            'code_hash'     => '98196905d4f1d250741ab885ac2411e0a547c72486f613d8cb5f302fd9d51c6a',
                            'data'          => 'te6ccgEBBQEAZQABYYAAALprbsYiAAAAAAANu6Bz7/7YtTvGrEybFjLtsWBiOmMMNeeUFYIztEi/gJR/syABAgPPIAQCAQHeAwAD0CAAQd59/9sWp3jViZNixl22LAxHTGGGvPKCsEZ2iRfwEo/2ZA==',
                            'data_hash'     => 'd78b821f710e32a6d7cc1b7a690b2a31297b680176d5073e4d16a7c89d884edb',
                            'due_payment'   => null,
                            'id'            => '0:c877c160a842426b556c769f932d9121a2771c713a407007e1c4fa3975f75bca',
                            'last_paid'     => 1601332679,
                            'last_trans_lt' => '0x499c9ac2',
                            'library'       => null,
                            'library_hash'  => null,
                            'proof'         => null,
                            'split_depth'   => null,
                            'state_hash'    => null,
                            'tick'          => null,
                            'tock'          => null,
                            'workchain_id'  => 0,
                        ],
                        [
                            'acc_type'      => 1,
                            'acc_type_name' => 'Active',
                            'balance'       => '0x333ce8',
                            'boc'           => 'te6ccgECEwEAAtAAAm3AARr5TMA8P6uudzCT7DR4nrQJx/ya9M4u0yIK1Ws2HbNCJoTPAvuTKFgAAAABEyKSCMzPOhNABgEBYYAAALprbAqnAAAAAAANu6BajrFNmwBL12UpotMDhMAjwVmMXw+w2KhbEUPDb66cKCACAgPPIAUDAQHeBAAD0CAAQdtR1imzYAl67KU0WmBwmAR4KzGL4fYbFQtiKHht9dOFBAIm/wD0pCAiwAGS9KDhiu1TWDD0oQkHAQr0pCD0oQgAAAIBIAwKAf7/fyHVIMcBkXCOEiCBAgDXIdcL/yL5AVMh+RDyqOIi0x/TPzUgcHBw7UTQ9AQBNCCBAIDXRZjTPwEz0z8BMpaCCBt3QDLicCMmuY4kJfgjgQPoqCSguY4XyCUB9AAmzws/I88LPyLPFiDJ7VR/MjDe3gVfBZkkIvFAAV8K2zDgCwAMgDTy8F8KAgEgEA0BCbzBpm7MDgH+cO1E0PQEATIg1oAy7Ucib4wjb4whb4wg7VdfBHBodaFgf7qVaHihYDHe7UdvEdcL/7ry4GT4APpA03/SADAhwgAglzAh+CdvELne8uBlIiIicMhxzwsBIs8KAHHPQPgozxYkzxYj+gJxz0Bw+gJw+gKAQM9A+CPPCx9yz0AgyQ8AFiL7AF8FXwNwatswAgFIEhEA67iJACddqJoEECAQWui+TmAfAB2o+RAgIBnoGToN8YA+gIst8Z2q4CYOHajt4lAIHoHeUVrhf/2o5A3iJgA5GX/5Og3qPar9qOQN4lkegAsEGOAxwgYOGeFn8EEDbugZ4WfuOegyueLOOeg8QC3iOeLZPaqODVAAytxwIddJIMEgjisgwACOHCPQc9ch1wsAIMABltswXwfbMJbbMF8H2zDjBNmW2zBfBtsw4wTZ4CLTHzQgdLsgjhUwIIIQ/////7ogmTAgghD////+ut/fltswXwfbMOAjIfFAAV8H',
                            'code'          => 'te6ccgECDQEAAjAAAib/APSkICLAAZL0oOGK7VNYMPShAwEBCvSkIPShAgAAAgEgBgQB/v9/IdUgxwGRcI4SIIECANch1wv/IvkBUyH5EPKo4iLTH9M/NSBwcHDtRND0BAE0IIEAgNdFmNM/ATPTPwEyloIIG3dAMuJwIya5jiQl+COBA+ioJKC5jhfIJQH0ACbPCz8jzws/Is8WIMntVH8yMN7eBV8FmSQi8UABXwrbMOAFAAyANPLwXwoCASAKBwEJvMGmbswIAf5w7UTQ9AQBMiDWgDLtRyJvjCNvjCFvjCDtV18EcGh1oWB/upVoeKFgMd7tR28R1wv/uvLgZPgA+kDTf9IAMCHCACCXMCH4J28Qud7y4GUiIiJwyHHPCwEizwoAcc9A+CjPFiTPFiP6AnHPQHD6AnD6AoBAz0D4I88LH3LPQCDJCQAWIvsAXwVfA3Bq2zACAUgMCwDruIkAJ12omgQQIBBa6L5OYB8AHaj5ECAgGegZOg3xgD6Aiy3xnargJg4dqO3iUAgegd5RWuF//ajkDeImADkZf/k6Deo9qv2o5A3iWR6ACwQY4DHCBg4Z4WfwQQNu6BnhZ+456DK54s456DxALeI54tk9qo4NUADK3HAh10kgwSCOKyDAAI4cI9Bz1yHXCwAgwAGW2zBfB9swltswXwfbMOME2ZbbMF8G2zDjBNngItMfNCB0uyCOFTAgghD/////uiCZMCCCEP////6639+W2zBfB9sw4CMh8UABXwc=',
                            'code_hash'     => '98196905d4f1d250741ab885ac2411e0a547c72486f613d8cb5f302fd9d51c6a',
                            'data'          => 'te6ccgEBBQEAZQABYYAAALprbAqnAAAAAAANu6BajrFNmwBL12UpotMDhMAjwVmMXw+w2KhbEUPDb66cKCABAgPPIAQCAQHeAwAD0CAAQdtR1imzYAl67KU0WmBwmAR4KzGL4fYbFQtiKHht9dOFBA==',
                            'data_hash'     => 'eae01044ea0e092b3516943cd18108f73e05971323c4a4812de18f2309b64cf9',
                            'due_payment'   => null,
                            'id'            => '0:11af94cc03c3fabae773093ec34789eb409c7fc9af4ce2ed3220ad56b361db34',
                            'last_paid'     => 1601332491,
                            'last_trans_lt' => '0x44c8a482',
                            'library'       => null,
                            'library_hash'  => null,
                            'proof'         => null,
                            'split_depth'   => null,
                            'state_hash'    => null,
                            'tick'          => null,
                            'tock'          => null,
                            'workchain_id'  => 0,
                        ],
                    ],
                ]
            )
        );
        self::assertEquals($expected, $this->net->queryCollection($query));
    }

    /**
     * @covers ::waitForCollection
     */
    public function testWaitForCollectionSuccessResult(): void
    {
        $query = new ParamsOfWaitForCollection(
            'accounts',
            [
                'acc_type',
                'acc_type_name',
                'balance',
                'boc',
                'code',
                'code_hash',
                'data',
                'data_hash',
                'due_payment',
                'id',
                'last_paid',
                'last_trans_lt',
                'library',
                'library_hash',
                'proof',
                'split_depth',
                'state_hash',
                'tick',
                'tock',
                'workchain_id',
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
            30_000
        );

        $expected = new ResultOfWaitForCollection(
            new Response(
                [
                    'result' => [
                        'acc_type'      => 0,
                        'acc_type_name' => 'Uninit',
                        'balance'       => '0x64',
                        'boc'           => 'te6ccgEBAQEANQAAZcANPONfsqA+mIiQu+GohDoLSC55OOuYpSuTnkb3LKwuinICU8L7kxagAAAAANkzBwhZBA==',
                        'code'          => null,
                        'code_hash'     => null,
                        'data'          => null,
                        'data_hash'     => null,
                        'due_payment'   => null,
                        'id'            => '0:d3ce35fb2a03e988890bbe1a8843a0b482e7938eb98a52b939e46f72cac2e8a7',
                        'last_paid'     => 1601331924,
                        'last_trans_lt' => '0x364cc1c2',
                        'library'       => null,
                        'library_hash'  => null,
                        'proof'         => null,
                        'split_depth'   => null,
                        'state_hash'    => null,
                        'tick'          => null,
                        'tock'          => null,
                        'workchain_id'  => 0,
                    ],
                ]
            )
        );

        self::assertEquals($expected, $this->net->waitForCollection($query));
    }

    /**
     * @covers ::subscribeCollection
     * @covers ::unsubscribe
     */
    public function testSubscribeCollectionSuccessResult(): void
    {
        $minBalanceDelta = 1_000;

        $query = new ParamsOfSubscribeCollection(
            'transactions',
            [
                'id',
                'status',
                'status_name',
                'block_id',
                'account_addr',
                'lt',
                'prev_trans_hash',
                'prev_trans_lt',
                'now',
                'balance_delta',
            ],
            (new Filters())->add(
                'balance_delta',
                Filters::GT,
                '0x' . dechex($minBalanceDelta)
            )
        );

        $result = $this->net->subscribeCollection($query);
        self::assertFalse($result->isFinished());
        self::assertGreaterThan(0, $result->getHandle());

        // Event saver (for manual check event data)
        $saver = $this->eventSaver->getSaver(__METHOD__);

        $counter = 0;
        foreach ($result->getIterator() as $event) {
            // Save event data to dump file (tests/Integration/artifacts/*.txt)
            $saver->send($event->getResult());

            $eventResult = $event->getResult();

            self::assertNotEmpty($eventResult['id']);
            self::assertNotEmpty($eventResult['status']);
            self::assertNotEmpty($eventResult['status_name']);
            self::assertNotEmpty($eventResult['block_id']);
            self::assertNotEmpty($eventResult['account_addr']);
            self::assertNotEmpty($eventResult['lt']);
            self::assertNotEmpty($eventResult['prev_trans_hash']);
            self::assertNotEmpty($eventResult['prev_trans_lt']);
            self::assertNotEmpty($eventResult['now']);
            self::assertNotEmpty($eventResult['balance_delta']);
            self::assertGreaterThan($minBalanceDelta, hexdec($eventResult['balance_delta']));

            if (++$counter > 3) {
                $this->net->unsubscribe($result->getHandle());
                // or call: $result->stop();
            }
        }

        self::assertTrue($result->isFinished());
    }
}
