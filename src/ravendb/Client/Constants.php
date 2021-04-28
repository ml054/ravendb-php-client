<?php

namespace RavenDB\Client;

class Constants
{
    /// METADATA GROUP
    public const METADATA_COLLECTION = "@collection";
    public const METADATA_PROJECTION = "@projection";
    public const METADATA_KEY = "@metadata";
    public const METADATA_ID = "@id";
    public const METADATA_CONFLICT = "@conflict";
    public const METADATA_ID_PROPERTY = "Id";
    public const METADATA_FLAGS = "@flags";
    public const METADATA_ATTACHMENTS = "@attachments";
    public const METADATA_COUNTERS = "@counters";
    public const METADATA_TIME_SERIES = "@timeseries";
    public const METADATA_REVISION_COUNTERS = "@counters-snapshot";
    public const METADATA_REVISION_TIME_SERIES = "@timeseries-snapshot";
    public const METADATA_INDEX_SCORE = "@index-score";
    public const METADATA_LAST_MODIFIED = "@last-modified";
    public const METADATA_RAVEN_JAVA_TYPE = "Raven-Java-Type";
    public const METADATA_CHANGE_VECTOR = "@change-vector";
    public const METADATA_EXPIRES = "@expires";
    public const METADATA_ALL_DOCUMENTS_COLLECTION = "@all_docs";
    /// HEADERS GROUP
    public const HEADERS_REQUEST_TIME = "Raven-Request-Time";
    public const HEADERS_REFRESH_TOPOLOGY = "Refresh-Topology";
    public const HEADERS_TOPOLOGY_ETAG = "Topology-Etag";
    public const HEADERS_LAST_KNOWN_CLUSTER_TRANSACTION_INDEX = "Known-Raft-Index";
    public const HEADERS_CLIENT_CONFIGURATION_ETAG = "Client-Configuration-Etag";
    public const HEADERS_REFRESH_CLIENT_CONFIGURATION = "Refresh-Client-Configuration";
    public const HEADERS_CLIENT_VERSION = "Raven-Client-Version";
    public const HEADERS_SERVER_VERSION = "Raven-Server-Version";
    public const HEADERS_ETAG = "ETag";
    public const HEADERS_IF_NONE_MATCH = "If-None-Match";
    public const HEADERS_TRANSFER_ENCODING = "Transfer-Encoding";
    public const HEADERS_CONTENT_ENCODING = "Content-Encoding";
    public const HEADERS_CONTENT_LENGTH = "Content-Length";
    public const HEADERS_CONTENT_TYPE_APPLICATION_JSON = "Content-Type: application/json";
    // RAVENDB ROUTES
    public const ROUTE_ADMIN_DATABASE = "/admin/databases";
    public const ROUTE_DOCS = "/docs";
    public const ROUTE_DATABASES="/databases/";

    // THE CLIENT
    public const CLIENT_VERSION="5.0.0";
    // TODO IMPLEMENT CONST FOR DOCUMENT CHANGE TYPE
    public const CHANGE_TYPE_DOCUMENT_ADDED="DOCUMENT_ADDED";

    // JUST DEFAULT VALUES.
    public const CURLOPT_RETURNTRANSFER = true;
    public const CURLOPT_SSL_VERIFYPEER = "1";
    public const CURLOPT_SSL_VERIFYHOST = "2";
    public const CURLOPT_CUSTOMREQUEST_DELETE="DELETE";
    public const CURLOPT_CUSTOMREQUEST_PUT="PUT";
    public const CURLOPT_CUSTOMREQUEST_GET="GET";

    public const EXCEPTION_STRING_NUMBER_OF_REQUESTS = "The maximum number of requests (%d) allowed for this session has been reached.".
    "Raven limits the number of remote calls that a session is allowed to make as an early warning system. Sessions are expected to be short lived, and ".
    "Raven provides facilities like load(String[] keys) to load multiple documents at once and batch saves (call SaveChanges() only once).".
    "You can increase the limit by setting DocumentConvention.MaxNumberOfRequestsPerSession or MaxNumberOfRequestsPerSession, but it is".
    "advisable that you'll look into reducing the number of remote calls first, since that will speed up your application significantly and result in a".
    "more responsive application.";
}
