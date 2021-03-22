<?php


namespace Transfermate\Models;

class Book implements \JsonSerializable
{
    /**
     * @var string $author
     */
    private string $author = '';

    /**
     * @var string $name
     */
    private string $name = '';

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->author = '';
        $this->name = '';
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Serialize Book to JSON array.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'author' => $this->author,
            'name' => $this->name,
        ];
    }
}