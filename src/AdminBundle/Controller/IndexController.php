<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('@RuworkAdmin/index.html.twig');
    }
}
