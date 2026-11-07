<?php
/* --------------------------------------------------
 * ------------------ CODE EDITOR -------------------
 * -------------------------------------------------- */

/*
The code editor window on the start page (template-parts/front/code-editor.php):
the lines of caroline.js, and the files in its tree.
*/

/*
One string per line, with the syntax colours as markers: {k:…} keyword,
{s:…} string, {p:…} property, {c:…} comment. {light} is the token that
switches to light mode. Spaces at the start are the indentation.
*/
function clind_code_editor_lines() {
	return array(
		__( '{c:// Dels en portfölj, dels en lekplats}', 'carolinelindstrom' ),
		__( '{k:const} caroline = {', 'carolinelindstrom' ),
		__( "  {p:roll}: {s:'Frontendutvecklare'},", 'carolinelindstrom' ),
		__( "  {p:bas}: {s:'Karlstad'},", 'carolinelindstrom' ),
		__( '  {p:soundtrack}: [', 'carolinelindstrom' ),
		__( "    {s:'Beyoncé'},", 'carolinelindstrom' ),
		__( "    {s:'Daft Punk'},", 'carolinelindstrom' ),
		__( '    {k:...}allPop,', 'carolinelindstrom' ),
		__( '  ],', 'carolinelindstrom' ),
		__( "  {p:stjärntecken}: {s:'Jungfru'}, {c:// förklarar en del}", 'carolinelindstrom' ),
		__( "  {p:blodtyp}: {s:'röd'},", 'carolinelindstrom' ),
		__( '  {p:handsvett}: {k:true},', 'carolinelindstrom' ),
		__( '  {p:tema}: {light}, {c:// ja, jag vet}', 'carolinelindstrom' ),
		__( '  {p:karaoke}: {', 'carolinelindstrom' ),
		__( '    {p:entusiasm}: {k:100},', 'carolinelindstrom' ),
		__( '    {p:träffsäkerhet}: {k:12},', 'carolinelindstrom' ),
		__( '  },', 'carolinelindstrom' ),
		__( '};', 'carolinelindstrom' ),
	);
}

/*
The light-mode token, twice: a button while the page is dark, plain text once
it's light or without JavaScript (components/code-editor.css shows one of them).
*/
function clind_code_editor_light_token() {
	return sprintf(
		'<button class="code-editor__light code-editor__tok--string" type="button" data-code-editor-light>%1$s<span class="sr-only">%2$s</span></button><span class="code-editor__light-text code-editor__tok--string">%1$s</span>',
		esc_html( "'light'" ),
		esc_html__( ', byt till ljust läge', 'carolinelindstrom' )
	);
}

/* One line as HTML: the line number (hidden from screen readers) and the coloured parts */
function clind_code_editor_line( $line, $number ) {
	$kinds = array(
		'k' => 'keyword',
		's' => 'string',
		'p' => 'prop',
		'c' => 'comment',
	);
	$parts = preg_split( '/(\{light\}|\{[kspc]:[^}]*\})/u', $line, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
	$html  = '<span class="code-editor__ln" aria-hidden="true">' . absint( $number ) . '</span>';

	foreach ( $parts as $part ) {
		if ( '{light}' === $part ) {
			$html .= clind_code_editor_light_token();
		} elseif ( preg_match( '/^\{([kspc]):(.*)\}$/su', $part, $match ) ) {
			$html .= '<span class="code-editor__tok--' . $kinds[ $match[1] ] . '">' . esc_html( $match[2] ) . '</span>';
		} else {
			$html .= esc_html( $part );
		}
	}

	return '<span class="code-editor__line">' . $html . '</span>';
}

/*
The pages in the file tree, as .md files: the four pages, in the menu's order.
A page that isn't published is still listed, as plain text.
*/
function clind_code_editor_pages() {
	$files = array(
		'om-mig'         => 'om-mig.md',
		'tillganglighet' => 'tillganglighet.md',
		'prestanda'      => 'prestanda.md',
		'frontend-lead'  => 'frontend-lead.md',
	);
	$pages = array();

	foreach ( $files as $slug => $file ) {
		$page    = get_page_by_path( $slug );
		$pages[] = array(
			'file' => $file,
			'url'  => $page && 'publish' === $page->post_status ? get_permalink( $page ) : '',
		);
	}

	return $pages;
}
