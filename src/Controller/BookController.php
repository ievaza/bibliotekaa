<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class BookController extends AbstractController
{
    #[Route('/book', name: 'book_index')]
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $books = $this->getDoctrine()->
        // getRepository(Book::class)->
        // findAll();

        $authors = $this->getDoctrine()->
        getRepository(Author::class)->
        findAll();

        $books = $this->getDoctrine()->
        getRepository(Book::class);
         if('null' !== $r->query->get('author_id') && $r->query->get('author_id') != 0) {
             $books = $books->findBy(['author_id' => $r->query->get('author_id')], ['title' => 'asc']);
        }
        elseif ($r->query->get('author_id') == 0) {
            $books = $books->findAll();
        }
        else{
            $books = $books->findAll();
         }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'authors' => $authors,
            'author_id' => $r->query->get('author_id') ?? 0,  
            'success' => $r->getSession()->getFlashBag()->get('success',[]),
        ]);
    }

    #[Route('/book/create', name: 'book_create', methods: ['GET'])]
    public function create(Request $r): Response
    {
        $authors = $this->getDoctrine()->
        getRepository(Author::class)->
        findBy([],['surname'=> 'asc']);

        $book_title = $r->getSession()->getFlashBag()->get('book_title',[]);
        $book_isbn = $r->getSession()->getFlashBag()->get('book_isbn',[]);
        $book_pages = $r->getSession()->getFlashBag()->get('book_pages',[]);
        $book_about = $r->getSession()->getFlashBag()->get('book_about',[]);


        return $this->render('book/create.html.twig',[
            'authors'=>$authors,
            'errors' => $r->getSession()->getFlashBag()->get('errors',[]),
            'title' => $book_title[0] ?? '',
            'isbn' => $book_isbn[0] ?? '',
            'pages' => $book_pages[0]??'',
            'about' => $book_about[0] ?? ''
        ]);

    }
    #[Route('/book/store', name:'book_store', methods:['POST'])]
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $author = $this->getDoctrine()->
        getRepository(Author::class)->
        find($r->request->get('book_author_id'));
        
         if($author == null) $r->getSession()->getFlashBag()->add('errors', 'Pasirink autoriu');


        $book = new Book;
        $book->
        setTitle($r->request->get('book_title'))->
        setIsbn($r->request->get('book_isbn'))->
        setPages((int)$r->request->get('book_pages'))->
        setAbout($r->request->get('book_about'))->
        setAuthor($author);



         $errors = $validator->validate($book);

          if(count($errors) > 0) {
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
        
            $r->getSession()->getFlashBag()->add('book_title', $r->request->get('book_title') );
            $r->getSession()->getFlashBag()->add('book_isbn', $r->request->get('book_isbn') );
              $r->getSession()->getFlashBag()->add('book_pages', $r->request->get('book_pages') );
              $r->getSession()->getFlashBag()->add('book_about', $r->request->get('book_about') );
        }
            if(null === $author) {

            return $this->redirectToRoute('book_create' );
            
        }

        $enitytManager = $this->getDoctrine()->getManager();
        $enitytManager->persist($book);
        $enitytManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Sekmingai pridejote knyga');

        return $this->redirectToRoute('book_index');
    }


    #[Route('/book/edit/{id}', name: 'book_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $book = $this->getDoctrine()->
        getRepository(Book::class)->
        find($id);

        $authors = $this->getDoctrine()->
        getRepository(Author::class)->
        findBy([],['name'=>'asc']);

        return $this->render('book/edit.html.twig',[
            'book' =>$book,
            'authors' =>$authors
        ]);

    }
    #[Route('/book/update/{id}', name: 'book_update', methods: ['POST'])]
     public function update(Request $r, $id): Response
    {
        $book = $this->getDoctrine()->
        getRepository(Book::class)->
        find($id);

        $author = $this->getDoctrine()->
        getRepository(Author::class)->
        find($r->request->get('books_author'));

       $book->
       setTitle($r->request->get('book_title'))->
        setIsbn($r->request->get('book_isbn'))->
        setPages($r->request->get('book_pages'))->
        setAbout($r->request->get('book_about'))->
        setAuthor($author);

        $enitytManager = $this->getDoctrine()->getManager();
        $enitytManager->persist($book);
        $enitytManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Sekmingai paredagavot knyga');

        return $this->redirectToRoute('book_index');
    }
        #[Route('/book/delete/{id}', name: 'book_delete', methods: ['POST'])]
        public function delete($id): Response
    {
        $book = $this->getDoctrine()->
        getRepository(Book::class)->
        find($id);

        $enitytManager = $this->getDoctrine()->getManager();
        $enitytManager->remove($book);
        $enitytManager->flush();

        return $this->redirectToRoute('book_index');
    }



}
