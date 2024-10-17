<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;


class BouncedEmailsExport implements FromArray, WithHeadings
{
    protected $bouncedEmails;

    public function __construct(array $bouncedEmails)
    {
        $this->bouncedEmails = $bouncedEmails;
    }

    public function array(): array
    {
        return $this->bouncedEmails;
    }

    public function headings(): array
    {
        return ['Email', 'Reason', 'Created At'];
    }
}
