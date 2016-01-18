<?php

use Carbon\Carbon;

class ComplaintsController extends ApiController {
    public function save() {
        $type = (string)Input::get('type');
        if(!in_array($type, array('mismatch', 'spam', 'flood', 'rude', 'obscene'))){
            return $this->respondWithError('Set incorrect complaint type');
        }

        $complaint = new Complaint();
        $complaint->owner_id = Auth::user()->id;
        $complaint->type = $type;

        if(Input::has('post_id')) {
            $post = Post::find((int)Input::get('post_id'));

            if(!$post) {
                return $this->respondWithError('Post isn\'t found');
            } else if($post->user_id == Auth::user()->id) {
                return $this->respondWithError('Can\'t complain to your post');
            }

			if (Complaint::where('owner_id', Auth::user()->id)->where('post_id', $post->id)->first())
				return $this->respondNoContent();


            $complaint->post_id = $post->id;
        } else if(Input::has('user_id')) {
            $user = User::find((int)Input::get('user_id'));

            if(!$user) {
                return $this->respondWithError('User isn\'t found');
            } else if($user->id == Auth::user()->id) {
                return $this->respondWithError('Can\'t complain to your profile');
            }

			if (Complaint::where('owner_id', Auth::user()->id)->where('user_id', $user->id)->first())
				return $this->respondNoContent();

            $complaint->user_id = $user->id;
        } else if(Input::has('comment_id')) {
            $comment = Comment::find((int)Input::get('comment_id'));

            if(!$comment) {
                return $this->respondWithError('Comment isn\'t found');
            } else if($comment->user_id == Auth::user()->id) {
                return $this->respondWithError('Can\'t complain to your comment');
            }

			if (Complaint::where('owner_id', Auth::user()->id)->where('comment_id', $comment->id)->first())
				return $this->respondNoContent();

            $complaint->comment_id = $comment->id;
        } elseif (Input::has('emergency_id')) {
            $emergency = Emergency::find(Input::get('emergency_id'));

			if (!$emergency) return $this->respondNotFound('Emergency not found');

			if ($emergency->receiver != Auth::id()) return $this->respondInsufficientPrivileges('This emergency is not for you to complain');

			$emergency->complained_at = Carbon::now();
			$emergency->save();

			$emergency->getMembersTokens()->each(function ($token) use($emergency) {
				$state = new StateSender($token->auth_token);
				$state->setEmergencyAsComplained($emergency->id, $emergency->complained_at);
			});

			return $this->respondNoContent();
        }

        $complaint->save();

        return $this->respondNoContent();
    }

    public function saveFilter() {
//        if(!Input::has('type')
//           || !(Input::has('post_id') || Input::has('user_id') || Input::has('comment_id'))
//			|| !Input::has('emergency_id')
//        ) {
//            return $this->respondWithError('Unset necessary parameters');
//        }
    }
}