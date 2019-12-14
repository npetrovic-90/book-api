<?php

namespace App\Transformers;

use App\Book;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Book $book)
    {
        return [
            //

            'identifier'=> (int) $book->id,
            'title'=> (string) $book->title,
            'details'=> (string) $book->description,
            'stock'=> (int) $book->quantity,
            'situation'=> (string) $book->status,
            'picture'=> url("img/{$book->image}"),
            'seller'=> (int) $book->seller_id,
            'creationDate'=>(string)$book->created_at,
            'lastChange'=>(string)$book->updated_at,
            'deletedDate'=>isset($book->deleted_at) ? (string)$book->deleted_at: null,

        ];
    }
}
