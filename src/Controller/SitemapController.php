<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class SitemapController extends AbstractController
{

    public function index(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>

        <loc>https://www.agat-avia.ru/page/main_page</loc>

        <lastmod>2022-05-02</lastmod>

        <priority>0.8</priority>

    </url>


    <url>

        <loc>https://www.agat-avia.ru/page/contacts</loc>

        <lastmod>2022-05-02</lastmod>

    </url>


</urlset>
';
        $response = new Response($xml);
        $response->headers->set('Content-Type', 'xml');
        return $response;
    }

}
