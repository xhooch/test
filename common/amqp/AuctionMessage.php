<?php

namespace common\amqp;

use yii\base\BaseObject;

class AuctionMessage extends BaseObject implements MessageInterface
{
    public int $userId;

    public int $itemId;

	public function getPayload(): string
    {
		return json_encode(
            [
                'userId' => $this->userId,
                'itemId' => $this->itemId,
            ]
        );
	}
}
