<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ProfesseursController;
use App\Repository\ProfesseursRepository;
use App\Entity\Professeurs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $professeursRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->professeursRepository = $this->createMock(ProfesseursRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->professeursController = new ProfesseursController($this->professeursRepository, $this->entityManager);
    }

    public function testGetProfesseurs()
    {
        $professeurs = [
            new Professeurs('1', 'Professeur 1'),
            new Professeurs('2', 'Professeur 2'),
        ];

        $this->professeursRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($professeurs);

        $response = $this->professeursController->getProfesseurs();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeurs), $response->getContent());
    }

    public function testGetProfesseurNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->professeursController->getProfesseur('1');
    }

    public function testGetProfesseur()
    {
        $professeur = new Professeurs('1', 'Professeur 1');

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $response = $this->professeursController->getProfesseur('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testPostProfesseur()
    {
        $professeur = new Professeurs('1', 'Professeur 1');

        $this->professeursRepository->expects($this->once())
            ->method('save')
            ->with($professeur)
            ->willReturn($professeur);

        $response = $this->professeursController->postProfesseur($professeur);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testPutProfesseur()
    {
        $professeur = new Professeurs('1', 'Professeur 1');

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $this->professeursRepository->expects($this->once())
            ->method('save')
            ->with($professeur)
            ->willReturn($professeur);

        $response = $this->professeursController->putProfesseur('1', $professeur);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($professeur), $response->getContent());
    }

    public function testDeleteProfesseur()
    {
        $professeur = new Professeurs('1', 'Professeur 1');

        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $this->professeursRepository->expects($this->once())
            ->method('remove')
            ->with($professeur);

        $response = $this->professeursController->deleteProfesseur('1');

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetProfesseurs`: Verifies that the `getProfesseurs` method returns a list of all professeurs.
- `testGetProfesseurNotFound`: Verifies that the `getProfesseur` method throws a `NotFoundHttpException` when the professeur is not found.
- `testGetProfesseur`: Verifies that the `getProfesseur` method returns a single professeur by ID.
- `testPostProfesseur`: Verifies that the `postProfesseur` method creates a new professeur and returns it.
- `testPutProfesseur`: Verifies that the `putProfesseur` method updates an existing professeur and returns it.
- `testDeleteProfesseur`: Verifies that the `deleteProfesseur` method removes a professeur by ID.

Note that this test file assumes that the `ProfesseursController` class has the following methods:

- `getProfesseurs`: Returns a list of all professeurs.
- `getProfesseur`: Returns a single professeur by ID.
- `postProfesseur`: Creates a new professeur and returns it.
- `putProfesseur`: Updates an existing professeur and returns it.
- `deleteProfesseur`: Removes a professeur by ID.

Also, this test file assumes that the `ProfesseursRepository` class has the following methods:

- `findAll`: Returns a list of all professeurs.
- `find`: Returns a single professeur by ID.
- `save`: Saves a professeur.
- `remove`: Removes a professeur.