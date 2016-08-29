<?php

use App\Models\Coupon;
use App\Services\CouponService;

/**
 * Class _CouponsSeeder
 */
class _CouponsSeeder extends DataSeeder
{
    
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
    /**
     * _CouponsSeeder constructor.
     *
     * @param \App\Services\CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        parent::__construct();
    
        $this->couponService = $couponService;
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::whereNotNull('id')->forceDelete();
        DB::statement('ALTER TABLE `'.((new Coupon())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(1, 10) as $index) {
            $input = [
                'type' => rand(0, 2),
                'name' => $this->getLocalizedFaker()->word,
                'description' => $this->getLocalizedFaker()->realText(),
                'discount' => rand(0, 100),
                'discount_type' => rand(0, 1),
                'count' => rand(0, 10),
                'users_count' => rand(0, 10),
            ];
            
            $coupon = new Coupon($input);
            $coupon->code = $this->couponService->newCode();
            
            $coupon->save();
        }
    }
}
