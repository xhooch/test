<?php

namespace common\amqp;

interface MessageInterface
{
	public function getPayload(): string;
}
