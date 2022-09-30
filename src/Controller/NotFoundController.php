<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class NotFoundController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('error404/error404.html.twig');
    }
} 