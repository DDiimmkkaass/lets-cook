<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Exceptions\TranslationOfGroupNotAllowed;
use App\Http\Requests\Backend\Translation\TranslationUpdateRequest;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Exception;
use File;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Meta;
use Response;

/**
 * Class TranslationController
 * @package App\Http\Controllers\Backend
 */
class TranslationController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "translation";

    /**
     * @var array
     */
    public $accessMap = [
        'index'  => 'translation.read',
        'update' => 'translation.write',
    ];

    /**
     * @var array
     */
    public $locales = [];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.translations'));

        $this->breadcrumbs(trans('labels.translations'));

        $this->getExistsLocales();
    }

    /**
     * @param string $group
     *
     * @return \Response
     */
    public function index($group)
    {
        try {
            $this->validateGroup($group);

            $page = request('page', 1);

            $list = $this->getGroupCollection($group);

            $total = $list->count();
            $list = $list->slice(($page - 1) * config('translation.per_page'))->take(config('translation.per_page'));

            $list = new LengthAwarePaginator(
                $list,
                $total,
                config('translation.per_page'),
                $page,
                [
                    'path'  => route('admin.translation.index', $group),
                    'query' => [],
                ]
            );

            $this->data('locales', $this->locales);
            $this->data('list', $list);
            $this->data('group', $group);
            $this->data('page', $page);

            $this->data('page_title', trans('labels.translation_group_'.$group));
            $this->breadcrumbs(trans('labels.translation_group_'.$group));

            request()->flush();

            return $this->render('views.translation.index');
        } catch (TranslationOfGroupNotAllowed $e) {
            FlashMessages::add('error', trans('messages.you can\\\'t edit this translations group'));
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.an error has occurred, try_later'));
        }

        return redirect()->route('admin.home');
    }

    /**
     * @param \App\Http\Requests\Backend\Translation\TranslationUpdateRequest $request
     *
     * @return mixed
     */
    public function update(TranslationUpdateRequest $request)
    {
        try {
            $group = $request->route('group');

            $this->validateGroup($group);

            foreach ($this->locales as $locale) {
                $translations = $request->get($locale);

                $locale_exist_translations = $this->getLocaleExistTranslationsForGroup($locale, $group);
                $translation = array_merge($locale_exist_translations, $translations);
                
                $path = app()->langPath().'/'.$locale.'/'.$group.'.php';
                $output = "<?php\n\nreturn ".var_export($translation, true).";\n";
                File::put($path, $output);

                sleep(1);
            }

            request()->flush();

            FlashMessages::add('success', trans('messages.save_ok'));

            return redirect(route('admin.translation.index', $group, ['page' => request('page', 1)]));
        } catch (TranslationOfGroupNotAllowed $e) {
            FlashMessages::add('error', trans('messages.you can\'t edit this translations group'));
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.an error has occurred, try_later'));
        }

        return redirect()->back();
    }

    /**
     * fill array of all physical exists locales
     */
    public function getExistsLocales()
    {
        $locales = File::directories(app()->langPath());

        foreach ($locales as $key => $locale) {
            $locale = explode('/', $locale);

            $this->locales[$key] = array_pop($locale);
        }
    }

    /**
     * @param string $group
     *
     * @return bool
     * @throws \App\Exceptions\TranslationOfGroupNotAllowed
     */
    private function validateGroup($group)
    {
        if (!in_array($group, config('translation.visible_groups'))) {
            throw new TranslationOfGroupNotAllowed();
        }

        return true;
    }

    /**
     * @param string $group
     *
     * @return Collection
     */
    private function getGroupCollection($group)
    {
        $list = [];
        foreach ($this->locales as $locale) {
            $path = app()->langPath().'/'.$locale.'/'.$group.'.php';
            $_list = include($path);

            foreach ($_list as $key => $item) {
                $list[$key][$locale] = $item;
            }
        }

        ksort($list);

        return Collection::make($list);
    }

    /**
     * @param string $locale
     * @param string $group
     *
     * @return array
     */
    private function getLocaleExistTranslationsForGroup($locale, $group)
    {
        $list = [];

        foreach ($this->getGroupCollection($group) as $key => $translation) {
            $list[$key] = isset($translation[$locale]) ? $translation[$locale] : '';
        }

        return $list;
    }
}