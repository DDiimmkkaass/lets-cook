<?php
use App\Models\Basket;

/**
 * Class _BasketsSeeder
 */
class _BasketsSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Basket::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Basket())->getTable()).'` AUTO_INCREMENT=1');
        
        for ($i = 0; $i < 5; $i++) {
            $prices = [];
            
            foreach (config('recipes.available_portions') as $portions) {
                $prices[$portions] = [];
                
                foreach (range(1, config('weekly_menu.menu_days')) as $day) {
                    $prices[$portions][$day] = rand(3000, 10000);
                }
            }
            
            $input = [
                'name'        => $this->getLocalizedFaker()->word,
                'description' => $this->getLocalizedFaker()->realText(),
                'image'       => $this->getLocalizedFaker()->imageUrl(250, 250, 'food'),
                'type'        => Basket::getTypeIdByName('basic'),
                'position'    => $i,
                'prices'      => $prices,
            ];
            
            $model = new Basket($input);
            $model->save();
        }
        
        foreach (range(1, 10) as $index) {
            $input = [
                'name'        => $this->getLocalizedFaker()->word,
                'description' => $this->getLocalizedFaker()->realText(),
                'image'       => $this->getLocalizedFaker()->imageUrl(250, 250, 'food'),
                'type'        => Basket::getTypeIdByName('additional'),
                'position'    => $index,
                'price'       => rand(3000, 10000),
            ];
            
            $model = new Basket($input);
            $model->save();
        }
    }
}
