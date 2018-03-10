<?php

/**
 * Adds a class to the array of classes to apply.
 *
 * NOTE: When calling this method do it this way:
 *
 *      return shq_genstrap_add_html_class();
 *
 * The return value is required by shq_genestrap_process_genesis_attr() to work properly.
 *
 * @param string $node This is the context of a genesis_structural_wrap_* or of a genesis_attr_*.
 * @param string $class
 *
 * @return array
 */
function shq_genestrap_add_html_class(string $node, string $class):array {
	// Assign the classes to a global variable so they can be retrieved by apply_classes()
	global $shq_genestrap_classes;

	if (null === $shq_genestrap_classes) {
		$shq_genestrap_classes = [];
	}

	if (
		// If the node isn't already set...
		false === isset($shq_genestrap_classes[$node])
		// If the class isn't already added to the list of the node...
		|| false === array_search($class, $shq_genestrap_classes)
	) {
		// Sanitize the class
		$class = sanitize_html_class($class);

		// If it is not empty after the sanitization...
		if (false === empty($class)) {
			// Adds it to the array
			$shq_genestrap_classes[ $node ][] = $class;
		}
	}

	return $shq_genestrap_classes;
}

/**
 * Helper method to apply multiple classes in one pass.
 *
 * @param string $node
 * @param array  $classes
 *
 * @return array
 */
function shq_genestrap_add_html_classes(string $node, array $classes):array {
	$return = [];
	foreach ($classes as $class) {
		$return = shq_genestrap_add_html_class($node, $class);
	}

	return $return;
}

/**
 * @param array $attr
 * @param string $context
 *
 * @return array
 */
function apply_classes($attr, $context):array {
	global $shq_genestrap_classes;

	$classes = '';
	if (isset($shq_genestrap_classes[$context])) {
		$classes = implode(' ', $shq_genestrap_classes[$context]);
	}

	$attr['class'] = $classes;

	return $attr;
}
