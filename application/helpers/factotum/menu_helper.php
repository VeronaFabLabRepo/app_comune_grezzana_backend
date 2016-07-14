<?php

function menu(array $pages, array $options = array())
{
	// Defaults
	$level    = 0;
	$maxdepth = null;
	$logged   = false;

	extract($options, EXTR_SKIP);

	if ($level > $maxdepth || count($pages) === 0) {
		return '';
	}

	$html = '';
	$html .= '<ul>' . "\n";

	foreach ($pages as $page) {
		if (isset($page['only_logged_user']) && $page['only_logged_user'] && !$logged) {
			continue;
		}

		$classes = array();

		$operation = isset($page['operation']) ? $page['operation'] : null;
		if ($operation === 'link') {
			$href   = $page['link'];
			$target = $page['link_open_in'];
			$title  = $page['link_title'];
		} else {
			$href   = '/' . $page['absolute_path'];
			$target = null;
			$title  = null;
		}

		$target  = ($target ? ' target="' . $target . '"' : '');
		$title   = ($title ? ' title="' . $title . '"' : '');
		$classes[] = (isset($page['active']) && $page['active'] ? 'active' : null);
		$classes[] = (isset($page['current']) && $page['current'] ? 'current' : null);


		$classes = implode(' ', array_filter($classes));
		$classes = ($classes ? ' class="' . $classes . '"' : '');
		$html .= "\t" . '<li>' . "\n"
			   . "\t\t" . '<a' . $classes . ' href="' . $href . '"' . $target . $title . '>' . $page['title'] . '</a>' . "\n";

		if (isset($page['children']) && is_array($page['children'])) {
			$html .= menu($page['children'], array_merge($options, array(
				'level' => $level + 1
			)));
		}

		$html .= "\t" . '</li>' . "\n";
	}

	$html .= '</ul>' . "\n";

	return $html;
}
