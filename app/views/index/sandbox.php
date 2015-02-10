<?php if (!$req->isAjax()) { ?>
	<?php $this->parent('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<div class="body">

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