<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    public const LOAN_PENDING_STATUS = 'PENDING';
    public const LOAN_APPROVED_STATUS = 'APPROVED';
    public const LOAN_REJECTED_STATUS = 'REJECTED';

    protected $guarded = [];

    protected $table = 'loan_application';

    public function user() {
        return $this->belongsTo('User');
    }
}
