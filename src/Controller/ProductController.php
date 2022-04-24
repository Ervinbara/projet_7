<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as TAG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


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
     * @TAG\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     *
     */
    public function getProducts(ProduitsRepository $produitsRepository)
    {
        $produits = $produitsRepository->findAll();
        $response = $this->json($produits, 200, [], ['groups' => 'produits:read']);
        $response->setSharedMaxAge(3600);
        return $response;
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
     * @TAG\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * )
     */
    public function getProductDetails(Produits $produit)
    {
        $response = $this->json($produit, 200, [], ['groups' => 'produits:detail']);
        $response->setSharedMaxAge(3600);
        return $response;
    }
}
