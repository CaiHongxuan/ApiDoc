<?php
/**
 * Created by PhpStorm.
 * User: 17586
 * Date: 2018/6/21
 * Time: 22:20
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\ApiController;
use App\Model\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends ApiController
{

    protected $project = null;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * 项目列表
     */
    public function index()
    {
        $projects = $this->project->orderBy('updated_at', 'DESC')->limit(10)->get()->toArray();

        return $this->responseSuccess($projects);
    }

    /**
     * 保存项目
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'icon' => 'string'
        ], [
            'name.required' => '项目标题必填',
            'desc.required' => '项目简介必填',
        ], []);
        if ($validate->fails()) {
            return $this->responseError(-1, $validate->errors()->first());
        }

        $this->project->create(
            array_merge(
                $request->only(['name', 'desc', 'icon']),
                ['created_by' => 1]
            )
        );

        return $this->responseSuccess();
    }

    /**
     * 项目详情
     * @param $id [项目id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = $this->project
            ->select(['id', 'name', 'desc', 'icon', 'created_at', 'updated_at'])
            ->find($id);
        if (!$project) {
            return $this->responseError(-1, '项目不存在');
        }

        return $this->responseSuccess($project->toArray());
    }

    /**
     * 更新项目
     * @param Request $request
     * @param         $id [项目id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (!$this->project->find($id)) {
            return $this->responseError(-1, '项目不存在');
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'icon' => 'string'
        ], [
            'name.required' => '项目标题必填',
            'desc.required' => '项目简介必填',
        ], []);
        if ($validate->fails()) {
            return $this->responseError(-1, $validate->errors()->first());
        }

        $this->project->update(['id' => $id], $request->only(['name', 'desc', 'icon']));

        return $this->responseSuccess();
    }

    /**
     * 删除项目
     * @param $id [项目id]
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!$this->project->find($id)) {
            return $this->responseError(-1, '项目不存在');
        }

        $this->project->where('id', $id)->delete();

        return $this->responseSuccess();
    }

}