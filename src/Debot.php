<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\App\AppInterface;
use Extraton\TonClient\Entity\Debot\DebotAction;
use Extraton\TonClient\Entity\Debot\RegisteredDebot;
use Extraton\TonClient\Exception\TonException;

/**
 * Module debot
 */
class Debot extends AbstractModule
{
    /**
     * Start is equivalent to fetch + switch to context 0.
     *
     * @param string $address Debot smart contract address
     * @param AppInterface $app Debot Browser callbacks. Called by debot engine to communicate with debot browser.
     * @return RegisteredDebot
     * @throws TonException
     */
    public function start(string $address, AppInterface $app): RegisteredDebot
    {
        return new RegisteredDebot(
            $this->tonClient->request(
                'debot.start',
                [
                    'address' => $address,
                ],
                $app
            )->wait()
        );
    }

    /**
     * Fetches debot from blockchain.
     * Downloads debot smart contract (code and data) from blockchain and creates an instance of Debot Engine for it.
     *
     * @param string $address Debot smart contract address
     * @param AppInterface $app Debot Browser callbacks. Called by debot engine to communicate with debot browser.
     * @return RegisteredDebot
     * @throws TonException
     */
    public function fetch(string $address, AppInterface $app): RegisteredDebot
    {
        return new RegisteredDebot(
            $this->tonClient->request(
                'debot.fetch',
                [
                    'address' => $address,
                ],
                $app
            )->wait()
        );
    }

    /**
     * Fetches debot from blockchain.
     * Downloads debot smart contract (code and data) from blockchain and creates an instance of Debot Engine for it.
     *
     * @param int $debotHandle Debot handle which references an instance of debot engine.
     * @param DebotAction $debotAction Debot Action that must be executed.
     * @return void
     * @throws TonException
     */
    public function execute(int $debotHandle, DebotAction $debotAction): void
    {
        $this->tonClient->request(
            'debot.execute',
            [
                'debot_handle' => $debotHandle,
                'action'       => $debotAction,
            ]
        )->wait();
    }

    /**
     * Sends message to Debot.
     * Used by Debot Browser to send response on Dinterface call or from other Debots.
     *
     * @param int $debotHandle Debot handle which references an instance of debot engine.
     * @param string $message BOC of internal message to debot encoded in base64 format.
     * @return void
     * @throws TonException
     */
    public function send(int $debotHandle, string $message): void
    {
        $this->tonClient->request(
            'debot.send',
            [
                'debot_handle' => $debotHandle,
                'message'      => $message,
            ]
        )->wait();
    }

    /**
     * Destroys debot handle.
     * Removes handle from Client Context and drops debot engine referenced by that handle.
     *
     * @param int $debotHandle Debot handle which references an instance of debot engine.
     * @param string $debotAbi Debot abi as json string.
     * @return void
     * @throws TonException
     */
    public function remove(int $debotHandle, string $debotAbi): void
    {
        $this->tonClient->request(
            'debot.remove',
            [
                'debot_handle' => $debotHandle,
                'debot_abi'    => $debotAbi,
            ]
        )->wait();
    }
}
