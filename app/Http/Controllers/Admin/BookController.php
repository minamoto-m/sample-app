<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests\BookPostRequest;
use App\Http\Requests\BookPutRequest;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    // public function index(): View
    public function index(): Response
    {
      // 書籍一覧を取得
      // $books=Book::all();
      $books=Book::with('category')
        ->orderBy('category_id')
        ->orderBy('title')
        ->get();
      
      // 書籍一覧をレスポンスとして返す
      // return $books;
      // return view('admin/book/index', ['books' => $books]);

      return response()
        ->view('admin/book/index', ['books' => $books])
        ->header('Content-Type', 'text/html')
        ->header('Content-Encoding', 'UTF-8');
    }

    // $idにルートパラメータ（http://localhost/admin/books/1の「1」）が代入される
    public function show(Book $book): View
    {
      // 書籍を１件取得
      // $book = Book::findOrFail($id);

      // 取得した書籍をレスポンスとして返す
      // return $book;
      return view('admin/book/show', compact('book'));
    }

    public function create(): View
    {
      // ビューにカテゴリ一覧を表示するために全件取得
      $categories = Category::all();

      // 著者一覧を表示するために全件取得
      $authors = Author::all();

      return view('admin/book/create', compact('categories', 'authors'));
    }

    // public function store(Request $request): Book
    // public function store(BookPostRequest $request): Book
    public function store(BookPutRequest $request): RedirectResponse
    {
      // 保存
      $book = new Book();

      $book->category_id = $request->category_id;
      $book->title = $request->title;
      $book->price = $request->price;


      DB::transaction(function () use($book, $request) {
        $book->save();
        $book->authors()->attach($request->author_ids);
      });
      
      // 登録完了後book.indexにリダイレクトする
      return redirect(route('book.index'))
        ->with('message', $book->title . 'を追加しました。');
    }

    public function edit(Book $book): View
    {
      $categories = Category::all();
      $authors = Author::all();

      $authorIds = $book->authors()->pluck('id')->all();

      return view('admin/book/edit', compact('book', 'categories', 'authors', 'authorIds'));
    }

    public function update(BookPutRequest $request, Book $book): RedirectResponse
    {
      // リクエストオブジェクトからパラメータを取得する

      $book->category_id = $request->category_id;
      $book->title = $request->title;
      $book->price = $request->price;

      DB::transaction(function() use($book, $request) {
        $book->update();

        $book->authors()->sync($request->author_ids);
      });
      return redirect(route('book.index'))
        ->with('message', $book->title . 'を変更しました。');
    }

    public function destroy(Book $book): RedirectResponse
    {
      // 削除
      $book->delete();
      
      return redirect(route('book.index'))
        ->with('message', $book->title . 'を削除しました。');
    }
  }
