<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Services\GiftsService;

class DefaultController extends AbstractController
{
    public function __construct(GiftsService $gifts)
    {
        $gifts->gifts = ['a', 'b', 'c', 'd'];
    }

    /**
     * @Route("/default", name="default")
     */
    public function index(GiftsService $gifts): Response
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/DefaultController.php',
        // ]);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
            'random_gift' => $gifts->gifts,
        ]);
    }

    /**
     * Set user in the database
     * 
     * @Route("/default/set_user/{name}", name="default_set_user")
     */
    public function setname($name): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User;
        $user->setName($name);
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response("Add User Success!");
    }

    /**
     * Redirect
     * 
     * @Route("/default/redirect", name="default_redirect")
     */
    public function redirectto(): Response
    {
        return $this->redirect("http://www.youtube.com");
    }

    /**
     * Advanced Route
     * 
     * @Route("/default/blog/{page?}", name="default_blog_list", requirements={"page"="\d+"})
     */
    public function blog(): Response
    {
        return new Response("Optional parameters in url and requirements for parameters");
    }

    /**
     * Advanced Route
     * 
     * @Route(
     *  "/default/articles/{locale}/{year}/{slug}/{category}",
     *  name="default_article",
     *  defaults={"category": "computers"},
     *  requirements={
     *      "_locale": "en|fr",
     *      "category": "computers|rtv",
     *      "year": "\d+",
     *  }
     * )
     */
    public function article()
    {
        return new Response("An advanced route example");
    }

    /**
     * Advanced Route
     * 
     * @Route({
     *      "nl": "/default/over-ons",
     *      "en": "/default/about-us"
     * }, name="default_about_us")
     */
    public function about()
    {
        return new Response("Translated Routes");
    }

    /**
     * Redirect to named routes
     * 
     * @Route("/default/redirectroutes", name="default_redirect_routes")
     */
    public function redirectroutes(): Response
    {
        return $this->redirectToRoute("default");
    }

    /**
     * Route Parameter
     * 
     * @Route("/default/{name}", name="default_name")
     */
    public function name($name): Response
    {
        return new Response("Hello! $name");
    }
}
