<?php

namespace App\Controller;

use DateTime;
use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManager;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaskController extends AbstractController
{
    /**
     * @var TaskRepository
     *
     * @return Response
     */
    private $repository;

    /**
     * @var EntityMenager
     *
     * @var [type]
     */
    private $manager;

    public function __construct(TaskRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @Route("/{_locale}/task/listing", name="task_listing")
     */
    public function index(Request $request): Response
    {
        // Recuperer les informations de l'utilisateur connectee
        $user = $this->getUser();
        // dd($user);

        // Dans ce repository nous recuperons toutes les donnes 
        $tasks = $this->repository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks, 'locale' => $request->getLocale()
        ]);
    }

    /**
     * @Route("/{_locale}/task/create", name="task_create")
     * @Route("/{_locale}/task/update/{id}", name="task_update", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @return void
     */
    public function task(Task $task = null, Request $request)
    {

        if (!$task) {

            $task = new Task;
            $task->setCreatedAt(new \DateTime());
        }


        $form = $this->createForm(TaskType::class, $task, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->persist($task);
            $this->manager->flush();

            return $this->redirectToRoute('task_listing');
        }

        return $this->render(
            'task/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/{_locale}/task/delete/{id}", name="task_delete", requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function deleteTask(Task $task): Response
    {
        $this->manager->remove($task);
        $this->manager->flush();

        return $this->redirectToRoute('task_listing');
    }
}
