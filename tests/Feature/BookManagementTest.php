<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/books', $this->data());

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

        $response = $this->post('/books', \array_merge($this->data(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        // $this->withoutExceptionHandling();

        $this->post('/books', $this->data());
        
        $bk = Book::first();

        $response = $this->patch('/books/' . $bk['id'], [
            'title' => 'New Book Title',
            'author_id' => 'New Author'
        ]);
        
        $bk = Book::first();

        $this->assertEquals('New Book Title', $bk['title']);
        $this->assertEquals(2, $bk['author_id']);
        $response->assertRedirect($bk->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        // $this->withoutExceptionHandling();

        $this->post('/books', $this->data());
        
        $bk = Book::first();
    
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' . $bk['id']);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author_id' => 'Viktor'
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data()
    {
        return  [
            'title' => 'Cool Book Title',
            'author_id' => 'viktor'
        ];
    }
}
