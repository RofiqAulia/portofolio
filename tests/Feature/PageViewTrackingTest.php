<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageViewTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_visiting_home_page_records_anonymized_page_view(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $initialCount = PageView::count();

        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(200);

        $this->assertEquals($initialCount + 1, PageView::count());
        $view = PageView::latest('id')->first();
        $this->assertNotNull($view);
        $this->assertSame('/', $view->page);
        $this->assertSame(64, strlen($view->visitor_hash));
    }
}
