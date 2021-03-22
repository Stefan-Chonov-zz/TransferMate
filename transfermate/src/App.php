<?php


namespace Transfermate;

use Transfermate\Utils\Utils;
use Transfermate\Models\Book;
use Transfermate\Core\DB;
use Transfermate\Core\Crud;

class App
{
    /**
     * Main point of the application.
     *
     * @return void
     */
    public function start(): void
    {
        try {
            $crud = new Crud($_ENV['XML_ROOT_NODE'], DB::getInstance());

            $xmlFiles = Utils::getXmlFilesRecursive($_ENV['ROOT_DIRECTORY']);
            $listOfBooks = Utils::decodeXmlFilesDataToObjects($xmlFiles, get_class(new Book()));
            $books = $this->fetchBooks($listOfBooks);
            foreach ($books as $book) {
                $bookAsJsonObject = $book->jsonSerialize();
                $foundedBooks = $crud->read($bookAsJsonObject);
                if (count($foundedBooks) === 0) {
                    $bookAsJsonObject['created_at'] = time();
                    $bookAsJsonObject['modified_at'] = time();
                    $crud->create($bookAsJsonObject);
                } else {
                    unset($foundedBooks[0]['created_at']);
                    $foundedBooks[0]['modified_at'] = time();
                    $crud->update($foundedBooks[0]);
                }
            }

            foreach ($xmlFiles as $xmlFile) {
                $this->printXmlFileContent($xmlFile);
            }
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }
    }

    /**
     * Retrieves array of Books.
     *
     * @param array $xmlData
     * @return Book[]
     */
    private function fetchBooks(array $xmlData): array
    {
        /** @var Book[] $results */
        $results = array();

        foreach ($xmlData as $books) {
            /** @var Book $book */
            foreach ($books as $book) {
                $results[] = $book;
            }
        }

        return $results;
    }

    /**
     * Print XML file content.
     *
     * @param string $xmlFile
     * @return void
     */
    private function printXmlFileContent(string $xmlFile): void
    {
        if (file_exists($xmlFile)) {
            echo PHP_EOL . realpath($xmlFile) . PHP_EOL;
            echo file_get_contents($xmlFile) . PHP_EOL;
        }
    }
}