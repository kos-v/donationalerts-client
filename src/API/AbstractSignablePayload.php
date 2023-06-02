<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

abstract class AbstractSignablePayload extends AbstractPayload
{
    /**
     * @return $this
     */
    final public function setSignature(string $signature)
    {
        $this->setExtraField($this->getSignatureFieldKey(), $signature);
        return $this;
    }

    abstract protected function getSignatureFieldKey(): string;
}
