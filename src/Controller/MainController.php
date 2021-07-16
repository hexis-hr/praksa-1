<?php

namespace App\Controller;

use App\Entity\User;
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
            array_push($result, substr($host['_id'], 0, strrpos($host['_id'], '__')));

            $file = "../var/uploads/". substr($host['_id'], 0, strrpos($host['_id'], '__')). ".pdf";

            if (!file_exists($file)) // in case a file was previously deleted
                return $this->redirectToRoute("search");

            $file_res = new BinaryFileResponse($file);
            $file_size =  $file_res->getFile()->getSize();
            $file_time =  $file_res->getLastModified();
            $u_uid = substr($host['_id'], strrpos($host['_id'], '__')+2);

            $u_user = $this->getDoctrine()->getRepository(User::class)->find($u_uid);

            array_push($result, $file_size);
            array_push($result, date_format($file_time, "r"));
            array_push($result, $u_user->getUserID());
            array_push($result,
                $u_user->getFirstName(). " ". $u_user->getLastName().
            " (". $u_user->getUsername() .")");
            array_push($arr, $result);

            $num++;
        }

        $cur_user_id = $this->getUser()->getUserID();

        //return new Response(var_dump($arr));

        return $this->render('search.html.twig', ['search_results' => $arr, 'content'=>$content,
            'num' => $num, 'cur_user_id' => $cur_user_id]);
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
     * @Route("manage_docs", name="manage_docs")
     */
    public function manage_docs() : Response
    {
        return new RedirectResponse($this->generateUrl("search"));
    }

    /**
     * @Route("manage_docs/delete/{doc}", name="delete_file")
     * @param Request $request
     */

    public function delete_file(Request $request, ?string $doc) : Response
    {
        if ($request->isMethod("GET")) {

            $jsonpath = '../var/json/';
            $uploadpath = '../var/uploads/';
            unlink($jsonpath. substr($doc, 0, strrpos($doc, '__')). "json");
            unlink($uploadpath. substr($doc, 0, strrpos($doc, '__')). ".pdf");

            $hosts = [
                'http://elasticsearch:9200',       // HTTP Basic Authentication
            ];
            $client = ClientBuilder::create()->setHosts($hosts)->build();
            $params = [
                'index' => 'elasticsearch',
                'id' => $doc
            ];

            $client->delete($params);

            $this->addFlash('success', 'Document deleted!');
        }

        return $this->redirectToRoute('manage_docs');
    }
}
