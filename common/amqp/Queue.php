<?php

namespace common\amqp;

use yii\base\BaseObject;

class Queue extends BaseObject
{
    public const DEFAULT_NAME = 'queue1';

	public string $name;

	public bool $passive;

	public bool $durable;

	public bool $exclusive;

	public bool $auto_delete;

	public bool $nowait;

	public array|null $arguments;

	public int|null $ticket;
}
