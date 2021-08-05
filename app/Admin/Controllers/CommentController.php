<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CommentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Comment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Comment());

        $grid->column('id', __('Id'));
        $grid->created_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->updated_at()->display(function ($rawDate) {
            $date = date_create($rawDate);
            return date_format($date, 'd-m-Y H:i');
        });
        $grid->column('content', __('Content'));
        $grid->column('happening_id', __('Happening id'));
        $grid->column('user_id', __('User id'));

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
        $show = new Show(Comment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('content', __('Content'));
        $show->field('happening_id', __('Happening id'));
        $show->user('User', function ($user) {
            $user->id();
            $user->name();
            $user->email();
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
        $form = new Form(new Comment());

        $form->text('content', __('Content'));
        //$form->number('happening_id', __('Happening id'));
        //$form->number('user_id', __('User id'));

        return $form;
    }
}
