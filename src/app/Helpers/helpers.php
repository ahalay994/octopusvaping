<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Nette\Utils\Image;

/**
 * @param Request $request
 * @param String $pathDir
 * @return array|bool
 */
function uploadImage(Request $request, String $pathDir)
{
    $images = [];
    try {
        if (!$request->file()) {
            return true;
        }

        /*$request->validate([
            'file' => 'mimes:pdf,xlx,csv|max:2048',
        ]);*/

        foreach ($request->file() as $fieldName => $file) {
            $fileName = $file->getClientOriginalName();
            $fullPath = public_path() . DIRECTORY_SEPARATOR .$pathDir;
            $file->move($fullPath, $fileName);
            ImageOptimizer::optimize($fullPath . $fileName);

            $images[$fieldName] = $fileName;
        }
    } catch (Exception $e) {
        return false;
    }

    return $images;
}

//function upload(UploadedFile $picture)
//{
//    $original = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
//    $sanitize = preg_replace('/[^a-zA-Z0-9]+/', '-', $original);
//    $fileName = $sanitize . '.' . $picture->getClientOriginalExtension();
//    $destination = public_path() . DIRECTORY_SEPARATOR . 'uploads/courses';
//    $uploaded = $picture->move($destination, $fileName);
//    Image::make($uploaded)->fit(300, 300)->save($destination . '/300x300-' . $fileName);
//    return $fileName;
//}
