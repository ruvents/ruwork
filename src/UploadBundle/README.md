# Ruwork Upload Bundle

This bundle provides an upload entity implementation.

## Installation

`composer require ruwork/upload-bundle`.

## Getting started

1. Create your upload entity.
    ```php
    <?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Ruwork\UploadBundle\Download\DownloadInterface;
    use Ruwork\UploadBundle\Entity\AbstractUpload;
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    /**
     * @ORM\Entity()
     */
    class Upload extends AbstractUpload implements DownloadInterface
    {
        /**
         * @ORM\Column(type="string", nullable=true)
         *
         * @var null|string
         */
        private $name;

        public function __construct(UploadedFile $uploadedFile, string $path)
        {
            parent::__construct($uploadedFile, $path);
            $this->name = $uploadedFile->getClientOriginalName();
        }

        /**
         * {@inheritdoc}
         */
        public function getDownloadName(): string
        {
            return $this->name ?: basename($this->getPath());
        }
    }
   ```

## Basic usage

```php
<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @Template()
     */
    public function indexAction(Request $request, EntityManagerInterface $em)
    {
        $user = new User();

        $builder = $this->createFormBuilder($user)
            ->add('upload', UploadType::class, [
                'factory' => function (UploadedFile $file, string $path) {
                    return new Upload($file, $path);
                },
            ])
            ->add('submit', SubmitType::class);

        $form = $builder
            ->getForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }
}
```

## Serving upload entity for downloading

```yaml
# config/routes.yaml
download:
    prefix: /download
    resource: '@RuworkUploadBundle/Resources/config/download_route.yaml'
    defaults:
        class: App\Entity\Upload
```

```twig
<a href="{{ path('ruwork_upload_download', {path: upload.path}) }}">Download</a>
```

## Default configuration

```yaml
ruwork_upload:
    public_dir: "%kernel.project_dir%/public"
    uploads_dir_name: uploads
```
