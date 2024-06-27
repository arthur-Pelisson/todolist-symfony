<?php

namespace App\Controller;

use App\Repository\TodoListRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TaskType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use App\Entity\TodoList;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TodoListController extends AbstractController
{

    
    public function index(User $user): Response
    {
        $todoLists = $user->getTodolists();

        return $this->render('todolist/_mytodolist.html.twig', [
            "todolists" => $todoLists,
        ]);
    }

    public function createTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $user = $this->getUser();
            $task->setUser($user);
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('todolist/todolist_new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
    public function editTask(Request $request, TodoListRepository $todoListRepository, Int $id, EntityManagerInterface $entityManager): Response
    {
        $task = $todoListRepository->find($id);
        
        $form = $this->createForm(TaskType::class);
        $form->setData($task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('todolist/todolist_edit.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    public function showTask(int $id, TodoListRepository $todoListRepository): Response
    {
        $task = $todoListRepository->find($id);
        return $this->render('todolist/todolist_show.html.twig', [
            "task" => $task,
        ]);
    }
    
    public function deleteTask(int $id, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(TodoList::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('No task found for id ' . $id);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    public function confirmDeleteTask(int $id): Response
    {
        return $this->render('todolist/todolist_delete.html.twig', [
            'id' => $id,
        ]);
    }
}
