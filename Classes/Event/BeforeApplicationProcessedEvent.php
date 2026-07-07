<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Event;

use ITX\Jobapplications\Domain\Model\Application;
use ITX\Jobapplications\Domain\Model\Posting;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

final class BeforeApplicationProcessedEvent
{
    private Application $application;

    private ?Posting $posting;

    private RequestInterface $request;

    private ?ResponseInterface $response = null;

    public function __construct(Application $application, ?Posting $posting, RequestInterface $request)
    {
        $this->application = $application;
        $this->posting = $posting;
        $this->request = $request;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function getPosting(): ?Posting
    {
        return $this->posting;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
