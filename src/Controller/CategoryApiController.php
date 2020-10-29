<?php

namespace App\Controller;

use App\DTO\CategoryDto;
use App\Entity\Category;
use App\Traits\ViolationsToResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CategoryApiController
 * @package App\Controller
 * @Route(path="/api/category/")
 */
class CategoryApiController extends AbstractController
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
        /** @var CategoryDto $categoryDto */
        $categoryDto = $this->serializer->deserialize($request->getContent(), CategoryDto::class, 'json');

        if (count($violations = $validator->validate($categoryDto)) > 0) {
            $errors = $this->violationToResponse($violations);
            return new JsonResponse(['errors' => $errors], 400);
        }

        $parentCategory = null;
        if ($parentCategoryId = $categoryDto->getParentCategory()) {
            /** @var Category $parentCategory */
            $parentCategory = $this->entityManager->getRepository(Category::class)->find($parentCategoryId);
            if (!$parentCategory) {
                return new JsonResponse(['error' => "parent category not found"], 404);
            }
        }

        $category = new Category();
        $category->setName($categoryDto->getName());
        $category->setMinPrice($categoryDto->getMinPrice());
        $category->setParentCategory($parentCategory);

        if (count($violations = $validator->validate($category)) > 0) {
            $errors = $this->violationToResponse($violations);
            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $data = $this->serializer->serialize($categoryDto, 'json');
        return new JsonResponse($data, 201, [], true);
    }

    /**
     * @Route(path="{id<\d+>}", methods={"GET"})
     */
    public function getCategory(int $id)
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        if (!$category) {
            throw new NotFoundHttpException();
        }
        $categoryDto = new CategoryDto();
        $categoryDto->setName($category->getName());
        $categoryDto->setMinPrice($category->getMinPrice());
        $categoryDto->setParentCategory($category->getParentCategory() ? $category->getParentCategory()->getId() : null);

        $data = $this->serializer->serialize($categoryDto, 'json');
        return new JsonResponse($data, 200, [], true);
    }
}
