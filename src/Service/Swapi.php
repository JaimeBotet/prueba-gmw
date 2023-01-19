<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Movie;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
Use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;

class Swapi extends AbstractController
{

    protected $om;
	private $doctrine;
	private $url;
	private $mr;
    
    public function __construct(
        ObjectManager $om,
		MovieRepository $mr,
		ManagerRegistry $doctrine
    ) {
        $this->om 			= $om;
		$this->url 			= 'http://swapi.dev/api/';
		$this->doctrine 	= $doctrine;
		$this->mr 			= $mr;
    }

	public function getSwapiData()
	{	
		$response = [
			'status' 	=> 'OK',
			'data' 		=> null,
			'error' 	=> null
		];

		$movies = $this->getSwapiMovies();
		$characters = $this->getSwapiCharacters();
		if($this->processMovies($movies) == null )  {
			$response['status'] = 'KO';
			$response['error'] = 'Error processing movies';
		}
		if($this->processCharacters($characters) == null) {
			$response['status'] = 'KO';
			$response['error'] = 'Error processing characters';
		} 

		return $response;
	}

	private function getSwapiMovies()
	{
		$url = $this->url . 'films/';
		$movies = json_decode($this->request('GET', $url),true);
		$movies = $movies['results'];

		return $movies;
	}

	private function getSwapiCharacters()
	{	
		$url = $this->url . 'people/';
		$totalCharacters = [];

		for($i =0; $i<3;$i++) {
			$characters = json_decode($this->request('GET', $url),true);
			$url = $characters['next']; 
			$characters = $characters['results'];
			$totalCharacters = array_merge($totalCharacters, $characters);
		}

		return $totalCharacters;
	}

	private function processMovies($movies)
	{
		$em = $this->doctrine->getManager();
		foreach($movies as $movie) {
			$newMovie = new Movie;
			$newMovie->setName($movie['title']);

			$em->persist($newMovie);
			$em->flush();
		}
	}

	private function processCharacters($characters)
	{
		$em = $this->doctrine->getManager();

		foreach($characters as $character) {
			$newCharacter = new Character;
			$newCharacter->setHeight($character['height']);
			$newCharacter->setGender($character['gender']);
			$newCharacter->setName($character['name']);
			$newCharacter->setPicture('some picture');
			$newCharacter->setMass($character['mass']);

			// here for each Character we add the films into de colection of movies
			foreach($character['films'] as $film) {
				$foundFilm = json_decode($this->request('GET', $film),true);
				$movieToAdd = $this->mr->findOneByName($foundFilm['title']);
				$newCharacter->addMovie($movieToAdd);
			}

			$em->persist($newCharacter);
			$em->flush();
		}
	}

    private function request($method, $url)
    {
        $client = new Client();
		$request = new Request($method, $url);

        try {

            $response = $client->send($request);

            $responseBody = (string) $response->getBody();

			error_log(json_encode($response));
            
            return $responseBody;

        } catch (GuzzleException $e) {

            $error = $e->getMessage();

            return $error;
        }
    }
}