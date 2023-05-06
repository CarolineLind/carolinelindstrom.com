<section role="search">
	<form action="/" method="get" class="searchForm">


		<label for="search" class="hidden">Sök:</label>

		<input type="text" name="s" id="search" placeholder="Sök" value="<?php the_search_query();?>" required>

		<button type="submit" class="relative" aria-label="Sök"><i class="fas fa-search"></i></button>


	</form>
</section>