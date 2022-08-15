<?php

/**
 * @author Kev
 * @param string $directory The directory where the files are localized (supports .png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .webp, .WEBP extensions).
 * @return array Image files array or empty array.
 */
function getImageFilesFromDirectory(string $directory): array
{
    $pattern = "{$directory}*.{[pP][nN][gG],[wW][eE][bB][pP],[jJ][pP][eE][gG],[jJ][pP][gG]}";

    $files = glob(
        pattern: $pattern,
        flags: GLOB_BRACE
    );

    if ($files === false) {
        throw new LogicException(
            message: 'glob() failed'
        );
    }

    return $files;
}

/**
 * @author Kev
 * @param array $files An array with Images files to rename with a specific prefix.
 * @param string $fileNamePrefix The prefix name.
 * @param string $outputDirectory The directory where to store the renamed files.
 * @return array An array with the new file names.
 */
function renameAllImagesFromDirectory(
    array $files,
    string $fileNamePrefix,
    string $outputDirectory
): array
{
    $fileNames = [];

    createDirectoryfNotExists(
        directory: $outputDirectory
    );

    foreach($files as $key => $filePath) {
        $fileExtension = strtolower(
            string: pathinfo(
                path: $filePath,
                flags: PATHINFO_EXTENSION
            )
        );

        $fileNameWithExtension = $fileNamePrefix . '-' . ($key + 1) . '.' . $fileExtension;

        $newFilePath = $outputDirectory . $fileNameWithExtension;

        $booleanResult = rename(
            from: $filePath,
            to: $newFilePath
        );

        if ($booleanResult === false) {
            throw new LogicException(
                message: 'rename() failed'
            );
        }

        $fileNames[] = $fileNameWithExtension;
    }

    return $fileNames;
}

/**
 * @author Kev
 * @param string $directory The directory to create if not exists.
 */
function createDirectoryfNotExists(string $directory): void
{
    if (
        is_dir(
            filename: $directory
        ) === true
    ) {
        return;
    }

    $booleanResult = mkdir(
        directory: $directory,
        permissions: 0755,
        recursive: true
    );

    if ($booleanResult === false) {
        throw new LogicException(
            message: 'mkdir() failed'
        );
    }
}
