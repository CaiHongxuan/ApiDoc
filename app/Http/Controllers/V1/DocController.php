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
            // 根据项目id刷选
            if ($request->has('pro_id')) {
                $q->where('pro_id', $request->input('pro_id'));
            }
            // 根据目录id刷选
            if ($request->has('cat_id')) {
                $q->where('cat_id', $request->input('cat_id'));
            }
            // 根据文档名称筛选
            if ($request->input('doc_name')) {
                $q->where('title', 'like', '%' . $request->input('doc_name') . '%');
            }
        };

        $documents = $this->document
            ->where($where)
            ->with(['created_by' => function ($q) {
                $q->select('id', 'name');
            }, 'updated_by'      => function ($q) {
                $q->select('id', 'name');
            }])
            ->orderBy('sort', 'ASC')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->select(
                'id', 'title', 'created_by', 'updated_by', 'created_at', 'updated_at'
            )
            ->paginate($this->pageNum);

        return $this->responseSuccess($documents);
    }

    /**
     * 保存文档
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validateData = $request->all();
        if ($request->input('arguments')) {
            $arguments = json_decode($request->input('arguments'), true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return $this->responseError(ApiCode::LACK_OF_PARAMETERS, '请求参数格式不正确');
            }
            $validateData = array_merge($request->all(), compact('arguments'));
        }

        $validate = Validator::make($validateData, [
            'title'                          => 'required',
            'type'                           => 'required|in:' . implode(',', array_keys(Document::$type_of_doc)),
            'url'                            => 'string|required_if:type,' . Document::API_DOC,
            'method'                         => 'in:' . implode(',', array_keys(Document::$type_of_method)) . '|required_if:type,' . Document::API_DOC,
            'arguments'                      => 'array',
            'arguments.parameters'           => 'array',
            'arguments.parameters.*.name'    => 'required_with:arguments.parameters|string',
            'arguments.parameters.*.is_must' => 'required_with:arguments.parameters|boolean',
            'arguments.parameters.*.type'    => 'required_with:arguments.parameters|in:' . implode(',', array_keys(Document::$para_type)),
            'arguments.parameters.*.remark'  => 'string',
            'arguments.headers'              => 'array',
            'arguments.headers.*.name'       => 'required_with:arguments.headers|string',
            'arguments.headers.*.is_must'    => 'required_with:arguments.headers|boolean',
            'arguments.headers.*.type'       => 'required_with:arguments.headers|in:' . implode(',', array_keys(Document::$para_type)),
            'arguments.headers.*.remark'     => 'string',
            'content'                        => 'required',
            'pro_id'                         => 'required|exists:projects,id',
            'cat_ids'                        => 'required|array',
            'sort'                           => 'integer'
        ], [
            'title.required'                              => '文档标题必填',
            'type.required'                               => '文档类型必填',
            'type.in'                                     => '文档类型的取值范围不正确',
            'url.required_if'                             => '接口地址必填',
            'url.string'                                  => '接口地址必须为字符串类型',
            'method.in'                                   => '请求方法的取值范围不正确',
            'method.required_if'                          => '请求方法必填',
            'arguments.array'                             => '请求参数格式不正确',
            'arguments.parameters.array'                  => '请求参数格式不正确',
            'arguments.parameters.*.name.required_with'   => '参数名称必填',
            'arguments.parameters.*.is_must.boolean'      => '是否必填取值不正确',
            'arguments.parameters.*.type.in'              => '参数类型取值不正确',
            'arguments.parameters.*.remark.required_with' => '参数备注必填',
            'arguments.headers.array'                     => '请求头部格式不正确',
            'arguments.headers.*.name.required_with'      => '参数名称必填',
            'arguments.headers.*.is_must.boolean'         => '是否必填取值不正确',
            'arguments.headers.*.type.in'                 => '参数类型取值不正确',
            'arguments.headers.*.remark.required_with'    => '参数备注必填',
            'content.required'                            => '内容不能为空',
            'pro_id.required'                             => '文档所属项目必填',
            'pro_id.exists'                               => '文档所属项目不存在',
            'cat_ids.required'                            => '文档所属目录必填',
            'cat_ids.array'                               => '文档所属目录必须为数组',
            'sort.integer'                                => '序号必须为整型'
        ], []);
        if ($validate->fails()) {
            return $this->responseError(ApiCode::LACK_OF_PARAMETERS, $validate->errors()->first());
        }

        $doc = $this->document->create(
            array_merge(
                $request->only(['title', 'type', 'url', 'method', 'arguments', 'content', 'pro_id']),
                [
                    'sort'       => $request->input('sort', 99),
                    'version'    => '1.0',
                    'created_by' => 1,
                    'updated_by' => 1,
                    'cat_ids'    => json_encode($request->input('cat_ids', [0])),
                    'cat_id'     => array_last($request->input('cat_ids', [0]))
                ]
            )
        );

        return $this->responseSuccess($doc);
    }

    /**
     * 文档详情
     * @param $id [文档id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $document = $this->document
            ->with(['created_by' => function ($q) {
                $q->select('id', 'name');
            }])
            ->with(['updated_by' => function ($q) {
                $q->select('id', 'name');
            }])
            ->find($id);
        if (!$document) {
            return $this->responseError(ApiCode::NOT_FOUND_OF_DOCUMENT);
        }

        $document = $document->toArray();
        $arguments = json_decode(array_get($document, 'arguments', []), true);
        $document['arguments'] = array_map(function ($item) {
            return array_map(function ($value) {
                $result = $value;
                $result['is_must_plan'] = array_get($value, 'is_must') ? '是' : '否';
                $result['type_plan'] = array_get(Document::$para_type, array_get($value, 'type'));
                return $result;
            }, $item);
        }, $arguments);
        $document['status_plan'] = Document::$type_of_status[$document['status']];
        $document['method_plan'] = Document::$type_of_method[$document['method']];

        return $this->responseSuccess($document);
    }

    /**
     * 更新文档
     * @param Request $request
     * @param         $id [文档id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $document = $this->document->find($id);
        if (!$document) {
            return $this->responseError(ApiCode::NOT_FOUND_OF_DOCUMENT);
        }

        $validateData = $request->all();
        if ($request->input('arguments')) {
            $arguments = json_decode($request->input('arguments'), true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return $this->responseError(ApiCode::LACK_OF_PARAMETERS, '请求参数格式不正确');
            }
            $validateData = array_merge($request->all(), compact('arguments'));
        }

        $validate = Validator::make($validateData, [
            'title'                          => 'required',
            'type'                           => 'required|in:' . implode(',', array_keys(Document::$type_of_doc)),
            'url'                            => 'string|required_if:type,' . Document::API_DOC,
            'method'                         => 'in:' . implode(',', array_keys(Document::$type_of_method)) . '|required_if:type,' . Document::API_DOC,
            'arguments'                      => 'array',
            'arguments.parameters'           => 'array',
            'arguments.parameters.*.name'    => 'required_with:arguments.parameters|string',
            'arguments.parameters.*.is_must' => 'required_with:arguments.parameters|boolean',
            'arguments.parameters.*.type'    => 'required_with:arguments.parameters|in:' . implode(',', array_keys(Document::$para_type)),
            'arguments.parameters.*.remark'  => 'string',
            'arguments.headers'              => 'array',
            'arguments.headers.*.name'       => 'required_with:arguments.headers|string',
            'arguments.headers.*.is_must'    => 'required_with:arguments.headers|boolean',
            'arguments.headers.*.type'       => 'required_with:arguments.headers|in:' . implode(',', array_keys(Document::$para_type)),
            'arguments.headers.*.remark'     => 'string',
            'content'                        => 'required',
            'pro_id'                         => 'required|exists:projects,id',
            'cat_ids'                        => 'required|array',
            'sort'                           => 'integer'
        ], [
            'title.required'                              => '文档标题必填',
            'type.required'                               => '文档类型必填',
            'type.in'                                     => '文档类型的取值范围不正确',
            'url.required_if'                             => '接口地址必填',
            'url.string'                                  => '接口地址必须为字符串类型',
            'method.in'                                   => '请求方法的取值范围不正确',
            'method.required_if'                          => '请求方法必填',
            'arguments.array'                             => '请求参数格式不正确',
            'arguments.parameters.array'                  => '请求参数格式不正确',
            'arguments.parameters.*.name.required_with'   => '参数名称必填',
            'arguments.parameters.*.is_must.boolean'      => '是否必填取值不正确',
            'arguments.parameters.*.type.in'              => '参数类型取值不正确',
            'arguments.parameters.*.remark.required_with' => '参数备注必填',
            'arguments.headers.array'                     => '请求头部格式不正确',
            'arguments.headers.*.name.required_with'      => '参数名称必填',
            'arguments.headers.*.is_must.boolean'         => '是否必填取值不正确',
            'arguments.headers.*.type.in'                 => '参数类型取值不正确',
            'arguments.headers.*.remark.required_with'    => '参数备注必填',
            'content.required'                            => '内容不能为空',
            'pro_id.required'                             => '文档所属项目必填',
            'pro_id.exists'                               => '文档所属项目不存在',
            'cat_ids.required'                            => '文档所属目录必填',
            'cat_ids.array'                               => '文档所属目录必须为数组',
            'sort.integer'                                => '序号必须为整型'
        ], []);
        if ($validate->fails()) {
            return $this->responseError(ApiCode::LACK_OF_PARAMETERS, $validate->errors()->first());
        }

        $this->document->where('id', $id)->update(
            array_merge(
                $request->only(['title', 'type', 'url', 'method', 'arguments', 'content', 'pro_id']),
                [
                    'sort'       => $request->input('sort', 99),
                    'version'    => (string)(floatval($document->version) + 0.1),
                    'updated_by' => 1,
                    'cat_ids'    => json_encode($request->input('cat_ids', [0])),
                    'cat_id'     => array_last($request->input('cat_ids', [0]))
                ]
            )
        );

        return $this->responseSuccess();
    }

    /**
     * 删除文档
     * @param $id [文档id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!$this->document->find($id)) {
            return $this->responseError(ApiCode::NOT_FOUND_OF_DOCUMENT);
        }

        $this->document->where('id', $id)->delete();

        return $this->responseSuccess();
    }
}