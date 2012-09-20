<?php
namespace tapeet;


interface Filter {


	function doFilter(FilterChain $chain);

}
