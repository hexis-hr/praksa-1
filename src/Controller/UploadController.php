<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use Psr\Log\LoggerInterface;

class UploadController extends AbstractController
{
    /**
     * @Route("/doUpload", name="do-upload")
     * @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(Request $request, string $uploadDir,
                          FileUploader $uploader, LoggerInterface $logger): Response
    {
        $token = $request->get("token");

        if (!$this->isCsrfTokenValid('upload', $token))
        {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed",  Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        $file = $request->files->get('myfile');

        if (empty($file))
        {
            return new Response("No file specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename);
        $ch = curl_init();
        $fullPath = realpath($uploadDir. '/'.$filename);
        fopen($fullPath, 'r');
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($fullPath));
        curl_setopt($ch, CURLOPT_INFILE, fopen($fullPath, 'r'));
        // set url
        curl_setopt($ch, CURLOPT_URL, "http://tika/tika");
        curl_setopt($ch, CURLOPT_PORT, "9998");
        curl_setopt($ch, CURLOPT_HEADER, "Accept: text/plain");
        curl_setopt($ch, CURLOPT_PUT, 1);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        var_dump("Filename: " .$filename ." Output:". $output);
        die();
        // close curl resource to free up system resources
        curl_close($ch);


        
        return new Response("File uploaded",  Response::HTTP_OK,
            ['content-type' => 'text/plain']);
    }
}