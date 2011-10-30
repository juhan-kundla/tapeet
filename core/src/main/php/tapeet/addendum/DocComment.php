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


	class DocComment {
		private static $classes = array();
		private static $methods = array();
		private static $fields = array();
		private static $parsedFiles = array();
	
		public static function clearCache() {
			self::$classes = array();
			self::$methods = array();
			self::$fields = array();
			self::$parsedFiles = array();
		}
		
		public function get($reflection) {
			if($reflection instanceof ReflectionClass) {
				return $this->forClass($reflection);
			} elseif($reflection instanceof ReflectionMethod) {
				return $this->forMethod($reflection);
			} elseif($reflection instanceof ReflectionProperty) {
				return $this->forProperty($reflection);
			}
		}
	
		public function forClass($reflection) {
			$this->process($reflection->getFileName());
			$name = $reflection->getName();
			return isset(self::$classes[$name]) ? self::$classes[$name] : false;
		}
		
		public function forMethod($reflection) {
			$this->process($reflection->getDeclaringClass()->getFileName());
			$class = $reflection->getDeclaringClass()->getName();
			$method = $reflection->getName();
			return isset(self::$methods[$class][$method]) ? self::$methods[$class][$method] : false;
		}
	
		public function forProperty($reflection) {
			$this->process($reflection->getDeclaringClass()->getFileName());
			$class = $reflection->getDeclaringClass()->getName();
			$field = $reflection->getName();
			return isset(self::$fields[$class][$field]) ? self::$fields[$class][$field] : false;
		}
		
		private function process($file) {
			if(!isset(self::$parsedFiles[$file])) {
				$this->parse($file);
				self::$parsedFiles[$file] = true;
			}
		}
		
		protected function parse($file) {
			$tokens = $this->getTokens($file);
			$currentClass = false;
			$currentBlock = false;
			$namespace = null;
			$max = count($tokens);
			$i = 0;
			while($i < $max) {
				$token = $tokens[$i];
				if(is_array($token)) {
					list($code, $value) = $token;
					switch($code) {
						case T_NAMESPACE:
							$namespace = $this->getString($tokens, $i, $max).'\\';
							break;
						
						case T_DOC_COMMENT: 
							$comment = $value; 
							break;
						
						case T_CLASS: 
						case T_INTERFACE:
							$class = $namespace.$this->getString($tokens, $i, $max);
							if($comment !== false) {
								self::$classes[$class] = $comment;
								$comment = false;
							}
							break;
							
						case T_VARIABLE: 
							if($comment !== false) {
								$field = substr($token[1], 1);
								self::$fields[$class][$field] = $comment;
								$comment = false;
							}
							break;
						
						case T_FUNCTION:
							if($comment !== false) {
								$function = $this->getString($tokens, $i, $max);
								self::$methods[$class][$function] = $comment;
								$comment = false;
							}
							
							break;
						
						// ignore
						case T_WHITESPACE: 
						case T_PUBLIC: 
						case T_PROTECTED: 
						case T_PRIVATE: 
						case T_ABSTRACT: 
						case T_FINAL: 
						case T_VAR: 
							break;
						
						default: 
							$comment = false;
							break;
					}
				} else {
					$comment = false;
				}
				$i++;
			}
		}
		
		private function getString($tokens, &$i, $max) {
			do {
				$token = $tokens[$i];
				$i++;
				if(is_array($token)) {
					if($token[0] == T_STRING) {
						return $token[1];
					}
				}
			} while($i <= $max);
			return false;
		}
		
		private function getTokens($file) {
			return token_get_all(file_get_contents($file));
		}
	}
?>