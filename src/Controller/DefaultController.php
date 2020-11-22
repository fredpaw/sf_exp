<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;
use App\Services\GiftsService;

class DefaultController extends AbstractController
{
    public function __construct(GiftsService $gifts, $logger)
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

    /**
     * @Route("/generate-url/{param?}", name="generate_url")
     */
    public function generate_url()
    {
        exit($this->generateUrl(
            'generate_url',
            array('param' => 10),
            UrlGeneratorInterface::ABSOLUTE_URL // Optional
        ));
    }

    /**
     * @Route("/download", name="download")
     */
    public function download()
    {
        $path = $this->getParameter('download_directory');  // Get parameter from services.yaml
        return $this->file($path . 'file.pdf');
    }

    /**
     * @Route("/redirect-test", name="redirect-test")
     */
    public function redirectTest()
    {
        return $this->redirectToRoute('route_to_redirect', array('param' => 10));
    }

    /**
     * @Route("/url-to-redirect/{param?}", name="route_to_redirect")
     */
    public function methodToRedirect()
    {
        exit("Test redirection");
    }

    /**
     * @Route("/forwarding-to-controller", name="forwarding_to_controller")
     */
    public function forwardingToController()
    {
        $response = $this->forward(
            'App\Controller\DefaultController::methodToForwardTo',
            array('param' => '1')
        );
        return $response;
    }

    /**
     * @Route("/url-to-forward-to/{param?}", name="route-to-forward-to")
     */
    public function methodToForwardTo($param)
    {
        exit("Test controller forwarding " . $param);
    }
}
