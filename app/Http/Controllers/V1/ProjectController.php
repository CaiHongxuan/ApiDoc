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

        // todo
    }

    /**
     * 项目详情
     */
    public function show()
    {
    }

    /**
     * 更新项目
     */
    public function update()
    {
    }

    /**
     * 删除项目
     */
    public function destroy()
    {
    }

}