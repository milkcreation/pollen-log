<?php

declare(strict_types=1);

namespace Pollen\Log;

use Psr\Log\LoggerInterface as BaseLoggerInterface;

/**
 * @mixin \Monolog\Logger
 */
interface LoggerInterface extends BaseLoggerInterface
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