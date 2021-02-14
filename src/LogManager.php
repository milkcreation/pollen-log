<?php

declare(strict_types=1);

namespace Pollen\Log;

use BadMethodCallException;
use Pollen\Support\Concerns\ConfigBagTrait;
use Pollen\Support\Concerns\ContainerAwareTrait;
use Psr\Container\ContainerInterface as Container;
use Throwable;

/**
 * @mixin Logger
 */
class LogManager implements LogManagerInterface
{
    use ConfigBagTrait;
    use ContainerAwareTrait;

    /**
     * @var LoggerInterface
     */
    protected $default;

    /**
     * @var LoggerInterface[]|array
     */
    protected $channels = [];

    /**
     * @param array $config
     * @param Container|null $container
     */
    public function __construct(array $config = [], ?Container $container = null)
    {
        $this->setConfig($config);

        if (!is_null($container)) {
            $this->setContainer($container);
        }
    }

    /**
     * @inheritDoc
     */
    public function __call(string $method, array $arguments)
    {
        try {
            return $this->getDefault()->{$method}(...$arguments);
        } catch (Throwable $e) {
            throw new BadMethodCallException(
                sprintf(
                    'Default Logger method call [%s] throws an exception: %s',
                    $method,
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function addChannel(LoggerInterface $channel): LogManagerInterface
    {
        $this->channels[$channel->getName()] = $channel;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function channel(string $name = null): ?LoggerInterface
    {
        if ($name === null) {
            return $this->getDefault();
        }

        return $this->channels[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getDefault(): LoggerInterface
    {
        if ($this->default === null) {
            $this->default = new Logger('default');

            if ($container = $this->getContainer()) {
                $this->default->setContainer($container);
            }
        }

        return $this->default;
    }

    /**
     * @inheritDoc
     */
    public function registerChannel(string $name, array $params = []): ?LoggerInterface
    {
        $channel = (new Logger($name))->setParams($params);

        if ($container = $this->getContainer()) {
            $channel->setContainer($container);
        }

        $this->addChannel($channel);

        return $channel;
    }

    /**
     * @inheritDoc
     */
    public function setDefault(LoggerInterface $default): LogManagerInterface
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = []): void
    {
        $this->getDefault()->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = []): void
    {
        $this->getDefault()->critical($message, $context);
    }


    /**
     * @inheritDoc
     */
    public function debug($message, array $context = []): void
    {
        $this->getDefault()->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = []): void
    {
        $this->getDefault()->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = []): void
    {
        $this->getDefault()->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info($message, array $context = []): void
    {
        $this->getDefault()->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        $this->getDefault()->log($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = []): void
    {
        $this->getDefault()->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message, array $context = []): void
    {
        $this->getDefault()->success($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = []): void
    {
        $this->getDefault()->warning($message, $context);
    }
}