<?php

namespace App\Controller;

use DateTime;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/listing", name="task_listing")
     */
    public function index(): Response
    {
        // On va chercher avec Doctrine le Repository de nos Task
        $repository = $this->getDoctrine()->getRepository(Task::class);

        // Dans ce repository nous recuperons toutes les donnes 
        $tasks = $repository->findAll();

        // Affichage de donnes dans la vue de ma var_dumper
        // dd($task);

        // var_dump($task);
        // die;

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/create", name="task_create")
     *
     * @param Request $request
     * @return void
     */
    public function createTask(Request $request)
    {
        $task = new Task;
        $task->setCreatedAt(new \DateTime());

        $form = $this->createForm(TaskType::class, $task, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $task->setName($form['name']->getData())
            //     ->setDescription($form['description']->getData())
            //     ->setDueAt($form['dueAt']->getData())
            //     ->setTag($form['tag']->getData());
            // tej części nie potrzebujemy już

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('task_listing');
        }

        return $this->render(
            'task/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
