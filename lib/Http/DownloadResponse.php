<?php

namespace NovaFrame\Http;

use NovaFrame\Http\Exceptions\FileRangeExceededException;
use NovaFrame\Http\Exceptions\PathNotFound;

class DownloadResponse extends Response
{
    /**
     * Absolute path to the file being downloaded.
     *
     * @var string
     */
    private string $filepath;

    /**
     * Filename to be sent to the client.
     *
     * @var string
     */
    private string $filename;

    /**
     * File size in bytes.
     *
     * @var string
     */
    private string $filesize;

    /**
     * DownloadResponse constructor.
     *
     * @param string $filepath Absolute path to the file on disk.
     * @param string $filename The filename to present to the user (optional).
     * @param int $status HTTP status code (default: 200).
     * @param array $headers Additional headers to send with the response.
     *
     * @throws PathNotFound If the file doesn't exist or can't be resolved.
     */
    public function __construct(
        string $filepath,
        string $filename = '',
        int $status = 200,
        array $headers = []
    )
    {
        $this->setFile($filepath, $filename);

        $headers = array_merge($headers, $this->getDownloadHeaders());

        parent::__construct(statusCode: $status, headers: $headers);
    }

    /**
     * Generates default headers for a file download.
     *
     * @return array<string, string>
     */
    private function getDownloadHeaders()
    {
        return [
            'Content-Description' => 'File Transfer',
            'Content-Type' => mime_content_type($this->filepath) ?: 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename=' . $this->filename,
            'Content-Transfer-Encoding' => 'binary',
            'Content-Length' => $this->filesize,
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
            'Expires' => '0',
        ];
    }

    /**
     * Sets internal file state for the download.
     *
     * @param string $filepath Absolute path to the file.
     * @param string $filename Optional custom filename.
     *
     * @return void
     *
     * @throws PathNotFound If file does not exist.
     */
    private function setFile(string $filepath, string $filename): void
    {
        $filepath = realpath($filepath);

        if ($filepath === false || !file_exists($filepath)) {
            throw new PathNotFound($filepath);
        }

        $this->filepath = $filepath;

        $this->filesize = filesize($filepath);

        $original_filename = basename($filepath);

        if (empty($filename)) {
            $filename = $original_filename;
        }

        if (empty(pathinfo($filename, PATHINFO_EXTENSION))) {
            $filename = $filename . '.' . pathinfo($this->filepath, PATHINFO_EXTENSION);
        }

        $this->filename = $filename;
    }

    /**
     * Sends the file to the client.
     * Supports partial content responses when HTTP_RANGE is present.
     *
     * @return void
     *
     * @throws FileRangeExceededException If the range is invalid.
     */
    public function send(): void
    {
        http_response_code($this->getStatusCode());

        $this->sendHeaders();

        $start = 0;
        $length = $this->filesize;

        if ($range = request()->server('HTTP_RANGE')) {
            list($start, $length) = $this->handleRangeHeader($range, $this->filesize);
        }

        $handle = fopen($this->filepath, 'rb');
        fseek($handle, $start);

        while ($length > 0 && !feof($handle)) {
            $chunk = fread($handle, min(1024 * 8, $length));
            echo $chunk;
            $this->flush();
            $length -= strlen($chunk);
        }

        fclose($handle);
    }

    /**
     * Handles HTTP range requests and updates response headers accordingly.
     *
     * @param string $range The HTTP_RANGE header.
     * @param int $filesize The total file size.
     *
     * @return array{0: int, 1: int} Tuple containing start position and length.
     *
     * @throws FileRangeExceededException If range is invalid or exceeds bounds.
     */
    private function handleRangeHeader(string $range, int $filesize)
    {
        $range = str_replace("byte=", '', $range);

        list($start, $end) = explode('-', $range, 2);

        $start = (int)$start;
        $end = $end === '' ? $filesize - 1 : (int)$end;

        if ($start > $end || $start >= $filesize || $end >= $filesize) {
            throw new FileRangeExceededException($start, $end, $filesize);
        }

        $this->setStatusCode(206);

        $this->setHeaders([
            'Content-Range' => "bytes $start-$end/$filesize",
            'Content-Length' => $end - $start + 1,
        ]);

        return [$start, $end - $start + 1];
    }
}
