<?php

declare(strict_types=1);

namespace Pollen\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Pollen\Support\DateTime;
use RuntimeException;

trait LoggerDefaultHandlersTrait
{
    /**
     * Déclaration des instances de traitement par défaut de la journalisation.
     *
     * @return void
     */
    public function registerDefaultHandlers(): void
    {
        if (empty($this->defaultHandlers)) {
            $defaultHandler = (new RotatingFileHandler(
                $this->params('filename', $this->getName() . '.log'),
                $this->params('rotate', 10),
                $this->params('level', self::DEBUG)
            ))->setFormatter(
                new LineFormatter(
                    $this->params('format'), $this->params('date_format')
                )
            );

            static::setTimezone($this->params('timezone', (new DateTime())->getTimezone()));

            $this->pushHandler($defaultHandler);
        } else {
            foreach ($this->defaultHandlers as $defaultHandler) {
                $this->pushHandler($defaultHandler);
            }
        }
    }

    /**
     * Définition des instances de traitement par défaut de la journalisation.
     *
     * @param LoggerInterface[] $defaultHandlers
     *
     * @return static
     */
    public function setDefaultsHandlers(array $defaultHandlers): LoggerDefaultHandlersTrait
    {
        foreach ($defaultHandlers as $defaultHandler) {
            if (!$defaultHandler instanceof HandlerInterface) {
                throw new RuntimeException('DefaultHandler must be an instance of HandlerInterface');
            }
        }

        $this->defaultHandlers = $defaultHandlers;

        return $this;
    }
}