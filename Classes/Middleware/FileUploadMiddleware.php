<?php

namespace ITX\Jobapplications\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Http\HtmlResponse;
use ITX\Jobapplications\Utility\UploadFileUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class FileUploadMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if ($request->getMethod() === 'POST' && $request->getUri()->getPath() == '/jobapplications_upload') {
            $response = new HtmlResponse('');

            $fileSizeLimit = 0;
            $errorBit = false;
            $responseContent = '';


            $extconf = GeneralUtility::makeInstance(ExtensionConfiguration::class);
            $extConfLimit = $extconf->get('jobapplications', 'customFileSizeLimit');
            $allowedFileTypesString = $extconf->get('jobapplications', 'allowedFileTypes');
            $allowedFileTypes = $allowedFileTypesString !== '' ? explode(',', $allowedFileTypesString) : [];

            if (count($request->getUploadedFiles()) > 1) {
                throw new \RuntimeException('Endpoint only accepts one file per request');
            }

            if ($extConfLimit !== '') {
                $fileSizeLimit = (int)$extConfLimit > (int)GeneralUtility::getMaxUploadFileSize() ?
                    GeneralUtility::getMaxUploadFileSize() : (int)$extConfLimit;
            } else {
                $fileSizeLimit = (int)GeneralUtility::getMaxUploadFileSize();
            }

            if (empty($request->getUploadedFiles())) {
                $responseContent = 'file_error';
                $errorBit = true;
            }

            foreach ($request->getUploadedFiles()['tx_jobapplications_applicationform'] as $uploadType => $file) {
                // In case upload field is multiple file field
                if (is_array($file)) {
                    $file = $file[0];
                    if ($file === null) {
                        throw new \RuntimeException('Could not find upload file.');
                    }
                }

                // Check against allowed filetypes
                if (!empty($allowedFileTypes) && !in_array('.' . pathinfo($file->getClientFilename())['extension'], $allowedFileTypes, true)) {
                    $responseContent = 'file_type';
                    $errorBit = true;
                    break;
                }

                // Check against size limit
                if ($file->getSize() > $fileSizeLimit * 1024) {
                    $responseContent = 'file_size';
                    $errorBit = true;
                    break;
                }

                // Check against php file errors
                if ($file->getError() !== 0) {
                    $responseContent = 'file_error';
                    $errorBit = true;
                    break;
                }

                $uploadFileUtil = new UploadFileUtility();
                $uniqueFileIdentifier = $uploadFileUtil->handleFile($file);

                $responseContent = $uniqueFileIdentifier;
            }

            $response->getBody()->write($responseContent);

            return $response->withStatus($errorBit ? 400 : 200);
        }

        return $handler->handle($request);
    }
}
