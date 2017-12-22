<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Pass;

use Doctrine\Common\Inflector\Inflector;
use Ruwork\AdminBundle\Config\Model\Config;
use Ruwork\AdminBundle\Form\Type\GroupType;
use Ruwork\AdminBundle\Form\Type\MarkdownType;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\FormRegistryInterface;

class FormFieldsPass implements PassInterface
{
    /**
     * @var FormRegistryInterface
     */
    private $registry;

    public function __construct(FormRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Config $config, array $data)
    {
        $aliases = $data['forms']['type_aliases'] += $this->getSymfonyFormTypeAliases();
        $aliases['group'] = GroupType::class;
        $aliases['markdown'] = MarkdownType::class;

        if (class_exists(UploadType::class)) {
            $aliases['upload'] = UploadType::class;
        }

        foreach ($config->entities as $entity) {
            $entity->create->type = $this->resolveType($entity->create->type, $aliases);
            $entity->edit->type = $this->resolveType($entity->edit->type, $aliases);

            foreach ($entity->create->fields as $field) {
                $field->type = $this->resolveType($field->type, $aliases);
            }

            foreach ($entity->edit->fields as $field) {
                $field->type = $this->resolveType($field->type, $aliases);
            }
        }
    }

    private function resolveType(?string $type, array $aliases): ?string
    {
        if (null === $type) {
            return null;
        }

        if (isset($aliases[$type])) {
            $type = $aliases[$type];
        }

        if (!$this->registry->hasType($type)) {
            throw new \InvalidArgumentException(sprintf('Form type "%s" is not registered.', $type));
        }

        return $type;
    }

    private function getSymfonyFormTypeAliases(): array
    {
        $aliases = [
            'entity' => EntityType::class,
        ];

        $finder = (new Finder())
            ->in(dirname((new \ReflectionClass(BaseType::class))->getFileName()))
            ->name('*Type.php');

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $alias = Inflector::tableize($file->getBasename('Type.php'));
            $aliases[$alias] = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\'.$file->getBasename('.php');
        }

        return $aliases;
    }
}
