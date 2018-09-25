# Ruwork Filter Bundle

## Usage

### Form type

```php
<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('onSale', CheckboxType::class);
    }
}
```

### Filter types

```php
<?php

declare(strict_types=1);

namespace App\Filter;

use Doctrine\ORM\QueryBuilder;
use Ruwork\Filter\Builder\FilterBuilderInterface;
use Ruwork\Filter\Type\FilterTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchFilterType implements FilterTypeInterface
{
    public function build(FilterBuilderInterface $builder, array $options): void
    {
        $builder->add(function (QueryBuilder $qb, ?string $query) use ($options): void {
            if (!$query) {
                return;
            }
            
            $qb
                ->andWhere("{$options['alias']}.title like :search")
                ->setParameter('search', "%{$query}%");
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('alias');
    }
}

final class OnSaleFilterType implements FilterTypeInterface
{
    public function build(FilterBuilderInterface $builder, array $options): void
    {
        $builder->add(function (QueryBuilder $qb, ?bool $onSale) use ($options): void {
            if (!$onSale) {
                return;
            }
            
            $qb
                ->andWhere("{$options['alias']}.onSale = :on_sale")
                ->setParameter('on_sale', $onSale);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('alias');
    }
}

final class ProductFilterType implements FilterTypeInterface
{
    public function build(FilterBuilderInterface $builder, array $options): void
    {
        $builder
            ->embed(SearchFilterType::class, [
                'alias' => $options['alias'],
            ], '[query]')
            ->embed(OnSaleFilterType::class, [
                'alias' => $options['alias'],
            ], '[onSale]');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('alias');
    }
}
```

### Controller

```php
<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Filter\ProductFilterType;
use App\Form\ProductFilterFormType;
use App\Repository\ProductRepository;
use Ruwork\Filter\Factory\FilterFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

final class FilterController
{
    private $productRepository;
    private $formFactory;
    private $filterFactory;

    public function __construct(
        ProductRepository $productRepository,
        FormFactoryInterface $formFactory,
        FilterFactoryInterface $filterFactory
    ) {
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
        $this->filterFactory = $filterFactory;
    }

    public function __invoke(Request $request)
    {
        $qb = $this->productRepository->createQueryBuilder('product');

        $form = $this->formFactory->create(ProductFilterFormType::class, []);
        $form->handleRequest($request);

        $filter = $this->filterFactory->create(ProductFilterType::class, [
            'alias' => 'product',
        ]);
        $filter->filter($qb, $form->getData());

        dd($qb->getQuery()->getDQL());
    }
}
```
