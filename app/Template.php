<?php

Class Template
{
	private $html;
	private $final;
	private $params;
	private $view;

	public function __construct($view, $params)
	{
		$this->params = $params;
		$this->view = $view;
		$this->final = '';
		$this->html = file_get_contents("app/views/" . $this->view . '.html');
		$this->addTemplate();
		$this->merge();
		$this->addInclude();
		$this->addIf();
		$this->addIfn();
		$this->addFor();
		$this->addValue();
		echo $this->final;
	}

	private function addInclude()
	{
		preg_match_all('/{#INCLUDE:(.*?)}/s', $this->final, $matchesFinal);
		foreach ($matchesFinal[1] as $k => $v)
		{
			$include = file_get_contents("app/views/" . $v . '.html');
			$this->final = preg_replace("/{#INCLUDE:" . preg_quote($v, '/') . "}/", $include, $this->final);
		}
	}

	private function merge()
	{
		preg_match_all('/{#(.*?)}(.*?){#END}/s', $this->html, $matchesHtml);
		preg_match_all('/{#(.*?)}/s', $this->final, $matchesFinal);
		foreach ($matchesHtml[1] as $k => $v)
			$this->final = preg_replace("/{#" . $v . "}/", $matchesHtml[2][$k], $this->final);
	}

	private function incrementIf($matches)
	{
		global $i;
		return '{%IF ' . $i++ . ' ';
	}

	private function incrementIfn($matches)
	{
		global $i;
		return '{%IFN ' . $i++ . ' ';
	}

	private function incrementFor($matches)
	{
		global $i;
		return '{*FOR ' . $i++ . ' ';
	}

	private function addIf()
	{
		$i = 0;
		$this->final = preg_replace_callback('/{\%IF /s', 'Template::incrementIf', $this->final);
		preg_match_all('/{\%IF (.*?) (.*?)}(.*?){\%END}/s', $this->final, $matchesIf);
		foreach ($matchesIf[2] as $k => $v) {
			$v_exp = explode(".", $v);
			$var = $this->params;
			foreach ($v_exp as $v2) {
				if (isset($var[$v2]))
					$var = $var[$v2];
				else
					$var = NULL;
			}
			if ($var == NULL)
				$this->final = preg_replace('/{\%IF ' . $matchesIf[1][$k] . ' ' . $v . '}(.*?){\%END}/s', '', $this->final);
			else
				$this->final = preg_replace('/{\%IF ' . $matchesIf[1][$k] . ' ' . $v . '}(.*?){\%END}/s', $matchesIf[3][$k], $this->final);
		}
	}

	private function addIfn()
	{
		$i = 0;
		$this->final = preg_replace_callback('/{\%IFN /s', 'Template::incrementIfn', $this->final);
		preg_match_all('/{\%IFN (.*?) (.*?)}(.*?){\%END}/s', $this->final, $matchesIf);
		foreach ($matchesIf[2] as $k => $v) {
			$v_exp = explode(".", $v);
			$var = $this->params;
			foreach ($v_exp as $v2) {
				if (isset($var[$v2]))
					$var = $var[$v2];
				else
					$var = NULL;
			}
			if ($var != NULL)
				$this->final = preg_replace('/{\%IFN ' . $matchesIf[1][$k] . ' ' . $v . '}(.*?){\%END}/s', '', $this->final);
			else
				$this->final = preg_replace('/{\%IFN ' . $matchesIf[1][$k] . ' ' . $v . '}(.*?){\%END}/s', $matchesIf[3][$k], $this->final);
		}
	}

	private function addFor()
	{
		$this->final = preg_replace_callback('/{\*FOR /s', 'Template::incrementFor', $this->final);
		preg_match_all('/{\*FOR (.*?) (.*?) AS (.*?)}(.*?){\*END}/s', $this->final, $matchesFor);
		foreach ($matchesFor[2] as $k => $v) {
			$htmlFor = "";
			$v_exp = explode(".", $v);
			$var = $this->params;
			foreach ($v_exp as $v2) {
				if (isset($var[$v2]))
					$var = $var[$v2];
				else
					$var = NULL;
			}
			if ($var !== NULL) {
				foreach ($var as $k3 => $v3)
				{
					if (is_array($v3))
					{
						$htmlFor .= $matchesFor[4][$k];
						foreach ($v3 as $k4 => $v4)
							$htmlFor = preg_replace('/{{' . $matchesFor[3][$k]. '.' .$k4 . '}}/', $v4, $htmlFor);
					}
					else
						$htmlFor .= preg_replace('/{{' . $matchesFor[3][$k] . '}}/', $v3, $matchesFor[4][$k]);
				}
				$this->final = preg_replace('/{\*FOR ' . $matchesFor[1][$k] . ' (.*?) AS (.*?)}(.*?){\*END}/s', $htmlFor, $this->final);
			}
			else
				$this->final = preg_replace('/{\*FOR ' . $matchesFor[1][$k] . ' (.*?) AS (.*?)}(.*?){\*END}/s', '', $this->final);
		}
	}

	private function addValue()
	{
		preg_match_all('/{{(.*?)}}/s', $this->final, $matches);
		foreach ($matches[1] as $k => $v) {
			$v_exp = explode(".", $v);
			$var = $this->params;
			foreach ($v_exp as $v2) {
				$var = (array)$var;
				if (isset($var[$v2]))
					$var = $var[$v2];
				else
					$var = NULL;
			}
			if ($var !== NULL)
				$this->final = preg_replace('/{{' . $matches[1][$k] . '}}/', $var, $this->final);
			else
				$this->final = preg_replace('/{{' . $matches[1][$k] . '}}/', '', $this->final);
		}
	}

	private function addTemplate()
	{
		preg_match_all('/{TEMPLATE}(.*?){END}/', $this->html, $matches);
		foreach ($matches[1] as $v)
			$this->final .= file_get_contents("app/views/" . $v . '.html');
	}

	public function getHtml()
	{
		return $this->html;
	}
}