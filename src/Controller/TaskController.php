<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/listing", name="task")
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
        $form = $this->createForm(TaskType::class, $task, []);

        return $this->render(
            'task/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
