<?php

namespace Fsuuaas\LaravelPlupload;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class File
{
    /**
     * @var Illuminate\Http\Request
     */
    protected Illuminate\Http\Request $request;

    /**
     * @var Illuminate\Filesystem\Filesystem
     */
    protected Illuminate\Filesystem\Filesystem $storage;

    /**
     * @var int
     */
    private int $maxFileAge = 600; // 600 seconds

    /**
     * Create new class instance.
     *
     * @param Request $request
     * @param Filesystem $file
     * @return void
     */
    public function __construct(Request $request, Filesystem $file)
    {
        $this->request = $request;
        $this->storage = $file;
    }

    /**
     * Get chuck upload path.
     *
     * @return string
     */
    public function getChunkPath(): string
    {
        $path = config('plupload.chunk_path');

        if (! $this->storage->isDirectory($path)) {
            $this->storage->makeDirectory($path, 0777, true);
        }

        return $path;
    }

    /**
     * Process uploaded files.
     *
     * @param string $name
     * @param Closure $closure
     * @return array
     * @throws Exception
     */
    public function process(string $name, Closure $closure): array
    {
        $response = [];
        $response['jsonrpc'] = '2.0';

        if ($this->hasChunks()) {
            $result = $this->chunks($name, $closure);
        } else {
            $result = $this->single($name, $closure);
        }

        $response['result'] = $result;

        return $response;
    }

    /**
     * Handle single uploaded file.
     *
     * @param string $name
     * @param Closure $closure
     * @return mixed
     */
    public function single(string $name, Closure $closure): mixed
    {
        if ($this->request->hasFile($name)) {
            return $closure($this->request->file($name));
        }
        return null;
    }

    /**
     * Handle single uploaded file.
     *
     * @param string $name
     * @param Closure $closure
     * @return mixed
     * @throws Exception
     */
	    public function chunks(string $name, Closure $closure): mixed
        {
        $result = false;

        if ($this->request->hasFile($name)) {
            $file = $this->request->file($name);

            $chunk = (int) $this->request->get('chunk', false);
            $chunks = (int) $this->request->get('chunks', false);
            $originalName = $this->request->get('name');

            $filePath = $this->getChunkPath().'/'.$originalName.'.part';

            $this->removeOldData($filePath);
            $this->appendData($filePath, $file);

            if ($chunk == $chunks - 1) {
                $file = new UploadedFile($filePath, $originalName, 'blob', is_array($filePath) && count($filePath), UPLOAD_ERR_OK, true);
                $result = $closure($file);
                @unlink($filePath);
            }
        }

        return $result;
    }

    /**
     * Remove old chunks.
     *
     * @param string $filePath
     * @return void
     */
    protected function removeOldData(string $filePath): void
    {
        if ($this->storage->exists($filePath) && ($this->storage->lastModified($filePath) < time() - $this->maxFileAge)) {
            $this->storage->delete($filePath);
        }
    }

    /**
     * Merge chunks.
     *
     * @param string $filePathPartial
     * @param UploadedFile $file
     * @return void
     * @throws Exception
     */
    protected function appendData(string $filePathPartial, UploadedFile $file): void
    {
        if (! $out = @fopen($filePathPartial, 'ab')) {
            throw new Exception('Failed to open output stream.', 102);
        }

        if (! $in = @fopen($file->getPathname(), 'rb')) {
            throw new Exception('Failed to open input stream', 101);
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);
    }

    /**
     * Check if request has chunks.
     *
     * @return bool
     */
    public function hasChunks(): bool
    {
        return (bool) $this->request->input('chunks', false);
    }
}
