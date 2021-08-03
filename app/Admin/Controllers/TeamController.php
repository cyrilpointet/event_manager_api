<?php

namespace App\Admin\Controllers;

use App\Models\Team;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TeamController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Team';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Team());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->created_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->updated_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Team::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));

        $show->members('Members', function ($user) {
            $user->resource('/admin/users');
            $user->id();
            $user->name();
            $user->pivot('Admin')->display(function ($userPivot) {
                return $userPivot['admin'] ? "yes" : "no";
            });
        });

        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Team());

        $form->text('name', __('Name'));

        $form->multipleSelect('members','members')->options(User::all()->pluck('name','id'));

        return $form;
    }
}
