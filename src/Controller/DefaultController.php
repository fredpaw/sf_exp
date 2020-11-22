<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        // if($users)
        // {
        //     throw $this->createNotFoundException("The users do not exist");
        // }

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
     * Set Cookie
     * 
     * @Route("/default/set_cookie", name="default_cookie")
     */
    public function setCookie()
    {
        $cookie = new Cookie(
            "my_cookie",    // Cookie name
            "cookie value", // Cookie value
            time() + (2 * 365 * 24 * 60 * 60)   // Expires after 2 years
        );

        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();

        return new Response("Cookie set");
    }

    /**
     * Clear Cookie
     * 
     * @Route("/default/clear_cookie", name="default_clear_cookie")
     */
    public function clearCookie()
    {
        $res = new Response();
        $res->headers->clearCookie('my_cookie');
        $res->send();

        return new Response("Cookie Cleared!");
    }

    /**
     * Get Cookie Data
     * 
     * @Route("/default/cookie", name="default_set_cookie")
     */
    public function getCookie(Request $request)
    {
        $cookie = $request->cookies->get("my_cookie");
        return new Response($cookie);
    }

    /**
     * Session
     * 
     * @Route("/default/session", name="default_session")
     */
    public function setSession(SessionInterface $session)
    {
        $session->set('name', 'session value');
        if($session->has('name'))
        {
            exit($session->get('name'));
        }
        // $session->remove('name');
        // $session->clear();
    }

    /**
     * @Route("/default/get", name="default_get")
     */
    public function getMethod(Request $request)
    {
        exit($request->query->get('page', 'default'));  // get query data
        exit($request->server->get('HTTP_HOST'));   // display server data
        $request->isXmlHttpRequest();   // is it an Ajax request?
        $request->request->get('page'); // get post data using request
        $request->files->get('foo');    // get upload file
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
     * Flash Message
     * 
     * @Route("/default/flash", name="default_flash")
     */
    public function flash()
    {
        $this->addFlash(
            "notice",
            "Your changes were saved!"
        );

        $this->addFlash(
            "warning",
            "Your changes were saved!"
        );

        return $this->render("default/flash.html.twig");
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
