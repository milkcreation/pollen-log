<?php

declare(strict_types=1);

namespace Pollen\Log;

use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;

/**
 * @mixin Logger
 */
interface LogManagerInterface extends ConfigBagAwareTraitInterface, ContainerProxyInterface
{
    /**
     * Ajout d'un canal de journalisation.
     *
     * @param LoggerInterface $channel
     *
     * @return static
     */
    public function addChannel(LoggerInterface $channel): LogManagerInterface;

    /**
     * Récupération d'un canal de journalisation.
     *
     * @param string|null $name
     *
     * @return LoggerInterface
     */
    public function channel(?string $name = null): LoggerInterface;

    /**
     * Récupération du canal de journalisation par défaut.
     *
     * @return LoggerInterface
     */
    public function getDefault(): LoggerInterface;

    /**
     * Déclaration d'un canal de journalisation.
     *
     * @param string $name
     * @param array $params Liste des paramètres de configuration.
     *
     * @return LoggerInterface|null
     */
    public function registerChannel(string $name, array $params = []): ?LoggerInterface;

    /**
     * Définition du canal de journalisation par défaut.
     *
     * @param LoggerInterface $default
     *
     * @return static
     */
    public function setDefault(LoggerInterface $default): LogManagerInterface;
}