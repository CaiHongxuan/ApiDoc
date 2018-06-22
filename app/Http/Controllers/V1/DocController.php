<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/21
 * Time: 17:21
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\ApiController;
use App\Lib\Code\ApiCode;
use App\Model\Catalog;
use App\Model\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocController extends ApiController
{
    protected $document = null;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * 文档列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $where = function ($q) use ($request) {
            // 根据目录id刷选
            if ($request->has('cat_id')) {
                $q->where('cat_id', $request->input('cat_id'));
            }
        };

        $documents = $this->document
            ->where($where)
            ->with(['created_by' => function ($q) {
                $q->select('id', 'name');
            }])
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get([
                'id', 'title', 'type', 'url', 'method', 'status', 'version', 'arguments', 'content', 'created_by', 'updated_by', 'cat_id', 'created_at', 'updated_at'
            ])
            ->toArray();

        return $this->responseSuccess($documents);
    }

    /**
     * 保存文档
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title'     => 'required',
            'type'      => 'required|in:' . implode(',', array_keys(Document::$type_of_doc)),
            'method'    => 'in:' . implode(',', array_keys(Document::$type_of_method)),
            'arguments' => 'json',
            'content'   => 'required',
            'cat_id'    => 'required|exists:catalogs,id',
        ], [
            'title.required'   => '文档标题必填',
            'type.required'    => '文档类型必填',
            'type.in'          => '文档类型的取值范围不正确',
            'method.in'        => '请求方法的取值范围不正确',
            'arguments.json'   => '请求参数格式不正确',
            'content.required' => '内容不能为空',
            'cat_id.required'  => '文档所属目录必填',
            'cat_id.exists'    => '文档所属目录不存在',
        ], []);
        if ($validate->fails()) {
            return $this->responseError(ApiCode::LACK_OF_PARAMETERS, $validate->errors()->first());
        }

        $this->document->create(
            array_merge(
                $request->only(['title', 'type', 'method', 'arguments', 'method', 'arguments', 'content', 'cat_id']),
                [
                    'version'    => 1,
                    'created_by' => 1
                ]
            )
        );

        return $this->responseSuccess();
    }

    /**
     * 文档详情
     */
    public function show()
    {
    }

    /**
     * 更新文档
     */
    public function update()
    {
    }

    /**
     * 删除文档
     */
    public function destroy()
    {
    }
}