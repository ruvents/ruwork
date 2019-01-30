<?php

declare(strict_types=1);

namespace Ruwork\Paginator;

use Ruwork\Paginator\Exception\PageOutOfRangeException;
use Ruwork\Paginator\Provider\ProviderInterface;

class PaginatorBuilder
{
    /**
     * @var ProviderInterface
     */
    private $provider;
    private $current = 1;
    private $perPage = 10;
    private $proximity = 2;

    public static function create()
    {
        return new static();
    }

    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    public function setCurrent(int $current)
    {
        $this->current = $current;

        return $this;
    }

    public function setPerPage(int $perPage)
    {
        if ($perPage < 1) {
            throw new \InvalidArgumentException(sprintf('The $perPage value must be a positive integer.'));
        }

        $this->perPage = $perPage;

        return $this;
    }

    public function setProximity(int $proximity)
    {
        if ($proximity < 1) {
            throw new \InvalidArgumentException(sprintf('The $proximity value must be a positive integer.'));
        }

        $this->proximity = $proximity;

        return $this;
    }

    public function getPaginator(): Paginator
    {
        if (null === $this->provider) {
            throw new \LogicException('Provider is not set.');
        }

        $totalItems = $this->provider->getTotal();

        if ($totalItems < 0) {
            throw new \UnexpectedValueException('Provider::getTotal() must return a non-negative integer.');
        }

        $total = (int) ceil($totalItems / $this->perPage) ?: 1;

        if ($this->current < 1 || $this->current > $total) {
            throw new PageOutOfRangeException($total, $this->current);
        }

        $items = $this->provider->getItems(($this->current - 1) * $this->perPage, $this->perPage);
        $sections = $this->buildSections($total);

        return new Paginator($sections, $total, $items, $totalItems, $this->current);
    }

    private function buildSections(int $total): array
    {
        $sections = [];

        // calculate the section of the first page
        $lastLeftPoint = $lastRightPoint = 1;

        // calculate the section of the current page
        $leftPoint = $this->current - $this->proximity;

        if ($lastRightPoint + 1 < $leftPoint) {
            // add the section of the first page
            $sections[] = $this->buildSection($lastLeftPoint, $lastRightPoint);
            $lastLeftPoint = $leftPoint;
        }

        $lastRightPoint = $this->current + $this->proximity;

        // calculate the section of the last page
        if ($lastRightPoint + 1 < $total) {
            // add the section of the current page
            $sections[] = $this->buildSection($lastLeftPoint, $lastRightPoint);
            $lastLeftPoint = $total;
        }

        // add the section of the last page
        $sections[] = $this->buildSection($lastLeftPoint, $total, true);

        return $sections;
    }

    private function buildSection(int $firstPage, int $lastPage, bool $last = false): Section
    {
        $pages = [];

        for ($number = $firstPage; $number <= $lastPage; ++$number) {
            $pages[] = new Page($number, $this->current === $number);
        }

        return new Section($pages, $last);
    }
}
