<?php

namespace App\Controller;

use Elasticsearch\ClientBuilder;
use PhpParser\Node\Scalar\MagicConst\Dir;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(): Response
    {
        return $this->render('login.html.twig');
    }
    /**
     * @Route("/", name="main")
     */
    public function main(): Response
    {

        return $this->render('home.html.twig');

    }
    /**
     * @Route ("/search", name="search")
     * @param Request $request
     */
    public function search(Request $request): Response{
        $arr = array();
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


        foreach($response['hits']['hits'] as $host) {
            array_push($arr, $host['_id']);
        }
        return $this->render('search.html.twig', ['search_results' => $arr, 'content'=>$content]);


    }
    /**
     * @Route ("/view", name="dada")
     * @param Request $request
     */

    public function displayFile(Request $request): Response{


        $name = $request->query->get('file');
        $pdf = file_get_contents("../var/uploads/$name.pdf");
        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename = '$name.pdf'"
        ]);
    }


}
