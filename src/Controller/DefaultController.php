<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Swapi;

/**
 * Default Controller
 * 
 * @Route("/swapi-front")
 */
class DefaultController extends AbstractController
{
	private $doctrine;

	public function __construct(
		ManagerRegistry $doctrine
    ) {
		$this->doctrine 	= $doctrine;
    }


	/**
	 * Main EntryPoint of our application
	 * 
	 * @Route("/", name="test_index", methods={"GET"})
	 * 
	 * @param Request   $request
	 * 
	 * @return Response
	 */
	public function indexAction(Request $request)
	{
		$items = null;
		return $this->render('default/index.html.twig', [
			'items' 	=> $items,
			'controller_name' 	=> 'default controller'
		]);
	}

	/**
	 * Main EntryPoint of our application
	 * 
	 * @Route("/test", name="just_test", methods={"GET"})
	 * 
	 * @param Request   $request
	 * 
	 * @return Response
	 */
	public function test(Request $request, Swapi $swapi)
	{
		$items = null;

		dump($swapi->getSwapiData());
		die;
	}


}
