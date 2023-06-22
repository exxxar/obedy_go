<?php

namespace App\Actions;

class ImageStoreAction
{

    public function __invoke($image, $path, $filename): string
    {
        $fileName = $filename . '.' . $image->extension();
        $image->storeAs($path, $fileName, 'public');
        return "/storage/" . $path . "/" . $fileName;
    }

}
