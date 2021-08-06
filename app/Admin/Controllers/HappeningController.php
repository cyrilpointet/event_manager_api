<?php

namespace App\Admin\Controllers;

use App\Models\Happening;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class HappeningController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Happening';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Happening());

        $grid->column('id', __('Id'));
        $grid->created_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->updated_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->column('name', __('Name'));
        $grid->column('status', __('Status'));
        $grid->column('team_id', __('Team id'));
        $grid->column('survey_id', __('Survey id'));

        $grid->start_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->end_at()->display(function ($rawDate) {
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
        $show = new Show(Happening::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('place', __('Place'));
        $show->field('status', __('Status'));
        $show->field('team_id', __('Team id'));
        $show->field('start_at', __('Start at'));
        $show->field('end_at', __('End at'));

        $show->members('Members', function ($user) {
            $user->resource('/admin/users');
            $user->id();
            $user->name();
            $user->pivot('Presence')->display(function ($userPivot) {
                return $userPivot['presence'];
            });
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Happening());

        $form->text('name', __('Name'));
        $form->text('description', __('Description'));
        $form->text('place', __('Place'));
        $form->text('status', __('Status'))->default('project');
        $form->datetime('start_at', __('Start at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_at', __('End at'))->default(date('Y-m-d H:i:s'));

        $form->multipleSelect('members','members')->options(User::all()->pluck('name','id'));

        return $form;
    }
}
