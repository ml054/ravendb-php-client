<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;

class PersonRole
{
    private string $role_name;

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->role_name;
    }

    /**
     * @param string $role_name
     */
    public function setRoleName(string $role_name): void
    {
        $this->role_name = $role_name;
    }


}
