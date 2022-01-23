<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BalanceController extends Controller
{
    private Request $request;

    private $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = Member::where('mUserId', $request->get('u_id'))->first();
    }

    public function get(): array
    {
        return [
            'Return_code' => '0000',
            'Cash_shop' => $this->user->Cash ?? 0,
            'Cash_contents' => 0,
            'Cash_bonus' => 0,
            'Cash_etc' => 0,
            'Mileage_shop' => 0,
            'Mileage_contents' => 0
        ];
    }

    public function purchase()
    {
        if ($this->user->Cash < $this->request->get('good_amt')) {
            return response('', 402);
        }

        $this->user->decrement('Cash', $this->request->get('good_amt'));

        return [
            'Return_code' => '0000',
            'txt_bxaid' => Str::random(20)
        ];
    }
}
