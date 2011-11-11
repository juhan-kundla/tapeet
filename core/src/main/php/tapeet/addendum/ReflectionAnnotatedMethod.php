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


use \ReflectionMethod;


class ReflectionAnnotatedMethod extends ReflectionMethod {
		private $annotations;
		
		public function __construct($class, $name) {
			parent::__construct($class, $name);
			$this->annotations = $this->createAnnotationBuilder()->build($this);
		}
		
		public function hasAnnotation($class) {
			return $this->annotations->hasAnnotation($class);
		}
		
		public function getAnnotation($annotation) {
			return $this->annotations->getAnnotation($annotation);
		}
		
		public function getAnnotations() {
			return $this->annotations->getAnnotations();
		}
		
		public function getAllAnnotations($restriction = false) {
			return $this->annotations->getAllAnnotations($restriction);
		}
		
		public function getDeclaringClass() {
			$class = parent::getDeclaringClass();
			return new ReflectionAnnotatedClass($class->getName());
		}
		
		protected function createAnnotationBuilder() {
			return new AnnotationsBuilder();
		}
	}