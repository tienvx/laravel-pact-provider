<?php

namespace Tienvx\PactProviderPackage\Model;

class Message
{
    public function __construct(
        public /* readonly */ string $contents,
        public /* readonly */ string $contentType,
        public /* readonly */ string $metadata
    ) {
    }
}
