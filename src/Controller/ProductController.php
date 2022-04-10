<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as TAG;
use Nelmio\ApiDocBundle\Annotation\Model;


class ProductController extends AbstractController
{

    /**
     * Récupération de l'ensemble des produits
     * @Route("/api/produits", name="api_produits_index", methods="GET")
     * @TAG\Tag(name="Products")
     * @TAG\Response(
     *     response=200,
     *     description="Returns list of products",
     *     @TAG\JsonContent(
     *        type="array",
     *        @TAG\Items(ref=@Model(type=Produits::class, groups={"produits:read"}))
     *  )
     * )
     *
     */
    public function getProducts(ProduitsRepository $produitsRepository)
    {
        $produits = $produitsRepository->findAll();
        return $this->json($produits, 200,[],['groups' => 'produits:read']);
    }

    /**
     * Récuprération du détail d'un produit
     * @Route("/api/produits/{id}", name="api_produit_detail", methods="GET")
     * @TAG\Tag(name="Products")
     * @TAG\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of product",
     *     @TAG\Schema(type="integer")
     * )
     * @TAG\Response(
     *     response=200,
     *     description="Return the detail of one product",
     *     @Model(type=Produits::class, groups={"produits:detail"})
     * )
     * @TAG\Response(
     *     response=404,
     *     description="Product not found."
     * )
     * )
     * @Security(name="Bearer")
     */
    public function getProductDetails(Produits $produit)
    {
        return $this->json($produit, 200,[],['groups' => 'produits:detail']);
    }

    /**
     * Insertion d'un produit
     * @Route("/api/produits", name="api_produits_insert", methods="POST")
     * @TAG\Tag(name="Products")
     * @TAG\RequestBody(
     *     description="Add product",
     *     required=true,
     *     @Model(type=Produits::class))
     * @Security(name="Bearer")
     */
    public function insertProduct(Request $request,SerializerInterface $serializer,
                                  EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonRetrieve = $request->getContent();
        // Désérialization on par de JSON et on le transforme en tableau associatif php
        // On récupère le json, et on le tranforme en tableau associatif qui se base sur l'entité Produits
        try{
            $produits = $serializer->deserialize($jsonRetrieve,Produits::class,'json');

            // On vérifie si il y a des erreurs
            $errors = $validator->validate($produits);
            if(count($errors) > 0){
                return $this->json($errors,400);
            }

            $em->persist($produits);
            $em->flush();

            // Status 201 veux dire qu'une ressource à été crée sur le serveur
            return $this->json($produits, 201,['groups' => 'produits:read']);
        } catch(NotEncodableValueException $e) {
             return $this->json([
                 'status' => 400,
                 'message' => $e->getMessage()
             ],400);
        }
    }
}
