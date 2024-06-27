<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;
class RegistrationController extends AbstractController
{

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkExistingEmail = $entityManager->getRepository(User::class)->findOneBy(['email' => $form->get('email')->getData()]);
            // encode the plain password
            if ($checkExistingEmail) {
                $form->get('email')->addError(new FormError('This email is already registered.'));
            }
            if ($form->get('plainPassword')->getData() != $form->get('confirmPassword')->getData()) {
                $form->get('confirmPassword')->addError(new FormError('Passwords do not match.'));
            }
            if ($form->getErrors(true)->count() == 0) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $user->setFirstName($form->get('firstName')->getData());
                $user->setLastName($form->get('lastName')->getData());
                $user->setEmail($form->get('email')->getData());
    
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
