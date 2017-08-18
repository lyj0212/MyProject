<ul class="nav nav-tabs hidden-xs mb20">
	<li<?php if(empty($active)) : ?> class="active"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>NULL)); ?>">기본 설정</a></li>
	<li<?php if($this->tableid) : ?><?php if($active == 'list') : ?> class="active"<?php endif; ?><?php else : ?> class="disabled"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>'list')); ?>" rel="tooltip">목록 설정</a></li>
	<li<?php if($this->tableid) : ?><?php if($active == 'category') : ?> class="active"<?php endif; ?><?php else : ?> class="disabled"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>'category')); ?>" rel="tooltip">카테고리 설정</a></li>
	<?php /*<li<?php if( ! empty($data['tableid'])) : ?><?php if($active == 'design') : ?> class="active"<?php endif; ?><?php else : ?> class="disabled"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>'design')); ?>" rel="tooltip">디자인 설정</a></li>*/ ?>
</ul>

<ul class="nav nav-tabs visible-xs mb20">
	<li class="pull-right active dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">더보기 <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li<?php if(empty($active)) : ?> class="active"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>NULL)); ?>">기본 설정</a></li>
			<li<?php if($this->tableid) : ?><?php if($active == 'list') : ?> class="active"<?php endif; ?><?php else : ?> class="disabled"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>'list')); ?>" rel="tooltip">목록 설정</a></li>
			<li<?php if($this->tableid) : ?><?php if($active == 'category') : ?> class="active"<?php endif; ?><?php else : ?> class="disabled"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>'category')); ?>" rel="tooltip">카테고리 설정</a></li>
			<?php /*<li<?php if( ! empty($data['tableid'])) : ?><?php if($active == 'design') : ?> class="active"<?php endif; ?><?php else : ?> class="disabled"<?php endif; ?>><a href="<?php echo $this->link->get(array('action'=>'write', 'active'=>'design')); ?>" rel="tooltip">디자인 설정</a></li>*/ ?>
		</ul>
	</li>
</ul>
