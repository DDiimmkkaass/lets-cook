<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 05.03.16
 * Time: 14:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestionTranslation
 * @package App\Models
 */
class QuestionTranslation extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['question', 'answer'];
}