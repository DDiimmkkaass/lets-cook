<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:41
 */

namespace App\Services;

use App\Models\Share;
use Datatables;

/**
 * Class ShareService
 * @package App\Services
 */
class ShareService
{
    
    /**
     * @return array|\Bllim\Datatables\json
     */
    public function table()
    {
        $list = Share::select(
            'id',
            'image',
            'link',
            'position',
            'status'
        );
        
        return $dataTables = Datatables::of($list)
            ->editColumn(
                'image',
                function ($model) {
                    $html = '';
                    
                    if ($model->image) {
                        $html = view(
                            'partials.image',
                            [
                                'src'        => $model->image,
                                'attributes' => ['width' => 200, 'height' => 150],
                            ]
                        )->render();
                    }
                    
                    return $html;
                }
            )
            ->editColumn(
                'link',
                function ($model) {
                    return view(
                        'views.share.partials.datatables.link',
                        ['model' => $model, 'type' => 'share']
                    )->render();
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view(
                        'partials.datatables.toggler',
                        ['model' => $model, 'type' => 'share', 'field' => 'status']
                    )->render();
                }
            )
            ->editColumn(
                'position',
                function ($model) {
                    return view(
                        'partials.datatables.text_input',
                        [
                            'model'        => $model,
                            'type'         => 'share',
                            'field'        => 'position',
                            'parent_class' => 'position',
                        ]
                    )->render();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        ['model' => $model, 'type' => 'share']
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->setRowClass('odd')
            ->make();
    }
}