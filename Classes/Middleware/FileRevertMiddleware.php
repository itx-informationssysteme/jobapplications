<?php

namespace ITX\Jobapplications\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use ITX\Jobapplications\Utility\UploadFileUtility;

class FileRevertMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $path = rtrim($request->getUri()->getPath(), '/');
        if ($request->getMethod() === 'DELETE' && str_ends_with($path, '/jobapplications-revert')) {
            $response = new HtmlResponse('');
            $body = $request->getBody();
            if (strlen($body) === 23) {
                $utility = new UploadFileUtility();
                $utility->deleteFolder($body);
            }

            return $response;
        }

        return $handler->handle($request);
    }
}
