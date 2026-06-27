<?php

namespace App\Services;

use ZipArchive;

class ZipService
{

    public function comprimir(
        $xmlPath,
        $zipPath
    )
    {

        $zip = new ZipArchive();

        if (

            $zip->open(
                $zipPath,
                ZipArchive::CREATE
            ) === true

        ) {

            $zip->addFile(

                $xmlPath,

                basename($xmlPath)

            );

            $zip->close();

            return true;
        }

        return false;
    }
}