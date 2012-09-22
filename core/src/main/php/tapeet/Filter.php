<?php
namespace tapeet;


interface Filter {


	function execute(FilterChain $chain);

}
