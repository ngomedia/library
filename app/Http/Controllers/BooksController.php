<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function store()
    {
        Book::create($this->validaterequest());
    }

    public function update(Book $book)
    {
        $book->update($this->validaterequest());
    }

    protected function validaterequest()
    {
        return request()->validate([
             'title' => 'required',
             'author' => 'required'
        ]);
    }
}
