<?php
namespace RavenDB\Client\Documents\Session;
use RavenDB\Client\Constants;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Util\StringUtils;

/**
 * Information held about an entity by the session
 */
class DocumentInfo
{
    /**
     * Automatic optimistic concurrency check depending on UseOptimisticConcurrency setting or provided Change Vector
     * Force optimistic concurrency check even if UseOptimisticConcurrency is not set
     * Disable optimistic concurrency check even if UseOptimisticConcurrency is set
     */
    const CONCURRENCY_CHECK_MODE_AUTO="AUTO";
    const CONCURRENCY_CHECK_MODE_FORCED="FORCED";
    const CONCURRENCY_CHECK_MODE_DISABLED="DISABLED";

    private string $id;
    private string $changeVector;
    private bool $ignoreChanges;
    private object $metadata;
    private ?object $document;
    private ?IMetadataDictionary $metadataInstance;
    private object|array|null $entity=null;
    private bool $newDocument;
    private string $collection;
    private string $concurrencyCheckMode;
    /**
     * Gets the Document id
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the Document id
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the ChangeVector.
     * @return string vector
     */
    public function getChangeVector(): string
    {
        return $this->changeVector;
    }

    /**
     * Sets the ChangeVector.
     * @param string $changeVector
     */
    public function setChangeVector(string $changeVector): void
    {
        $this->changeVector = $changeVector;
    }

    /**
     * If set to true, the session will ignore this document
     * when saveChanges() is called, and won't perform and change tracking
     * @return bool
     */
    public function isIgnoreChanges(): bool
    {
        return $this->ignoreChanges;
    }

    /**
     * If set to true, the session will ignore this document
     * when saveChanges() is called, and won't perform and change tracking
     * @param bool $ignoreChanges
     */
    public function setIgnoreChanges(bool $ignoreChanges): void
    {
        $this->ignoreChanges = $ignoreChanges;
    }

    public function isNewDocument(): bool
    {
        return $this->newDocument;
    }

    public function setNewDocument(bool $newDocument): void
    {
        $this->newDocument = $newDocument;
    }

    public function getMetadata(): object
    {
        return $this->metadata;
    }

    public function setMetadata(object $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getDocument(): ?object
    {
        return $this->document;
    }

    public function setDocument(?object $document=null): void
    {
        $this->document = $document;
    }

    public function getMetadataInstance(): ?IMetadataDictionary
    {
        return $this->metadataInstance;
    }

    public function setMetadataInstance(IMetadataDictionary $metadataInstance): void
    {
        $this->metadataInstance = $metadataInstance;
    }

    public function getEntity(): object|array|null
    {
        return $this->entity;
    }

    public function setEntity(object|array|null $entity): void
    {
        $this->entity = $entity;
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function setCollection(string $collection): void
    {
        $this->collection = $collection;
    }

    /**
     * EMMULATION OF ENUM JAVA FUNCTION NOT YET AVAILABLE IN PHP 8.0. ENUM TO COME IN PHP 8.1
     */
    public function getConcurrencyCheckMode():string {
        return $this->concurrencyCheckMode;
    }

    /**
     * @param string $concurrencyCheckMode
     * @throws \Exception
     */
    public function setConcurrencyCheckMode(string $concurrencyCheckMode): void
    {
        $this->concurrencyCheckMode = match ($concurrencyCheckMode){
            "AUTO"=>self::CONCURRENCY_CHECK_MODE_AUTO,
            "FORCED"=>self::CONCURRENCY_CHECK_MODE_FORCED,
            "DISABLED"=>self::CONCURRENCY_CHECK_MODE_DISABLED,
            default=>throw new \Exception("Unknown CheckMode ".$concurrencyCheckMode." provided")
        };
    }

    /**
     * @throws \Exception
     */
    public static function getNewDocumentInfo($document):DocumentInfo {
        $documentObject = new ObjectNode($document);

        $metadata = $documentObject->get(Constants::METADATA_KEY);
        if(null === $metadata || empty($metadata)) throw new \Exception("Document must have a metadata");

        $id = $metadata[Constants::METADATA_ID];
        if(null === $id || !StringUtils::isString($id)) throw new \Exception("Document must have a id");

        $changeVector = $metadata[Constants::METADATA_CHANGE_VECTOR];
        if(null === $changeVector || !StringUtils::isString($changeVector)) throw new \Exception("Document " . $id." must have a Change Vector");

        $newDocumentInfo = new DocumentInfo();
        $newDocumentInfo->setId($id);
        $newDocumentInfo->setDocument((object)$document);
        $newDocumentInfo->setMetadata((object)$metadata);
        $newDocumentInfo->setEntity(null);
        $newDocumentInfo->setChangeVector($changeVector);
        return $newDocumentInfo;
    }

}
