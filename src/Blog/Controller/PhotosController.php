<?php

namespace Blog\Controller;

use Silex\Application;

class PhotosController
{
    public function createAction(Application $app)
    {
        return sprintf("Hello from %s", __METHOD__);
    }

    public function searchAction(Application $app)
    {
        return sprintf("Hello from %s", __METHOD__);
    }

    public function showAction(Application $app)
    {
        return sprintf("Hello from %s", __METHOD__);
    }

    public function editAction(Application $app)
    {
        return sprintf("Hello from %s", __METHOD__);
    }

    public function deleteAction(Application $app)
    {
        return sprintf("Hello from %s", __METHOD__);
    }
}
