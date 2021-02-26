<?php

declare(strict_types=1);

namespace Pollen\Log;

use Pollen\Support\Proxy\ContainerProxyInterface;
use Psr\Log\LoggerInterface as BaseLoggerInterface;
use Pollen\Support\Concerns\ParamsBagAwareTraitInterface;

/**
 * @mixin \Monolog\Logger
 */
interface LoggerInterface extends BaseLoggerInterface, ContainerProxyInterface, ParamsBagAwareTraitInterface
{
    /**
     * Alias de création d'un message de succès.
     *
     * @param string $message Intitulé du message.
     * @param array $context Liste des données de contexte.
     *
     * @return void
     */
    public function success(string $message, array $context = []): void;
}