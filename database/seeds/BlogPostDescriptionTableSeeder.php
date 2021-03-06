<?php

use Illuminate\Database\Seeder;

class BlogPostDescriptionTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('blog_post_description')->delete();
        
		\DB::table('blog_post_description')->insert(array (
			0 => 
			array (
				'post_id' => 1,
				'language_id' => 1,
				'name' => 'Lorem Ipsum Test Post',
				'description' => '&lt;p&gt;&lt;span style=&quot;font-weight: bold;&quot;&gt;Lorem ipsum dolor sit amet,&lt;/span&gt; consectetur adipiscing elit. Phasellus feugiat dui a odio facilisis porta commodo ut ligula. Nullam ligula urna, blandit vel justo eu, tristique tempor nisl. Nam ornare auctor enim vel venenatis. Duis et elit augue. Nulla auctor nunc et ultrices lobortis. Donec ultricies, metus quis convallis tincidunt, nisl nibh molestie tortor, et accumsan turpis nulla rhoncus libero. Donec hendrerit enim ut lectus condimentum malesuada.&lt;/p&gt;
&lt;p&gt;Quisque vel augue semper, euismod turpis sed, suscipit odio. Morbi venenatis neque a tristique sodales. Suspendisse sed tempor mauris, eu blandit nulla. Vivamus quis enim et nibh ultrices pellentesque. In nec dapibus orci. Nam vel augue at nunc convallis adipiscing eget quis libero. Cras semper odio congue, varius diam a, rutrum dolor.&lt;/p&gt;
&lt;p&gt;Pellentesque sed tincidunt velit, pellentesque luctus tellus. Duis vulputate lacus eu metus consectetur pretium. Interdum et malesuada fames ac ante ipsum primis in faucibus. Fusce pulvinar quam non magna aliquet, a tincidunt diam lobortis. Vestibulum quam quam, commodo commodo ornare at, sodales ac elit. Pellentesque convallis pellentesque ante at facilisis. Duis ut porta mauris. Vestibulum suscipit elit vitae urna hendrerit, fermentum placerat justo tristique. Quisque eget odio nunc. Morbi interdum sed mi sit amet suscipit. Nullam nec lacinia dolor, vel dapibus nulla. Aenean sed congue nisi, id dictum diam. Phasellus ac metus non ligula interdum hendrerit eget vitae nisi.&lt;/p&gt;
&lt;p&gt;In posuere molestie imperdiet. Nunc auctor sagittis risus, ullamcorper elementum dui ullamcorper eget. Donec quis diam porttitor, fringilla purus nec, viverra tellus. In sit amet pulvinar risus, sed porttitor lectus. Proin nunc metus, porta id viverra nec, aliquet in lorem. Quisque faucibus lorem vitae nulla adipiscing, in aliquet lorem cursus. Pellentesque id suscipit justo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sagittis non ante quis vehicula.&lt;/p&gt;
&lt;p&gt;Sed at metus ipsum. Integer odio leo, volutpat eu sagittis sed, sodales vel dui. Vestibulum nec tellus orci. Etiam hendrerit at arcu vel interdum. Praesent quis nulla adipiscing, convallis est in, pulvinar quam. Curabitur at massa pulvinar eros rhoncus molestie vitae eget purus. Curabitur ullamcorper dictum tortor, in blandit urna gravida et. Nulla a nisl ligula. Vestibulum lobortis rutrum luctus. Phasellus mattis, turpis at dignissim fringilla, quam quam egestas nisl, vel viverra sem diam gravida purus. In eget erat rutrum, pulvinar lectus porta, aliquam lorem. Sed lorem purus, sodales id pellentesque id, volutpat a tellus. Nam aliquam ullamcorper odio sed egestas. Donec vel dictum odio.&lt;/p&gt;',
				'meta_description' => 'Test Meta Description',
				'meta_keyword' => 'lorem, ipsum',
			),
		));
	}

}
