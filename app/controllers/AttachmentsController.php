<?php

class AttachmentsController extends ApiController {

	/**
	 * @deprecated
	 * @param $type
	 * @param $postable_id
	 * @param $id
	 * @return Response
	 */
	function destroy($type, $postable_id, $id) {
		$table = $type == 'posts' ? 'attachables' : 'comments_attachables';
		if (DB::table($table)->where('postable_id', $postable_id)->where('attachable_id', $id)->delete())
			return $this->respondNoContent();

		return $this->respondNotFound();
	}

}