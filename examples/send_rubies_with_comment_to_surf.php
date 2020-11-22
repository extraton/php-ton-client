<?php

declare(strict_types=1);

use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

// Abi wallet json
$abiWalletJson = <<<JSON
{
  "ABI version": 2,
  "header": [
    "pubkey",
    "time",
    "expire"
  ],
  "functions": [
    {
      "name": "constructor",
      "inputs": [
        {
          "name": "owners",
          "type": "uint256[]"
        },
        {
          "name": "reqConfirms",
          "type": "uint8"
        }
      ],
      "outputs": []
    },
    {
      "name": "acceptTransfer",
      "inputs": [
        {
          "name": "payload",
          "type": "bytes"
        }
      ],
      "outputs": []
    },
    {
      "name": "sendTransaction",
      "inputs": [
        {
          "name": "dest",
          "type": "address"
        },
        {
          "name": "value",
          "type": "uint128"
        },
        {
          "name": "bounce",
          "type": "bool"
        },
        {
          "name": "flags",
          "type": "uint8"
        },
        {
          "name": "payload",
          "type": "cell"
        }
      ],
      "outputs": []
    },
    {
      "name": "submitTransaction",
      "inputs": [
        {
          "name": "dest",
          "type": "address"
        },
        {
          "name": "value",
          "type": "uint128"
        },
        {
          "name": "bounce",
          "type": "bool"
        },
        {
          "name": "allBalance",
          "type": "bool"
        },
        {
          "name": "payload",
          "type": "cell"
        }
      ],
      "outputs": [
        {
          "name": "transId",
          "type": "uint64"
        }
      ]
    },
    {
      "name": "confirmTransaction",
      "inputs": [
        {
          "name": "transactionId",
          "type": "uint64"
        }
      ],
      "outputs": []
    },
    {
      "name": "isConfirmed",
      "inputs": [
        {
          "name": "mask",
          "type": "uint32"
        },
        {
          "name": "index",
          "type": "uint8"
        }
      ],
      "outputs": [
        {
          "name": "confirmed",
          "type": "bool"
        }
      ]
    },
    {
      "name": "getParameters",
      "inputs": [],
      "outputs": [
        {
          "name": "maxQueuedTransactions",
          "type": "uint8"
        },
        {
          "name": "maxCustodianCount",
          "type": "uint8"
        },
        {
          "name": "expirationTime",
          "type": "uint64"
        },
        {
          "name": "minValue",
          "type": "uint128"
        },
        {
          "name": "requiredTxnConfirms",
          "type": "uint8"
        },
        {
          "name": "requiredUpdConfirms",
          "type": "uint8"
        }
      ]
    },
    {
      "name": "getTransaction",
      "inputs": [
        {
          "name": "transactionId",
          "type": "uint64"
        }
      ],
      "outputs": [
        {
          "components": [
            {
              "name": "id",
              "type": "uint64"
            },
            {
              "name": "confirmationsMask",
              "type": "uint32"
            },
            {
              "name": "signsRequired",
              "type": "uint8"
            },
            {
              "name": "signsReceived",
              "type": "uint8"
            },
            {
              "name": "creator",
              "type": "uint256"
            },
            {
              "name": "index",
              "type": "uint8"
            },
            {
              "name": "dest",
              "type": "address"
            },
            {
              "name": "value",
              "type": "uint128"
            },
            {
              "name": "sendFlags",
              "type": "uint16"
            },
            {
              "name": "payload",
              "type": "cell"
            },
            {
              "name": "bounce",
              "type": "bool"
            }
          ],
          "name": "trans",
          "type": "tuple"
        }
      ]
    },
    {
      "name": "getTransactions",
      "inputs": [],
      "outputs": [
        {
          "components": [
            {
              "name": "id",
              "type": "uint64"
            },
            {
              "name": "confirmationsMask",
              "type": "uint32"
            },
            {
              "name": "signsRequired",
              "type": "uint8"
            },
            {
              "name": "signsReceived",
              "type": "uint8"
            },
            {
              "name": "creator",
              "type": "uint256"
            },
            {
              "name": "index",
              "type": "uint8"
            },
            {
              "name": "dest",
              "type": "address"
            },
            {
              "name": "value",
              "type": "uint128"
            },
            {
              "name": "sendFlags",
              "type": "uint16"
            },
            {
              "name": "payload",
              "type": "cell"
            },
            {
              "name": "bounce",
              "type": "bool"
            }
          ],
          "name": "transactions",
          "type": "tuple[]"
        }
      ]
    },
    {
      "name": "getTransactionIds",
      "inputs": [],
      "outputs": [
        {
          "name": "ids",
          "type": "uint64[]"
        }
      ]
    },
    {
      "name": "getCustodians",
      "inputs": [],
      "outputs": [
        {
          "components": [
            {
              "name": "index",
              "type": "uint8"
            },
            {
              "name": "pubkey",
              "type": "uint256"
            }
          ],
          "name": "custodians",
          "type": "tuple[]"
        }
      ]
    },
    {
      "name": "submitUpdate",
      "inputs": [
        {
          "name": "codeHash",
          "type": "uint256"
        },
        {
          "name": "owners",
          "type": "uint256[]"
        },
        {
          "name": "reqConfirms",
          "type": "uint8"
        }
      ],
      "outputs": [
        {
          "name": "updateId",
          "type": "uint64"
        }
      ]
    },
    {
      "name": "confirmUpdate",
      "inputs": [
        {
          "name": "updateId",
          "type": "uint64"
        }
      ],
      "outputs": []
    },
    {
      "name": "executeUpdate",
      "inputs": [
        {
          "name": "updateId",
          "type": "uint64"
        },
        {
          "name": "code",
          "type": "cell"
        }
      ],
      "outputs": []
    },
    {
      "name": "getUpdateRequests",
      "inputs": [],
      "outputs": [
        {
          "components": [
            {
              "name": "id",
              "type": "uint64"
            },
            {
              "name": "index",
              "type": "uint8"
            },
            {
              "name": "signs",
              "type": "uint8"
            },
            {
              "name": "confirmationsMask",
              "type": "uint32"
            },
            {
              "name": "creator",
              "type": "uint256"
            },
            {
              "name": "codeHash",
              "type": "uint256"
            },
            {
              "name": "custodians",
              "type": "uint256[]"
            },
            {
              "name": "reqConfirms",
              "type": "uint8"
            }
          ],
          "name": "updates",
          "type": "tuple[]"
        }
      ]
    }
  ],
  "data": [],
  "events": [
    {
      "name": "TransferAccepted",
      "inputs": [
        {
          "name": "payload",
          "type": "bytes"
        }
      ],
      "outputs": []
    }
  ]
}
JSON;

// Abi transfer (add comment)
$abiTransferJson = <<<JSON
{
  "ABI version": 2,
  "functions": [
    {
      "name": "transfer",
      "id": "0x00000000",
      "inputs": [
        {
          "name": "comment",
          "type": "bytes"
        }
      ],
      "outputs": []
    }
  ],
  "events": [],
  "data": []
}
JSON;

// Sender
$sender = '0:516bb1069529cb0c980d370390d53351b4578cefffc542a232beb24fa85250d5';
$senderPublicKey = 'd61ef5cd2b92f78d5313ed0811e94de234040877eb98ed2113a5366e8371facc';
$senderPrivateKey = '6bb92729c575d7ed1c121fe81ce695e18485b9c3e1653c699ad47f940373ec4a';

// Receiver
$receiverAddress = '0:c9b0168e734446da8ab7adb60ddf153f04bed283c2539073cddf34aab9d110bb';

// Message
$message = 'Hi, how are you?';

$tonClient = TonClient::createDefault();

$abiTransfer = AbiType::fromJson($abiTransferJson);
$signerUnknown = Signer::fromNone();

// Generate message body with comment
$resultOfEncodeMessageBody = $tonClient->getAbi()->encodeMessageBody(
    $abiTransfer,
    $signerUnknown,
    $callSet = (new CallSet('transfer'))->withInput(
        [
            'comment' => bin2hex($message),
        ]
    ),
    true
);

$messageBody = $resultOfEncodeMessageBody->getBody();

$abiWallet = AbiType::fromJson($abiWalletJson);
$keyPair = new KeyPair($senderPublicKey, $senderPrivateKey);
$signer = Signer::fromKeyPair($keyPair);

// Send rubies with comment
$resultOfProcessMessage = $tonClient->getProcessing()->processMessage(
    $abiWallet,
    $signer,
    null,
    $callSet = (new CallSet('submitTransaction'))->withInput(
        [
            'dest'       => $receiverAddress,
            'value'      => 5_000_000,
            'bounce'     => true,
            'allBalance' => false,
            'payload'    => $messageBody,
        ]
    ),
    $sender,
    null,
    true
);

echo 'Events:' . PHP_EOL;
foreach ($resultOfProcessMessage->getIterator() as $event) {
    var_dump($event->getResponseData());
}

echo 'Result:' . PHP_EOL;
var_dump($resultOfProcessMessage->getResponseData());

echo 'Finished!' . PHP_EOL;
