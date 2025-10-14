<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use App\Models\Tag;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TagsControllerTest extends TestCase
{
    #[Test]
    public function it_returns_top_10_tags_ordered_by_usage_count(): void
    {
        // Arrange
        $this->assertDatabaseCount(Product::class, 0);
        $this->assertDatabaseCount(Tag::class, 0);

        $popularTags = Tag::factory()->count(3)->create();
        $unpopularTags = Tag::factory()->count(40)->create();

        Product::factory()
            ->count(4)
            ->hasAttached($popularTags)
            ->create();

        // Act
        $response = $this->getJson(route('tags.getPopularList'));

        // Assert
        $response->assertSuccessful();

        $data = $response->json();
        $this->assertCount(10, $data);

        // First 3 tags should have 4 products each
        foreach (range(0, 2) as $i) {
            $this->assertEquals(4, $data[$i]['count'], "Tag at index {$i} should have count 4");
        }

        // Remaining 7 should have count 0
        foreach (range(3, 9) as $i) {
            $this->assertEquals(0, $data[$i]['count'], "Tag at index {$i} should have count 0");
        }

        $this->assertDatabaseCount(Product::class, 4);
        $this->assertDatabaseCount(Tag::class, 43);
    }
}
