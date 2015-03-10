<?php	!defined('IN_ART') && exit('Access Denied');

return array(
    'console' => array(
        'name'      => lang('console'),
        'default'   => 'welcome',
        'children'  => array(
            'home'   => array(
                'name'  => lang('home_top'),
                'url'   => 'index.php?act=welcome',  
                'parent'=> 'home', 
                'isnav'=>1,             
            ),
            'article'=> array(
                'name'  => lang('article_top'),
                'parent'=> 'article',
                'url'   => 'index.php?wskm=article',
                'isnav'=>1,
            ),            
            'user_manage' => array(
                'name'  => lang('user_manage_top'),
                'parent'=> 'user',
                'url'   => 'index.php?wskm=user',
                'isnav'=>1,
            ),
            'base_setting'  => array(
                'parent'=> 'setting',
                'name'  => lang('base_setting_top'),
                'url'   => 'index.php?wskm=setting',
                'isnav'=>1,
            ),
           'tool'  => array(
                'parent'=> 'tool',
                'name'  => lang('tool'),
                'url'   => 'index.php?wskm=tool&act=updatecache',
                'isnav'=>1,
            ),            
           'plugin'  => array(
                'parent'=> 'plugin',
                'name'  => lang('plugin'),
                'url'   => 'index.php?wskm=plugin',
                'isnav'=>1,
            ),
        ),
    ),
    'home'   => array(
        'default'   => 'base_setting',
        'children'  => array(
            'welcome'  => array(
                'name'  => lang('home_index'),
                'url'   => 'index.php?act=welcome',
            ),                         
            'navigation' => array(
                'name'  => lang('navigation'),
                'url'   => 'index.php?wskm=setting&act=nav',
            ),
            'friendlink'=>array(
                'name'  => lang('friendlink'),
                'url'   => 'index.php?wskm=setting&act=friendlink',
            ),            
            'announce'=>array(
    			'name'  => lang('announce'),
                'url'   => 'index.php?wskm=setting&act=announce',
            ),
            'ad'=>array(
    			'name'  => lang('ad'),
                'url'   => 'index.php?wskm=setting&act=ad',
            ),
        ),
    ),
    'setting'   => array(
        'default'   => 'base_setting',
        'children'  => array(
            'base_setting'  => array(
                'name'  => lang('base_setting'),
                'url'   => 'index.php?wskm=setting',
            ),
            'theme' => array(
                'name'  => lang('theme'),
                'url'   => 'index.php?wskm=theme',
            ),

        ),
    ),
    'user' => array(
        'default'   => 'user_manage',
        'children'  => array(
            'user_manage' => array(
                'name'  => lang('user_manage'),
                'url'   => 'index.php?wskm=user',
            ),
            'usergroup_name' => array(
                'name'  => lang('usergroup_name'),
                'url'   => 'index.php?wskm=user&act=usergroup',
            ),

        ),
    ),
 	'article' => array(
        'default'   => 'article',
        'children'  => array(
            'article' => array(
                'name'  => lang('article'),
                'url'   => 'index.php?wskm=article',
            ),
             'category' => array(
                'name'  => lang('category'),
                'url'   => 'index.php?wskm=category',
            ),
            'comment'=>array(
            	'name'  => lang('comment_verify'),
                'url'   => 'index.php?wskm=article&act=comment',
            ),
            'filter_word' =>array(
            	'name'  => lang('filter_word'),
                'url'   => 'index.php?wskm=article&act=filterword',
            ),            
            'poll' => array(
                'name'  => lang('poll'),
                'url'   => 'index.php?wskm=poll',
            ),
        ),
    ),
    'tool' => array(
        'default'   => 'update_cache',
        'children'  => array(
            'update_cache' => array(
                'name'  => lang('update_cache'),
                'url'   => 'index.php?wskm=tool&act=updatecache',
            ),
            'update_cachehtml' => array(
                'name'  => lang('update_cachehtml'),
                'url'   => 'index.php?wskm=tool&act=cachehtml',
            ),
            'db' => array(
                'name'  => lang('db_manage'),
                'url'   => 'index.php?wskm=tool&act=db',
            ),
            'loginlog' => array(
                'name'  => lang('loginlog'),
                'url'   => 'index.php?wskm=tool&act=loginlog',
            ),
    		
           
        ),
    ),
	'plugin' => array(
        'default'   => 'plugin',
        'children'  => array(
            'plugin' => array(
                'name'  => lang('plugin_manage'),
                'url'   => 'index.php?wskm=plugin',
            ),
           
        ),
    ),
);

?>