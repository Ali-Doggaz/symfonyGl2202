<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TodoController
 * @package App\Controller
 * @Route("todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo")
     */
    public function index(SessionInterface $session): Response
    {
        if(! $session->has('todos')) {
            $todos = [
                'lundi' => 'HTML',
                'mardi' => 'CSs',
                'mercredi' => 'Js',
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "Bienvenue dans votre plateforme de gestion des todos");
        }
        return $this->render('todo/index.html.twig');
    }

    /**
     * @Route("/addToDo/{name}/{content}", name="addTodo")
     */
    public function addTodo($name, $content="Blank", SessionInterface $session) {

        // Vérifier que ma session contient le tableau de todo
        if (!$session->has('todos')) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        } else {
            //ok
            // Je vérifie si le todo existe
            $todos = $session->get('todos');

            if (isset($todos[$name])) {
                //Si il a la meme valeur que celle qu'on essaye de lui assigner
                if ($todos[$name] == $content) $this->addFlash('info', "Le todo $name possede deja la valeur que vous tentez de lui assigner.");
                // Sinon
                else {
                    $todos[$name] = $content;
                    $session->set('todos', $todos);
                    $this->addFlash('success', "Le todo $name a été modifié avec succès");
                }
            } else {
                //Si l'element n'existe pas, on le créer.
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo $name a été ajouté avec succès");
            }
        }
        return $this->redirectToRoute('todo');
    }

    /**
     * @Route("/deleteToDo/{key}", name="deleteToDo")
     */
    public function deleteToDo($key, SessionInterface $session) {

        // Vérifier que ma session contient le tableau de todo
        if (!$session->has('todos')) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        } else {
            //ok
            // Je vérifie si le todo existe
            $todos = $session->get('todos');
            if (!array_key_exists($key, $todos)) {
                //ko => messsage erreur + redirection
                $this->addFlash('error', "Le todo n'existe pas");
            } else {
                //ok => je supprime le todo
                unset($todos[$key]);
                $session->set('todos', $todos);
                $this->addFlash('success', "L'element d'indice $key a été supprimé avec succès");
            }
        }
        return $this->redirectToRoute('todo');
    }

    /**
     * @Route("/resetToDo", name="resetToDo")
     */
    public function resetToDo(SessionInterface $session) {

        // Vérifier que ma session contient le tableau de todo
        if (!$session->has('todos')) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        } else {
            $session->set('todos', [
                        'Lundi' => 'HTML',
                        'Mardi' => 'CSS',
                        'Mercredi' => 'JS',
                        ]);
            $this->addFlash('success', "La liste a été réinitialisée avec succés!");
        }
        return $this->redirectToRoute('todo');
    }
}
