<?php
/**
 * Created by PhpStorm.
 * User: talv
 * Date: 02/10/16
 * Time: 18:59.
 */

namespace Roae\MediaManager\Http;

use Illuminate\Support\Collection;
use Roae\MediaManager\Contracts\UploadedFilesInterface;

class UploadedFiles extends Collection implements UploadedFilesInterface
{
    /**
     * @return Collection
     */
    public function getUploadedFiles()
    {
        return $this;
    }
}
