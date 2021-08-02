<?php

namespace App\Admin\Controllers;

use App\Models\Invitation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InvitationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Invitation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Invitation());

        $grid->column('id', __('Id'));
        $grid->column('team_id', __('Team id'));
        $grid->column('user_email', __('User email'));

        $grid->is_from_team()->display(function ($isFromTeam) {
            return $isFromTeam ? "yes" : "no";
        });
        $grid->created_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->updated_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });

        $grid->disableCreateButton();

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
        $show = new Show(Invitation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('team_id', __('Team id'));
        $show->field('user_email', __('User email'));
        $show->is_from_team()->as(function ($isFromTeam) {
            return $isFromTeam ? "yes" : "no";
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
        $form = new Form(new Invitation());

        return $form;
    }
}
