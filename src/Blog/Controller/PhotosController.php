<?php

namespace Blog\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class PhotosController
{
    public function createAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function searchAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function showAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function editAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function deleteAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }
}
