<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comment_model extends CI_Model
{
	public function datatable_comment()
	{
		$set_object=new datatables;
		$set_object->datatable_comment();
	}
	public function statuschange($get_comment_id,$get_status,$user_comment,$modified,$userid)
	{
		$set_object=new comment_status;
		$set_object->statuschange($get_comment_id,$get_status,$user_comment,$modified,$userid);
	}
	function get_commentrecords()
	{
		$set_object= new checktables;
		return $set_object->get_commentrecords(); 
		
	}
	
	function insert_article_comment()
	{
		$set_object=new insert_comment;
		return $set_object->insert_article_comment();
	}
	
	function ArticleDetails($contentid)
	{
		$set_object=new insert_comment;
		return $set_object->ArticleDetails($contentid);
	}
	
	function get_comments_by_article_id($article_id)
	{
		$set_object=new retrieve_comment;
		return $set_object->get_comments_by_article_id($article_id);
	}
	function get_tree_view_comments($article_id, $article_comments, $level)
	{
		$set_object=new retrieve_comment;
		return $set_object->get_tree_view_comments($article_id, $article_comments, $level);
	}
	function time2string($timeline)
	{
		$set_object=new retrieve_comment;
		return $set_object->time2string($timeline);
	}
}

class insert_comment extends Comment_model
{
	function insert_article_comment()
	{
		extract($_POST);
		//print_r(extract($_POST));exit;
		
		$user_ip= $_SERVER['REMOTE_ADDR'];	
		$comment_id = ($comment_id!='')? $comment_id : 0;
		
		
		$block_words = $this->db->query("CALL get_block_words()")->result_array();
		

		$status='P';
		foreach($block_words as $t=>$v)
    	{
        	foreach($v as $n)
        	{   
        		/*$re = '/^([^\s]*)$|\\b' . trim($n) . '\\b/i';
            	if(preg_match($re,$comment))
            	{
					$status='B';
                	break;
				}*/
				$text = explode(' ',$comment);
				$text = array_flip($text);
				if (isset($text[trim($n)]))  {
                	$status='B';
                	break;
				}
            	
        	}
   	 }
		//(preg_match("/\b^([^\s]*)$|" . $n . "\b/i", trim($comment))
		//^([^\s]*)$|
		//$commentTxt = htmlentities($comment, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		$commentTxt =  addslashes($comment);
		$content_title = addslashes($article_title);
		
		$commentmaster = $this->db->query('CALL add_article_comments ("' . $content_id . '","'.$content_title.'","' . $content_type_id . '","' . $name . '","' . $email . '","' . date("Y-m-d H:i:s") . '","'.$user_ip.'" ,@insert_id, "'.$commentTxt.'", "'.$comment_id.'","'.$status.'", "'.$section_id.'")');
		$this->session->set_flashdata('msg', "Your Comments added Successfully...Waiting For the Approval ");
		
		// to update comments count on table content_hit_history
		$live_db = $this->load->database('live_db', TRUE);
		$data = $live_db->query('CALL  update_most_hits_and_emailed("C", '.$content_type_id.', '.$content_id.', "'.addslashes($content_title).'", "'.$section_id.'", " ")');
		
		$ret_comment = new retrieve_comment;
		$comments = $ret_comment->get_comments_by_article_id($content_id);
		return $comments;
		
   
	}
	
	public function ArticleDetails($contentid){
		$response =[];
		$live_db = $this->load->database('live_db', TRUE);
		$details = $live_db->query("SELECT title, section_id FROM article WHERE content_id='".$contentid."'");
		$response['count'] = $details->num_rows();
		$response['data'] = $details->result();
		return $response;
	}
}

class retrieve_comment extends Comment_model
{
	function get_comments_by_article_id($article_id)
	{
		
		$article_comments = $this->db->query('CALL get_comments_by_article_id("' . $article_id . '", "", "")')->result_array();
		//echo $this->db->last_query();exit;
		
		//$article_parent_comments = $this->db->query('CALL get_comments_by_article_id("'.$article_id.'", "parent", "")')->result_array();	 //old
		$article_parent_comments = $this->db->query('CALL get_comments_by_article_id("'.$article_id.'", "", "")')->result_array();	 
		
		$show_comments= count($article_parent_comments)>0 ? $this->get_tree_view_comments($article_id, $article_parent_comments, $level=1) : '';	
		$result['view_comments'] = $show_comments;
		$result['count']         =  count($article_comments);	
		return $result;
	}
	
	function get_tree_view_comments_old($article_id , $article_comments, $level){
	    $show_comments='';
		$p=0;
		foreach($article_comments as $article_comment){
			$comment_id = $article_comment['comments_id'];
			$parent_comment_id= $article_comment['Parentcommentid'];
			$reply_to = ($parent_comment_id!=0) ? $parent_comment_id : $comment_id;
			$div_style=($level>=3 &&$parent_comment_id!=0)? 'style="padding:0 0 0 80px;border:none;"' : '';		
			$show_comments .='<div class="ArticlePosts" '.$div_style.'><div class="ArticleUser">';
			$show_comments .='<h4><span class="UserIcon"><i class="fa fa-user"></i></span>'.$article_comment['Guestname'].'</h4>';
			$show_comments .='<p>'.($article_comment['UpdatedComment']!='')? $article_comment['UpdatedComment'] : $article_comment['OriginalComment'].'</p>';
			$time= $article_comment['Createdon']; $post_time= $this->comment_model->time2string($time);
			$show_comments .='<p class="PostTime">'.$post_time.' ago <span class="SiteColor">';
			$show_comments .='<a href="javascript:void(0);" class="reply" data-comment-id="'.$comment_id.'">reply(0)</a></span> <i class="fa fa-flag"></i></p>';
			$show_comments .='</div></div>';
			
			$child_comments = $this->db->query('CALL get_comments_by_article_id(' . $article_id . ', "", '.$comment_id.')')->result_array();
			if(count($child_comments)>0){
				$c=0;
				foreach($child_comments as $child_comment){
					$comment_id = $child_comment['comments_id'];
					$parent_comment_id= $child_comment['Parentcommentid'];
					$reply_to = ($parent_comment_id!=0) ? $parent_comment_id : $comment_id;
					$pixel =40;
					$level = ($level==3)? $level+1 : '';
					$div_style=($parent_comment_id!=0 && $level<=3)? 'style="padding:0 0 0 40px; border:none;"' : 'style="padding:0 0 0 '.($pixel+80) .'px;border:none;"';
					
					$show_comments .='<div class="ArticlePosts" '.$div_style.'>
					<div class="ArticleUser">';
					$show_comments .='<h4><span class="UserIcon"><i class="fa fa-user"></i></span>
					'.$child_comment['Guestname'].'</h4>';
					$show_comments .='<p>'.($child_comment['UpdatedComment']!='')? $child_comment['UpdatedComment'] : $child_comment['OriginalComment'].'</p>';
					$time= $child_comment['Createdon']; $post_time= $this->comment_model->time2string($time);
					$show_comments .='<p class="PostTime">'.$post_time.' ago <span class="SiteColor">';
					$show_comments .='<a href="javascript:void(0);" class="reply" data-comment-id="'.$comment_id.'">reply(0)</a></span> <i class="fa fa-flag"></i></p>';
					$show_comments .='</div></div>';
					
					$grant_child_comments = $this->db->query('CALL get_comments_by_article_id(' . $article_id . ', "", '.$comment_id.')')->result_array();
					if(count($grant_child_comments)>0){
						$show_comments .= $this->get_tree_view_comments($article_id , $grant_child_comments, $level=3); 
					}
					$level=4;
					$pixel = $pixel+40;
					$c++;
				}
			} 
			$p++;
		}
		 //$show_comments .='</div>';
		 
		 return $show_comments;
	}
	
	function get_tree_view_comments($article_id , $comments, $level){
    $html = array();
    $root_id = 0;
    foreach ($comments as $comment)
        $children[$comment['Parentcommentid']][] = $comment;

    // loop will be false if the root has no children (i.e., an empty comment!)
    $loop = !empty($children[$root_id]);

    // initializing $parent as the root
    $parent = $root_id;
    $parent_stack = array();

    // HTML wrapper for the menu (open)
    $html[] = '<ul class="article_comment">';

    while ($loop && ( ( $option = each($children[$parent]) ) || ( $parent > $root_id ) )) {
        if ($option === false) {
            $parent = array_pop($parent_stack);

            // HTML for comment item containing childrens (close)
            $html[] = str_repeat("\t", ( count($parent_stack) + 1 ) * 2) . '</ul>';
            $html[] = str_repeat("\t", ( count($parent_stack) + 1 ) * 2 - 1) . '</li>';
        } elseif (!empty($children[$option['value']['comments_id']])) {
            $tab = str_repeat("\t", ( count($parent_stack) + 1 ) * 2 - 1);
            $keep_track_depth = count($parent_stack);
            if ($keep_track_depth <= 3) {
                $reply_link = '%1$s%1$s<a href="javascript:void(0);" class="reply" data-comment-id="%2$s">reply</a><br/>%1$s';
            } else {
               // $reply_link = '';
			   $reply_link = '%1$s%1$s<a href="javascript:void(0);" class="reply" data-comment-id="%2$s">reply</a><br/>%1$s';
            }
            $name = strlen($option['value']['Guestname']) ? $option['value']['Guestname'] : 'Anonymous_user';
            //$reply_link = '%1$s%1$s<a href="#" class="reply_button" id="%2$s">reply</a><br/>';
            // HTML for comment item containing childrens (open)
            $html[] = sprintf(
                    '%1$s<li id="li_comment_%2$s" data-depth-level="' . $keep_track_depth . '">' .
                    '%1$s%1$s<div><span class="commenter">%3$s</span>' .
                    '%1$s%1$s<div style="margin-top:4px;">%4$s</div>' .
					'<span class="comment_date">%5$s</span>' .
                    $reply_link . '</div></li>', $tab, // %1$s = tabulation
                    $option['value']['comments_id'], //%2$s id
                    '<h4><span class="UserIcon"><i class="fa fa-user"></i></span>'.$name . '</h4>', // %3$s = commenter
                    ($option['value']['UpdatedComment']) ? $option['value']['UpdatedComment'] : $option['value']['OriginalComment'], // %4$s = comment
                    $this->comment_model->time2string($option['value']['Createdon']). ' ago' // %5$s = comment created_date
            );
            //$check_status = "";
            $html[] = $tab . "\t" . '<ul class="article_comment">';

            array_push($parent_stack, $option['value']['Parentcommentid']);
            $parent = $option['value']['comments_id'];
        } else {
            $name = strlen($option['value']['Guestname']) ? $option['value']['Guestname'] : 'anonymous user';
            $keep_track_depth = count($parent_stack);
            if ($keep_track_depth <= 3) {
                $reply_link = '%1$s%1$s<a href="javascript:void(0);" class="reply" data-comment-id="%2$s">reply</a><br/>%1$s';
            } else {
               $reply_link = '%1$s%1$s<a href="javascript:void(0);" class="reply" data-comment-id="%2$s">reply</a><br/>%1$s';
			   //$reply_link = '';
            }

            //$reply_link = '%1$s%1$s<a href="#" class="reply_button" id="%2$s">reply</a><br/>%1$s</li>';
            // HTML for comment item with no children (aka "leaf")
            $html[] = sprintf(
                    '%1$s<li id="li_comment_%2$s" data-depth-level="' . $keep_track_depth . '">' .
                    '%1$s%1$s<div><span class="commenter">%3$s</span>' .
                    '%1$s%1$s<div style="margin-top:4px;">%4$s</div>' .
					'<span class="comment_date">%5$s</span>' .
                    $reply_link . '</div></li>', str_repeat("\t", ( count($parent_stack) + 1 ) * 2 - 1), // %1$s = tabulation
                    $option['value']['comments_id'], //%2$s id
                    '<h4><span class="UserIcon"><i class="fa fa-user"></i></span>'.$name . '</h4>', // %3$s = commenter
                    ($option['value']['UpdatedComment']) ? $option['value']['UpdatedComment'] : $option['value']['OriginalComment'], // %4$s = comment
                    $this->comment_model->time2string($option['value']['Createdon']). ' ago' // %5$s = comment created_date
            );
        }
    }

    // HTML wrapper for the comment (close)
    $html[] = '</ul>';
    return implode("\r\n", $html);
}
	
	function time2string($timeline) 
	{
		$datetime1 = new DateTime(); // Today's Date/Time
		$datetime2 = new DateTime($timeline);
		$interval = $datetime1->diff($datetime2);
		$post_year = $interval->y; // %D years ago
		$post_month = $interval->m; // %D month ago
		$post_date = $interval->d; // %D days ago
		$post_hours = $interval->h; //  %H hours ago
		$post_mins = $interval->i; //  %I minutes ago
		$post_secs = $interval->s; //  %s seconds ago
		
		if($interval->format('%i%h%d%m%y')=="00000"){		
			if($post_secs == 1)
			return $post_secs." second";
			else
			return $post_secs." seconds";	
		}
		else if($interval->format('%h%d%m%y')=="0000"){		
			if($post_mins == 1)
			return $post_mins." minute";
			else
			return $post_mins." minutes";	
		}
		else if($interval->format('%d%m%y')=="000"){
			if($post_hours== 1)
			return $post_hours." hour";
			else
			return $post_hours." hours";	
		}
		else if($interval->format('%m%y')=="00"){
			if($post_date == 1)
			return $post_date." day";
			else
			return $post_date." days";	
		}
		else if($interval->format('%y')=="0"){		
			if($post_month== 1)
			return $post_month." month";
			else
			return $post_month." months";	
		}
		else{
			if($post_year== 1)
			return $post_year." year";
			else
			return $post_year." years";
		}
	}

}
class datatables  extends Comment_model//datatable search
{
	public function datatable_comment()
	{
		extract($_POST);
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
		
		switch ($Field) 
		{
			
			case 1:
				$order_field = 't1.content_type_id';
				break;
			case 2:
				$order_field = 't1.title';
				break;
			case 3:
				$order_field = 't1.UpdatedComment';
				break;
			case 4:
		   		$order_field = 't1.Guestname';
				break;
			case 5:
		   		$order_field = 't1.Createdon';
				break;
			case 6:
		   		$order_field = 't1.Modifiedon';
				break;
			case 7:
		   		$order_field = 't1.Status';
				break;
			
    		default:
        		$order_field = 't1.comments_id';
		}
		
		$Total_rows = $this->db->query('CALL comment_datatable("","","","","'.$filterby.'","")')->num_rows();
		
		
		if($from_date != '')  
		{
			$check_in_date 	= new DateTime($from_date);
			$from_date = $check_in_date->format('Y-m-d');
		}
		if($to_date != '')
		{
			$check_out_date = new DateTime($to_date);
			$to_date = $check_out_date->format('Y-m-d');
		}
		$searchtxt= htmlspecialchars($searchtxt);
		$searchtxt = addslashes(str_replace("'", "''", $searchtxt));
		
		$searchtxt = ($filterby==1)? ((strtolower($searchtxt)=='article')? 1: ((strtolower($searchtxt)=='gallery')? 3 :((strtolower($searchtxt)=='video')? 4:5))): $searchtxt ;
		
		$comment_manager =  $this->db->query('CALL comment_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->result_array();	
		
		$block_words = $this->db->query("CALL get_block_words()")->result_array();
		
		$recordsFiltered =  $this->db->query('CALL comment_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$data['Menu_id'] = get_menu_details_by_menu_name("Comments");

		foreach($comment_manager as $comments)
		{
			foreach($block_words as $k=>$v)
		{
			
			foreach($v as $n)
			{
				//$n = preg_quote($n);
				//$comments['UpdatedComment'] = preg_replace("|(?![^<]+>)(\b$n\b)(?![^<]+>)|iu","<b>$0</b>",$comments['UpdatedComment']);
				//$comments['UpdatedComment']= highlight_phrase($comments['UpdatedComment'], $n, '<b>', '</b>');
				//$str="<div id='fake_textarea' contenteditable>".$comments['UpdatedComment']."</div>";
				//preg_replace('/$n/i', '<b>$0</b>', $comments['updated']);
			}
		}
		
		
			//print_r($str);
			$subdata = array();
			$subdata[]= '<input type="checkbox" name="chk_value" id="chk_value" class="select_check"  value="'.$comments['comments_id'].'">';
			
			$title = stripslashes($comments['title']);
			$content_type = ($comments['content_type_id']==1)? "Article" : (($comments['content_type_id']==3) ? "Gallery" : (($comments['content_type_id']==4) ? "Video" : "Audio"));
			$subdata[] = $content_type;
			if(strlen($title) > 30)
			{
				$subdata[] ='<p class="tooltip_cursor" href="#" title="'.$title.'">'.substr($title, 0, 20).'...'.'</p>';
				//$subdata[] ='<p class="tooltip_cursor" href="#" title="'.$section['Questiontext'].'">'.substr($section['Questiontext'], 0, 20).'...'.'</p>';
			}
			else
			{
				$subdata[] = $title;
			}
			
			if($comments['Status']=="B")
			{
			$subdata[] ='<div class="CommentPopup"><a href="#removemegssage"  onclick="show_comments('.$comments['comments_id'].')" title="View Original Comment"><i class="fa fa-comment-o"></i></a>
			<textarea id="OrgComments'.$comments['comments_id'].'" style="display:none;">'.$comments['OriginalComment'].'</textarea>
			<textarea name="txtComments[]" class="CommentPopupText" id="txtComments'.$comments['comments_id'].'" readonly="readonly" title="Updated Comment">'.$comments['UpdatedComment'].'</textarea></div>';
			}
			else
			{
				$subdata[] ='<div class="CommentPopup"><a href="#removemegssage" onclick="show_comments('.$comments['comments_id'].')" title="View Original Comment"><i class="fa fa-comment-o"></i></a>
			<textarea id="OrgComments'.$comments['comments_id'].'" style="display:none;">'.$comments['OriginalComment'].'</textarea><textarea id="UpdComments'.$comments['comments_id'].'" style="display:none;">'.$comments['UpdatedComment'].'</textarea>
			<textarea name="txtComments[]" class="CommentPopupText" id="txtComments'.$comments['comments_id'].'" readonly="readonly"  onDblClick="ToggleReadOnlyState('.$comments['comments_id'].')" title="Updated Comment">'.$comments['UpdatedComment'].'</textarea></div><div class="save_cancel_'.$comments['comments_id'].'" style="display:none;float:right;margin-top:6px;"><a href="javascript:void(0);" id="save_changes" comment_id = '.$comments['comments_id'].' comment_status='.$comments['Status'].'  class="btn-primary">Save</a>&nbsp;<a href="javascript:void(0);" id="cancel_changes" onClick="ToggleReadOnlyState('.$comments['comments_id'].')" class="btn-primary" style="background:#ccc!important;color:#000">Cancel</a></div>';
			}
				
			$subdata[] = $comments['Guestname'].'<br>'.$comments['EmailID'].'<br>'.$comments['frontenduserIP']; 
			$subdata[] = date("d-m-Y h:i:s",strtotime($comments['Createdon']));
			$subdata[] = date("d-m-Y h:i:s",strtotime($comments['Modifiedon']));
			if($comments['Status']=="A")
				$subdata[] = '<td><span data-toggle="tooltip" title="Approved"><i class="fa fa-check"></i></span></td>';
			elseif($comments['Status']=="R")
				$subdata[] = '<td><a data-toggle="tooltip" title="Rejected" href="javascript:void()"><i class="fa fa-times"></i></a></td>';
			elseif($comments['Status']=="P")
				$subdata[] = '<td><span data-toggle="tooltip" title="Pending"><i class="fa fa-exclamation-triangle"></i></span></td>';
			else
				$subdata[] ='<td><span data-toggle="tooltip" title="Blocked"><i class="fa fa-ban"></i></span></td>';
				
				
			/*$subdata[] ='<a class="button tick" href="#"   data-toggle="tooltip" title="Approve" comment_id = '.$comments['comments_id'].' status = '.$comments["Status"].'   id="status_change"><i id="status'.$comments['comments_id'].'" class="fa fa-check"></i></a>
			  <a class="button cross" href="#" data-toggle="tooltip" title="Reject" comment_id = '.$comments['comments_id'].'   id="status_reject"><i id="status'.$comments['comments_id'].'" class="fa fa-times"></i></a>'; */
			  
			  
			  $set_rights = "";
			  
			  if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1 && ($comments['Status']!="B")){
			  $set_rights .= '<a class="button tick" href="#"   data-toggle="tooltip" title="Approve" comment_id = '.$comments['comments_id'].' status = '.$comments["Status"].'   id="status_change"><i id="status'.$comments['comments_id'].'" class="fa fa-check"></i></a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  if(defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == 1 && ($comments['Status']!="B"))
			  {
			  $set_rights .= '<a class="button cross" href="#" data-toggle="tooltip" title="Reject" comment_id = '.$comments['comments_id'].'   id="status_reject"><i id="status'.$comments['comments_id'].'" class="fa fa-times"></i></a>'; 
			  }
			  else 
			  { 
			  	$set_rights.="";
			  }
   			 $subdata[] = $set_rights;
			  
			  
			  
			  
	   
			$data['data'][$Count] = $subdata;
			$Count++;
		 }
		
			if($recordsFiltered == 0) 
			{
			}
		echo json_encode($data);
		exit;
	  }
	  
	  
	  function highlight_search($string)
	  {   
    	 $query = fetch_search_query();
    	 if (!empty($query))
    	 {
			$replace = array();
			$with = array();
        	foreach (explode(' ', $query) as $key => $query_word)
        	{               
            	$with[] = '<strong class="search">\1</strong>';
            	$replace[] = "|(?![^<]+>)(\b$query_word\b)(?![^<]+>)|iu";
        	}
        	return preg_replace($replace, $with, $string);
      }

    return $string;
}
}

class comment_status extends Comment_model
{
	public function statuschange($get_comment_id,$get_status,$user_comment,$modified,$userid)//to change status
	{
		//echo "CALL Comment_updatestatus('".$get_status."','".$get_comment_id."','".$user_comment."','".$modified."')";exit;
		$status = $this->db->query("CALL Comment_updatestatus('".$get_status."','".$get_comment_id."','".$this->db->escape_str(addslashes($user_comment))."','".$modified."','".$userid."')");
 	// return true;
  
 	 if($status == TRUE)
 	 {
		  $this->session->set_flashdata('success', 'Updated successfully');
		// redirect(base_url().'admin/comment_manager' ,'refresh');
		 echo 'success';
 	 }
	 else
	 {
		 $this->session->set_flashdata('Fail', 'Status updation failed');
		 //redirect(base_url().'admin/comment_manager');
	 }
		
	}
	
	
	
}
class checktables extends Comment_model
{
	function get_commentrecords()
	{
		
		$subsection_name = $this->db->query("CALL check_commentrecords()");
		return $subsection_name->num_rows();
		
	}
	

	
}
	
	?>