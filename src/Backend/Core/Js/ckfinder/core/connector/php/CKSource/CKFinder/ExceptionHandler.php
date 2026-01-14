<?php

/*
 * CKFinder
 * ========
 * http://cksource.com/ckfinder
 * Copyright (C) 2007-2016, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

namespace CKSource\CKFinder;

use CKSource\CKFinder\Exception\CKFinderException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use CKSource\CKFinder\Response\JsonResponse;
use \Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The exception handler class.
 *
 * All errors are resolved here and passed to the response.
 *
 * @copyright 2016 CKSource - Frederico Knabben
 */
class ExceptionHandler implements EventSubscriberInterface
{
    public function __construct(
        protected Translator $translator,
        protected bool $debug = false,
        protected ?LoggerInterface $logger = null,
    )
    {
        if ($this->debug) {
            set_error_handler($this->errorHandler(...));
        }
    }

    public function onCKFinderError(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $exceptionCode = $exception->getCode() ?: Error::UNKNOWN;

        $replacements = [];

        $httpStatusCode = 200;

        if ($exception instanceof CKFinderException) {
            $replacements = $exception->getParameters();
            $httpStatusCode = $exception->getHttpStatusCode();
        }

        $message =
            $exceptionCode === Error::CUSTOM_ERROR
                ? $exception->getMessage()
                : $this->translator->translateErrorMessage($exceptionCode, $replacements);

        $response = JsonResponse::create()->withError($exceptionCode, $message);

        $event->setException(new HttpException($httpStatusCode));

        $event->setResponse($response);

        if ($this->debug && $this->logger) {
            $this->logger->error($exception);
        }

        if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
            throw $exception;
        }
    }

    /**
     * Custom error handler to catch all errors in the debug mode.
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     *
     * @throws \Exception
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $wrapperException = new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        $this->logger->warning($wrapperException);

        if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
            throw $wrapperException;
        }
    }

    /**
     * Returns all events and callbacks.
     *
     * @see <a href="http://api.symfony.com/2.5/Symfony/Component/EventDispatcher/EventSubscriberInterface.html">EventSubscriberInterface</a>
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => ['onCKFinderError', -255]];
    }
}
