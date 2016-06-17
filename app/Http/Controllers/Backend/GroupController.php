<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Backend;

use App\Contracts\Permissions as PermissionsContract;
use App\Http\Requests\Backend\Group\GroupCreateRequest;
use App\Http\Requests\Backend\Group\GroupUpdateRequest;
use App\Models\Group;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Orchestra\Support\Facades\Config;
use Redirect;
use Response;
use Sentry;

/**
 * Class GroupController
 * @package App\Http\Controllers\Backend
 */
class GroupController extends BackendController
{

    /**
     * @var string
     */
    public $module = "group";

    /**
     * @var array
     */
    public $accessMap = ['all' => 'group'];

    /**
     * @var \App\Contracts\Permissions
     */
    private $permissions;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Contracts\Permissions                    $permissions
     */
    public function __construct(ResponseFactory $response, PermissionsContract $permissions)
    {
        parent::__construct($response);

        $this->permissions = $permissions;

        Meta::title(trans('labels.groups'));

        $this->breadcrumbs(trans('labels.groups'), route('admin.group.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {

            $list = Group::select('id', 'name');

            return $dataTables = Datatables::of($list)
                ->filter_column('id', 'where', 'id', 'LIKE', '$1')
                ->filter_column('name', 'where', 'name', 'LIKE', '%$1%')
                ->add_column(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => 'group']
                        )->render();
                    }
                )
                ->set_index_column('id')
                ->make();
        }

        $this->data('page_title', trans('labels.groups_list'));

        return $this->render('views.group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $model = new Group();
        $this->data("model", $model);

        $permissions = $this->permissions->loadArray();
        $this->data('permissions', $permissions);

        $this->data('page_title', trans('labels.group_create'));

        $this->_fillAdditionTemplateData();

        return $this->render('views.group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Backend\Group\GroupCreateRequest $request
     *
     * @return \Response
     */
    public function store(GroupCreateRequest $request)
    {
        $input = $request->only('name', 'permissions');

        $this->_fixPermissionArray($input);

        Sentry::createGroup($input);

        FlashMessages::add('success', trans('messages.save_ok'));

        return Redirect::route('admin.group.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->index($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            // Find the group using the group id
            $group = Sentry::findGroupById($id);
            // Get the group permissions
            $groupPermissions = $group->getPermissions();
        } catch (GroupNotFoundException $e) {
            FlashMessages::add("error", trans('messages.record_not_found'));

            return Redirect::route('admin.group.index');
        }

        $this->data('model', $group);

        $permissions = $this->permissions->loadAndCombine($groupPermissions);
        $this->data('permissions', $permissions);

        $this->_fillAdditionTemplateData();

        $this->data('page_title', trans('labels.group_edit').' "'.$group->name.'"');

        return $this->render('views.group.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                                $id
     * @param \App\Http\Requests\Backend\Group\GroupUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, GroupUpdateRequest $request)
    {
        $input = $request->only('name', 'permissions');

        try {
            $group = Sentry::findGroupById($id);

            $this->_fixPermissionArray($input);

            $group->update($input);

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.group.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add("error", trans('messages.record_not_found'));

            return Redirect::route('admin.group.index');
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.save_failed').' '.$e->getMessage());

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            // Find the group using the group id
            $group = Sentry::findGroupById($id);

            // Delete the group
            $group->delete();
        } catch (GroupNotFoundException $e) {
            FlashMessages::add("error", trans('messages.record_not_found'));
        }

        FlashMessages::add('success', trans('messages.delete_success'));

        return Redirect::route('admin.group.index');
    }

    /**
     * fill additional template data
     */
    private function _fillAdditionTemplateData()
    {
        $permissions_description = config('permissions_description');
        $this->data('permissions_description', $permissions_description);
    }

    /**
     * @param $input
     */
    private function _fixPermissionArray(&$input)
    {
        $permissions = [];

        array_walk(
            $input['permissions'],
            function ($value, $key) use (&$permissions) {
                $permissions[str_replace('_', '.', $key)] = $value;
            }
        );

        $input['permissions'] = $permissions;
    }
}
