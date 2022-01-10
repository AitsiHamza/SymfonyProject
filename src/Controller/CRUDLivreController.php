<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\Livre1Type;
use App\Repository\AuteurRepository;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\DatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/*
 * @Route("/c/r/u/d/livre")
 */
class CRUDLivreController extends AbstractController
{
    /**
     * @Route("/", name="c_r_u_d_livre_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository, GenreRepository $genreRepository, AuteurRepository $auteurRepository): Response
    {
        $livres = $livreRepository->findAll();
        return $this->render('crud_livre/index.html.twig', [
            'livres' => $livres,
            'genres' => $genreRepository->findAll(),
            'auteurs' => $auteurRepository->findAll(),
            'dates' => $livreRepository->findDates(),
        ]);
    }

    /**
     * @Route("/new", name="c_r_u_d_livre_new", methods={"GET", "POST"})
    * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(Livre1Type::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('c_r_u_d_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="c_r_u_d_livre_show", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        return $this->render('crud_livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="c_r_u_d_livre_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Livre1Type::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('c_r_u_d_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="c_r_u_d_livre_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('c_r_u_d_livre_index', [], Response::HTTP_SEE_OTHER);
    }

        /**
     * @Route("/livre/chercher/{motCle}", name="livre_chercher", methods={"GET"})
     */
    public function chercher(String $motCle, LivreRepository $livreRepository): Response
    {//, GenreRepository $genreRepository, AuteurRepository $auteurRepository
        $livres = $livreRepository->findByTitre($motCle);

        return $this->render('crud_livre/chercher.html.twig', [
            'livres' => $livres,
            //'genres' => $genreRepository->findAll(),
            //'auteurs' => $auteurRepository->findAll(),
            //'dates' => $datesRepository->findDates(),
        ]);
    }

    /**
     * @Route("/livre/liste-entre-deux-dates/{dateMin}/{dateMax}")
     */
    public function listeEntreDeuxDates($dateMin, $dateMax, LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findBetweenTwoDates(strval($dateMin), strval($dateMax));
        return $this->render('crud_livre/chercher.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livre/liste-par-note/{note}", name="livre_list_by_note", methods={"GET"})
     */
    public function listByNote(LivreRepository $livreRepository, $note): Response
    {
        $livres = $livreRepository->findByNote($note);
        return $this->render('crud_livre/chercher.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livre/liste-par-date/{date}/", name="livre_list_by_date", methods={"GET"})
     */
    public function listByDate(LivreRepository $livreRepository, $date): Response
    {
        $livres = $livreRepository->findByDate($date);
        return $this->render('crud_livre/chercher.html.twig', [
            'livres' => $livres,
        ]);
    }


    /**
     * @Route("/livre/liste-par-auteur/{auteur}/", name="livre_list_by_auteur", methods={"GET"})
     */
    public function listByAuteur(LivreRepository $livreRepository, $auteur): Response
    {
        $livres = $livreRepository->findByAuteur($auteur);
        return $this->render('crud_livre/chercher.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livre/liste-par-genre/{genre}/", name="livre_list_by_genre", methods={"GET"})
     */
    public function listByGenre(LivreRepository $livreRepository, $genre): Response
    {
        $livres =  $livreRepository->findByGenre($genre);

        return $this->render('crud_livre/chercher.html.twig', [
            'livres' => $livres,
        ]);
    }
}
