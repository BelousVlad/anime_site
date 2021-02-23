<?php

return array(
	'filter_list' => 'main/filteredList',
	'title_status' => 'main/title_status',
	'save_info' => 'user/save_info',
	'add_comment' => 'main/add_comment',
	'delete_title' => 'main/delete_title',
	'edit/create_new_title' => 'main/create_new_title',
	'edit/save_names' => 'main/edit_save_names',
	'edit/save_img' => 'main/edit_save_img',
	'edit/save_series' => 'main/edit_save_series',
	'edit/save_addition_info' => 'main/edit_save_addition_info',
	'edit/(\\d+)' => 'main/edit/$1',
	'profile/(\\d*)' => 'user/profile/$1',
	'profile' => 'user/profile/$1',
	'^(\\d+)$' => 'main/title/$1',
	'register_input' => 'user/register_in',
	'login_input' => 'user/login_in',
	'register' => 'user/registerView',
	'login' => 'user/loginView',
	'logout' => 'user/logout',
	'^$' => 'main/view'
);

?>	