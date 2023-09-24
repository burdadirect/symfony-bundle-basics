<?php

namespace HBM\BasicsBundle\Traits\ServiceDependencies;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait FormFactoryDependencyTrait
{
    protected FormFactoryInterface $formFactory;

    #[Required]
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }
}
