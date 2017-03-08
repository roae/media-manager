<?php
/**
 * Created by PhpStorm.
 * User: talv
 * Date: 02/10/16
 * Time: 18:44.
 */

namespace Roae\MediaManager\Contracts;

use Illuminate\Support\Collection;

interface UploadedFilesInterface
{
    /**
     * @return Collection
     */
    public function getUploadedFiles();
}
