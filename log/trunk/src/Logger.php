<?php

declare(strict_types=1);

namespace Pollen\Log;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger as BaseLogger;
use Pollen\Support\Concerns\ContainerAwareTrait;
use Pollen\Support\Concerns\ParamsBagTrait;


class Logger extends BaseLogger implements LoggerInterface
{
    use ContainerAwareTrait;
    use LoggerDefaultHandlersTrait;
    use ParamsBagTrait;

    /**
     * @var HandlerInterface[]|array
     */
    protected $defaultHandlers = [];

    /**
     * @inheritDoc
     */
    public function addRecord($level, $message, array $context = []): bool
    {
        if (!$this->getHandlers()) {
            $this->registerDefaultHandlers();
        }

        return parent::addRecord($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message, array $context = []): void
    {
        $this->notice($message, $context);
    }
}