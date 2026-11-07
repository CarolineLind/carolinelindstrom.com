<?php
/*
The code editor window on the start page (components/code-editor.css,
js/code-editor.js): a short "about me" as a JavaScript object, with a file
tree beside it and a terminal below. The pages in the tree are real links;
the other files are just text. The 'light' token is a button that switches to
light mode, and the terminal answers. The lines live in inc/code-editor.php.
With motion on, the code is typed out when it scrolls into view. The real code
is always there for screen readers and without JavaScript; the typing is a
copy on top of it (decision 0009).
*/

$clind_lines = clind_code_editor_lines();
?>
<section class="code-editor" aria-label="<?php esc_attr_e( 'Kort om mig, som kod', 'carolinelindstrom' ); ?>">
	<div class="code-editor__inner container">
		<div class="code-editor__window">
			<div class="code-editor__bar" aria-hidden="true">carolinelindstrom.com — caroline.js</div>

			<div class="code-editor__body">
				<nav class="code-editor__side" aria-label="<?php esc_attr_e( 'Projektfiler', 'carolinelindstrom' ); ?>">
					<p class="code-editor__label"><?php esc_html_e( 'Utforskare', 'carolinelindstrom' ); ?></p>
					<ul class="code-editor__tree">
						<li>
							<span class="code-editor__row code-editor__row--root"><svg class="code-editor__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>carolinelindstrom.com</span>
							<ul>
								<li>
									<span class="code-editor__row"><svg class="code-editor__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg><svg class="code-editor__folder" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M3 6.5C3 5.7 3.7 5 4.5 5H9l2 2.5h8.5c.8 0 1.5.7 1.5 1.5v8.5c0 .8-.7 1.5-1.5 1.5h-15C3.7 19 3 18.3 3 17.5z"/></svg>src</span>
									<ul>
										<li><span class="code-editor__row code-editor__row--active"><i class="code-editor__dot code-editor__dot--yellow"></i>caroline.js</span></li>
										<li><span class="code-editor__row"><i class="code-editor__dot code-editor__dot--lilac"></i>tema.css</span></li>
									</ul>
								</li>
								<li>
									<span class="code-editor__row"><svg class="code-editor__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg><svg class="code-editor__folder" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M3 6.5C3 5.7 3.7 5 4.5 5H9l2 2.5h8.5c.8 0 1.5.7 1.5 1.5v8.5c0 .8-.7 1.5-1.5 1.5h-15C3.7 19 3 18.3 3 17.5z"/></svg><?php esc_html_e( 'sidor', 'carolinelindstrom' ); ?></span>
									<ul>
										<?php foreach ( clind_code_editor_pages() as $clind_page ) : ?>
											<li>
												<?php if ( $clind_page['url'] ) : ?>
													<a class="code-editor__row code-editor__row--link" href="<?php echo esc_url( $clind_page['url'] ); ?>"><i class="code-editor__dot code-editor__dot--sky"></i><?php echo esc_html( $clind_page['file'] ); ?></a>
												<?php else : ?>
													<span class="code-editor__row"><i class="code-editor__dot code-editor__dot--sky"></i><?php echo esc_html( $clind_page['file'] ); ?></span>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</li>
								<li><span class="code-editor__row"><i class="code-editor__dot code-editor__dot--muted"></i>README.md</span></li>
							</ul>
						</li>
					</ul>
				</nav>

				<div class="code-editor__main">
					<div class="code-editor__tabs" aria-hidden="true"><span class="code-editor__tab"><i class="code-editor__dot code-editor__dot--yellow"></i>caroline.js</span></div>

					<?php
					/*
					No whitespace inside <pre>: it would show. Newlines only between the lines:
					after the last one, the typing caret would start a line 19 and the window
					would grow, then shrink again when the typing is done (CLS)
					*/
					$clind_html = array();
					foreach ( $clind_lines as $clind_i => $clind_line ) {
						$clind_html[] = clind_code_editor_line( $clind_line, $clind_i + 1 );
					}
					$clind_code = implode( "\n", $clind_html );
					?>
					<pre class="code-editor__code" tabindex="0"><code class="code-editor__source"><?php echo $clind_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every part is escaped in clind_code_editor_line(). ?></code></pre>

					<div class="code-editor__terminal">
						<p class="code-editor__terminal-head" aria-hidden="true">Terminal</p>
						<p class="code-editor__terminal-line">$ node caroline.js</p>
						<p class="code-editor__terminal-line code-editor__terminal-line--muted"><?php esc_html_e( '✓ Allt laddat. Välkommen in!', 'carolinelindstrom' ); ?></p>
						<div class="code-editor__terminal-answer code-editor__terminal-answer--waiting" tabindex="-1">
							<p class="code-editor__terminal-line">$ caroline.tema = 'light'</p>
							<p class="code-editor__terminal-line code-editor__terminal-line--ok"><?php esc_html_e( 'Ljust läge på. Vill du tillbaka till mörkt? Knappen finns högst upp på sidan.', 'carolinelindstrom' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
