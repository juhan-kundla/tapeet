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


namespace tapeet\addendum;


use \ReflectionClass;


	class Addendum {
		private static $rawMode;
		private static $ignore;
		private static $classnames = array();
		private static $annotations = false;
		
		public static function getDocComment($reflection) {
			if(self::checkRawDocCommentParsingNeeded()) {
				$docComment = new DocComment();
				return $docComment->get($reflection);
			} else {
				return $reflection->getDocComment();
			}
		}
		
		/** Raw mode test */
		private static function checkRawDocCommentParsingNeeded() {
			if(self::$rawMode === null) {
				$reflection = new ReflectionClass('\tapeet\addendum\Addendum');
				$method = $reflection->getMethod('checkRawDocCommentParsingNeeded');
				self::setRawMode($method->getDocComment() === false);
			}
			return self::$rawMode;
		}
		
		public static function setRawMode($enabled = true) {
			if($enabled) {
				require_once(dirname(__FILE__).'/annotations/doc_comment.php');
			}
			self::$rawMode = $enabled;
		}
		
		public static function resetIgnoredAnnotations() {
			self::$ignore = array();
		}
		
		public static function ignores($class) {
			return isset(self::$ignore[$class]);
		}
		
		public static function ignore() {
			foreach(func_get_args() as $class) {
				self::$ignore[$class] = true;
			}
		}

		public static function resolveClassName($class) {
			if(isset(self::$classnames[$class])) return self::$classnames[$class];
			$matching = array();
			foreach(self::getDeclaredAnnotations() as $declared) {
				if($declared == $class) {
					$matching[] = $declared;
				} else {
					$pos = strrpos($declared, "_$class");
					if($pos !== false && ($pos + strlen($class) == strlen($declared) - 1)) {
						$matching[] = $declared;
					}
				}
			}
			$result = null;
			switch(count($matching)) {
				case 0: $result = $class; break;
				case 1: $result = $matching[0]; break;
				default: trigger_error("Cannot resolve class name for '$class'. Possible matches: " . join(', ', $matching), E_USER_ERROR);
			}
			self::$classnames[$class] = $result;
			return $result;
		}

		private static function getDeclaredAnnotations() {
			if(!self::$annotations) {
				self::$annotations = array();
				foreach(get_declared_classes() as $class) {
					if(is_subclass_of($class, '\tapeet\addendum\Annotation') || $class == '\tapeet\addendum\Annotation') self::$annotations[] = $class;
				}
			}
			return self::$annotations;
		}

		
	}
