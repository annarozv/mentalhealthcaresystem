<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\DiaryRecord;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param $recordId
     * @return Application|Factory|View
     */
    public function create($recordId)
    {
        return view('comment_new', ['recordId' => $recordId]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $recordId
     * @return Application|Redirector|RedirectResponse
     */
    public function store(Request $request, $recordId)
    {
        $request->validate([
            'comment_text' => 'required|string|min:5|max:1500'
        ]);

        if (Auth::check()) {
            $comment = new Comment();
            $comment->author_id = Auth::id();
            $comment->record_id = $recordId;
            $comment->comment_text = $request->comment_text;
            $comment->save();
        }

        $redirectPath = sprintf(
            'record/%s/info',
            $recordId
        );

        return redirect($redirectPath);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id)
    {
        $comment = Comment::find($id);

        // if comment is found and current user is a record author, then he will be able to edit the comment
        if (!empty($comment) && Auth::check() && Auth::id() === $comment->author_id) {
            return view('comment_edit', ['comment' => $comment]);
        }

        // otherwise, redirect back
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'comment_text' => 'required|string|min:5|max:1500'
        ]);

        $comment = Comment::find($id);

        if (!empty($comment)) {
            $comment->comment_text = $request->comment_text;
            $comment->save();

            $redirectPath = sprintf(
                'record/%s/info',
                $comment->record_id
            );

            return redirect($redirectPath);
        }

        // if comment is not found than user is just redirected to homepage
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!empty($comment)) {
            $comment->is_active = false;
            $comment->save();
        }

        return redirect()->back();
    }
}
