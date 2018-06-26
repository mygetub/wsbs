<?php

!defined('DEBUG') AND exit('Access Denied.');

include _include(APP_PATH.'model/modlog.func.php');

$action = param(1);





if($action == 'digest') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'plugin/xn_digest/view/htm/mod_digest.htm');
		
	} else {
		
		$digest = param('digest', 0);
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
		
		
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_digest_change($tid, $digest, $thread['uid'], $thread['fid']);
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'digest',
				);
				modlog_create($arr);
			}
		}
		
		
		
		message(0, lang('set_completely'));
	
	}
}



if($action == 'top') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_top.htm');
		
	} else {
		
		$top = param('top', 0);
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if($top == 3 && ($gid != 1 && $gid != 2)) {
				continue;
			}
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_top_change($tid, $top);
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'top',
				);
				
				
				modlog_create($arr);
				
			}
		}
		
		
   	// 消息(主题-置顶) 重写foreach问题不大, 后期如果程序升级这里可作调整
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if($top == 3 && ($gid != 1 && $gid != 2)) {
			continue;
		}
		if(forum_access_mod($fid, $gid, 'allowtop')) {
			// notice send
			if($top != $thread['top']) {
				$thread['subject'] = notice_substr($thread['subject'], 20);
				$todo = lang('notice_template_yourtopic_top'.$top);
				$thread_top_notice_message = lang('notice_admin').'<span class="handle mx-1">'.$todo.'</span>'.lang('notice_template_yourtopic').'<a href="'.url("thread-$thread[tid]").'">《'.$thread['subject'].'》</a>';
				$notice_nid = notice_send($user['uid'], $thread['uid'], $thread_top_notice_message, 3);
			}
			// end notice send
		}
	}

		
		message(0, lang('set_completely'));
	}

} elseif($action == 'close') {
		
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_close.htm');
		
	} else {
		
		$close = param('close', 0);
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowtop')) {
				thread_update($tid, array('closed'=>$close));
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'close',
				);
				
				
				modlog_create($arr);
			}
		}
		
		
    // 消息(主题-关闭) 重写foreach问题不大,后期如果程序升级这里可作调整
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if(forum_access_mod($fid, $gid, 'allowtop')) {
			// notice send
			if($close != $thread['closed']) {
				$thread['subject'] = notice_substr($thread['subject'], 20);
				$todo = lang('notice_template_yourtopic_closeopen'.$close);
				$thread_close_notice_message = lang('notice_admin').'<span class="handle mx-1">'.$todo.'</span>'.lang('notice_template_yourtopic').'<a href="'.url("thread-$thread[tid]").'">《'.$thread['subject'].'》</a>';
				$notice_nid = notice_send($user['uid'], $thread['uid'], $thread_close_notice_message, 3);
			}
			// end notice send
		}
	}

		
		message(0, lang('set_completely'));
	}
	
} elseif($action == 'delete') {
	
	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_delete.htm');
		
	} else {
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		
		$threadlist = thread_find_by_tids($tidarr);
		
		
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowdelete')) {
				thread_delete($tid);
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'delete',
				);
				
				modlog_create($arr);
			}
		}
		
		
    	// 消息(主题-删除) 重写foreach问题不大, 后期如果程序升级这里可作调整
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if(forum_access_mod($fid, $gid, 'allowdelete')) {
			// notice send
		    $thread['subject'] = notice_substr($thread['subject'], 20);
			
			$todo = lang('notice_template_yourtopic_delete');
			$thread_delete_notice_message = lang('notice_admin').'<span class="handle mx-1">'.$todo.'</span>'.lang('notice_template_yourtopic').'<a href="'.url("thread-$thread[tid]").'">《'.$thread['subject'].'》</a>';

			$notice_nid = notice_send($user['uid'], $thread['uid'], $thread_delete_notice_message, 3);
			// end notice send
		}
	}

		
		message(0, lang('delete_completely'));
	}
	
	
} elseif($action == 'move') {

	if($method == 'GET') {
		
		include _include(APP_PATH.'view/htm/mod_move.htm');
		
	} else {
		
		$tidarr = param('tidarr', array(0));
		empty($tidarr) AND message(-1, lang('please_choose_thread'));
		$threadlist = thread_find_by_tids($tidarr);
			
		$newfid = param('newfid', 0);
		!forum_read($newfid) AND message(1, lang('forum_not_exists'));
		
		
		
		foreach($threadlist as &$thread) {
			$fid = $thread['fid'];
			$tid = $thread['tid'];
			if(forum_access_mod($fid, $gid, 'allowmove')) {
				if($fid == $newfid) continue;
				thread_update($tid, array('fid'=>$newfid));
				$arr = array(
					'uid' => $uid,
					'tid' => $thread['tid'],
					'pid' => $thread['firstpid'],
					'subject' => $thread['subject'],
					'comment' => '',
					'create_date' => $time,
					'action' => 'move',
				);
				thread_update($tid, array('tagids'=>'', 'tagids_time'=>0));
tag_thread_delete_by_tid($tid);
				modlog_create($arr);
			}
		}
		
		// 清理下缓存
		forum_list_cache_delete();
		
		
    // 消息(主题-移动) 重写foreach问题不大, 后期如果程序升级这里可作调整
	foreach($threadlist as &$thread) {
		$fid = $thread['fid'];
		$tid = $thread['tid'];
		if(forum_access_mod($fid, $gid, 'allowmove')) {
			if($fid == $newfid) continue;			
			// notice send
			$newforum = forum_read($fid);
		   	$thread['subject'] = notice_substr($thread['subject'], 20); 
			$todo = lang('notice_template_yourtopic_move');
			$thread_move_notice_message = lang('notice_admin').'<span class="handle mx-1">'.$todo.'</span>'.lang('notice_template_yourtopic').'<a href="'.url("thread-$thread[tid]").'">《'.$thread['subject'].'》</a>'.lang('notice_template_yourtopic_moveto').' <a href="'.url("forum-$newforum[fid]").'">【'.$newforum['name'].'】</a>';
			$notice_nid = notice_send($user['uid'], $thread['uid'], $thread_move_notice_message, 3);
			// end notice send
		}
	}


		
		message(0, lang('move_completely'));
		
	}
	
} elseif($action == 'deleteuser') {
	
	$_uid = param(2, 0);
	
	$method != 'POST' AND message(-1, 'Method error');
	
	empty($group['allowdeleteuser']) AND message(-1, lang('insufficient_delete_user_privilege'));
	
	$u = user_read($_uid);
	empty($u) AND message(-1, lang('user_not_exists_or_deleted'));
	
	$u['gid'] < 6 AND message(-1, lang('cant_delete_admin_group'));
	
	
	
	$r = user_delete($_uid);
	$r === FALSE AND message(-1, lang('delete_failed'));

	
	
	message(0, lang('delete_successfully'));
}



?>