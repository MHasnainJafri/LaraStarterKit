<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Upload a file to a specified disk.
     *
     * @param object $file The file from request
     * @param string $path The storage path
     * @param string $disk The storage disk (default: 'public')
     * @return string|false The stored file path or false on failure
     */
    public static function upload($file, $path = 'uploads', $disk = 'public')
    {
        return $file->store($path, $disk);
    }

    /**
     * Delete a file from a specified disk.
     *
     * @param string $filePath The path of the file to delete
     * @param string $disk The storage disk (default: 'public')
     * @return bool True if deleted, false if file does not exist
     */
    public static function delete($filePath, $disk = 'public')
    {
        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }
        return false;
    }

    /**
     * Get the full URL of a stored file.
     *
     * @param string $filePath The path of the file
     * @param string $disk The storage disk (default: 'public')
     * @return string|null The file URL
     */
    public static function getUrl($filePath, $disk = 'public')
    {
        return Storage::disk($disk)->url($filePath);
    }

    /**
     * Check if a file exists in storage.
     *
     * @param string $filePath The path of the file
     * @param string $disk The storage disk (default: 'public')
     * @return bool True if file exists, false otherwise
     */
    public static function exists($filePath, $disk = 'public')
    {
        return Storage::disk($disk)->exists($filePath);
    }

    /**
     * Move/Rename a file in storage.
     *
     * @param string $oldPath The current file path
     * @param string $newPath The new file path
     * @param string $disk The storage disk (default: 'public')
     * @return bool True on success, false otherwise
     */
    public static function move($oldPath, $newPath, $disk = 'public')
    {
        if (self::exists($oldPath, $disk)) {
            return Storage::disk($disk)->move($oldPath, $newPath);
        }
        return false;
    }


   public static function  handleFileUploads(array|Request $data, $path="uploads",$disk = 'public'):array
{
    // Convert Request to an array if necessary
    if ($data instanceof Request) {
        $data = $data->all();
    }

    foreach ($data as $key => $value) {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Store file and replace the original key with the file path
            $path =FileHelper::upload($value, $path , $disk);
            $data[$key] = $path;
        }
    }

    return $data;
}
}
