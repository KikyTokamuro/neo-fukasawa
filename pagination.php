<?php if ($wp_query->max_num_pages > 1) : ?>
	<div class="archive-nav clear">
		<?php echo get_next_posts_link(__('Older posts', 'neofukasawa') . ' &rarr;'); ?>
		<?php echo get_previous_posts_link('&larr; ' . __('Newer posts', 'neofukasawa')); ?>
	</div><!-- .archive-nav -->
<?php endif; ?>