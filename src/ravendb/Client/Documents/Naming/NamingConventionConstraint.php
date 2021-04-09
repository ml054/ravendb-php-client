<?php

namespace RavenDB\Client\Documents\Naming;
use Symfony\Component\Validator\Constraint;

class NamingConventionConstraint extends Constraint
{
    public string $message="Unknow type provided. Conversion failed";
}
