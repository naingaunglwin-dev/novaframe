<?php

namespace Nova\HTTP;

class DownloadResponse extends Response
{
    /**
     * The path to the file to be downloaded
     *
     * @var string
     */
    private string $filepath;

    /**
     * The size of the file to be downloaded
     *
     * @var string
     */
    private string $filesize;

    /**
     * @var IncomingRequest
     */
    private IncomingRequest $request;

    /**
     * Constructs a new DownloadResponse instance.
     *
     * @param string $filepath The path to the file to be downloaded.
     * @param string $filename The name of the file to be downloaded (optional).
     * @param int    $status   The HTTP status code of the response (default is 200).
     * @param array  $headers  The headers of the response (default is an empty array).
     */
    public function __construct(string $filepath, string $filename = '', int $status = 200, array $headers = [])
    {
        $this->filepath = realpath($filepath);

        if ($this->filepath === false || !file_exists($filepath)) {
            throw new \InvalidArgumentException("File does not exist: $filepath");
        }

        $this->filesize = filesize($this->filepath);

        $original = basename($this->filepath);

        if (empty($filename)) {
            $filename = $original;
        } elseif (count(explode('.', $filename)) < 2) {
            $filename = $filename.".".pathinfo($this->filepath, PATHINFO_EXTENSION);
        }

        $headers = array_merge($headers, [
            'Content-Description'       => 'File Transfer',
            'Content-Type'              => $this->detectMimeType($this->filepath),
            'Content-Disposition'       => 'attachment; filename="' . $filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Length'            => (string) $this->filesize,
            'Cache-Control'             => 'must-revalidate',
            'Pragma'                    => 'public',
            'Expires'                   => '0',
        ]);

        parent::__construct('', $status, $headers);

        $this->request = IncomingRequest::createFromGlobals();
    }

    /**
     * Send the download response to server
     *
     * @return void
     */
    public function download(): void
    {
        $start  = 0;
        $length = (int) $this->filesize;

        if ($range = $this->request->getFromServer("HTTP_RANGE")) {
            list($start, $length) = $this->handleRangeHeader($range, $this->filesize);
        }

        $this->setBody($this->output($start, $length));

        $this->send();
    }

    /**
     * Detects the MIME type of file.
     *
     * @param string $filepath The path to the file.
     * @return string The MIME type of the file.
     */
    private function detectMimeType(string $filepath): string
    {
        return mime_content_type($filepath) ?: 'application/octet-stream';
    }

    /**
     * Handles the Range header for partial content.
     *
     * @param string $range The range header value.
     * @param int    $filesize The size of the file.
     * @return array An array containing the start and length of the partial content.
     */
    private function handleRangeHeader(string $range, int $filesize): array
    {
        $range = str_replace("byte=", '', $range);

        list($start, $end) = explode("-", $range);

        $start = (int)$start;
        $end   = $end === '' ? $filesize - 1 : (int)$end;

        if ($start > $end || $start >= $filesize || $end >= $filesize) {
            throw new \InvalidArgumentException("Range $start to $end exceeded filesize: $filesize");
        }

        $this->setStatus(206);

        $this->setHeaders([
            'Content-Range'  => "bytes {$start}-{$end}/$filesize",
            'Content-Length' => (string)($end - $start + 1),
        ]);

        return [$start, $end - $start + 1];
    }

    /**
     * Outputs the file content
     *
     * @param int $start The start position of the file content.
     * @param int $length The length of the file content.
     * @return string
     */
    private function output(int $start, int $length): string
    {
        $handle = fopen($this->filepath, "rb");
        $result = '';

        if ($handle === false) {
            throw new \RuntimeException("Unable to open " . $this->filepath . " for reading");
        }

        fseek($handle, $start);

        while ($length > 0 && !feof($handle)) {
            $read = min(1024, $length);
            $result .= fread($handle, $read);
            $length -= $read;
        }

        fclose($handle);

        return $result;
    }
}
