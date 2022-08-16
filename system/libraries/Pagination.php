<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright		Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @copyright		Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class CI_Pagination {

	var $base_url			= ''; // The page we are linking to
	var $prefix				= ''; // A custom prefix added to the path.
	var $suffix				= ''; // A custom suffix added to the path.

	var $total_rows			=  0; // Total number of items (database results)
	var $per_page			= 10; // Max number of items you want shown per page
	var $num_links			=  2; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page			=  0; // The current page being viewed
	var $custom_num_links   =  5;
	var $use_page_numbers	= FALSE; // Use page number for segment instead of offset
	var $first_link			= '&lsaquo; First';
	var $next_link			= '&gt;';
	var $prev_link			= '&lt;';
	var $last_link			= 'Last &rsaquo;';
	var $uri_segment		= 3;
	var $full_tag_open		= '';
	var $full_tag_close		= '';
	var $first_tag_open		= '';
	var $first_tag_close	= '&nbsp;';
	var $last_tag_open		= '&nbsp;';
	var $last_tag_close		= '';
	var $first_url			= ''; // Alternative URL for the First Page.
	var $cur_tag_open		= '&nbsp;<strong>';
	var $cur_tag_close		= '</strong>';
	var $next_tag_open		= '&nbsp;';
	var $next_tag_close		= '&nbsp;';
	var $prev_tag_open		= '&nbsp;';
	var $prev_tag_close		= '';
	var $num_tag_open		= '&nbsp;';
	var $num_tag_close		= '';
	var $page_query_string	= FALSE;
	var $query_string_segment = 'per_page';
	var $display_pages		= TRUE;
	var $anchor_class		= '';

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		if ($this->anchor_class != '')
		{
			$this->anchor_class = 'class="'.$this->anchor_class.'" ';
		}

		log_message('debug', "Pagination Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
function create_links()
{
    // If our item count or per-page total is zero there is no need to continue.
    if ($this->total_rows == 0 OR $this->per_page == 0)
    {
        return '';
    }

    // Calculate the total number of pages
    $num_pages = ceil($this->total_rows / $this->per_page);

    // Is there only one page? Hm... nothing more to do here then.
    if ($num_pages == 1)
    {
        return '';
    }

    // Determine the current page number.
    $CI =& get_instance();

    if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
    {
        if ($CI->input->get($this->query_string_segment) != 0)
        {
            $this->cur_page = $CI->input->get($this->query_string_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }
    }
    else
    {
        if ($CI->uri->segment($this->uri_segment) != 0)
        {
            $this->cur_page = $CI->uri->segment($this->uri_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }
    }

    $this->num_links = (int)$this->num_links;

    if ($this->num_links < 1)
    {
        show_error('Your number of links must be a positive number.');
    }

    if ( ! is_numeric($this->cur_page))
    {
        $this->cur_page = 0;
    }

    // Is the page number beyond the result range?
    // If so we show the last page
    if ($this->cur_page > $this->total_rows)
    {
        $this->cur_page = ($num_pages - 1) * $this->per_page;
    }

    $uri_page_number = $this->cur_page;
    $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

    // Calculate the start and end numbers. These determine
    // which number to start and end the digit links with
    $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
    $end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

    // Is pagination being used over GET or POST?  If get, add a per_page query
    // string. If post, add a trailing slash to the base URL if needed
    if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
    {
		

        $this->base_url = str_replace('?&', '?', rtrim($this->base_url).'?'.$this->query_string_segment.'=');

		 $this->base_url = str_replace('/&', '?', $this->base_url);
		
	
    }
    else
    {
        $this->base_url = rtrim($this->base_url, '/') .'/';
    }

    // And here we go...
    $output = '';

    // Render the "First" link
    if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
    {
        $first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
        $output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
    }

    // Render the "previous" link
    if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
    {
        $i = $uri_page_number - $this->per_page;

        if ($i == 0 && $this->first_url != '')
        {
            $output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        }
        else
        {
            $i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
            $output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$i.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        }

    }

    // Render the pages
    if ($this->display_pages !== FALSE)
    {
        // Write the digit links
        for ($loop = $start -1; $loop <= $end; $loop++)
        {
            $i = ($loop * $this->per_page) - $this->per_page;

            if ($i >= 0)
            {
                if ($this->cur_page == $loop)
                {
                    $output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
                }
                else
                {
                    $n = ($i == 0) ? '' : $i;

                    if ($n == '' && $this->first_url != '')
                    {
                        $output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
                    }
                    else
                    {
                        $n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;

                        $output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$n.'">'.$loop.'</a>'.$this->num_tag_close;
                    }
                }
            }
        }
    }

    // Render the "next" link
    if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
    {
        $output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.($this->cur_page * $this->per_page).$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
    }

    // Render the "Last" link
    if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
    {
        $i = (($num_pages * $this->per_page) - $this->per_page);
        $output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->last_link.'</a>'.$this->last_tag_close;
    }

    // Kill double slashes.  Note: Sometimes we can end up with a double slash
    // in the penultimate link so we'll kill all double slashes.
    $output = preg_replace("#([^:])//+#", "\\1/", $output);

    // Add the wrapper HTML if exists
    $output = $this->full_tag_open.$output.$this->full_tag_close;

    return $output;
}

/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
function custom_create_links()
{
	
	 $this->first_tag_close		= '';
	 $this->last_tag_open		= '';
	 $this->last_tag_close		= '';
	 $this->first_url			= ''; // Alternative URL for the First Page.
	 $this->next_tag_open		= '';
	 $this->next_tag_close		= '';
	 $this->prev_tag_open		= '';
	 $this->prev_tag_close		= '';
	 $this->num_tag_open		= '';
	
    // If our item count or per-page total is zero there is no need to continue.
    if ($this->total_rows == 0 OR $this->per_page == 0)
    {
        return '';
    }

    // Calculate the total number of pages
    $num_pages = ceil($this->total_rows / $this->per_page);

    // Is there only one page? Hm... nothing more to do here then.
    if ($num_pages == 1)
    {
        return '';
    }

    // Determine the current page number.
    $CI =& get_instance();

    if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
    {
        if ($CI->input->get($this->query_string_segment) != 0)
        {
            $this->cur_page = $CI->input->get($this->query_string_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }
    }
    else
    {
        if ($CI->uri->segment($this->uri_segment) != 0)
        {
            $this->cur_page = $CI->uri->segment($this->uri_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }
    }

    $this->num_links = (int)$this->num_links;

    if ($this->num_links < 1)
    {
        show_error('Your number of links must be a positive number.');
    }

    if ( ! is_numeric($this->cur_page))
    {
        $this->cur_page = 0;
    }

    // Is the page number beyond the result range?
    // If so we show the last page
    if ($this->cur_page > $this->total_rows)
    {
        $this->cur_page = ($num_pages - 1) * $this->per_page;
    }

    $uri_page_number = $this->cur_page;
    $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

    // Calculate the start and end numbers. These determine
    // which number to start and end the digit links with

	// Start the 5 pagination  coding
	
	/*
	echo $this->cur_page;
	exit; 
	*/
	/*
	if($num_pages <= 5) {
		$start = 1;
		$end = $num_pages;
	} else {
		
		if($this->cur_page == 1) {
			$start = 1;
			$end = 5;
		} else {
			if($this->cur_page+(5 - 2) < $num_pages ) {
			$start = $this->cur_page-1;
			$end =  ((($this->cur_page-1)+(5 - 2)) < $num_pages)? (($this->cur_page-1)+(5)) : $num_pages;
			} else {
				echo "vairam";
				exit;
				$start = $num_pages - (5 - 2);
				$end = $num_pages;
			}
		}
	}
	*/
	
	
	if($num_pages <= $this->custom_num_links) {
		$start = 1;
		$end = $num_pages;
	} else {
		
		if($this->cur_page == 1) {
			$start = 1;
			$end = $this->custom_num_links;
		} else {
	 
			if($this->cur_page+($this->custom_num_links - 1) < $num_pages ) {
			$start = $this->cur_page;
			$end =  (($this->cur_page+($this->custom_num_links - 1)) < $num_pages)? ($this->cur_page+($this->custom_num_links - 1)) : $num_pages;
			} else {
				$start = $num_pages - ($this->custom_num_links - 1);
				$end = $num_pages;
			}
		}
	}
	
	
	
	// Start the 5 pagination coding

    // Is pagination being used over GET or POST?  If get, add a per_page query
    // string. If post, add a trailing slash to the base URL if needed
    if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
    {
		

        $this->base_url = str_replace('?&', '?', rtrim($this->base_url).'?'.$this->query_string_segment.'=');

		 $this->base_url = str_replace('/&', '?', $this->base_url);
		
	
    }
    else
    {
        $this->base_url = rtrim($this->base_url, '/') .'/';
    }

    // And here we go...
    $output = '';

    // Render the "First" link
	//     if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1)) 
    if  ($this->first_link !== FALSE AND $this->cur_page > 1) 
    {
		
       // $first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
	      $first_url = strtok($_SERVER["REQUEST_URI"],'?');
		
        $output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
    }

    // Render the "previous" link
    if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
    {
        $i = $uri_page_number - $this->per_page;

        if ($i == 0 && $this->first_url != '')
        {
            $output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        }
        else
        {
            $i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
			
			if($i == '') 
				$custom_prev_link = $first_url;
			else 
				$custom_prev_link = $this->base_url.$i;
			
            $output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$custom_prev_link.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        }

    }

    // Render the pages
    if ($this->display_pages !== FALSE)
    {
        // Write the digit links
		//  for ($loop = $start -1; $loop <= $end; $loop++)
        for ($loop = $start; $loop <= $end; $loop++)
        {
            $i = ($loop * $this->per_page) - $this->per_page;

            if ($i >= 0)
            {
                if ($this->cur_page == $loop)
                {
                    $output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
                }
                else
                {
                    $n = ($i == 0) ? '' : $i;

                    if ($n == '' && $this->first_url != '')
                    {
                        $output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
                    }
                    else
                    {
                        $n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;
						
						if($n != ''){
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$n.'"> '.$loop.'</a>'.$this->num_tag_close;
						} else {
							if($loop == 1) {
								$base_url_link =  strtok($_SERVER["REQUEST_URI"],'?');
							} else {
								$base_url_link = $this->base_url; 
							}
							
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$base_url_link.$n.'" test> '.$loop.'</a>'.$this->num_tag_close;
						}
                    }
                }
            }
        }
    }

    // Render the "next" link
    if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
    {
        $output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.($this->cur_page * $this->per_page).$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
    }

    // Render the "Last" link
	// customize this condition
	//if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
    if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages AND $num_pages != $end)	
    {
        $i = (($num_pages * $this->per_page) - $this->per_page);
        $output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->last_link.'</a>'.$this->last_tag_close;
    }

    // Kill double slashes.  Note: Sometimes we can end up with a double slash
    // in the penultimate link so we'll kill all double slashes.
    $output = preg_replace("#([^:])//+#", "\\1/", $output);

    // Add the wrapper HTML if exists
    $output = $this->full_tag_open.$output.$this->full_tag_close;

    return $output;
}



/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
function custom_search_create_links()
{
	
	 $this->first_tag_close		= '';
	 $this->last_tag_open		= '';
	 $this->last_tag_close		= '';
	 $this->first_url			= ''; // Alternative URL for the First Page.
	 $this->next_tag_open		= '';
	 $this->next_tag_close		= '';
	 $this->prev_tag_open		= '';
	 $this->prev_tag_close		= '';
	 $this->num_tag_open		= '';
	
    // If our item count or per-page total is zero there is no need to continue.
    if ($this->total_rows == 0 OR $this->per_page == 0)
    {
        return '';
    }

    // Calculate the total number of pages
    $num_pages = ceil($this->total_rows / $this->per_page);

    // Is there only one page? Hm... nothing more to do here then.
    if ($num_pages == 1)
    {
        return '';
    }

    // Determine the current page number.
    $CI =& get_instance();

    if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
    {
        if ($CI->input->get($this->query_string_segment) != 0)
        {
            $this->cur_page = $CI->input->get($this->query_string_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }
    }
    else
    {
        if ($CI->uri->segment($this->uri_segment) != 0)
        {
            $this->cur_page = $CI->uri->segment($this->uri_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }
    }

    $this->num_links = (int)$this->num_links;

    if ($this->num_links < 1)
    {
        show_error('Your number of links must be a positive number.');
    }

    if ( ! is_numeric($this->cur_page))
    {
        $this->cur_page = 0;
    }

    // Is the page number beyond the result range?
    // If so we show the last page
    if ($this->cur_page > $this->total_rows)
    {
        $this->cur_page = ($num_pages - 1) * $this->per_page;
    }

    $uri_page_number = $this->cur_page;
    $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

    // Calculate the start and end numbers. These determine
    // which number to start and end the digit links with

	// Start the 5 pagination  coding
	
	/*
	echo $this->cur_page;
	exit; 
	*/
	/*
	if($num_pages <= 5) {
		$start = 1;
		$end = $num_pages;
	} else {
		
		if($this->cur_page == 1) {
			$start = 1;
			$end = 5;
		} else {
			if($this->cur_page+(5 - 2) < $num_pages ) {
			$start = $this->cur_page-1;
			$end =  ((($this->cur_page-1)+(5 - 2)) < $num_pages)? (($this->cur_page-1)+(5)) : $num_pages;
			} else {
				echo "vairam";
				exit;
				$start = $num_pages - (5 - 2);
				$end = $num_pages;
			}
		}
	}
	*/
	
	
	if($num_pages <= $this->custom_num_links) {
		$start = 1;
		$end = $num_pages;
	} else {
		
		if($this->cur_page == 1) {
			$start = 1;
			$end = $this->custom_num_links;
		} else {
	 
			if($this->cur_page+($this->custom_num_links - 1) < $num_pages ) {
			$start = $this->cur_page;
			$end =  (($this->cur_page+($this->custom_num_links - 1)) < $num_pages)? ($this->cur_page+($this->custom_num_links - 1)) : $num_pages;
			} else {
				$start = $num_pages - ($this->custom_num_links - 1);
				$end = $num_pages;
			}
		}
	}
	
	
	
	// Start the 5 pagination coding

    // Is pagination being used over GET or POST?  If get, add a per_page query
    // string. If post, add a trailing slash to the base URL if needed
    if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
    {
		

        $this->base_url = str_replace('?&', '?', rtrim($this->base_url).'?'.$this->query_string_segment.'=');

		 $this->base_url = str_replace('/&', '?', $this->base_url);
		
	
    }
    else
    {
        $this->base_url = rtrim($this->base_url, '/') .'/';
    }

    // And here we go...
    $output = '';

    // Render the "First" link
	//     if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1)) 
    if  ($this->first_link !== FALSE AND $this->cur_page > 1) 
    {
		
        $first_url = ($this->first_url == '') ? $this->base_url.$this->suffix  : $this->first_url.$this->suffix ;
	     // $first_url = strtok($_SERVER["REQUEST_URI"],'?');
		
        $output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
    }

    // Render the "previous" link
    if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
    {
        $i = $uri_page_number - $this->per_page;

        if ($i == 0 && $this->first_url != '')
        {
            $output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        }
        else
        {
            $i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
			
			if($i == '') 
				$custom_prev_link = $first_url;
			else 
				$custom_prev_link = $this->base_url.$i;
			
            $output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$custom_prev_link.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
        }

    }

    // Render the pages
    if ($this->display_pages !== FALSE)
    {
        // Write the digit links
		//  for ($loop = $start -1; $loop <= $end; $loop++)
        for ($loop = $start; $loop <= $end; $loop++)
        {
            $i = ($loop * $this->per_page) - $this->per_page;

            if ($i >= 0)
            {
                if ($this->cur_page == $loop)
                {
                    $output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
                }
                else
                {
                    $n = ($i == 0) ? '' : $i;

                    if ($n == '' && $this->first_url != '')
                    {
                        $output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
                    }
                    else
                    {
                        $n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;
						
						if($n != ''){
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$n.'"> '.$loop.'</a>'.$this->num_tag_close;
						} else {
							if($loop == 1) {
								$base_url_link =  $this->base_url.$this->suffix; 	
							} else {
								$base_url_link = $this->base_url; 
							}
							
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$base_url_link.$n.'" test> '.$loop.'</a>'.$this->num_tag_close;
						}
                    }
                }
            }
        }
    }

    // Render the "next" link
    if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
    {
        $output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.($this->cur_page * $this->per_page).$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
    }

    // Render the "Last" link
	// customize this condition
	//if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
    if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages AND $num_pages != $end)	
    {
        $i = (($num_pages * $this->per_page) - $this->per_page);
        $output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->last_link.'</a>'.$this->last_tag_close;
    }

    // Kill double slashes.  Note: Sometimes we can end up with a double slash
    // in the penultimate link so we'll kill all double slashes.
    $output = preg_replace("#([^:])//+#", "\\1/", $output);

    // Add the wrapper HTML if exists
    $output = $this->full_tag_open.$output.$this->full_tag_close;

    return $output;
}

}
// END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */