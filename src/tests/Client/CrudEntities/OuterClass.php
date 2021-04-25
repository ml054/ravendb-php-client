<?php

namespace RavenDB\Tests\Client\CrudEntities;

class OuterClass
{
        private InnerClass $innerClassMatrix;
        private InnerClass $innerClasses;
        private string $a;
        private InnerClass $innerClass;
        private MiddleClass $middleClass;

    public function getInnerClassMatrix(): InnerClass
    {
        return $this->innerClassMatrix;
    }

    public function setInnerClassMatrix(InnerClass $innerClassMatrix): void
    {
        $this->innerClassMatrix = $innerClassMatrix;
    }

    public function getInnerClasses(): InnerClass
    {
        return $this->innerClasses;
    }

    public function setInnerClasses(InnerClass $innerClasses): void
    {
        $this->innerClasses = $innerClasses;
    }

    public function getA(): string
    {
        return $this->a;
    }

    public function setA(string $a): void
    {
        $this->a = $a;
    }

    public function getInnerClass(): InnerClass
    {
        return $this->innerClass;
    }

    public function setInnerClass(InnerClass $innerClass): void
    {
        $this->innerClass = $innerClass;
    }

    public function getMiddleClass(): MiddleClass
    {
        return $this->middleClass;
    }

    public function setMiddleClass(MiddleClass $middleClass): void
    {
        $this->middleClass = $middleClass;
    }


}
