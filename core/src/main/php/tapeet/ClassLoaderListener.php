<?php
namespace tapeet;


interface ClassLoaderListener {


	function onLoad($className);

}
