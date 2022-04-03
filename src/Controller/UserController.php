<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Repository\UserClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * Récuprération des utilisateurs en lien avec le client
     * @Route("/api/users", name="api_users_index", methods="GET")
     */
    public function getUsers(UserClientRepository $UserClientRepository)
    {
        // Récupérer slmt les utilisateurs liée au client
        // => Créer une fonction dans le repository
        $users = $UserClientRepository->findByClient($this->getUser());
        // Pour faire le tout en une ligne :
        return $this->json($users, 200,[],['groups' => 'user:read']);
    }

    /**
     * Récuprération du détail d'un utilisateur en lien avec le client
     * @Route("/api/users/{user}", name="api_user_detail", methods="GET")
     */
    public function getUserDetails(UserClient $user):Response
    {
        return $this->json($user, 200,[],['groups' => 'user:read']);
    }

    /**
     * @Route("/api/users", name="api_users_insert", methods="POST")
     */
    public function insertUsers(Request $request,SerializerInterface $serializer,
                                EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $client = $this->getUser();
        $jsonRetrieve = $request->getContent();
        // Désérialization on par de JSON et on le transforme en tableau associatif php
        // On récupère le json, et on le tranforme en tableau associatif qui se base sur l'entité Produits
        try{
            $user = $serializer->deserialize($jsonRetrieve,UserClient::class,'json');

            // On vérifie si il y a des erreurs
            $errors = $validator->validate($user);
            if(count($errors) > 0){
                return $this->json($errors,400);
            }
            $user->setClient($client);

            $em->persist($user);
            $em->flush();

            // Status 201 veux dire qu'une ressource à été crée sur le serveur
            return $this->json($user, 201,[],["groups" => 'user:read']);
        } catch(NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }
    }
    /**
     * @Route("/api/users/delete/{id}", name="api_users_delete", methods="DELETE")
     */
    public function deleteUser(ValidatorInterface $validator, UserClient $user)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->json('Suppresion de l\'utilisateur effectué', 201);
        } catch(NotEncodableValueException $e){
            {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ],400);
            }
        }
    }
}