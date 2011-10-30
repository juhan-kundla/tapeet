<?php
	/**
	 * Addendum PHP Reflection Annotations
	 * http://code.google.com/p/addendum/
	 *
	 * Copyright (C) 2006-2009 Jan "johno Suchal <johno@jsmf.net>
	
	 * This library is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU Lesser General Public
	 * License as published by the Free Software Foundation; either
	 * version 2.1 of the License, or (at your option) any later version.
	
	 * This library is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	 * Lesser General Public License for more details.
	
	 * You should have received a copy of the GNU Lesser General Public
	 * License along with this library; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
	 **/


namespace tapeet\addendum\parser;


	class ParallelMatcher extends CompositeMatcher {
		protected function match($string, &$value) {
			$maxLength = false;
			$result = null;
			foreach($this->matchers as $matcher) {
				$length = $matcher->matches($string, $subvalue);
				if($maxLength === false || $length > $maxLength) {
					$maxLength = $length;
					$result = $subvalue;
				}
			}
			$value = $this->process($result);
			return $maxLength;
		}

		protected function process($value) {
			return $value;
		}
	}
