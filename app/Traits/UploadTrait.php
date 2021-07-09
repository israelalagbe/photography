<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;

trait UploadTrait
{
    /**
     * @param UploadedFile $file
     * @param $directory
     * @return string
     * @throws NotFoundException
     */
    public function handleUpload(UploadedFile $file, $directory): string
    {
        $destinationPath = "/public/$directory";
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
        $uploadedFilePath = $file->storeAs($destinationPath, $fileNameToStore);
        if (!$uploadedFilePath) {
            throw new Exception("File not saved");
        }
        return "storage/$directory/" . $fileNameToStore;
    }
}
