<?php

namespace Tests\Feature;

use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'viktor'
        ]);

        // $response->assertOk();
        $this->assertCount(1, Book::all());
        $bk = Book::first();
        $response->assertRedirect($bk->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => '',
            'author' => 'viktor'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Cooll book title',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        // $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Viktor'
        ]);
        
        $bk = Book::first();

        $response = $this->patch('/books/' . $bk['id'], [
            'title' => 'New Book Title',
            'author' => 'New Author'
        ]);
        
        $bk = Book::first();

        $this->assertEquals('New Book Title', $bk['title']);
        $this->assertEquals('New Author', $bk['author']);
        $response->assertRedirect($bk->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        // $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Viktor'
        ]);
        
        $bk = Book::first();
    
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' . $bk['id']);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
