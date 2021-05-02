<?php

declare(strict_types=1);

namespace Pollen\Log;

use BadMethodCallException;
use Exception;
use Pollen\Support\Concerns\ConfigBagAwareTrait;
use Pollen\Support\Exception\ManagerRuntimeException;
use Pollen\Support\Proxy\ContainerProxy;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;
use Throwable;

/**
 * @mixin Logger
 */
class LogManager implements LogManagerInterface
{
    use ConfigBagAwareTrait;
    use ContainerProxy;

    /**
     * Instance principale.
     * @var static|null
     */
    private static $instance;


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

        if ($container !== null) {
            $this->setContainer($container);
        }
    }

    /**
     * Récupération de l'instance principale.
     *
     * @return static
     */
    public static function getInstance(): LogManagerInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new ManagerRuntimeException(sprintf('Unavailable [%s] instance', __CLASS__));
    }

    /**
     * Délégation d'appel des méthodes du canal de journalisation par défaut.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __call(string $method, array $arguments)
    {
        try {
            return $this->getDefault()->{$method}(...$arguments);
        } catch (Exception $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BadMethodCallException(
                sprintf(
                    'Default Logger method call [%s] throws an exception: %s',
                    $method,
                    $e->getMessage()
                ), 0, $e
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
    public function channel(?string $name = null): LoggerInterface
    {
        if ($name === null) {
            return $this->getDefault();
        }

        if (!isset($this->channels[$name])) {
            return $this->channels[$name];
        }

        throw new RuntimeException(sprintf('Log Channel [%s] unresolvable', $name));
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
        $channel = new Logger($name);
        $channel->setParams($params);

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
}