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
use \tapeet\addendum\parser\AnnotationsMatcher;


	class AnnotationsBuilder {
		private static $cache = array();
		
		public function build($targetReflection) {
			$data = $this->parse($targetReflection);
			$annotations = array();
			foreach($data as $class => $parameters) {
				foreach($parameters as $params) {
					$annotation = $this->instantiateAnnotation($class, $params, $targetReflection);
					if($annotation !== false) {
						$annotations[get_class($annotation)][] = $annotation;
					}
				}
			}
			return new AnnotationsCollection($annotations);
		}

		public function instantiateAnnotation($class, $parameters, $targetReflection = false) {
			$class = Addendum::resolveClassName($class);
			if(is_subclass_of($class, '\tapeet\addendum\Annotation') && !Addendum::ignores($class) || $class == '\tapeet\addendum\Annotation') {
				$annotationReflection = new ReflectionClass($class);
				return $annotationReflection->newInstance($parameters, $targetReflection);
			}
			return false;
		}
		
		private function parse($reflection) {
			$key = $this->createName($reflection);
			if(!isset(self::$cache[$key])) {
				$parser = new AnnotationsMatcher;
				$parser->matches($this->getDocComment($reflection), $data);
				self::$cache[$key] = $data;
			}
			return self::$cache[$key];
		}
		
		private function createName($target) {
			if($target instanceof ReflectionMethod) {
				return $target->getDeclaringClass()->getName().'::'.$target->getName();
			} elseif($target instanceof ReflectionProperty) {
				return $target->getDeclaringClass()->getName().'::$'.$target->getName();
			} else {
				return $target->getName();
			}
		}
		
		protected function getDocComment($reflection) {
			return Addendum::getDocComment($reflection);
		}
		
		public static function clearCache() {
			self::$cache = array();
		}
	}
