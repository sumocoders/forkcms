<?php

namespace Backend\Modules\Pages\Ajax;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Common\Core\Model;
use Common\Uri;
use Backend\Core\Engine\Base\AjaxAction;
use Backend\Core\Engine\Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * This action will enable you to upload files trough ajax.
 * It accepts both XmlHttp requests or file uploads using a form.
 *
 * Note that it only accepts single file uploads.
 * The type $_GET parameter will be used to determine which folder to upload in.
 */
class UploadFile extends AjaxAction
{
    private const ALLOWED_EXTENSIONS = [
        'apng',
        'avif',
        'gif',
        'jfif',
        'jpeg',
        'jpg',
        'pjp',
        'pjpeg',
        'png',
        'svg',
        'webp',
    ];

    public function execute(): void
    {
        $request = $this->getRequest();

        try {
            $fileName = $this->writeFile(
                $this->getFileContentFromRequest($request),
                $this->getFileNameFromRequest($request),
                $request->get('type')
            );
        } catch (Exception $backendCoreException) {
            $this->output(Response::HTTP_BAD_REQUEST, $backendCoreException->getMessage());

            return;
        }

        $this->output(Response::HTTP_OK, $fileName);
    }

    /**
     * Extracts the uploaded file from a request. It handles both XmlHttpRequest
     * uploads and form uploads (with files in the $_FILES global)
     *
     * @param Request $request
     *
     * @throws Exception When no file could be extracted
     *
     * @return string The content of the uploaded file
     */
    private function getFileContentFromRequest(Request $request): string
    {
        // ajax uploaders fallback to submitting a form with the file in the fields.
        $uploadedFiles = $request->files->all();
        if (count($uploadedFiles) === 1) {
            $file = array_values($uploadedFiles)[0];

            return file_get_contents($file->getPathname());
        }

        throw new Exception('The request doesn\'t contain one file.');
    }

    /**
     * Extracts the uploaded file name from a request. It handles both XmlHttpRequest
     * uploads and form uploads (with files in the $_FILES global)
     *
     * @param Request $request
     *
     * @throws Exception When no file could be extracted
     *
     * @return string The content of the uploaded file
     */
    private function getFileNameFromRequest(Request $request): string
    {
        // ajax uploaders fallback to submitting a form with the file in the fields.
        $uploadedFiles = $request->files->all();
        if (count($uploadedFiles) === 1) {
            $file = array_values($uploadedFiles)[0];

            return $file->getClientOriginalName();
        }

        throw new Exception('The request doesn\'t contain one file.');
    }

    /**
     * Writes some content to a file in a given folder
     *
     * @param string $content
     * @param string $fileName
     * @param string $destinationFolder
     *
     * @return string The filename of the written file.
     */
    private function writeFile(string $content, string $fileName, string $destinationFolder): string
    {
        $path = FRONTEND_FILES_PATH . '/Pages/' . basename($destinationFolder);

        // create the needed folder if it doesn't exist
        $filesystem = new Filesystem();
        if (!$filesystem->exists($path)) {
            $filesystem->mkdir($path);
        }

        // convert the filename to url friendly version
        $baseName = Uri::getUrl(pathinfo($fileName, PATHINFO_FILENAME)) . '_' . time();
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), self::ALLOWED_EXTENSIONS)) {
            throw new Exception('This is not an image.');
        }
        $fileName = $baseName . '.' . $extension;

        // generate a non-existing filename
        while ($filesystem->exists($path . '/' . $fileName)) {
            $baseName = Model::addNumber($baseName);
            $fileName = $baseName . '.' . $extension;
        }

        // save the content of the file
        $filesystem->dumpFile(
            $path . '/' . $fileName,
            $content
        );

        return $fileName;
    }
}
