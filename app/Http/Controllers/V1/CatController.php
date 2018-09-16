<?php
/**
 * Created by PhpStorm.
 * User: 17586
 * Date: 2018/6/23
 * Time: 0:55
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\ApiController;
use App\Lib\Code\ApiCode;
use App\Model\Catalog;
use App\Model\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatController extends ApiController
{
    protected $catalog = null;

    public function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * 目录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (!$request->has('pro_id')) {
            return $this->responseError(ApiCode::LACK_OF_PARAMETERS, '缺少所属项目参数');
        }
        $where = function ($q) use ($request) {
            // 根据项目id刷选
            $q->where('pro_id', $request->input('pro_id'));
            if ($request->has('docs') && $request->input('doc_name')) {
                $q->whereHas('docs', function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->input('doc_name') . '%');
                });
            }
        };

        $with = [];
        if ($request->has('docs')) {
            $with = ['docs' => function ($query) use ($request)  {
                $query
                    ->select('id', 'title', 'cat_id', 'sort')
                    ->orderBy('sort');
                if ($request->input('doc_name')) {
                    $query->where('title', 'like', '%' . $request->input('doc_name') . '%');
                }
            }];
        }

        $catalogs = $this->catalog
            ->where($where)
            ->with($with)
            ->orderBy('sort', 'ASC')
            ->orderBy('id', 'ASC')
            ->get([
                'id', 'name', 'parent_id', 'parent_ids', 'sort'
            ])
            ->toArray();

        $catalogs = list_to_tree($catalogs);

        if ($request->has('docs')) {
            $docs_where = function ($q) use ($request) {
                $q->where(['cat_id' => 0, 'pro_id' => $request->input('pro_id')]);
                if ($request->input('doc_name')) {
                    $q->where('title', 'like', '%' . $request->input('doc_name') . '%');
                }
            };
            $docs = (new Document())->getDocs($docs_where, ['id', 'title', 'cat_id', 'sort'], 'sort', 1);
            $catalogs = [
                'cats' => $catalogs,
                'docs' => $docs
            ];
        }

        return $this->responseSuccess($catalogs);
    }

    /**
     * 保存目录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'       => 'required',
            'parent_ids' => 'array',
            'pro_id'     => 'required|exists:projects,id',
            'sort'       => 'integer'
        ], [
            'name.required'     => '目录名称必填',
            'parent_ids.array'  => '上级目录必须为数组',
            'pro_id.required'   => '目录所属项目必填',
            'pro_id.exists'     => '目录所属项目不存在',
            'sort.integer'      => '序号必须为整型'
        ], []);
        if ($validate->fails()) {
            return $this->responseError(ApiCode::LACK_OF_PARAMETERS, $validate->errors()->first());
        }

        $catalog = $this->catalog->create(
            array_merge(
                $request->only(['name', 'pro_id']),
                [
                    'parent_ids' => implode(',', $request->input('parent_ids', [0])),
                    'parent_id' => array_last($request->input('parent_ids', [0]))
                ]
            )
        );

        return $this->responseSuccess($catalog);
    }

    /**
     * 目录详情
     * @param $id [目录id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $catalog = $this->catalog->find($id);
        if (!$catalog) {
            return $this->responseError(ApiCode::NOT_FOUND_OF_CATALOG);
        }

        return $this->responseSuccess($catalog->toArray());
    }

    /**
     * 更新目录
     * @param Request $request
     * @param         $id [目录id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $catalog = $this->catalog->find($id);
        if (!$catalog) {
            return $this->responseError(ApiCode::NOT_FOUND_OF_CATALOG);
        }

        $validate = Validator::make($request->all(), [
            'name'       => 'required',
            'parent_ids' => 'array',
            'pro_id'     => 'required|exists:projects,id',
            'sort'       => 'integer'
        ], [
            'name.required'     => '目录名称必填',
            'parent_ids.array'  => '上级目录必须为数组',
            'pro_id.required'   => '目录所属项目必填',
            'pro_id.exists'     => '目录所属项目不存在',
            'sort.integer'      => '序号必须为整型'
        ], []);
        if ($validate->fails()) {
            return $this->responseError(ApiCode::LACK_OF_PARAMETERS, $validate->errors()->first());
        }


        $this->catalog->where('id', $id)->update(
            array_merge(
                $request->only(['name', 'pro_id']),
                [
                    'parent_ids' => implode(',', $request->input('parent_ids', [0])),
                    'parent_id' => array_last($request->input('parent_ids', [0]))
                ]
            )
        );

        return $this->responseSuccess();
    }

    /**
     * 删除目录
     * @param $id [目录id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!$this->catalog->find($id)) {
            return $this->responseError(ApiCode::NOT_FOUND_OF_CATALOG);
        }

        $this->catalog->where('id', $id)->delete();

        return $this->responseSuccess();
    }
}