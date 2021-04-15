<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\Session\Loaders\ILoaderWithInclude;
use RavenDB\Client\Documents\Session\Operations\BatchOperation;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\StringUtils;

class DocumentSession extends InMemoryDocumentSessionOperations implements IDocumentSessionImpl
{
    private DocumentStore $documentStore;
    private string $id;
    private SessionOptions $options;

    public function __construct(DocumentStore $documentStore, string $id, SessionOptions $options)
    {
        $this->documentStore = $documentStore;
        $this->id = $id;
        $this->options = $options;
        parent::__construct($documentStore,$id,$options);
    }
    public function saveChanges(){
        $saveChangeOperation = new BatchOperation($this);
        $command = $saveChangeOperation->createRequest();
        try{
            if(null === $command) return;
            if($this->noTracking){
                throw new IllegalStateException("Cannot execute saveChanges when entity tracking is disabled in session.");
            }
            $this->_requestExecutor->execute($command,$this->sessionInfo);
        } finally {
            $this->close();
        }
    }

    public function close(){ }

    /**
     *  Loads the specified entity with the specified id.
     */
    public function load(string $clazz, string $id)
    {

    }

    public function delete(string $id, string $expectedChangeVector)
    {
        // TODO: Implement delete() method.
    }

    public function store(object $entity, string $id, string $changeVector=null): void
    {
        $serializer = JsonExtensions::serializer();
        $json = $serializer->normalize($entity);
        $pascalizer = StringUtils::pascalize($json);
        $encode = $serializer->encode($pascalizer,'json');
        dd($encode);
    }

    public function include(string $path): ILoaderWithInclude
    {
        // TODO: Implement include() method.
    }

    public function getConventions(): DocumentConventions
    {
        // TODO: Implement getConventions() method.
    }

    public function advanced()
    {
        // TODO: Implement advanced() method.
    }
}
