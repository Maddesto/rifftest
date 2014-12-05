<?php
return array(
    'guest'=> array(
        'home',
        'login',
        'register',
		'confirm',
		'admin'
    ),
    'admin'=> array(
        'admin/user_list',
        'admin/user_list/admin_edit',
		'login',
		'admin/logout',
		'admin/user_list/pager'
    ),
	'user'=> array(
        'home/view',
        'home/view/edit',
        'logout'
    ),
);