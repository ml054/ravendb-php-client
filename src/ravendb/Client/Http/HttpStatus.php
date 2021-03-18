<?php

namespace RavenDB\Client\Http;
// TODO : A STATIC FILE TO ACCESS HTTP RETURN CODE
// Source code recreated from a .class file by IntelliJ IDEA
// (powered by Fernflower decompiler)
//
interface HttpStatus
{
    public const SC_CONTINUE = 100;
    public const SC_SWITCHING_PROTOCOLS = 101;
    public const SC_PROCESSING = 102;
    public const SC_OK = 200;
    public const SC_CREATED = 201;
    public const SC_ACCEPTED = 202;
    public const SC_NON_AUTHORITATIVE_INFORMATION = 203;
    public const SC_NO_CONTENT = 204;
    public const SC_RESET_CONTENT = 205;
    public const SC_PARTIAL_CONTENT = 206;
    public const SC_MULTI_STATUS = 207;
    public const SC_MULTIPLE_CHOICES = 300;
    public const SC_MOVED_PERMANENTLY = 301;
    public const SC_MOVED_TEMPORARILY = 302;
    public const SC_SEE_OTHER = 303;
    public const SC_NOT_MODIFIED = 304;
    public const SC_USE_PROXY = 305;
    public const SC_TEMPORARY_REDIRECT = 307;
    public const SC_BAD_REQUEST = 400;
    public const SC_UNAUTHORIZED = 401;
    public const SC_PAYMENT_REQUIRED = 402;
    public const SC_FORBIDDEN = 403;
    public const SC_NOT_FOUND = 404;
    public const SC_METHOD_NOT_ALLOWED = 405;
    public const SC_NOT_ACCEPTABLE = 406;
    public const SC_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const SC_REQUEST_TIMEOUT = 408;
    public const SC_CONFLICT = 409;
    public const SC_GONE = 410;
    public const SC_LENGTH_REQUIRED = 411;
    public const SC_PRECONDITION_FAILED = 412;
    public const SC_REQUEST_TOO_LONG = 413;
    public const SC_REQUEST_URI_TOO_LONG = 414;
    public const SC_UNSUPPORTED_MEDIA_TYPE = 415;
    public const SC_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const SC_EXPECTATION_FAILED = 417;
    public const SC_INSUFFICIENT_SPACE_ON_RESOURCE = 419;
    public const SC_METHOD_FAILURE = 420;
    public const SC_UNPROCESSABLE_ENTITY = 422;
    public const SC_LOCKED = 423;
    public const SC_FAILED_DEPENDENCY = 424;
    public const SC_INTERNAL_SERVER_ERROR = 500;
    public const SC_NOT_IMPLEMENTED = 501;
    public const SC_BAD_GATEWAY = 502;
    public const SC_SERVICE_UNAVAILABLE = 503;
    public const SC_GATEWAY_TIMEOUT = 504;
    public const SC_HTTP_VERSION_NOT_SUPPORTED = 505;
    public const SC_INSUFFICIENT_STORAGE = 507;
}
