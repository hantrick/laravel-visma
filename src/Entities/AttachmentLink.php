<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

/**
 * @property string   $DocumentId
 * @property int      $DocumentType  0 = None, 1 = SupplierInvoice, 2 = Receipt, 3 = Voucher, 4 = SupplierInvoiceDraft, 5 = AllocationPeriod, 6 = Transfer
 * @property string[] $AttachmentIds
 */
class AttachmentLink extends BaseEntity
{
    protected string $endpoint = '/attachmentlinks';

    public function save(): object
    {
        return $this->basePost($this);
    }
}
