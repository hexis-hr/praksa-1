<?php

namespace App\Controller;

use Elasticsearch\ClientBuilder;
//use Imagick;
use PhpParser\Node\Scalar\MagicConst\Dir;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function main(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
            return new RedirectResponse($this->generateUrl("dashboard"));

        return new RedirectResponse($this->generateUrl("app_login"));
    }

    /**
     * @Route("/dashboard", name="dashboard");
     */
    public function dashboard() : Response
    {
        $firstname = $this->getUser()->getFirstName();

        return $this->render('home.html.twig', ['firstname' => $firstname]);
    }

    /**
     * @Route ("/search", name="search")
     * @param Request $request
     */
    public function search(Request $request): Response
    {
        $arr = array();
        $num = 0;
        $hosts = [
            'http://elasticsearch:9200',       // HTTP Basic Authentication
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $content = $request->get('search');

        $param = [
            "index" => "elasticsearch",
            "body" => [
                "query" => [
                    "regexp" => [
                        "Content" => [
                            "value" => ".*$content.*",
                            "flags" => "ALL",
                            "case_insensitive" => true
                        ]
                    ]
                ]
            ]
        ];
        $response = $client->search($param);

        //return new Response(var_dump($response));

        foreach($response['hits']['hits'] as $host) {

            $result = array();
            array_push($result, $host['_id']);

            //array_push($arr[], $host['_id']);

            $file = "../var/uploads/". $host['_id']. ".pdf";
            $file_res = new BinaryFileResponse($file);
            $file_size =  $file_res->getFile()->getSize();
            $file_time =  $file_res->getLastModified();

            array_push($result, $file_size);
            array_push($result, date_format($file_time, "r"));
            array_push($arr, $result);

            $num++;
        }

        //return new Response(var_dump($arr));

        return $this->render('search.html.twig', ['search_results' => $arr, 'content'=>$content,
            'num' => $num]);
    }

    /**
     * @Route ("/view", name="dada")
     * @param Request $request
     */
    public function displayFile(Request $request): Response
    {
        $name = $request->query->get('file');
        $pdf = file_get_contents("../var/uploads/$name.pdf");
        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename = '$name.pdf'"
        ]);
    }

    /**
     * @Route ("/preview", name="preview")
     * @param Request $request
     */
    public function previewFile(Request $request) : Response
    {
       $file = "../var/uploads/thumbnails/". $request->query->get('file'). ".jpg";

        // TODO: Implement thumbnail generation
        /*if (!file_exists($file)) {
            $image = new Imagick($file);
            $image->thumbnailImage($file);
        }*/

        return new BinaryFileResponse($file);
    }

    /**
     * @Route("/manage_docs", name="manage_docs")
     */
    // TODO: Implement function to manage documents
    public function manage_docs() : Response
    {
        return $this->render('manage_docs.html.twig');
    }
}
