<?php

use \Phalcon\Utils\Timeformat as Timeformat;

class Post extends ModelBase
{

	public $id;

	public $slug;

	public $subject;

	public $type;

	public $short_text;

	public $full_text;

	public $created_at;

	public $modified_in;

	public $is_draft;

	public $is_comment;

	// Meta-tag

	public $meta_description;

	public $meta_keywords;


	public function initialize()
	{
		// Не записываем при редактировании сюда
		$this->skipAttributesOnUpdate(array('created_at'));

		// Не записываем при создании сюда
		$this->skipAttributesOnCreate(array('modified_in'));

	}

	// После того как выбрали данные из базы
	public function afterFetch()
	{	
		// Дата атомного формата
		$this->created_format = date(DATE_ATOM, $this->created_at);

		// Дата в приятном формате
		if ($this->created_at)
			$this->created_at = Timeformat::generate($this->created_at);

		if ($this->modified_in)
			$this->modified_in = Timeformat::generate($this->modified_in);
	}

	public function getContent($type = "short")
	{
		if ($type == "short") {
			return $this->short_text;
		}
		elseif ($type == "full") {
			$text = $this->short_text;
			if ( isset($this->full_text) )
				$text .= '<hr id="readmore">' . $this->full_text;

			return $text;
		}
	}

	public function getDate()
	{
		return '<time datetime="' . $this->created_format . '" itemprop="datePublished">' . $this->created_at . '</time>';
	}
}
