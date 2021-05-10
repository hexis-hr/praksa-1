<?php


//FOSElasticaBundle page with more info and how to use it:https://github.com/FriendsOfSymfony/FOSElasticaBundle
use App\Kernel;


class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new FOS\ElasticaBundle\FOSElasticaBundle(),

        ];

        // ...
    }
}