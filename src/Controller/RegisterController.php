<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {

        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if ($search_email) {
                $notification = "Cette adresse email est déjà utilisée";
            } else {
                $psw = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($psw);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $notification = 'Votre compte a bien été créé !';

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);

    }
}
