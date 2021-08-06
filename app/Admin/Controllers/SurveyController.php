<?php

namespace App\Admin\Controllers;

use App\Models\Survey;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SurveyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Survey';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Survey());

        $grid->column('id', __('Id'));
        $grid->created_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->updated_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->column('team_id', __('Team id'));

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
        $show = new Show(Survey::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('team_id', __('Team id'));

        $show->happenings('Happenings', function ($user) {
            $user->resource('/admin/happenings');
            $user->id();
            $user->name();
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
        $form = new Form(new Survey());

        //$form->number('team_id', __('Team id'));

        return $form;
    }
}
