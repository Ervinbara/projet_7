<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Repository\UserClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as TAG;
use Nelmio\ApiDocBundle\Annotation\Model;

class UserController extends AbstractController
{
    /**
     * Récupération des utilisateurs en lien avec le client
     * @Route("/api/users", name="api_users_index", methods="GET")
     * @TAG\Tag(name="Users")
     */
    public function getUsers(UserClientRepository $UserClientRepository)
    {
        // Récupéreratio des utilisateurs liée au client
        $users = $UserClientRepository->findByClient($this->getUser());
        // Pour faire le tout en une ligne :
        return $this->json($users, 200,[],['groups' => 'user:read']);
    }

    /**
     * Récupération du détail d'un utilisateur en lien avec le client
     * @Route("/api/users/{id}", name="api_user_detail", methods="GET")
     * @TAG\Tag(name="Users")
     * @TAG\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of user",
     *     @TAG\Schema(type="integer")
     * )
     * @TAG\Response(
     *     response=200,
     *     description="Return one user affiliate to the client",
     *     @Model(type=UserClient::class, groups={"user:detail"})
     * )
     * @TAG\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * @TAG\Response(
     *     response=403,
     *     description="Unauthorized."
     * )
     * @TAG\Response(
     *     response=404,
     *     description="User not found."
     * )
     * @TAG\Tag(name="Users")
     * @Security(name="Bearer")
     */
    public function getUserDetails(UserClient $user, UserClientRepository $UserClientRepository):Response
    {
        $user = $UserClientRepository->findByClient($this->getUser());
        return $this->json($user, 200,[],['groups' => 'user:detail']);
    }

    /**
     * Insertion d'un utilisateur
     * @Route("/api/users", name="api_users_insert", methods="POST")
     * @TAG\Tag(name="Users")
     * @TAG\RequestBody(
     *     description="Add user",
     *     required=true,
     *     @Model(type=UserClient::class, groups={"user:detail"}))
     *
     * @TAG\Response(
     *     response=201,
     *     description="Success - Return User added.",
     *     @Model(type=UserClient::class, groups={"user:detail"})
     * )
     * @TAG\Response(
     *     response=400,
     *     description="Bad Requet - Syntax Error"
     * )
     * @TAG\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * @TAG\Response(
     *     response=500,
     *     description="Internal Error"
     * )
     * @TAG\Tag(name="Users")
     * @Security(name="Bearer")
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
     * Suppression d'un utilisateur
     * @Route("/api/users/delete/{id}", name="api_users_delete", methods="DELETE")
     * @TAG\Tag(name="Users")
     * @TAG\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of the user to delete",
     *     @TAG\Schema(type="integer")
     * )
     * @TAG\Response(
     *     response=204,
     *     description="Success, User deleted."
     * )
     * @TAG\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * @TAG\Response(
     *     response=403,
     *     description="Unauthorized."
     * )
     * @TAG\Response(
     *     response=404,
     *     description="User not found."
     * )
     * @TAG\Tag(name="Users")
     * @Security(name="Bearer")
     */
    public function deleteUser(UserClient $user)
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