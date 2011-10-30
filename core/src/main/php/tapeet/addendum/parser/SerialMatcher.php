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


	class SerialMatcher extends CompositeMatcher {
		protected function match($string, &$value) {
			$results = array();
			$total_length = 0;
			foreach($this->matchers as $matcher) {
				if(($length = $matcher->matches($string, $result)) === false) return false;
				$total_length += $length;
				$results[] = $result;
				$string = substr($string, $length);
			}
			$value = $this->process($results);
			return $total_length;
		}

		protected function process($results) {
			return implode('', $results);
		}
	}
