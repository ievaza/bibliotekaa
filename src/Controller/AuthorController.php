<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'author_index', methods: ['GET'])]
    public function index(): Response
    {
        $authors = $this->getDoctrine()->
        getRepository(Author::class)->
        findAll();

        
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }
    #[Route('/author/create', name: 'author_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('author/create.html.twig');

    }
    #[Route('/author/store', name:'author_store', methods:['POST'])]
    public function store(Request $r): Response
    {
        $author = new Author;
        $author->
        setName($r->request->get('author_name'))->
        setSurname($r->request->get('author_surname'));

        $enitytManager = $this->getDoctrine()->getManager();
        $enitytManager->persist($author);
        $enitytManager->flush();

        return $this->redirectToRoute('author_index');
    }

        #[Route('/author/edit/{id}', name: 'author_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $author = $this->getDoctrine()->
        getRepository(Author::class)->
        find($id);

        return $this->render('author/edit.html.twig',[
            'author' =>$author,
        ]);

    }
    #[Route('/author/update/{id}', name: 'author_update', methods: ['POST'])]
     public function update(Request $r, $id): Response
    {
        $author = $this->getDoctrine()->
        getRepository(Author::class)->
        find($id);

        $author->
        setName($r->request->get('author_name'))->
        setSurname($r->request->get('author_surname'));

        $enitytManager = $this->getDoctrine()->getManager();
        $enitytManager->persist($author);
        $enitytManager->flush();

        return $this->redirectToRoute('author_index');
    }
        #[Route('/author/delete/{id}', name: 'author_delete', methods: ['POST'])]
        public function delete($id): Response
    {
        $author = $this->getDoctrine()->
        getRepository(Author::class)->
        find($id);

        $enitytManager = $this->getDoctrine()->getManager();
        $enitytManager->remove($author);
        $enitytManager->flush();

        return $this->redirectToRoute('author_index');
    }


}
