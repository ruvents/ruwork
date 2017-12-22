<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Ruwork\DoctrineBehaviorsBundle\Mapping\Author;
use Ruwork\DoctrineBehaviorsBundle\Mapping\Multilingual;
use Ruwork\DoctrineBehaviorsBundle\Mapping\PersistTimestamp;
use Ruwork\DoctrineBehaviorsBundle\Mapping\SearchColumn;
use Ruwork\DoctrineBehaviorsBundle\Mapping\UpdateTimestamp;

final class Metadata
{
    private $class;
    private $searchColumns = [];
    private $authors = [];
    private $multilinguals = [];
    private $persistTimestamps = [];
    private $updateTimestamps = [];

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return SearchColumn[]
     */
    public function getSearchColumns(): array
    {
        return $this->searchColumns;
    }

    public function addSearchColumn(SearchColumn $column)
    {
        $this->searchColumns[$column->name] = $column;

        return $this;
    }

    /**
     * @return Author[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function addAuthor(string $property, Author $author)
    {
        $this->authors[$property] = $author;

        return $this;
    }

    /**
     * @return Multilingual[]
     */
    public function getMultilinguals(): array
    {
        return $this->multilinguals;
    }

    public function addMultilingual(string $property, Multilingual $multilingual)
    {
        $this->multilinguals[$property] = $multilingual;

        return $this;
    }

    /**
     * @return PersistTimestamp[]
     */
    public function getPersistTimestamps(): array
    {
        return $this->persistTimestamps;
    }

    public function addPersistTimestamp(string $property, PersistTimestamp $timestamp)
    {
        $this->persistTimestamps[$property] = $timestamp;

        return $this;
    }

    /**
     * @return UpdateTimestamp[]
     */
    public function getUpdateTimestamps(): array
    {
        return $this->updateTimestamps;
    }

    public function addUpdateTimestamp(string $property, UpdateTimestamp $timestamp)
    {
        $this->updateTimestamps[$property] = $timestamp;

        return $this;
    }
}
