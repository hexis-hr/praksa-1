<?php


/*
You can find more info on how to connect to Google Account on https://github.com/thephpleague/oauth2-google
*/
namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GoogleController extends AbstractController
{
    //Link to this controller to start the connect process
    /**
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry){
       //will redirect to Google
        return $clientRegistry
            ->getClient('google') //key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(['public_profile', 'email']); // the scopes you want to access
    }
    //After going to Google, you're redirected back here
    /**
     * @Route("/connect/google/ckeck", name="connect_google_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry ){
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client */
        $client = $clientRegistry->getClient('google');
        try {
            /** @var \League\OAuth2\Client\Provider\GoogleUser $user */
            $user = $client->fetchUser();

            var_dump($user); die;
        }catch (IdentityProviderException $e){


            //Something went wrong.
            var_dump($e->getMessage()); die;
        }
    }

}
