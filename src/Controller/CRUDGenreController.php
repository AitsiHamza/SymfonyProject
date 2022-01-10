<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\Genre1Type;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/c/r/u/d/genre")
 */
class CRUDGenreController extends AbstractController
{
    /**
     * @Route("/", name="c_r_u_d_genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('crud_genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="c_r_u_d_genre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $genre = new Genre();
        $form = $this->createForm(Genre1Type::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('c_r_u_d_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="c_r_u_d_genre_show", methods={"GET"})
     */
    public function show(Genre $genre): Response
    {
        return $this->render('crud_genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="c_r_u_d_genre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Genre1Type::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('c_r_u_d_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="c_r_u_d_genre_delete", methods={"POST"})
     */
    public function delete(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('c_r_u_d_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
