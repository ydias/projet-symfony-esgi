<?php

namespace App\Controller;

use App\Entity\Bug;
use App\Form\BugType;
use App\Repository\BugRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bug")
 */
class BugController extends AbstractController
{
    /**
     * @Route("/", name="bug_index", methods="GET")
     */
    public function index(BugRepository $bugRepository): Response
    {
        return $this->render('bug/index.html.twig', ['bugs' => $bugRepository->findAll()]);
    }

    /**
     * @Route("/new", name="bug_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $bug = new Bug();
        $form = $this->createForm(BugType::class, $bug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bug);
            $em->flush();

            return $this->redirectToRoute('bug_index');
        }

        return $this->render('bug/new.html.twig', [
            'bug' => $bug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bug_show", methods="GET")
     */
    public function show(Bug $bug): Response
    {
        return $this->render('bug/show.html.twig', ['bug' => $bug]);
    }

    /**
     * @Route("/{id}/edit", name="bug_edit", methods="GET|POST")
     */
    public function edit(Request $request, Bug $bug): Response
    {
        $form = $this->createForm(BugType::class, $bug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bug_index', ['id' => $bug->getId()]);
        }

        return $this->render('bug/edit.html.twig', [
            'bug' => $bug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bug_delete", methods="DELETE")
     */
    public function delete(Request $request, Bug $bug): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bug->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bug);
            $em->flush();
        }

        return $this->redirectToRoute('bug_index');
    }
}
