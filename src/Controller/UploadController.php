<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use Psr\Log\LoggerInterface;
use Elasticsearch\ClientBuilder;
use function Sodium\increment;



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
        $hosts = [
            'http://elasticsearch:9200',       // HTTP Basic Authentication
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $token  = $request->get("token");

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
        $final = str_replace(".pdf", "", $filename);
        $final = str_replace(" ", "", $final);
        $uploader->upload($uploadDir, $file, $final.'.pdf');

        $fullPath = $uploadDir. '/'.$final.'.pdf';
        $ch = curl_init();
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

        $jsonarray = array("Filename" => $filename, "Content" => $output);


        $jsonpath = '../var/json';
        if(!file_exists($jsonpath)){
            mkdir($jsonpath);
        }


        $fp = fopen($jsonpath.'/'.$final."json", 'w');
        fwrite($fp,json_encode($jsonarray));
        fclose($fp);
        $content = json_encode(($jsonarray));
        // close curl resource to free up system resources
        curl_close($ch);
        $params = [
            'index' => 'elasticsearch',
            'id'    =>  $final,
            'body'  => ['Content' => $content]
        ];

        $response = $client->index($params);
        print_r($response);
        //IMPORTING INTO ELASTICSEARCH
       /* $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,"http://elasticsearch/elasticsearch/pdffiles/".$final);
        curl_setopt($ch, CURLOPT_PORT, "9200");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        $response = curl_exec($ch);

        echo $response;
        curl_close($ch); */




        
        return new Response("File uploaded",  Response::HTTP_OK,
            ['content-type' => 'text/plain']);
    }




}