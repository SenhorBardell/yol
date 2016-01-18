<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<?php #search form
		#<form action="#" method="get" class="sidebar-form">
		#	<div class="input-group">
		#		<input type="text" name="q" class="form-control" placeholder="Поиск"/>
         #     <span class="input-group-btn">
          #      <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
           #   </span>
			#</div>
		#</form>
		#<!-- sidebar menu: : style can be found in sidebar.less --> ?>
		<ul class="sidebar-menu">

			<?php #<li class="header">Навигация</li> ?>
			<li class="treeview">
				<a href="#">
					<span>Справочник авто</span> <i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>{{link_to_action('VehicleTypeRefsController@index', 'Типы авто')}}</li>
					<li>{{link_to_action('MarkRefsController@index', 'Марка')}}</li>
					<li>{{link_to_action('ModelRefsController@listAll', 'Модель')}}</li>
					<li>{{link_to_action('BodyTypeRefsController@index', 'Типы кузовов')}}</li>
				</ul>
			</li>
			<li class="active"><?php echo link_to('/admin/users', 'Пользователи') ?></li>
			<li class="treeview">
				<a href="#">
					<span>Форумы</span> <i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>{{link_to('/admin/categories', 'Категории')}}</li>
					<li>{{link_to('/admin/posts', 'Посты')}}</li>
					<li>{{link_to('/admin/comments', 'Комментарии')}}</li>
				</ul>
			</li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
