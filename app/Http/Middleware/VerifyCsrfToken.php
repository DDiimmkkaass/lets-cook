<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

/**
 * Class VerifyCsrfToken
 * @package App\Http\Middleware
 */
class VerifyCsrfToken extends BaseVerifier
{

    /**
     * @var array
     */
    protected $except = [
        'admin/elfinder/connector',
        'admin/elfinder',
        'admin/elfinder/ckeditor4',
        'admin/variable/value/update',
    ];

    /**
     * @var array
     */
    protected $translatable_except = [
        'likes',
        'subscribe'
    ];

    /**
     * VerifyCsrfToken constructor.
     *
     * @param  \Illuminate\Foundation\Application         $app
     * @param  \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        parent::__construct($app, $encrypter);

        foreach (config('app.locales') as $locale) {
            foreach ($this->translatable_except as $link) {
                $this->except[] = '/' . $locale . '/' . $link;
            }
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return parent::handle($request, $next);
    }
}
