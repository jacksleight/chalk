<?php
class DemoEntity extends \Js\Entity
{
	protected $text;
	protected $password;
	protected $url;
	protected $email;
	protected $tel;
	protected $datetime;
	protected $datetimelocal;
	protected $date;
	protected $time;
	protected $month;
	protected $week;
	protected $number;
	protected $search;
	protected $range;
	protected $color;
	protected $file;
	protected $checkbox;
	protected $radio;
	protected $select;
	protected $textarea;

	protected static function _defineMetadata($class)
	{
		return [
			'fields' => [
				'text' => [
					'type'		=> 'string',
					'length'	=> 255,
				],
				'password' => [
					'type'		=> 'string',
					'length'	=> 255,
				],
				'url' => [
					'type'		=> 'url',
					'length'	=> 255,
				],
				'email' => [
					'type'		=> 'string',
					'length'	=> 255,
				],
				'tel' => [
					'type'		=> 'string',
					'length'	=> 255,
				],
				'datetime' => [
					'type'		=> 'datetime',
				],
				'datetimelocal' => [
					'type'		=> 'datetime',
				],
				'date' => [
					'type'		=> 'date',
				],
				'time' => [
					'type'		=> 'time',
				],
				'month' => [
					'type'		=> 'date',
				],
				'week' => [
					'type'		=> 'date',
				],
				'number' => [
					'type'		=> 'integer',
					'validator'	=> new \Js\Validator\Chain([
						new \Js\Validator\Range(1, 10),
					]),
				],
				'search' => [
					'type'		=> 'string',
					'length'	=> 255,
				],
				'range' => [
					'type'		=> 'integer',
					'validator'	=> new \Js\Validator\Chain([
						new \Js\Validator\Range(1, 10),
					]),
				],
				'color' => [
					'type'		=> 'string',
				],
				'file' => [
					'type'		=> 'string',
				],
				'checkbox' => [
					'type'		=> 'boolean',
				],
				'radio' => [
					'type'		=> 'string',
					'length'	=> 255,
					'values'	=> ['yes', 'no'],
				],
				'select' => [
					'type'		=> 'string',
					'length'	=> 255,
					'values'	=> ['yes', 'no'],
				],
				'textarea' => [
					'type'		=> 'text',
				],
			],
		];
	}
	
	public function __construct()
	{
		parent::__construct();

		$this->fromArray([
			'text'			=> 'Example',
			'password'		=> 'Example',
			'url'			=> new \Coast\Url('http://example.com/page?name=value'),
			'email'			=> 'test@example.com',
			'tel'			=> '00000 000 000',
			'datetime'		=> new \DateTime(),
			'datetimelocal'	=> new \DateTime(),
			'date'			=> new \DateTime(),
			'time'			=> new \DateTime(),
			'month'			=> new \DateTime(),
			'week'			=> new \DateTime(),
			'number'		=> 5,
			'search'		=> 'Example',
			'range'			=> 5,
			'color'			=> '#000000',
			'file'			=> null,
			'checkbox'		=> true,
			'radio'			=> 'yes',
			'select'		=> 'no',
			'textarea'		=> 'Example',
		]);
	}
}
$entity = $this->entity->wrap(new DemoEntity());
if ($req->isPost()) {		
	$entity->graphFrom[$req->postParams()];
	$entity->graphIsValid();
}

$locales = [
	$this->locale,
	new \Js\Locale('en-US@timezone=America/New_York;currency=USD'), 
	new \Js\Locale('fr-FR@timezone=Europe/Paris;currency=EUR'), 
	new \Js\Locale('zh-CN@timezone=Asia/Shanghai;currency=CNY'), 
];
?>
<? $this->layout('/layouts/page', [
	'title' => 'Demo',
]) ?>
<? $this->block('main') ?>

<h1 class="demo-header">Header &amp; Paragraph</h1>
<div class="demo-cols">
	<div class="demo-col-half">
		<h1>Header 1</h1>
		<p class="details">Details. Published <?= $this->locale->date(new \DateTime()) ?></p>
		<p class="summary">Summary. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Aenean massa. In enim justo, rhoncus ut, imperdiet a.</p>
		<p>Paragraph. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque dolorem placeat praesentium rerum autem assumenda! Est at unde quos quod fuga eveniet quia voluptas consequuntur ratione a numquam voluptates ullam.</p>
		<h2>Header 2</h2>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam voluptate error corporis possimus distinctio eligendi maiores placeat animi! Rerum commodi expedita consequatur vitae temporibus modi harum. Tenetur similique ullam unde.</p>
	</div>
	<div class="demo-col-half">
		<h3>Header 3</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo nihil facere voluptatem vitae ipsa rem adipisci deleniti veniam praesentium nulla voluptas inventore quaerat. Corrupti vitae dignissimos nisi doloribus excepturi deserunt.</p>
		<h4>Header 4</h4>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil nobis non et quia ut provident totam dolorum temporibus libero perspiciatis ex consequatur qui eum officiis quas atque tempora id quae.</p>
		<h5>Header 5</h5>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat fuga quam harum possimus iste ut incidunt voluptas error non temporibus. Quidem natus quasi maiores sequi ullam laborum nobis dolores ad.</p>
		<h6>Header 6</h6>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore cumque officia modi harum totam magni unde rerum iure beatae optio quas iste? Dolorum voluptatem est nobis maiores aliquam eveniet et?</p>
	</div>
</div>

<h1 class="demo-header">List</h1>
<div class="demo-cols">
	<div class="demo-col-fourth">
		<ol>
			<li>
				Ordered List
				<ol>
					<li>
						Item 1
						<ol>
							<li>Item 1</li>
						</ol>
					</li>
				</ol>
			</li>
		</ol>
	</div>
	<div class="demo-col-fourth">
		<ul>
			<li>
				Unordered List
				<ul>
					<li>
						Item 1
						<ul>
							<li>Item 1</li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="demo-col-fourth">
		<dl>
			<dt>Title 1</dt>
				<dd>Definition List</dd>
			<dt>Title 1</dt>
				<dd>Description 1</dd>
			<dt>Title 1</dt>
				<dd>Description 1</dd>
		</dl>
	</div>
</div>

<h1 class="demo-header">Text</h1>
<div class="demo-cols">
	<div class="demo-col-sixth">
		<em>Emphasis text</em>
	</div>
	<div class="demo-col-sixth">
		<strong>Strong text</strong>
	</div>
	<div class="demo-col-sixth">
		<a>Anchor text</a>
	</div>
	<div class="demo-col-sixth">
		<a href="<?= $this->url() ?>">Link text</a>
	</div>
	<div class="demo-col-sixth">
		<ins>Inserted text</ins>
	</div>
	<div class="demo-col-sixth">
		<del>Deleted text</del>
	</div>
	<div class="demo-col-sixth">
		<q>Quote text</q>
	</div>
	<div class="demo-col-sixth">
		Superscript <sup>text</sup>
	</div>
	<div class="demo-col-sixth">
		Subscript <sub>text</sub>
	</div>
	<div class="demo-col-sixth">
		<small>Small print text</small>
	</div>
	<div class="demo-col-sixth">
		<span class="error">Error text</span>
	</div>
	<div class="demo-col-sixth">
		<abbr title="Abbreviation text">Abbreviation text</abbr>
	</div>
	<div class="demo-col-sixth">
		<mark>Marked text</mark>
	</div>
	<div class="demo-col-sixth">
		<code>Code text</code>
	</div>
</div>

<h1 class="demo-header">Table</h1>
<table>
	<caption>Caption. Donec nec massa. Mauris volutpat<sub>[6]</sub> turpis vehicula ante.</caption>
	<col>
	<col>
	<col>
	<col style="width: 10%;">
	<thead>
		<tr>
			<th scope="col" colspan="3">Header</th>
			<th scope="col">Value</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="row" colspan="3">Footer</th>
			<td>100</td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<th scope="col">Lorem ipsum dolor sit amet</th>
			<td>Consectetuer adipiscing elit</td>
			<td>Consectetuer adipiscing elit</td>
			<td>20</td>
		</tr>
		<tr>
			<th scope="col">Lorem ipsum dolor sit amet</th>
			<td>Consectetuer adipiscing elit</td>
			<td>Consectetuer adipiscing elit</td>
			<td>20</td>
		</tr>
		<tr>
			<th scope="col">Lorem ipsum dolor sit amet</th>
			<td>Consectetuer adipiscing elit</td>
			<td>Consectetuer adipiscing elit</td>
			<td>20</td>
		</tr>
		<tr>
			<th scope="col">Lorem ipsum dolor sit amet</th>
			<td>Consectetuer adipiscing elit</td>
			<td>Consectetuer adipiscing elit</td>
			<td>20</td>
		</tr>
		<tr>
			<th scope="col">Lorem ipsum dolor sit amet</th>
			<td>Consectetuer adipiscing elit</td>
			<td>Consectetuer adipiscing elit</td>
			<td>20</td>
		</tr>
	</tbody>
</table>

<h1 class="demo-header">Form</h1>
<form class="demo-cols" action="<?= $this->url() ?>" method="post" novalidate>
	<div class="demo-col-half">
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_text',
			'entity'	=> $entity,
			'name'		=> 'text',
			'label'		=> 'Text',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_password',
			'entity'	=> $entity,
			'name'		=> 'password',
			'label'		=> 'Password',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_url',
			'entity'	=> $entity,
			'name'		=> 'url',
			'label'		=> 'URL',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_email',
			'entity'	=> $entity,
			'name'		=> 'email',
			'label'		=> 'Email',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_tel',
			'entity'	=> $entity,
			'name'		=> 'tel',
			'label'		=> 'Tel',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_datetime',
			'entity'	=> $entity,
			'name'		=> 'datetime',
			'label'		=> 'Date Time',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_datetime-local',
			'entity'	=> $entity,
			'name'		=> 'datetimelocal',
			'label'		=> 'Date Time Local',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_date',
			'entity'	=> $entity,
			'name'		=> 'date',
			'label'		=> 'Date',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_time',
			'entity'	=> $entity,
			'name'		=> 'time',
			'label'		=> 'Time',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_month',
			'entity'	=> $entity,
			'name'		=> 'month',
			'label'		=> 'Month',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_week',
			'entity'	=> $entity,
			'name'		=> 'week',
			'label'		=> 'Week',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_number',
			'entity'	=> $entity,
			'name'		=> 'number',
			'label'		=> 'Number',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_search',
			'entity'	=> $entity,
			'name'		=> 'search',
			'label'		=> 'Search',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_range',
			'entity'	=> $entity,
			'name'		=> 'range',
			'label'		=> 'Range'
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_color',
			'entity'	=> $entity,
			'name'		=> 'color',
			'label'		=> 'Color'
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_file',
			'entity'	=> $entity,
			'name'		=> 'file',
			'label'		=> 'File',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_checkbox',
			'entity'	=> $entity,
			'name'		=> 'checkbox',
			'label'		=> 'Checkbox',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'input_radio',
			'entity'	=> $entity,
			'name'		=> 'radio',
			'label'		=> 'Radio',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'select',
			'entity'	=> $entity,
			'name'		=> 'select',
			'label'		=> 'Select',
		]) ?>
		<?= $this->render('elements/form-item', [
			'type'		=> 'textarea',
			'entity'	=> $entity,
			'name'		=> 'textarea',
			'label'		=> 'Textarea',
		]) ?>
		<div class="form-item">
			<div>
				<input type="submit" value="Normal Button">&nbsp;
				<input type="submit" disabled value="Disabled Button">&nbsp;
				or <a href="#">Cancel</a>
			</div>
		</div>
	</div>
	<div class="demo-col-half">
		<div class="form-item">
			<label>Inline Test</label>
			<div>
				<input type="text" class="demo-input" value="Input">
				<select><option>Select</option></select>
				<input type="checkbox">
				<label>Checkbox</label>
				<input type="radio">
				<label>Radio</label>
			</div>
		</div>
		<div class="form-item">
			<label></label>
			<div>
				<input type="text" class="demo-input" value="Input">
				<input type="button" value="Input">
				<button>Button</button>
				<a class="button" href="#">Link</a>
				<a class="button disabled" href="#">Link</a>
			</div>
		</div>
		<div class="form-item">
			<label>Select Test</label>
			<div>
				<select>
					<option>Option 1</option>
					<option>Option 2</option>
					<option>Option 3</option>
					<optgroup label="Group">
						<option>Option 1</option>
						<option>Option 2</option>
						<option>Option 3</option>
					</optgroup>
				</select>
			</div>
		</div>
		<div class="form-item">
			<label>Placeholder Test</label>
			<div>
				<input type="text" placeholder="Placeholder">
			</div>
		</div>
		<div class="form-item">
			<label>Focus Test</label>
			<div>
				<input type="text" value="Valid">
			</div>
		</div>
		<div class="form-item">
			<label>Valid Test</label>
			<div>
				<input type="text" value="Valid" required>
			</div>
		</div>
		<div class="form-item">
			<label>Invalid Test</label>
			<div>
				<input type="text" value="Invalid" pattern="0">
			</div>
		</div>
		<div class="form-item">
			<label>Validation Test</label>
			<div>
				<input type="text" value="" required pattern="test">
			</div>
		</div>
		<div class="form-item">
			<label>Disabled Test</label>
			<div>
				<input type="text" disabled value="Input">
			</div>
		</div>
		<div class="form-item">
			<label>Note &amp; Error Test</label>
			<div>
				<input type="text" value="Input">
				<p class="error">This is an example error.</p>
				<p><small>This is an example note.</small></p>
			</div>
		</div>
	</div>
</form>

<div class="demo-cols">
	<div class="demo-col-fourth">
		<h1 class="demo-header">Image</h1>
		<?= $this->render("elements/image_vector", [
			'src' => 'public/images/placeholder.svg',
		]) ?>
	</div>
	<div class="demo-col-fourth">
		<h1 class="demo-header">Sprite</h1>
		<span class="demo-sprite-raster"></span> <span class="demo-sprite-raster-attach">Tick</span><br><br>
		<span class="demo-sprite-vector"></span> <span class="demo-sprite-vector-attach">Tick</span><br><br>
	</div>
	<div class="demo-col-fourth">
		<h1 class="demo-header">Background</h1>
		<span class="demo-image-raster"></span><br><br>
		<span class="demo-image-vector"></span>
	</div>
	<div class="demo-col-fourth">
		<h1 class="demo-header">Audio</h1>
		<?= $this->render("elements/audio", [
			'mp3Src'	=> 'public/demo/audio.mp3',
			'oggSrc'	=> 'public/demo/audio.ogg',
		]) ?>
		<?= $this->render("elements/audio", [
			'mp3Src'	=> 'public/demo/audio.mp3',
		]) ?>
	</div>
</div>

<h1 class="demo-header">Video</h1>
<div class="demo-cols">
	<div class="demo-col-half">
		<?= $this->render("elements/video", [
			'width'		=> 960,
			'height'	=> 400,
			'mp4Src'	=> 'public/demo/video.mp4',
			'webmSrc'	=> 'public/demo/video.webm',
			'poster'	=> 'public/demo/video.jpg',
			'tracks' => [
				[
					'kind'		=> 'captions',
					'src'		=> 'public/demo/video.vtt',
					'srclang'	=> 'en',
					'label'		=> 'English',
				],
			],
		]) ?>
	</div>
	<div class="demo-col-half">
		<?= $this->render("elements/video", [
			'width'		=> 960,
			'height'	=> 400,
			'mp4Src'	=> 'public/demo/video.mp4',
			'poster'	=> 'public/demo/video.jpg',
		]) ?>
	</div>
</div>

<div class="demo-cols">
	<div class="demo-col-third">
		<h1 class="demo-header">Block Quote</h1>
		<blockquote>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam ratione impedit repellendus ad voluptatibus autem voluptates voluptatum architecto quis nam cumque possimus voluptas.</p>
		</blockquote>
	</div>
	<div class="demo-col-third">
		<h1 class="demo-header">Rule</h1>
		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo.</p>
		<hr>
		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo.</p>
	</div>
	<div class="demo-col-third">
		<h1 class="demo-header">Preformatted</h1>
		<pre>Preformatted
	Indented Text
	Indented Text</pre>
	</div>
</div>

<h1 class="demo-header">Figure</h1>
<div class="demo-cols">
	<div class="demo-col-third">
		<figure class="quote">
			<blockquote>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam ratione impedit repellendus ad voluptatibus autem voluptates voluptatum architecto quis nam cumque possimus voluptas.</p>
			</blockquote>
			<figcaption>Quote figure. <cite>Lorem ipsum.</cite></figcaption>
		</figure>
	</div>
	<div class="demo-col-third">
		<figure class="image">
			<img src="<?= $this->url($this->image->lorempixel(270)) ?>" alt="Placeholder" width="270" height="270">
			<figcaption>Image figure. Lorem ipsum.</figcaption>
		</figure>
	</div>
	<div class="demo-col-third">
		<figure class="table">
			<table>
				<col>
				<col>
				<thead>
					<tr>
						<th scope="col">Header</th>
						<th scope="col">Value</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="row">Footer</th>
						<td>100</td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<th scope="col">Lorem ipsum dolor</th>
						<td>20</td>
					</tr>
					<tr>
						<th scope="col">Lorem ipsum dolor</th>
						<td>20</td>
					</tr>
					<tr>
						<th scope="col">Lorem ipsum dolor</th>
						<td>20</td>
					</tr>
					<tr>
						<th scope="col">Lorem ipsum dolor</th>
						<td>20</td>
					</tr>
					<tr>
						<th scope="col">Lorem ipsum dolor</th>
						<td>20</td>
					</tr>
				</tbody>
			</table>
			<figcaption>Table figure. Figure caption.</figcaption>
		</figure>
	</div>
</div>

<h1 class="demo-header">Grid Test</h1>
<div class="demo-grid">
	<div><div class="demo-grid-filler"></div></div>
	<div><div class="demo-grid-filler"></div></div>
	<div><div class="demo-grid-filler"></div></div>
	<div><div class="demo-grid-filler"></div></div>
	<div><div class="demo-grid-filler"></div></div>
	<div><div class="demo-grid-filler"></div></div>
</div>

<div class="demo-cols">
	<div class="demo-col-half">
		<h1 class="demo-header">Resize Test</h1>
		<p>
			<img src="<?= $this->url($this->image('public/images/2x/placeholder.png', 200)) ?>" alt="Placeholder" width="200" height="200">&nbsp;&nbsp;
			<img src="<?= $this->url($this->image('public/images/2x/placeholder.png', 150)) ?>" alt="Placeholder" width="150" height="150">&nbsp;&nbsp;
			<img src="<?= $this->url($this->image('public/images/2x/placeholder.png', 100)) ?>" alt="Placeholder" width="100" height="100">
		</p>
	</div>
	<div class="demo-col-half">
		<h1 class="demo-header">Placeholder Test</h1>
		<p>
			<img src="<?= $this->url($this->image->lorempixel(200, 200, 'cats')) ?>" alt="Placeholder">&nbsp;&nbsp;
			<img src="<?= $this->url($this->image->lorempixel(150, 150)) ?>" alt="Placeholder">&nbsp;&nbsp;
			<img src="<?= $this->url($this->image->lorempixel(100, 100, null, true)) ?>" alt="Placeholder">
		</p>
	</div>
</div>

<h1 class="demo-header">Locale Test</h1>
<div class="demo-cols">
	<? foreach ($locales as $locale) { ?>
		<div class="demo-col-half">
			<h4><?= $locale->getDisplayLanguage($this->locale->get()) . ', ' . $locale->getDisplayRegion($this->locale->get()) ?></h4>
			<dl>
				<dt>Number</dt>
					<dd><?= $locale->number(1000.5) ?></dd>
				<dt>Currency</dt>
					<dd><?= $locale->currency(1000.5) ?></dd>
				<dt>Currency</dt>
					<dd><?= $locale->currency(1000.5, false) ?></dd>
				<dt>Percent</dt>
					<dd><?= $locale->percent(0.5) ?></dd>
				<dt>Scientific</dt>
					<dd><?= $locale->scientific(1000.5) ?></dd>
				<dt>Spellout</dt>
					<dd><?= $locale->spellout(1000.5) ?></dd>
				<dt>Ordinal</dt>
					<dd><?= $locale->ordinal(10) ?></dd>
				<dt>Duration</dt>
					<dd><?= $locale->duration(120) ?>, <?= $locale->duration(120, true) ?></dd>
				<dt>Date</dt>
					<dd><?= $locale->date(new \DateTime()) ?></dd>
				<dt>Time</dt>
					<dd><?= $locale->time(new \DateTime()) ?></dd>
				<dt>Date Time</dt>
					<dd><?= $locale->datetime(new \DateTime(), \Js\Locale::DATETIME_FULL, \Js\Locale::DATETIME_LONG) ?></dd>
				<dt>Message</dt>
					<dd><?= $locale->message('validator_length_short', [10]) ?></dd>
			</dl>
		</div>
	<? } ?>		
</div>

<h1 class="demo-header">URL Test</h1>
<div class="demo-cols">
	<div class="demo-col-third">
		<dl>
			<dt>Base</dt>
				<dd><?= $this->url() ?></dd>
			<dt>Routed</dt>
				<dd><?= $this->url(['path' => 'example'], 'index', true, false) ?></dd>
				<dd><?= $this->url(['path' => 'example']) ?></dd>
			<dt>Query</dt>
				<dd><?= $this->url->query(['type' => 'example'], true, false) ?></dd>
				<dd><?= $this->url->query(['type' => 'example']) ?></dd>
		</dl>
	</div>
	<div class="demo-col-third">
		<dl>
			<dt>File</dt>
				<dd><?= $this->url->file('public/favicon.ico', true, false) ?></dd>
				<dd><?= $this->url->file('public/favicon.ico') ?></dd>
				<dd><?= $this->url->file('public/favicon.ico', false) ?></dd>
		</dl>
	</div>
	<div class="demo-col-third">
		<dl>
			<dt>Current</dt>
				<dd><?= $this->url($req->url()) ?></dd>
				<dd><?= $this->url($req->url(), \Coast\Url::PART_PATH) ?></dd>
				<dd><?= $this->url($req->url(), \Coast\Url::PART_HOST, true) ?></dd>
		</dl>
	</div>
</div>