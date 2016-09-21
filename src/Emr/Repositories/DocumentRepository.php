<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 2/5/16
 * Time: 9:44 AM
 */

namespace LibreEHR\Core\Emr\Repositories;

use Illuminate\Support\Facades\DB;
use LibreEHR\Core\Contracts\DocumentRepositoryInterface;
use LibreEHR\Core\Contracts\DocumentInterface;
use LibreEHR\Core\Emr\Eloquent\Document;

class DocumentRepository extends AbstractRepository implements DocumentRepositoryInterface
{
    public function model()
    {
        return '\LibreEHR\Core\Contracts\DocumentInterface';
    }

    public function find()
    {
        return parent::find();
    }

    public function create(DocumentInterface $documentInterface)
    {
        $documentInterface->save();
        $id = $documentInterface->getId();
        $results = [];
        foreach ($documentInterface->getCategories() as $categoryId) {
            $results[] = DB::table('categories_to_documents')->insert(
                [ 'category_id' => $categoryId, 'document_id' => $id ]
            );
        }
        return $results;
    }

    public function update($id, array $data)
    {
    }

    public function delete($id)
    {
    }

    public function fetchAll()
    {
        return Document::all();
    }

    public function get($id)
    {
        $document = Document::find($id);
        return $document;
    }

    public function getFile($id)
    {
        $document = $this->get($id);
        $url = $document->getUrl();
        $file = file_get_contents($url);
        return $file;
    }
}
