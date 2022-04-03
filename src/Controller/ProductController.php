<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{

    /**
     * @Route("/api/produits", name="api_produits_index", methods="GET")
     */
    public function getProducts(ProduitsRepository $produitsRepository, NormalizerInterface $normalizer,SerializerInterface $serializer)
    {
        $produits = $produitsRepository->findAll();

        // En utilisant le NormalizerInterface :
        // Transformation d'objets en tableaux associatifs simples : Normalisation
        // Pour éviter les références circulaire il faut étiquetté les élèments que l'on souhaite récupérer
        // $produitsNormalises = $normalizer->normalize($produits,NULL, ['groups' => 'produits:read']);

        // Object => Tableau associatif => json
        // Transformation de nos produits normalizé en une phrase qui sera du JSON : Encodage
        // $json = json_encode($produitsNormalises);

        // En utilisant le SerializerInterface :
        // Le serializer utilise enfaite le normalizer
        // $json = $serializer->serialize($produits, 'json', ['groups' => 'produits:read']);


        // JsonResponse
        // $response = new JsonResponse($json, 200,[],true);
        // return $response;

        // Pour faire le tout en une ligne :
        return $this->json($produits, 200,[],['groups' => 'produits:read']);

    }

    /**
     * Récuprération du détail d'un produit
     * @Route("/api/produits/{id}", name="api_produit_detail", methods="GET")
     */
    public function getProductDetails(Produits $p,ProduitsRepository $produitsRepository)
    {
//        $produit = $produitsRepository->find($p);
        $produits = $produitsRepository->findOneBy(['id' => $p->getId()]);
        return $this->json($produits, 200,[],['groups' => 'produits:read']);
    }

    /**
     * @Route("/api/produits", name="api_produits_insert", methods="POST")
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
