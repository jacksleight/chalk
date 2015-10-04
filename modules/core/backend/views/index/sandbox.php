<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<div class="body">

	<h2>Button</h2>
	<p>
		<span class="btn">Button</span>
		<span class="btn btn-out">Button</span>
		<span class="btn btn-light">Button</span>
		<span class="btn btn-light btn-out">Button</span>
		<span class="btn btn-lighter">Button</span>
		<span class="btn btn-lighter btn-out">Button</span>
		<span class="btn btn-positive">Button</span>
		<span class="btn btn-positive btn-out">Button</span>
		<span class="btn btn-negative">Button</span>
		<span class="btn btn-negative btn-out">Button</span>
		<span class="btn btn-focus">Button</span>
		<span class="btn btn-focus btn-out">Button</span>
		<span class="btn btn-active">Button</span>
		<span class="btn btn-active btn-out">Button</span>
	</p>

	<h2>Badge</h2>
	<p>
		<span class="badge">Badge</span>
		<span class="badge badge-out">Badge</span>
		<span class="badge badge-light">Badge</span>
		<span class="badge badge-light badge-out">Badge</span>
		<span class="badge badge-lighter">Badge</span>
		<span class="badge badge-lighter badge-out">Badge</span>
		<span class="badge badge-positive">Badge</span>
		<span class="badge badge-positive badge-out">Badge</span>
		<span class="badge badge-negative">Badge</span>
		<span class="badge badge-negative badge-out">Badge</span>
		<span class="badge badge-focus">Badge</span>
		<span class="badge badge-focus badge-out">Badge</span>
		<span class="badge badge-active">Badge</span>
		<span class="badge badge-active badge-out">Badge</span>
	</p>

	<div class="dark">
		
	<h2>Button</h2>
	<p>
		<span class="btn">Button</span>
		<span class="btn btn-out">Button</span>
		<span class="btn btn-light">Button</span>
		<span class="btn btn-light btn-out">Button</span>
		<span class="btn btn-lighter">Button</span>
		<span class="btn btn-lighter btn-out">Button</span>
		<span class="btn btn-positive">Button</span>
		<span class="btn btn-positive btn-out">Button</span>
		<span class="btn btn-negative">Button</span>
		<span class="btn btn-negative btn-out">Button</span>
		<span class="btn btn-focus">Button</span>
		<span class="btn btn-focus btn-out">Button</span>
		<span class="btn btn-active">Button</span>
		<span class="btn btn-active btn-out">Button</span>
	</p>

	<h2>Badge</h2>
	<p>
		<span class="badge">Badge</span>
		<span class="badge badge-out">Badge</span>
		<span class="badge badge-light">Badge</span>
		<span class="badge badge-light badge-out">Badge</span>
		<span class="badge badge-lighter">Badge</span>
		<span class="badge badge-lighter badge-out">Badge</span>
		<span class="badge badge-positive">Badge</span>
		<span class="badge badge-positive badge-out">Badge</span>
		<span class="badge badge-negative">Badge</span>
		<span class="badge badge-negative badge-out">Badge</span>
		<span class="badge badge-focus">Badge</span>
		<span class="badge badge-focus badge-out">Badge</span>
		<span class="badge badge-active">Badge</span>
		<span class="badge badge-active badge-out">Badge</span>
	</p>

	</div>

	<h2>Toolbar</h2>
	<ul class="toolbar toolbar-right">
		<li class="">
			<a href="" class="btn">Test</a>
		</li>
		<li>
			<ul class="toolbar toolbar-tight">
				<li>
					<a href="?page=1" class="btn btn-quieter active">1</a>
				</li>
				<li>
					<a href="?page=1" class="btn btn-quieter btn-icon icon-next disabled" rel="next"><span>Next</span></a>
				</li>
			</ul>
		</li>
		<li class="">
			<select name="limit" id="limit">
				<option value="">
					All	
				</option>
				<option value="50" selected="">
					50	
				</option>
				<option value="100">
					100		
				</option>
				<option value="200">
					200		
				</option>
			</select>  
		</li>
		<li class="">
			<select name="action" id="action">
				<option value="" selected="">
					Action	
				</option>
				<option value="archive">
					Archive	
				</option>
				<option value="restore">
					Restore	
				</option>
			</select>  
		</li>
	</ul>
	<ul class="toolbar toolbar-tight">
		<li class="">
			<a href="" class="btn">Test</a>
		</li>
		<li>
			<ul class="toolbar toolbar-tight">
				<li>
					<a href="?page=1" class="btn btn-quieter active">1</a>
				</li>
				<li>
					<a href="?page=1" class="btn btn-quieter btn-icon icon-next disabled" rel="next"><span>Next</span></a>
				</li>
			</ul>
		</li>
		<li class="">
			<select name="limit" id="limit">
				<option value="">
					All	
				</option>
				<option value="50" selected="">
					50	
				</option>
				<option value="100">
					100		
				</option>
				<option value="200">
					200		
				</option>
			</select>  
		</li>
		<li class="">
			<select name="action" id="action">
				<option value="" selected="">
					Action	
				</option>
				<option value="archive">
					Archive	
				</option>
				<option value="restore">
					Restore	
				</option>
			</select>  
		</li>
	</ul>
	<ul class="toolbar">
		<li class="flex">
			<a href="" class="btn">Test</a>
		</li>
		<li>
			<ul class="toolbar toolbar-tight">
				<li>
					<a href="?page=1" class="btn btn-quieter active">1</a>
				</li>
				<li>
					<a href="?page=1" class="btn btn-quieter btn-icon icon-next disabled" rel="next"><span>Next</span></a>
				</li>
			</ul>
		</li>
		<li class="flex">
			<select name="limit" id="limit">
				<option value="">
					All	
				</option>
				<option value="50" selected="">
					50	
				</option>
				<option value="100">
					100		
				</option>
				<option value="200">
					200		
				</option>
			</select>  
		</li>
		<li class="flex">
			<select name="action" id="action">
				<option value="" selected="">
					Action	
				</option>
				<option value="archive">
					Archive	
				</option>
				<option value="restore">
					Restore	
				</option>
			</select>  
		</li>
	</ul>

</div>