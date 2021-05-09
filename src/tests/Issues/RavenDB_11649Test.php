<?php

namespace RavenDB\Tests\Issues;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\InnerClass;
use RavenDB\Tests\Client\CrudEntities\OuterClass;
use RavenDB\Tests\Client\RemoteTestBase;

class RavenDB_11649Test extends RemoteTestBase
{
    public function testWhatChanged_WhenInnerPropertyChanged_ShouldReturnThePropertyNamePlusPath(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $doc = new OuterClass();
                $doc->setA("outerValue");
                $innerClass = new InnerClass();
                $doc->setInnerClass($innerClass);
                $id = "docs/1";
                $session->store($doc, $id);
                $session->saveChanges();
                $doc->getInnerClass()->setA("NewInnerValue");
                $changes = $session->advanced()->whatChanged();
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testWhatChanged_WhenInnerPropertyChangedFromNull_ShouldReturnThePropertyNamePlusPath(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $doc = new OuterClass();
                $doc->setA("outerValue");

                $innerClass = new InnerClass();
                $doc->setInnerClass($innerClass);
                $innerClass->setA(null);

                $id = "docs/1";
                $session->store(doc, id);
                $session->saveChanges();
                $doc->getInnerClass()->setA("newInnerValue");
                $changes = $session->advanced()->whatChanged();
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testwhatChanged_WhenPropertyOfInnerPropertyChangedToNull_ShouldReturnThePropertyNamePlusPath(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $doc = new OuterClass();
                $doc->setA("outerValue");
                $innerClass = new InnerClass();
                $innerClass->setA("innerValue");
                $doc->setInnerClass($innerClass);
                $id = "docs/1";
                $session->store($doc, $id);
                $session->saveChanges();
                $doc->getInnerClass()->setA(null);
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testwhatChanged_WhenOuterPropertyChanged_FieldPathShouldBeEmpty(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $doc = new OuterClass();
                $doc->setA("outerValue");
                $innerClass = new InnerClass();
                $innerClass->setA("innerClass");
                $doc->setInnerClass($innerClass);
                $id = "docs/1";
                $session->store($doc, $id);
                $session->saveChanges();
                $doc->setA("newOuterValue");
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testWhatChanged_WhenInnerPropertyInArrayChanged_ShouldReturnWithRelevantPath(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $doc = new OuterClass();
                $doc->setA("outerValue");
                $innerClass = new InnerClass();
                $innerClass->setA("innerValue");
                $doc->setInnerClasses(new InnerClass());
                $id = "docs/1";
                $session->store($doc, $id);
                $session->saveChanges();
                $doc->getInnerClasses()[0]->setA("newInnerValue");
                $changes = $session->advanced()->whatChanged();
                $changedPaths = $changes->get($id);
                AssertUtils::assertThat($changedPaths)::isIdenticalTo("innerClasses[0]");
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testWhatChanged_WhenArrayPropertyInArrayChangedFromNull_ShouldReturnWithRelevantPath(){
            try{
                $store = $this->getDocumentStore();
                $options = new SessionOptions();
                $options->setDatabase($store->getDatabase());
                try {
                   $session = $store->openSession($options);
                   $doc = new OuterClass();
                   $doc->setInnerClassMatrix(new InnerClass[][]);
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
}
