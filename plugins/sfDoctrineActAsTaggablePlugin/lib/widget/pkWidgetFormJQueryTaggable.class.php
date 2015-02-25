<?php

class pkWidgetFormJQueryTaggable extends sfWidgetFormInput
{
	var $script_options = array(
			'typeahead-url' => null,
			'tags-label' => null,
			'popular-tags' => null,
			'all-tags' => null,
			'commit-selector' => null,
			'commit-event' => null,
			'popular-tags-label' => null,
			'add-link-class' => null,
			'remove-link-class' => null
		);
	
	protected function configure($options = array(), $attributes = array())
	{
		$jq_path = '/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js';
		sfContext::getInstance()->getResponse()->addJavascript($jq_path, 'first');
		
		foreach ($this->script_options as $key => $value)
		{
			$this->addOption($key, $value);
		}
		parent::configure($options, $attributes);
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array())
	{	
		$render_options = array();
		foreach($this->script_options as $key => $value)
		{
			if ($this->options[$key])
				$render_options[$key] = $this->options[$key];
		}
		
		if (isset($this->options['default']))
		{
			$value = $this->options['default'];
		}
		
		$attributes['id'] = $this->generateId($name);
		$html = parent::render($name, $value, $attributes, $errors);
		
		$html .= "<script type='text/javascript'>$(document).ready(function() { pkInlineTaggableWidget('#" . $attributes['id'] . "', " . json_encode($render_options) . "); } );</script>";
		
		return $html;
	}
}
?>
