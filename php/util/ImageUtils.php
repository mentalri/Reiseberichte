<?php

namespace php\util;

class ImageUtils
{

    /**
     * Handles the upload of a picture, ensuring it meets size requirements and converts it to WebP format.
     *
     * @param array $file The uploaded file information from $_FILES.
     * @param int $minWidth Minimum width of the image.
     * @param int $minHeight Minimum height of the image.
     * @param int $maxWidth Maximum width of the image.
     * @param int $maxHeight Maximum height of the image.
     * @return string|null Returns an error message if there's an issue, or null if successful.
     */
    public static function handlePictureUpload(&$file, $minWidth = 128, $minHeight = 128, $maxWidth = 256, $maxHeight = 256): ?string
    {
        if (!isset($file) || $file["error"] != UPLOAD_ERR_OK) {
            return "missing_file";
        }

        $imageInfo = getimagesize($file["tmp_name"]);
        if ($imageInfo === false) {
            return "invalid_image";
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        if ($width < $minWidth || $height < $minHeight) {
            return "invalid_image_size";
        }

        // Load image resource
        switch ($mime) {
            case 'image/jpeg':
                $srcImage = imagecreatefromjpeg($file["tmp_name"]);
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($file["tmp_name"]);
                break;
            case 'image/gif':
                $srcImage = imagecreatefromgif($file["tmp_name"]);
                break;
            default:
                return "unsupported_image_type";
        }

        // Scale down if needed
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
            $dstImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG and GIF
            if ($mime === 'image/png' || $mime === 'image/gif') {
                imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);
            }

            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($srcImage);
            $srcImage = $dstImage;
        }

        // Save as WebP (overwrite tmp file)
        imagewebp($srcImage, $file["tmp_name"]);
        imagedestroy($srcImage);

        //update the file extension to .webp for later saving
        $file['name'] = pathinfo($file['name'], PATHINFO_FILENAME) . '.webp';

        return null; // No error
    }
}