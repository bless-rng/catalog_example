<?php

namespace App\Controller;

use App\DTO\ProductDto;
use App\Entity\Category;
use App\Entity\Product;
use App\Traits\ViolationsToResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProductApiController
 * @package App\Controller
 * @Route(path="api/product/")
 */
class ProductApiController extends AbstractController
{
    use ViolationsToResponseTrait;

    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function addCategory(Request $request, ValidatorInterface $validator)
    {
        /** @var ProductDto $productDto */
        $productDto = $this->serializer->deserialize($request->getContent(), ProductDto::class, 'json');

        if (count($violations = $validator->validate($productDto)) > 0) {
            $errors = $this->violationToResponse($violations);
            return new JsonResponse(['errors' => $errors], 400);
        }

        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->find($productDto->getCategory());
        if (!$category) {
            return new JsonResponse(['error' => "category with id {$productDto->getCategory()} not found"], 404);
        }

        if ($productDto->getPrice() < $category->getMinPrice()) {
            return new JsonResponse("Price of product must be greater than or equal category min price: " . $category->getMinPrice(), '400');
        }

        $product = new Product();
        $product->setName($productDto->getName());
        $product->setPrice($productDto->getPrice());
        $product->setCategory($category);

        if (count($violations = $validator->validate($product)) > 0) {
            $errors = $this->violationToResponse($violations);
            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $data = $this->serializer->serialize($productDto, 'json');
        return new JsonResponse($data, 201, [], true);
    }

    /**
     * @Route(path="{id<\d+>}", methods={"GET"})
     */
    public function getCategory(int $id)
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new NotFoundHttpException();
        }
        $productDto = new ProductDto();
        $productDto->setName($product->getName());
        $productDto->setPrice($product->getPrice());
        $productDto->setCategory($product->getCategory()->getId());

        $data = $this->serializer->serialize($productDto, 'json');
        return new JsonResponse($data, 200, [], true);
    }
}
