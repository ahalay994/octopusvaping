<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Nette\Utils\Image;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

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

/**
 * @param Request $request
 * @param String $pathDir
 * @return array|bool
 */
function uploadImages(Request $request, String $pathDir)
{
    $images = [];
    try {
        if (!$request->file()) {
            return true;
        }

        foreach ($request->file() as $fieldName => $files) {
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $fullPath = public_path() . DIRECTORY_SEPARATOR . $pathDir;
                $file->move($fullPath, $fileName);
                ImageOptimizer::optimize($fullPath . $fileName);

                $images[$fieldName][] = $fileName;
            }
        }
    } catch (Exception $e) {
        return false;
    }

    return $images;
}

/**
 * @param Request $request
 * @param String $pathDir
 * @return array|bool
 */
function uploadImageTable(Request $request, String $pathDir, String $filename)
{
    $images = [];
    try {
        if (!$request->file()) {
            return true;
        }

        foreach ($request->file()[$filename] as $files) {
            foreach ($files as $nameId => $file) {
                $fileName = $file->getClientOriginalName();
                $fullPath = public_path() . DIRECTORY_SEPARATOR .$pathDir;
                $file->move($fullPath, $fileName);
                ImageOptimizer::optimize($fullPath . $fileName);

                $images[$nameId] = $fileName;
            }

        }
    } catch (Exception $e) {
        return false;
    }

    return $images;
}
