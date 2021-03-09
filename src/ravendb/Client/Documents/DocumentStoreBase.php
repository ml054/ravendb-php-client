<?php

namespace RavenDB\Client\Documents;

use Exception;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\This;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\StringUtils;

/**
 * Class DocumentStoreBase
 * @package RavenDB\Client\Documents
 */
abstract class DocumentStoreBase implements IDocumentStore
{
    /**
     * @var bool
     */
    protected bool $disposed;
    /**
     * @var bool
     */
    protected bool $initialized = false;
    /**
     * @var string|array
     */
    protected string|array $urls;
    /**
     * @var string|null
     */
    protected ?string $database = null;

    /**
     * @return bool
     */
    public function isDisposed(): bool
    {
        return $this->disposed;
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * @throws Exception
     */
    public function assertInitialized(): void
    {
        if (!$this->initialized) {
            throw new Exception("You cannot open a session or access the database commands
            before initializing the document store. Did you forget calling initialize()?");
        }
    }

    /**
     * @param string $property
     * @throws Exception
     */
    private function assertNotInitialized(string $property): void
    {
        if ($this->initialized) {
            throw new Exception("You cannot set $property after the document store has been initialized.");
        }
    }

    /**
     * Format the url values
     * @param array|string $values
     * @return void
     */
    public function setUrls(array|string $values): void
    {
        if (null === $values) throw new InvalidArgumentException("value cannot be null");

        $collect = $values;

        if (is_array($values)) {

            $collect = [];
            for ($i = 0; $i < count($values); $i++) {

                $values[$i] ?: throw new InvalidArgumentException("value cannot be null");
                // TODO: check URL to migrate to an Utils (UrlUtils::checkUrl()). based on occurrences
                if (false === filter_var($values[$i], FILTER_VALIDATE_URL)) {
                    throw new InvalidArgumentException("The url " . $values[$i] . " is not valid");
                }
                // TODO rtrim to StringUtils
                $collect[$i] = rtrim($values[$i], "/");
            }
        }

        $this->urls = $collect;
    }

    /**
     * @return array|string
     */
    public function getUrls(): array|string
    {
        return $this->urls;
    }

    /**
     * @return string
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * Set the database instance
     * @param string|null $database
     * @return string|null
     */
    public function setDatabase(?string $database = null): ?string
    {
        return $this->database = $database;
    }

    /**
     * Ensure the resource is not closed
     * @return void
     * @throws Exception
     */
    protected function ensureNotClosed(): void
    {
        if ($this->disposed) {
            throw new Exception('The document store has already been disposed and cannot be used');
        }
    }

    /**
     * @param string $database
     * @param bool|null $secured
     * @param int|null $waitIndexingTimeout
     */
    public function getDocumentStore(string $database, ?bool $secured, ?int $waitIndexingTimeout)
    {
    }

    public function getEffectiveDatabase(string $database): string
    {
              return self::effectiveDatabase($database);
    }

    public static function effectiveDatabase(string $database, ?IDocumentStore $store = null): string
    {
        if (null === $database) {
            $database = $store->getDatabase();
        }
        /* TODO: CHECK improvement*/
        if (StringUtils::isNotBlank($database)) {
            return $database;
        }

        throw new InvalidArgumentException("Cannot determine database to operate on. " .
            "Please either specify 'database' directly as an action parameter " .
            "or set the default database to operate on using 'DocumentStore.setDatabase' method. " .
            "Did you forget to pass 'database' parameter? ");
    }

    private function runServer(bool $secured)
    {
        // TODO
    }
}
