<?php

class ImageHandlerException extends Exception {}

class ImageHandler
{
    private const MAX_FILE_SIZE = 5242880; // 5 MB limit
    private const ALLOWED_TYPES = [
        'image/jpeg',  // JPEG images
        'image/png',   // PNG images
        'image/gif',   // GIF images
        'image/bmp',   // BMP images
        'image/tiff',  // TIFF images
        'image/webp',  // WebP images
        'image/svg+xml' // SVG images
    ];
    
    public static function prepareImageForStorage(string $temp_name, string $file_name, string $dir_to_save): array
    {
        // Validate the directory
        if (!self::validateDirectory($dir_to_save)) {
            throw new ImageHandlerException("Directory is invalid or not writable.");
        }

        // Validate the image file
        if (!self::validateImage($temp_name)) {
            throw new ImageHandlerException("Image validation failed. Please check the file type and size.");
        }

        // Generate a sanitized and unique file name
        $sanitizedFileName = self::sanitizeFileName($file_name);
        $uniqueFileName = self::generateUniqueFileName($sanitizedFileName);

        // Define the path to save the image
        $destination = rtrim($dir_to_save, '/') . '/' . $uniqueFileName;

        return [
            'name' => $uniqueFileName, 
            'destination' => $destination, 
            'temp_name' => $temp_name
        ];
    }

    private static function generateUniqueFileName(string $file_name): string
    {
        return uniqid(time() . "_") . strtolower($file_name);
    }

    private static function validateDirectory(string $dir_to_save): bool
    {
        return is_dir($dir_to_save) && is_writable($dir_to_save);
    }

    private static function validateImage(string $file_name): bool
    {
        // Check if the file exists and is readable
        if (!file_exists($file_name) || !is_readable($file_name)) {
            throw new ImageHandlerException("Image file does not exist or is not readable.");
        }

        // Check the file size
        if (filesize($file_name) > self::MAX_FILE_SIZE) {
            throw new ImageHandlerException("Image file exceeds the maximum allowed size of " . self::MAX_FILE_SIZE . " bytes.");
        }

        // Get image information
        $imageInfo = getimagesize($file_name);
        if (!$imageInfo) {
            throw new ImageHandlerException("File is not a valid image.");
        }

        // Check the MIME type
        if (!in_array($imageInfo['mime'], self::ALLOWED_TYPES)) {
            throw new ImageHandlerException("Image type not allowed. Allowed types are: " . implode(', ', self::ALLOWED_TYPES));
        }

        return true;
    }

    private static function sanitizeFileName(string $file_name): string
    {
        // Remove potentially harmful characters and spaces
        $file_name = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $file_name);
        return preg_replace('/\s+/', '_', $file_name);
    }
}
