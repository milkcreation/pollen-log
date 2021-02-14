<?php

declare(strict_types=1);

namespace Pollen\Log;

/**
 * @mixin Logger
 */
interface LogManagerInterface extends LoggerInterface
{
    /**
     * Délégation d'appel des méthodes du canal de journalisation par défaut.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments);

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
     * @return LoggerInterface|null
     */
    public function channel(string $name = null): ?LoggerInterface;

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