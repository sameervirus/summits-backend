<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ctf0\PayMob\Integrations\Contracts\Billable;

class Order extends Model implements Billable
{
    public function getBillingData(): array
    {
        return [
            'email'        => $this->email,
            'first_name'   => $this->fname,
            'last_name'    => $this->lname,
            'street'       => $this->address,
            'phone_number' => $this->phone,
        ];
    }
}
