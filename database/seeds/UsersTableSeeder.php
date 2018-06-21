<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name'     => 'admin',
            'email'    => 'admin@qq.com',
            'password' => encrypt('admin')
        ])
            ->each(function ($u) {
                $u
                    ->projects()
                    ->save(factory(\App\Model\Project::class)->make(
                        [
                            'name' => '开发规范',
                            'desc' => '为方便团队间的协作以及后续维护，本文档制定了一系列的开发规范，望各位遵守！',
                            'icon' => ''
                        ]
                    ));
            });
    }
}
