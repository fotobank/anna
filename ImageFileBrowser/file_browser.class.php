<?php
// file_browser.class.php v0.2

// Copyright (c) 2006,7 Panos Kyriakakis (http://www.salix.gr)
// 
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
// 
    class File_browser {
		protected $ajax_handlers_file = '';
		protected $init_path = '';
		protected $cur_path = '';
		protected $browse_upper_level_init = FALSE;
		protected $disp_files_view_type = 0;
		protected $disp_files_per_row = 4;
		protected $disp_rows_per_page = 2;
		protected $disp_browser_id='file_browser_1';
		protected $disp_browser_div = '';
		protected $disp_browser_dir_div = '';
		protected $disp_browser_file_div = '';
		protected $disp_browser_img_dlg_div = '';
		protected  $disp_browser_img_view_dlg_div = '';
		protected $disp_dirs_cur_page=0;
		protected $disp_files_cur_page=0;
		protected $inited = FALSE;
		protected $ajax_init_params='';
		protected $sub_dirs = array();
		protected $dir_files = array();
		protected $selected_img_file ='';
		protected $click_action = 'img_dlg'; // or 'img_view'
		protected $image_extensions = array('jpg','gif','png','bmp');
		protected $swf_extensions = array('swf');

        function init(
                      $ajax_handlers_file,
                      $init_path, 
                      $cur_path='',
                      $disp_files_view_type=0,
                      $disp_files_per_row=4, 
                      $disp_rows_per_page=2,
                      $disp_browser_id='file_browser_1',
                      $click_action = 'img_dlg'
                     ) {
            $this->ajax_handlers_file=$ajax_handlers_file;
            $this->init_path = $init_path;
            $this->cur_path = $this->init_path;
            if( $cur_path!='' )
                $this->cur_path = $cur_path;
            
            $this->disp_files_view_type = $disp_files_view_type;
            $this->disp_files_per_row = $disp_files_per_row;
            if( $this->disp_files_per_row<=0 )
                $this->disp_files_per_row=4;
            $this->disp_rows_per_page = $disp_rows_per_page;
            if( $this->disp_rows_per_page<=0 )
                $this->disp_rows_per_page=2;
            $this->disp_browser_id = $disp_browser_id;
            $this->disp_browser_div = 'fb_container_'.$disp_browser_id;
            $this->disp_browser_dir_div = 'fb_container_dirs_'.$disp_browser_id;
            $this->disp_browser_file_div = 'fb_container_files_'.$disp_browser_id;
            $this->disp_browser_img_dlg_div = 'fb_container_img_dlg_'.$disp_browser_id;
            $this->disp_browser_img_view_dlg_div = 'fb_container_img_view_dlg_'.$disp_browser_id;
            $this->ajax_init_params =
                '&a_h_f='.$this->ajax_handlers_file.
                '&i_path='.$this->init_path.
                '&d_f_v_t='.$this->disp_files_view_type.
                '&d_f_p_r='.$this->disp_files_per_row.
                '&d_r_p_p='.$this->disp_rows_per_page.
                '&d_b_i='.$this->disp_browser_id;
            
            $this->click_action = $click_action;
            $this->inited = TRUE;
            
            $this->load_cur_dir_info();
        }   // end constructor ------------------------------------------------
        
        function get_http_vars() {
            if( isset($_REQUEST['fp']) ) 
                $this->disp_files_cur_page=$_REQUEST['fp'];
            else
                $this->disp_files_cur_page=0;
            if( isset($_REQUEST['dp']) ) 
                $this->disp_dirs_cur_page=$_REQUEST['dp'];
            else
                $this->disp_dirs_cur_page=0;
            if( isset($_REQUEST['cd']) ) 
                $this->cur_path = $_REQUEST['cd'];
            if( isset($_REQUEST['imdlg']) ) 
                $this->selected_img_file = $_REQUEST['imdlg'];
                
        }   // end function get_http_vars -------------------------------------
        
        function load_cur_dir_info() {
            if( !$this->inited ) 
                return(FALSE);
            $this->sub_dirs = array();
            $this->dir_files = array();

            if (is_dir($this->cur_path)) {
                $this->cur_path = str_replace('\\','/', realpath($this->cur_path)).'/';
                if ($dh = opendir($this->cur_path)) {
                    if( $this->cur_path!=$this->init_path ) {
                        if( is_readable( realpath($this->cur_path.'..')) ) {
                            $ddetails = array('descr'=>'One Level Up',
                                              'dir_name'=>'..'
                                             );
                            $this->sub_dirs[] = $ddetails;
                        }
                    }
                    while (($file = readdir($dh)) !== false) {
                        if( substr($file,0,1)!='.' ) {
                            if( is_dir($this->cur_path.$file) ) {
                                $ddetails = array('descr'=>$file,
                                                  'dir_name'=>$file
                                                 );
                                $this->sub_dirs[] = $ddetails;
                            }
                            else {
                                if( filetype($this->cur_path.$file)=='file' ) {
                                    $pf = pathinfo($this->cur_path.$file);
                                    $fs = filesize($this->cur_path.$file);
                                    $fdetails = array( 'file_name' => $file,
                                                       'file_type' => '',
                                                       'file_size' => $fs
                                                     );
                                    if( in_array( strtolower($pf["extension"]), $this->image_extensions) ) {
                                        $fdetails['file_type']='Image';
                                    }
                                    elseif ( in_array( strtolower($pf["extension"]), $this->swf_extensions) ) {
                                        $fdetails['file_type']='Flash';
                                    }
                                    else {
                                        $fdetails['file_type']='Other';
                                    }
                                    $this->dir_files[] = $fdetails;
                                }
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }   // end function load_dir_info -------------------------------------
        
        function add_browser_jscripts($prototype_url="") {
            if( $prototype_url!='' ) {
            ?>
                <script src="<?php echo $prototype_url; ?>prototype.js" type="text/javascript"></script>
                <script src="<?php echo $prototype_url; ?>scriptaculous.js" type="text/javascript"></script>
                <script src="<?php echo $prototype_url; ?>unittest.js" type="text/javascript"></script>
            <?php
            }
            ?>
                <script language="javascript">
                    var img_url_<?php echo $this->disp_browser_id; ?>='';
                    
                    /* Strip whitespace from the beginning and end of a string */
                    if (typeof String.prototype.trim == "undefined") {
                        String.prototype.trim = function () {
                            var s = this.replace(/^\s*/, "");
                            return s.replace(/\s*$/, "");
                        }
                    }

                    function file_browser_list_files_<?php echo $this->disp_browser_id; ?>(pagenum) {
                        var cur_path = $('<?php echo 'fb_cur_dir_'.$this->disp_browser_id; ?>').value;
                        $('<?php echo $this->disp_browser_file_div; ?>').innerHTML='Loading...';
                        var params = 'handle=1&<?php echo $this->ajax_init_params; ?>&c_path='+cur_path+'&fp='+pagenum;
                        new Ajax.Updater('<?php echo $this->disp_browser_file_div; ?>', 
                                        '<?php echo $this->ajax_handlers_file; ?>',
                                        {asynchronous:true,
                                         method:'get', 
                                         parameters:params,
                                         onFailure: file_browser_list_files_reportError_<?php echo $this->disp_browser_id; ?>
                                        }
                                    );
                    }
                    function file_browser_list_files_reportError_<?php echo $this->disp_browser_id; ?>(request) {
                        $('<?php echo $this->disp_browser_file_div; ?>').innerHTML='ERRORRRR';
                    }
                    
                    function file_browser_list_dirs_<?php echo $this->disp_browser_id; ?>(pagenum) {
                        var cur_path = $('<?php echo 'fb_cur_dir_'.$this->disp_browser_id; ?>').value;
                        $('<?php echo $this->disp_browser_dir_div; ?>').innerHTML='Loading...';
                        var params = 'handle=10&<?php echo $this->ajax_init_params; ?>&c_path='+cur_path+'&dp='+pagenum;
                        new Ajax.Updater('<?php echo $this->disp_browser_dir_div; ?>', 
                                        '<?php echo $this->ajax_handlers_file; ?>',
                                        {asynchronous:true,
                                         method:'get', 
                                         parameters:params,
                                         onFailure: file_browser_list_dirs_reportError_<?php echo $this->disp_browser_id; ?>
                                        }
                                    );
                    }
                    function file_browser_list_dirs_reportError_<?php echo $this->disp_browser_id; ?>(request) {
                        $('<?php echo $this->disp_browser_dir_div; ?>').innerHTML='ERRORRRR';
                    }
                    
                    function file_browser_change_dir_<?php echo $this->disp_browser_id; ?>(dirname) {
                        var cur_path = $('<?php echo 'fb_cur_dir_'.$this->disp_browser_id; ?>').value;
                        $('<?php echo $this->disp_browser_div; ?>').innerHTML='Loading...';
                        var params = 'handle=11&<?php echo $this->ajax_init_params; ?>&c_path='+cur_path+'&cd='+dirname;
                        new Ajax.Updater('<?php echo $this->disp_browser_div; ?>', 
                                        '<?php echo $this->ajax_handlers_file; ?>',
                                        {asynchronous:true,
                                         method:'get', 
                                         parameters:params,
                                         onFailure: file_browser_change_dir_reportError_<?php echo $this->disp_browser_id; ?>
                                        }
                                    );                        
                    }

                    function file_browser_change_dir_reportError_<?php echo $this->disp_browser_id; ?>(request) {
                        $('<?php echo $this->disp_browser_dir_div; ?>').innerHTML='ERRORRRR';
                    }

                    function file_browser_show_img_dlg_<?php echo $this->disp_browser_id; ?>(filename,path,w,h,url) {
                        var cur_path = $('<?php echo 'fb_cur_dir_'.$this->disp_browser_id; ?>').value;
                        $('<?php echo 'file_name_'.$this->disp_browser_id;?>').innerHTML = filename;
                        $('<?php echo 'file_location_'.$this->disp_browser_id;?>').innerHTML = path;
                        $('<?php echo 'size_width_'.$this->disp_browser_id;?>').value = w;
                        $('<?php echo 'size_height_'.$this->disp_browser_id;?>').value = h;
                        $('<?php echo 'size_border_'.$this->disp_browser_id;?>').value=0;
                        $('<?php echo 'size_vspace_'.$this->disp_browser_id;?>').value=0;
                        $('<?php echo 'size_hspace_'.$this->disp_browser_id;?>').value=0;
                        $('<?php echo 'size_align_'.$this->disp_browser_id;?>').value='';
                        $('<?php echo 'img_alt_text_'.$this->disp_browser_id;?>').value='';
                        $('<?php echo 'img_title_text_'.$this->disp_browser_id;?>').value='';

                        img_url_<?php echo $this->disp_browser_id; ?>=url;
                        $('<?php echo 'img_preview_'.$this->disp_browser_id;?>').src=url;
                    	if(w>h) {
                    		$('<?php echo 'img_preview_'.$this->disp_browser_id;?>').width=65;
                    		$('<?php echo 'img_preview_'.$this->disp_browser_id;?>').height=Math.round( 65* h/w);
                    	}
                    	else {
                    		$('<?php echo 'img_preview_'.$this->disp_browser_id;?>').width=Math.round( 65* w/h);
                    		$('<?php echo 'img_preview_'.$this->disp_browser_id;?>').height=65;
                    	}
                        file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();
                        
                        Effect.toggle('<?php echo $this->disp_browser_dir_div; ?>','appear', 
                                        {duration:0.5,
                                         afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_img_dlg_div; ?>_top','appear')
                                                                 }
                                        }
                                     );
                        Effect.toggle('<?php echo $this->disp_browser_file_div; ?>','appear',
                                        { duration:0.5, 
                                          afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_img_dlg_div; ?>','appear')
                                                                  } 
                                        }
                                     );
                        
                    }
                    
                    function file_browser_hide_img_dlg_<?php echo $this->disp_browser_id; ?>() {
                        Effect.toggle('<?php echo $this->disp_browser_img_dlg_div; ?>_top',
                                      'appear',
                                      { duration:0.5, afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_dir_div; ?>','appear', {duration:0.5});
                                                                 } 
                                      });
                        Effect.toggle('<?php echo $this->disp_browser_img_dlg_div; ?>',
                                      'appear',
                                      { duration:0.5, afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_file_div; ?>','appear', {duration:0.5});
                                                                 } 
                                      });
                    }

                    function file_browser_show_img_view_dlg_<?php echo $this->disp_browser_id; ?>(filename,path,w,h,url) {
                        var cur_path = $('<?php echo 'fb_cur_dir_'.$this->disp_browser_id; ?>').value;
                        $('<?php echo 'img_view_file_name_'.$this->disp_browser_id;?>').innerHTML = filename;

                        img_url_<?php echo $this->disp_browser_id; ?>=url;
						var img_view_preview =  $('<?php echo 'img_view_preview_'.$this->disp_browser_id;?>');
						img_view_preview.src=url;
						img_view_preview.width=w;
						img_view_preview.height=h;

                        Effect.toggle('<?php echo $this->disp_browser_dir_div; ?>','appear', 
                                        {duration:0.5,
                                         afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_img_view_dlg_div; ?>_top','appear')
                                                                 }
                                        }
                                     );
                        Effect.toggle('<?php echo $this->disp_browser_file_div; ?>','appear',
                                        { duration:0.5, 
                                          afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_img_view_dlg_div; ?>','appear')
                                                                  } 
                                        }
                                     );
                    }
                    
                    function file_browser_hide_img_view_dlg_<?php echo $this->disp_browser_id; ?>() {
                        Effect.toggle('<?php echo $this->disp_browser_img_view_dlg_div; ?>_top',
                                      'appear',
                                      { duration:0.5, afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_dir_div; ?>','appear', {duration:0.5});
                                                                 } 
                                      });
                        Effect.toggle('<?php echo $this->disp_browser_img_view_dlg_div; ?>',
                                      'appear',
                                      { duration:0.5, afterFinish: function() {
                                                                    Effect.toggle('<?php echo $this->disp_browser_file_div; ?>','appear', {duration:0.5});
                                                                 } 
                                      });
                    }

                    function file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>() {
                        var w = parseInt($('<?php echo 'size_width_'.$this->disp_browser_id;?>').value);
                        var h = parseInt($('<?php echo 'size_height_'.$this->disp_browser_id;?>').value);
                        var url = img_url_<?php echo $this->disp_browser_id; ?>;
                        var b = parseInt($('<?php echo 'size_border_'.$this->disp_browser_id;?>').value);
                        var align = $('<?php echo 'size_align_'.$this->disp_browser_id;?>').value.trim();
                        var vspace = parseInt($('<?php echo 'size_vspace_'.$this->disp_browser_id;?>').value);
                        var hspace = parseInt($('<?php echo 'size_hspace_'.$this->disp_browser_id;?>').value);
                        var at = $('<?php echo 'img_alt_text_'.$this->disp_browser_id;?>').value.trim();
                        var tt = $('<?php echo 'img_title_text_'.$this->disp_browser_id;?>').value.trim();
                        var link = '<img src="'+url+'"';
                        if(isNaN(w)) 
                            $('<?php echo 'size_width_'.$this->disp_browser_id;?>').value='';
                        else {
                            if( w!=0 )
                                link = link+ ' width="'+w+'" ';
                        }
                        if(isNaN(h)) 
                            $('<?php echo 'size_height_'.$this->disp_browser_id;?>').value='';
                        else {
                            if( h!=0 )
                                link = link+ ' height="'+h+'" ';
                        }
                        if(isNaN(vspace)) 
                            $('<?php echo 'size_vspace_'.$this->disp_browser_id;?>').value='';
                        else {
                            if( vspace!=0 )
                                link = link+ ' vspace="'+vspace+'" ';
                        }
                        if(isNaN(hspace)) 
                            $('<?php echo 'size_hspace_'.$this->disp_browser_id;?>').value='';
                        else {
                            if( hspace!=0 )
                                link = link+ ' hspace="'+hspace+'" ';
                        }
                        if(!isNaN(b)) 
                            if( b!=0 )
                                link = link+ ' border="'+b+'" ';
                        if( align!='' ) {
                            link = link+ ' align="'+align+'" ';
                        }
                        if( at!='' ) {
                            link = link+ ' alt="'+at+'" ';
                        }
                        if( tt!='' ) {
                            link = link+ ' title="'+tt+'" ';
                        }
                        link = link+ ' />';
                        $('<?php echo 'img_link_'.$this->disp_browser_id;?>').value = link;
                    }
                    
                    function insert_to_tinymce() {
                        //tinyMCE.execInstanceCommand('page_content', 'mceFocus');
                        tinyMCE.execCommand('mceInsertContent', false, $('<?php echo 'img_link_'.$this->disp_browser_id;?>').value);
                    }
                </script>
            <?php            
        }   // end function add_browser_jscripts ------------------------------
        
        function place_browser($only_inner_divs=FALSE) {
            if(!$only_inner_divs) {
            ?>
                <div id="<?php echo $this->disp_browser_div; ?>">
            <?php
            }
            ?>
                    <div style="height:40px;">
                        <div id="<?php echo $this->disp_browser_dir_div; ?>" style="height:40px;">
                            <?php $this->show_dirs_pane() ?>
                        </div>
                        <div id="<?php echo $this->disp_browser_img_dlg_div;?>_top" style="display:none;height:40px;" >
                            Image tag properties
                        </div>
                        <div id="<?php echo $this->disp_browser_img_view_dlg_div;?>_top" style="display:none;height:40px;" >
                            Image view
                        </div>
                    </div>
                    <div style="border: 1px solid #eee; "><img src="images/folder_open.png" align="absmiddle">&nbsp;&nbsp;<?php echo $this->cur_path; ?></div>
                    <div id="<?php echo $this->disp_browser_img_dlg_div;?>" style="display:none;" >
                        <?php $this->place_image_link_dialog(); ?>
                        <div align="right">
                            <a href="javascript:insert_to_tinymce();">To Editor</a>
                            <a href="javascript:file_browser_hide_img_dlg_<?php echo $this->disp_browser_id; ?>();">Back</a>
                        </div>
                    </div>
                    <div id="<?php echo $this->disp_browser_img_view_dlg_div;?>" style="display:none;" >
                        <?php $this->place_image_view_dialog(); ?>
                        <div align="right">
                            <a href="javascript:file_browser_hide_img_view_dlg_<?php echo $this->disp_browser_id; ?>();">Back</a>
                        </div>
                    </div>
                    <div id="<?php echo $this->disp_browser_file_div;?>" style="height:250px; overflow:auto;" >
                        <?php $this->show_files_pane() ?>
                    </div>
            <?php
            if(!$only_inner_divs) {
            ?>
                </div>
            <?php
            }
        }   // end function place_browser -------------------------------------
        
        function show_files_pane() {
            switch( $this->disp_files_view_type ) {
                default:
                    $this->show_files_pane_thumbs();
            }
        }   // end function show_files_pane -----------------------------------
        
        function calc_img_size($size,$max_wh) {
        	$new_size = array();
        	$w = $size[0]; $h=$size[1];
        	if($w>$h) {
        		$new_size[0]=$max_wh;
        		$new_size[1]=round( $max_wh* $h/$w, 0);
        	}
        	else {
        		$new_size[0]=round( $max_wh* $w/$h, 0);
        		$new_size[1]=$max_wh;
        	}
        	return($new_size);
        }   // end function calc_img_size -------------------------------------

        
        function show_files_pane_thumbs() {
            $picnt= $this->disp_files_per_row*$this->disp_rows_per_page;
            $tot_pages = ceil( count($this->dir_files)/$picnt);
            if( $this->disp_files_cur_page>$tot_pages )
                $this->disp_files_cur_page=$tot_pages-1;
            $start = $this->disp_files_cur_page*$picnt;
            if( $start>count($this->dir_files) ) 
            	$start=0;
            $page_items = array_slice ($this->dir_files,$start,$picnt);
            
            if( count($page_items)!=0 ) {
    			?>
                <table style="width: 100%">
                <?php            
    			$itmIdx = 1;
    			foreach( $page_items as $item ) {
    				if( $itmIdx == 1 )
    					echo "<tr>";
    				if( $item['file_type']=='Image' ) {
    				    $photo_file = $this->cur_path.$item['file_name'];
    				    $img_src = $this->htmlpath( $this->cur_path.$item['file_name']);
    				    $size = getimagesize($photo_file);
    				    $size = $this->calc_img_size($size,65);
    				    
                    }
                    else {
    				    $img_src = 'images/empty.png';
    				    $size=array(65,65);
                    }
    			?>
        			<td width="180px">
        			    <div style="border: 1px solid #eee; padding:10px; margin-right:10px;">
        				<table width="100%">
        					<tr>
        						<td style="width:65px;">
        						    <?php 
        						    if( $item['file_type']=='Image' ) { 
        						        $img_info = getimagesize($this->cur_path.$item['file_name']);
        						        switch( $this->click_action ) {
        						            case 'img_dlg':
        						                echo "<a href=\"javascript:file_browser_show_img_dlg_{$this->disp_browser_id}(";
        						                echo "'{$item['file_name']}','{$this->cur_path}',{$img_info[0]},{$img_info[1]},'{$img_src}')\">";
        						                break;
        						            default:
        						                echo "<a href=\"javascript:file_browser_show_img_view_dlg_{$this->disp_browser_id}(";
        						                echo "'{$item['file_name']}','{$this->cur_path}',{$img_info[0]},{$img_info[1]},'{$img_src}')\">";
        						        }
        						        
        						    } 
        						    ?>
        							<div style="height:65px; width:65px;">        							
                    					<img  border="0"
                    					    alt="<?php echo($item['file_name']); ?>" 
                    						title="<?php echo($item['file_name']); ?>" 
                    						align="center"
                    						src="<?php echo $img_src; ?>"
                    						width="<?php echo $size[0]; ?>px"
                    						height="<?php echo $size[1]; ?>px"
                    						>
        							</div>
        							<?php if( $item['file_type']=='Image' ) { ?>
        							</a>
        							<?php } ?>
        						</td>
        						<td style="vertical-align:top;">
        						<?php echo $item['file_type']; ?><br />
        						<?php echo $this->returnFileSize($item['file_size']); ?><br />
        						</td>
        					</tr>
        					<tr>
        						<td style="vertical-align:bottom;" colspan="2">
        						    <?php echo $item['file_name']; ?>
        						</td>
        					</tr>
        				</table>
        				</div>
        			</td>
    
    			<?php
    				if( $itmIdx % $this->disp_files_per_row == 0 ) {
    					echo "</tr>";
    					$itmIdx =0;
    				}
    				$itmIdx++;
    			}
    			if( $itmIdx>1 && $itmIdx<$this->disp_files_per_row ) {
    				$remainder = $itmIdx % $this->disp_files_per_row;
    				for($i=0; $i<$remainder-1; $i++)
    					echo "<td>&nbsp;</td>";
    				echo "</tr>";
    			}
    			if( count($this->dir_files)>$picnt ) {
    			    echo "<tr>";
    			    echo '<td colspan="2" align="left">';
    			    if( $this->disp_files_cur_page>0 )
    			        echo '<a href="javascript:file_browser_list_files_'.$this->disp_browser_id.'('.($this->disp_files_cur_page-1).')">Prev page</a>';
    			    else
    			        echo '&nbsp;';
    			    echo '</td>';
    			    echo '<td colspan="2" align="right">';
    			    if( $this->disp_files_cur_page<$tot_pages-1 )
    			        echo '<a href="javascript:file_browser_list_files_'.$this->disp_browser_id.'('.($this->disp_files_cur_page+1).')">Next page</a>';
    			    else
    			        echo '&nbsp;';			    
    			    echo '</td>';
    			    echo "</tr>";
    			}
    			?>
                </table>
                <?php
            }
            else {
                echo 'No files found';
            }
        }   // end function show_files_pane_thumbs ----------------------------
        
        function show_dirs_pane() {
            echo '<input type="hidden" id="fb_cur_dir_'.$this->disp_browser_id.'" value="'.$this->cur_path.'">';
            switch( $this->disp_files_view_type ) {
                default:
                    $this->show_dir_pane_thumbs();
            }
        }   // end function show_dirs_pane ------------------------------------
        
        function show_dir_pane_thumbs() {
            $dirs_per_page = $this->disp_files_per_row+2;
            $tot_pages = ceil( count($this->sub_dirs)/$dirs_per_page);
            if( $this->disp_dirs_cur_page>$tot_pages )
                $this->disp_dirs_cur_page=$tot_pages-1;
            $start = $this->disp_dirs_cur_page*$dirs_per_page;

            if( $start>count($this->sub_dirs) ) 
            	$start=0;
            $pc = $dirs_per_page;
            if($start+$dirs_per_page>count($this->sub_dirs))
                $pc = count($this->sub_dirs)-$start;
            $page_items = array_slice ($this->sub_dirs,$start,$pc);
            if( count($page_items)!=0 ) {
                echo '<table width="100%"><tr>';
    			if( count($this->sub_dirs)>$dirs_per_page ) {
    			    if( $this->disp_dirs_cur_page>0 ) {
        			    ?>
                			<td style="width:30px;">
       					        <a href="javascript:file_browser_list_dirs_<?php echo $this->disp_browser_id; ?>(<?php echo ($this->disp_dirs_cur_page-1);?>)"><img src="images/1leftarrow.png" border="0" alt="Next page" title="Next page"></a>
        				    </td>
          				<?php
    			    }
    			}
    			foreach( $page_items as $item ) {
    			    if( $item['dir_name']=='..' )
    			        $img_src = 'images/up.png';
    			    else
    			        $img_src = 'images/folder.png';
    			    $size=array(32,32);
    			?>
        			<td style="width:30px;">
							<a href="javascript:file_browser_change_dir_<?php echo $this->disp_browser_id;?>('<?php echo $this->cur_path.$item['dir_name'];?>/')">
            					<img  border="0"
            					    alt="<?php echo($item['descr']); ?>" 
            						title="<?php echo($item['descr']); ?>" 
            						align="center"
            						src="<?php echo $img_src; ?>"
            						width="<?php echo $size[0]; ?>px"
            						height="<?php echo $size[1]; ?>px"
            						>
							</a>
				    </td>
				    <?php 
				     if( $item['dir_name']!='..' ) { 
					    echo '<td style="vertical-align:top;">';
					    echo $item['dir_name'];
					    echo '</td>';
                     }
    			}
    			if( count($page_items)-1<$dirs_per_page ) {
    				$remainder =$dirs_per_page-count($page_items);
    				for($i=0; $i<$remainder; $i++) {
    				    echo '<td width="150px">&nbsp;</td>';
        		    }
    			}
    			if( count($this->sub_dirs)>$dirs_per_page ) {
    			    if( $this->disp_dirs_cur_page<$tot_pages-1 ) {
        			    ?>
                			<td style="width:30px;">
       					        <a href="javascript:file_browser_list_dirs_<?php echo $this->disp_browser_id; ?>(<?php echo ($this->disp_dirs_cur_page+1);?>)"><img src="images/1rightarrow.png" border="0" alt="Next page" title="Next page"></a>
        				    </td>
           				<?php
    			    }
    			}
   				echo "</tr>";
                echo "</table>";
            }
            else {
                echo 'No forders found';
            }
        }   // end function show_dir_pane_thumbs ------------------------------

        function place_image_link_dialog() {
            ?>
           <fieldset>
                <legend>File</legend>
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100px">Selected File:</td>
                        <td><span id="file_name_<?php echo $this->disp_browser_id;?>"></span></td>
                        <td  width="100px" rowspan="2">
							<div style="height:65px; width:65px;">        							
            					<img id="img_preview_<?php echo $this->disp_browser_id;?>"  
            					    border="0"
            					    alt="" 
            						title="" 
            						align="center"
            						src="images/empty.png"
            						width="65px"
            						height="65px"
            						>
							</div>
                        </td>
                    </tr>
                    <tr>
                        <td>File location:</td>
                        <td><span id="file_location_<?php echo $this->disp_browser_id;?>"></span></td>
                    </tr>
               </table>
           </fieldset>
           <fieldset>
                <legend>Size & Border</legend>
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="60px">Width:</td>
                        <td>
                            <input id="size_width_<?php echo $this->disp_browser_id;?>" 
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onkeyup="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onBlur="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                                style="width:40px;" type="text" value="">
                        </td>
                        <td width="60px">Height:</td>
                        <td>
                            <input id="size_height_<?php echo $this->disp_browser_id;?>" 
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onkeyup="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onBlur="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                                style="width:40px;" type="text" value="">
                        </td>
                        <td width="60px">Hspace:</td>
                        <td>
                            <input id="size_hspace_<?php echo $this->disp_browser_id;?>" 
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onkeyup="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onBlur="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                                style="width:40px;" type="text" value="">
                        </td>
                        <td width="60px">Vspace:</td>
                        <td>
                            <input id="size_vspace_<?php echo $this->disp_browser_id;?>" 
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onkeyup="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onBlur="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                                style="width:40px;" type="text" value="">
                        </td>
                        <td width="60px">Border:</td>
                        <td>
                            <select id="size_border_<?php echo $this->disp_browser_id;?>"
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                            >
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </td>
                        <td width="60px">Align:</td>
                        <td>
                            <select id="size_align_<?php echo $this->disp_browser_id;?>"
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                            >
                                <option value="">None</option> 
                                <option value="baseline">Baseline</option> 
                                <option value="top">Top</option> 
                                <option value="middle">Middle</option> 
                                <option value="bottom">Bottom</option> 
                                <option value="texttop">Texttop</option> 
                                <option value="absmiddle">Absmiddle</option> 
                                <option value="absbottom">Absbottom</option> 
                                <option value="left">Left</option> 
                                <option value="right">Right</option> 
                            </select>
                        </td>
                    <tr>
                </table>
           </fieldset>
           <fieldset>
                <legend>Texts</legend>
               <table width="100%" cellspacing="0" cellpadding="0">
                    <tr >
                        <td width="100px">Title text:</td>
                        <td>
                            <input id="img_title_text_<?php echo $this->disp_browser_id;?>" 
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onkeyup="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onBlur="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                                type="text" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <td>Alt text:</td>
                        <td>
                            <input id="img_alt_text_<?php echo $this->disp_browser_id;?>" 
                                onchange="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onkeyup="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
        						onBlur="file_browser_make_img_link_<?php echo $this->disp_browser_id; ?>();"
                                type="text" style="width:100%;">
                        </td>
                    </tr>
                </table>
           </fieldset>
           <fieldset>
                <legend>Link</legend>
                <input id="img_link_<?php echo $this->disp_browser_id;?>" type="text" style="width:100%;">
           </fieldset>
            <?php
        }   // end function place_image_link_dialog ---------------------------
        
        function place_image_view_dialog() {
            ?>
           <fieldset>
                <legend>File: <span id="img_view_file_name_<?php echo $this->disp_browser_id;?>"></span></legend>
				<img id="img_view_preview_<?php echo $this->disp_browser_id;?>"  
				    border="0"
				    alt="" 
					title="" 
					align="center"
					src="images/empty.png"
					width="65px"
					height="65px"
					>
            </fieldset>
            <?php
        }   // end function place_image_view_dialog ---------------------------
        
        // HELPER METHODS -----------------------------------------------------
    	function returnFileSize($sizeInBytes,$precision=2){
    		if($sizeInBytes < 1024){
    			return "$sizeInBytes bytes";
    		}else{
    			$k = intval($sizeInBytes/1024);
    			if($k < 1024){
    				return $k . " Kb";
    			}else{
    				$m = number_format((($sizeInBytes/1024) / 1024),2);
    				return $m . " Mb";
    			}
    		}
    	}

        function htmlpath($relative_path) {
           $realpath=str_replace('\\','/', realpath($relative_path));
           $htmlpath=str_replace($_SERVER['DOCUMENT_ROOT'],'',$realpath);
           return $htmlpath;
        }
        
        // STATIC METHODS -----------------------------------------------------
      static  function is_called_to_handle_ajax_calls() {
            if( isset($_GET['handle']) )
                return( TRUE );
            else
                return( FALSE );
        }   // end function is_called_to_handle_ajax_calls --------------------
        
      static  function create_instance_for_ajax_call() {
            $ajax_handlers_file='';
            $init_path='';
            $cur_path='';
            $disp_files_view_type=0;
            $disp_files_per_row=4;
            $disp_rows_per_page=2;
            $disp_browser_id='file_browser_1';
            if( isset($_GET['a_h_f']) && isset($_GET['i_path']) && isset($_GET['c_path']) &&
                isset($_GET['d_f_p_r']) && isset($_GET['d_r_p_p']) &&
                isset($_GET['d_b_i']) 
              ){
                $ajax_handlers_file=$_GET['a_h_f'];
                $init_path=$_GET['i_path'];
                $cur_path=$_GET['c_path'];
                $disp_files_view_type=$_GET['d_f_v_t'];
                $disp_files_per_row=$_GET['d_f_p_r'];
                $disp_rows_per_page=$_GET['d_r_p_p'];
                $disp_browser_id=$_GET['d_b_i'];
                
                $ob = new File_browser();
                $ob->init($ajax_handlers_file,
                          $init_path, 
                          $cur_path,
                          $disp_files_view_type,
                          $disp_files_per_row, 
                          $disp_rows_per_page,
                          $disp_browser_id);
                $ob->get_http_vars();
                return($ob);
            }
            else {
                return(FALSE);
            }
        }   // end function create_instance_for_ajax_call ---------------------

    }   // END CLASS File_browser =============================================

    // ========================================================================
    // AJAX Handling Stuff ====================================================
    // ========================================================================
    if( File_browser::is_called_to_handle_ajax_calls() ) {
        $ob = File_browser::create_instance_for_ajax_call();
        if( $ob===FALSE ) {
            die('Not correctly setuped to handle ajax responses');
        }
        else {
            switch( $_GET['handle'] ) {
                case 1:
                    $ob->show_files_pane();
                    break;
                case 10:
                    $ob->show_dirs_pane();
                    break;
                case 11:
                    $ob->load_cur_dir_info();
                    $ob->place_browser(TRUE);
                    break;    
                case 20:            
                    $ob->show_image_link_dialog();
                    break;
                default:
                    $ob->place_browser();
                    break;
                    
            }
        }
    }

?>