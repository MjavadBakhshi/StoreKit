<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class UpsertProductCategoryTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    #[Test]
    function create_new_product_category_successfully()
    {
        $categoryData = $this->getRandomCategoryData();
        $response = $this->createNewCategory($categoryData);

        $response->assertStatus(200);

        $response->json([
            'ok' => true,
            'data' => [
                ...$categoryData
            ]
        ]);


    }

    #[Test]
    function create_new_sub_product_category_successfully()
    {
        $categoryData = $this->getRandomCategoryData();
        $subCategoryData = $this->getRandomCategoryData();

        $response = $this->createNewCategory($categoryData);
        $parentPublicId = $response->json('data.product_category.public_id');

        $subCategoryResponse = $this->createNewCategory([
            ...$subCategoryData,
            'parent_public_id' => $parentPublicId
        ]);


        $subCategoryResponse->assertStatus(200);
        
        // Checking parent id is stored successfully?
        $parentId = $subCategoryResponse->json('data.product_category.parent.public_id');
        $this->assertEquals($parentPublicId, $parentId);

    }

    function cannot_create_loop_category()
    {
        #TODO : This feature will be added later.
    }

    #[Test]
    function update_a_product_category_successfulyy()
    {
        // Create first category
        $categoryData = $this->getRandomCategoryData();
        $responseCategoryOne = $this->createNewCategory($categoryData);
        // Create second category
        $responseCategoryTwo = $this->createNewCategory([
            ...$categoryData,
            'slug' => 'category-slug-2',
        ]);

        // Keep the slug untouched to check if it ignores the unique validation?
        $updatedData = [
            ...$categoryData,
            'title' => fake()->name()
        ];
        
        $publicId = $responseCategoryOne->json('data.product_category.public_id');

        $updateResponse = $this->putJson(
            route('products.categories.update', $publicId),
            $updatedData
        );

        $updateResponse->assertStatus(200);

        $updateResponse->json([
            'ok' => true,
            'data' => $updatedData
        ]);

        // Now toching the slug and checking the unique validation.

        $updatedData['slug'] = 'category-slug-2';

        $updateResponse = $this->putJson(
            route('products.categories.update', $publicId),
            $updatedData
        );

        $updateResponse->assertStatus(422);

        $updateResponse->assertJsonValidationErrorFor('slug');

    }


    private function getRandomCategoryData()
    {
        return [
            'title' => fake()->name(),
            'slug' => str_replace(' ', '-', fake()->unique()->name()),
            'status' => true,
            'menu_visibility' => true,
        ];
    }

    private function createNewCategory(array $data)
    {
        $response = $this->postJson(
            route('products.categories.store'),
            $data
        );

        return $response;
    }
}
