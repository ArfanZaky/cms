<?php

/*
|--------------------------------------------------------------------------
| Documentation for this config :
|--------------------------------------------------------------------------
| online  => http://unisharp.github.io/laravel-filemanager/config
| offline => vendor/unisharp/laravel-filemanager/docs/config.md
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
     */

    'use_package_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Shared folder / Private folder
    |--------------------------------------------------------------------------
    |
    | If both options are set to false, then shared folder will be activated.
    |
     */

    'allow_private_folder' => true,

    // Flexible way to customize client folders accessibility
    // If you want to customize client folders, publish tag="lfm_handler"
    // Then you can rewrite userField function in App\Handler\ConfigHandler class
    // And set 'user_field' to App\Handler\ConfigHandler::class
    // Ex: The private folder of user will be named as the user id.
    'private_folder_name' => UniSharp\LaravelFilemanager\Handlers\ConfigHandler::class,

    'allow_shared_folder' => false,

    'shared_folder_name' => 'shares',

    /*
    |--------------------------------------------------------------------------
    | Folder Names
    |--------------------------------------------------------------------------
     */

    'folder_categories' => [
        'file' => [
            'folder_name' => 'files',
            'startup_view' => 'list',
            'max_size' => 100000,
            'thumb' => false,
            'thumb_width' => 80,
            'thumb_height' => 80,
            'valid_mime' => [
                'text/plain',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'image/gif',
                'image/svg+xml',
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/webp',
                'audio/mpeg',
                'audio/webm',
                'video/mp4',
                'video/mpeg',
                'video/webm',
            ],
        ],
        'document' => [
            'folder_name' => 'documents',
            'startup_view' => 'grid',
            'max_size' => 100000,
            'thumb' => false,
            'thumb_width' => 80,
            'thumb_height' => 80,
            'valid_mime' => [
                'text/plain',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ],
        ],
        'image' => [
            'folder_name' => 'images',
            'startup_view' => 'grid',
            'max_size' => 100000,
            'thumb' => false,
            'thumb_width' => 80,
            'thumb_height' => 80,
            'valid_mime' => [
                'image/gif',
                'image/svg+xml',
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/webp',
            ],
        ],
        'video' => [
            'folder_name' => 'videos',
            'startup_view' => 'grid',
            'max_size' => 100000,
            'thumb' => false,
            'thumb_width' => 80,
            'thumb_height' => 80,
            'valid_mime' => [
                'audio/mpeg',
                'audio/webm',
                'video/mp4',
                'video/mpeg',
                'video/webm',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
     */

    'paginator' => [
        'perPage' => 40,
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload / Validation
    |--------------------------------------------------------------------------
     */

    'disk' => 'public',

    'rename_file' => false,

    'rename_duplicates' => false,

    'alphanumeric_filename' => true,

    'alphanumeric_directory' => true,

    'should_validate_size' => true,

    'should_validate_mime' => true,

    // behavior on files with identical name
    // setting it to true cause old file replace with new one
    // setting it to false show `error-file-exist` error and stop upload
    'over_write_on_duplicate' => false,

    // mimetypes of executables to prevent from uploading
    'disallowed_mimetypes' => ['text/x-php', 'text/html', 'text/plain'],

    // Item Columns
    'item_columns' => ['name', 'url', 'time', 'icon', 'is_file', 'is_image', 'thumb_url'],

    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
     */

    // If true, image thumbnails would be created during upload
    'should_create_thumbnails' => false,

    'thumb_folder_name' => 'thumbs',

    // Create thumbnails automatically only for listed types.
    'raster_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/webp',
    ],

    'thumb_img_width' => 100,

    'thumb_img_height' => 100,

    /*
    |--------------------------------------------------------------------------
    | File Extension Information
    |--------------------------------------------------------------------------
     */

    'file_type_array' => [
        'txt' => 'Text',
        'pdf' => 'Adobe Acrobat',
        'doc' => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls' => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'ppt' => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
        'zip' => 'Archive',
        'rar' => 'Archive',
        'gif' => 'GIF Image',
        'svg' => 'SVG Image',
        'jpg' => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png' => 'PNG Image',
        'webp' => 'WEBP Image',
        'mp3' => 'MP3 Audio',
        'weba' => 'WEBM Audio',
        'mp4' => 'MP4 Video',
        'mpeg' => 'MPEG Video',
        'webm' => 'WEBM Video',
    ],

    /*
    |--------------------------------------------------------------------------
    | php.ini override
    |--------------------------------------------------------------------------
    |
    | These values override your php.ini settings before uploading files
    | Set these to false to ingnore and apply your php.ini settings
    |
    | Please note that the 'upload_max_filesize' & 'post_max_size'
    | directives are not supported.
     */
    'php_ini_overrides' => [
        'max_execution_time' => '3600',
        'max_input_time' => '3600',
        'memory_limit' => '1024M',
        'post_max_size' => '1024M',
        'upload_max_filesize' => '1024M',
    ],
];
