<?php $this->beginContent('//layouts/main'); ?>
<div id="content" class="colleft">
	<?php echo $content; ?>
</div>
<div class="sidebar">
	<ul>
		 <li>
					<h4><span>Navigate</span></h4>
					<ul class="blocklist">
							<li><a href="index.html">Home</a></li>
							<li><a href="examples.html">Examples</a></li>
							<li><a href="#">Products</a></li>
							<li><a href="#">Solutions</a></li>
							<li><a href="#">Contact</a></li>
					</ul>
			</li>

			<li>
					<h4>About</h4>
					<ul>
							<li>
								<p style="margin: 0;">Aenean nec massa a tortor auctor sodales sed a dolor. Duis vitae lorem sem. Proin at velit vel arcu pretium luctus.</p>
							</li>
					</ul>
			</li>

			<li>
				<h4>Search</h4>
					<ul>
						<li>
									<form method="get" class="searchform" action="http://wpdemo.justfreetemplates.com/" >
											<p>
													<input type="text" size="22" value="" name="s" class="s" />
													<input type="submit" class="searchsubmit formbutton" value="Search" />
											</p>
									</form>
	</li>
</ul>
			</li>

			<li>
					<h4>Sponsors</h4>
					<ul>
							<li><a href="http://www.themeforest.net/?ref=spykawg" title="premium templates"><strong>ThemeForest</strong></a> - premium HTML templates, WordPress themes and PHP scripts</li>
							<li><a href="http://www.dreamhost.com/r.cgi?259541" title="web hosting"><strong>Web hosting</strong></a> - 50 dollars off when you use promocode <strong>awesome50</strong></li>
							<li><a href="http://www.4templates.com/?aff=spykawg" title="4templates"><strong>4templates</strong></a> - brilliant premium templates</li>
					</ul>
			</li>

	</ul>
</div>
<?php $this->endContent(); ?>